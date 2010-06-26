<?php
/**
 * Zend类库扩展
 * 
 * 这是CoreMVC类库扩展文件，需要有Zend类库目录。
 * core.php ---> <b>core/Zend.php</b> ---> Zend类库目录
 */

/**
 * 定义(define)
 */

function Zend_autoload ( $name ) {
	if ( strncmp($name,'Zend_',5)!==0 ) {
		return false;
	}
	$file = dirname(__FILE__) . '/'. '/'.str_replace('_', '/', $name) . '.php';
	if ( !is_file($file) ) {
		return false;
	}
	require_once $file;
	return true;
}

/**
 * 执行(execute)
 */

spl_autoload_register ( 'Zend_autoload' );

?>