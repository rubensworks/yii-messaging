<?php

/**
 * Messaging extensions
 */

/**
 * Messaging module class. Based on the Yii private-messaging extension: http://www.yiiframework.com/extension/private-messaging
 *
 * @author Ruben Taelman
 */
class MessagingModule extends CWebModule
{
	public $userModel;

        public $user;

	public $dateFormat = 'Y-m-d H:i:s';

	public function init()
	{
            //$this->user=User::model()->findByPk(3);
            $this->user=Yii::app()->user;
            
            $this->defaultController='message';

            if (!$this->userModel)
                throw new Exception(MessagingModule::t("Property Messaging::{userModel} not defined", array('{userModel}' => $this->userModel)));
            if (!class_exists($this->userModel))
                throw new Exception(MessagingModule::t("Class {userModel} not defined", array('{userModel}' => $this->userModel)));

            $this->setImport(array(
                    'messaging.models.*',
                    'messaging.components.*',
                    'messaging.controllers.*',
                    'messaging.views.*',
            ));

            Yii::log("MessagingModule succesfully loaded.", "info", "MessagingModule");
            
            parent::init();
	}

	public function beforeControllerAction($controller, $action)
	{
            //TODO
            /*if (Yii::app()->user->isGuest) {
                    if (Yii::app()->user->loginUrl) {
                            $controller->redirect($controller->createUrl(reset(Yii::app()->user->loginUrl)));
                    } else {
                            $controller->redirect($controller->createUrl('/'));
                    }
            } else if (parent::beforeControllerAction($controller, $action)) {
                    // this method is called before any module controller action is performed
                    // you may place customized code here
                    return true;
            } else {
                    return false;
            }*/
            return parent::beforeControllerAction($controller,$action);
	}

	public static function t($str='',$params=array(),$dic='message') {
		return Yii::t("MessageModule.".$dic, $str, $params);
	}

        /**
         * Returns an array of groups with index the group id, and as value another array that contains all of those group members (excluding yourself)
         * @return type
         */
        public function openedGroups() {
            $criteria = new CDbCriteria;
            $criteria->select="t2.user, t2.grp";
            $criteria->condition="t.user = :user AND t2.user <> :user";
            $criteria->params=array(":user"=>$this->user->id);
            $criteria->join='JOIN '.Group::model()->tableName().' t2 ON t.grp=t2.grp';
            $groups=Group::model()->findAll($criteria);
            $usergroups=array();
            foreach($groups as $group) {
                $usergroups[$group->grp][]=$group->user;
            }
            return $usergroups;
        }
        
        /**
         * Check if this user is in this group
         */
        public function hasGroup($grp) {
            $criteria = new CDbCriteria;
            $criteria->condition="user = :user AND grp = :grp";
            $criteria->params=array(":user"=>$this->user->id,":grp"=>$grp);
            $group=Group::model()->find($criteria);
            return $group!=NULL;
        }

        /**
         * Check if a group with exactly those members (and myself) exists in the given usergroups from a user (result from openedGroups())
         * Returns the id of the common group or false if nothing was found
         * @param type $ids
         * @return integer
         */
        public function groupExists($ids) {
            array_multisort($ids);
            $usergroups=$this->openedGroups();
            if(!$usergroups || !$ids)
                return false;
            foreach($usergroups as $groupid=>$group) {
                array_multisort($group);
                if($ids==$group)
                    return $groupid;
            }
            return false;
        }

        /**
         * Opens a group for yourself and a set of other users (the group could've been previously made), and returns the id of that group or false if an error has occured
         * @param type $ids
         */
        public function openGroup($ids) {
            if(in_array($this->user->id,$ids))//You can not open a chat with yourself!
                throw new Exception(MessagingModule::t("You can't open a group with yourself.", array()));
            if(empty($ids) || !$ids)
                return null;
            $group=$this->groupExists($ids);

            if(!$group) {
                //Only one lock will be needed, because after writing one member from the group with a group id, no other new group can use this group-id because findLastGroup will then show another Id.
                $firstGroup=new Group();
                $transaction=$firstGroup->dbConnection->beginTransaction();
                Yii::app()->db->createCommand("LOCK TABLES ".Group::model()->tableName()." WRITE")->execute();
                try
                {
                    $firstGroup->grp=$this->findLastGroup()+1;
                    $firstGroup->user=$this->user->id;
                    $firstGroup->save();
                    $transaction->commit();
                }
                catch(Exception $e)
                {
                    $transaction->rollback();
                    Yii::app()->db->createCommand("UNLOCK TABLES")->execute();
                    return false;
                }
                Yii::app()->db->createCommand("UNLOCK TABLES")->execute();

                foreach($ids as $id) {
                    $this->addToGroup($firstGroup->grp, $id);
                }

                return $firstGroup->grp;
            }
            else return $group;
        }

        /**
         * Finds the last used group
         * @return type
         */
        private function findLastGroup() {
            return Yii::app()->db->createCommand("SELECT MAX(`grp`) as `max` FROM `".Group::model()->tableName()."` WHERE 1")->queryScalar();
        }

        /**
         * Adds a user to a group
         * @param type $group   group id
         * @param type $id      user id
         */
        private function addToGroup($group, $id) {
            $newGroup=new Group();
            $newGroup->grp=$group;
            $newGroup->user=$id;
            $newGroup->save();
        }

        /**
         * Returns true if the user has unread messages
         */
        public function isUnread() {
            $updated=MessagesUpdated::model()->findByPk($this->user->id);
            return $updated!=null?$updated->updated:false;
        }
        
        /**
         * Sets the updated field for a user
         * @param type $user
         * @param type $unread (default: sets to unread)
         */
        public function setUnread($user, $unread=true) {
            $updated=MessagesUpdated::model()->findByPk($user);
            if(!$updated) {
                $updated=new MessagesUpdated();
                $updated->user=$user;
            }
            $updated->updated=$unread?1:0;
            $updated->save();
        }
        
        /**
         * Returns true if the user has unread messages in a certain group
         */
        public function isUnreadGroup($group) {
            $criteria = new CDbCriteria;
            $criteria->condition="grp=:group AND user=:user";
            $criteria->params=array(":group"=>$group, ":user"=>$this->user->id);
            $updated=MessagesUpdatedGroup::model()->find($criteria);
            return $updated!=null?$updated->updated:false;
        }
        
        /**
         * Returns all group id's (array) that contain unread messages.
         */
        public function getUnreadGroups() {
            $criteria = new CDbCriteria;
            $criteria->condition="user=:user";
            $criteria->params=array(":user"=>$this->user->id);
            $groups=MessagesUpdatedGroup::model()->findAll($criteria);
            $ids=array();
            foreach($groups as $group) {
                $ids[]=$group->grp;
            }
            return $ids;
        }
        
        /**
         * Sets the updated field for a user in a group
         * @param type $user
         * @param type $group
         * @param type $unread (default: sets to unread)
         */
        public function setUnreadGroup($user, $group, $unread=true) {
            $criteria = new CDbCriteria;
            $criteria->condition="grp=:group AND user=:user";
            $criteria->params=array(":group"=>$group, ":user"=>$user);
            $updated=MessagesUpdatedGroup::model()->find($criteria);
            if(!$updated) {
                $updated=new MessagesUpdatedGroup();
                $updated->user=$user;
                $updated->grp=$group;
            }
            $updated->updated=$unread?1:0;
            $updated->save();
        }

        /**
         * Sends a message to a group and notifies all those users
         * @param type $group   group id of users
         * @param type $content message to be sent
         */
        public function sendMessage($group, $content) {
            if(!$this->hasGroup($group))
                throw new Exception(MessagingModule::t("User is not present in this group.", array()));
            $message=new Message();
            $message->grp=$group;
            $message->content=$content;
            $message->save();
            //SET UNREAD ON GROUP MEMBERS (EXCEPT FOR SENDER)
            $criteria = new CDbCriteria;
            $criteria->condition="grp = :group and user <> :user";
            $criteria->params=array(":group"=>$group, ":user"=>$this->user->id);
            $groupUsers=Group::model()->findall($criteria);
            foreach($groupUsers as $user) {
                $this->setUnread($user->user);
                $this->setUnreadGroup($user->user,$group);
            }
        }
        
        /**
         * Gets your messages by group
         * @param type $group 
         */
        public function getMessages($group) {
            if(!$this->hasGroup($group))
                throw new Exception(MessagingModule::t("User is not present in this group.", array()));
            $criteria = new CDbCriteria;
            $criteria->condition="grp = :group";
            $criteria->params=array(":group"=>$group);
            $criteria->order="created DESC";
            $messages=Message::model()->findAll($criteria);
            return $messages;
        }
        
        /**
         * Updates unread status of a group
         * @param type $group 
         */
        public function readGroup($group) {
            $this->setUnreadGroup($this->user->id,$group,false);
            if(sizeof($this->getUnreadGroups())==0)
                $this-setUnread($this->user->id,false);
        }

}
