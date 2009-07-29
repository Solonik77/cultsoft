<?php
class Blog_Form_EditBlog extends App_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);        
        $this->setAction('');        
        $title = $this->createElement('text', 'blog_title', array('label' => 'Title'));
        $title->addValidator('stringLength', false, array(1 , 100))->setRequired(true);
        $description = $this->createElement('textarea', 'blog_description', array('label' => 'Description'));
        $description->addValidator('StringLength', false, array(3))->setRequired(true);
        $this->addElement($title)
        ->addElement($description)
        ->addElement('submit', 'enter', array('label' => 'Save'));
        $this->addElement('hash', 'csrf_hash', array('salt' => 'unique'));
    }
}