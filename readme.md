Messaging
===========

A Yii plugin that allows you to easily create a messaging/chat functionality
into your existing User-based application.
The base controller, widget and view are not included, they aren't sufficiently
tested. These might get updated sometime, or not, we'll see...

Installation
------------

You'll first need to install the MySQL tables included in the `data` folder.
Unpack to `protected/extensions/`. Add the following to your `protected/config/main.php`:

~~~
<?php
...
return array(
	...
	'modules'=>array(
                ...
		'messaging'=>array(
				'class'=>'ext.messaging.MessagingModule',
				'userModel'=>'User',
			),
		),
                ...
);
...
?>
~~~
The only requirement for the User model is that is has a valid 'id' field that
is in fact unique. `Yii::app()->user` must also hold the current user model.

Example code
------------
~~~
<?php
...
//makes a new group based on an array of user id's (or opens an existing one)
$newgroup=Yii::app()->getModule('messaging')->openGroup(array(1,2));

//check if you have unread messages
Yii::app()->getModule('messaging')->isUnread();
//check if you have unread messages in a group
Yii::app()->getModule('messaging')->isUnreadGroup($newgroup);

//send a new message to the group
Yii::app()->getModule('messaging')->sendMessage($newgroup,"test");
//check this groups messages as 'read'
Yii::app()->getModule('messaging')->readGroup($newgroup);

//get all your unread groups
$unreadgroups=Yii::app()->getModule('messaging')->getUnreadGroups();
//get all your groups
$allgroups=Yii::app()->getModule('messaging')->openedGroups();

//easy to loop over the groups and messages
foreach($allgroups as $group) {

    //gets the messages of a group
    $messages=Yii::app()->getModule('messaging')->getMessages($group);
    foreach($messages as $message) {
        echo $message->grp.":".$message->content.Yii::app()->getModule('messaging')->isUnreadGroup($group)?" [Unread] ":"[_]"."<br />";
    }
}
...
?>
~~~