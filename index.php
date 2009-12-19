<?php
/**
 * APPLICATION FRONT CONTROLLER FILE
 *
 * @author Denysenko Dmytro
 */
ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);
define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('BP', dirname(dirname(__FILE__)));
$pathinfo = pathinfo(__FILE__);
// Define the front controller name and docroot
define('DOC_ROOT', $pathinfo['dirname'] . DS);
define('FRONT_CONTROLLER_FILE', $pathinfo['basename']);
// If the front controller is a symlink, change to the real docroot
is_link(FRONT_CONTROLLER_FILE) and chdir(dirname(realpath(__FILE__)));
define('STATIC_PATH', realpath(DOC_ROOT . 'static') . DS);
define('VAR_PATH', realpath(dirname(__FILE__) . '/var') . DS);
// Define path to application directory
define('APPLICATION_PATH', DOC_ROOT . 'application' . DS);
// Define application environment
define('APPLICATION_ENV', ((file_exists(DOC_ROOT . '.production')) ? 'production' : 'development'));
define('INSTALLER_PATH', DOC_ROOT . 'installer' . DS);
define('LIBRARY_PATH', DOC_ROOT . 'library' . DS);
@set_include_path(LIBRARY_PATH . PATH_SEPARATOR . './');
if (! file_exists(VAR_PATH . 'configuration.ini') and file_exists(INSTALLER_PATH . 'Bootstrap.php')) {
    $bootstrap = INSTALLER_PATH . 'Bootstrap.php';
    $bootstrapClass = 'Installer_Bootstrap';
} else {
    $bootstrap = LIBRARY_PATH . 'Bootstrap.php';
    $bootstrapClass = 'Main_Bootstrap';
}
require_once $bootstrap;
$bootstrap = new $bootstrapClass();
$bootstrap->run();
