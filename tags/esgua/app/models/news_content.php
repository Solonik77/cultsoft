<?php

class news_content extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'news_content':
	 * @var integer $id
	 * @var string $title
	 * @var string $content
	 * @var integer $news_id
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
		return 'news_content';
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
			array('news_id', 'numerical', 'integerOnly'=>true),
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
        'news' => array(self::BELONGS_TO, 'news', 'id'),
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
			'news_id' => 'Page',
		);
	}
}