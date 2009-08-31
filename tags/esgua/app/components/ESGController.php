<?php
// UTF-8 Support
include_once(APP_PATH . 'extensions/utf8/utf8.php');

/**
* Base controller
*/
class ESGController extends CController {
    public $websiteLanguages;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
		header('Content-Type: text/html; charset=UTF-8');
        date_default_timezone_set('Europe/Helsinki');
        $this->websiteLanguages = Yii::app()->params['websiteLanguages'];
    }
}
/**
* Text Translator
*/
function __($message, $params = array(), $category = 'base', $language = 'uk', $source = "siteTranlation")
{
    return Yii::t($category, $message, $params, $source, $language);
}