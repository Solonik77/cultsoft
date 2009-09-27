<?php
/**
 * Classes Autoloader
 *
 * @author Denysenko Dmytro
 */
require_once 'Zend/Loader/Autoloader/Interface.php';
require_once 'Zend/Loader/Autoloader/Resource.php';
class App_Loader implements Zend_Loader_Autoloader_Interface {
    const CACHE_ENABLED = FALSE;
    private static $filesInCache = array();
    private static $cacheFileList = array();
    private static $cacheFile;

    public static function init()
    {
        clearstatcache();
        self::$cacheFile = VAR_PATH . 'cache/system/code/autoloaded.php';
        if (self::CACHE_ENABLED and file_exists(self::$cacheFile)) {
            set_include_path('./');
            include_once self::$cacheFile;
            
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
            $loadedFiles = array_unique(get_included_files());
			
            foreach ($loadedFiles as $key => $file) {
        if(!strpos($file,'Zend'.DIRECTORY_SEPARATOR.'Loa') AND
         !strpos($file,'Zend'.DIRECTORY_SEPARATOR.'App') AND
		 strpos($file,'Zend'.DIRECTORY_SEPARATOR))
		{           
            
                $contents[$key] =  trim(str_replace("require_once 'Zend/", "// require_once 'Zend/", file_get_contents($file)));
				if (empty($contents[$key])) {
                    trigger_error('Failed to load contents from file ' . $file, E_USER_ERROR);
                }
                // cut opening and closing php-tags
                $contents[$key] = substr($contents[$key], 5);
                if ('?>' === substr($contents[$key], - 2)) {
                    $contents[$key] = substr_replace($contents[$key], "\n", - 2);
                }
            }
            if (!@file_put_contents( self::$cacheFile, $contents, FILE_APPEND))
            {
                trigger_error('Failed to put contents to file ' . self::$cacheFile, E_USER_ERROR);
            }
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