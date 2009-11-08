<?php
/**
 * Application process control file, loaded by the front controller.
 *
 * @author Denysenko Dmytro
 */
define('INSTALLER_RUN', TRUE);
define('TIME_NOW', time());
// SERVER_UTF8 ? use mb_* functions : use non-native functions
if (extension_loaded('mbstring')) {
    mb_internal_encoding('UTF-8');
    define('SERVER_UTF8', true);
} else {
    define('SERVER_UTF8', false);
}
@set_include_path(INSTALLER_PATH . PATH_SEPARATOR . get_include_path());
require_once LIBRARY_PATH . 'App/Loader.php';
final class Bootstrap
{
    public function __construct()
    {
         
        App_Loader::init();
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array('basePath' => INSTALLER_PATH, 'namespace' => 'Install'));
        $resourceLoader->addResourceTypes(array('component' => array('namespace' => 'Component' , 'path' => 'components') , 'dbtable' => array('namespace' => 'Model_DbTable' , 'path' => 'models/DbTable') , 'form' => array('namespace' => 'Form' , 'path' => 'forms') , 'model' => array('namespace' => 'Model' , 'path' => 'models') , 'plugin' => array('namespace' => 'Plugin' , 'path' => 'plugins') , 'service' => array('namespace' => 'Service' , 'path' => 'services') , 'helper' => array('namespace' => 'Helper' , 'path' => 'helpers') , 'viewhelper' => array('namespace' => 'View_Helper' , 'path' => 'views/helpers') , 'viewfilter' => array('namespace' => 'View_Filter' , 'path' => 'views/filters')));
        $this->_initErrorHandler();
        $this->_initEnvironment();
        $this->_initConfiguration();
        $this->_initDatabase();
        $this->_initRoutes();
        $this->_initSession();
        $this->_initView();
        $this->_initDebug();
    }
    /**
     * Setup system error handler
     *
     * @return void
     */
    private function _initErrorHandler ()
    {
        // Enable exception handling
        App_Exception::enable();
        $front = App::front();
        $front->throwExceptions(false);
        $front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(array('module' => 'install' , 'controller' => 'error' , 'action' => 'error')));
        $logger = new Zend_Log();
        if ('development' === APPLICATION_ENV) {
            $logger->addWriter(new Zend_Log_Writer_Firebug());
        }
        $logger->addWriter(new Zend_Log_Writer_Stream(VAR_PATH . "logs" . "/installer_log_" . date('Y-m-d') . '.log'));
        App::setLog($logger);
    }

    /**
     * Setup php, server environment, clean input parameters
     */
    private function _initEnvironment()
    {
        App_Utf8::clean_globals();
        App_Input::instance();
        ini_set('log_errors', true);
        if ('development' === APPLICATION_ENV) {
            ini_set('display_errors', true);
            error_reporting(E_ALL & ~ E_STRICT);
        } else {
            ini_set('display_errors', false);
            error_reporting(E_COMPILE_ERROR | E_RECOVERABLE_ERROR | E_ERROR | E_CORE_ERROR);
        }
        @date_default_timezone_set(@date_default_timezone_get());
        umask(0);
    }
    
    private function _initConfiguration ()
    {
        if (file_exists(VAR_PATH . 'initial.configuration.ini')) {
        $options = new Zend_Config_Ini(VAR_PATH . 'initial.configuration.ini', null, true);
        App::addConfig($options);
        } elseif(file_exists(VAR_PATH . 'configuration.ini')){
            $options = new Zend_Config_Ini(VAR_PATH . 'configuration.ini', null, true);
            App::addConfig($options);
        } 
    }

    /**
     * * Database connection setup
     *
     * @return void
     */
    private function _initDatabase ()
    {
        try {
            $config = App::config()->database->toArray();
            $config['adapterNamespace'] = 'App_Db_Adapter';
            $config['persistent'] = false;
            $config['charset'] = 'utf8';
            $config['driver_options'] = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION , PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true);
            App::setDb(Zend_Db::factory(App_Utf8::strtoupper($config['adapter']), $config));
            App::db()->getConnection();
            App::db()->query("SET NAMES 'utf8'");
            defined('DB_TABLE_PREFIX') or define('DB_TABLE_PREFIX', App::config()->database->table_prefix);
        } catch (Zend_Db_Adapter_Exception $e) {
            throw new App_Exception($e->getMessage());
        }
    }   
    
    /**
     * ZendDebug panel
     *
     * @return void
     */
    private function _initDebug ()
    {
        if ('development' === APPLICATION_ENV) {
            App::front()->registerPlugin(new ZFDebug_Controller_Plugin_Debug(array('plugins' => array('Auth' => array('user' => 'email' , 'role' => 'role_id') , 'Text' , 'Variables' , 'Database' => array('adapter' => array('standard' => App::db())) , 'File' => array('basePath' => APPLICATION_PATH) , 'Memory' , 'Html' , 'Time' , 'Registry' , 'Cache' => array('backend' => App_Cache::getInstance('File')->getBackend()) , 'Exception'))));
        }
    }
    /**
     * Setup URI routes
     *
     * @return void
     */
    private function _initRoutes ()
    {
        App::front()->getRouter()->addRoute('default', new Zend_Controller_Router_Route(':module/:action/', array('module' => 'install' , 'controller' => 'index' , 'action' => 'index')));
    }

    /**
     * PHP Session handler setup
     *
     * @return void
     */
    private function _initSession ()
    {
        if(is_dir(VAR_PATH . "session") AND is_writeable(VAR_PATH . "session")){
            Zend_Session::setOptions(array('remember_me_seconds' => 8500 , 'save_path' => VAR_PATH . "session" , 'gc_probability' => 1 , 'gc_divisor' => 5000 , 'name' => "zfsession" , 'use_only_cookies' => 1));
        }
        Zend_Session::start();
    }

    /**
     * Layout setup
     */
    private function _initView()
    {
        $layout = Zend_Layout::startMvc(array('layoutPath' => INSTALLER_PATH . 'views/layouts/' , 'layout' => 'installer' , 'mvcSuccessfulActionOnly' => FALSE));
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $view = new Zend_View(array('encoding' => 'UTF-8'));
        $view->addHelperPath(LIBRARY_PATH . 'App/View/Helper/', 'App_View_Helper');
        $view->addHelperPath(INSTALLER_PATH . 'views/helpers/', 'Install_View_Helper');
        $view->addFilterPath('App/View/Filter', 'App_View_Filter');
        // Enable JQuery support
        $view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
        $view->jQuery()->enable();
        $view->jQuery()->uiEnable();
        $view->headTitle()->setSeparator(' « ');
        $view->strictVars(true);
        // Set global content type to html with UTF-8 charset
        $view->getHelper('HeadMeta')->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        // Set default reset.css file. Clear all CSS rules.
        $view->getHelper('HeadLink')->appendStylesheet(App::baseUri() . 'static/system/css/reset.css');
        $view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/minmax.js');
        // Add latest Jquery library to html header.
        $view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/jquery.js');
        $view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/swfobject.js');
        $view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/jquery/pngfix.js');
        $view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/init_global.js');
        $view->getHelper('HeadLink')->appendStylesheet(App::baseUri() . 'static/system/css/installer.css');
        $view->setScriptPath(INSTALLER_PATH . 'views/scripts/');
        $viewRenderer->setView($view)->setViewBasePathSpec(INSTALLER_PATH . 'views/scripts/');
        $viewRenderer->setViewScriptPathSpec(':controller/:action.:suffix');
        $viewRenderer->setViewScriptPathNoControllerSpec(':action.:suffix');
        $viewRenderer->setViewSuffix('phtml');
    }

    public function run()
    {
        try {
            $front = App::front();
            $front->setDefaultModule('install');
            $front->setControllerDirectory(INSTALLER_PATH . 'controllers');
            $default = $front->getDefaultModule();
            if (null === $front->getControllerDirectory($default)) {
                throw new App_Exception('No default controller directory registered with front controller');
            }
            $front->setParam('prefixDefaultModule', true);
            $front->returnResponse(true);
            $response = App::front()->dispatch();
            $response->setHeader('Expires', 'Sat, 13 Apr 1985 00:30:00 GMT')->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT')->setHeader('Cache-Control', 'no-cache, must-revalidate')->setHeader('Cache-Control', 'post-check=0,pre-check=0')->setHeader('Cache-Control', 'max-age=0')->setHeader('Pragma', 'no-cache')->setHeader('Content-type', 'text/html; charset=UTF-8');
            $response->sendResponse();
            exit();
        } catch (Exception $e) {
            throw new App_Exception($e->getMessage());
        }
    }
}