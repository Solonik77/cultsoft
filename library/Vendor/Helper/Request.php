<?php
/**
 * Request helper class.
 *
 * $Id: request.php 4355 2009-05-15 17:18:28Z kiall $
 *
 * @author Kohana Team
 * @copyright (c) 2007-2008 Kohana Team
 * @license http://kohanaphp.com/license.html*
 * @author Denysenko Dmytro
 */
namespace Vendor\Helper {
    class Request {
        // Possible HTTP methods
        protected static $http_methods = array('get' , 'head' , 'options' , 'post' , 'put' , 'delete');
        // Content types from client's HTTP Accept request header(array)
        protected static $accept_types;

        /**
         * Returns the HTTP referrer, or the default if the referrer is not set.
         *
         * @param mixed $ default to return
         * @return string
         */
        public static function referrer($default = false)
        {
            if (! empty($_SERVER['HTTP_REFERER'])) {
                // Set referrer
                $ref = $_SERVER['HTTP_REFERER'];
                if (strpos($ref, Vendor\Helper\url::base(false)) === 0) {
                    // Remove the base URL from the referrer
                    $ref = substr($ref, strlen(Vendor\Helper\url::base(false)));
                }
            }
            return isset($ref) ? $ref : $default;
        }

        /**
         * Returns the current request protocol, based on $_SERVER['https']. In CLI
         * mode, NULL will be returned.
         *
         * @return string
         */
        public static function protocol()
        {
            if (PHP_SAPI === 'cli') {
                return null;
            } elseif (! empty($_SERVER['HTTPS']) and $_SERVER['HTTPS'] === 'on') {
                return 'https';
            } else {
                return 'http';
            }
        }

        /**
         * Tests if the current request is an AJAX request by checking the X-Requested-With HTTP
         * request header that most popular JS frameworks now set for AJAX calls.
         *
         * @return boolean
         */
        public static function is_ajax()
        {
            return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
        }

        /**
         * Returns current request method.
         *
         * @throws App_Exceptionin case of an unknown request method
         * @return string
         */
        public static function method()
        {
            $method = strtolower($_SERVER['REQUEST_METHOD']);
            if (! in_array($method, Vendor\Helper\Request::$http_methods))
            throw new App_Exception('Unknown request method: ' . $method);
            return $method;
        }

        /**
         * Returns boolean of whether client accepts content type.
         *
         * @param string $ content type
         * @param boolean $ set to TRUE to disable wildcard checking
         * @return boolean
         */
        public static function accepts($type = null, $explicit_check = false)
        {
            Vendor\Helper\Request::parse_accept_header();
            if ($type === null)
            return Vendor\Helper\Request::$accept_types;
            return (Vendor\Helper\Request::accepts_at_quality($type, $explicit_check) > 0);
        }

        /**
         * Compare the q values for given array of content types and return the one with the highest value.
         * If items are found to have the same q value, the first one encountered in the given array wins.
         * If all items in the given array have a q value of 0, FALSE is returned.
         *
         * @param array $ content types
         * @param boolean $ set to TRUE to disable wildcard checking
         * @return mixed string mime type with highest q value, FALSE if none of the given types are accepted
         */
        public static function preferred_accept($types, $explicit_check = false)
        {
            // Initialize
            $mime_types = array();
            $max_q = 0;
            $preferred = false;
            // Load q values for all given content types
            foreach(array_unique($types) as $type) {
                $mime_types[$type] = Vendor\Helper\Request::accepts_at_quality($type, $explicit_check);
            }
            // Look for the highest q value
            foreach($mime_types as $type => $q) {
                if ($q > $max_q) {
                    $max_q = $q;
                    $preferred = $type;
                }
            }
            return $preferred;
        }

        /**
         * Returns quality factor at which the client accepts content type.
         *
         * @param string $ content type(e.g. "image/jpg", "jpg")
         * @param boolean $ set to TRUE to disable wildcard checking
         * @return integer |float
         */
        public static function accepts_at_quality($type = null, $explicit_check = false)
        {
            Vendor\Helper\Request::parse_accept_header();
            // Normalize type
            $type = strtolower((string) $type);
            // General content type(e.g. "jpg")
            if (strpos($type, '/') === false) {
                // Don't accept anything by default
                $q = 0;
                // Look up relevant mime types
                foreach((array) App::config('mimes.' . $type) as $type) {
                    $q2 = Vendor\Helper\Request::accepts_at_quality($type, $explicit_check);
                    $q = ($q2 > $q) ? $q2 : $q;
                }
                return $q;
            }
            // Content type with subtype given(e.g. "image/jpg")
            $type = explode('/', $type, 2);
            // Exact match
            if (isset(Vendor\Helper\Request::$accept_types[$type[0]][$type[1]]))
            return Vendor\Helper\Request::$accept_types[$type[0]][$type[1]];
            // Wildcard match(if not checking explicitly)
            if ($explicit_check === false and isset(Vendor\Helper\Request::$accept_types[$type[0]]['*']))
            return Vendor\Helper\Request::$accept_types[$type[0]]['*'];
            // Catch-all wildcard match(if not checking explicitly)
            if ($explicit_check === false and isset(Vendor\Helper\Request::$accept_types['*']['*']))
            return Vendor\Helper\Request::$accept_types['*']['*'];
            // Content type not accepted
            return 0;
        }

        /**
         * Parses client's HTTP Accept request header, and builds array structure representing it.
         *
         * @return void
         */
        protected static function parse_accept_header()
        {
            // Run this function just once
            if (Vendor\Helper\Request::$accept_types !== null)
            return;
            // Initialize accept_types array
            Vendor\Helper\Request::$accept_types = array();
            // No HTTP Accept header found
            if (empty($_SERVER['HTTP_ACCEPT'])) {
                // Accept everything
                Vendor\Helper\Request::$accept_types['*']['*'] = 1;
                return;
            }
            // Remove linebreaks and parse the HTTP Accept header
            foreach(explode(',', str_replace(array("\r" , "\n"), '', $_SERVER['HTTP_ACCEPT'])) as $accept_entry) {
                // Explode each entry in content type and possible quality factor
                $accept_entry = explode(';', trim($accept_entry), 2);
                // Explode each content type(e.g. "text/html")
                $type = explode('/', $accept_entry[0], 2);
                // Skip invalid content types
                if (! isset($type[1]))
                continue;
                // Assume a default quality factor of 1 if no custom q value found
                $q = (isset($accept_entry[1]) and preg_match('~\bq\s*+=\s*+([.0-9]+)~', $accept_entry[1], $match)) ? (float) $match[1] : 1;
                // Populate accept_types array
                if (! isset(Vendor\Helper\Request::$accept_types[$type[0]][$type[1]]) or $q > Vendor\Helper\Request::$accept_types[$type[0]][$type[1]]) {
                    Vendor\Helper\Request::$accept_types[$type[0]][$type[1]] = $q;
                }
            }
        }
    } // End request
}