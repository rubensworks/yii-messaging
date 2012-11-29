<?php

/**
 * This is the model class for table "test_messages_updated_group".
 *
 * The followings are the available columns in table 'test_messages_updated_group':
 * @property integer $id
 * @property integer $user
 * @property integer $grp
 * @property integer $updated
 */
class MessagesUpdatedGroup extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'test_messages_updated_group';
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
                        'id' => Yii::t('form', 'ID: '),
                        'user' => Yii::t('form', 'User: '),
                        'grp' => Yii::t('form', 'Group: '),
			'updated' => Yii::t('form', 'Updated: '),
		);
	}
}