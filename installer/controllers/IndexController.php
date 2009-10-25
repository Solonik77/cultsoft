<?php
class Install_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $pages = new Install_Component_Structure();
        $this->view->headTitle('Engine installer');
        $this->view->sideNavigationMenu = new Zend_Navigation($pages->getSequence());
    }

    public function indexAction()
    {
        $this->view->pageTitle = 'Pre-installation Check';
        $this->view->pageDescription = '';
        $sys_info = new Main_Model_DashboardInfo;
        $this->view->engine_version = $sys_info->getAppVersion();
        $this->view->php_version = $sys_info->getPhpVersion();
        $this->view->php_sapi = $sys_info->getPhpServerAPI();
        $this->view->zf_version = $sys_info->getZfVersion();

        $this->view->apache_rewrite = $sys_info->isServerModuleAvailable('mod_rewrite');
        $this->view->free_disk_space = Vendor_Helper_Format::size($sys_info->getFreeDiskSpace());
        $this->view->safe_mode = $sys_info->isSafeMode();
        $this->view->memory_limit = $sys_info->getMemoryLimit();
        $this->view->disable_functions = $sys_info->getPHPDisabledFunctions();
        $this->view->max_upload = Vendor_Helper_Format::size($sys_info->getMaxUploadFilezie());
        $this->view->output_buffering = $sys_info->isOutputBufferingOn();
        $this->view->file_uploads = $sys_info->isFileUploadsOn();
        $this->view->xml_ext = $sys_info->isPHPExtensionLoded('xml');
        $this->view->zlib_ext = $sys_info->isPHPExtensionLoded('zlib');
        $this->view->mbstring_ext = $sys_info->isPHPExtensionLoded('mbstring');
        $this->view->iconv_func = $sys_info->isPHPFunctionExist('iconv');
        $this->view->os_version = $sys_info->getOsVersion();
        $this->view->form = new Install_Form_Base;
        $this->view->form->setAction('install/license');
    }

    public function licenseAction()
    {
        $this->view->pageTitle = 'License';
        $this->view->pageDescription = '';
        $this->view->form = new Install_Form_License;
        $this->view->form->setAction('install/config');
    }

    public function configAction()
    {
        $this->view->pageTitle = 'Base Configuration';
        $this->view->pageDescription = '';
        $this->view->form = new Install_Form_Config;
        $this->view->form->setAction('install/create-admin');
    }

    public function createAdminAction()
    {
        $this->view->pageTitle = 'Create admin account';
        $this->view->pageDescription = '';
        $this->view->form = new Install_Form_Administrator;
        $this->view->form->setAction('install/modules');
    }

    public function modulesAction()
    {
        $this->view->pageTitle = 'Install modules';
        $this->view->pageDescription = '';
        $this->view->form = new Install_Form_Modules;
        $this->view->form->setAction('install/finish');
    }

    public function finishAction()
    {
        $this->view->pageTitle = 'Finish installation';
        $this->view->pageDescription = '';
        $this->view->form = new Install_Form_Finish();
    }
}
