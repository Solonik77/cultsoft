<?php
/**
* Admin blog
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
*/
class Blog_AdminController extends App_Controller_Action {
    CONST BACKOFFICE_CONTROLLER = true;
    protected $blogService;

    public function init()
    {
        $this->view->pageTitle = 'Blogs';
        $this->view->pageDescription = 'Create, edit, delete posts. Manage communities.';
        $this->view->headTitle ($this->view->pageTitle);
    }

    public function preDispatch()
    {
        $this->blogService = new Blog_Model_Service ();
    }

    /**
    * Basic information about blogs
    */
    public function indexAction()
    {
    }

    /**
    * Manage blogs
    */
    public function manageBlogsAction()
    {
        $this->view->pageDescription = 'Manage blogs.';
        $this->view->blogsList = $this->blogService->fetchBlogsList ();
    }

    /**
    * Create new blog
    */
    public function newBlogAction()
    {
        $this->view->pageDescription = 'Create new blog';
        $this->view->headTitle ($this->view->pageDescription);
        $form = new Blog_Form_EditBlog ();
        $form->compose ();
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost ();
            $form->populate ($formData);
            if (! $form->isValid ($formData)) {
                // Errors in input data
                $this->view->form = $form;
                return $this->render ();
            } else {
                // Saving new blog
                if ($this->blogService->saveBlog ()) {
                    // Set message to view
                    $this->_helper->messages ('New blog successfully created', 'success', true);
                    // Clear post
                    $this->_redirect ('blog/admin/manage-blogs');
                } else {
                    // Set message to view
                    $this->_helper->messages ('Error in creation new blog', 'error', true);
                    // Clear post
                    $this->_selfRedirect();
                }
            }
        }
        $this->view->form = $form;
    }

    /**
    * Edit blog
    */
    public function editBlogAction()
    {
        if (! $blogId = $this->_request->getParam ('id')) {
            return $this->render ('error-no-id');
        }
        $this->view->pageDescription = 'Edit blog';
        $this->view->headTitle ($this->view->pageDescription);
        $form = new Blog_Form_EditBlog ();
        $form->compose ();
        // Get blog content
        $formData = (! $this->_request->isPost ()) ? $this->blogService->findBlog ($blogId) : $this->_request->getPost ();
        $form->populate ($formData);
        if ($this->_request->isPost ()) {
            if ($this->_request->getParam ('delete_blog')) {
                // Delete blog
                if ($this->blogService->deleteBlog ()) {
                    $this->_helper->messages ('Blog deleted successfully', 'success', true);
                    $this->_redirect ('blog/admin/manage-blogs');
                }
            }
            if (! $form->isValid ($formData)) {
                // Errors in input data
                $this->view->form = $form;
                return $this->render ();
            } else {
                // Saving new blog
                if ($this->blogService->saveBlog ()) {
                    // Set message to view
                    $this->_helper->messages ('Changes for blog successfully saved', 'success', true);
                    // Clear post
                    $this->_redirect ('blog/admin/manage-blogs');
                } else {
                    // Set message to view
                    $this->_helper->messages ('Error editing blog', 'error', true);
                    // Clear post
                    $this->_selfRedirect();
                }
            }
        }
        $this->view->form = $form;
    }

    /**
    * Delete blog
    */
    public function deleteBlogAction()
    {
        if (! $blogId = $this->_request->getParam ('id')) {
            return $this->render ('error-no-id');
        } else {
            if ($this->blogService->deleteBlog ()) {
                $this->_helper->messages ('Blog deleted successfully', 'success', true);
            }
        }
        $this->_redirect ('blog/admin/manage-blogs');
    }
}
