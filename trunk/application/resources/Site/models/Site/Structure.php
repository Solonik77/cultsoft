<?php
/**
* Website navigation menus model
*
* @package Core
* @author Denysenko Dmytro
* @copyright(c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
class Site_Model_Site_Structure {
    public function __construct()
    {
    }

    /**
    * Get all site navigation tree from cache
    *
    * @return array
    */
    public function getNavigationTree()
    {
        return App_Cache::getInstance()->getSiteNavigationTree();
    }

    /**
    * Get first level from navigation tree
    *
    * @return array
    */
    public function getTopMenu()
    {
        $tree = $this->getNavigationTree();
        $menu_data = array();
        foreach($tree as $leaf) {
            if($leaf ["depth"] == 1) {
                $menu_data [] = $leaf;
            }
        }
        return $menu_data;
    }
}
