<?php
/**
 * Format helper class.
 *
 * $Id: format.php 4070 2009-03-11 20:37:38Z Geert $
 *
 * @author Kohana Team
 * @copyright (c) 2007-2008 Kohana Team
 * @license http://kohanaphp.com/license.html*
 * @author Denysenko Dmytro
 
 
 */
namespace Vendor\Helper {
class Format {
    /**
     * Formats a phone number according to the specified format.
     *
     * @param string $ phone number
     * @param string $ format string
     * @return string
     */
    public static function phone($number, $format = '3-3-4')
    {
        // Get rid of all non-digit characters in number string
        $number_clean = preg_replace('/\D+/', '', (string) $number);
        // Array of digits we need for a valid format
        $format_parts = preg_split('/[^1-9][^0-9]*/', $format, - 1, PREG_SPLIT_NO_EMPTY);
        // Number must match digit count of a valid format
        if (strlen($number_clean) !== array_sum($format_parts))
        return $number;
        // Build regex
        $regex = '(\d{' . implode('})(\d{', $format_parts) . '})';
        // Build replace string
        for($i = 1, $c = count($format_parts); $i <= $c; $i ++) {
            $format = preg_replace('/(?<!\$)[1-9][0-9]*/', '\$' . $i, $format, 1);
        }
        // Hocus pocus!
        return preg_replace('/^' . $regex . '$/', $format, $number_clean);
    }

    /**
     * Formats a URL to contain a protocol at the beginning.
     *
     * @param string $ possibly incomplete URL
     * @return string
     */
    public static function url($str = '')
    {
        // Clear protocol-only strings like "http://"
        if ($str === '' or substr($str, - 3) === '://')
        return '';
        // If no protocol given, prepend "http://" by default
        if (strpos($str, '://') === false)
        return 'http://' . $str;
        // Return the original URL
        return $str;
    }

    public static function size($file_size)
    {
        if ($file_size >= 1073741824) {
            $file_size = round($file_size / 1073741824 * 100) / 100 . " Gb";
        } elseif ($file_size >= 1048576) {
            $file_size = round($file_size / 1048576 * 100) / 100 . " Mb";
        } elseif ($file_size >= 1024) {
            $file_size = round($file_size / 1024 * 100) / 100 . " Kb";
        } else {
            $file_size = $file_size . " b";
        }
        return $file_size;
    }
} // End format
}