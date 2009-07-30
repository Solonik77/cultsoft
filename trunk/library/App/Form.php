<?php
/**
 * Application Forms Class
 *
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/engine/license.html
 */
class App_Form extends Zend_Form {
	/**
	 * Contructor
	 *
	 * @return Zend_Form object
	 */
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->addPrefixPath('App_Form_Element', 'App/Form/Element/', 'element');
		$this->setMethod('post');
	}

	/**
	 * Set form action
	 *
	 * @param  string $action
	 * @return Zend_Form
	 */
	public function setAction($action)
	{
		$request = App::front()->getRequest();
		if(empty($action))
		{
			$action = App::baseUri() . trim($request->getRequestUri(), '/') . '/';
		}
		if(Zend_Registry::get('BACKOFFICE_CONTROLLER'))
		{}
		return parent::setAction($action);
	}

	/**
	 * Set form name
	 *
	 * @param  string $name
	 * @return Zend_Form
	 */
	public function setName($name)
	{
		$name = $this->filterName($name, TRUE);
		if (('0' !== $name) && empty($name)) {
			require_once 'Zend/Form/Exception.php';
			throw new Zend_Form_Exception('Invalid name provided; must contain only valid variable characters and be non-empty');
		}
		return $this->setAttrib('name', $name);
	}
}