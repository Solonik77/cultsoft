<?php
/**
* Application process control file, loaded by the front controller.
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
*/
require_once(LIBRARY_PATH . 'Zend/Loader/Autoloader.php');
require_once(LIBRARY_PATH . 'app.php');
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
    protected $_language_identificator;

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
        $autoloader->registerNamespace('Vendor_');
        if ('development' === APPLICATION_ENV) {
            $autoloader->registerNamespace('ZFDebug_');
        }
        $autoloader->setFallbackAutoloader(TRUE);
        Zend_Controller_Action_HelperBroker::addPrefix('App_Controller_Action_Helper');
        $this->_initConfiguration();
        $classFileIncCache = VAR_PATH . "cache/system" . '/plugin_loader_cache_' . md5((isset($_SERVER['REMOTE_ADDR']) AND isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['REMOTE_ADDR'] . $_SERVER['SCRIPT_FILENAME'] . @php_uname('s') . ' ' . @php_uname('r') : 'Zend Framework')) . '.php';

        if (file_exists($classFileIncCache)) {
            include_once $classFileIncCache;          
        }
        Zend_Loader_PluginLoader::setIncludeFileCache($classFileIncCache);
        // Resource autoload
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array('basePath' => APPLICATION_PATH . 'modules/main' , 'namespace' => 'Main'));
        $resourceLoader->addResourceTypes(array('component' => array('namespace' => 'Component' , 'path' => 'components') , 'dbtable' => array('namespace' => 'DbTable' , 'path' => 'models/DbTable') , 'form' => array('namespace' => 'Form' , 'path' => 'forms') , 'model' => array('namespace' => 'Model' , 'path' => 'models') , 'plugin' => array('namespace' => 'Plugin' , 'path' => 'plugins') , 'service' => array('namespace' => 'Service' , 'path' => 'services') , 'helper' => array('namespace' => 'Helper' , 'path' => 'helpers') , 'viewhelper' => array('namespace' => 'View_Helper' , 'path' => 'views/helpers') , 'viewfilter' => array('namespace' => 'View_Filter' , 'path' => 'views/filters')));
        $this->_initErrorHandler();
        set_include_path(APPLICATION_PATH . 'modules/main/models/' . PATH_SEPARATOR . get_include_path());        
        try {
            $this->_initEnvironment();
            $this->_initDatabase();
            $this->_initInternationalization();
            $this->_initDate();
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
        if (version_compare(phpversion(), '5.3', '<') === true) {
            echo  '<h3>Whoops, it looks like you have an invalid PHP version.</h3></div><p>CultEngine supports PHP 5.3.0 or newer. Your vesrion is ' . phpversion() . '. <a href="http://cultsoft.org.ua/engine/install" target="">Find out</a> how to install</a> CultEngine using PHP-CGI as a work-around.</p>';
            exit;
        }
        
        App_Utf8::clean_globals();
        App_Input::instance();
        ini_set('log_errors', true);
        if ('development' === APPLICATION_ENV) {
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
        if ('development' === APPLICATION_ENV and file_exists(VAR_PATH . 'configuration_development.ini')) {
            $options->merge(new Zend_Config_Ini(VAR_PATH . 'configuration_development.ini', null));
        }
        if (file_exists(VAR_PATH . 'cache/configs/settings.ini')) {
            $options->merge(new Zend_Config_Ini(VAR_PATH . 'cache/configs/settings.ini', null));
        }
        App::addConfig($options);
    }

    /*
    * Website language and locale setup
    */
    protected function _initInternationalization()
    {
        if (function_exists('date_default_timezone_set')) {
            $timezone = App::config()->project->timezone;
            // Set default timezone, due to increased validation of date settings
            // which cause massive amounts of E_NOTICEs to be generated in PHP 5.2+
            date_default_timezone_set(empty($timezone) ? date_default_timezone_get() : $timezone);
        }

        $i18n = new App_I18n();
        $languages = $i18n->getSiteLanguages();
        $default_request_lang = 'en';
        $default_site_locale = 'en_US';
        foreach($languages as $lang) {
            if ($lang['is_active'] and $lang['is_default'] > 0) {
                $default_request_lang = $lang['request_lang'];
                $default_site_locale = $lang['locale'];
                break;
            }
        }

        Zend_Locale::setDefault($default_request_lang);
        $i18n->setLocale(new Zend_Locale($default_request_lang));
        App::setI18N($i18n);
        $this->_language_identificator = $default_request_lang;
    }

    /**
    * Setup system error handler
    *
    * @return void
    */
    protected function _initErrorHandler()
    {
        // Enable exception handling
        App_Exception::enable();
        $front = App::front();
        $front->throwExceptions(false);
        $front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(array('module' => 'main' , 'controller' => 'error' , 'action' => 'error')));
        $logger = new Zend_Log();
        if ('development' === APPLICATION_ENV) {
            $logger->addWriter(new Zend_Log_Writer_Firebug());
        }
        $logger->addWriter(new Zend_Log_Writer_Stream(VAR_PATH . "logs" . "/system_log_" . date('Y-m-d') . '.log'));
        App::setLog($logger);
    }

    /**
    * * Database connection setup
    *
    * @return void
    */
    protected function _initDatabase()
    {
        try {
            $config = App::config()->database->toArray();
            $config['persistent'] = false;
            $config['charset'] = 'utf8';
            $config['driver_options'] = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION , PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true);
            App::setDb(Zend_Db::factory(App_Utf8::strtoupper($config['adapter']), $config));
            $profiler = new Zend_Db_Profiler_Firebug('Database queries');
            $profiler->setEnabled(true);
            App::db()->setProfiler($profiler);
            Zend_Db_Table_Abstract::setDefaultMetadataCache(App_Cache::getInstance('File'));
            Zend_Db_Table_Abstract::setDefaultAdapter(App::db());
            App::db()->getConnection();
            App::db()->query("SET NAMES 'utf8'");
            defined('DB_TABLE_PREFIX') or define('DB_TABLE_PREFIX', App::config()->database->table_prefix);
        }
        catch(Zend_Db_Adapter_Exception $e) {
            throw new App_Exception($e->getMessage());
        }
    }

    /**
    * Zend Date setup
    *
    * @return void
    */
    protected function _initDate()
    {
        Zend_Date::setOptions(array('cache' => App_Cache::getInstance('permCache') , 'format_type' => 'php'));
    }

    /**
    * PHP Session handler setup
    *
    * @return void
    */
    protected function _initSession()
    {
        Zend_Session::setOptions(array(
                'remember_me_seconds' => intval(App::config()->remember_me_seconds),
                'save_path' => VAR_PATH . "session",
                'gc_probability' => 1,
                'gc_divisor' => 5000,
                'name' => "zfsession",
                'use_only_cookies' => 1
                ));
        Zend_Session::setSaveHandler(new App_Session_SaveHandler_DbTable(array('name' => DB_TABLE_PREFIX . 'session' , 'primary' => 'id' , 'modifiedColumn' => 'modified' , 'dataColumn' => 'data' , 'lifetimeColumn' => 'lifetime')));
        Zend_Session::start();
    }

    /**
    * View and Layout setup
    */
    protected function _initView()
    {
        App::front()->registerPlugin(new App_Controller_Plugin_View());
    }

    /**
    * Setup URI routes
    *
    * @return void
    */
    protected function _initRoutes()
    {
        // Change default router
        App::front()->getRouter()->addRoute('default',
            new Zend_Controller_Router_Route(':module/:controller/:action/*', array('module' => 'main' , 'controller' => 'index' , 'action' => 'index' , 'requestLang' => $this->_language_identificator)));
        // Add multilingual route
        App::front()->getRouter()->addRoute('default_multilingual',
            new Zend_Controller_Router_Route(':requestLang/:module/:controller/:action/*', array('module' => 'main' , 'controller' => 'index' , 'action' => 'index' , 'requestLang' => $this->_language_identificator), array('requestLang' => '\w{2}')));
        // Admin panel route
        App::front()->getRouter()->addRoute('backoffice',
            new Zend_Controller_Router_Route(App::config()->backoffice_path . '/:requestLang/:module/:controller/:action/*',
                array('module' => 'main' , 'controller' => 'backofficeDashboard' , 'action' => 'index' , 'requestLang' => $this->_language_identificator), array('requestLang' => '\w{2}')));
        App::front()->registerPlugin(new App_Controller_Plugin_Language());
        $router = App::front()->getRouter();
        $config = new Zend_Config_Ini(VAR_PATH . 'cache/configs/routes.ini', null);
        $router->addConfig($config);
        defined('BACKOFFICE_PATH') or define('BACKOFFICE_PATH', App::config()->backoffice_path);
    }

    /**
    * Member access setup
    *
    * @return void
    */
    protected function _initAccess()
    {
        App_Member::getInstance();
        App::front()->registerPlugin(new App_Controller_Plugin_Access());
    }

    /**
    * Init default application mailer
    *
    * @return void
    */
    protected function _initApplicationMailer()
    {
        App_Mail::setDefaultTransport(App::config()->mail->toArray());
    }

    /**
    * ZendDebug panel
    *
    * @return void
    */
    protected function _initDebug()
    {
        if ('development' === APPLICATION_ENV) {
            App::front()->registerPlugin(new ZFDebug_Controller_Plugin_Debug(
                    array('plugins' => array('Auth' => array('user' => 'email', 'role' => 'role_id'),
                            'Text',
                            'Variables' ,
                            'Database' => array('adapter' => array('standard' => App::db())) ,
                            'File' => array('basePath' => APPLICATION_PATH) ,
                            'Memory' ,
                            'Html',
                            'Time' ,
                            'Registry' ,
                            'Cache' => array('backend' => App_Cache::getInstance('File')->getBackend()) ,
                            'Exception'
                            ))));
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
            $front->setdefaultModule('main');
            $front->setModuleControllerDirectoryName('controllers');
            $front->addModuleDirectory(APPLICATION_PATH . 'modules' . DIRECTORY_SEPARATOR);
            $front->setRequest(new App_Controller_Request_Http());
            $default = $front->getDefaultModule();
            if (null === $front->getControllerDirectory($default)) {
                throw new App_Exception('No default controller directory registered with front controller');
            }
            $front->setParam('bootstrap', $this)->setParam('prefixDefaultModule', true);
            $front->returnResponse(true);
            $response = App::front()->dispatch();
            $response->setHeader('Expires', 'Sat, 13 Apr 1985 00:30:00 GMT')->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT')->setHeader('Cache-Control', 'no-cache, must-revalidate')->setHeader('Cache-Control',
                'post-check=0,pre-check=0')->setHeader('Cache-Control', 'max-age=0')->setHeader('Pragma', 'no-cache')->setHeader('Content-type', 'text/html; charset=UTF-8');

            if ($level = 9 and ini_get('output_handler') !== 'ob_gzhandler' and (int) ini_get('zlib.output_compression') === 0) {
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
                        $response->setBody(
                            gzencode($response->getBody(), $level));
                        break;
                    case 'deflate':
                        // Compress output using zlib(HTTP deflate)
                        $response->setBody(
                            gzdeflate($response->getBody(), $level));
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