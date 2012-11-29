<?php

/**
 * This is the model class for table "test_message".
 *
 * The followings are the available columns in table 'test_message':
 * @property int $id
 * @property int $grp
 * @property text $content
 * @property date $created
 */
class Message extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Games the static model class
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
		return 'test_message';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('content', 'required'),
			array('content', 'length', 'max'=>512),
                        array('created','default',
                            'value'=>time(),
                            'setOnEmpty'=>false,'on'=>'insert')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('Message','id'),
			'content' => Yii::t('Message','content'),
			'read' => Yii::t('Message','read'),
		);
	}
}