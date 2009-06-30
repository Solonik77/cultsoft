<?php

/**
 * V_UTF8::transliterate_to_ascii
 *
 * @package Core
 * @author Kohana Team
 * @copyright (c) 2007 Kohana Team
 * @copyright (c) 2005 Harry Fuecks
 * @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
function _transliterate_to_ascii($str, $case = 0)
{
    static $UTF8_LOWER_ACCENTS = null;
    static $UTF8_UPPER_ACCENTS = null;
    if($case <= 0)
    {
        if($UTF8_LOWER_ACCENTS === null)
        {
            $UTF8_LOWER_ACCENTS = array('a' => 'a' , 'o' => 'o' , 'd' => 'd' , '?' => 'f' , 'e' => 'e' , 's' => 's' , 'o' => 'o' , '?' => 'ss' , 'a' => 'a' , 'r' => 'r' , '?' => 't' , 'n' => 'n' , 'a' => 'a' , 'k' => 'k' , 's' => 's' , '?' => 'y' , 'n' => 'n' , 'l' => 'l' , 'h' => 'h' , '?' => 'p' , 'o' => 'o' , 'u' => 'u' , 'e' => 'e' , 'e' => 'e' , 'c' => 'c' , '?' => 'w' , 'c' => 'c' , 'o' => 'o' , '?' => 's' , 'o' => 'o' , 'g' => 'g' , 't' => 't' , '?' => 's' , 'e' => 'e' , 'c' => 'c' , 's' => 's' , 'i' => 'i' , 'u' => 'u' , 'c' => 'c' , 'e' => 'e' , 'w' => 'w' , '?' => 't' , 'u' => 'u' , 'c' => 'c' , 'o' => 'o' , 'e' => 'e' , 'y' => 'y' , 'a' => 'a' , 'l' => 'l' , 'u' => 'u' , 'u' => 'u' , 's' => 's' , 'g' => 'g' , 'l' => 'l' , '?' => 'f' , 'z' => 'z' , '?' => 'w' , '?' => 'b' , 'a' => 'a' , 'i' => 'i' , 'i' => 'i' , '?' => 'd' , 't' => 't' , 'r' => 'r' , 'a' => 'a' , 'i' => 'i' , 'r' => 'r' , 'e' => 'e' , 'u' => 'u' , 'o' => 'o' , 'e' => 'e' , 'n' => 'n' , 'n' => 'n' , 'h' => 'h' , 'g' => 'g' , 'd' => 'd' , 'j' => 'j' , 'y' => 'y' , 'u' => 'u' , 'u' => 'u' , 'u' => 'u' , 't' => 't' , 'y' => 'y' , 'o' => 'o' , 'a' => 'a' , 'l' => 'l' , '?' => 'w' , 'z' => 'z' , 'i' => 'i' , 'a' => 'a' , 'g' => 'g' , '?' => 'm' , 'o' => 'o' , 'i' => 'i' , 'u' => 'u' , 'i' => 'i' , 'z' => 'z' , 'a' => 'a' , 'u' => 'u' , '?' => 'th' , '?' => 'dh' , '?' => 'ae' , 'µ' => 'u' , 'e' => 'e');
        }
        $str = str_replace(
        array_keys($UTF8_LOWER_ACCENTS), 
        array_values($UTF8_LOWER_ACCENTS), $str);
    }
    if($case >= 0)
    {
        if($UTF8_UPPER_ACCENTS === null)
        {
            $UTF8_UPPER_ACCENTS = array('A' => 'A' , 'O' => 'O' , 'D' => 'D' , '?' => 'F' , 'E' => 'E' , 'S' => 'S' , 'O' => 'O' , 'A' => 'A' , 'R' => 'R' , '?' => 'T' , 'N' => 'N' , 'A' => 'A' , 'K' => 'K' , 'E' => 'E' , 'S' => 'S' , '?' => 'Y' , 'N' => 'N' , 'L' => 'L' , 'H' => 'H' , '?' => 'P' , 'O' => 'O' , 'U' => 'U' , 'E' => 'E' , 'E' => 'E' , 'C' => 'C' , '?' => 'W' , 'C' => 'C' , 'O' => 'O' , '?' => 'S' , 'O' => 'O' , 'G' => 'G' , 'T' => 'T' , '?' => 'S' , 'E' => 'E' , 'C' => 'C' , 'S' => 'S' , 'I' => 'I' , 'U' => 'U' , 'C' => 'C' , 'E' => 'E' , 'W' => 'W' , '?' => 'T' , 'U' => 'U' , 'C' => 'C' , 'O' => 'O' , 'E' => 'E' , 'Y' => 'Y' , 'A' => 'A' , 'L' => 'L' , 'U' => 'U' , 'U' => 'U' , 'S' => 'S' , 'G' => 'G' , 'L' => 'L' , '?' => 'F' , 'Z' => 'Z' , '?' => 'W' , '?' => 'B' , 'A' => 'A' , 'I' => 'I' , 'I' => 'I' , '?' => 'D' , 'T' => 'T' , 'R' => 'R' , 'A' => 'A' , 'I' => 'I' , 'R' => 'R' , 'E' => 'E' , 'U' => 'U' , 'O' => 'O' , 'E' => 'E' , 'N' => 'N' , 'N' => 'N' , 'H' => 'H' , 'G' => 'G' , 'D' => 'D' , 'J' => 'J' , 'Y' => 'Y' , 'U' => 'U' , 'U' => 'U' , 'U' => 'U' , 'T' => 'T' , 'Y' => 'Y' , 'O' => 'O' , 'A' => 'A' , 'L' => 'L' , '?' => 'W' , 'Z' => 'Z' , 'I' => 'I' , 'A' => 'A' , 'G' => 'G' , '?' => 'M' , 'O' => 'O' , 'I' => 'I' , 'U' => 'U' , 'I' => 'I' , 'Z' => 'Z' , 'A' => 'A' , 'U' => 'U' , '?' => 'Th' , '?' => 'Dh' , '?' => 'Ae');
        }
        $str = str_replace(
        array_keys($UTF8_UPPER_ACCENTS), 
        array_values($UTF8_UPPER_ACCENTS), $str);
    }
    return $str;
}
