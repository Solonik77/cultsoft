<?php
/**
 * Number helper class.
 *
 * $Id: num.php 3769 2008-12-15 00:48:56Z zombor $
 *
 * @author Kohana Team
 * @copyright (c) 2007-2008 Kohana Team
 * @license http://kohanaphp.com/license.html*
 * @author Denysenko Dmytro
 */
namespace Vendor\Helper {
    class Num {
        /**
         * Round a number to the nearest nth
         *
         * @param integer $ number to round
         * @param integer $ number to round to
         * @return integer
         */
        public static function round($number, $nearest = 5)
        {
            return round($number / $nearest) * $nearest;
        }
    } // End num
}