<?php
/**
 * Backoffice navigation component
 *
 * @author Denysenko Dmytro
 */
class Main_Component_BackofficeStructure {
 private $_systemInfo;
 public function __construct()
 {
    $this->_systemInfo = new Main_Model_SystemInfo;
    $modulesInfo = $this->_systemInfo->getModulesInfo();
     foreach($modulesInfo as $module => $moduleData){
        if($module != 'main'){
            $moduleId = ucfirst($module);
            $moduleFolder = strtolower($module);
            $resourceLoader = new Zend_Loader_Autoloader_Resource(array('basePath' => APPLICATION_PATH . 'modules/' . $moduleFolder, 'namespace' => $moduleId));
            $resourceLoader->addResourceTypes(array('component' => array('namespace' => 'Component' , 'path' => 'components') , 'dbtable' => array('namespace' => 'Model_DbTable' , 'path' => 'models/DbTable') , 'form' => array('namespace' => 'Form' , 'path' => 'forms') , 'model' => array('namespace' => 'Model' , 'path' => 'models') , 'plugin' => array('namespace' => 'Plugin' , 'path' => 'plugins') , 'service' => array('namespace' => 'Service' , 'path' => 'services') , 'helper' => array('namespace' => 'Helper' , 'path' => 'helpers') , 'viewhelper' => array('namespace' => 'View_Helper' , 'path' => 'views/helpers') , 'viewfilter' => array('namespace' => 'View_Filter' , 'path' => 'views/filters')));
        }
    }
 }

    /*
     * getTopMenu
     * @return array
     */
    public function getTopMenu()
    {        
        $data = array();
        $mainModule = array('module' => 'main' , 'controller' => 'backofficeDashboard' , 'action' => 'index' , 'label' => __('Dashboard'),
                'pages' => array(
        array('module' => 'main' , 'controller' => 'backofficeDashboard' , 'action' => 'settings' , 'label' => __('Settings')),
        )
        );
        
        $data[] = $mainModule;
        $modulesInfo = $this->_systemInfo->getModulesInfo();
        foreach($modulesInfo as $moduleId => $moduleData){
        if($moduleId != 'main'){
            $component = ucfirst($moduleId) . '_Component_BackofficeStructure';
            $component = new $component;
            $data[] = $component->getTopMenu();
            }
        }
        return $data;
    }
    /*
     * getFooterMenu
     * @return array
     */
    public function getFooterMenu()
    {
        return array(array('module' => 'main' , 'controller' => 'backofficeDashboard' , 'method' => 'index' , 'label' => __('Dashboard')));
    }
}