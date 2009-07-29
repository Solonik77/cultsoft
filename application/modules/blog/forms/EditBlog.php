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
		$title = $this->createElement('text', 'title', array('label' => 'Title'));
		$title->addValidator('stringLength', false, array(1 , 100))->setRequired(true)->addFilter('stringTrim');
		$description = $this->createElement('textarea', 'description', array('label' => 'Description' , 'rows' => '5'));
		$description->addValidator('StringLength', false, array(3))->setRequired(true)->addFilter('stringTrim');
		$type = $this->createElement('select', 'blog_type');
		$type->setRequired(true)->setLabel('Type')->setMultiOptions(array(__('Personal blog') , __('Collaborative blog (community)')))->setValue($this->_type);
		$this->addElement($title)->addElement($description)->addElement($type)->addElement('submit', 'enter', array('label' => 'Save'));
		$this->addElement('hash', 'csrf_hash', array('salt' => 'unique'));
		return $this;
	}

	public function setType($id)
	{
		$this->_type = $id;
	}
}