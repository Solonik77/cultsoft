<?php

class sitetree extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public function behaviors(){
        return array(
            'TreeBehavior' => array(
                'class' => 'application.extensions.nestedset.TreeBehavior'
            )
        );
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lft, rgt, level', 'required'),
			array('lft, rgt, level, page_id', 'numerical', 'integerOnly'=>true),
			array('element_type', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
        return array(

        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'lft' => 'Lft',
			'rgt' => 'Rgt',
			'level' => 'Level',
			'page_id' => 'Page',
			'element_type' => 'Element Type',
		);
	}
}