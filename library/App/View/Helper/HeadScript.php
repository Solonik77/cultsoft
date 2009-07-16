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
* App_View_Helper_HeadScript
*
* @see http://www.w3.org/TR/xhtml1/dtds.html
* @uses Zend_View_Helper_Placeholder_Container_Standalone
* @package Zend_View
* @subpackage Helper
*/
class App_View_Helper_HeadScript extends Zend_View_Helper_HeadScript {
    /**
    * Render link elements as string
    *
    * @return string
    */
    public function toString($indent = null)
    {
        $indent = (null !== $indent) ? $this->getWhitespace ($indent) : $this->getIndent();
        if ($this->view) {
            $useCdata = $this->view->doctype()->isXhtml() ? true : false;
        } else {
            $useCdata = $this->useCdata ? true : false;
        }
        $return = '';
        $escapeStart = ($useCdata) ? '//<![CDATA[' : '//<!--';
        $escapeStart .= PHP_EOL;
        $escapeEnd = ($useCdata) ? '//]]>' : '//-->';
        $escapeEnd = PHP_EOL . $escapeEnd;
        $scripts = array();
        $items = array();
        foreach ($this as $item) {
            if (! $this->_isValid ($item)) {
                continue;
            }
            if (isset ($item->attributes ['src'])) {
                $scripts [] = $item->attributes ['src'];
            } else {
                $items [] = $this->itemToString ($item, $indent, $escapeStart, $escapeEnd);
            }
        }
        if (count ($scripts) > 0) {
            $scripts = $this->getMinUrl() . '?f=' . implode (',', str_replace (App::baseUri(), '/', $scripts));
            $data = new stdClass();
            $data->type = 'text/javascript';
            $data->attributes = array('src' => $scripts);
            $data->source = null;
            $return .= $this->itemToString ($data, $indent, $escapeStart, $escapeEnd) . $this->getSeparator();
        }
        if (count ($items) > 0) {
            $return .= implode ($this->getSeparator(), $items);
        }
        return $return;
    }

    /**
    * Getting Minify URL
    */
    public function getMinUrl()
    {
        return App::baseUri() . 'static/system/vendor/min/';
    }
}
