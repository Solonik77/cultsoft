<?php
/**
 * Website navigation menus model
 *
 * @package Core
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/platform/license.html
 */
class Site_Model_Navigation_Menu
{

    public function __construct ()
    {
    }

    public function getPageTopMenu ()
    {
        return array(array('label' => 'Home' , 'title' => 'Go Home' , 'module' => 'default' , 'controller' => 'index' , 'action' => 'index' , 'order' => - 100) , // make sure home is the first page
array('label' => 'Special offer this week only!' , 'module' => 'store' , 'controller' => 'offer' , 'action' => 'amazing' , 'visible' => false) , // not visible
array('label' => 'Products' , 'module' => 'default' , 'controller' => 'index' , 'action' => 'test'));
    }
}