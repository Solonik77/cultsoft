<?php

class Team_content extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
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
		return 'team_content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, post', 'required'),
			array('team_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('content', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
        return array(
        'team' => array(self::BELONGS_TO, 'team', 'id'),
        );

	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'name' => 'ФИО',
			'post' => 'Должность',
			'team_id' => 'Человек',
		);
	}
}