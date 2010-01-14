<?php
/**
* Ectorus
*
* @author Dmytro Denysenko
* @copyright (c) 2010 Dmytro Denysenko
*/

/**
* The directory in which your application specific resources and modules are located.
*/
$application_path = 'app';

/**
* The directory in which the framework resources are located.
*/
$framework_path = 'framework';

/**
* The directory in which system cache and session data are located.
*/
$var_path = 'var';

/**
* Error reporting
*/
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$pathinfo = pathinfo(__FILE__);
// Define the front controller name and docroot
define('DOC_ROOT', $pathinfo['dirname'] . DIRECTORY_SEPARATOR);
define('FRONT_CONTROLLER_FILE', $pathinfo['basename']);
// If the front controller is a symlink, change to the real docroot
is_link(FRONT_CONTROLLER_FILE) and chdir(dirname(realpath(__FILE__)));

/**
* Define application environment
*/
define('APP_ENV', ((file_exists(DOC_ROOT . '.production')) ? 'production' : 'development'));
// Make the framework relative to the docroot
if (! is_dir($framework_path) and is_dir(DOC_ROOT . $framework_path)) {
    $framework_path = DOC_ROOT . $framework_path;
}
// Make the application relative to the docroot
if (! is_dir($application_path) and is_dir(DOC_ROOT . $application_path)) {
    $application_path = DOC_ROOT . $application_path;
}
// Make system var directory relative to the docroot
if (! is_dir($var_path) and is_dir(DOC_ROOT . $var_path)) {
    $var_path = DOC_ROOT . $var_path;
}
// Define path to framework directory
define('FW_PATH', str_replace('\\', '/', realpath($framework_path) . DIRECTORY_SEPARATOR));
// Define path to application directory
define('APP_PATH', str_replace('\\', '/', realpath($application_path) . DIRECTORY_SEPARATOR));
// Define path to var directory
define('VAR_PATH', str_replace('\\', '/', realpath($var_path) . DIRECTORY_SEPARATOR));
// Clean up the configuration vars
unset($framework_path, $application_path, $var_path, $pathinfo);

if (version_compare(phpversion(), '5.3.0', '<') === true) {
    echo '<h3>You have an invalid PHP version</h3>';
    echo '<p>Ectorus supports PHP 5.3.0 or newer</p>';
    exit();
}

$ectorus_file = FW_PATH . 'ectorus.php';

if (! file_exists($ectorus_file)) {
    echo '<h1>Error in system directories configuration</h1>';
    echo $ectorus_file . ' was not found. Check path config variables in file: ' . DOC_ROOT . FRONT_CONTROLLER_FILE;
} else {
    require_once $ectorus_file;
    Ectorus::run();
}
