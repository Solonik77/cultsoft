<?php
class Install_Component_Structure
{

    public function __construct()
    {
    }

    public function getSequence()
    {
       $sequence = array(
       array( 'controller' => 'index' , 'action' => 'index' , 'label' => 'Pre-installation check'),
       array( 'controller' => 'index' , 'action' => 'license' , 'label' => 'License')
       );
       return $sequence;
    }
}