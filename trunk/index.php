<?php
/**
 * APPLICATION FRONT CONTROLLER FILE
 *
 * $Id$
 *
 * @package Core
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/engine/license.html
 */
$pathinfo = pathinfo(__FILE__);
// Define the front controller name and docroot
define('DOC_ROOT', $pathinfo['dirname'] . DIRECTORY_SEPARATOR);
define('FRONT_CONTROLLER_FILE', $pathinfo['basename']);
define('STATIC_PATH', realpath(DOC_ROOT . 'static') . DIRECTORY_SEPARATOR);
define('VAR_PATH', realpath(dirname(__FILE__) . '/var') . DIRECTORY_SEPARATOR);
// Define path to application directory
define('APPLICATION_PATH', DOC_ROOT . 'platform' . DIRECTORY_SEPARATOR);
// Define application environment
define('APPLICATION_ENV', 'development');
// define('APPLICATION_ENV', 'production');
define('CORE_PATH', DOC_ROOT . 'core' . DIRECTORY_SEPARATOR);
@set_include_path(CORE_PATH . @get_include_path());
/**
 * * Zend_Application
 */
require_once 'Zend/Application.php';
// Create application, bootstrap, and run
$application = new Zend_Application(APPLICATION_ENV, VAR_PATH . 'configuration.ini');
$application->bootstrap()->run();
