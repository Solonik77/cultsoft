<?php
/**
* Setting website language and locale settings for request
*
* @package Core
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
class App_Controller_Plugin_Language extends Zend_Controller_Plugin_Abstract {
    /**
    * Constructor
    */
    public function __construct()
    {
    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $system_locales = App::config()->locales->toArray();
        foreach($system_locales as $key => $value) {
            $default_lang_key = $key;
            Zend_Locale::setDefault($default_lang_key);
            break;
        }
        
        $language_id = intval(App_Member::getInstance()->getField('language_id'));        
        
        $request_lang = $request->getParam('requestLang');
        $system_lang = (array_key_exists($request_lang, $system_locales)) ? $request_lang : $default_lang_key;
        Zend_Translate::setCache(App_Cache::getInstance('File'));
        $translate = new Zend_Translate('csv', APPLICATION_PATH . 'i18n/', $system_lang, array('scan' => Zend_Translate::LOCALE_FILENAME, 'disableNotices' => true));
        App::setTranslate($translate);
        setlocale(LC_ALL, $system_locales [$system_lang] . '.UTF-8');
        Zend_Form::setDefaultTranslator($translate);
        App::Front()->setParam('requestLang', $system_lang);
    }
}
