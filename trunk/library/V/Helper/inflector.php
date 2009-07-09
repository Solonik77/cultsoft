<?php
/**
* Inflector helper class.
*
* $Id: inflector.php 4072 2009-03-13 17:20:38Z jheathco $
*
* @package Core
* @author Kohana Team
* @copyright (c) 2007-2008 Kohana Team
* @license http://kohanaphp.com/license.html
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
class V_Helper_Inflector {
    // Cached inflections
    protected static $cache = array ();
    // Uncountable and irregular words
    protected static $uncountable;
    protected static $irregular;

    /**
    * Checks if a word is defined as uncountable.
    *
    * @param string $ word to check
    * @return boolean
    */
    public static function uncountable($str)
    {
        if (V_Helper_Inflector::$uncountable === null) {
            // Cache uncountables
            V_Helper_Inflector::$uncountable = Kohana::config ('inflector.uncountable');
            // Make uncountables mirroed
            V_Helper_Inflector::$uncountable = array_combine (V_Helper_Inflector::$uncountable, V_Helper_Inflector::$uncountable);
        }
        return isset (V_Helper_Inflector::$uncountable [strtolower ($str)]);
    }

    /**
    * Makes a plural word singular.
    *
    * @param string $ word to singularize
    * @param integer $ number of things
    * @return string
    */
    public static function singular($str, $count = null)
    {
        // Remove garbage
        $str = strtolower (trim ($str));
        if (is_string ($count)) {
            // Convert to integer when using a digit string
            $count = (int) $count;
        }
        // Do nothing with a single count
        if ($count === 0 or $count > 1)
            return $str;
        // Cache key name
        $key = 'singular_' . $str . $count;
        if (isset (V_Helper_Inflector::$cache [$key]))
            return V_Helper_Inflector::$cache [$key];
        if (V_Helper_Inflector::uncountable ($str))
            return V_Helper_Inflector::$cache [$key] = $str;
        if (empty (V_Helper_Inflector::$irregular)) {
            // Cache irregular words
            V_Helper_Inflector::$irregular = Kohana::config ('inflector.irregular');
        }
        if ($irregular = array_search ($str, V_Helper_Inflector::$irregular)) {
            $str = $irregular;
        } elseif (preg_match ('/[sxz]es$/', $str) or preg_match ('/[^aeioudgkprt]hes$/', $str)) {
            // Remove "es"
            $str = substr ($str, 0, - 2);
        } elseif (preg_match ('/[^aeiou]ies$/', $str)) {
            $str = substr ($str, 0, - 3) . 'y';
        } elseif (substr ($str, - 1) === 's' and substr ($str, - 2) !== 'ss') {
            $str = substr ($str, 0, - 1);
        }
        return V_Helper_Inflector::$cache [$key] = $str;
    }

    /**
    * Makes a singular word plural.
    *
    * @param string $ word to pluralize
    * @return string
    */
    public static function plural($str, $count = null)
    {
        // Remove garbage
        $str = strtolower (trim ($str));
        if (is_string ($count)) {
            // Convert to integer when using a digit string
            $count = (int) $count;
        }
        // Do nothing with singular
        if ($count === 1)
            return $str;
        // Cache key name
        $key = 'plural_' . $str . $count;
        if (isset (V_Helper_Inflector::$cache [$key]))
            return V_Helper_Inflector::$cache [$key];
        if (V_Helper_Inflector::uncountable ($str))
            return V_Helper_Inflector::$cache [$key] = $str;
        if (empty (V_Helper_Inflector::$irregular)) {
            // Cache irregular words
            V_Helper_Inflector::$irregular = Kohana::config ('inflector.irregular');
        }
        if (isset (V_Helper_Inflector::$irregular [$str])) {
            $str = V_Helper_Inflector::$irregular [$str];
        } elseif (preg_match ('/[sxz]$/', $str) or preg_match ('/[^aeioudgkprt]h$/', $str)) {
            $str .= 'es';
        } elseif (preg_match ('/[^aeiou]y$/', $str)) {
            // Change "y" to "ies"
            $str = substr_replace ($str, 'ies', - 1);
        } else {
            $str .= 's';
        }
        // Set the cache and return
        return V_Helper_Inflector::$cache [$key] = $str;
    }

    /**
    * Makes a phrase camel case.
    *
    * @param string $ phrase to camelize
    * @return string
    */
    public static function camelize($str)
    {
        $str = 'x' . strtolower (trim ($str));
        $str = ucwords (preg_replace ('/[\s_]+/', ' ', $str));
        return substr (str_replace (' ', '', $str), 1);
    }

    /**
    * Makes a phrase underscored instead of spaced.
    *
    * @param string $ phrase to underscore
    * @return string
    */
    public static function underscore($str)
    {
        return preg_replace ('/\s+/', '_', trim ($str));
    }

    /**
    * Makes an underscored or dashed phrase human-reable.
    *
    * @param string $ phrase to make human-reable
    * @return string
    */
    public static function humanize($str)
    {
        return preg_replace ('/[_-]+/', ' ', trim ($str));
    }
} // End inflector