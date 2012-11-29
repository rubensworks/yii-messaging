<?php

/**
 * This is the model class for table "test_group".
 *
 * The followings are the available columns in table 'test_group':
 * @property integer $id
 * @property integer $grp
 * @property integer $user
 */
class Group extends CActiveRecord
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
		return 'test_group';
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('form', 'ID: '),
			'group' => Yii::t('form', 'Group: '),
			'user' => Yii::t('form', 'User: '),
		);
	}
}