<?php
/**
* * Zend_Controller_Request_Abstract
*/
require_once 'Zend/Controller/Request/Abstract.php';
/**
* * Zend_Uri
*/
require_once 'Zend/Uri.php';
class App_Controller_Request_Http extends Zend_Controller_Request_Http {
    public function __construct($uri = null)
    {
        parent::__construct($uri);
    }

    public function getI18N($langId = null)
    {
        $post = $this->getPost();
        $siteLanguages = App::siteLanguages();
        $i18n = array();
        foreach($post as $key => $value) {
            if (substr($key, 0, 7) == 'langid_') {
                $i18n[substr($key, 7, 1)][substr($key, 9)] = $value;
                $i18n[substr($key, 7, 1)]['lang_id'] = (int) substr($key, 7, 1);
                $i18n[substr($key, 7, 1)]['lang_identificator'] = (string) $siteLanguages[substr($key, 7, 1)]['language_identificator'];
            }
        }
        if (null === $langId) {
            return $i18n;
        } else {
            $langId = intval($langId);
            return (isset($i18n[$langId]) ? $i18n[$langId] : null);
        }
    }
}
