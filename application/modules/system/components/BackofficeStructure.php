<?php
/**
* Backoffice navigation
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
*/
class System_Component_BackofficeStructure {
    public function __construct()
    {
    }

    public function getTopMenu()
    {
        return array(array('module' => 'system' , 'controller' => 'backofficeDashboard' , 'action' => 'index' , 'label' => __('Dashboard')) , array('module' => 'blog' , 'controller' => 'admin' , 'action' => 'index' , 'label' => __('Blog') , 'pages' => array(array('module' => 'blog' , 'controller' => 'admin' , 'action' => 'new-blog' , 'label' => __('Create blog')) , array('module' => 'blog' , 'controller' => 'admin' , 'action' => 'manage-blogs' , 'label' => __('Manage blogs')))));
    }

    public function getFooterMenu()
    {
        return array(array('module' => 'system' , 'controller' => 'backofficeDashboard' , 'method' => 'index' , 'label' => __('Dashboard')) , array('module' => 'blog' , 'controller' => 'admin' , 'action' => 'index' , 'label' => __('Blog')));
    }
}
