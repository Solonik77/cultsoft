<?php


require_once 'Zend/Controller/Action/Helper/Redirector.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';


class App_Controller_Action_Helper_Redirector extends Zend_Controller_Action_Helper_Redirector
{
    public function testMethod()
    {
        return 'Test';
    }

}