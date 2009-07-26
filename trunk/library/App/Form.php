<?php
/**
* Application Forms Class
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
*/
class App_Form extends Zend_Form {

    /**
    * Contructor
    *
    * @return Zend_Form object
    */
    public function __construct($options = null)
    {
        parent::__construct($options);
    }
}