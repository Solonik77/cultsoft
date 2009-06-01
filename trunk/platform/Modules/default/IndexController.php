<?php
/**
* Demo Index controller.
* This controller used for front demo and testing pages.
*
* @package Core
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
*/
class Default_IndexController extends App_Controller_Action {
    public function init()
    {
        // action body
        $pages = array(
            array('label' => 'Home',
                'title' => 'Go Home',
                'module' => 'default',
                'controller' => 'index',
                'action' => 'index',
                'order' => -100 // make sure home is the first page
                ),
            array('label' => 'Special offer this week only!',
                'module' => 'store',
                'controller' => 'offer',
                'action' => 'amazing',
                'visible' => false // not visible
                ),
            array('label' => 'Products',
                'module' => 'default',
                'controller' => 'index',
                'action' => 'test',
                )
            );
        // Create container from array
        $container = new Zend_Navigation($pages);
        $this->view->navigation($container);
    }
    /**
    * Default system action
    */
    public function indexAction()
    {
    }
    /**
    * Test Zend_Navigation helpers
    */
    public function testAction()
    {
    }
}

