<?php
class Install_Component_Structure
{

    public function __construct()
    {
    }

    public function getSequence()
    {
       $sequence = array(
       array( 'controller' => 'index' , 'action' => 'index' , 'label' => 'License'),
       array( 'controller' => 'index' , 'action' => 'pre-installation-check' , 'label' => 'Pre-installation check'),
       array( 'controller' => 'index' , 'action' => 'config' , 'label' => 'Base Configuration'),
       array( 'controller' => 'index' , 'action' => 'create-admin' , 'label' => 'Create admin account'),
       array( 'controller' => 'index' , 'action' => 'modules' , 'label' => 'Install modules'),
       array( 'controller' => 'index' , 'action' => 'finish' , 'label' => 'Finish installation'),
       );
       return $sequence;
    }
}