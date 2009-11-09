<?php
/**
 * Backoffice navigation component
 *
 * @author Denysenko Dmytro
 */
class Main_Component_BackofficeStructure {

    /*
     * getTopMenu
     * @return array
     */
    public function getTopMenu()
    {
        return array(array('module' => 'main' , 'controller' => 'backofficeDashboard' , 'action' => 'index' , 'label' => __('Dashboard'),
                'pages' => array(
        array('module' => 'main' , 'controller' => 'backofficeDashboard' , 'action' => 'settings' , 'label' => __('Settings')),
        )
        ) ,
        array('module' => 'blog' , 'controller' => 'admin' , 'action' => 'index' , 'label' => __('Blog') ,
                'pages' => array(
        array('module' => 'blog' , 'controller' => 'admin' , 'action' => 'manage-blogs' , 'label' => __('Manage blogs')),
        array('module' => 'blog' , 'controller' => 'admin' , 'action' => 'create-blog' , 'label' => __('Create blog')) ,
        )),
        );
    }
    /*
     * getFooterMenu
     * @return array
     */
    public function getFooterMenu()
    {
        return array(array('module' => 'main' , 'controller' => 'backofficeDashboard' , 'method' => 'index' , 'label' => __('Dashboard')) , array('module' => 'blog' , 'controller' => 'admin' , 'action' => 'index' , 'label' => __('Blog')));
    }
}