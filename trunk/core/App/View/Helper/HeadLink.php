<?php
/**
* View Helper
*
* @package Core
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
/**
* App_View_Helper_HeadLink
*
* @see http://www.w3.org/TR/xhtml1/dtds.html
* @uses Zend_View_Helper_Placeholder_Container_Standalone
* @package Zend_View
* @subpackage Helper
*/
class App_View_Helper_HeadLink extends Zend_View_Helper_HeadLink {
    /**
    * Render link elements as string
    *
    * @return string
    */
    public function toString()
    {
        $stylesheets = array();
        $stylesheets = array();
        foreach($this as $stylesheet) {
            if ($stylesheet->type == 'text/css' && $stylesheet->conditionalStylesheet === false) {
                $stylesheets[$stylesheet->media][] = $stylesheet->href;
            } else {
                $stylesheets[] = $this->itemToString($stylesheet);
            }
        }
        foreach($stylesheets as $media => $styles) {
            $stylesheet = new stdClass();
            $stylesheet->rel = 'stylesheet';
            $stylesheet->type = 'text/css';
            $stylesheet->href = $this->getMinUrl() . '?f=' . implode(',',
                str_replace(
                    App::baseUri(), '/',
                    $styles));
            $stylesheet->media = $media;
            $stylesheet->conditionalStylesheet = false;
            $items[] = $this->itemToString($stylesheet);
        }
        return implode("\n", $items);
    }

    /**
    * Getting Minify URL
    */
    public function getMinUrl()
    {
        return App::baseUri() . 'static/system/vendor/min/';
    }
}
