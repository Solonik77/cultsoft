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

    public function init()
    {
        $this->view->pageTitle = __('Blogs');
        $this->view->pageDescription = __('Create, edit, delete posts for individual.');
    }

    /**
     * Default system action
     */
    public function indexAction()
    {
     $blogs = new Blog_Model_DbTable_Blog();
     $this->view->listBlogs = $blogs->fetchAll();
    }
}