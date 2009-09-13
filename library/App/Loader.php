<?php
/**
* Classes Autoloader
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
*/
class App_Loader implements Zend_Loader_Autoloader_Interface {
    const CACHE_ENABLED = false;
    private static $loadedFiles = array();
    private static $filesInCache = array();
    private static $cacheFileList = array();
    private static $cacheFile;

    public static function init()
    {
        $cacheFile = VAR_PATH . 'cache/system/autoload_cached_code.php';
        $cacheFileList = VAR_PATH . 'cache/system/autoloader_file_list.php';
        $mode = 0644;
        if (!file_exists($cacheFile)) {
            if ((!touch($cacheFile)) || (!chmod($cacheFile, 0777)) || @(!file_put_contents($cacheFile, '<?php ' . "\n"))) {
                trigger_error('Failed to initialize file ' . $cacheFile, E_USER_ERROR);
            }
        } elseif (!is_writable($cacheFile)) {
            trigger_error('File ' . $cacheFile . ' is not writable.', E_USER_ERROR);
        }
        if (!file_exists($cacheFileList)) {
            if (!file_put_contents($cacheFileList, '<?php return array();' . "\n")) {
                trigger_error('Failed to initialize file ' . $cacheFileList, E_USER_ERROR);
            }
        } elseif (!is_writable($cacheFileList)) {
            trigger_error('File ' . $cacheFileList . ' is not writable.', E_USER_ERROR);
        }

        if (self::CACHE_ENABLED and file_exists($cacheFile)) {
            include $cacheFile;
        }
        if (file_exists($cacheFileList) and self::CACHE_ENABLED) {
            self::$loadedFiles = self::$cacheFileList = include VAR_PATH . 'cache/system/autoloader_file_list.php';
        }
    }

    public function autoload($class)
    {
        if (class_exists($class, false) || interface_exists($class, false)) {
            return;
        }
        // autodiscover the path from the class name
        $file = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        self::_securityCheck($file);

        if ((!in_array($file, self::$loadedFiles))) {
            include $file;
            if (substr($file, 0, 4) == 'App' . DIRECTORY_SEPARATOR) {
                self::$loadedFiles[] = $file;
            }
        } else {
            return;
        }

        if (!class_exists($class, false) && !interface_exists($class, false)) {
            require_once 'App/Exception.php';
            throw new App_Exception("File \"$file\" does not exist or class \"$class\" was not found in the file");
        }
    }

    public static function cacheAutoload()
    {
        if (self::CACHE_ENABLED and (count(self::$cacheFileList) == 0 or (count(self::$loadedFiles) > self::$cacheFileList))) {
            $i = 0;
            $body = "<?php
            return array(\n";
            foreach(self::$loadedFiles as $class) {
                $body .= "$i => '$class',\n";
                $i++;
            }

            $body .= ");\n";
            file_put_contents(VAR_PATH . 'cache/system/autoloader_file_list.php', $body);

            $contents = array(- 1 => "\n\n// autoloaded at " . time() . "\n\n");
            foreach (self::$loadedFiles as $key => $file) {
                $contents[$key] = str_replace(array('require_once ', 'include_once '), '//', @trim(file_get_contents($file, true)));
                if (empty($contents[$key])) {
                    trigger_error('Failed to load contents from file ' . $file, E_USER_ERROR);
                }
                // cut opening and closing php-tags
                $contents[$key] = substr($contents[$key], 5);
                if ('?>' === substr($contents[$key], - 2)) {
                    $contents[$key] = substr_replace($contents[$key], "\n", - 2);
                }
            }
            // append to cache file
            if (!@file_put_contents(VAR_PATH . 'cache/system/autoload_cached_code.php', $contents, FILE_APPEND)) {
                trigger_error('Failed to put contents to file ' . $cacheFile, E_USER_ERROR);
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