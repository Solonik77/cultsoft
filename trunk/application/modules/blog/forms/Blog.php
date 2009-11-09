<?php
/*
 * Application form for create and update blog data
 */
class Blog_Form_Blog extends App_Form {
    private $_currentBlogType = 'private';
    private $_blogTypes = array();
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
                if ($this->getIsUpdate()) {
                    $langForm->addElement($this->createElement('hidden', 'id'));
                }
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
        $type->setRequired(true)->setLabel('Type')->addValidator('StringLength', false, array(3 , 255))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags')->setMultiOptions($this->_blogTypes)->setValue($this->_currentBlogType);
        $this->addElement($type);
        $this->addElement('hash', 'csrf_hash', array('salt' => 'unique'));

        $this->addElement($this->createElement('submit', 'save_blog')->setLabel('Save')->setDecorators($this->decoratorSpan));
        if ($this->getIsUpdate()) {
            $this->addElement($this->createElement('submit', 'delete_blog')->setLabel('Delete')->setDecorators($this->decoratorSpan));
            $this->addElement($this->createElement('hidden', 'id'));
        }

        return $this;
    }

    public function setBlogTypes($array)
    {
        $this->_blogTypes = $array;
    }

    public function setCurrentBlogType($type)
    {
        $this->_currentBlogType = $type;
        return $this;
    }
}
