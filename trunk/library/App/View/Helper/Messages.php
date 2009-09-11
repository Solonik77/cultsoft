<?php
/**
 * Messages view helper
 *
 * This helper creates an easy method to return groupings of
 * flash messages by status.
 */
class App_View_Helper_Messages {
    public function messages()
    {
        return $this;
    }

    public function __toString()
    {
        $messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();
        $messages = (count($messages) > 0) ? $messages : App_Controller_Action_Helper_Messages::getMessages();
        $statMessages = array();
        $output = '';
        // If there are no messages, don't bother with this whole process.
        if (count($messages) > 0) {
            foreach($messages as $message) {
                if (! array_key_exists($message['status'], $statMessages))
                $statMessages[$message['status']] = array();
                array_push($statMessages[$message['status']], __($message['message']));
            }
            // This chunk of code formats messages for HTML output (per
            // the example in the class comments).
            foreach($statMessages as $status => $messages) {
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
                    foreach($messages as $message)
                    $output .= '<li>' . $message . '</li>';
                    $output .= '</ul>';
                }
                $output .= '</div>';
            }
        }
        return $output;
    }
}