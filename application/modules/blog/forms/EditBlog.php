<?php
class Blog_Form_EditBlog extends App_Form
{

    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setAction('');
        $title = $this->createElement('text', 'blog_title', array('label' => 'Title'));
        $title->addValidator('stringLength', false, array(1 , 100))->setRequired(true)->addFilter('stringTrim');
        $description = $this->createElement('textarea', 'blog_description', array('label' => 'Description' , 'rows' => '5'));
        $description->addValidator('StringLength', false, array(3))->setRequired(true)->addFilter('stringTrim');
        $type = $this->createElement('select', 'blog_type');
        $type->setRequired(true)->setLabel('Type')->setOptions($vFrmStatusOptions);
        $this->addElement($title)->addElement($description)->addElement($type)->addElement('submit', 'enter', array('label' => 'Save'));
        $this->addElement('hash', 'csrf_hash', array('salt' => 'unique'));
    }
}