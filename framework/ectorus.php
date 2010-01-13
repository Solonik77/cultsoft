<?php defined('DOC_ROOT') or exit('No direct script access.');
/**
* Ectorus
*
* @author Dmytro Denysenko
* @copyright (c) 2010 Dmytro Denysenko
*/
if (version_compare(phpversion(), '5.3.0', '<') === true) {
    echo '<h3>You have an invalid PHP version</h3>';
    echo '<p>Ectorus supports PHP 5.3.0 or newer</p>';
    exit();
}

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

