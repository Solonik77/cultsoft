<?php

class Files_for_content_description extends CActiveRecord {
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'files_for_content_description';
    }

    /**
    *
    * @return array validation rules for model attributes.
    */
    public function rules()
    {
        return array(
            array('file_id', 'numerical', 'integerOnly' => true),
            array('file_name', 'length', 'max' => 255),
            array('description', 'safe'),           
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

            );
    }

    /**
    *
    * @return array customized attribute labels (name=>label)
    */
    public function attributeLabels()
    {
        return array(
            'id' => 'Id',
            'file_name' => 'Ќазвание файла',
            'description' => 'ќписание',
			'lang' => 'язык',
            );
    }
}