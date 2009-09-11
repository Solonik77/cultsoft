<?php

class App_View_Filter_ObfuscateMailto implements Zend_Filter_Interface
{
    /**
     * Regex to find mailto links
     * Note that it only finds the links,
     * it does not care about the validity of the email-address
     *
     * @var string
     */
    protected $_pattern = '/<a(.*)href="mailto:([^"]*)"(.*)>(.*)<\/a>/iu';

    /**
     * Defined in Zend_Filter_Interface
     *
     * @param	mixed	$value	Value to filter
     * @return	mixed			Filtered value
     */
    public function filter($value)
    {
        return preg_replace_callback($this->_pattern, array($this, '_obfuscate'), $value);
    }

    /**
     * Obfuscates found mailto links to encoded javascript
     *
     * @param	array	$matches	Matches from regex
     * @return	string				Obfuscated mailto link
     */
    protected function _obfuscate(array $matches)
    {
        // javascript to be executed
        $javascript = "document.write('". $matches[0] ."')";

        // empty string that will hold encoded version of javascript
        $encodedJavascript = '';

        // encode each character from $javascript to hex and append it to $encodedJavascript
        for($i = 0; $i < strlen($javascript); $i++) {
            $encodedJavascript .= '%' . bin2hex($javascript[$i]);
        }

        // return as html script-tag
        return '<script type="text/javascript">eval(unescape(\''. $encodedJavascript .'\'))</script>';
    }
}