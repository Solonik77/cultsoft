<?php
class Install_IndexController extends Zend_Controller_Action
{
    private $_session;

    /**
     * Init method
     * Create session data for installer actions
     */
    public function init()
    {
        $pages = new Install_Component_Structure();
        $this->view->headTitle('Engine installer');
        $this->view->sideNavigationMenu = new Zend_Navigation($pages->getSequence());
        $this->_session = new Zend_Session_Namespace('Installer_Session');
        $actions = $this->_session->actions;
        foreach($pages->getSequence() as $action){
            if(! isset($actions[$action['action']])){
                $actions[$action['action']] = 0;
            }
        }
        unset($actions['index']);
        $this->_session->actions = $actions;
        $action = $this->_request->getActionName();
        if(!isset($actions[$action])) { 
            $actions[$action] = 0;
        }
        if($action != 'index' and $actions[$action] === 0){
            $this->_redirect('install');
        }
    }

    /*
     * License action
     */
    public function indexAction()
    {
        $this->view->pageTitle = 'License';
        $this->view->pageDescription = '';
        $form = new Install_Form_License();
        $fileLicense = DOC_ROOT . 'LICENSE.txt';
        if(file_exists($fileLicense) and is_readable($fileLicense)){
            $form->setLicenseContent(file_get_contents($fileLicense));
        }
        if($this->_request->isPost()){
            $this->view->form = $form->compose();
            $form->populate($this->_request->getPost());
            if($form->isValid($this->_request->getPost()) != TRUE){
                unset($this->_session->actions);
                // Errors in input data
                return $this->render();
            }
            else{
                $this->_session->actions['pre-installation-check'] = 1;
                $this->_redirect('install/pre-installation-check');
            }
        }
        else{
            $this->view->form = $form->compose();
        }
    }

    /*
     * Pre-installation action
     */
    public function preInstallationCheckAction()
    {
        $this->view->pageTitle = 'Pre-installation Check';
        $this->view->pageDescription = '';
        $hasError = FALSE;
        $this->view->form = new Install_Form_Base();
        $sysInfo = App::systemInfo();
        $this->view->sysInfo = $sysInfo;
        if(version_compare($sysInfo->getPhpVersion(), $sysInfo->getRequiredPhpVersion()) < 0){
            $hasError = TRUE;
        }
        $this->view->check_php_extensions = $sysInfo->checkRequiredPHPExtensions();
        $this->view->check_filesystem = $sysInfo->checkWritableSystemDirectories();
        if(! $hasError){
            foreach($this->view->check_php_extensions as $ext){
                if(! $ext['status'] and $ext['hault']){
                    $hasError = TRUE;
                    break;
                }
            }
        }
        if(! $hasError){
            foreach($this->view->check_filesystem as $dir){
                if(! $dir['is_writable']){
                    $hasError = TRUE;
                    break;
                }
            }
        }
        if($this->_request->isPost()){
            if($this->view->form->isValid($this->_request->getPost()) and $hasError === FALSE){
                $this->_session->actions['config'] = 1;
                $this->_redirect('install/config');
            }
        }
    }

    /*
     * Set configuration action
     */
    public function configAction()
    {
        $this->view->pageTitle = 'Base Configuration';
        $this->view->pageDescription = '';
        $this->view->errors = array();
        $form = new Install_Form_Config();
        if($this->_request->isPost()){
            $form->populate($this->_request->getPost());
            if($form->isValid($this->_request->getPost())){
                $mainConfig = new Zend_Config(array(), true);
                $mainConfig->database = array();
                $mainConfig->database->adapter = "pdo_mysql";
                $mainConfig->database->host = $_POST['db_server'];
                $mainConfig->database->username = $_POST['db_username'];
                $mainConfig->database->password = $_POST['db_password'];
                $mainConfig->database->dbname = $_POST['db_name'];
                $mainConfig->database->dbname = $_POST['db_name'];
                $mainConfig->database->adapterNamespace = 'App_Db_Adapter';
                $mainConfig->database->table_prefix = $tablePrefix = $_POST['db_table_prefix'] . "_";
                $mainConfig->backoffice_path = $_POST['admin_path'];
                try{
                    $db = Zend_Db::factory(App_Utf8::strtoupper($mainConfig->database->adapter), $mainConfig->database);
                    $db->getConnection();
                    $db->query("SET NAMES 'utf8'");
                    unset($mainConfig->database->adapterNamespace);
                }
                catch(Zend_Db_Adapter_Exception $e){
                    $this->view->errors[] = "Error in database connection params: " . $e->getMessage();
                }
                if(count($this->view->errors) == 0){
                    $writer = new Zend_Config_Writer_Ini();
                    $writer->setRenderWithoutSections(true);
                    $writer->setConfig($mainConfig)->setFilename(VAR_PATH . 'initial.configuration.ini');
                    $writer->write();
                    $routesConfig = new Zend_Config(array(), true);
                    $routesConfig->signin = array();
                    $routesConfig->signin->route = "signin/*";
                    $routesConfig->signin->defaults = array();
                    $routesConfig->signin->defaults->module = "main";
                    $routesConfig->signin->defaults->controller = "profile";
                    $routesConfig->signin->defaults->action = "signin";
                    $routesConfig->signin->defaults->requestLang = "ru";
                    $routesConfig->signin->defaults->requestLangId = "1";
                    $routesConfig->view_profile = array();
                    $routesConfig->view_profile->defaults = array();
                    $routesConfig->view_profile->route = "profile/:profile_id/";
                    $routesConfig->view_profile->defaults->module = "main";
                    $routesConfig->view_profile->defaults->controller = "profile";
                    $routesConfig->view_profile->defaults->action = "view";
                    $routesConfig->view_profile->defaults->requestLang = "ru";
                    $routesConfig->view_profile->defaults->requestLangId = "1";
                    $routesConfig->view_profile->defaults->profile_id = "0";
                    $writer->setConfig($routesConfig)->setFilename(VAR_PATH . 'cache/configs/routes.ini');
                    $writer->write();
                    $settings = new Zend_Config(array(), true);
                    $settings->session_save_handler = $_POST['session_handler'];
                    $settings->system_log_threshold = 4;
                    $settings->items_per_page = 10;
                    $settings->project = array();
                    $settings->project->template = "simple";
                    $settings->project->email = $_POST['admin_email'];
                    $settings->project->timezone = "Europe/Helsinki";
                    $settings->remember_me_seconds = 864000;
                    $settings->mail = array();
                    $settings->mail->transport = "smtp";
                    $settings->mail->host = "localhost";
                    $settings->mail->username = "smtp";
                    $settings->mail->password = "smtp";
                    $settings->mail->port = 25;
                    $settings->mail->auth = "";
                    $settings->image = array();
                    $settings->image->params = array();
                    $settings->image->adapter = "GD";
                    $settings->image->params->directory = "";
                    $settings->encryption = array();
                    $settings->encryption->default = array();
                    $settings->encryption->default->key = $_POST['encryption_key'];
                    $settings->encryption->default->mode = MCRYPT_MODE_NOFB;
                    $settings->encryption->default->cipher = MCRYPT_RIJNDAEL_128;
                    $writer->setConfig($settings)->setFilename(VAR_PATH . 'cache/configs/settings.ini');
                    $writer->write();
                    App::addConfig($settings);
                    $adminData = array('login' => $_POST['admin_login'] , 'email' => $_POST['admin_email'] , 'password' => md5(md5($_POST['admin_password'])) , 'role_id' => 1 , 'is_active' => 1 , 'date_registered' => new Zend_Db_Expr('NOW()'));
                    $sqlFile = APPLICATION_PATH . 'modules/main/data/sql/mysql.sql';
                    $sqlData = file_get_contents($sqlFile);
                    $sqlData = str_ireplace(array('DROP TABLE IF EXISTS `' , 'CREATE TABLE `' , 'insert  into `', 'CONSTRAINT `', 'REFERENCES `'), array('DROP TABLE IF EXISTS `' . $tablePrefix , 'CREATE TABLE `' . $tablePrefix , 'INSERT INTO `' . $tablePrefix, 'CONSTRAINT `' . $tablePrefix,  'REFERENCES `' . $tablePrefix), $sqlData);
                    try{
                        $db->multi_query($sqlData);
                        $db->insert($tablePrefix . 'members', $adminData);
                        $this->_session->actions['modules'] = 1;
                        $this->_redirect('install/modules');
                    }
                    catch(Exception $e){
                        throw new App_Exception($e->getMessage());
                    }
                }
            }
        }
        $this->view->form = $form;
    }

    /*
     * Install modules action
     */
    public function modulesAction()
    {
        $this->view->pageTitle = 'Install modules';
        $this->view->pageDescription = '';
        $sysInfo = new Main_Model_SystemInfo();
        $modulesInfo = $sysInfo->getModuleInfoFromDataFile();
        $form = new Install_Form_Modules();
        $form->setModulesInfo($modulesInfo);
        $form->compose();
        if(isset($_POST['modules'])){
            $form->populate($this->_request->getPost());
            if($form->isValid($this->_request->getPost())){
                try{
                    $tablePrefix = DB_TABLE_PREFIX;
                    foreach($modulesInfo as $module => $info){
                        if(isset($_POST['modules'][$module]) and $_POST['modules'][$module] > 0){
                            $sqlFile = APPLICATION_PATH . 'modules/' . $module . '/data/sql/mysql.sql';
                            $sqlData = file_get_contents($sqlFile);
                            $sqlData = str_ireplace(array('DROP TABLE IF EXISTS `' , 'CREATE TABLE `' , 'insert  into `' , 'CONSTRAINT `', 'REFERENCES `'), array('DROP TABLE IF EXISTS `' . $tablePrefix , 'CREATE TABLE `' . $tablePrefix , 'INSERT INTO `' . $tablePrefix, , 'CONSTRAINT `' . $tablePrefix,  'REFERENCES `' . $tablePrefix), $sqlData);
                            App::db()->multi_query($sqlData);
                        }
                    }
                    $this->_session->actions['finish'] = 1;
                    $this->_redirect('install/finish');
                }
                catch(Exception $e){
                    throw new App_Exception($e->getMessage());
                }
            }
        }
        $this->view->form = $form;
    }

    /*
     * Finalize installation action
     */
    public function finishAction()
    {
        $this->view->pageTitle = 'Finish installation';
        $this->view->pageDescription = '';
        @rename(VAR_PATH . 'initial.configuration.ini', VAR_PATH . 'configuration.ini');
        $form = new Install_Form_Finish();
        if($this->_request->isPost()){
            if($form->isValid($this->_request->getPost())){
                if(isset($_POST['redirect_to_backend'])){
                    $this->_redirect('admin');
                }
                else{
                    $this->_redirect(App::baseUrl());
                }
            }
        }
        $this->view->form = $form;
    }
}
