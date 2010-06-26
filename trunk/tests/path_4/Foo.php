<?php
function Foo_autoload ( $name ) {
	if ( strncmp($name,'Foo_',4)!==0 ) {
		return false;
	}
	$file = dirname(__FILE__) . '/'. str_replace('_', '/', $name) . '.php';
	if ( !is_file($file) ) {
		return false;
	}
	require_once $file;
	return true;
}
spl_autoload_register ( 'Foo_autoload' );
?>