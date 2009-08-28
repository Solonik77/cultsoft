<?php

class Static_Pages_Content extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'Static_Pages_Content':
	 * @var integer $id
	 * @var string $title
	 * @var string $content
	 * @var integer $page_id
	 */

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
		return 'Static_Pages_Content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content', 'required'),
			array('page_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('content', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
        return array(
        'static_pages' => array(self::BELONGS_TO, 'Static_Pages', 'id'),
        );

	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'title' => 'Заголовок',
			'content' => 'Содержание',
			'page_id' => 'Page',
		);
	}
}