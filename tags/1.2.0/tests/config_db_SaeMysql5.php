<?php
$_SERVER['HTTP_MYSQLPORT'] = 3306;
$_SERVER['HTTP_APPNAME'] = 'test';
defined('SAE_ACCESSKEY') or define('SAE_ACCESSKEY','root');
defined('SAE_SECRETKEY') or define('SAE_SECRETKEY','');
return array(
	'connect_provider' => 'SaeMysql5',
	'connect_server' => 'localhost',
	'connect_username' => 'ODBC',
	'connect_password' => '',
	'connect_dbname' => 'test',
);
?>