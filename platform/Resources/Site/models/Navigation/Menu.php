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

    public function getNavigationTree ()
    {
        return App_Cache::getInstance()->getSiteNavigationTree();
    }

    public function getTopMenu ()
    {
        $tree = $this->getNavigationTree();
        Zend_Debug::dump($tree);
        die('@todo class ' . __CLASS__);
        return;
    }
}