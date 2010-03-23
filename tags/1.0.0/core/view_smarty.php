<?php
/**
 * Smarty连接器
 * 
 * 这是CoreMVC扩展文件，连接核心文件和Smarty装载器/程序。
 * core.php ---> <b>core/view_smarty.php</b> ---> core/SmartyZip.php
 * core.php ---> <b>core/view_smarty.php</b> ---> Smarty程序
 */

/**
 * 执行(execute)
 */

// 配置信息
$smarty_file = ''; //Smarty类所在的文件，默认使用SmartyZip
$template_dir = ''; //模板文件所在目录，默认使用当前路径
$compile_dir = ''; //模板文件所在目录，默认使用临时路径

// 载入类文件
if (! class_exists ( 'smarty' , false ) ) {
	if ( $smarty_file === '' ) {
		require_once dirname(__FILE__) . '/SmartyZip.php';
	} else {
		require_once $smarty_file;
	}
}

// 创建实例
static $smarty = null;
if( $smarty === null ) {
	$smarty = new smarty;
}

// 模板路径
if ( $template_dir === '' ) {
	$smarty->template_dir = getcwd ();
} else {
	$smarty->template_dir = $template_dir;
}

// 编译路径
if ( $compile_dir === '' ) {
	$smarty->compile_dir = sys_get_temp_dir () . 'smarty/template_c/' . md5 ( $smarty->template_dir );
	if (! is_dir ( $smarty->compile_dir )) {
		if (mkdir ( $smarty->compile_dir, 0777, true ) === false) {
			trigger_error ( 'Connot create directory ' . $smarty->compile_dir , E_USER_ERROR );
		}
	}
} else {
	$smarty->compile_dir = $compile_dir;
}

// 传入参数
$smarty->_tpl_vars = $_view_vars2;

// 显示模板
if ( $_view_show2 ) {
	return $smarty->display ( $_view_file2 );
} else {
	return $smarty->fetch ( $_view_file2 );
}

?>