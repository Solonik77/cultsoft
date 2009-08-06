<?php
/**
* Website Language translation and locale configuration
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
*/
class App_I18n {
    // System localization
    protected $locale;
    // Language tranlator
    protected $translator;
    
    /**
    * Contructor
    */
    public function __construct()
    {

    }
    
    public function setLocale(Zend_Locale $object)
    {
        $this->locale = $object;        
    }
    
    public function getLocale()
    {
     return $this->locale;
    }
    
        /**
    * Set translator object
    */
    public function setTranslator(Zend_Translate $object)
    {
        $this->translator = $object;
        Zend_Validate_Abstract::setDefaultTranslator($this->translator);
        Zend_Form::setDefaultTranslator($this->translator);
        Zend_Form::setDefaultTranslator($this->translator);
        Zend_Controller_Router_Route::setDefaultTranslator($this->translator);
    }
    
    /*
    * Return Zend translator object
    */
    public function getTranslator()
    {
     return $this->translator;
    }
}