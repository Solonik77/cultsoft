<?php

class Team extends CActiveRecord {
    public $photo = TRUE;

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
        return 'team';
    }

    /**
    *
    * @return array validation rules for model attributes.
    */
    public function rules()
    {
        return array(
           // array('photo', 'file', 'types'=>'jpg, gif, png'),
            array('is_show, user_id', 'numerical', 'integerOnly' => true),
            array('fancy_url', 'length', 'max' => 100),
            array('date_created, date_updated', 'safe'),
            array('date_created, date_updated', 'application.extensions.validators.ValidDate'),
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
            'team_content' => array(self::HAS_MANY, 'team_content', 'team_id'),
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
            'name' => 'ФИО',
            'photo' => 'Фото',
            'user_id' => 'Добавил',
            'date_created' => 'Дата создания',
			'date_updated' => 'Последнее изменение',
			'is_show' => 'На сайте',
            );
    }
}
