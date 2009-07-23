<?php
/**
* FlashMessages view helper
*
* This helper creates an easy method to return groupings of
* flash messages by status.
*/
class App_View_Helper_FlashMessage {
    /**
    * flashMessages function.
    *
    * Takes a specially formatted array of flash messages and prepares them
    * for output.
    *
    * SAMPLE INPUT (in, say, a controller):
    *     $this->_flashMessenger->addMessage(array('message' => 'Success message #1', 'status' => 'success'));
    *     $this->_flashMessenger->addMessage(array('message' => 'Error message #1', 'status' => 'error'));
    *     $this->_flashMessenger->addMessage(array('message' => 'Warning message #1', 'status' => 'warning'));
    *     $this->_flashMessenger->addMessage(array('message' => 'Success message #2', 'status' => 'success'));
    *
    * SAMPLE OUTPUT (in a view):
    *     <div class="success">
    *         <ul>
    *             <li>Success message #1</li>
    *             <li>Success message #2</li>
    *         </ul>
    *     </div>
    *     <div class="error">Error message #1</div>
    *     <div class="warning">Warning message #2</div>
    *
    * @access public
    * @param  $translator An optional instance of Zend_Translate
    * @return string HTML of output messages
    */
    public function flashMessage($message = null, $status = 'success')
    {
        if ($message === null) {
            return $this;
        }

        if (is_string($message) AND is_string($status) AND !empty($message) AND !empty($status)) {
            $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
            $flashMessenger->addMessage(array('message' => $message, 'status' => $status));
        }
    }

    public function __toString()
    {
        // Set up some variables, including the retrieval of all flash messages.
        $messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();
        $statMessages = array();
        $output = '';
        // If there are no messages, don't bother with this whole process.
        if (count($messages) > 0) {
            foreach ($messages as $message) {
                if (!array_key_exists($message['status'], $statMessages))
                    $statMessages[$message['status']] = array();
                array_push($statMessages[$message['status']], App::translate()->_($message['message']));
            }
            // This chunk of code formats messages for HTML output (per
            // the example in the class comments).
            foreach ($statMessages as $status => $messages) {
                $output .= '<div class="' . $status . '">';
                // If there is only one message to look at, we don't need to deal with
                // ul or li - just output the message into the div.
                if (count($messages) == 1) {
                    $output .= $messages[0];
                }
                // If there are more than one message, format it in the fashion of the
                // sample output above.
                else {
                    $output .= '<ul>';
                    foreach ($messages as $message)
                    $output .= '<li>' . $message . '</li>';
                    $output .= '</ul>';
                }

                $output .= '</div>';
            }
        }
        return $output;
    }
}
