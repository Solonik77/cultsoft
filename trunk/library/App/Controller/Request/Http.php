<?php
class App_Controller_Request_Http extends Zend_Controller_Request_Http {
    public function __construct($uri = null)
    {
        parent::__construct($uri);
    }
}