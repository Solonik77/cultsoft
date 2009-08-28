<?php

class Users extends CActiveRecord {
    /**
    * The followings are the available columns in table 'Users':
    *
    * @var integer $id
    * @var string $login
    * @var string $password
    * @var string $email
    * @var string $role
    * @var string $first_name
    * @var string $last_name
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
        return 'Users';
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
            array('login, email, first_name, last_name', 'length', 'max' => 255),
            array('email', 'email'),
            array('password', 'length', 'min' => 4, 'max' => 32),
            array('login, password, email, role, first_name, last_name', 'required'),
            array('role', 'length', 'max' => 50),
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
        'static_pages'=>array(self::HAS_MANY, 'Static_Pages', 'user_id'),
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
            'login' => 'Логин',
            'password' => 'Пароль',
            'email' => 'Email',
            'role' => 'Роль',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            );
    }
}