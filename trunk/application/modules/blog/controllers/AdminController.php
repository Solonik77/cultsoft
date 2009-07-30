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
	protected $blogService;

	public function init()
	{
		$this->view->pageTitle = __('Blogs');
		$this->view->pageDescription = __('Create, edit, delete posts for individual.');
	}

	public function preDispatch()
	{
		$this->blogService = new Blog_Model_Service();
	}

	/**
	 * Basic information about blogs
	 */
	public function indexAction()
	{
		$this->view->listBlogs = $this->blogService->getAllBlogs()->toArray();
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
				$this->blogService->save();
				// Set message to view
				$this->_helper->messages('Add new blog', 'success', TRUE);
				// Clear post
				$this->_redirect('admin/blog');
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