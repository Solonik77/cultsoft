<?php
class Blog_Form_EditBlog extends App_Form
{
    private $_type = 0;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function compose()
    {
        $blog = new Blog_Model_DbTable_Blog();
        $this->setAction('');
        $siteLang = App::siteLanguages();
        foreach($siteLang as $lang)
        {
            $title = $this->createElement('text', 'title[' . $lang['id'] . ']')->setLabel('Title');
            $title->addValidator('stringLength', false, array(1 , 100))->setRequired(true)->addFilter('stringTrim');
            $description = $this->createElement('textarea', 'description[' . $lang['id'] . ']', array('label' => 'Description' , 'rows' => '5'));
            $description->addValidator('StringLength', false, array(3))->setRequired(true)->addFilter('stringTrim');
            $this->addElement($title)->addElement($description);
        }
        $type = $this->createElement('select', 'blog_type');
        $type->setRequired(true)->setLabel('Type')->addValidator('int')->setMultiOptions(array(__('Personal blog') , __('Collaborative blog (community)')))->setValue($this->_type);
        $this->addElement($type)->addElement('submit', 'enter', array('label' => 'Save'));
        $this->addElement('hash', 'csrf_hash', array('salt' => 'unique'));
        return $this;
    }

    public function setType($id)
    {
        $this->_type = $id;
        return $this;
    }
}