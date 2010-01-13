<?php
$base_url = ((empty($_SERVER['HTTPS']) or $_SERVER['HTTPS'] === 'off') ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'];
$base_url = rtrim($base_url, '/') . '/';
return array(
   'is_installed' => FALSE,
   'session_handler' => 'native',
   'base_url' =>  $base_url,
   'modules'  => array(
                   'core' => MODPATH . 'system' . DS . 'core' . DS,
                   'installer' => MODPATH . 'system' . DS . 'installer' . DS,
                 )
);