<?php
$paths['framework'] = '../framework/';
$paths['application'] = '../app/';

$pathinfo = pathinfo(__FILE__);
// Define the front controller name and docroot
define('DOC_ROOT', $pathinfo['dirname'] . DIRECTORY_SEPARATOR);
define('FRONT_CONTROLLER_FILE', $pathinfo['basename']);
// If the front controller is a symlink, change to the real docroot
is_link(FRONT_CONTROLLER_FILE) and chdir(dirname(realpath(__FILE__)));
$paths['framework'] = file_exists($paths['framework']) ? $paths['framework'] : DOC_ROOT . $paths['framework'];
$paths['application'] = file_exists($paths['application']) ? $paths['application'] : DOC_ROOT . $paths['application'];
define('YII_PATH', str_replace('\\', '/', realpath($paths['framework'])) . '/');
define('APP_PATH', str_replace('\\', '/', realpath($paths['application'])) . '/');
define('STATIC_PATH',  DOC_ROOT . 'static/');
define('IN_PRODUCTION', (file_exists(DOC_ROOT . '.production')) ? TRUE : FALSE);
define('YII_DEBUG', (!IN_PRODUCTION) ? TRUE : FALSE);
require_once(YII_PATH . '/yii.php');
Yii::createWebApplication(APP_PATH . 'config/main.php')->run();