<?php
/**
 * This is the bootstrap file for test application.
 * This file should be removed when the application is deployed for production.
 */
$yii = dirname(__FILE__).'/../framework/yii.php';
$config = dirname(__FILE__) . '/../source/config/main.php';
defined('YII_DEBUG') or define('YII_DEBUG',true);
require_once($yii);
Yii::createWebApplication($config)->run();