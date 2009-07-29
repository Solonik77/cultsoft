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
        $this->view->pageDescription = __('Create new blog.');
    }

    /**
     * Manage blogs
     */
    public function manageBlogsAction()
    {
        $this->view->pageDescription = __('Manage blogs.');
    }
}