<?php
/**
 * APPLICATION FRONT CONTROLLER FILE
 *
 * @author Denysenko Dmytro 
 */
$pathinfo = pathinfo ( __FILE__ );
define ( 'DS', DIRECTORY_SEPARATOR );
// Define the front controller name and docroot
define ( 'DOC_ROOT', $pathinfo ['dirname'] . DS );
define ( 'FRONT_CONTROLLER_FILE', $pathinfo ['basename'] );
// If the front controller is a symlink, change to the real docroot
is_link ( FRONT_CONTROLLER_FILE ) and chdir ( dirname ( realpath ( __FILE__ ) ) );
define ( 'STATIC_PATH', realpath ( DOC_ROOT . 'static' ) . DS );
define ( 'VAR_PATH', realpath ( dirname ( __FILE__ ) . '/var' ) . DS );
// Define path to application directory
define ( 'APPLICATION_PATH', DOC_ROOT . 'application' . DS );
// Define application environment
defined ( 'APPLICATION_ENV' ) || define ( 'APPLICATION_ENV', getenv ( 'APPLICATION_ENV' ) );
define ( 'LIBRARY_PATH', DOC_ROOT . 'library' . DS );
@set_include_path ( LIBRARY_PATH . PATH_SEPARATOR . './' );
require_once LIBRARY_PATH . 'Bootstrap.php';
$bootstrap = new Bootstrap();
$bootstrap->run();