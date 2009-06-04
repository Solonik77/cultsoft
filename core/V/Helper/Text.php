<?php
require_once CORE_PATH . 'V/UTF8.php';
/**
 * Text helper class.
 *
 * $Id: text.php 4329 2009-05-06 15:53:27Z Geert $
 *
 * @package Core
 * @author Kohana Team
 * @copyright (c) 2007-2008 Kohana Team
 * @license http://kohanaphp.com/license.html
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/engine/license.html
 */
class V_Helper_Text
{
    /**
     * Limits a phrase to a given number of words.
     *
     * @param string $ phrase to limit words of
     * @param integer $ number of words to limit to
     * @param string $ end character or entity
     * @return string
     */
    public static function limit_words ($str, $limit = 100, $end_char = null)
    {
        $limit = (int) $limit;
        $end_char = ($end_char === null) ? '&#8230;' : $end_char;
        if (trim($str) === '')
            return $str;
        if ($limit <= 0)
            return $end_char;
        preg_match('/^\s*+(?:\S++\s*+){1,' . $limit . '}/u', $str, $matches);
        // Only attach the end character if the matched string is shorter
        // than the starting string.
        return rtrim($matches[0]) . (strlen($matches[0]) === strlen($str) ? '' : $end_char);
    }
    /**
     * Limits a phrase to a given number of characters.
     *
     * @param string $ phrase to limit characters of
     * @param integer $ number of characters to limit to
     * @param string $ end character or entity
     * @param boolean $ enable or disable the preservation of words while limiting
     * @return string
     */
    public static function limit_chars ($str, $limit = 100, $end_char = null, $preserve_words = false)
    {
        $end_char = ($end_char === null) ? '&#8230;' : $end_char;
        $limit = (int) $limit;
        if (trim($str) === '' or V_UTF8::strlen($str) <= $limit)
            return $str;
        if ($limit <= 0)
            return $end_char;
        if ($preserve_words == false) {
            return rtrim(V_UTF8::substr($str, 0, $limit)) . $end_char;
        }
        preg_match('/^.{' . ($limit - 1) . '}\S*/us', $str, $matches);
        return rtrim($matches[0]) . (strlen($matches[0]) == strlen($str) ? '' : $end_char);
    }
    /**
     * Alternates between two or more strings.
     *
     * @param string $ strings to alternate between
     * @return string
     */
    public static function alternate ()
    {
        static $i;
        if (func_num_args() === 0) {
            $i = 0;
            return '';
        }
        $args = func_get_args();
        return $args[($i ++ % count($args))];
    }
    /**
     * Generates a random string of a given type and length.
     *
     * @param string $ a type of pool, or a string of characters to use as the pool
     * @param integer $ length of string to return
     * @return string
     * @tutorial alnum     alpha-numeric characters
     * @tutorial alpha     alphabetical characters
     * @tutorial hexdec    hexadecimal characters, 0-9 plus a-f
     * @tutorial numeric   digit characters, 0-9
     * @tutorial nozero    digit characters, 1-9
     * @tutorial distinct  clearly distinct alpha-numeric characters
     */
    public static function random ($type = 'alnum', $length = 8)
    {
        $utf8 = false;
        switch ($type) {
            case 'alnum':
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'alpha':
                $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'hexdec':
                $pool = '0123456789abcdef';
                break;
            case 'numeric':
                $pool = '0123456789';
                break;
            case 'nozero':
                $pool = '123456789';
                break;
            case 'distinct':
                $pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
                break;
            default:
                $pool = (string) $type;
                $utf8 = ! V_UTF8::is_ascii($pool);
                break;
        }
        // Split the pool into an array of characters
        $pool = ($utf8 === true) ? V_UTF8::str_split($pool, 1) : str_split($pool, 1);
        // Largest pool key
        $max = count($pool) - 1;
        $str = '';
        for ($i = 0; $i < $length; $i ++) {
            // Select a random character from the pool and add it to the string
            $str .= $pool[mt_rand(0, $max)];
        }
        // Make sure alnum strings contain at least one letter and one digit
        if ($type === 'alnum' and $length > 1) {
            if (ctype_alpha($str)) {
                // Add a random digit
                $str[mt_rand(0, $length - 1)] = chr(mt_rand(48, 57));
            } elseif (ctype_digit($str)) {
                // Add a random letter
                $str[mt_rand(0, $length - 1)] = chr(mt_rand(65, 90));
            }
        }
        return $str;
    }
    /**
     * Reduces multiple slashes in a string to single slashes.
     *
     * @param string $ string to reduce slashes of
     * @return string
     */
    public static function reduce_slashes ($str)
    {
        return preg_replace('#(?<!:)//+#', '/', $str);
    }
    /**
     * Replaces the given words with a string.
     *
     * @param string $ phrase to replace words in
     * @param array $ words to replace
     * @param string $ replacement string
     * @param boolean $ replace words across word boundries (space, period, etc)
     * @return string
     */
    public static function censor ($str, $badwords, $replacement = '#', $replace_partial_words = true)
    {
        foreach ((array) $badwords as $key => $badword) {
            $badwords[$key] = str_replace('\*', '\S*?', preg_quote((string) $badword));
        }
        $regex = '(' . implode('|', $badwords) . ')';
        if ($replace_partial_words === false) {
            // Just using \b isn't sufficient when we need to replace a badword that already contains word boundaries itself
            $regex = '(?<=\b|\s|^)' . $regex . '(?=\b|\s|$)';
        }
        $regex = '!' . $regex . '!ui';
        if (V_UTF8::strlen($replacement) == 1) {
            $regex .= 'e';
            return preg_replace($regex, 'str_repeat($replacement, V_UTF8::strlen(\'$1\'))', $str);
        }
        return preg_replace($regex, $replacement, $str);
    }
    /**
     * Finds the text that is similar between a set of words.
     *
     * @param array $ words to find similar text of
     * @return string
     */
    public static function similar (array $words)
    {
        // First word is the word to match against
        $word = current($words);
        for ($i = 0, $max = strlen($word); $i < $max; ++ $i) {
            foreach ($words as $w) {
                // Once a difference is found, break out of the loops
                if (! isset($w[$i]) or $w[$i] !== $word[$i])
                    break 2;
            }
        }
        // Return the similar text
        return substr($word, 0, $i);
    }
    /**
     * Converts text email addresses and anchors into links.
     *
     * @param string $ text to auto link
     * @return string
     */
    public static function auto_link ($text)
    {
        // Auto link emails first to prevent problems with "www.domain.com@example.com"
        return text::auto_link_urls(text::auto_link_emails($text));
    }
    /**
     * Converts text anchors into links.
     *
     * @param string $ text to auto link
     * @return string
     */
    public static function auto_link_urls ($text)
    {
        // Finds all http/https/ftp/ftps links that are not part of an existing html anchor
        if (preg_match_all('~\b(?<!href="|">)(?:ht|f)tps?://\S+(?:/|\b)~i', $text, $matches)) {
            foreach ($matches[0] as $match) {
                // Replace each link with an anchor
                $text = str_replace($match, html::anchor($match), $text);
            }
        }
        // Find all naked www.links.com (without http://)
        if (preg_match_all('~\b(?<!://)www(?:\.[a-z0-9][-a-z0-9]*+)+\.[a-z]{2,6}\b~i', $text, $matches)) {
            foreach ($matches[0] as $match) {
                // Replace each link with an anchor
                $text = str_replace($match, html::anchor('http://' . $match, $match), $text);
            }
        }
        return $text;
    }
    /**
     * Converts text email addresses into links.
     *
     * @param string $ text to auto link
     * @return string
     */
    public static function auto_link_emails ($text)
    {
        // Finds all email addresses that are not part of an existing html mailto anchor
        // Note: The "58;" negative lookbehind prevents matching of existing encoded html mailto anchors
        // The html entity for a colon (:) is &#58; or &#058; or &#0058; etc.
        if (preg_match_all('~\b(?<!href="mailto:|">|58;)(?!\.)[-+_a-z0-9.]++(?<!\.)@(?![-.])[-a-z0-9.]+(?<!\.)\.[a-z]{2,6}\b~i', $text, $matches)) {
            foreach ($matches[0] as $match) {
                // Replace each email with an encoded mailto
                $text = str_replace($match, html::mailto($match), $text);
            }
        }
        return $text;
    }
    /**
     * Automatically applies <p> and <br /> markup to text. Basically nl2br() on steroids.
     *
     * @param string $ subject
     * @param boolean $ convert single linebreaks to <br />
     * @return string
     */
    public static function auto_p ($str, $br = true)
    {
        // Trim whitespace
        if (($str = trim($str)) === '')
            return '';
            // Standardize newlines
        $str = str_replace(array("\r\n" , "\r"), "\n", $str);
        // Trim whitespace on each line
        $str = preg_replace('~^[ \t]+~m', '', $str);
        $str = preg_replace('~[ \t]+$~m', '', $str);
        // The following regexes only need to be executed if the string contains html
        if ($html_found = (strpos($str, '<') !== false)) {
            // Elements that should not be surrounded by p tags
            $no_p = '(?:p|div|h[1-6r]|ul|ol|li|blockquote|d[dlt]|pre|t[dhr]|t(?:able|body|foot|head)|c(?:aption|olgroup)|form|s(?:elect|tyle)|a(?:ddress|rea)|ma(?:p|th))';
            // Put at least two linebreaks before and after $no_p elements
            $str = preg_replace('~^<' . $no_p . '[^>]*+>~im', "\n$0", $str);
            $str = preg_replace('~</' . $no_p . '\s*+>$~im', "$0\n", $str);
        }
        // Do the <p> magic!
        $str = '<p>' . trim($str) . '</p>';
        $str = preg_replace('~\n{2,}~', "</p>\n\n<p>", $str);
        // The following regexes only need to be executed if the string contains html
        if ($html_found !== false) {
            // Remove p tags around $no_p elements
            $str = preg_replace('~<p>(?=</?' . $no_p . '[^>]*+>)~i', '', $str);
            $str = preg_replace('~(</?' . $no_p . '[^>]*+>)</p>~i', '$1', $str);
        }
        // Convert single linebreaks to <br />
        if ($br === true) {
            $str = preg_replace('~(?<!\n)\n(?!\n)~', "<br />\n", $str);
        }
        return $str;
    }
    /**
     * Returns human readable sizes.
     *
     * @see Based on original functions written by:
     * @see Aidan Lister: http://aidanlister.com/repos/v/function.size_readable.php
     * @see Quentin Zervaas: http://www.phpriot.com/d/code/strings/filesize-format/
     * @param integer $ size in bytes
     * @param string $ a definitive unit
     * @param string $ the return string format
     * @param boolean $ whether to use SI prefixes or IEC
     * @return string
     */
    public static function bytes ($bytes, $force_unit = null, $format = null, $si = true)
    {
        // Format string
        $format = ($format === null) ? '%01.2f %s' : (string) $format;
        // IEC prefixes (binary)
        if ($si == false or strpos($force_unit, 'i') !== false) {
            $units = array('B' , 'KiB' , 'MiB' , 'GiB' , 'TiB' , 'PiB');
            $mod = 1024;
        } // SI prefixes (decimal)
else {
            $units = array('B' , 'kB' , 'MB' , 'GB' , 'TB' , 'PB');
            $mod = 1000;
        }
        // Determine unit to use
        if (($power = array_search((string) $force_unit, $units)) === false) {
            $power = ($bytes > 0) ? floor(log($bytes, $mod)) : 0;
        }
        return sprintf($format, $bytes / pow($mod, $power), $units[$power]);
    }
    /**
     * Prevents widow words by inserting a non-breaking space between the last two words.
     *
     * @see http://www.shauninman.com/archive/2006/08/22/widont_wordpress_plugin
     * @param string $ string to remove widows from
     * @return string
     */
    public static function widont ($str)
    {
        $str = rtrim($str);
        $space = strrpos($str, ' ');
        if ($space !== false) {
            $str = substr($str, 0, $space) . '&nbsp;' . substr($str, $space + 1);
        }
        return $str;
    }
} // End text