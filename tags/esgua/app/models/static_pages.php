<?php

class static_pages extends CActiveRecord {
    /**
    * The followings are the available columns in table 'static_pages':
    *
    * @var integer $id
    * @var string $fancy_url
    * @var integer $user_id
    */

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
        return 'static_pages';
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
            array('user_id', 'numerical', 'integerOnly' => true),
            array('fancy_url', 'length', 'max' => 100),
            array('date_created, date_updated', 'safe'),
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
            'static_pages_content' => array(self::HAS_MANY, 'static_pages_Content', 'page_id'),
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
            );
    }
}
