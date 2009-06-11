<?php
/**
 * Application process control file, loaded by the front controller.
 *
 * $Id$
 *
 * @package Core
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/engine/license.html
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

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
    public function run ()
    {
        try {
            $front = $this->getResource('FrontController');
            $front->setModuleControllerDirectoryName("");
            $front->addModuleDirectory(APPLICATION_PATH . 'Modules' . DIRECTORY_SEPARATOR);
            $default = $front->getDefaultModule();
            if (null === $front->getControllerDirectory($default)) {
                throw new App_Exception('No default controller directory registered with front controller');
            }
            $front->setParam('bootstrap', $this)->setParam('prefixDefaultModule', true);
            $front->returnResponse(true);
            $response = App::front()->dispatch();
            $response->setHeader('Expires', 'Sat, 13 Apr 1985 00:30:00 GMT')->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT')->setHeader('Cache-Control', 'no-cache, must-revalidate')->setHeader('Cache-Control', 'post-check=0,pre-check=0')->setHeader('Cache-Control', 'max-age=0')->setHeader('Pragma', 'no-cache')->setHeader('Content-type', 'text/html; charset=' . App::config()->locale->charset);
            if ($level = App::config()->output_compression and ini_get('output_handler') !== 'ob_gzhandler' and (int) ini_get('zlib.output_compression') === 0) {
                if ($level < 1 or $level > 9) {
                    // Normalize the level to be an integer between 1 and 9. This
                    // step must be done to prevent gzencode from triggering an error
                    $level = max(1, min($level, 9));
                }
                if (stripos(@$_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
                    $compress = 'gzip';
                } elseif (stripos(@$_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate') !== false) {
                    $compress = 'deflate';
                }
            }
            if (isset($compress) and $level > 0) {
                switch ($compress) {
                    case 'gzip':
                        // Compress output using gzip
                        $response->setBody(gzencode($response->getBody(), $level));
                        break;
                    case 'deflate':
                        // Compress output using zlib (HTTP deflate)
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
        } catch (Exception $e) {
            throw new App_Exception($e->getMessage());
        }
    }

    /**
     * Constructor
     *
     * @param Zend_Application $ |Zend_Application_Bootstrap_Bootstrapper $application
     * @return void
     */
    public function __construct ($application)
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
        require_once (CORE_PATH . 'Zend/Loader/Autoloader.php');
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('App_');
        $autoloader->setFallbackAutoloader(true);
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array('basePath' => APPLICATION_PATH . 'Resources/Site' , 'namespace' => 'Site'));
        $resourceLoader->addResourceTypes(array('dbtable' => array('namespace' => 'Model_DbTable' , 'path' => 'models/DbTable') , 'form' => array('namespace' => 'Form' , 'path' => 'forms') , 'model' => array('namespace' => 'Model' , 'path' => 'models') , 'plugin' => array('namespace' => 'Plugin' , 'path' => 'plugins') , 'service' => array('namespace' => 'Service' , 'path' => 'services') , 'helper' => array('namespace' => 'Helper' , 'path' => 'helpers') , 'viewhelper' => array('namespace' => 'View_Helper' , 'path' => 'views/helpers') , 'viewfilter' => array('namespace' => 'View_Filter' , 'path' => 'views/filters')));
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array('basePath' => APPLICATION_PATH . 'Resources/Admin' , 'namespace' => 'Admin'));
        $resourceLoader->addResourceTypes(array('dbtable' => array('namespace' => 'Model_DbTable' , 'path' => 'models/DbTable') , 'form' => array('namespace' => 'Form' , 'path' => 'forms') , 'model' => array('namespace' => 'Model' , 'path' => 'models') , 'plugin' => array('namespace' => 'Plugin' , 'path' => 'plugins') , 'service' => array('namespace' => 'Service' , 'path' => 'services') , 'helper' => array('namespace' => 'Helper' , 'path' => 'helpers') , 'viewhelper' => array('namespace' => 'View_Helper' , 'path' => 'views/helpers') , 'viewfilter' => array('namespace' => 'View_Filter' , 'path' => 'views/filters')));
        $options = new Zend_Config($this->getOptions(), APPLICATION_ENV, true);
        if (APPLICATION_ENV == 'development' and file_exists(VAR_PATH . 'configuration_development.ini')) {
            $options->merge(new Zend_Config_Ini(VAR_PATH . 'configuration_development.ini', APPLICATION_ENV));
        }
        if (file_exists(VAR_PATH . 'cache/configs/settings.ini')) {
            $options->merge(new Zend_Config_Ini(VAR_PATH . 'cache/configs/settings.ini', APPLICATION_ENV));
        }
        $options->setReadOnly();
        App::setConfig($options);
        $this->_initErrorHandler();
        try {
            $this->_initEnvironment();
            $this->_initDatabase();
            $this->_initSession();
            $this->_initRoutes();
            $this->_initAccess();
            $this->_initView();
            $this->_initDebug();
        } catch (Exception $e) {
            throw new App_Exception($e->getMessage());
        }
    }

    /**
     * Setup php, server environment, clean input parameters
     */
    protected function _initEnvironment ()
    {
        V_UTF8::clean_globals();
        V_Input::instance();
        // Disable notices and "strict" errors
        $ER = error_reporting(~ E_NOTICE & ~ E_STRICT);
        // Set the user agent
        App::$user_agent = (! empty($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : '');
        // Restore error reporting
        error_reporting($ER);
        // Set locale information
        $this->_setLanguage();
        ini_set('log_errors', true);
        if (APPLICATION_ENV == 'development') {
            ini_set('display_errors', true);
            error_reporting(E_ALL & ~ E_STRICT);
        } else {
            ini_set('display_errors', false);
            error_reporting(E_COMPILE_ERROR | E_RECOVERABLE_ERROR | E_ERROR | E_CORE_ERROR);
        }
        umask(0);
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
        // Enable error handling
        App_Exception_PHP::enable();
        $front = App::front();
        $front->throwExceptions(false);
        $front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(array('module' => 'default' , 'controller' => 'error' , 'action' => 'error')));
        $logger = new Zend_Log();
        $logger->addWriter(new Zend_Log_Writer_Firebug());
        $logger->addWriter(new Zend_Log_Writer_Stream(App::Config()->syspath->log . "/system_log_" . date('Y-m-d') . '.log'));
        App::setLog($logger);
    }

    /**
     * Language setup
     *
     * @return void
     */
    private function _setLanguage ()
    {
        $system_locales = App::Config()->locales->toArray();
        foreach ($system_locales as $key => $value) {
            $default_lang_key = $key;
            $default_lang_value = $value;
            Zend_Locale::setDefault($default_lang_key);
            break;
        }
        if (function_exists('date_default_timezone_set')) {
            $timezone = App::config()->locale->timezone;
            // Set default timezone, due to increased validation of date settings
            // which cause massive amounts of E_NOTICEs to be generated in PHP 5.2+
            date_default_timezone_set(empty($timezone) ? date_default_timezone_get() : $timezone);
        }
        try {
            App::setLocale(new Zend_Locale('auto'));
        } catch (Zend_Locale_Exception $e) {
            App::setLocale(new Zend_Locale($default_lang_value));
        }
        $system_lang = (array_key_exists(App::getLocale()->getLanguage(), $system_locales)) ? App::getLocale()->getLanguage() : $default_lang_key;
        // change default router
        App::Front()->getRouter()->addRoute('default', new Zend_Controller_Router_Route(':module/:controller/:action/*', array('module' => 'default' , 'controller' => 'index' , 'action' => 'index' , 'requestLang' => $system_lang)));
        // add multilingual route
        App::Front()->getRouter()->addRoute('default_multilingual', new Zend_Controller_Router_Route(':requestLang/:module/:controller/:action/*', array('module' => 'default' , 'controller' => 'index' , 'action' => 'index' , 'requestLang' => $system_lang), array('requestLang' => '\w{2}')));
        App::front()->registerPlugin(new App_Controller_Plugin_Language());
    }

    /**
     * * Database connection setup
     *
     * @return void
     */
    private function _initDatabase ()
    {
        try {
            $config = App::config();
            App::setDb(Zend_Db::factory($config->database->adapter, $config->database->toArray()));
            $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
            $profiler->setEnabled(true);
            App::DB()->setProfiler($profiler);
            Zend_Db_Table_Abstract::setDefaultMetadataCache(App_Cache::getInstance('File'));
            Zend_Db_Table::setDefaultAdapter(App::DB());
            Zend_Db_Table_Abstract::setDefaultAdapter(App::DB());
            App::DB()->query("SET NAMES 'utf8'");
            define('DB_TABLE_PREFIX', App::config()->database->table_prefix);
        } catch (Zend_Db_Adapter_Exception $e) {
            throw new App_Exception($e->getMessage());
        }
    }

    /**
     * PHP Session handler setup
     *
     * @return void
     */
    private function _initSession ()
    {
        Zend_Session::setOptions(App::config()->session->toArray());
        if (App::config()->session_save_handler === 'db') {
            Zend_Db_Table_Abstract::setDefaultAdapter(App::DB());
            Zend_Session::setSaveHandler(new Zend_Session_SaveHandler_DbTable(array('name' => DB_TABLE_PREFIX . 'session' , 'primary' => 'id' , 'modifiedColumn' => 'modified' , 'dataColumn' => 'data' , 'lifetimeColumn' => 'lifetime')));
        }
        Zend_Session::start();
    }

    /**
     * View and Layout setup
     */
    private function _initView ()
    {
        App::front()->registerPlugin(new App_Controller_Plugin_View());
    }

    /**
     * Setup URI routes
     *
     * @return void
     */
    private function _initRoutes ()
    {
        $router = App::front()->getRouter();
        $config = new Zend_Config_Ini(VAR_PATH . 'cache/configs/routes.ini', APPLICATION_ENV);
        $router->addConfig($config);
    }

    /**
     * Member access setup
     *
     * @return void
     */
    private function _initAccess ()
    {
        App_Member::getInstance();
        App::front()->registerPlugin(new App_Controller_Plugin_Access());
    }

    /**
     * ZendDebug panel
     *
     * @return void
     */
    private function _initDebug ()
    {
        if (APPLICATION_ENV == 'development') {
            App::front()->registerPlugin(new ZFDebug_Controller_Plugin_Debug(array('plugins' => array('Variables' , 'Html' , 'Database' => array('adapter' => array('standard' => App::DB())) , 'File' => array('basePath' => APPLICATION_PATH) , 'Memory' , 'Time' , 'Registry' , 'Cache' => array('backend' => App_Cache::getInstance('File')->getBackend()) , 'Exception'))));
        }
    }
}

