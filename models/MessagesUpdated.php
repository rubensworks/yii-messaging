<?php

/**
 * This is the model class for table "test_messages_updated".
 *
 * The followings are the available columns in table 'test_messages_updated':
 * @property integer $user
 * @property integer $updated
 */
class MessagesUpdated extends CActiveRecord
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
		return 'test_messages_updated';
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
                        'user' => Yii::t('form', 'User: '),
			'updated' => Yii::t('form', 'Updated: '),
		);
	}
}