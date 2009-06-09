<?php
/**
 * Number helper class.
 *
 * $Id: num.php 3769 2008-12-15 00:48:56Z zombor $
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 *
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/engine/license.html
 */
class V_Helper_Num
{

    /**
     * Round a number to the nearest nth
     *
     * @param   integer  number to round
     * @param   integer  number to round to
     * @return  integer
     */
    public static function round ($number, $nearest = 5)
    {
        return round($number / $nearest) * $nearest;
    }
} // End num