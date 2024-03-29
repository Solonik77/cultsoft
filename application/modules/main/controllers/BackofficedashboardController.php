<?php
/**
 * Admin Index controller.
 * This is dashboard controller.
 *
 * @author Denysenko Dmytro
 */
class Main_BackofficeDashboardController extends App_Controller_Action {
    CONST BACKOFFICE_CONTROLLER = true;

    public function init()
    {
        $this->view->pageTitle = __('Dashboard');
        $this->view->pageDescription = __('Fast access links, statistics, system information');
        $this->view->headTitle($this->view->pageTitle);
    }
    /*
     * System information, statistic data
     */
    public function indexAction()
    {
        $sys_info = App::systemInfo();
        $this->view->engine_version = $sys_info->getAppVersion();
        $this->view->php_version = $sys_info->getPhpVersion();
        $this->view->php_sapi = $sys_info->getPhpServerAPI();
        $this->view->zf_version = $sys_info->getZfVersion();
        $this->view->sql_adapter = $sys_info->getSqlAdapter();
        $this->view->sql_version = $sys_info->getSqlVersion();
        $this->view->image_adapter = $sys_info->getImageAdapter();
        $this->view->image_adapter_version = $sys_info->getImageAdapterVersion();
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
    }
    /*
     * Edit system configuration action
     */
    public function settingsAction()
    {
        $model = new Main_Model_Settings();
        $settings = $model->getSettings()->toArray();
        $form = new Main_Form_Backoffice_Settings();
        $form->setFields($settings)->compose();

        $this->view->settingsForm = $form;
    }
}