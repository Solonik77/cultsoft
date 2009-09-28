<?php
/**
 * Classes Autoloader
 *
 * @author Denysenko Dmytro
 */
require_once LIBRARY_PATH . 'Zend/Loader/Autoloader/Interface.php';
require_once LIBRARY_PATH . 'Zend/Loader/Autoloader/Resource.php';
require_once LIBRARY_PATH . 'Zend/Loader/Autoloader.php';
require_once LIBRARY_PATH . 'Zend/Config.php';
require_once LIBRARY_PATH . 'Zend/Loader/PluginLoader.php';
require_once LIBRARY_PATH . 'App.php';
require_once LIBRARY_PATH . 'App/Input.php';
require_once LIBRARY_PATH . 'App/Utf8.php';
require_once LIBRARY_PATH . 'App/Exception.php';

class App_Loader implements Zend_Loader_Autoloader_Interface {
    const CACHE_ENABLED = FALSE;
    private static $baseIncludedFiles = array();
    private static $cacheFile;

    public static function init()
    {   
        clearstatcache();       
      
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->setDefaultAutoloader(array('App_Loader' , 'autoload'));
        $autoloader->setFallbackAutoloader(TRUE);
        Zend_Controller_Action_HelperBroker::addPrefix('App_Controller_Action_Helper');
        $classFileIncCache = VAR_PATH . "cache/system" . '/plugin_loader_cache_' . md5((isset($_SERVER['REMOTE_ADDR']) and isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['REMOTE_ADDR'] . $_SERVER['SCRIPT_FILENAME'] . @php_uname('s') . ' ' . @php_uname('r') : 'Zend Framework')) . '.php'; 
        if(file_exists($classFileIncCache)){
            require_once $classFileIncCache;
        }
        Zend_Loader_PluginLoader::setIncludeFileCache($classFileIncCache);
        // Resource autoload
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array('basePath' => APPLICATION_PATH . 'modules/main' , 'namespace' => 'Main'));
        $resourceLoader->addResourceTypes(array('component' => array('namespace' => 'Component' , 'path' => 'components') , 'dbtable' => array('namespace' => 'DbTable' , 'path' => 'models/DbTable') , 'form' => array('namespace' => 'Form' , 'path' => 'forms') , 'model' => array('namespace' => 'Model' , 'path' => 'models') , 'plugin' => array('namespace' => 'Plugin' , 'path' => 'plugins') , 'service' => array('namespace' => 'Service' , 'path' => 'services') , 'helper' => array('namespace' => 'Helper' , 'path' => 'helpers') , 'viewhelper' => array('namespace' => 'View_Helper' , 'path' => 'views/helpers') , 'viewfilter' => array('namespace' => 'View_Filter' , 'path' => 'views/filters')));
        
        self::$baseIncludedFiles = get_included_files();        
        self::$cacheFile = VAR_PATH . 'cache/system/autoloaded_code.php';
        if (self::CACHE_ENABLED and file_exists(self::$cacheFile)) {
            include_once self::$cacheFile;
            //set_include_path('./');
        }

        
    }

    public function autoload($class)
    {
        if (class_exists($class, false) || interface_exists($class, false)) {
            return;
        }
        // autodiscover the path from the class name
        $file = str_replace(array('_', '\\'), DIRECTORY_SEPARATOR, $class) . '.php';
        self::_securityCheck($file);
        include $file;
        if (!class_exists($class, false) && !interface_exists($class, false)) {
            throw new App_Exception("File \"$file\" does not exist or class \"$class\" was not found in the file");
        }
    }

    public static function cacheAutoload()
    {
        if (self::CACHE_ENABLED AND !file_exists(self::$cacheFile))
        {
            touch(self::$cacheFile);
            chmod(self::$cacheFile, 0777);
            file_put_contents(self::$cacheFile, '<?php ' . "\n");

            $contents = array(- 1 => "\n\n// autoloaded at " . time() . "\n\n");
            $loadedFiles = get_included_files();	
            foreach ($loadedFiles as $key => $file) {
        if(!in_array($file, self::$baseIncludedFiles) AND !strpos($file,'Zend'.DIRECTORY_SEPARATOR.'Loa') AND
         !strpos($file,'Zend'.DIRECTORY_SEPARATOR.'App') AND
		 strpos($file,'Zend'.DIRECTORY_SEPARATOR))
		{           
            
                $contents[$key] = trim(str_replace(array("Zend_Loader::loadClass(","require_once 'Zend/", "require_once('Zend/"), array("App_Loader::loadClass(","// require_once 'Zend/", "// require_once('Zend/" ), file_get_contents($file)));
				if (empty($contents[$key])) {
                    trigger_error('Failed to load contents from file ' . $file, E_USER_ERROR);
                }
                // cut opening and closing php-tags
                $contents[$key] = substr($contents[$key], 5);
                if ('?>' === substr($contents[$key], - 2)) {
                    $contents[$key] = substr_replace($contents[$key], "\n", - 2);
                }
                
                $class = str_replace(array(LIBRARY_PATH, DIRECTORY_SEPARATOR, '.php'), array('', '_', ''), $file);
                $contents[$key] = "if(!class_exists('" . $class . "') || !interface_exists('" . $class . "')){\n" . $contents[$key]  . "\n } \n";
            }
           } 
            if (!@file_put_contents( self::$cacheFile, $contents, FILE_APPEND))
            {
                trigger_error('Failed to put contents to file ' . self::$cacheFile, E_USER_ERROR);
            }
            
        }
    }

    /**
     * Ensure that filename does not contain exploits
     *
     * @param string $filename
     * @return void
     * @throws Zend_Exception
     */
    protected static function _securityCheck($filename)
    {
        /**
         * Security check
         */
        if (preg_match('/[^a-z0-9\\/\\\\_.:-]/i', $filename)) {
            require_once 'App/Exception.php';
            throw new App_Exception('Security check: Illegal character in filename');
        }
    }
}