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
        $this->view->pageDescription = __('Create, edit, delete posts. Manage communities.');
        $this->view->headTitle($this->view->pageTitle);
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
        $this->view->listBlogs = $this->blogService->getBlogsList();
    }

    /**
     * Create new blog
     */
    public function newBlogAction()
    {
        $this->view->pageDescription = __('Create new blog');
        $this->view->headTitle($this->view->pageDescription);
        
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
                if($this->blogService->createBlog())
                {
                    // Set message to view
                    $this->_helper->messages('Add new blog', 'success', TRUE);
                    // Clear post
                    $this->_redirect('blog/admin');
                }
                else
                {
                    // Set message to view
                    $this->_helper->messages('Add new blog error', 'error', TRUE);
                    // Clear post
                    $this->_helper->redirector()->selfRedirect();
                }
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