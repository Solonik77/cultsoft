<?php
class ELangCUrlManager extends CUrlManager {
    public function createUrl($route, $params = array(), $ampersand = '&')
    {

	    if (!isset($params['lang']) and !isset(Yii::app()->controller->module->id)) {
            $params['lang'] = Yii::app()->GetLanguage();
        }
        return parent::createUrl($route, $params, $ampersand);
    }
}
