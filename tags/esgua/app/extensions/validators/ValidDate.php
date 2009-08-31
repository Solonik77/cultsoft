<?php

class ValidDate extends CValidator
{
    public $allowEmpty = true;
	
    protected function validateAttribute($object, $attribute)
	{
		$value = $object->$attribute;
		if($this->allowEmpty && ($value===null || $value===''))
        {
			return;
        }

		if(is_string($value)) {
        $valid = strtotime($value);
		if(!$valid)
		{
			$message = ($this->message !== null) ? $this->message : Yii::t('yii','{attribute} is not a valid date.');
			$this->addError($object,$attribute,$message);
		}
       }
	}
}