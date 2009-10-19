<?php

class Install_Component_Structure
{
    public function __construct()
    {
    }
    
    public function getMenu()
    {
        return array(array('module' => 'install' , 'controller' => 'index' , 'action' => 'index' , 'label' => 'Pre-installation check',
                'pages' => array(
        array('module' => 'install' , 'controller' => 'index' , 'action' => 'license' , 'label' => 'License'),
        )
        )
        );
    }
}