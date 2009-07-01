<?php
/**
* Input library.
*
* $Id$
*
* @package Core
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
class V_Input {
    // Enable or disable automatic XSS cleaning
    protected $use_xss_clean = false;
    // Are magic quotes enabled?
    protected $magic_quotes_gpc = false;
    // IP address of current user
    public $ip_address;
    // IP address of current user
    public $user_agent;
    // Input singleton
    protected static $instance;

    /**
    * Retrieve a singleton instance of Input. This will always be the first
    * created instance of this class.
    *
    * @return object
    */
    public static function instance()
    {
        if (V_Input::$instance === null) {
            // Create a new instance
            return new V_Input();
        }
        return V_Input::$instance;
    }

    /**
    * Sanitizes global GET, POST and COOKIE data. Also takes care of
    * magic_quotes and register_globals, if they have been enabled.
    *
    * @return void
    */
    public function __construct()
    {
        // Use XSS clean?
        $this->use_xss_clean = (bool) true;
        if (V_Input::$instance === null) {
            // magic_quotes_runtime is enabled
            if (get_magic_quotes_runtime()) {
                set_magic_quotes_runtime(0);
                App::Log('Disable magic_quotes_runtime! It is evil and deprecated: http://php.net/magic_quotes',
                    Zend_Log::DEBUG);
            }
            // magic_quotes_gpc is enabled
            if (get_magic_quotes_gpc()) {
                $this->magic_quotes_gpc = true;
                App::Log('Disable magic_quotes_gpc! It is evil and deprecated: http://php.net/magic_quotes',
                    Zend_Log::DEBUG);
            }
            // register_globals is enabled
            if (ini_get('register_globals')) {
                if (isset($_REQUEST['GLOBALS'])) {
                    // Prevent GLOBALS override attacks
                    exit('Global variable overload attack.');
                }
                // Destroy the REQUEST global
                $_REQUEST = array();
                // These globals are standard and should not be removed
                $preserve = array('GLOBALS' , '_REQUEST' , '_GET' , '_POST' , '_FILES' , '_COOKIE' , '_SERVER' , '_ENV' , '_SESSION');
                // This loop has the same effect as disabling register_globals
                foreach(array_diff(
                        array_keys($GLOBALS),
                        $preserve) as $key) {
                    global $$key;
                    $$key = null;
                    // Unset the global variable
                    unset($GLOBALS[$key],
                        $$key);
                }
                // Warn the developer about register globals
                App::Log('Disable magic_quotes_gpc! It is evil and deprecated: http://php.net/magic_quotes',
                    Zend_Log::DEBUG);
            }
            // Disable notices and "strict" errors
            $ER = error_reporting(~ E_NOTICE &~ E_STRICT);
            // Set the user agent
            $this->user_agent = (! empty($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : '');
            if (v_utf8::strlen($this->user_agent) > 255) {
                $this->user_agent = v_utf8::substr($this->user_agent,
                    0,
                    255);
            }
            // Restore error reporting
            error_reporting($ER);
            if (is_array($_GET)) {
                foreach($_GET as $key => $val) {
                    // Sanitize $_GET
                    $_GET[$this->clean_input_keys($key)] = $this->clean_input_data($val);
                }
            } else {
                $_GET = array();
            }
            if (is_array($_POST)) {
                foreach($_POST as $key => $val) {
                    // Sanitize $_POST
                    $_POST[$this->clean_input_keys($key)] = $this->clean_input_data($val);
                }
            } else {
                $_POST = array();
            }
            if (is_array($_COOKIE)) {
                foreach($_COOKIE as $key => $val) {
                    // Ignore special attributes in RFC2109 compliant cookies
                    if ($key == '$Version' or $key == '$Path' or $key == '$Domain')
                        continue;
                    // Sanitize $_COOKIE
                    $_COOKIE[$this->clean_input_keys($key)] = $this->clean_input_data($val);
                }
            } else {
                $_COOKIE = array();
            }
            // Create a singleton
            V_Input::$instance = $this;
            App::Log('Global GET, POST and COOKIE data sanitized',
                Zend_Log::DEBUG);
        }
    }

    /**
    * Fetch an item from the $_GET array.
    *
    * @param string $ key to find
    * @param mixed $ default value
    * @param boolean $ XSS clean the value
    * @return mixed
    */
    public function get($key = array(), $default = null, $xss_clean = false)
    {
        return $this->search_array($_GET, $key,
            $default, $xss_clean);
    }

    /**
    * Fetch an item from the $_POST array.
    *
    * @param string $ key to find
    * @param mixed $ default value
    * @param boolean $ XSS clean the value
    * @return mixed
    */
    public function post($key = array(), $default = null,
        $xss_clean = false)
    {
        return $this->search_array($_POST, $key,
            $default, $xss_clean);
    }

    /**
    * Fetch an item from the $_COOKIE array.
    *
    * @param string $ key to find
    * @param mixed $ default value
    * @param boolean $ XSS clean the value
    * @return mixed
    */
    public function cookie($key = array(), $default = null,
        $xss_clean = false)
    {
        return $this->search_array($_COOKIE,
            $key, $default, $xss_clean);
    }

    /**
    * Fetch an item from the $_SERVER array.
    *
    * @param string $ key to find
    * @param mixed $ default value
    * @param boolean $ XSS clean the value
    * @return mixed
    */
    public function server($key = array(), $default = null,
        $xss_clean = false)
    {
        return $this->search_array($_SERVER,
            $key, $default, $xss_clean);
    }

    /**
    * Fetch an item from a global array.
    *
    * @param array $ array to search
    * @param string $ key to find
    * @param mixed $ default value
    * @param boolean $ XSS clean the value
    * @return mixed
    */
    protected function search_array($array, $key,
        $default = null, $xss_clean = false)
    {
        if ($key === array())
            return $array;
        if (! isset($array[$key]))
            return $default;
        // Get the value
        $value = $array[$key];
        if ($this->use_xss_clean === false and $xss_clean === true) {
            // XSS clean the value
            $value = $this->xss_clean($value);
        }
        return $value;
    }

    /**
    * Fetch the IP Address.
    *
    * @return string
    */
    public function ip_address()
    {
        if ($this->ip_address !== null)
            return $this->ip_address;
        // Server keys that could contain the client IP address
        $keys = array('HTTP_X_FORWARDED_FOR' , 'HTTP_CLIENT_IP' , 'REMOTE_ADDR');
        foreach($keys as $key) {
            if ($ip = $this->server($key)) {
                $this->ip_address = $ip;
                // An IP address has been found
                break;
            }
        }
        if ($comma = strrpos($this->ip_address,
                ',') !== false) {
            $this->ip_address = substr($this->ip_address,
                $comma + 1);
        }
        if (! V_Helper_Valid::ip($this->ip_address)) {
            // Use an empty IP
            $this->ip_address = '0.0.0.0';
        }
        return $this->ip_address;
    }

    /**
    * Fetch the User Agent.
    *
    * @return string
    */
    public function user_agent()
    {
        return $this->user_agent;
    }

    /**
    * Clean cross site scripting exploits from string.
    * HTMLPurifier may be used if installed, otherwise defaults to built in method.
    * Note - This function should only be used to deal with data upon submission.
    * It's not something that should be used for general runtime processing
    * since it requires a fair amount of processing overhead.
    *
    * @param string $ data to clean
    * @param string $ xss_clean method to use ('htmlpurifier' or defaults to built-in method)
    * @return string
    */
    public function xss_clean($data, $tool = null)
    {
        if ($tool === null) {
            // Use the default tool
            $tool = 'default';
        }
        if (is_array($data)) {
            foreach($data as $key => $val) {
                $data[$key] = $this->xss_clean($val,
                    $tool);
            }
            return $data;
        }
        // Do not clean empty strings
        if (trim($data) === '') {
            return $data;
        }
        if ($tool === true) {
            $tool = 'default';
        } elseif ($tool == ! method_exists($this,
                'xss_filter_' . $tool)) {
            App::Log('Unable to use Input::xss_filter_' . $tool . '(), no such method exists',
                Zend_Log::ERR);
            $tool = 'default';
        }
        $method = 'xss_filter_' . $tool;
        return $this->$method($data);
    }

    /**
    * Default built-in cross site scripting filter.
    *
    * @param string $ data to clean
    * @return string
    */
    protected function xss_filter_default($data)
    {
        // http://svn.bitflux.ch/repos/public/popoon/trunk/classes/externalinput.php
        // +----------------------------------------------------------------------+
        // | Copyright (c) 2001-2006 Bitflux GmbH                                 |
        // +----------------------------------------------------------------------+
        // | Licensed under the Apache License, Version 2.0 (the "License");      |
        // | you may not use this file except in compliance with the License.     |
        // | You may obtain a copy of the License at                              |
        // | http://www.apache.org/licenses/LICENSE-2.0                           |
        // | Unless required by applicable law or agreed to in writing, software  |
        // | distributed under the License is distributed on an "AS IS" BASIS,    |
        // | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or      |
        // | implied. See the License for the specific language governing         |
        // | permissions and limitations under the License.                       |
        // +----------------------------------------------------------------------+
        // | Author: Christian Stocker <chregu@bitflux.ch>                        |
        // +----------------------------------------------------------------------+
        // * Changed double quotes to single quotes, changed indenting and spacing
        // * Removed magic_quotes stuff
        // * Increased regex readability:
        // * Used delimeters that aren't found in the pattern
        // * Removed all unneeded escapes
        // * Deleted U modifiers and swapped greediness where needed
        // * Increased regex speed:
        // * Made capturing parentheses non-capturing where possible
        // * Removed parentheses where possible
        // * Split up alternation alternatives
        // * Made some quantifiers possessive
        // Fix &entity\n;
        $data = str_replace(
            array('&amp;' , '&lt;' , '&gt;'),
            array('&amp;amp;' , '&amp;lt;' , '&amp;gt;'),
            $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data,
            ENT_COMPAT, 'UTF-8');
        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu',
            '$1>', $data);
        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu',
            '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu',
            '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u',
            '$1=$2nomozbinding...', $data);
        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i',
            '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i',
            '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu',
            '$1>', $data);
        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i',
                '', $data);
        } while ($old_data !== $data);
        return $data;
    }

    /**
    * HTMLPurifier cross site scripting filter. This version assumes the
    * existence of the standard htmlpurifier library, and is set to not tidy
    * input.
    *
    * @param string $ data to clean
    * @return string
    */
    protected function xss_filter_htmlpurifier($data)
    {
        /**
        *
        * @todo License should go here, http://htmlpurifier.org/
        */
        if (! class_exists('HTMLPurifier_Config',
                false)) {
            // Load HTMLPurifier
            require 'HTMLPurifier/HTMLPurifier.auto.php';
            require 'HTMLPurifier.func.php';
        }
        // Set configuration
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML', 'TidyLevel', 'none'); // Only XSS cleaning now
        // Run HTMLPurifier
        $data = HTMLPurifier($data, $config);
        return $data;
    }

    /**
    * This is a helper method. It enforces W3C specifications for allowed
    * key name strings, to prevent malicious exploitation.
    *
    * @param string $ string to clean
    * @return string
    */
    public function clean_input_keys($str)
    {
        $chars = PCRE_UNICODE_PROPERTIES ? '\pL' : 'a-zA-Z';
        if (! preg_match('#^[' . $chars . '0-9:_.-]++$#uD', $str)) {
            exit('Disallowed key characters in global data.');
        }
        return $str;
    }

    /**
    * This is a helper method. It escapes data and forces all newline
    * characters to "\n".
    *
    * @param unknown_type $ string to clean
    * @return string
    */
    public function clean_input_data($str)
    {
        if (is_array($str)) {
            $new_array = array();
            foreach($str as $key => $val) {
                // Recursion!
                $new_array[$this->clean_input_keys($key)] = $this->clean_input_data($val);
            }
            return $new_array;
        }
        if ($this->magic_quotes_gpc === true) {
            // Remove annoying magic quotes
            $str = stripslashes($str);
        }
        if ($this->use_xss_clean === true) {
            $str = $this->xss_clean($str);
        }
        if (strpos($str, "\r") !== false) {
            // Standardize newlines
            $str = str_replace(
                array("\r\n" , "\r"),
                "\n", $str);
        }
        return $str;
    }
}
