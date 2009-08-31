<?php

class files_for_content extends CActiveRecord {
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'files_for_content';
    }

    /**
    *
    * @return array validation rules for model attributes.
    */
    public function rules()
    {
        return array(
            array('user_id, element_id', 'numerical', 'integerOnly' => true),
            array('file_type', 'length', 'max' => 255),
            array('date_attach', 'safe'),
            array('date_attach', 'application.extensions.validators.ValidDate'),
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
            'files_for_content_description' => array(self::HAS_MANY, 'files_for_content_description', 'file_id'),
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
            'file_url' => 'Путь к файлу на сервере',
            'user_id' => 'Добавил',
            'date_attach' => 'Дата прикрипления',
            );
    }
}