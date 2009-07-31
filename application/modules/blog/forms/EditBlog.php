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
        $this->setAction('');
        $siteLang = App::siteLanguages();
        foreach($siteLang as $lang)
        {
            $title = $this->createElement('text', 'langid_' . $lang['id'] . '_title')->setLabel('Title');
            $title->addValidator('stringLength', false, array(1 , 100))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
            $description = $this->createElement('textarea', 'langid_' . $lang['id'] . '_description', array('label' => 'Description' , 'rows' => '2'));
            $description->addValidator('StringLength', false, array(3, 255))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
            $this->addElement($title)->addElement($description);
            $this->addDisplayGroup(array('langid_' . $lang['id'] . '_title' , 'langid_' . $lang['id'] . '_description'), $lang['id'] . '_content', array("legend" => __($lang['name'])));
        }
        $slug = $this->createElement('text', 'slug')->setLabel('Slug');
        $slug->addValidator('stringLength', false, array(1 , 100));
        $slug->addFilter('stringTrim')->addFilter('StripTags')->addFilter('StringToLower');
        $this->addElement($slug);
        $type = $this->createElement('select', 'type');
        $type->setRequired(true)->setLabel('Type')->addValidator('int')->setMultiOptions(array(1 => __('Personal blog') , 2 => __('Collaborative blog (community)')))->setValue($this->_type);
        $this->addElement($type);
        $this->addElement('hash', 'csrf_hash', array('salt' => 'unique'));
        if(App::front()->getRequest()->getParam('id')){
        	$this->addElement('submit', 'delete_blog', array('label' => 'Delete'));        	
        }
        $this->addElement('submit', 'save_blog', array('label' => 'Save'));
        
        
        
        return $this;
    }

    public function setType($id)
    {
        $this->_type = $id;
        return $this;
    }
}