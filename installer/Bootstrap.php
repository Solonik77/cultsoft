<?php
/**
 * Application process control file, loaded by the front controller.
 *
 * @author Denysenko Dmytro
 */
define('INSTALLER_RUN', TRUE);
define('TIME_NOW', time());
require_once LIBRARY_PATH . 'App/Loader.php';
final class Bootstrap
{
    public function __construct ()
    {
       
        App_Loader::init();
        $this->_initErrorHandler();
        $this->_initRoutes();
        $this->_initLayout();
        $this->_initDebug();
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
        $front = App::front();
        $front->throwExceptions(false);
        $front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(array('module' => 'installer' , 'controller' => 'error' , 'action' => 'error')));
        $logger = new Zend_Log();
        if ('development' === APPLICATION_ENV) {
            $logger->addWriter(new Zend_Log_Writer_Firebug());
        }
        $logger->addWriter(new Zend_Log_Writer_Stream(VAR_PATH . "logs" . "/installer_log_" . date('Y-m-d') . '.log'));
        App::setLog($logger);
    }
    /**
     * ZendDebug panel
     *
     * @return void
     */
    private function _initDebug ()
    {
        if ('development' === APPLICATION_ENV) {
            App::front()->registerPlugin(new ZFDebug_Controller_Plugin_Debug(array('plugins' => array('Auth' => array('user' => 'email' , 'role' => 'role_id') , 'Text' , 'Variables' , 'Database' => array('adapter' => array('standard' => App::db())) , 'File' => array('basePath' => APPLICATION_PATH) , 'Memory' , 'Html' , 'Time' , 'Registry' , 'Cache' => array('backend' => App_Cache::getInstance('File')->getBackend()) , 'Exception'))));
        }
    }
    /**
     * Setup URI routes
     *
     * @return void
     */
    private function _initRoutes ()
    {
        App::front()->getRouter()->addRoute('default', new Zend_Controller_Router_Route(':module/:controller/:action/*', array('module' => 'install' , 'controller' => 'index' , 'action' => 'index')));
    }
    
    /**
     * Layout setup
     */
    private function _initLayout()
    {
      $layout = Zend_Layout::startMvc(array('layoutPath' => INSTALLER_PATH . 'views/layouts/' , 'layout' => 'installer' , 'mvcSuccessfulActionOnly' => FALSE));
      $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
    }
    
    public function run()
    {
        try {
            $front = App::front();
            $front->setDefaultModule('installer');
            $front->setControllerDirectory(INSTALLER_PATH . 'controllers');
            $default = $front->getDefaultModule();
            if (null === $front->getControllerDirectory($default)) {
                throw new App_Exception('No default controller directory registered with front controller');
            }
            $front->setParam('prefixDefaultModule', true);
            $front->returnResponse(true);
            $response = App::front()->dispatch();
            $response->setHeader('Expires', 'Sat, 13 Apr 1985 00:30:00 GMT')->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT')->setHeader('Cache-Control', 'no-cache, must-revalidate')->setHeader('Cache-Control', 'post-check=0,pre-check=0')->setHeader('Cache-Control', 'max-age=0')->setHeader('Pragma', 'no-cache')->setHeader('Content-type', 'text/html; charset=UTF-8');
            $response->sendResponse();
            exit();
        } catch (Exception $e) {
            throw new App_Exception($e->getMessage());
        }
    }
}