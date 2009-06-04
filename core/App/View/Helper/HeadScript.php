<?php
/**
 * View Helper 
 *
 * @package Core
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/engine/license.html
 */
/*
* App_View_Helper_HeadScript
* @see        http://www.w3.org/TR/xhtml1/dtds.html
* @uses       Zend_View_Helper_Placeholder_Container_Standalone
* @package    Zend_View
* @subpackage Helper
*/
class App_View_Helper_HeadScript extends Zend_View_Helper_HeadScript
{
    /**
     * Render link elements as string
     *
     * @return string
     */
    public function toString ()
    {
        $indent = $this->getIndent();
        if ($this->view) {
            $useCdata = $this->view->doctype()->isXhtml() ? true : false;
        } else {
            $useCdata = $this->useCdata ? true : false;
        }
        $escapeStart = ($useCdata) ? '//<![CDATA[' : '//<!--';
        $escapeEnd = ($useCdata) ? '//]]>' : '//-->';
        $scripts = array();
        foreach ($this as $item) {
            if (! $this->_isValid($item)) {
                continue;
            }
            $scripts[] = $item->attributes['src'];
        }
        $scripts = $this->getMinUrl() . '?f=' . implode(',', str_replace(App::baseUri(), '/', $scripts));
        $data = new stdClass();
        $data->type = 'text/javascript';
        $data->attributes = array('src' => $scripts);
        $data->source = null;
        $return = $this->itemToString($data, $indent, $escapeStart, $escapeEnd) . $this->getSeparator();
        return $return;
    }
    /*
     Getting Minify URL
    */
    public function getMinUrl ()
    {
        return App::baseUri() . 'static/system/min/';
    }
}