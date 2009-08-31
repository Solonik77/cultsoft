<?php

/**
 * Файл служит проверкой доступа по сессии,
 * вместо user подставьте ваше значение.
 * 
 */

if(!isset($_SESSION['admin_authorized']) and $_SESSION['admin_authorized'] != TRUE) {
	echo 'В доступе отказано, проверьте файл '.basename(__FILE__);
	exit();
}