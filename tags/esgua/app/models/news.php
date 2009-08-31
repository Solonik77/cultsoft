<?php

class news extends CActiveRecord {


    /**
    * Returns the static model of the specified AR class.
    *
    * @return CActiveRecord the static model class
    */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
    *
    * @return string the associated database table name
    */
    public function tableName()
    {
        return 'news';
    }

    /**
    *
    * @return array validation rules for model attributes.
    */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('is_published, user_id', 'numerical', 'integerOnly' => true),
            array('fancy_url', 'length', 'max' => 100),
            array('date_created, date_updated, date_publish', 'safe'),
            array('date_created, date_updated, date_publish', 'application.extensions.validators.ValidDate'),
            );
    }

    /**
    *
    * @return array relational rules.
    */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'news_content' => array(self::HAS_MANY, 'news_content', 'news_id'),
            'users' => array(self::BELONGS_TO, 'Users', 'user_id'),
            );
    }

    /**
    *
    * @return array customized attribute labels (name=>label)
    */
    public function attributeLabels()
    {
        return array('id' => 'Id',
            'fancy_url' => 'Псевдоним для URL',
            'user_id' => 'Пользователь',
            'date_created' => 'Дата создания',
			'date_updated' => 'Последнее изменение',
			'date_publish' => 'Дата публикации',
			'is_published' => 'Статус публикации',
            );
    }
}