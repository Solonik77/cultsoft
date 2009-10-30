<?php
class Main_Model_SystemInfo {
    private $db;
    private $errors = array();

    public function __construct()
    {
        $this->db = App::db();
    }

    public function getPhpVersion()
    {
        return phpversion();
    }

    public function getAppVersion()
    {
        return App::getVersion();
    }

    public function getPhpServerAPI()
    {
        return php_sapi_name();
    }

    public function getZfVersion()
    {
        return Zend_Version::VERSION;
    }

    public function getSqlAdapter()
    {
        return App_UTF8::strtoupper(App::config()->database->adapter);
    }

    public function getSqlVersion()
    {
        switch ($this->getSqlAdapter()) {
            default:
                return $this->db->fetchOne('SELECT VERSION()');
                break;
        }
    }

    public function getImageAdapter()
    {
        return App::config()->image->adapter;
    }

    public function getImageAdapterVersion()
    {
        switch ($this->getImageAdapter()) {
            default:
                ob_start();
                phpinfo(8);
                $module_info = ob_get_contents();
                ob_end_clean();
                if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $module_info, $matches)) {
                    $gdversion = $matches[1];
                } else {
                    $gdversion = 0;
                }
                return $gdversion;
                break;
        }
    }

    public function isServerModuleAvailable($module)
    {
        $return = false;
        if (function_exists('apache_get_modules')) {
            if (in_array($module, apache_get_modules())) {
                $return = true;
            }
        }
        return $return;
    }

    public function getFreeDiskSpace()
    {
        $dfs = @disk_free_space(".");
        return $dfs;
    }

    public function isSafeMode()
    {
        return (bool) (@ini_get('safe_mode') == 1);
    }


    public function getMemoryLimit()
    {
        return (@ini_get('memory_limit') != '') ? (int) @ini_get('memory_limit') : false;
    }

    public function getPHPDisabledFunctions()
    {
        return (strlen(ini_get('disable_functions')) > 1) ? str_replace(',', ', ', @ini_get('disable_functions')) : false;
    }

    public function getMaxUploadFilezie()
    {
        $maxupload = str_replace(array('M' , 'm'), '', @ini_get('upload_max_filesize'));
        return $maxupload * 1024 * 1024;
    }

    public function isOutputBufferingOn()
    {
        return (@ini_get('output_buffering')) ? true : false;
    }

    public function isFileUploadsOn()
    {
        return (@ini_get('file_uploads')) ? true : false;
    }

    public function isPHPFunctionExist($function)
    {
        return (@function_exists($function)) ? true : false;
    }

    public function isPHPExtensionLoded($extension)
    {
        return (@extension_loaded($extension)) ? true : false;
    }

    public function getOsVersion()
    {
        return @php_uname('s') . ' ' . @php_uname('r');
    }

    public function getRequiredExtensions()
    {
        return  array(

        array( 'fancy_name'		=> "DOM XML Handling",
	   'extension_name'	=> "libxml2",
	   'helpurl'		=> "http://www.php.net/manual/en/dom.setup.php",
	   'testfor'		=> 'dom',
	   'no_hault'		=> false ),

        array( 'fancy_name'		=> "GD Library",
	   'extension_name'	=> "gd",
	   'helpurl'		=> "http://www.php.net/manual/en/image.setup.php",
	   'testfor'		=> 'gd',
	   'no_hault'		=> true ),


        array( 'fancy_name'		=> "Reflection Class",
	   'extension_name'	=> "Reflection",
	   'helpurl'		=> "http://uk2.php.net/manual/en/language.oop5.reflection.php",
	   'testfor'		=> 'Reflection',
	   'no_hault'		=> false ),
        );
    }

    public function checkPHPExtentions()
    {
        $extensions    = get_loaded_extensions();
        $extensionsOK  = TRUE;
        $extensionData = array();
        $requiredExtensions = $this->getRequiredExtensions();
        if ( is_array( $requiredExtensions ) )
        {
            foreach( $requiredExtensions as $data )
            {
                if ( ! in_array( $data['testfor'], $extensions ) )
                {
                    //-----------------------------------------
                    // Added 'no_hault' key which will show a
                    // warning but not prohibit installation
                    //-----------------------------------------
                    	
                    if( $data['no_hault'] )
                    {
                        $data['_ok']	= 1;
                        $extensionsOK	= 1;
                    }
                    else
                    {
                        $extensionsOK = FALSE;
                    }
                }
                else
                {
                    $data['_ok'] = TRUE;
                }

                $extensionData[] = $data;
            }
        }
        return  $extensionData;
    }
    public function isValidFilesystem()
    {
        return TRUE;
    }
}