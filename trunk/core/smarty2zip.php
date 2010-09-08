<?php
/**
 * Smarty 2.x连接器
 * 
 * 这是CoreMVC扩展文件，需使用smarty2zip目录下的SmartyZip。
 */

/**
 * 执行(execute)
 */

// 配置信息
$template_dir = ''; //模板文件所在目录，默认使用当前路径
$compile_dir = ''; //模板文件所在目录，默认使用临时路径

// 载入类文件
if (! class_exists ( 'smarty' , false ) ) {
	require_once dirname(__FILE__) . '/' . basename(__FILE__,'.php') . '/SmartyZip.php';
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

	// 注册函数
	if (! function_exists ('smarty_function_view')) {
		function smarty_function_view($params, &$smarty) {
			if (isset ($params ['file'])) {
				$file = $params ['file'];
				$vars = $params;
				unset ($vars ['file']);
				return call_user_func (array ($smarty->_view_self2, 'view'), $file, $vars, $smarty->_view_type2, false);
			} else {
				return '';
			}
		}
	}
	$smarty->_view_self2 = $_view_self2;
	$smarty->_view_type2 = $_view_type2;
	$smarty->register_function('view', 'smarty_function_view');
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