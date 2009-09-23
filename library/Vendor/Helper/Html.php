<?php

/**
 * HTML helper class.
 *
 * $Id: html.php 4318 2009-05-04 01:20:50Z kiall $
 *
 * @author Kohana Team
 * @copyright (c) 2007-2008 Kohana Team
 * @license http://kohanaphp.com/license.html*
 * @author Denysenko Dmytro


 */
namespace Vendor\Helper {
    class Html {
        // Enable or disable automatic setting of target="_blank"
        public static $windowed_urls = false;

        /**
         * Convert special characters to HTML entities
         *
         * @param string $ string to convert
         * @param boolean $ encode existing entities
         * @return string
         */
        public static function specialchars($str, $double_encode = true)
        {
            // Force the string to be a string
            $str = (string) $str;
            // Do encode existing HTML entities(default)
            if ($double_encode === true) {
                $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
            } else {
                // Do not encode existing HTML entities
                // From PHP 5.2.3 this functionality is built-in, otherwise use a regex
                if (version_compare(PHP_VERSION, '5.2.3', '>=')) {
                    $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8', false);
                } else {
                    $str = preg_replace('/&(?!(?:#\d++|[a-z]++);)/ui', '&amp;', $str);
                    $str = str_replace(array('<' , '>' , '\'' , '"'), array('&lt;' , '&gt;' , '&#39;' , '&quot;'), $str);
                }
            }
            return $str;
        }

        /**
         * Perform a Vendor\Helper\Html::specialchars() with additional URL specific encoding.
         *
         * @param string $ string to convert
         * @param boolean $ encode existing entities
         * @return string
         */
        public static function specialurlencode($str, $double_encode = true)
        {
            return str_replace(' ', '%20', Vendor\Helper\Html::specialchars($str, $double_encode));
        }

        /**
         * Create HTML link anchors.
         *
         * @param string $ URL or URI string
         * @param string $ link text
         * @param array $ HTML anchor attributes
         * @param string $ non-default protocol, eg: https
         * @return string
         */
        public static function anchor($uri, $title = null, $attributes = null, $protocol = null)
        {
            if ($uri === '') {
                $site_url = url::base(false);
            } elseif (strpos($uri, '#') === 0) {
                // This is an id target link, not a URL
                $site_url = $uri;
            } elseif (strpos($uri, '://') === false) {
                $site_url = url::site($uri, $protocol);
            } else {
                if (Vendor\Helper\Html::$windowed_urls === true and empty($attributes['target'])) {
                    $attributes['target'] = '_blank';
                }
                $site_url = $uri;
            }
            return // Parsed URL
        '<a href="' . Vendor\Helper\Html::specialurlencode($site_url, false) . '"' . // Attributes empty? Use an empty string
            (is_array($attributes) ? Vendor\Helper\Html::attributes($attributes) : '') . '>' . // Title empty? Use the parsed URL
            (($title === null) ? $site_url : $title) . '</a>';
        }

        /**
         * Creates an HTML anchor to a file.
         *
         * @param string $ name of file to link to
         * @param string $ link text
         * @param array $ HTML anchor attributes
         * @param string $ non-default protocol, eg: ftp
         * @return string
         */
        public static function file_anchor($file, $title = null, $attributes = null, $protocol = null)
        {
            return // Base URL + URI = full URL
        '<a href="' . Vendor\Helper\Html::specialurlencode(url::base(false, $protocol) . $file, false) . '"' . // Attributes empty? Use an empty string
            (is_array($attributes) ? Vendor\Helper\Html::attributes($attributes) : '') . '>' . // Title empty? Use the filename part of the URI
            (($title === null) ? end(explode('/', $file)) : $title) . '</a>';
        }

        /**
         * Similar to anchor, but with the protocol parameter first.
         *
         * @param string $ link protocol
         * @param string $ URI or URL to link to
         * @param string $ link text
         * @param array $ HTML anchor attributes
         * @return string
         */
        public static function panchor($protocol, $uri, $title = null, $attributes = false)
        {
            return Vendor\Helper\Html::anchor($uri, $title, $attributes, $protocol);
        }

        /**
         * Create an array of anchors from an array of link/title pairs.
         *
         * @param array $ link/title pairs
         * @return array
         */
        public static function anchor_array(array $array)
        {
            $anchors = array();
            foreach($array as $link => $title) {
                // Create list of anchors
                $anchors[] = Vendor\Helper\Html::anchor($link, $title);
            }
            return $anchors;
        }

        /**
         * Generates an obfuscated version of an email address.
         *
         * @param string $ email address
         * @return string
         */
        public static function email($email)
        {
            $safe = '';
            foreach(str_split($email) as $letter) {
                switch (($letter === '@') ? rand(1, 2) : rand(1, 3)) {
                    // HTML entity code
                    case 1:
                        $safe .= '&#' . ord($letter) . ';';
                        break;
                        // Hex character code
                    case 2:
                        $safe .= '&#x' . dechex(ord($letter)) . ';';
                        break;
                        // Raw(no) encoding
                    case 3:
                        $safe .= $letter;
                }
            }
            return $safe;
        }

        /**
         * Creates an email anchor.
         *
         * @param string $ email address to send to
         * @param string $ link text
         * @param array $ HTML anchor attributes
         * @return string
         */
        public static function mailto($email, $title = null, $attributes = null)
        {
            if (empty($email))
            return $title;
            // Remove the subject or other parameters that do not need to be encoded
            if (strpos($email, '?') !== false) {
                // Extract the parameters from the email address
                list ($email, $params) = explode('?', $email, 2);
                // Make the params into a query string, replacing spaces
                $params = '?' . str_replace(' ', '%20', $params);
            } else {
                // No parameters
                $params = '';
            }
            // Obfuscate email address
            $safe = Vendor\Helper\Html::email($email);
            // Title defaults to the encoded email address
            empty($title) and $title = $safe;
            // Parse attributes
            empty($attributes) or $attributes = Vendor\Helper\Html::attributes($attributes);
            // Encoded start of the href="" is a static encoded version of 'mailto:'
            return '<a href="&#109;&#097;&#105;&#108;&#116;&#111;&#058;' . $safe . $params . '"' . $attributes . '>' . $title . '</a>';
        }

        /**
         * Generate a "breadcrumb" list of anchors representing the URI.
         *
         * @param array $ segments to use as breadcrumbs, defaults to using Router::$segments
         * @return string
         */
        public static function breadcrumb($segments = null)
        {
            empty($segments) and $segments = Router::$segments;
            $array = array();
            while ($segment = array_pop($segments)) {
                $array[] = Vendor\Helper\Html::anchor(// Complete URI for the URL
                implode('/', $segments) . '/' . $segment, // Title for the current segment
                ucwords(inflector::humanize($segment)));
            }
            // Retrun the array of all the segments
            return array_reverse($array);
        }

        /**
         * Creates a meta tag.
         *
         * @param string $ |array   tag name, or an array of tags
         * @param string $ tag "content" value
         * @return string
         */
        public static function meta($tag, $value = null)
        {
            if (is_array($tag)) {
                $tags = array();
                foreach($tag as $t => $v) {
                    // Build each tag and add it to the array
                    $tags[] = Vendor\Helper\Html::meta($t, $v);
                }
                // Return all of the tags as a string
                return implode("\n", $tags);
            }
            // Set the meta attribute value
            $attr = in_array(strtolower($tag), App::config('http.meta_equiv')) ? 'http-equiv' : 'name';
            return '<meta ' . $attr . '="' . $tag . '" content="' . $value . '" />';
        }

        /**
         * Creates a stylesheet link.
         *
         * @param string $ |array  filename, or array of filenames to match to array of medias
         * @param string $ |array  media type of stylesheet, or array to match filenames
         * @param boolean $ include the index_page in the link
         * @return string
         */
        public static function stylesheet($style, $media = false, $index = false)
        {
            return Vendor\Helper\Html::link($style, 'stylesheet', 'text/css', $media, $index);
        }

        /**
         * Creates a link tag.
         *
         * @param string $ |array  filename
         * @param string $ |array  relationship
         * @param string $ |array  mimetype
         * @param string $ |array  specifies on what device the document will be displayed
         * @param boolean $ include the index_page in the link
         * @return string
         */
        public static function link($href, $rel, $type, $media = false, $index = false)
        {
            $compiled = '';
            if (is_array($href)) {
                foreach($href as $_href) {
                    $_rel = is_array($rel) ? array_shift($rel) : $rel;
                    $_type = is_array($type) ? array_shift($type) : $type;
                    $_media = is_array($media) ? array_shift($media) : $media;
                    $compiled .= Vendor\Helper\Html::link($_href, $_rel, $_type, $_media, $index);
                }
            } else {
                if (strpos($href, '://') === false) {
                    // Make the URL absolute
                    $href = url::base($index) . $href;
                }
                $attr = array('rel' => $rel , 'type' => $type , 'href' => $href);
                if (! empty($media)) {
                    // Add the media type to the attributes
                    $attr['media'] = $media;
                }
                $compiled = '<link' . Vendor\Helper\Html::attributes($attr) . ' />';
            }
            return $compiled . "\n";
        }

        /**
         * Creates a script link.
         *
         * @param string $ |array  filename
         * @param boolean $ include the index_page in the link
         * @return string
         */
        public static function script($script, $index = false)
        {
            $compiled = '';
            if (is_array($script)) {
                foreach($script as $name) {
                    $compiled .= Vendor\Helper\Html::script($name, $index);
                }
            } else {
                if (strpos($script, '://') === false) {
                    // Add the suffix only when it's not already present
                    $script = url::base((bool) $index) . $script;
                }
                $compiled = '<script type="text/javascript" src="' . $script . '"></script>';
            }
            return $compiled . "\n";
        }

        /**
         * Creates a image link.
         *
         * @param string $ image source, or an array of attributes
         * @param string $ |array  image alt attribute, or an array of attributes
         * @param boolean $ include the index_page in the link
         * @return string
         */
        public static function image($src = null, $alt = null, $index = false)
        {
            // Create attribute list
            $attributes = is_array($src) ? $src : array('src' => $src);
            if (is_array($alt)) {
                $attributes += $alt;
            } elseif (! empty($alt)) {
                // Add alt to attributes
                $attributes['alt'] = $alt;
            }
            if (strpos($attributes['src'], '://') === false) {
                // Make the src attribute into an absolute URL
                $attributes['src'] = url::base($index) . $attributes['src'];
            }
            return '<img' . Vendor\Helper\Html::attributes($attributes) . ' />';
        }

        /**
         * Compiles an array of HTML attributes into an attribute string.
         *
         * @param string $ |array  array of attributes
         * @return string
         */
        public static function attributes($attrs)
        {
            if (empty($attrs))
            return '';
            if (is_string($attrs))
            return ' ' . $attrs;
            $compiled = '';
            foreach($attrs as $key => $val) {
                $compiled .= ' ' . $key . '="' . Vendor\Helper\Html::specialchars($val) . '"';
            }
            return $compiled;
        }
    }
    // End html
}