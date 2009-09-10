<?php
class Blog_Form_EditBlog extends App_Form {
    private $_type = 0;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function compose()
    {
        $this->setAction('');
        $this->setElementsBelongTo('blog');
        $moduleLangs = App::i18n()->getModuleLanguages();
        if (count($moduleLangs) > 0) {
            foreach($moduleLangs as $lang) {
                $langForm = new Zend_Form_SubForm;
                $langForm->setLegend(__($lang['name']));
                $langForm->setElementsBelongTo('i18n_blog[' . $lang['id'] . ']');
                $title = $this->createElement('text', 'title', array('maxlength' => 100))->setLabel('Title');
                $title->addValidator('stringLength', false, array(1 , 100))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
                $description = $this->createElement('textarea', 'description', array('label' => 'Description' , 'rows' => '2'));
                $description->addValidator('StringLength', false, array(3 , 255))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
                $langForm->addElement($title)->addElement($description);
                $this->addSubForm($langForm, 'lang_' . $lang['id']);
            }
        } else {
            //  @todo Show error message
        }
        $fancy_url = $this->createElement('text', 'fancy_url')->setLabel('Fancy url');
        $fancy_url->addValidator('stringLength', false, array(1 , 100));
        $fancy_url->addFilter('stringTrim')->addFilter('StripTags')->addFilter('StringToLower');
        $this->addElement($fancy_url);
        $type = $this->createElement('select', 'type');
        $type->setRequired(true)->setLabel('Type')->addValidator('int')->setMultiOptions(array(1 => __('Personal blog') , 2 => __('Collaborative blog (community)')))->setValue($this->_type);
        $this->addElement($type);
        $this->addElement('hash', 'csrf_hash', array('salt' => 'unique'));

        $this->addElement($this->createElement('submit', 'save_blog')->setLabel('Save')->setDecorators($this->decoratorSpan));
        if (App::front()->getRequest()->getParam('id')) {
            $this->addElement($this->createElement('submit', 'delete_blog')->setLabel('Delete')->setDecorators($this->decoratorSpan));
        }
        return $this;
    }

    public function setType($id)
    {
        $this->_type = $id;
        return $this;
    }
}
