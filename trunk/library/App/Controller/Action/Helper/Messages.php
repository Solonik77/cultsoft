<?php
/**
* Messages helper, translated into view
*
* This helper creates an easy method to return groupings of
* flash messages by status.
*/
class App_Controller_Action_Helper_Messages extends Zend_Controller_Action_Helper_Abstract {
    /**
    * $_messages - Messages
    *
    * @var array
    */
    private static $_messages = array();

    /**
    * Messages function.
    *
    * Takes a specially formatted array of flash messages and prepares them
    * for output.
    */
    public function messages($message = null, $status = null, $flash = false)
    {
        if ($message === null) {
            return $this;
        }
        if (is_string($message) and is_string($status) and ! empty($message) and ! empty($status)) {
            if ($flash == true) {
                $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
                $flashMessenger->addMessage(array('message' => $message , 'status' => $status));
            } else {
                self::$_messages[] = array('message' => $message , 'status' => $status);
            }
        }
    }

    public function direct($message = null, $status = null, $flash = false)
    {
        $this->messages($message, $status, $flash);
    }

    public static function getMessages()
    {
        return App_Controller_Action_Helper_Messages::$_messages;
    }
}