<?php
/**
 * Smarty 3.x连接器
 * 
 * 这是CoreMVC扩展文件，需下载Smarty 3.x并将其中的lib拷到smarty3目录中。
 */

/**
 * 执行(execute)
 */

// 配置信息
$template_dir = ''; //模板文件所在目录，默认使用当前路径
$compile_dir = ''; //模板文件所在目录，默认使用临时路径

// 载入类文件
if (! class_exists ( 'smarty' , false ) ) {
	require_once dirname(__FILE__) . '/' . basename(__FILE__,'.php') . '/Smarty.class.php';
}

// 创建实例
static $smarty = null;
if( $smarty === null ) {
	$smarty = new smarty;

	// 模板路径
	if ( $template_dir === '' ) {
		$smarty->template_dir = getcwd ();
	} else {
		$smarty->template_dir = $template_dir;
	}

	// 编译路径
	if ( $compile_dir === '' ) {
		if (function_exists ('sys_get_temp_dir')) {
			$_view_sys_get_temp_dir = realpath (sys_get_temp_dir ());
		} elseif ($_view_sys_get_temp_dir = getenv ('TMP')) {
			$_view_sys_get_temp_dir = realpath ($_view_sys_get_temp_dir);
		} elseif ($_view_sys_get_temp_dir = getenv ('TEMP')) {
			$_view_sys_get_temp_dir = realpath ($_view_sys_get_temp_dir);
		} elseif ($_view_sys_get_temp_dir = getenv ('TMPDIR')) {
			$_view_sys_get_temp_dir = realpath ($_view_sys_get_temp_dir);
		} else {
			$_view_sys_get_temp_dir = tempnam (__FILE__, '');
			if (file_exists($_view_sys_get_temp_dir)) {
				unlink($_view_sys_get_temp_dir);
				$_view_sys_get_temp_dir = realpath (dirname ($_view_sys_get_temp_dir));
			} else {
				$_view_sys_get_temp_dir = '/tmp';
			}
		}
		$smarty->compile_dir = $_view_sys_get_temp_dir . '/' . basename(__FILE__,'.php') . '/template_c/' . md5 ( $smarty->template_dir );
		if (! is_dir ( $smarty->compile_dir )) {
			if (mkdir ( $smarty->compile_dir, 0777, true ) === false) {
				trigger_error ( 'Connot create directory ' . $smarty->compile_dir , E_USER_ERROR );
			}
		}
	} else {
		$smarty->compile_dir = $compile_dir;
	}
}

// 传入参数
foreach ($_view_vars2 as $_key => $_val) {
	$smarty->tpl_vars[$_key] = new Smarty_variable($_val);
}

// 显示模板
if ( $_view_show2 ) {
	return $smarty->display ( $_view_file2 );
} else {
	return $smarty->fetch ( $_view_file2 );
}

?>