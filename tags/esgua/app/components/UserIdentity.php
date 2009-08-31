<?php

class UserIdentity extends CUserIdentity {
    protected $_id;

    public function authenticate()
    {
        $user = Users::model()->find('LOWER(login)=?', array(strtolower($this->username)));
        if (($user === null) or ($this->password !== $user->password)) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else {
            $this->_id = $user->id;
            $this->username = $user->first_name . ' ' . $user->last_name;
            Yii::app()->user->setState('role', $user->role);
            if($user->role == 'admin'){
                $_SESSION['admin_authorized'] = TRUE;
            }
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->_id;
    }
}