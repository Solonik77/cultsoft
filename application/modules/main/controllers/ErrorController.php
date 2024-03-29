<?php
/**
 * Default system errors controller.
 * This controller used for view system error
 *                information pages in development environment and simple warnings in production.
 *
 * @author Denysenko Dmytro
 */
class Main_ErrorController extends App_Controller_Action {
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
            case 'EXCEPTION_SITE_404':
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }
        $this->view->exception = $errors->exception;
        $this->view->request = $errors->request;
        App::log($errors->exception->getMessage(), Zend_Log::ERR);
    }
    /*
     * Access deny action
     */
    public function denyAction()
    {
        $this->getResponse()->setHttpResponseCode(401);
        $this->view->message = 'Access deny';
    }
}