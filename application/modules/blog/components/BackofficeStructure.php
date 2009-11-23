<?php

class Blog_Component_BackofficeStructure
{
    public function getTopMenu()
    {
        return array('module' => 'blog' , 'controller' => 'admin' , 'action' => 'index' , 'label' => __('Blog') ,
                'pages' => array(
        array('module' => 'blog' , 'controller' => 'admin' , 'action' => 'manage-blogs' , 'label' => __('Manage blogs')),
        array('module' => 'blog' , 'controller' => 'admin' , 'action' => 'create-blog' , 'label' => __('Create blog')) ,
        ));
    }
}