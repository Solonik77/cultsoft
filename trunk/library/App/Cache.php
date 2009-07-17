<?php
/**
* Provide simple sinleton interface to Zend_Cache different backends and internal system information.
*
* @package Core
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
class App_Cache {
    protected static $instance;
    protected $cache;
    protected $_instances = array();
    protected $_defaultInstance = 'File';
    protected $_defaultFrontendOptions;
    protected $_defaultBackendOptions;

    /**
    * Singleton Application cache
    */
    public static function getInstance($instanceId = null)
    {
        if (App_Cache::$instance == null) {
            new App_Cache();
        }
        if ($instanceId == null) {
            return App_Cache::$instance;
        } else if (is_string($instanceId)) {
            $instanceId = 'getInstance' . ucfirst(strtolower($instanceId));
            return App_Cache::$instance->$instanceId();
        }
    }

    /**
    * Constructor
    */
    public function __construct()
    {
        if (App_Cache::$instance === null) {
            $this->_defaultFrontendOptions = array('lifetime' => App::config()->cache_lifetime, 'cache_id_prefix' => 'content_', 'write_control' => true, 'automatic_serialization' => true, 'ignore_user_abort' => true);
            $this->_defaultBackendOptions = array('cache_dir' => App::config()->syspath->cache . '/', 'read_control' => true, 'hashed_directory_level' => 1, 'file_name_prefix' => 'app_file_cache');
            $systemCacheFrontend = $this->_defaultFrontendOptions;
            // Permanent storage
            $systemCacheFrontend ['lifetime'] = null;
            $this->initInstance(array('id' => 'System', $systemCacheFrontend));
            App_Cache::$instance = $this;
        }
    }

    /**
    * Magic __call execute system internal or Zend_Cache methods
    */
    public function __call($method, $args)
    {
        $return = null;
        if ((strlen($method) > 11) and(substr($method, 0, 11) == 'getInstance')) {
            $id = ucfirst(strtolower(substr($method, 11)));
            if (! isset($this->cache->$id)) {
                $return = $this->initInstance(array('id' => $id));
            } else {
                $return = $this->cache->$id;
            }
        }
        return $return;
    }

    /**
    * Get cached system info
    */
    public function getAclRoles()
    {
        $data = null;
        if (!($data = $this->cache->System->load('AclRoles'))) {
            $model = new App_Model_DbTable_Acl_Roles();
            $model = $model->fetchAll();
            $model = $model->toArray();
            $data = array();
            foreach($model as $item) {
                $data [$item ['id']] = $item;
            }
            $this->cache->System->save($data);
        }
        return $data;
    }

    /**
    * Get data for website navigation menu tree
    */
    public function getSiteNavigationTree()
    {
        $data = null;
        if (!($data = $this->cache->System->load('SiteNavigationTree'))) {
            $model = new App_Model_DbTable_Site_Structure();
            $data = $model->getTree();
            $this->cache->System->save($data);
        }
        return $data;
    }

    /**
    * Create instances of Zend_Cache with unique ID
    */
    public function initInstance(array $instanceId, $frontendOptions = null, $backendOptions = null)
    {
        $frontendOptions = (! is_null($frontendOptions) and is_array($frontendOptions) and ! empty($frontendOptions)) ? $frontendOptions : $this->_defaultFrontendOptions;
        $backendOptions = (! is_null($backendOptions) and is_array($backendOptions) and ! empty($backendOptions)) ? $backendOptions : $this->_defaultBackendOptions;
        if (is_array($instanceId) and isset($instanceId ['id'])) {
            $instanceId ['id'] = ucfirst(strtolower($instanceId ['id']));
            // Create instance only once
            if (! in_array($instanceId ['id'], $this->_instances)) {
                $backend = (isset($instanceId ['backend'])) ? ucfirst(strtolower($instanceId ['backend'])) : 'File';
                $this->cache->$instanceId ['id'] = Zend_Cache::factory('Core', $backend, $frontendOptions, $backendOptions);
                $this->_instances [] = $instanceId ['id'];
            }
        } else {
            throw new App_Exception('Cache instance ID must be valids array');
        }
        return $this->cache->$instanceId ['id'];
    }
}
