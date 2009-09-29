<?php

class Blog_Form_Simple_Search_Blog extends App_Form {
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setAction('');
        $query = $this->createElement('text', 'search_query', array('maxlength' => 100));
        $query->addValidator('stringLength', false, array(1 , 100))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
        $this->addElement($query);
        $this->addElement($this->createElement('submit', 'search_blog')->setLabel(__('Search blog'))->setDecorators($this->decoratorSpan));
    }
}