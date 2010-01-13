<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'cookie' => array(
		'encrypted' => FALSE,
),
    'native' => array(
		'save_path' => VAR_PATH . 'session' . DS,
),
);
