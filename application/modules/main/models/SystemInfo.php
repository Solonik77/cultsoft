<?php
class Main_Model_SystemInfo
{
    private $db;
    private $errors = array();
    private $_siteModulesInfo = array();

    public function __construct()
    {
        $this->db = App::db();
    }

    public function getPhpVersion()
    {
        return phpversion();
    }

    public function getRequiredPhpVersion()
    {
        return '5.2.4';
    }

    public function getRequiredMySQLVersion()
    {
        return '4.1.0';
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
        switch($this->getSqlAdapter()){
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
        switch($this->getImageAdapter()){
            default:
                ob_start();
                phpinfo(8);
                $module_info = ob_get_contents();
                ob_end_clean();
                if(preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $module_info, $matches)){
                    $gdversion = $matches[1];
                }
                else{
                    $gdversion = 0;
                }
                return $gdversion;
                break;
        }
    }

    public function isServerModuleAvailable($module)
    {
        $return = false;
        if(function_exists('apache_get_modules')){
            if(in_array($module, apache_get_modules())){
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

    public function getRequiredPHPExtensions()
    {
        return array(array('fancy_name' => "Iconv" , 'extension_name' => "iconv" , 'ext_web_url' => "http://php.net/manual/en/book.iconv.php" , 'check_extension' => 'iconv' , 'hault' => TRUE) , array('fancy_name' => "Mcrypt" , 'extension_name' => "mcrypt" , 'ext_web_url' => "http://php.net/manual/en/book.mcrypt.php" , 'check_extension' => 'mcrypt' , 'hault' => TRUE) , array('fancy_name' => "PDO" , 'extension_name' => "PDO" , 'ext_web_url' => "http://php.net/manual/en/book.pdo.php" , 'check_extension' => 'PDO' , 'hault' => TRUE) , array('fancy_name' => "PDO MySQL" , 'extension_name' => "iconv" , 'ext_web_url' => "http://php.net/manual/en/ref.pdo-mysql.php" , 'check_extension' => 'pdo_mysql' , 'hault' => TRUE) , array('fancy_name' => "SimpleXML Handling" , 'extension_name' => "SimpleXML" , 'ext_web_url' => "http://php.net/manual/en/book.simplexml.php" , 'check_extension' => 'SimpleXML' , 'hault' => false) , array('fancy_name' => "Gettext" , 'extension_name' => "gettext" , 'ext_web_url' => "http://php.net/manual/en/book.gettext.php" , 'check_extension' => 'gettext' , 'hault' => false) , array('fancy_name' => "GD Library" , 'extension_name' => "gd" , 'ext_web_url' => "http://www.php.net/manual/en/image.setup.php" , 'check_extension' => 'gd' , 'hault' => true) , array('fancy_name' => "Reflection Class" , 'extension_name' => "Reflection" , 'ext_web_url' => "http://uk2.php.net/manual/en/language.oop5.reflection.php" , 'check_extension' => 'Reflection' , 'hault' => true));
    }

    public function checkRequiredPHPextensions()
    {
        $extensions = get_loaded_extensions();
        $extensionData = array();
        $requiredExtensions = $this->getRequiredPHPExtensions();
        if(is_array($requiredExtensions)){
            foreach($requiredExtensions as $data){
                if(! in_array($data['check_extension'], $extensions)){
                    $data['status'] = FALSE;
                }
                else{
                    $data['status'] = TRUE;
                }
                $extensionData[] = $data;
            }
        }
        return $extensionData;
    }

    public function getWritableSystemDirectories()
    {
        return array(VAR_PATH , VAR_PATH . 'cache/system/' , VAR_PATH . 'cache/configs/' , VAR_PATH . 'indexes/' , VAR_PATH . 'logs/' , VAR_PATH . 'session/' , VAR_PATH . 'cache/system/' , STATIC_PATH . 'uploads/');
    }

    public function getModuleInfoFromDataFile($module = null)
    {
        $dirModules = glob(APPLICATION_PATH . 'modules/*', GLOB_ONLYDIR);
        $info = array();
        foreach($dirModules as $fileInfo){
            $key = end(explode('/', $fileInfo));
            $fileInfo .= '/data/information.php';
            if(file_exists($fileInfo) and is_readable($fileInfo)){
                $info[$key] = include ($fileInfo);
            }
        }
        if($module){
            return $info[$module];
        }
        else{
            return $info;
        }
    }
    
    public function getModulesInfo($module = null)
    {
       if($this->_siteModulesInfo == null){
            $data = $this->db->fetchAll('SELECT * FROM ' . DB_TABLE_PREFIX . 'site_modules');
            $siteModulesInfo = array();            
            foreach($data as $value)
            {
                $siteModulesInfo[$value['module']] = $value;
            }
            
            $this->_siteModulesInfo = $siteModulesInfo;
       }
       return $this->_siteModulesInfo;
    }

    public function checkWritableSystemDirectories()
    {
        $directories = $this->getWritableSystemDirectories();
        $dirsData = array();
        foreach($directories as $dir){
            $dir = str_replace('\\', '/', $dir);
            if(is_dir($dir) and is_writable($dir)){
                $dirsData[] = array('path' => $dir , 'is_writable' => TRUE);
            }
            else{
                $dirsData[] = array('path' => $dir , 'is_writable' => FALSE);
            }
        }
        return $dirsData;
    }
}