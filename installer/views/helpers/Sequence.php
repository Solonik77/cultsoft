<?php

class Install_View_Helper_Sequence extends Zend_View_Helper_Navigation_Menu {
    public function sequence(Zend_Navigation_Container $container = null)
    {
        $this->setRenderParents(false);
        return $this->menu($container);
    }

    public function htmlify(Zend_Navigation_Page $page)
    {
        // get label and title for translating
        $label = $page->getLabel();
        $title = $page->getTitle();
        // translate label and title?
        if ($this->getUseTranslator() && $t = $this->getTranslator()) {
            if (is_string($label) && !empty($label)) {
                $label = $t->translate($label);
            }
            if (is_string($title) && !empty($title)) {
                $title = $t->translate($title);
            }
        }
        // get attribs for element
        $attribs = array('id' => $page->getId(),
            'title' => $title,
            'class' => $page->getClass()
            );
        $element = 'span';

        return '<' . $element . $this->_htmlAttribs($attribs) . '>'
         . $this->view->escape($label)
         . '</' . $element . '>';
    }
}
