<?php
/**
* * Zend_Controller_Request_Abstract
*/
require_once 'Zend/Controller/Request/Abstract.php';
/**
* * Zend_Uri
*/
require_once 'Zend/Uri.php';
class App_Controller_Request_Http extends Zend_Controller_Request_Http {
    public function __construct($uri = null)
    {
        parent::__construct($uri);
    }
}