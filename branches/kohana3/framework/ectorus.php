<?php defined('DOC_ROOT') or exit('No direct script access.');
/**
* Ectorus
*
* @author Dmytro Denysenko
* @copyright (c) 2010 Dmytro Denysenko
*/
require_once FW_PATH . 'ectorus' . DIRECTORY_SEPARATOR . 'core.php';
require_once FW_PATH . 'ectorus' . DIRECTORY_SEPARATOR . 'kohana.php';

/**
* Core system static class
*/
class Ectorus extends Ectorus\Core {
}

function __($string, array $values = null, $lang = 'en-us')
{
    return Ectorus\I18n::instance()->__($string, $values, $lang);
}

