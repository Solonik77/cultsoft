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
		$languages = App::config()->languages->toArray();
		$default_site_language_id = $languages['default_id'];
		$default_site_locale = $languages['locale'][$default_site_language_id];
		$default_language_identificator = $languages['identificator'][$default_site_language_id];

		$member_language_id = intval(App_Member::getInstance()->getField('language_id'));
		$request_lang = $request->getParam('requestLang');

		if (in_array($request_lang, $languages['identificator'])) {
			$system_lang = $request_lang;
			foreach($languages['identificator'] as $key => $value) {
				if ($value == $system_lang) {
					$site_language_id = $key;
				}
			}
		} else {
			$system_lang = $default_language_identificator;
			$site_language_id = $default_site_language_id;
		}
		Zend_Translate::setCache(App_Cache::getInstance('File'));
		$translate = new Zend_Translate('csv', APPLICATION_PATH . 'i18n/', $system_lang, array('scan' => Zend_Translate::LOCALE_FILENAME, 'disableNotices' => true));
		App::setTranslate($translate);
		Zend_Locale::setDefault($system_lang);
		Zend_Controller_Router_Route::setDefaultLocale($system_lang);
		Zend_Form::setDefaultTranslator($translate);
		Zend_Controller_Router_Route::setDefaultTranslator($translate);
		App::front()->setParam('requestLang', $system_lang);
		App::front()->setParam('requestLangId', $site_language_id);
	}
}
