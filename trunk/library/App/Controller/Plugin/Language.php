<?php
/**
* Setting website language and locale settings for request
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
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
        $languages = App::I18N()->getSiteLanguages();
        $request_lang_id = 1;
        $request_lang = 'en';
        $site_locale = 'en_US';
        foreach($languages as $lang) {
            if ($lang['is_active'] and $lang['is_default'] > 0) {
                $request_lang_id = $lang['id'];
                $request_lang = $lang['request_lang'];
                $site_locale = $lang['locale'];
                break;
            }
        }
        $member_language_id = intval(App_Member::getInstance()->getField('language_id'));
        $query_srting_request_lang = $request->getParam('requestLang');
        foreach($languages as $lang) {
            if ($lang['is_active'] and $lang['request_lang'] == $query_srting_request_lang) {
                $request_lang_id = $lang['id'];
                $request_lang = $lang['request_lang'];
                $site_locale = $lang['locale'];
                break;
            }
        }

        Zend_Translate::setCache(App_Cache::getInstance('File'));
        $translate = new Zend_Translate('csv', APPLICATION_PATH . 'i18n/', $site_locale, array('scan' => Zend_Translate::LOCALE_FILENAME , 'disableNotices' => true));
        App::i18n()->setTranslator($translate);
        Zend_Locale::setDefault($site_locale);
        App::i18n()->setLocale(new Zend_Locale($request_lang));
        App::front()->setParam('requestLang', $request_lang);
        App::front()->setParam('requestLangId', $request_lang_id);
    }
}