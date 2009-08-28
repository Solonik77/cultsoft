<?php
class ELangHandler extends CApplicationComponent {
    public $languages = array();
    public function init()
    {
        array_push($this->languages, Yii::app()->getLanguage());
        $this->parseLanguage();
    }

    private function parseLanguage()
    {
        Yii::app()->urlManager->parseUrl(Yii::app()->getRequest());
        if (!isset($_GET['lang'])) {
            $defaultLang = Yii::app()->getRequest()->getPreferredLanguage();
            if (in_array($defaultLang, $this->languages)) {
                Yii::app()->setLanguage($defaultLang);
                setlocale(LC_ALL, $this->languages[$defaultLang]['locale'] . '.UTF8');
            } else {
                Yii::app()->setLanguage($this->languages[0]);
                setlocale(LC_ALL, $this->languages[$this->languages[0]]['locale'] . '.UTF8');
            }
        } elseif ($_GET['lang'] != Yii::app()->getLanguage() && in_array($_GET['lang'], $this->languages)) {
            Yii::app()->setLanguage($_GET['lang']);
            setlocale(LC_ALL, $this->languages[$_GET['lang']]['locale'] . '.UTF8');
        }
    }
}
