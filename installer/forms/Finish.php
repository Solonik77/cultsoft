<?php
class Install_Form_Finish extends App_Form
{

    function __construct()
    {
        $options = array('method'=> 'post', 'target' => '_blank', 'id' => 'form_redirect', 'action' => App::baseUri());
        parent::__construct($options);
        $this->addElement($this->createElement('submit', 'redirect_to_frontend')->setLabel('Go to frontend'));
        $this->addElement($this->createElement('submit', 'redirect_to_backend')->setLabel('Go to backend'));
    }
}