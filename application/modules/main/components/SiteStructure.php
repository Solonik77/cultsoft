<?php
/**
 * Website navigation menus
 *
 * @author Denysenko Dmytro
 */
class Main_Component_SiteStructure {
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
            if ($leaf["depth"] == 1) {
                $menu_data[] = $leaf;
            }
        }
        return $menu_data;
    }
}