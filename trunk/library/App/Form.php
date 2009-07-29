<?php
/**
 * Application Forms Class
 *
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/engine/license.html
 */
class App_Form extends Zend_Form
{

	/**
	 * Contructor
	 *
	 * @return Zend_Form object
	 */
	public function __construct($options = null)
	{
		parent::__construct($options);
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
			$action = $request->getRequestUri();
		}
		if(Zend_Registry::get('BACKOFFICE_CONTROLLER'))
		{}
		return parent::setAction($action);
	}
}