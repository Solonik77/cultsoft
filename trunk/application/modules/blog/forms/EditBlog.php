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
			$title = $this->createElement('text','langid_'. $lang['id'].'_title')->setLabel('Title');
			$title->addValidator('stringLength', false, array(1 , 100))->setRequired(true)->addFilter('stringTrim');
			$description = $this->createElement('textarea', 'langid_'. $lang['id'].'_description', array('label' => 'Description' , 'rows' => '5'));
			$description->addValidator('StringLength', false, array(3))->setRequired(true)->addFilter('stringTrim');
			$this->addElement($title)->addElement($description);
            $this->addDisplayGroup(array('langid_'. $lang['id'].'_title',
            'langid_'. $lang['id'].'_description'),
            $lang['id'] . '_content',
            array("legend" =>  __($lang['name'])));
		}
        
		$type = $this->createElement('select', 'type');
		$type->setRequired(true)->setLabel('Type')->addValidator('int')->setMultiOptions(array(1 => __('Personal blog') , 2 => __('Collaborative blog (community)')))->setValue($this->_type);
		$this->addElement($type)->addElement('submit', 'submit', array('label' => 'Save'));
		$this->addElement('hash', 'csrf_hash', array('salt' => 'unique'));
		return $this;
	}

	public function setType($id)
	{
		$this->_type = $id;
		return $this;
	}
}