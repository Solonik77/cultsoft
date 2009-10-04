<?php
/**
 * Website Language translation and locale configuration
 *
 * @author Denysenko Dmytro
 */
class App_I18n {
    protected $_request;
    // System localization
    protected $locale;
    // Language tranlator
    protected $translator;
    // Website languages
    protected $site_languages = array();
    protected $default_site_language = array();
    protected $default_site_language_id = array();

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
        Zend_Controller_Router_Route::setDefaultTranslator($this->translator);
    }

    /**
     * Return Zend translator object
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Website languages
     */
    public function getSiteLanguages()
    {
        $site_languages = $this->site_languages;
        if (count($site_languages) > 0) {
            return $site_languages;
        }
        $cache = App_Cache::getInstance('permCache');
        if (! $site_languages = $cache->load('website_languages')) {
            $site_languages = new Main_Model_DbTable_Site_Languages();
            $site_languages = $site_languages->fetchAll()->getCollection()->toArray();
            if (count($site_languages) > 0) {
                $cache->save($site_languages);
            }
        }
        $data = array();
        foreach($site_languages as $key => $value) {
            $data[$value['id']] = $value;
            if ($value['is_active'] and $value['is_default']) {
                $this->default_site_language = $value;
                $this->default_site_language_id = $value['id'];
            }
        }
        $this->site_languages = $data;

        return $this->site_languages;
    }

    /**
     * Get default language
     */
    public function getDefaultSiteLanguage()
    {
        if ($this->default_site_language == null) {
            $this->getSiteLanguages();
        }
        return $this->default_site_language;
    }

    /**
     * Get default language ID
     */
    public function getDefaultSiteLanguageId()
    {
        if ($this->default_site_language_id == null) {
            $this->getSiteLanguages();
        }
        return $this->default_site_language_id;
    }

    /**
     * Get languages allowed for module
     *
     * @return array
     */
    public function getModuleLanguages($module = null)
    {
        $module = ($module) ? $module : App::front()->getRequest()->getModuleName();
        $siteLanguages = $this->getSiteLanguages();
        $model = new Main_Model_Settings;
        $config = current($model->getSettings("module = '" . $module . "' AND setting_key = 'allowed_languages'")->toArray());
        $config = explode(',', $config['setting_value']);

        $result = array();
        foreach($siteLanguages as $lang) {
            if (in_array($lang['request_lang'], $config)) {
                $result[$lang['id']] = $lang;
            }
        }
        return $result;
    }
}