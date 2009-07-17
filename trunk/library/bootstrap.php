<?php
/**
* Application process control file, loaded by the front controller.
*
* $Id$
*
* @package Core
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
require_once(LIBRARY_PATH . 'Zend/Loader/Autoloader.php');
require_once(LIBRARY_PATH . 'app.php');
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
    /**
    * Constructor
    *
    * @param Zend_Application $ |Zend_Application_Bootstrap_Bootstrapper $application
    * @return void
    */
    public function __construct($application)
    {
        define('TIME_NOW', time());
        // SERVER_UTF8 ? use mb_* functions : use non-native functions
        if (extension_loaded('mbstring')) {
            mb_internal_encoding('UTF-8');
            define('SERVER_UTF8', true);
        } else {
            define('SERVER_UTF8', false);
        }
        parent::__construct($application);
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('App_');
        $autoloader->registerNamespace('V_');
        if (APPLICATION_ENV == 'development') {
            $autoloader->registerNamespace('ZFDebug_');
        }
        $autoloader->setFallbackAutoloader(false);
        $this->_initConfiguration();
        $classFileIncCache = App::config()->syspath->cache . '/zend_framework_plugin_loader_cache.php';
        if (file_exists($classFileIncCache)) {
            include_once $classFileIncCache;
        }
        Zend_Loader_PluginLoader::setIncludeFileCache($classFileIncCache);
        // Resource autoload
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array('basePath' => APPLICATION_PATH . 'resources/Site', 'namespace' => 'Site'));
        $resourceLoader->addResourceTypes(array('model' => array('namespace' => 'Model', 'path' => 'models'), 'dbtable' => array('namespace' => 'Model_DbTable', 'path' => 'models/DbTable'), 'form' => array('namespace' => 'Form', 'path' => 'forms'), 'model' => array('namespace' => 'Model', 'path' => 'models'), 'plugin' => array('namespace' => 'Plugin', 'path' => 'plugins'), 'service' => array('namespace' => 'Service', 'path' => 'services'), 'helper' => array('namespace' => 'Helper', 'path' => 'helpers'), 'viewhelper' => array('namespace' => 'View_Helper', 'path' => 'views/helpers'), 'viewfilter' => array('namespace' => 'View_Filter', 'path' => 'views/filters')));
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array('basePath' => APPLICATION_PATH . 'resources/Admin', 'namespace' => 'Admin'));
        $resourceLoader->addResourceTypes(array('model' => array('namespace' => 'Model', 'path' => 'models'), 'dbtable' => array('namespace' => 'Model_DbTable', 'path' => 'models/DbTable'), 'form' => array('namespace' => 'Form', 'path' => 'forms'), 'model' => array('namespace' => 'Model', 'path' => 'models'), 'plugin' => array('namespace' => 'Plugin', 'path' => 'plugins'), 'service' => array('namespace' => 'Service', 'path' => 'services'), 'helper' => array('namespace' => 'Helper', 'path' => 'helpers'), 'viewhelper' => array('namespace' => 'View_Helper', 'path' => 'views/helpers'), 'viewfilter' => array('namespace' => 'View_Filter', 'path' => 'views/filters')));
        $this->_initErrorHandler();
        try {
            $this->_initEnvironment();
            $this->_initDatabase();
            $this->_initSession();
            $this->_initRoutes();
            $this->_initAccess();
            $this->_initView();
            $this->_initApplicationMailer();
            $this->_initDebug();
        }
        catch(Exception $e) {
            throw new App_Exception($e->getMessage());
        }
    }

    /**
    * Setup php, server environment, clean input parameters
    */
    protected function _initEnvironment()
    {
        App_Utf8::clean_globals();
        App_Input::instance();
        // Set locale information
        $this->_setLanguage();
        ini_set('log_errors', true);
        if (APPLICATION_ENV == 'development') {
            ini_set('display_errors', true);
            error_reporting(E_ALL &~ E_STRICT);
        } else {
            ini_set('display_errors', false);
            error_reporting(E_COMPILE_ERROR | E_RECOVERABLE_ERROR | E_ERROR | E_CORE_ERROR);
        }
        umask(0);
    }

    /**
    * Load system configuration
    */
    protected function _initConfiguration()
    {
        $options = new Zend_Config_Ini(VAR_PATH . 'configuration.ini', null, true);
        if (APPLICATION_ENV == 'development' and file_exists(VAR_PATH . 'configuration_development.ini')) {
            $options->merge(new Zend_Config_Ini(VAR_PATH . 'configuration_development.ini', null));
        }
        if (file_exists(VAR_PATH . 'cache/configs/settings.ini')) {
            $options->merge(new Zend_Config_Ini(VAR_PATH . 'cache/configs/settings.ini', null));
        }
        $options->setReadOnly();
        App::setConfig($options);
    }

    /**
    * Setup system error handler
    *
    * @return void
    */
    private function _initErrorHandler()
    {
        // Enable exception handling
        App_Exception::enable();
        // Enable error handling
        App_Exception_PHP::enable();
        $front = App::front();
        $front->throwExceptions(false);
        $front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(array('module' => 'default', 'controller' => 'error', 'action' => 'error')));
        $logger = new Zend_Log();
        if (APPLICATION_ENV == 'development') {
            $logger->addWriter(new Zend_Log_Writer_Firebug());
        }
        $logger->addWriter(new Zend_Log_Writer_Stream(App::config()->syspath->log . "/system_log_" . date('Y-m-d') . '.log'));
        App::setLog($logger);
    }

    /**
    * Language setup
    *
    * @return void
    */
    private function _setLanguage()
    {
        if (function_exists('date_default_timezone_set')) {
            $timezone = App::config()->project->timezone;
            // Set default timezone, due to increased validation of date settings
            // which cause massive amounts of E_NOTICEs to be generated in PHP 5.2+
            date_default_timezone_set(empty($timezone) ? date_default_timezone_get() : $timezone);
        }

        $languages = App::config()->languages->toArray();
        $default_site_language_id = $languages['default_id'];
        $default_site_locale = $languages['locale'][$default_site_language_id];
        $default_language_identificator = $languages['identificator'][$default_site_language_id];
        Zend_Locale::setDefault($default_language_identificator);
        try {
            App::setLocale(new Zend_Locale('auto'));
        }
        catch(Zend_Locale_Exception $e) {
            App::setLocale(new Zend_Locale($default_language_identificator));
        }

        $default_language_identificator = (in_array(App::locale()->getLanguage(), $languages['identificator'])) ? App::locale()->getLanguage() : $default_language_identificator;
        App::front()->getRouter()->addRoute('default', new Zend_Controller_Router_Route(':requestLang/:module/:controller/:action/*', array('module' => 'default', 'controller' => 'index', 'action' => 'index', 'requestLang' => $default_language_identificator), array('requestLang' => '\w{2}')));
        App::front()->registerPlugin(new App_Controller_Plugin_Language());
    }

    /**
    * * Database connection setup
    *
    * @return void
    */
    private function _initDatabase()
    {
        try {
            $config = App::config()->database->toArray();
            $config['driver_options'] = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true);
            App::setDb(Zend_Db::factory($config['adapter'], $config));
            $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
            $profiler->setEnabled(true);
            App::db()->setProfiler($profiler);
            Zend_Db_Table_Abstract::setDefaultMetadataCache(App_Cache::getInstance('File'));
            Zend_Db_Table::setDefaultAdapter(App::db());
            Zend_Db_Table_Abstract::setDefaultAdapter(App::db());
            App::db()->getConnection();
            App::db()->query("SET NAMES 'utf8'");
            define('DB_TABLE_PREFIX', App::config()->database->table_prefix);
        }
        catch(Zend_Db_Adapter_Exception $e) {
            throw new App_Exception($e->getMessage());
        }
    }

    /**
    * PHP Session handler setup
    *
    * @return void
    */
    private function _initSession()
    {
        Zend_Session::setOptions(App::config()->session->toArray());
        if (App::config()->session_save_handler === 'db') {
            Zend_Db_Table_Abstract::setDefaultAdapter(App::db());
            Zend_Session::setSaveHandler(new App_Session_SaveHandler_DbTable(array('name' => DB_TABLE_PREFIX . 'session', 'primary' => 'id', 'modifiedColumn' => 'modified', 'dataColumn' => 'data', 'lifetimeColumn' => 'lifetime')));
        }
        Zend_Session::start();
    }

    /**
    * View and Layout setup
    */
    private function _initView()
    {
        App::front()->registerPlugin(new App_Controller_Plugin_View());
    }

    /**
    * Setup URI routes
    *
    * @return void
    */
    private function _initRoutes()
    {
        $router = App::front()->getRouter();
        $config = new Zend_Config_Ini(VAR_PATH . 'cache/configs/routes.ini', null);
        $router->addConfig($config);
    }

    /**
    * Member access setup
    *
    * @return void
    */
    private function _initAccess()
    {
        App_Member::getInstance();
        App::front()->registerPlugin(new App_Controller_Plugin_Access());
    }

    /**
    * Init default application mailer
    *
    * @return void
    */
    private function _initApplicationMailer()
    {
        App_Mail::setDefaultTransport(App::config()->mail->toArray());
    }

    /**
    * ZendDebug panel
    *
    * @return void
    */
    private function _initDebug()
    {
        if (APPLICATION_ENV == 'development') {
            App::front()->registerPlugin(new ZFDebug_Controller_Plugin_Debug(array('plugins' => array('Variables', 'Html', 'Database' => array('adapter' => array('standard' => App::db())), 'File' => array('basePath' => APPLICATION_PATH), 'Memory', 'Time', 'Registry', 'Cache' => array('backend' => App_Cache::getInstance('File')->getBackend()), 'Exception'))));
        }
    }

    /**
    * Run the application
    *
    * Checks to see that we have a default controller directory. If not, an
    * exception is thrown.
    *
    * If so, it registers the bootstrap with the 'bootstrap' parameter of
    * the front controller, and dispatches the front controller.
    *
    * @return void
    * @throws Zend_Application_Bootstrap_Exception
    */
    public function run()
    {
        try {
            $front = $this->getResource('FrontController');
            $front->setModuleControllerDirectoryName('');
            $front->addModuleDirectory(APPLICATION_PATH . 'controllers' . DIRECTORY_SEPARATOR);
            $default = $front->getDefaultModule();
            if (null === $front->getControllerDirectory($default)) {
                throw new App_Exception('No default controller directory registered with front controller');
            }
            $front->setParam('bootstrap', $this)->setParam('prefixDefaultModule', true);
            $front->returnResponse(true);
            $response = App::front()->dispatch();
            $response->setHeader('Expires', 'Sat, 13 Apr 1985 00:30:00 GMT')->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT')->setHeader('Cache-Control', 'no-cache, must-revalidate')->setHeader('Cache-Control', 'post-check=0,pre-check=0')->setHeader('Cache-Control', 'max-age=0')->setHeader('Pragma', 'no-cache')->setHeader('Content-type', 'text/html; charset=UTF-8');
            if ($level = 9 and ini_get('output_handler') !== 'ob_gzhandler' and(int) ini_get('zlib.output_compression') === 0) {
                if ($level < 1 or $level > 9) {
                    // Normalize the level to be an integer between 1 and 9. This
                    // step must be done to prevent gzencode from triggering an error
                    $level = max(1, min($level, 9));
                }
                if (stripos(@$_SERVER ['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
                    $compress = 'gzip';
                } elseif (stripos(@$_SERVER ['HTTP_ACCEPT_ENCODING'], 'deflate') !== false) {
                    $compress = 'deflate';
                }
            }
            if (isset($compress) and $level > 0) {
                switch ($compress) {
                    case 'gzip' :
                        // Compress output using gzip
                        $response->setBody(gzencode($response->getBody(), $level));
                        break;
                    case 'deflate' :
                        // Compress output using zlib(HTTP deflate)
                        $response->setBody(gzdeflate($response->getBody(), $level));
                        break;
                }
                // This header must be sent with compressed content to prevent
                // browser caches from breaking
                $response->setHeader('Vary', 'Accept-Encoding');
                // Send the content encoding header
                $response->setHeader('Content-Encoding', $compress);
                // Sending Content-Length in CGI can result in unexpected behavior
                if (stripos(PHP_SAPI, 'cgi') === false) {
                    $response->setHeader('Content-Length', strlen($response->getBody()));
                }
            }
            $response->sendResponse();
            exit;
        }
        catch(Exception $e) {
            throw new App_Exception($e->getMessage());
        }
    }
}
