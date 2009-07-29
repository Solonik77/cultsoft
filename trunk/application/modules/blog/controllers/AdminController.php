<?php
/**
 * Admin blog
 *
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/engine/license.html
 */
class Blog_AdminController extends App_Controller_Action
{
	CONST BACKOFFICE_CONTROLLER = TRUE;
	protected $blogModel;

	public function init()
	{
		$this->view->pageTitle = __('Blogs');
		$this->view->pageDescription = __('Create, edit, delete posts for individual.');
	}

	public function preDispatch()
	{
		$this->blogModel = new Blog_Model_DbTable_Blog();
	}

	/**
	 * Basic information about blogs
	 */
	public function indexAction()
	{
		$this->view->listBlogs = $this->blogModel->fetchAll()->toArray();
	}

	/**
	 * Create new blog
	 */
	public function newBlogAction()
	{
		$this->view->pageDescription = __('Create new blog');
		$form = new Blog_Form_EditBlog();
		$form->compose();
		if($this->_request->isPost())
		{
			$formData = $this->_request->getPost();
			$form->populate($formData);
			if(! $form->isValid($formData))
			{
				// Errors in input data
				$this->view->form = $form;
				return $this->render();
			}
			else
			{
				// Saving new blog
				$this->_redirect('admin');
				$this->view->messages('Add new blog','success', TRUE);
			}
		}
		$this->view->form = $form;
	}

	/**
	 * Manage blogs
	 */
	public function manageBlogsAction()
	{
		$this->view->pageDescription = __('Manage blogs.');
		$this->view->listBlogs = $this->blogModel->fetchAll()->toArray();
	}
}