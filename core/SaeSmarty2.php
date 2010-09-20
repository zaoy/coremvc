<?php
/**
 * Sae的Smarty 2.x连接器
 * 
 * 这是CoreMVC扩展文件，需下载Smarty 2.x并将其中的lib拷到SzeSmarty2目录中，并需要SaeMemcacheWrapper2类库。
 */

/**
 * 导入(import)
 */
require_once dirname(__FILE__) . '/' . basename(__FILE__,'.php') . '/Smarty.class.php';

/**
 * 执行(execute)
 */

// 创建实例
static $smarty = null;
if( $smarty === null ) {
	$smarty = new smarty;

	// 模板路径
	$smarty->template_dir = getcwd ();

	// 编译路径
	if ($memcache = @memcache_init()) {
		require_once dirname(__FILE__) . '/SaeMemcacheWrapper2.php';
		SaeMemcacheWrapper2::$memcache = $memcache;
		$smarty->compile_dir = 'saemc2://smarty_data';
	} else {
		$smarty->compile_dir = SAE_TMP_PATH;
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

	// 缓存函数
	if (! function_exists ('smarty_function_cache')) {
		function smarty_function_cache($action, &$smarty_obj, &$cache_content, $tpl_file=null, $cache_id=null, $compile_id=null, $exp_time=null){
			$CacheID = md5($tpl_file.$cache_id.$compile_id);
			switch ($action) {
				case 'read':
					$data = memcache_get(SaeMemcacheWrapper2::$memcache, $CacheID);
					if ($data === false) {
						$cache_content = '';
						$return = false;
					} else {
						$cache_content = $data;
						$return = true;
					}
					break;
				case 'write':
					memcache_set(SaeMemcacheWrapper2::$memcache, $CacheID, $cache_content);
					$return = true;
					break;
				case 'clear':
					if(empty($cache_id) && empty($compile_id) && empty($tpl_file)) {
						$return = false;
					} else {
						memcache_delete(SaeMemcacheWrapper2::$memcache, $CacheID);
						$return = true;
					}
					break;
				default:
					$return = false;
					break;
			}
			return $return;
		}
	}
	if ($memcache) {
		$smarty->cache_handler_func = 'smarty_function_cache';
	} else {
		$smarty->cache_dir = SAE_TMP_PATH;
	}
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