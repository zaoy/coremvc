<?php
/**
 * CoreMVC核心模块
 * 
 * @version 1.2.0 alpha 5
 * @author Z <602000@gmail.com>
 * @link http://www.coremvc.cn/
 */
/**
 * 定义(define)
 */
class core {

	/**
	 * 配置文件或配置数组
	 *
	 * @link http://www.coremvc.cn/api/core/config.php
	 */
	private static $config = '';
	private static $connect = '';

	/**
	 * 初始化函数（可继承）
	 *
	 * @link http://www.coremvc.cn/api/core/init.php
	 * @param mixed $config
	 * @param mixed &$variable
	 * @return mixed
	 */
	public static function init($config = null, &$variable = null) {

		// 引用参数处理
		if ($variable === null){
			$current_config = &self::$config;
		} else {
			$current_config = &$variable;
		}

		// 导入配置文件
		if (! is_array ($current_config)){
			if (empty ($current_config)){
				$current_config = array();
			} else {
				$first = strlen($current_config)>0 ? $current_config['0'] : '';
				if ($first === '@'){
					$config_file = dirname (__FILE__) . DIRECTORY_SEPARATOR . substr ($current_config, 1);
				} else {
					$config_file = $current_config;
				}
				$ext = strtolower (strrchr ($config_file, '.'));
				if ($ext === '.php') {
					if (is_file ($config_file)) {
						$current_config = @require $config_file;
					}
				} elseif ($ext === '.ini') {
					if (is_file ($config_file)) {
						$current_config = @parse_ini_file ($config_file);
					}
				}
				if (! is_array ($current_config)){
					$current_config = array ();
				}
			}
		}

		// 导入环境变量
		if (isset ($_SERVER [__CLASS__ . '_config']) && $_SERVER [__CLASS__ . '_config']) {
			$env_prefix = __CLASS__ . '_config_';
			$env_length = strlen($env_prefix);
			foreach ($_SERVER as $key=>$value) {
				if (strncmp ($key, $env_prefix, $env_length) === 0) {
					$current_config [substr ($key, $env_length)] = $value;
				}
			}
			// 导入配置文件
			$env_config = $_SERVER [__CLASS__ . '_config'];
			$first = $env_config ['0'];
			if ($first === '@'){
				$env_file = dirname (__FILE__) . DIRECTORY_SEPARATOR . substr ($env_config, 1);
			} else {
				$env_file = $env_config;
			}
			$ext = strtolower (strrchr ($env_file, '.'));
			if ($ext === '.php') {
				if (is_file ($env_file)) {
					$import_config = @require $env_file;
					if (is_array ($import_config) ){
						$current_config = array_merge ($current_config, $import_config);
					}
				}
			} elseif ($ext === '.ini') {
				if (is_file ($env_file)) {
					$import_config = @parse_ini_file ($env_file);
					if (is_array ($import_config) ){
						$current_config = array_merge ($current_config, $import_config);
					}
				}
			}
		}

		// 配置参数处理
		if (is_bool ($config)) {
			// 完全清空配置
			if ($config) {
				$current_config = array ();
			} else {
				return $current_config;
			}
		} elseif (is_array ($config)) {
			// 导入参数数组
			$current_config = array_merge ($current_config, $config);
		} elseif (is_string ($config)) {
			$ext = strtolower (strrchr ($config,'.'));
			if ($ext === '.php' || $ext === '.ini') {
				$config_file = self::path ($config, 'config');
				// 导入参数文件
				if ($ext === '.php') {
					if (is_file ($config_file)) {
						$import_config = @require $config_file;
						if (is_array ($import_config)) {
							$current_config = array_merge ($current_config, $import_config);
						}
					}
				} elseif ($ext === '.ini') {
					if (is_file ($config_file)) {
						$import_config = @parse_ini_file($config_file);
						if (is_array ($import_config)) {
							$current_config = array_merge ($current_config, $import_config);
						}
					}
				}
			} else {
				// 返回参数配置
				return isset($current_config [$config]) ? $current_config [$config] : '';
			}
		}

		// 需要初始化的函数
		if ($variable === null) {
			self::path ( $current_config );
			self::stub ( $current_config );
		}

		return $current_config;

	}

	/**
	 * 存根函数（可继承）
	 *
	 * @param bool $autoload_enable
	 * @param string $autoload_path
	 * @param string $autoload_extensions
	 * @param bool $autoload_prepend
	 * @return bool
	 */
	public static function stub($autoload_enable = null, $autoload_path = null, $autoload_extensions = null, $autoload_prepend = null) {

		// 非数组参数处理
		if (! is_array ($autoload_enable)) {
			// 初始化过程
			self::stub (array (
				'autoload_enable' => $autoload_enable,
				'autoload_path' => $autoload_path,
				'autoload_extensions' => $autoload_extensions,
				'autoload_prepend' => $autoload_prepend,
			));
			// 判断访问或者引用
			foreach ( debug_backtrace ( false ) as $row ) {
				switch ($row ['function']) {
					case 'include' :
					case 'require' :
					case 'include_once' :
					case 'require_once' :
					case 'spl_autoload_call' :
						return false;
				}
			}
			return true;
		}

		// 数组参数处理
		$config = self::init (false);
		$config = array (
			'autoload_enable' => isset ($config ['autoload_enable']) ? $config ['autoload_enable'] : '', 
			'autoload_path' => isset ($config ['autoload_path']) ? $config ['autoload_path'] : '', 
			'autoload_extensions' => isset ($config ['autoload_extensions']) ? $config ['autoload_extensions'] : '', 
			'autoload_prepend' =>isset ($config ['autoload_prepend']) ? $config ['autoload_prepend'] : '', 
		);
		foreach ( $config as $key => $value ) {
			isset ( $autoload_enable [$key] ) and $config [$key] = $autoload_enable [$key];
		}

		// 自动载入功能
		static $static_config = array (
			'autoload_enable' => '',
			'autoload_path' => '',
			'autoload_extensions' => '',
			'autoload_prepend' => '',
		);
		static $static_last = array (
			'autoload_path' => '',
			'include_path' => '',
			'autoload_extensions' => '',
			'spl_autoload_extensions' => '',
			'autoload_enable' => '',
			'spl_autoload_functions' => '',
			'autoload_realname' => '',
		);
		if ( $static_config !== $config ) {
			// 设置路径
			if ($static_config ['autoload_path'] !== $config ['autoload_path'] || $static_config ['autoload_prepend'] !== $config ['autoload_prepend']) {
				if (empty ($static_last ['autoload_path'])) {
					$static_last ['include_path'] = get_include_path ();
				}
				if (empty ($config ['autoload_path'])) {
					set_include_path ( $static_last ['include_path'] );
				} else {
					$autoload_realpath = self::path ( $config ['autoload_path'] );
					if (empty ($config ['autoload_prepend'])) {
						set_include_path ( $static_last ['include_path'] . PATH_SEPARATOR . $autoload_realpath );
					} else {
						set_include_path ( $autoload_realpath . PATH_SEPARATOR . $static_last ['include_path'] );
					}
				}
				$static_last ['autoload_path'] = $config ['autoload_path'];
			}
			// 设置扩展名
			if ($static_config ['autoload_extensions'] !== $config ['autoload_extensions']) {
				if (empty($static_last ['autoload_extensions'])) {
					 $static_last ['spl_autoload_extensions'] = spl_autoload_extensions ();
				}
				if (empty($config ['autoload_extensions'])) {
					spl_autoload_extensions (  $static_last ['spl_autoload_extensions'] );
				} else {
					spl_autoload_extensions ( $config ['autoload_extensions'] );
				}
				$static_last ['autoload_extensions'] = $config ['autoload_extensions'];
			}
			// 设置自动载入
			if ($static_config ['autoload_enable'] !== $config ['autoload_enable'] || $static_config ['autoload_prepend'] !== $config ['autoload_prepend']) {
				if (empty($static_last ['autoload_enable'])) {
					$static_last ['spl_autoload_functions'] = spl_autoload_functions ();
				}
				if (empty($config ['autoload_enable'])) {
					if (! in_array($static_last ['autoload_realname'],(array)$static_last ['spl_autoload_functions']) ) {
						spl_autoload_unregister ( $static_last ['autoload_realname'] );
					}
				} else {
					if ($config ['autoload_enable']===true) {
						$static_last ['autoload_realname'] = 'spl_autoload';
					} else {
						$static_last ['autoload_realname'] = $config ['autoload_enable'];
					}
					if ($static_last ['spl_autoload_functions'] === array('__autoload')) {
						spl_autoload_register ( '__autoload' );
					}
					if ( version_compare(PHP_VERSION,'5.3.0','>=') ) {
						if (empty($config ['autoload_prepend'])) {
							spl_autoload_register ( $static_last ['autoload_realname'], true, false );
						} else {
							spl_autoload_register ( $static_last ['autoload_realname'], true, true );
						}
					} else {
						spl_autoload_register ( $static_last ['autoload_realname'] );
					}
				}
				$static_last ['autoload_enable'] = $config ['autoload_enable'];
			}
			$static_config = $config;
		}

		return $config;

	}

	/**
	 * 入口函数（可继承）
	 *
	 * @param bool $framework_enable
	 * @param string $framework_require
	 * @param string $framework_mdoule
	 * @param string $framework_action
	 * @param string $framework_parameter
	 * @return bool
	 */
	public static function main($framework_enable = null, $framework_require = null, $framework_module = null, $framework_action = null, $framework_parameter = null) {

		// 跳转功能
		$framework_function = self::init ('framework_function');
		if (is_callable ($framework_function)) {
			self::init (array ('framework_function'=>''));
			return call_user_func ($framework_function, $framework_enable, $framework_require, $framework_module, $framework_action, $framework_parameter);
		}

		// 入口参数处理
		$config = self::init (false);
		isset ( $framework_enable ) or $framework_enable = isset ($config ['framework_enable']) ? $config ['framework_enable'] : '';
		isset ( $framework_require ) or $framework_require = isset ($config ['framework_require']) ? $config ['framework_require'] : '';
		isset ( $framework_module ) or $framework_module = isset ($config ['framework_module']) ? $config ['framework_module'] : '';
		isset ( $framework_action ) or $framework_action = isset ($config ['framework_action']) ? $config ['framework_action'] : '';
		isset ( $framework_parameter ) or $framework_parameter = isset ($config ['framework_parameter']) ? $config ['framework_parameter'] : '';
		$return_array = is_bool($framework_enable) || $framework_enable === '' ? array () : explode (',', $framework_enable);

		// 框架控制功能
		while ( $framework_enable ) {
			// 1. 默认
			$require = $framework_require;
			$module = $framework_module === '' ? '(static)|[file:1]!(self)' : $framework_module;
			$action = $framework_action === '' ? '[get:1]|index^(self)' : $framework_action;
			$parameter = $framework_parameter;
			$string = $require . ' ' . $module . ' ' . $action . ' ';
			$string.= is_array($parameter)?implode(' ',$parameter):$parameter;
			// 2. 数组
			if (stripos ( $string, '[get:' ) !== false) {
				$get_array = array_values($_GET) + $_GET;
				array_unshift ( $get_array, null);
			}
			if (stripos ( $string, '[post:' ) !== false) {
				$post_array = array_values($_POST) + $_POST;
				array_unshift ( $post_array, null);
			}
			if (stripos ( $string, '[query:' ) !== false) {
				$query_array = array ();
				if (isset ( $_SERVER ['QUERY_STRING'] )) {
					$query_string = $_SERVER['QUERY_STRING'];
					$query_array = explode('&',$query_string);
					array_unshift ( $query_array, $query_string );
				}
			}
			if (stripos ( $string, '[path:' ) !== false) {
				$path_array = array ();
				if (isset ( $_SERVER ['PATH_INFO'] )) {
					$path_info = trim($_SERVER ['PATH_INFO'],'/');
					$path_array [] = $path_info;
					$tok = strtok ( $path_info, '/' );
					while ( $tok !== false ) {
						$path_array [] = $tok;
						$tok = strtok ( '/' );
					}
				}
			}
			if (stripos ( $string, '[file:' ) !== false) {
				$file_array = array ();
				foreach ( debug_backtrace ( false ) as $row ) {
					strtok ( $row ['file'], '/\\' );
					while ( ($tok = strtok ( '/\\' )) !== false ) {
						array_unshift ( $file_array, $tok );
					}
					$file_array [0] = strtok ( $file_array [0], '.' );
					array_unshift ( $file_array, strtok ( '.' ) );
					break;
				}
			}
			// 3. 引用
			if ($require != '') {
				$reason = $require;
				$result = '';
				$pos1 = 0;
				while ( true ) {
					$pos2 = strpos ( $reason, '[', $pos1 );
					if ($pos2 === false) {
						$result .= substr ( $reason, $pos1 );
						break;
					}
					$result .= substr ( $reason, $pos1, $pos2 - $pos1 );
					$pos1 = $pos2 + 1;
					$pos2 = strpos ( $reason, ']', $pos1 );
					if ($pos2 === false) {
						break;
					}
					$tok = substr ( $reason, $pos1, $pos2 - $pos1 );
					$pos1 = $pos2 + 1;
					if (strpos ( $tok, ':' ) === false) {
						$str = isset ( $_GET [$tok] ) ? $_GET [$tok] : '';
					} else {
						list ( $key, $sub ) = explode ( ':', $tok );
						$key_lower = strtolower($key);
						$key_upper = strtoupper($key);
						$key_ucfirst = ucfirst($key_lower);
						$key_lcfirst = $key_upper;
						if ( strlen($key_lcfirst) > 0 ) {
							$key_lcfirst[0] = strtolower($key_lcfirst[0]);
						}
						switch ($key_lower){
							case 'get':
								$str = isset ( $get_array [$sub] ) ? $get_array [$sub] : '';
								break;
							case 'post':
								$str = isset ( $post_array [$sub] ) ? $post_array [$sub] : '';
								break;
							case 'query':
								$str = isset ( $query_array [$sub] ) ? $query_array [$sub] : '';
								break;
							case 'path':
								$str = isset ( $path_array [$sub] ) ? $path_array [$sub] : '';
								break;
							case 'file':
								$str = isset ( $file_array [$sub] ) ? $file_array [$sub] : '';
								break;
							default:
								$str = '';
								break;
						}
						switch ($key) {
							case $key_lower:
								break;
							case $key_upper:
								$str = strtoupper($str);
								break;
							case $key_ucfirst:
								$str = ucfirst(strtolower($str));
								break;
							case $key_lcfirst:
								$str = strtoupper($str);
								if ( strlen($str) > 0 ) {
									$str[0] = strtolower($str[0]);
								}
								break;
							default:
								$str = strtolower($str);
								break;
						}
					}
					if (preg_match ( '/^[a-zA-Z0-9_\x7f-\xff]+$/', $str ) === false) {
						$str = '';
					}
					$result .= $str;
				}
				$reason = $result;
				$result = '';
				$pos1 = 0;
				while ( true ) {
					$pos2 = strpos ( $reason, '{', $pos1 );
					if ($pos2 === false) {
						$result .= substr ( $reason, $pos1 );
						break;
					}
					$result .= substr ( $reason, $pos1, $pos2 - $pos1 );
					$pos1 = $pos2 + 1;
					$pos2 = strpos ( $reason, '}', $pos1 );
					if ($pos2 === false) {
						break;
					}
					$tok = substr ( $reason, $pos1, $pos2 - $pos1 );
					$pos1 = $pos2 + 1;
					$sub = $tok;
					if (isset ( $_POST [$sub] )) {
						$str = $_POST [$sub];
					} else {
						$str = '';
					}
					if (preg_match ( '/^[a-zA-Z0-9_\x7f-\xff]+$/', $str ) === false) {
						$str = '';
					}
					$result .= $str;
				}
				if (strpos ( $result, '(self)' ) !== false) {
					$result = str_replace ( '(self)', __CLASS__, $result );
				}
				if (strpos ( $result, '(static)' ) !== false) {
					if (function_exists ( 'get_called_class' )) {
						$result = str_replace ( '(static)', get_called_class (), $result );
					} else {
						$result = str_replace ( '(static)', '', $result );
					}
				}
				$require_not = explode ( '!', $result );
				$require_arr = explode ( '|', array_shift ( $require_not ) );
				$require_now = '';
				foreach ( $require_arr as $require_name ) {
					if ($require_name === '') {
						continue;
					}
					if (in_array ( $require_name, $require_not )) {
						continue;
					}
					$require_name = self::path ( $require_name );
					if (is_file ( $require_name )) {
						$require_now = $require_name;
						break;
					}
				}
				if ($require_now === '') {
					return false;
				} else {
					if ( in_array( 'require', $return_array ) ) {
						if (! in_array( 'module', $return_array ) && ! in_array( 'action', $return_array ) ) {
							return $require_now;
						}
					}
					require_once $require_name;
				}
			} else {
				if ( in_array( 'require', $return_array ) ) {
					if (! in_array( 'module', $return_array ) && ! in_array( 'action', $return_array ) ) {
						return '';
					}
				}
				$require_now = '';
			}
			// 4. 模块
			$reason = $module;
			$result = '';
			$pos1 = 0;
			while ( true ) {
				$pos2 = strpos ( $reason, '[', $pos1 );
				if ($pos2 === false) {
					$result .= substr ( $reason, $pos1 );
					break;
				}
				$result .= substr ( $reason, $pos1, $pos2 - $pos1 );
				$pos1 = $pos2 + 1;
				$pos2 = strpos ( $reason, ']', $pos1 );
				if ($pos2 === false) {
					break;
				}
				$tok = substr ( $reason, $pos1, $pos2 - $pos1 );
				$pos1 = $pos2 + 1;
				if (strpos ( $tok, ':' ) === false) {
					$str = isset ( $_GET [$tok] ) ? $_GET [$tok] : '';
				} else {
					list ( $key, $sub ) = explode ( ':', $tok );
					$key_lower = strtolower($key);
					$key_upper = strtoupper($key);
					$key_ucfirst = ucfirst($key_lower);
					$key_lcfirst = $key_upper;
					if ( strlen($key_lcfirst) > 0 ) {
						$key_lcfirst[0] = strtolower($key_lcfirst[0]);
					}
					switch ($key_lower){
						case 'get':
							$str = isset ( $get_array [$sub] ) ? $get_array [$sub] : '';
							break;
						case 'post':
							$str = isset ( $post_array [$sub] ) ? $post_array [$sub] : '';
							break;
						case 'query':
							$str = isset ( $query_array [$sub] ) ? $query_array [$sub] : '';
							break;
						case 'path':
							$str = isset ( $path_array [$sub] ) ? $path_array [$sub] : '';
							break;
						case 'file':
							$str = isset ( $file_array [$sub] ) ? $file_array [$sub] : '';
							break;
						default:
							$str = '';
							break;
					}
					switch ($key) {
						case $key_lower:
							break;
						case $key_upper:
							$str = strtoupper($str);
							break;
						case $key_ucfirst:
							$str = ucfirst(strtolower($str));
							break;
						case $key_lcfirst:
							$str = strtoupper($str);
							if ( strlen($str) > 0 ) {
								$str[0] = strtolower($str[0]);
							}
							break;
						default:
							$str = strtolower($str);
							break;
					}
				}
				if (preg_match ( '/^[a-zA-Z0-9_\x7f-\xff]+$/', $str ) === false) {
					$str = '';
				}
				$result .= $str;
			}
			$reason = $result;
			$result = '';
			$pos1 = 0;
			while ( true ) {
				$pos2 = strpos ( $reason, '{', $pos1 );
				if ($pos2 === false) {
					$result .= substr ( $reason, $pos1 );
					break;
				}
				$result .= substr ( $reason, $pos1, $pos2 - $pos1 );
				$pos1 = $pos2 + 1;
				$pos2 = strpos ( $reason, '}', $pos1 );
				if ($pos2 === false) {
					break;
				}
				$tok = substr ( $reason, $pos1, $pos2 - $pos1 );
				$pos1 = $pos2 + 1;
				$sub = $tok;
				if (isset ( $_POST [$sub] )) {
					$str = $_POST [$sub];
				} else {
					$str = '';
				}
				if (preg_match ( '/^[a-zA-Z0-9_\x7f-\xff]+$/', $str ) === false) {
					$str = '';
				}
				$result .= $str;
			}
			if (strpos ( $result, '(self)' ) !== false) {
				$result = str_replace ( '(self)', __CLASS__, $result );
			}
			if (strpos ( $result, '(static)' ) !== false) {
				if (function_exists ( 'get_called_class' )) {
					$result = str_replace ( '(static)', get_called_class (), $result );
				} else {
					$result = str_replace ( '(static)', '', $result );
				}
			}
			$module_not = explode ( '!', $result );
			$module_arr = explode ( '|', array_shift ( $module_not ) );
			$module_now = '';
			foreach ( $module_arr as $module_name ) {
				if ($module_name === '') {
					continue;
				}
				if (in_array ( $module_name, $module_not )) {
					continue;
				}
				if (preg_match ( '/(^|\\\\)[0-9]/', $module_name )) {
					continue;
				}
				try {
					$class_exists = class_exists ( $module_name );
				} catch ( Exception $e ) {
					continue;
				}
				if (! $class_exists) {
					continue;
				}
				$class = new ReflectionClass ( $module_name );
				if ( $class->isInternal () || $class->isAbstract () || $class->isInterface () ) {
					continue;
				}
				$module_now = $module_name;
				break;
			}
			if ($module_now === '') {
				if ( $return_array !== array() ) {
					return false;
				}
				break;
			} else {
				if ( in_array( 'module', $return_array ) ) {
					if (! in_array( 'action', $return_array ) ) {
						if ( in_array( 'require', $return_array ) ) {
							return array ($require_now, $module_now );
						} else {
							return $module_now;
						}
					}
				}
			}
			// 5. 动作
			$reason = $action;
			$result = '';
			$pos1 = 0;
			while ( true ) {
				$pos2 = strpos ( $reason, '[', $pos1 );
				if ($pos2 === false) {
					$result .= substr ( $reason, $pos1 );
					break;
				}
				$result .= substr ( $reason, $pos1, $pos2 - $pos1 );
				$pos1 = $pos2 + 1;
				$pos2 = strpos ( $reason, ']', $pos1 );
				if ($pos2 === false) {
					break;
				}
				$tok = substr ( $reason, $pos1, $pos2 - $pos1 );
				$pos1 = $pos2 + 1;
				if (strpos ( $tok, ':' ) === false) {
					$str = isset ( $_GET [$tok] ) ? $_GET [$tok] : '';
				} else {
					list ( $key, $sub ) = explode ( ':', $tok );
					$key_lower = strtolower($key);
					$key_upper = strtoupper($key);
					$key_ucfirst = ucfirst($key_lower);
					$key_lcfirst = $key_upper;
					if ( strlen($key_lcfirst) > 0 ) {
						$key_lcfirst[0] = strtolower($key_lcfirst[0]);
					}
					switch ($key_lower){
						case 'get':
							$str = isset ( $get_array [$sub] ) ? $get_array [$sub] : '';
							break;
						case 'post':
							$str = isset ( $post_array [$sub] ) ? $post_array [$sub] : '';
							break;
						case 'query':
							$str = isset ( $query_array [$sub] ) ? $query_array [$sub] : '';
							break;
						case 'path':
							$str = isset ( $path_array [$sub] ) ? $path_array [$sub] : '';
							break;
						case 'file':
							$str = isset ( $file_array [$sub] ) ? $file_array [$sub] : '';
							break;
						default:
							$str = '';
							break;
					}
					switch ($key) {
						case $key_lower:
							break;
						case $key_upper:
							$str = strtoupper($str);
							break;
						case $key_ucfirst:
							$str = ucfirst(strtolower($str));
							break;
						case $key_lcfirst:
							$str = strtoupper($str);
							if ( strlen($str) > 0 ) {
								$str[0] = strtolower($str[0]);
							}
							break;
						default:
							$str = strtolower($str);
							break;
					}
				}
				if (preg_match ( '/^[a-zA-Z0-9_\x7f-\xff]+$/', $str ) === false) {
					$str = '';
				}
				$result .= $str;
			}
			$reason = $result;
			$result = '';
			$pos1 = 0;
			while ( true ) {
				$pos2 = strpos ( $reason, '{', $pos1 );
				if ($pos2 === false) {
					$result .= substr ( $reason, $pos1 );
					break;
				}
				$result .= substr ( $reason, $pos1, $pos2 - $pos1 );
				$pos1 = $pos2 + 1;
				$pos2 = strpos ( $reason, '}', $pos1 );
				if ($pos2 === false) {
					break;
				}
				$tok = substr ( $reason, $pos1, $pos2 - $pos1 );
				$pos1 = $pos2 + 1;
				$sub = $tok;
				if (isset ( $_POST [$sub] )) {
					$str = $_POST [$sub];
				} else {
					$str = '';
				}
				if (preg_match ( '/^[a-zA-Z0-9_\x7f-\xff]+$/', $str ) === false) {
					$str = '';
				}
				$result .= $str;
			}
			if (strpos ( $result, '(self)' ) !== false) {
				$result = str_replace ( '(self)', __CLASS__, $result );
			}
			if (strpos ( $result, '(static)' ) !== false) {
				if (function_exists ( 'get_called_class' )) {
					$result = str_replace ( '(static)', get_called_class (), $result );
				} else {
					$result = str_replace ( '(static)', '', $result );
				}
			}
			$module_not = explode ( '^', $result );
			$module_arr = explode ( '&', array_shift ( $module_not ) );
			$action_not = explode ( '!', array_shift ( $module_arr ) );
			$action_arr = explode ( '|', array_shift ( $action_not ) );
			$module_not = array_map('strtolower', $module_not);
			$module_arr = array_map('strtolower', $module_arr);
			$action_now = '';
			$number_of_parameters = 0;
			foreach ( $action_arr as $action_name ) {
				if ($action_name === '') {
					continue;
				}
				if (in_array ( $action_name, $action_not )) {
					continue;
				}
				if (! method_exists ( $module_now, $action_name )) {
					continue;
				}
				$method = new ReflectionMethod ( $module_now, $action_name );
				if (! $method->isPublic () || $method->isConstructor () || $method->isDestructor () ) {
					continue;
				}
				if ( in_array( 'object', $return_array ) ) {
					if ( $method->isStatic () ) {
						continue;
					}
				} else {
					if (! $method->isStatic () ) {
						continue;
					}
				}
				if ( in_array( 'final', $return_array ) ) {
					if (! $method->isFinal () ) {
						continue;
					}
				}
				$classname = $method->getDeclaringClass()->getName();
				if ($module_arr && !in_array(strtolower($classname),$module_arr) ) {
					continue;
				}
				if ($module_not && in_array(strtolower($classname),$module_not) ) {
					continue;
				}
				$number_of_parameters = $method->getNumberOfParameters();
				$action_now = $action_name;
				break;
			}
			if ($action_now === '') {
				if ( $return_array !== array() ) {
					return false;
				}
				break;
			} else {
				if ( in_array( 'action', $return_array ) ) {
					if (! in_array( 'parameter', $return_array ) ) {
						if ( in_array( 'module', $return_array ) ) {
							if ( in_array( 'require', $return_array ) ) {
								return array ($require_now, $module_now, $action_now );
							} else {
								return array ($module_now, $action_now );
							}
						} else {
							return $action_now;
						}
					}
				}
			}
			// 6. 参数
			$parameter_array = array();
			if ($parameter != '') {
				if (!is_array($parameter)) {
					$parameter = array($parameter);
				}
				foreach ($parameter as $param) {
					$reason = $param;
					$result = '';
					$pos1 = 0;
					while ( true ) {
						$pos2 = strpos ( $reason, '[', $pos1 );
						if ($pos2 === false) {
							$result .= substr ( $reason, $pos1 );
							break;
						}
						$result .= substr ( $reason, $pos1, $pos2 - $pos1 );
						$pos1 = $pos2 + 1;
						$pos2 = strpos ( $reason, ']', $pos1 );
						if ($pos2 === false) {
							break;
						}
						$tok = substr ( $reason, $pos1, $pos2 - $pos1 );
						$pos1 = $pos2 + 1;
						if (strpos ( $tok, ':' ) === false) {
							$str = isset ( $_GET [$tok] ) ? $_GET [$tok] : '';
						} else {
							list ( $key, $sub ) = explode ( ':', $tok );
							$key_lower = strtolower($key);
							$key_upper = strtoupper($key);
							$key_ucfirst = ucfirst($key_lower);
							$key_lcfirst = $key_upper;
							if ( strlen($key_lcfirst) > 0 ) {
								$key_lcfirst[0] = strtolower($key_lcfirst[0]);
							}
							switch ($key_lower){
								case 'get':
									$str = isset ( $get_array [$sub] ) ? $get_array [$sub] : '';
									break;
								case 'post':
									$str = isset ( $post_array [$sub] ) ? $post_array [$sub] : '';
									break;
								case 'query':
									$str = isset ( $query_array [$sub] ) ? $query_array [$sub] : '';
									break;
								case 'path':
									$str = isset ( $path_array [$sub] ) ? $path_array [$sub] : '';
									break;
								case 'file':
									$str = isset ( $file_array [$sub] ) ? $file_array [$sub] : '';
									break;
								default:
									$str = '';
									break;
							}
							switch ($key) {
								case $key_lower:
									break;
								case $key_upper:
									$str = strtoupper($str);
									break;
								case $key_ucfirst:
									$str = ucfirst(strtolower($str));
									break;
								case $key_lcfirst:
									$str = strtoupper($str);
									if ( strlen($str) > 0 ) {
										$str[0] = strtolower($str[0]);
									}
									break;
								default:
									$str = strtolower($str);
									break;
							}
						}
						if (preg_match ( '/^[a-zA-Z0-9_\x7f-\xff]+$/', $str ) === false) {
							$str = '';
						}
						$result .= $str;
					}
					$reason = $result;
					$result = '';
					$pos1 = 0;
					while ( true ) {
						$pos2 = strpos ( $reason, '{', $pos1 );
						if ($pos2 === false) {
							$result .= substr ( $reason, $pos1 );
							break;
						}
						$result .= substr ( $reason, $pos1, $pos2 - $pos1 );
						$pos1 = $pos2 + 1;
						$pos2 = strpos ( $reason, '}', $pos1 );
						if ($pos2 === false) {
							break;
						}
						$tok = substr ( $reason, $pos1, $pos2 - $pos1 );
						$pos1 = $pos2 + 1;
						$sub = $tok;
						if (isset ( $_POST [$sub] )) {
							$str = $_POST [$sub];
						} else {
							$str = '';
						}
						if (preg_match ( '/^[a-zA-Z0-9_\x7f-\xff]+$/', $str ) === false) {
							$str = '';
						}
						$result .= $str;
					}
					if (strpos ( $result, '(self)' ) !== false) {
						$result = str_replace ( '(self)', __CLASS__, $result );
					}
					if (strpos ( $result, '(static)' ) !== false) {
						if (function_exists ( 'get_called_class' )) {
							$result = str_replace ( '(static)', get_called_class (), $result );
						} else {
							$result = str_replace ( '(static)', '', $result );
						}
					}
					$action_not = explode ( '^', $result );
					$action_arr = explode ( '&', array_shift ( $action_not ) );
					$param_not = explode ( '!', array_shift ( $action_arr ) );
					$param_arr = explode ( '|', array_shift ( $param_not ) );
					$action_not = array_map('strtolower', $action_not);
					$action_arr = array_map('strtolower', $action_arr);
					$param_now = '';
					foreach ( $param_arr as $param_name ) {
						if ($param_name === '') {
							continue;
						}
						if (in_array ( $param_name, $param_not )) {
							continue;
						}
						if ($action_arr && !in_array(strtolower($action_now),$action_arr) ) {
							continue;
						}
						if ($action_not && in_array(strtolower($action_now),$action_not) ) {
							continue;
						}
						$param_now = $param_name;
						break;
					}
					if($param_now==''){
						$parameter_array [] = null;
					} else {
						$parameter_array [] = $param_now;
					}
				}
			}
			if ( in_array( 'parameter', $return_array ) ) {
				if ( in_array( 'action', $return_array ) ) {
					if ( in_array( 'module', $return_array ) ) {
						if ( in_array( 'require', $return_array ) ) {
							return array ($require_now, $module_now, $action_now, $parameter_array );
						} else {
							return array ($module_now, $action_now, $parameter_array );
						}
					} else {
						return array ($action_now, $parameter_array );
					}
				} else {
					return $parameter_array;
				}
			}
			// 7. 执行
			if ( in_array( 'object', $return_array ) ) {
				$module_now = new $module_now;
			}
			$parameter_array = array_slice($parameter_array, 0, $number_of_parameters);
			$return = call_user_func_array ( array ($module_now, $action_now ) , $parameter_array );
			if ( in_array( 'return', $return_array ) ) {
				return $return;
			} else {
				return true;
			}
		}

		// 模拟文件隐藏效果
		if (! in_array( 'manual', $return_array ) ) {
			if (PHP_SAPI == 'cli') {
				echo ('Could not open input file: ' . basename ( $_SERVER ['SCRIPT_FILENAME'] ) . PHP_EOL);
			} else {
				header ( "HTTP/1.0 404 Not Found" );
			}
		}

		return false;

	}

	/**
	 * 路径函数
	 *
	 * @param string $filename
	 * @param string $filetype
	 * @return string
	 */
	public static function path($filename, $filetype = null) {

		// 非数组参数处理
		if (! is_array ($filename)) {
			// 生成基础路径
			$first = strlen($filename)>0 ? $filename['0'] : '';
			if ($first === '@') {
				return dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . substr ($filename, 1);
			} elseif (empty ($filetype) || $first === '\\' || $first === '/' || strncmp ( $filename, './', 2 ) == 0 || strncmp ( $filename, '.\\', 2 ) == 0 || strpos ( $filename, ':' ) !== false) {
				return $filename;
			}
			// 生成转义路径
			$config = self::init (false);
			switch ($filetype) {
				case 'extension' :
					$extension_path = isset ($config ['extension_path']) ? $config ['extension_path'] : '';
					if ($extension_path === '') {
						$filename = dirname (__FILE__) . DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . $filename;
					} elseif ($extension_path [0] === '@') {
						$filename =  dirname (__FILE__) . DIRECTORY_SEPARATOR . substr ($extension_path, 1) . DIRECTORY_SEPARATOR . $filename;
					} else {
						$filename = $extension_path . DIRECTORY_SEPARATOR . $filename;
					}
					break;
				case 'config' :
					$config_path = isset ($config ['config_path']) ? $config ['config_path'] : '';
					if ($config_path === '') {
					} elseif ($config_path [0] === '@') {
						$filename = dirname (__FILE__) . DIRECTORY_SEPARATOR . substr ($config_path, 1) . DIRECTORY_SEPARATOR . $filename;
					} else {
						$filename = $config_path . DIRECTORY_SEPARATOR . $filename;
					}
					break;
				case 'template' :
					$template_path = isset ($config ['template_path']) ? $config ['template_path'] : '';
					if ($template_path === '') {
					} elseif ($template_path [0] === '@') {
						$filename = dirname (__FILE__) . DIRECTORY_SEPARATOR . substr ($template_path, 1) . DIRECTORY_SEPARATOR . $filename;
					} else {
						$filename = $template_path . DIRECTORY_SEPARATOR . $filename;
					}
					break;
			}

			return $filename;
		}

		// 数组参数处理
		$config = self::init (false);
		$config = array (
			'extension_enable' => isset ($config ['extension_enable']) ? $config ['extension_enable'] : '', 
			'extension_path' => isset ($config ['extension_path']) ? $config ['extension_path'] : '', 
			'extension_prepend' => isset ($config ['extension_prepend']) ? $config ['extension_prepend'] : '', 
		);
		foreach ( $config as $key => $value ) {
			isset ( $filename [$key] ) and $config [$key] = $filename [$key];
		}

		// 扩展类库功能
		static $static_config = array(
			'extension_enable' => '',
			'extension_path' => '',
			'extension_prepend' => '',
		);
		if ( $static_config !== $config ) {
			$extension_enable = $config ['extension_enable'];
			$extension_prepend = $config['extension_prepend'];
			// 类库功能启用
			if ($extension_enable) {
				$extension_path = rtrim (self::path ('', 'extension'), '/\\');
				$include_path_array = explode (PATH_SEPARATOR, get_include_path ());
				if ( is_bool ($extension_prepend)) {
					if ( in_array ($extension_path, $include_path_array) ) {
						$include_path_array = array_values(array_diff($include_path_array, array($extension_path)));
					}
					if ($extension_prepend === true) {
						array_unshift($include_path_array, $extension_path);
					} else {
						array_push($include_path_array, $extension_path);
					}
					set_include_path ( implode (PATH_SEPARATOR, $include_path_array));
				} elseif ( !in_array ( $extension_path, $include_path_array ) ) {
					array_push($include_path_array, $extension_path);
					set_include_path ( implode (PATH_SEPARATOR, $include_path_array));
				}
				// 自动载入类库
				if ($extension_enable !== true) {
					$extension_array = explode (',', $extension_enable);
					foreach ($extension_array as $extension) {
						$extension_file = self::path ( trim($extension) . '.php', 'extension' );
						if (is_file ( $extension_file )) {
							require_once $extension_file;
						}
					}
				}
			}
			$static_config = $config;
		}

		return $config;

	}

	/**
	 * 视图函数（可继承）
	 *
	 * @param string $_view_file
	 * @param array $_view_vars
	 * @param string $_view_type
	 * @param bool $_view_show
	 * @return string
	 */
	public static function view($_view_file, $_view_vars = null, $_view_type = null, $_view_show = null) {

		// 视图参数处理
		$_view_config = self::init (false);
		$_view_config = array (
			'template_search' => isset ($_view_config ['template_search']) ? $_view_config ['template_search'] : '', 
			'template_replace' => isset ($_view_config ['template_replace']) ? $_view_config ['template_replace'] : '', 
			'template_type' => isset ($_view_config ['template_type']) ? $_view_config ['template_type'] : '', 
			'template_show' => isset ($_view_config ['template_show']) ? $_view_config ['template_show'] : '', 
		);
		isset ( $_view_type ) and $_view_config ['template_type'] = $_view_type;
		isset ( $_view_show ) and $_view_config ['template_show'] = $_view_show;

		// 视图数据处理
		if ($_view_config ['template_search'] !== '' && $_view_config ['template_search'] !== $_view_config ['template_replace']) {
			$_view_file2 = self::path (str_replace ($_view_config ['template_search'], $_view_config ['template_replace'], $_view_file), 'template');
		} else {
			$_view_file2 = self::path ($_view_file, 'template');
		}
		$_view_vars2 = is_array ($_view_vars) ? $_view_vars : array ();
		$_view_type2 = $_view_config ['template_type'] === '' ? 'include' : $_view_config ['template_type'];
		$_view_show2 = $_view_config ['template_show'] === '' ? true : $_view_config ['template_show'];

		// 视图模板处理
		switch ($_view_type2) {
			case 'include' :
				extract ($_view_vars2);
				if ($_view_show2) {
					return require $_view_file2;
				} else {
					ob_start ();
					require $_view_file2;
					return ob_get_clean ();
				}
			case 'string' :
				extract ($_view_vars2);
				if ($_view_show2) {
					return eval ('echo <<<_END_OF_EVAL' . PHP_EOL . file_get_contents ($_view_file2, FILE_USE_INCLUDE_PATH) . PHP_EOL . '_END_OF_EVAL;' . PHP_EOL);
				} else {
					return eval ('return <<<_END_OF_EVAL' . PHP_EOL . file_get_contents ($_view_file2, FILE_USE_INCLUDE_PATH) . PHP_EOL . '_END_OF_EVAL;' . PHP_EOL);
				}
			default :
				extract ( $_view_vars2 );
				$_view_extension = self::path ($_view_type2 . '.php', 'extension');
				if (is_file ( $_view_extension)) {
					return require $_view_extension;
				} else {
					return;
				}
		}

	}

	/**
	 * 数据库连接
	 *
	 * @link http://www.coremvc.cn/api/core/connect.php
	 * @param mix $args
	 * @param array &$ref
	 * @return $dbh
	 */
	public static function connect($args = null, &$ref = null) {


		// 导入配置文件
		$connect = &self::$connect;
		if (! is_array ($connect)){
			if (empty ($connect)){
				$connect = array();
			} else {
				$first = strlen($connect)>0 ? $connect['0'] : '';
				if ($first === '@'){
					$connect_file = dirname (__FILE__) . DIRECTORY_SEPARATOR . substr ($connect, 1);
				} else {
					$connect_file = $connect;
				}
				$ext = strtolower (strrchr ($connect_file, '.'));
				if ($ext === '.php') {
					if (is_file ($connect_file)) {
						$connect = @require $connect_file;
					}
				}
				if (! is_array ($connect)){
					$connect = array();
				}
			}
			// 导入环境变量
			if (isset ($_SERVER [__CLASS__ . '_connect']) && $_SERVER [__CLASS__ . '_connect']) {
				// 导入配置文件
				$env_config = $_SERVER [__CLASS__ . '_connect'];
				$first = $env_config ['0'];
				if ($first === '@'){
					$env_file = dirname (__FILE__) . DIRECTORY_SEPARATOR . substr ($env_config, 1);
				} else {
					$env_file = $env_config;
				}
				$ext = strtolower (strrchr ($env_file, '.'));
				if ($ext === '.php') {
					if (is_file ($env_file)) {
						$import_config = @require $env_file;
						if (is_array ($import_config) ){
							$connect = array_merge ($connect, $import_config);
						}
					}
				}
			}
		} elseif ($args === true && isset ($connect ['current']) && isset ($connect ['connections']) 
			&& isset ($connect ['configs'] [$connect ['current']]) && isset ($connect ['connections'] [$connect ['current']])) {
			// 返回当前连接
			$ref = $connect ['configs'] [$connect ['current']];
			return $connect ['connections'] [$connect ['current']];
		}

		// 选择指定连接
		if (is_int ($args)) {
			$connect ['current'] = $args;
		} elseif (! array_key_exists ('current', $connect)) {
			$connect ['current'] = 0;
		} elseif (! is_int ($connect ['current'])) {
			$connect ['current'] = (int)$connect ['current'];
		}
		$pos = $connect ['current'];
		if (! array_key_exists ('configs', $connect) || ! is_array ($connect ['configs'])) {
			$connect ['configs'] = array ();
		}
		if (! array_key_exists ('connections', $connect) || ! is_array ($connect ['connections'])) {
			$connect ['connections'] = array ();
		}
		if (! array_key_exists ($pos, $connect ['configs']) || ! is_array ($connect ['configs'] [$pos])) {
			$connect ['configs'] [$pos] = array ();
		}
		if (! array_key_exists ($pos, $connect ['connections'])) {
			$connect ['connections'] [$pos] = null;
		}
		$cfg = &$connect ['configs'] [$pos];
		$dbh = &$connect ['connections'] [$pos];
		if (is_int ( $args )) {
			$ref = $cfg;
			return $dbh;
		}

		if ($args === null) {
			// 返回所有参数
			$ref = $cfg;
			return $connect;
		} elseif (is_array ($args)) {
			// 设置参数配置
			foreach ($args as $key=>$value) {
				$cfg [$key] = $value;
			}
			$ref = $cfg;
			return $dbh;
		} elseif (is_string ($args)) {
			// 导入参数配置
			$ext = strtolower (strrchr ($args,'.'));
			if ($ext === '.php' || $ext === '.ini') {
				$config_file = self::path ($args, 'config');
				// 导入参数文件
				if ($ext === '.php') {
					if (is_file ($config_file)) {
						$import_config = @require $config_file;
						if (is_array ($import_config)) {
							$cfg = array_merge ($cfg, $import_config);
						}
					}
				} elseif ($ext === '.ini') {
					if (is_file ($config_file)) {
						$import_config = @parse_ini_file($config_file);
						if (is_array ($import_config)) {
							$cfg = array_merge ($cfg, $import_config);
						}
					}
				}
				$ref = $cfg;
				return $dbh;
			} else {
				// 返回参数配置
				$ref = $cfg;
				return isset($cfg [$args]) ? $cfg [$args] : '';
			}
		}


		// 设置当前连接
		$config = self::init (false);
		$properties = array ('connect_provider','connect_dsn','connect_type','connect_server','connect_username','connect_password',
			'connect_new_link','connect_client_flags','connect_dbname','connect_charset','connect_port','connect_socket',
			'connect_driver_options','prefix_search','prefix_replace','debug_enable','debug_file');
		foreach ($properties as $property) {
			if (! isset ($cfg [$property])) {
				if (isset ($config [$property])) {
					$cfg [$property] = $config [$property];
				} else {
					$cfg [$property] = '';
				}
			}
		}
		$ref = $cfg;

		// 断开数据库
		if ($args === false) {
			switch ($ref ['connect_provider']) {
				case '' :
				case 'mysql' :
					if (is_resource ( $dbh ) && get_resource_type ( $dbh ) == 'mysql link') {
						$return = mysql_close ( $dbh );
					} else {
						$return = false;
					}
					break;

				// 【扩展功能】断开数据库
				default :
					$provider = $ref ['connect_provider'];
					$provider_file = self::path ( $provider . '.php', 'extension' );
					if (is_file ($provider_file)) {
						require_once $provider_file;
						$return = call_user_func ( array ( $provider, 'disconnect' ), $dbh, $ref );
					} else {
						$return = false;
					}
					break;

			}
			$ref = $cfg = array ();
			$dbh = null;
			return $dbh;
		}

		// 连接数据库
		if ($args === true) {
			switch ($ref ['connect_provider']) {
				case '' :
				case 'mysql' :
					$type = $ref ['connect_type'];
					$server = $ref ['connect_server'];
					$username = $ref ['connect_username'];
					$password = $ref ['connect_password'];
					$new_link = $ref ['connect_new_link'];
					$client_flags = $ref ['connect_client_flags'];
					$dbname = $ref ['connect_dbname'];
					$charset = $ref ['connect_charset'];
					if ($type === 'persist') {
						$dbh = mysql_pconnect ( $server, $username, $password, ( int ) $client_flags );
					} else {
						$dbh = mysql_connect ( $server, $username, $password, ( bool ) $new_link, ( int ) $client_flags );
					}
					if ($dbname !== '') {
						mysql_select_db ( $dbname, $dbh );
					}
					if ($charset !== '') {
						mysql_set_charset ( $charset, $dbh );
					}
					break;

				// 【扩展功能】连接数据库
				default :
					$provider = $ref ['connect_provider'];
					$provider_file = self::path ( $provider . '.php', 'extension' );
					if (is_file ($provider_file)) {
						require_once $provider_file;
						$dbh = call_user_func ( array ( $provider, 'connect' ), $ref );
					} else {
						$dbh = null;
					}
					break;
			}

			return $dbh;

		}

	}

	/**
	 * 执行SQL语句
	 *
	 * + 作用：1.执行SQL语句，返回结果集；2.扩展方式。
	 * + 示例：
	 * <code>
	 * // 执行SQL语句 
	 *core::execute('SET NAMES GBK');
	 *
	 * // 执行带参数的SQL语句 
	 *$result = core::execute('SELECT * FROM test WHERE id=?', array(1));
	 *
	 * // 执行SQL语句，并返回执行信息 
	 *$result = core::execute('SELECT * FROM test', null, $ref);
	 *echo '查询总数：'.$ref['num_rows'];
	 *
	 * // 执行带参数的SQL语句，并返回执行信息 
	 *$result = core::execute('SELECT * FROM test WHERE name LIKE ?', array('test%'), $ref);
	 *echo '查询总数：'.$ref['num_rows'];
	 *
	 * // 执行带参数的SQL语句，同时使用附加参数（仅针对PDO） 
	 *$ref = array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY);
	 *$result = core::execute('SELECT * FROM test WHERE name=:name', array('name'=>'test'), $ref);
	 * </code>
	 * @param string $sql
	 * @param array $param
	 * @param array &$ref
	 * @return $result
	 */
	public static function execute($sql, $param = null, &$ref = null) {

		// 【基础功能】执行语句
		$ref_flag = (func_num_args () > 2);
		$dbh = self::connect ( true, $args );
		if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
			$sql = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $sql );
		}
		switch ($args ['connect_provider']) {
			case '' :
			case 'mysql' :
				if (is_array ( $param )) {
					$stmt = 'coremvc_mysql_stmt';
					$var = '@coremvc_mysql_var';
					$sql_set = '';
					$sql_unset = '';
					$using = '';
					$key = 0;
					foreach ( $param as $value ) {
						if ($value === null) {
							$value = 'NULL';
						} elseif ($value === true) {
							$value = '1';
						} elseif ($value === false) {
							$value = '0';
						} elseif (is_int ( $value ) || is_float ( $value )) {
						} else {
							$value = '\'' . mysql_real_escape_string ( ( string ) $value, $dbh ) . '\'';
						}
						$varname = $var . ++ $key;
						if ($key === 1) {
							$sql_set = 'SET ' . $varname . '=' . $value;
							$sql_unset = 'SET ' . $varname . '=NULL';
							$using = ' USING ' . $varname;
						} else {
							$sql_set .= ',' . $varname . '=' . $value;
							$sql_unset .= ',' . $varname . '=NULL';
							$using .= ',' . $varname;
						}
					}
					mysql_query ( 'PREPARE ' . $stmt . ' FROM \'' . mysql_real_escape_string ( $sql, $dbh ) . '\'', $dbh );
					if ($sql_set !== '') {
						mysql_query ( $sql_set, $dbh );
					}
					$result = mysql_query ( 'EXECUTE ' . $stmt . $using, $dbh );
				} else {
					$result = mysql_query ( $sql, $dbh );
				}
				if ($args ['debug_enable'] === true) {
					if ($result === false) {
						$extra = array( 'errno'=>mysql_errno ( $dbh ), 'error'=>mysql_error ( $dbh ) );
					} else {
						$extra = null;
					}
					self::prepare( $sql, $param, null, true, $args ['debug_file'] ,$extra );
				}
				if ($ref_flag) {
					$ref = array ();
					$ref ['insert_id'] = ( string ) mysql_insert_id ( $dbh );
					$ref ['affected_rows'] = max ( mysql_affected_rows ( $dbh ), 0 );
					$ref ['num_fields'] = is_resource ( $result ) ? mysql_num_fields ( $result ) : 0;
					$ref ['num_rows'] = is_resource ( $result ) ? mysql_num_rows ( $result ) : 0;
				}
				if (is_array ( $param )) {
					if ($sql_unset !== '') {
						mysql_query ( $sql_unset, $dbh );
					}
					mysql_query ( 'DEALLOCATE PREPARE ' . $stmt, $dbh );
				}
				break;

			// 【扩展功能】执行语句
			default :
				$provider = $args ['connect_provider'];
				if ($ref_flag) {
					$result = call_user_func_array ( array ($provider, 'execute' ), array ($dbh, $args, __CLASS__, $sql, $param, &$ref ) );
				} else {
					$result = call_user_func_array ( array ($provider, 'execute' ), array ($dbh, $args, __CLASS__, $sql, $param ) );
				}
				break;

		}
		return $result;

	}

	/**
	 * 准备SQL语句
	 *
	 * + 作用：1.准备SQL语句，返回SQL和参数的数组；2.调试SQL语句；3.扩展方式
	 * + 示例：
	 * <code>
	 * // 获得SQL语句和参数
	 *list($sql,$param) = core::prepare('selects',array($field,$table,$where,$other));
	 *
	 * // 获得不带参数的SQL语句
	 *$sql = core::prepare('selects',array($field,$table,$where,$other),true);
	 *
	 * // 获得子句
	 *$sql = core::prepare('field',array('col1','col2'),true);
	 *
	 * // 调试SQL语句
	 *core::prepare('SELECT ?,?',array(1,'a'),null,true); //页面显示SQL语句
	 *core::prepare('SELECT ?,?',array(1,'a'),null,true,'db.log'); //将SQL语句写入文件
	 *core::prepare('SELECT ?,?',array(1,'a'),null,true,null,array('errno'=>mysql_errno($dbh),'error'=>mysql_error($dbh))); //同时显示错误信息
	 * </code>
	 * @param string $sql
	 * @param array $param
	 * @param bool $format
	 * @param bool $debug
	 * @param string $output
	 * @param array $extra
	 * @return mix
	 */
	public static function prepare($sql, $param = null, $format = null, $debug = null, $output = null, $extra = null) {

		// 【基础功能】准备SQL语句
		$mysql_escape_search = array ("\\", "\x00", "\n", "\r", "'", "\"", "\x1a" );
		$mysql_escape_replace = array ("\\\\", "\\0", "\\n", "\\r", "\\'", "\\\"", "\\Z" );
		if (strncmp ( $sql, 'mysql_escape_', 13 ) === 0) {
			$debug_provider = 'mysql';
			if ($format) {
				$return_sql = array ();
				foreach ( $param as $key => $value ) {
					if ($value === array () || $key === 'page' && $sql === 'mysql_escape_other') {
						continue;
					}
					if (is_int ( $key )) {
						switch ($sql) {
							case 'mysql_escape_where' :
							case 'mysql_escape_where2' :
								if (is_array ( $value )) {
									$escape_sql = $sql === 'mysql_escape_where2' ? 'mysql_where' : 'mysql_where2';
									$return_sql [] = self::prepare ( $escape_sql, $value, true );
									break;
								}
							default :
								if ($value === null) {
									$value = 'NULL';
								} elseif ($value === true) {
									$value = '1';
								} elseif ($value === false) {
									$value = '0';
								} else {
									$value = ( string ) $value;
								}
								$return_sql [] = $value;
								break;
						}
						continue;
					}
					if (is_array ( $value )) {
						foreach ( $value as &$value2 ) {
							if ($value2 === null) {
								$value2 = 'NULL';
							} elseif ($value2 === true) {
								$value2 = '1';
							} elseif ($value2 === false) {
								$value2 = '0';
							} elseif (is_int ( $value2 ) || is_float ( $value2 )) {
							} else {
								$value2 = '\'' . str_replace ( $mysql_escape_search, $mysql_escape_replace, $value2 ) . '\'';
							}
						}
					} elseif ($value === null) {
						$value = 'NULL';
					} elseif ($value === true) {
						$value = '1';
					} elseif ($value === false) {
						$value = '0';
					} elseif (is_int ( $value ) || is_float ( $value )) {
					} else {
						$value = '\'' . str_replace ( $mysql_escape_search, $mysql_escape_replace, $value ) . '\'';
					}
					$pos = strpos ( $key, '?' );
					if ($pos === false) {
						if (is_array ( $value )) {
							switch ($sql) {
								case 'mysql_escape_set' :
									$sep1 = $key . '=CONCAT_WS(\',\',';
									$sep2 = ')';
									break;
								default :
								case 'mysql_escape_value' :
									$sep1 = 'CONCAT_WS(\',\',';
									$sep2 = ')';
									break;
								case 'mysql_escape_where' :
								case 'mysql_escape_where2' :
									$sep1 = $key . ' IN (';
									$sep2 = ')';
									break;
								case 'mysql_escape_other' :
									$sep1 = $key . ' ';
									$sep2 = '';
									break;
							}
							$return_sql [] = $sep1 . implode ( ',', $value ) . $sep2;
						} else {
							switch ($sql) {
								case 'mysql_escape_set' :
								case 'mysql_escape_where' :
								case 'mysql_escape_where2' :
									$sep = $key . '=';
									break;
								default :
								case 'mysql_escape_value' :
									$sep = '';
									break;
								case 'mysql_escape_other' :
									$sep = $key . ' ';
									break;
							}
							$return_sql [] = $sep . $value;
						}
					} else {
						if (is_array ( $value )) {
							$key = str_replace ( array ('%', '?' ), array ('%%', '%s' ), $key );
							$return_sql [] = vsprintf ( $key, $value );
						} else {
							$return_sql [] = substr_replace ( $key, $value, $pos, 1 );
						}
					}
				}
				$return = $return_sql;
			} else {
				$return_sql = array ();
				$return_param = array ();
				foreach ( $param as $key => $value ) {
					if ($value === array () || $key === 'page' && $sql === 'mysql_escape_other') {
						continue;
					}
					if (is_int ( $key )) {
						switch ($sql) {
							case 'mysql_escape_where' :
							case 'mysql_escape_where2' :
								if (is_array ( $value )) {
									$escape_sql = $sql === 'mysql_escape_where2' ? 'mysql_where' : 'mysql_where2';
									list ( $list_sql, $list_param ) = self::prepare ( $escape_sql, $value );
									$return_sql [] = $list_sql;
									foreach ( $list_param as &$value2 ) {
										$return_param [] = $value2;
									}
									break;
								}
							default :
								if ($value === null) {
									$value = 'NULL';
								} elseif ($value === true) {
									$value = '1';
								} elseif ($value === false) {
									$value = '0';
								} else {
									$value = ( string ) $value;
								}
								$return_sql [] = $value;
								break;
						}
						continue;
					}
					$pos = strpos ( $key, '?' );
					if ($pos === false) {
						if (is_array ( $value )) {
							switch ($sql) {
								case 'mysql_escape_set' :
									$sep1 = $key . '=CONCAT_WS(\',\',';
									$sep2 = ')';
									break;
								default :
								case 'mysql_escape_value' :
									$sep1 = 'CONCAT_WS(\',\',';
									$sep2 = ')';
									break;
								case 'mysql_escape_where' :
								case 'mysql_escape_where2' :
									$sep1 = $key . ' IN (';
									$sep2 = ')';
									break;
								case 'mysql_escape_other' :
									$sep1 = $key . ' ';
									$sep2 = '';
									break;
							}
							$return_sql [] = $sep1 . implode ( ',', array_fill ( 0, count ( $value ), '?' ) ) . $sep2;
							foreach ( $value as &$value2 ) {
								$return_param [] = $value2;
							}
						} else {
							switch ($sql) {
								case 'mysql_escape_set' :
								case 'mysql_escape_where' :
								case 'mysql_escape_where2' :
									$sep = $key . '=';
									break;
								default :
								case 'mysql_escape_value' :
									$sep = '';
									break;
								case 'mysql_escape_other' :
									$sep = $key . ' ';
									break;
							}
							$return_sql [] = $sep . '?';
							$return_param [] = $value;
						}
					} else {
						if (is_array ( $value )) {
							$return_sql [] = $key;
							foreach ( $value as &$value2 ) {
								$return_param [] = $value2;
							}
						} else {
							$return_sql [] = $key;
							$return_param [] = $value;
						}
					}
				}
				$return = array ($return_sql, $return_param );
			}
		} else {
			if (strncmp ( $sql, 'mysql_', 6 ) === 0) {
				$mysql = $sql;
				$sql = substr($sql,6);
				$debug_provider = 'mysql';
			} else {
				$dbh = self::connect ( true, $args );
				switch ($args ['connect_provider']) {
					case '' :
						$mysql = 'mysql_' . $sql;
						$debug_provider = 'mysql';
						break;
					case 'mysql' :
						$mysql = 'mysql_' . $sql;
						$debug_provider = 'mysql';
						break;
					default :
						$mysql = false;
						$debug_provider = $args ['connect_provider'];
						break;
				}
			}
			switch ($mysql) {
				case 'mysql_selects' :
					isset ( $param [0] ) or $param [0] = null;
					isset ( $param [1] ) or $param [1] = null;
					isset ( $param [2] ) or $param [2] = null;
					isset ( $param [3] ) or $param [3] = null;
					if ($format) {
						$field_sql = self::prepare ( 'mysql_field', $param [0], true );
						$table_sql = self::prepare ( 'mysql_table', $param [1], true );
						$where_sql = self::prepare ( 'mysql_where', $param [2], true );
						$other_sql = self::prepare ( 'mysql_other', $param [3], true );
						if ($field_sql === '') {
							$field_sql = '*';
						}
						if ($table_sql === '') {
							$return_sql = "SELECT $field_sql $other_sql";
						} elseif ($where_sql === '') {
							$return_sql = "SELECT $field_sql FROM $table_sql $other_sql";
						} else {
							$return_sql = "SELECT $field_sql FROM $table_sql WHERE $where_sql $other_sql";
						}
						$return = rtrim ( $return_sql );
					} else {
						list ( $field_sql, $field_param ) = self::prepare ( 'field', $param [0] );
						list ( $table_sql, $table_param ) = self::prepare ( 'table', $param [1] );
						list ( $where_sql, $where_param ) = self::prepare ( 'where', $param [2] );
						list ( $other_sql, $other_param ) = self::prepare ( 'other', $param [3] );
						if ($field_sql === '') {
							$field_sql = '*';
						}
						if ($table_sql === '') {
							$return_sql = "SELECT $field_sql $other_sql";
							$return_param = array_merge ( $field_param, $other_param );
						} elseif ($where_sql === '') {
							$return_sql = "SELECT $field_sql FROM $table_sql $other_sql";
							$return_param = array_merge ( $field_param, $table_param, $other_param );
						} else {
							$return_sql = "SELECT $field_sql FROM $table_sql WHERE $where_sql $other_sql";
							$return_param = array_merge ( $field_param, $table_param, $where_param, $other_param );
						}
						$return = array (rtrim ( $return_sql ), $return_param );
					}
					break;
				case 'mysql_inserts' :
				case 'mysql_replaces' :
					isset ( $param [0] ) or $param [0] = null;
					isset ( $param [1] ) or $param [1] = null;
					isset ( $param [2] ) or $param [2] = null;
					isset ( $param [3] ) or $param [3] = null;
					$mysql_sql = strtoupper ( substr ( substr ( $mysql, 6 ), 0, - 1 ) );
					if ($format) {
						$table_sql = self::prepare ( 'mysql_table', $param [0], true );
						$other_sql = self::prepare ( 'mysql_other', $param [3], true );
						if (isset ( $param [2] )) {
							$column_sql = self::prepare ( 'mysql_column', $param [1], true );
							$value_sql = self::prepare ( 'mysql_value', $param [2], true );
							$return_sql = "$mysql_sql $table_sql $column_sql VALUES $value_sql $other_sql";
						} elseif (is_array ( $param [1] ) && count ( $param [1] ) > 0 && ! isset ( $param [1] [count ( $param [1] ) - 1] )) {
							$set_sql = self::prepare ( 'mysql_set', $param [1], true );
							$return_sql = "$mysql_sql $table_sql SET $set_sql $other_sql";
						} else {
							$column_sql = self::prepare ( 'mysql_column', $param [1], true );
							$return_sql = "$mysql_sql $table_sql $column_sql $other_sql";
						}
						$return = rtrim ( $return_sql );
					} else {
						list ( $table_sql, $table_param ) = self::prepare ( 'mysql_table', $param [0] );
						list ( $other_sql, $other_param ) = self::prepare ( 'mysql_other', $param [3] );
						if (isset ( $param [2] )) {
							list ( $column_sql, $column_param ) = self::prepare ( 'mysql_column', $param [1] );
							list ( $value_sql, $value_param ) = self::prepare ( 'mysql_value', $param [2] );
							$return_sql = "$mysql_sql $table_sql $column_sql VALUES $value_sql $other_sql";
							$return_param = array_merge ( $table_param, $column_param, $value_param, $other_param );
						} elseif (is_array ( $param [1] ) && count ( $param [1] ) > 0 && ! isset ( $param [1] [count ( $param [1] ) - 1] )) {
							list ( $set_sql, $set_param ) = self::prepare ( 'mysql_set', $param [1] );
							$return_sql = "$mysql_sql $table_sql SET $set_sql $other_sql";
							$return_param = array_merge ( $table_param, $set_param, $other_param );
						} else {
							list ( $column_sql, $column_param ) = self::prepare ( 'mysql_column', $param [1] );
							$return_sql = "$mysql_sql $table_sql $column_sql $other_sql";
							$return_param = array_merge ( $table_param, $column_param, $other_param );
						}
						$return = array (rtrim ( $return_sql ), $return_param );
					}
					break;
				case 'mysql_updates' :
					isset ( $param [0] ) or $param [0] = null;
					isset ( $param [1] ) or $param [1] = null;
					isset ( $param [2] ) or $param [2] = null;
					isset ( $param [3] ) or $param [3] = null;
					if ($format) {
						$table_sql = self::prepare ( 'mysql_table', $param [0], true );
						$set_sql = self::prepare ( 'mysql_set', $param [1], true );
						$where_sql = self::prepare ( 'mysql_where', $param [2], true );
						$other_sql = self::prepare ( 'mysql_other', $param [3], true );
						if ($where_sql === '') {
							$return_sql = "UPDATE $table_sql SET $set_sql $other_sql";
						} else {
							$return_sql = "UPDATE $table_sql SET $set_sql WHERE $where_sql $other_sql";
						}
						$return = rtrim ( $return_sql );
					} else {
						list ( $table_sql, $table_param ) = self::prepare ( 'mysql_table', $param [0] );
						list ( $set_sql, $set_param ) = self::prepare ( 'mysql_set', $param [1] );
						list ( $where_sql, $where_param ) = self::prepare ( 'mysql_where', $param [2] );
						list ( $other_sql, $other_param ) = self::prepare ( 'mysql_other', $param [3] );
						if ($where_sql === '') {
							$return_sql = "UPDATE $table_sql SET $set_sql $other_sql";
							$return_param = array_merge ( $table_param, $set_param, $other_param );
						} else {
							$return_sql = "UPDATE $table_sql SET $set_sql WHERE $where_sql $other_sql";
							$return_param = array_merge ( $table_param, $set_param, $where_param, $other_param );
						}
						$return = array (rtrim ( $return_sql ), $return_param );
					}
					break;
				case 'mysql_deletes' :
					isset ( $param [0] ) or $param [0] = null;
					isset ( $param [1] ) or $param [1] = null;
					isset ( $param [2] ) or $param [2] = null;
					isset ( $param [3] ) or $param [3] = null;
					if ($format) {
						$field_sql = self::prepare ( 'mysql_field', $param [0], true );
						$table_sql = self::prepare ( 'mysql_table', $param [1], true );
						$where_sql = self::prepare ( 'mysql_where', $param [2], true );
						$other_sql = self::prepare ( 'mysql_other', $param [3], true );
						if ($where_sql === '') {
							$return_sql = "DELETE $field_sql FROM $table_sql $other_sql";
						} else {
							$return_sql = "DELETE $field_sql FROM $table_sql WHERE $where_sql $other_sql";
						}
						$return = rtrim ( $return_sql );
					} else {
						list ( $field_sql, $field_param ) = self::prepare ( 'mysql_field', $param [0] );
						list ( $table_sql, $table_param ) = self::prepare ( 'mysql_table', $param [1] );
						list ( $where_sql, $where_param ) = self::prepare ( 'mysql_where', $param [2] );
						list ( $other_sql, $other_param ) = self::prepare ( 'mysql_other', $param [3] );
						if ($where_sql === '') {
							$return_sql = "DELETE $field_sql FROM $table_sql $other_sql";
							$return_param = array_merge ( $field_param, $table_param, $other_param );
						} else {
							$return_sql = "DELETE $field_sql FROM $table_sql WHERE $where_sql $other_sql";
							$return_param = array_merge ( $field_param, $table_param, $where_param, $other_param );
						}
						$return = array (rtrim ( $return_sql ), $return_param );
					}
					break;
				case 'mysql_column' :
					if (is_array ( $param )) {
						$return_sql = '';
						$expect = '';
						foreach ( $param as $key => $value ) {
							if ($value === null) {
								$value = 'NULL';
							} elseif ($value === true) {
								$value = '1';
							} elseif ($value === false) {
								$value = '0';
							} else {
								$value = ( string ) $value;
							}
							$return_sql .= $expect . $value;
							$expect = ',';
						}
					} else {
						$return_sql = ( string ) $param;
					}
					if ($return_sql !== '') {
						$return_sql = '(' . $return_sql . ')';
					}
					$return = $format ? $return_sql : array ($return_sql, array () );
					break;
				case 'mysql_field' :
				case 'mysql_table' :
					if (is_array ( $param )) {
						$return_sql = '';
						$expect = '';
						foreach ( $param as $key => $value ) {
							if (is_array ( $value )) {
								foreach ( $value as $value2 ) {
									if ($value2 === null) {
										$value2 = 'NULL';
									} elseif ($value2 === true) {
										$value2 = '1';
									} elseif ($value2 === false) {
										$value2 = '0';
									} else {
										$value2 = ( string ) $value2;
									}
									$return_sql .= $expect . $value2;
									$expect = ' ';
								}
							} else {
								if ($value === null) {
									$value = 'NULL';
								} elseif ($value === true) {
									$value = '1';
								} elseif ($value === false) {
									$value = '0';
								} else {
									$value = ( string ) $value;
								}
								if (is_int ( $key )) {
									$return_sql .= $expect . $value;
								} else {
									$return_sql .= $expect . $value . ' AS ' . $key;
								}
								$expect = ',';
							}
						}
					} else {
						$return_sql = ( string ) $param;
					}
					$return = $format ? $return_sql : array ($return_sql, array () );
					break;
				case 'mysql_set' :
				case 'mysql_other' :
					$escape_sql = substr_replace ( $mysql, 'escape_', 6, 0 );
					if (is_array ( $param )) {
						if ($format) {
							$return_sql = implode ( ',', self::prepare ( $escape_sql, $param, true ) );
						} else {
							list ( $return_sql, $return_param ) = self::prepare ( $escape_sql, $param );
							$return_sql = implode ( ',', $return_sql );
						}
					} else {
						$return_sql = ( string ) $param;
						$return_param = array ();
					}
					$return = $format ? $return_sql : array ($return_sql, $return_param );
					break;
				case 'mysql_where' :
				case 'mysql_where2' :
					$escape_sql = substr_replace ( $mysql, 'escape_', 6, 0 );
					$escape_sep = $mysql === 'mysql_where2' ? ' OR ' : ' AND ';
					if (is_array ( $param )) {
						if ($format) {
							$return_sql = implode ( $escape_sep, self::prepare ( $escape_sql, $param, true ) );
						} else {
							list ( $return_sql, $return_param ) = self::prepare ( $escape_sql, $param );
							$return_sql = implode ( $escape_sep, $return_sql );
						}
					} else {
						$return_sql = ( string ) $param;
						$return_param = array ();
					}
					if ($mysql === 'mysql_where2' && $return_sql !== '') {
						$return_sql = '(' . $return_sql . ')';
					}
					$return = $format ? $return_sql : array ($return_sql, $return_param );
					break;
				case 'mysql_value' :
					if (is_array ( $param )) {
						if (isset ( $param [0] ) && is_array ( $param [0] )) {
							$return_sql = '';
							$return_param = array ();
							$expect = '';
							foreach ( $param as $key => $value ) {
								if ($format) {
									$value_sql = implode ( ',', self::prepare ( 'mysql_escape_value', $value, true ) );
								} else {
									list ( $value_sql, $value_param ) = self::prepare ( 'mysql_escape_value', $value );
									$value_sql = implode ( ',', $value_sql );
									foreach ( $value_param as $value_value ) {
										$return_param [] = $value_value;
									}
								}
								if ($value_sql !== '') {
									$return_sql .= $expect . '(' . $value_sql . ')';
									$expect = ',';
								}
							}
						} else {
							if ($format) {
								$return_sql = implode ( ',', self::prepare ( 'mysql_escape_value', $param, true ) );
							} else {
								list ( $return_sql, $return_param ) = self::prepare ( 'mysql_escape_value', $param );
								$return_sql = implode ( ',', $return_sql );
							}
							if ($return_sql !== '') {
								$return_sql = '(' . $return_sql . ')';
							}
						}
					} else {
						$return_sql = ( string ) $param;
						$return_param = array ();
						if ($return_sql !== '') {
							$return_sql = '(' . $return_sql . ')';
						}
					}
					$return = $format ? $return_sql : array ($return_sql, $return_param );
					break;
				case false :

					// 【扩展功能】准备SQL语句
					$provider = $args ['connect_provider'];
					if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
						$sql = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $sql );
					}
					$return = call_user_func ( array ($provider, 'prepare' ), $dbh, $args, __CLASS__, $sql, $param, $format );

					break;
				default :
					if ($format) {
						$return_sql = str_replace ( array ('%', '?' ), array ('%%', '%s' ), $sql );
						if (is_array($param)){
							$return_param = $param;
							foreach ( $return_param as &$value ) {
								if ($value === null) {
									$value = 'NULL';
								} elseif ($value === true) {
									$value = '1';
								} elseif ($value === false) {
									$value = '0';
								} elseif (is_int ( $value ) || is_float ( $value )) {
								} else {
									$value = '\'' . str_replace ( $mysql_escape_search, $mysql_escape_replace, $value ) . '\'';
								}
							}
							$return_sql = vsprintf ( $return_sql, $return_param );
						}
						$return = $return_sql;
					} else {
						$return = array ($sql, $param );
					}
					break;
			}
		}

		// 【基础功能】调试SQL语句
		if ( $debug ) {
			if ($format) {
				$debug_sql = $return;
				$debug_param = null;
			} else {
				list($debug_sql,$debug_param) = $return;
			}
			$echo2 = null;
			if (is_array ( $debug_param )) {
				$i = 0;
				$echo2 = '';
				foreach ( $debug_param as $value ) {
					$echo2 .= PHP_EOL . '#'. ($i++) . ': ';
					if ($value === null) {
						$echo2 .= 'NULL';
					} elseif ($value === true) {
						$echo2 .= 'bool(true)';
					} elseif ($value === false) {
						$echo2 .= 'bool(false)';
					} elseif (is_int ( $value )) {
						$echo2 .= 'int(' . $value . ')';
					} elseif (is_float ( $value )) {
						$echo2 .= 'float(' . $value . ')';
					} else {
						$echo2 .= 'string(' . strlen($value) . ') ' . $value;
					}
				}
			}
			if (PHP_SAPI == 'cli' || !empty($output)) {
				$echo = PHP_EOL . '(' . $debug_provider . '): ' . $debug_sql;
				if ( !empty($echo2) ) {
					$echo .= $echo2;
				}
				$echo .= PHP_EOL;
				if ( !empty($extra['errno']) ) {
					$echo .= $extra['errno'] . ": " . $extra['error'] . PHP_EOL;
				}
			} else {
				$echo = PHP_EOL . '<hr />' . PHP_EOL . '(' . $debug_provider . '): ' . htmlentities ( $debug_sql ) .PHP_EOL;
				if ( !empty($echo2) ) {
					$echo .= str_replace ( PHP_EOL, '<br />'.PHP_EOL, htmlentities ($echo2) );
				}
				$echo .= '<hr />' . PHP_EOL;
				if ( !empty($extra['errno']) ) {
					$echo .= $extra['errno'] . ": " . htmlentities ( $extra['error'] ) . '<br />' . PHP_EOL;
				}
			}
			if (empty($output)) {
				echo $echo;
			} else {
				file_put_contents ( self::path($output), $echo, FILE_APPEND );
			}
		}

		return $return;

	}

	/**
	 * 生成自增序列（可继承）
	 *
	 * + 作用：1.生成自增序列，返回序列号；2.扩展方式。
	 * + 示例：
	 * <code>
	 * // 使用默认自增序列的情况。 
	 *$seq = core::sequence();
	 *
	 * // 使用多个独立的自增序列的情况。 
	 *$user_id = core::sequence('sequence_user');
	 *$role_id = core::sequence('sequence_role');
	 *
	 * // 使用特定开始值的自增序列的情况。 
	 *$type_id = core::sequence('sequence_type',10000);
	 * </code>
	 * @param string $tablename
	 * @param int $start_index
	 * @return int
	 */
	public static function sequence($tablename = 'sequence', $start_index = 1) {

		// 【基础功能】生成自增序列
		$dbh = self::connect ( true, $args );
		// 表名
		if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
			$tablename = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $tablename );
		}
		// 执行
		switch ($args ['connect_provider']) {
			case '' :
			case 'mysql' :
				$result = mysql_query ( 'UPDATE ' . $tablename . ' SET id=LAST_INSERT_ID(id+1)', $dbh );
				if ($result === false) {
					mysql_query ( 'CREATE TABLE ' . $tablename . ' (id INT NOT NULL)', $dbh );
					$rs = mysql_query ( 'SELECT COUNT(*) FROM ' . $tablename . ' LIMIT 1', $dbh );
					if (mysql_result ( $rs, 0 ) == 0) {
						mysql_query ( 'INSERT INTO ' . $tablename . ' VALUES (' . ($start_index - 1) . ')', $dbh );
					}
					mysql_query ( 'UPDATE ' . $tablename . ' SET id=LAST_INSERT_ID(id+1)', $dbh );
				}
				$return = mysql_insert_id ( $dbh );
				if ($return === false) {
					return false;
				}
				if ($start_index > $return) {
					mysql_query ( 'UPDATE ' . $tablename . ' SET id=' . $start_index, $dbh );
					$return = $start_index;
				}
				break;

			// 【扩展功能】生成自增序列
			default :
				$provider = $args ['connect_provider'];
				$return = call_user_func ( array ($provider, 'sequence' ), $dbh, $args, __CLASS__, $tablename, $start_index );
				break;

		}
		return $return;
	}

	/**
	 * 静态构造函数（可继承）
	 *
	 * + 作用：1.静态构造函数，返回构造数组。
	 * + 示例：
	 * <code>
	 * // 返回对象数组。
	 *$result = core::structs($array_arr); //传入二维数组，返回对象数组
	 *$result = core::structs($object_arr); //传入对象数组，返回对象数组
	 *$result = core::structs($object_arr, null); //同上
	 *$result = core::structs($object_arr, 'core'); //同上
	 *$result = core::structs($object_arr, array(null, null)); //同上
	 *$result = core::structs($object_arr, array(null, 'core')); //同上
	 *$result = core::structs($object_arr, array(null, 'class'=>null)); //同上
	 *$result = core::structs($object_arr, array(null, 'class'=>'core')); //同上
	 *$result = account::structs($object_arr); //PHP5.2返回core对象数组，PHP5.3返回account对象数组
	 *
	 * // 返回各类数组。 
	 *$result = core::structs($object_arr, array(null, 'assoc'=>null)); //返回关联数组的数组
	 *$result = core::structs($object_arr, array(null, 'num'=>null)); //返回二维数组
	 *$result = core::structs($object_arr, array(null, 'both'=>null)); //返回关联二维数组
	 *$result = core::structs($object_arr, array(null, 'array'=>array('id'=>null,'name'=>null))); //返回指定二维数组
	 *$result = core::structs($object_arr, array(null, 'column'=>0)); //返回数值数组
	 *$result = core::structs($object_arr, array(null, 'column'=>'id')); //返回数值数组
	 *$result = core::structs($object_arr, array(null, 'class'=>null)); //返回对象数组，PHP5.3以调用为对象
	 *$result = core::structs($object_arr, array(null, 'class'=>'account')); //返回对象数组，
	 *$result = core::structs($object_arr, array(null, 'class|classtype'=>null)); //返回对象数组，以第一个字段为对象
	 *$result = core::structs($object_arr, array(null, 'clone'=>new account)); //返回对象数组
	 *
	 * // 返回各种数值。 
	 *$result = core::structs($object_arr, array('assoc'=>null)); //返回关联数组
	 *$result = core::structs($object_arr, array('column'=>'id')); //返回数值
	 *$result = core::structs($object_arr, array('class'=>'account')); //返回单个对象
	 *$result = core::structs($object_arr, array('id', 'class'=>'account')); //返回id为下标的对象数组
	 *$result = core::structs($object_arr, array('name',null ,'class'=>'account')); //返回name为下标的对象数组的数组
	 * </code>
	 * @param array $array
	 * @param array $class
	 * @return array
	 */
	public static function structs($array = null, $class = null) {

		// 【基础功能】构造对象数组
		// 数组
		if ($array === null || $array === '') {
			$data_arr = array (null );
		} elseif (is_object ( $array )) {
			$data_arr = array ($array );
		} elseif (is_array ( $array )) {
			$data_arr = $array;
		} else {
			return;
		}
		// 类名
		if ($class === null || $class === '') {
			$class_arr = array (null, 'class' => null );
		} elseif (is_string ( $class )) {
			$class_arr = array (null, 'class' => $class );
		} elseif (is_object ( $class )) {
			$class_arr = array (null, 'clone' => $class );
		} elseif (is_array ( $class )) {
			if ($class === array ()) {
				return;
			}
			$class_arr = $class;
		} else {
			return;
		}
		foreach ( $class_arr as $classkey => $classname ) {
		}
		unset ( $class_arr [$classkey] );
		if (is_int ( $classkey )) {
			if ($classname === null || $classname === '') {
				$classkey = 'class';
			} elseif (is_int ( $classname )) {
				$classkey = 'column';
			} elseif (is_string ( $classname )) {
				$classkey = 'column';
			} elseif (is_object ( $classname )) {
				$classkey = 'clone';
			} elseif (is_array ( $classname )) {
				$classkey = 'array';
			} else {
				return;
			}
		}
		if (function_exists ( 'get_called_class' )) {
			$calledclass = get_called_class ();
		} else {
			$calledclass = __CLASS__;
		}
		// 整理
		$return = array ();
		foreach ( $data_arr as $data ) {
			$data_str = array ();
			$data_int = array ();
			$data_all = array ();
			if (is_object ( $data ) || is_array ( $data )) {
				$int2 = 0;
				foreach ( $data as $key2 => $data2 ) {
					if (is_int ( $key2 )) {
						$data_int [$key2] = $data2;
						continue;
					}
					$data_str [$key2] = $data2;
					$data_int [$int2] = $data2;
					$data_all [$key2] = $data2;
					$data_all [$int2] = $data2;
					$int2 ++;
				}
			}
			$point1 = &$return;
			foreach ( $class_arr as $class ) {
				$point2 = array ();
				if ($class === null || $class === '') {
					$point1 [] = &$point2;
				} else {
					if (is_int ( $class ) && isset ( $data_int [$class] )) {
						$point3 = $data_int [$class];
					} elseif (is_string ( $class ) && isset ( $data_str [$class] )) {
						$point3 = $data_str [$class];
					} else {
						$point3 = '';
					}
					if (isset ( $point1 [$point3] )) {
						$point2 = &$point1 [$point3];
					} else {
						$point1 [$point3] = &$point2;
					}
				}
				unset ( $point1 );
				$point1 = &$point2;
				unset ( $point2 );
			}
			switch ($classkey) {
				case 'assoc' :
					$point1 = $data_str;
					break;
				case 'num' :
					$point1 = $data_int;
					break;
				case 'both' :
					$point1 = $data_all;
					break;
				case 'array' :
					if (is_array ( $classname )) {
						$point1 = $classname;
						foreach ( $data_str as $key => $value ) {
							if (array_key_exists ( $key, $point1 )) {
								$point1 [$key] = $value;
							}
						}
						foreach ( $data_int as $key => $value ) {
							if (array_key_exists ( $key, $point1 )) {
								$point1 [$key] = $value;
							}
						}
					} else {
						$point1 = array ();
					}
					break;
				case 'column' :
					if (is_int ( $classname ) && isset ( $data_int [$classname] )) {
						$point1 = $data_int [$classname];
					} elseif (is_string ( $classname ) && isset ( $data_str [$classname] )) {
						$point1 = $data_str [$classname];
					} else {
						$point1 = null;
					}
					break;
				default :
				case 'class' :
					if (class_exists ( $classname )) {
						$point1 = new $classname ( );
					} else {
						$point1 = new $calledclass ( );
					}
					foreach ( $data_str as $key => $value ) {
						$point1->$key = $value;
					}
					break;
				case 'class|classtype' :
					$i = 0;
					foreach ( $data_str as $key => $value ) {
						if ($i ++ === 0) {
							if (isset ( $data_int [0] ) && preg_match ( '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $data_int [0] ) && class_exists ( $data_int [0] )) {
								$point1 = new $data_int [0] ( );
							} elseif (class_exists ( $classname )) {
								$point1 = new $classname ( );
							} else {
								$point1 = new $calledclass ( );
							}
							continue;
						}
						$point1->$key = $value;
					}
					if ($i === 0) {
						if (isset ( $data_int [0] ) && preg_match ( '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $data_int [0] ) && class_exists ( $data_int [0] )) {
							$point1 = new $data_int [0] ( );
						} elseif (class_exists ( $classname )) {
							$point1 = new $classname ( );
						} else {
							$point1 = new $calledclass ( );
						}
					}
					break;
				case 'clone' :
					if (is_object ( $classname )) {
						$point1 = clone $classname;
					} else {
						$point1 = new $calledclass ( );
					}
					foreach ( $data_str as $key => $value ) {
						$point1->$key = $value;
					}
					break;
			}
		}
		return $return;

	}

	/**
	 * 静态选择函数（可继承）
	 *
	 * @link http://www.coremvc.cn/api/core/connect.php
	 * @param mix $field_sql
	 * @param mix $table_param
	 * @param mix $where_bool
	 * @param mix $other
	 * @param mix $struct
	 * @return array
	 */
	public static function selects($field_sql = null, $table_param = null, $where_bool = null, $other = null, $struct = null) {

		// 【基础功能】静态选择数据
		$dbh = self::connect ( true, $args );
		// 类名
		if ($struct === null || $struct === '') {
			$class_arr = array (null, 'class' => null );
		} elseif (is_string ( $struct )) {
			$class_arr = array (null, 'class' => $struct );
		} elseif (is_object ( $struct )) {
			$class_arr = array (null, 'clone' => $struct );
		} elseif (is_array ( $struct )) {
			if ($struct === array ()) {
				return;
			}
			$class_arr = $struct;
		} else {
			return;
		}
		foreach ( $class_arr as $classkey => $classname ) {
		}
		unset ( $class_arr [$classkey] );
		if (is_int ( $classkey )) {
			if ($classname === null || $classname === '') {
				$classkey = 'class';
			} elseif (is_int ( $classname )) {
				$classkey = 'column';
			} elseif (is_string ( $classname )) {
				$classkey = 'column';
			} elseif (is_object ( $classname )) {
				$classkey = 'clone';
			} elseif (is_array ( $classname )) {
				$classkey = 'array';
			} else {
				return;
			}
		}
		if (function_exists ( 'get_called_class' )) {
			$calledclass = get_called_class ();
		} else {
			$calledclass = __CLASS__;
		}
		$classkey_arr = null;
		if (strpos($classkey,'|') !== false){
			$classkey_arr = explode('|',$classkey);
			$classkey = array_shift($classkey_arr);
			foreach ($classkey_arr as $classkey_key){
				if (strncmp($classkey_key,'table=',6) === 0) {
					$table_class = substr($classkey_key,6);
					if ($table_class === '') {
						$table_class = $calledclass;
					}
				}
			}
		}
		// 参数
		if (is_bool ( $where_bool )) {
			$sql = $field_sql;
			$param = is_array ( $table_param ) ? $table_param : array ();
		} else {
			if ($table_param === null) {
				if (isset($table_class)) {
				} elseif (is_string ( $classname )) {
					if (class_exists ( $classname )) {
						$table_class = $classname;
					} else {
						$table_class = $calledclass;
					}
				} elseif (is_object ( $classname )) {
					$table_class = get_class ( $classname );
				} else {
					$table_class = $calledclass;
				}
				if ($args ['prefix_search'] === '') {
					$table = $table_class;
				} else {
					$table = $args ['prefix_search'] . $table_class;
				}
			} else {
				$table = $table_param;
			}
			$field = $field_sql;
			$where = $where_bool;
			list ( $sql, $param ) = self::prepare ( 'selects', array ($field, $table, $where, $other ) );
		}
		// 分页
		if (is_array( $other ) && isset ( $other ['page'] )) {
			$page = &$other ['page'];
			if (is_array ( $page )) {
				if (isset ( $page ['page'] )) {
					settype ( $page ['page'], 'int' );
					if ($page ['page'] < 1) {
						$page ['page'] = 1;
					}
				} else {
					$page ['page'] = 1;
				}
				if (isset ( $page ['size'] )) {
					settype ( $page ['size'], 'int' );
					if ($page ['size'] < 1) {
						$page ['size'] = 1;
					}
				} else {
					$page ['size'] = 1;
				}
				if (isset ( $page ['count'] )) {
					settype ( $page ['count'], 'int' );
					if ($page ['count'] < 0) {
						$page ['count'] = null;
					}
				} else {
					$page ['count'] = null;
				}
				if (isset ( $page ['total'] )) {
					settype ( $page ['total'], 'int' );
					if ($page ['total'] < 0) {
						$page ['total'] = null;
					}
				} else {
					$page ['total'] = null;
				}
			} else {
				$page = array ('page' => 1, 'size' => 1, 'count' => null, 'total' => null );
			}
		} else {
			$page = null;
		}
		// 执行
		switch ($args ['connect_provider']) {
			case '' :
			case 'mysql' :
				if ($page !== null) {
					if ($page ['count'] === null) {
						$sql = preg_replace ( '/SELECT/i', 'SELECT SQL_CALC_FOUND_ROWS', $sql, 1 );
					}
					$limit = 'LIMIT ' . ($page ['size'] * ($page ['page'] - 1)) . ',' . $page ['size'];
					if (isset ( $page ['limit'] )) {
						$sql = preg_replace ( '/(.*)' . $page ['limit'] . '/i', '$1' . $limit, $sql, 1 );
					} else {
						$sql .= ' ' . $limit;
					}
				}
				$data_key = array ();
				foreach ( $class_arr as $value ) {
					if ($value !== null && $value !== '' && ! in_array ( $value, $data_key, true )) {
						$data_key [] = $value;
					}
				}
				$result = self::execute ( $sql, $param );
				if ($result === false) {
					return false;
				}
				if ($page !== null) {
					if ($page ['count'] === null) {
						list ( $page ['count'] ) = mysql_fetch_row ( mysql_unbuffered_query ( 'SELECT FOUND_ROWS()', $dbh ) );
					}
					$page ['total'] = ( int ) ceil ( $page ['count'] / $page ['size'] );
				}
				// 数据
				if (!is_resource($result) || mysql_num_rows ( $result ) === 0) {
					$data_arr = array ();
					break;
				}
				$data_all = array ();
				if ($data_key !== array ()) {
					while ( $obj = mysql_fetch_array ( $result ) ) {
						$obj_arr = array ();
						foreach ( $data_key as $value ) {
							if (array_key_exists ( $value, $obj )) {
								$obj_arr [$value] = $obj [$value];
							}
						}
						$data_all [] = $obj_arr;
					}
					mysql_data_seek ( $result, 0 );
				}
				$data_arr = array ();
				switch ($classkey) {
					case 'assoc' :
						while ( $obj = mysql_fetch_assoc ( $result ) ) {
							$data_arr [] = $obj;
						}
						break;
					case 'num' :
						while ( $obj = mysql_fetch_row ( $result ) ) {
							$data_arr [] = $obj;
						}
						break;
					case 'both' :
					case 'array' :
						while ( $obj = mysql_fetch_array ( $result ) ) {
							$data_arr [] = $obj;
						}
						break;
					case 'column' :
						while ( $obj = mysql_fetch_array ( $result ) ) {
							if (isset ( $obj [$classname] )) {
								$data_arr [] = $obj [$classname];
							} else {
								$data_arr [] = null;
							}
						}
						break;
					default :
					case 'class' :
						if ( isset($classkey_arr) && in_array('classtype',$classkey_arr) ) {
							while ( $obj = mysql_fetch_assoc ( $result ) ) {
								$obj_classname = $classname;
								foreach ( $obj as $key => $obj_classname ) {
									unset ( $obj [$key] );
									break;
								}
								if (preg_match ( '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $obj_classname ) && class_exists ( $obj_classname )) {
									$clone = new $obj_classname ( );
								} elseif (class_exists ( $classname )) {
									$clone = new $classname ( );
								} else {
									$clone = new $calledclass ( );
								}
								foreach ( $obj as $key => $value ) {
									$clone->$key = $value;
								}
								$data_arr [] = $clone;
							}
						} else {
							if (class_exists ( $classname )) {
								$obj_classname = $classname;
							} else {
								$obj_classname = $calledclass;
							}
							while ( $obj = mysql_fetch_object ( $result, $obj_classname ) ) {
								$data_arr [] = $obj;
							}
						}
						break;
					case 'clone' :
						if (is_object ( $classname )) {
							$obj_classname = $classname;
						} else {
							$obj_classname = new $calledclass ( );
						}
						while ( $obj = mysql_fetch_assoc ( $result ) ) {
							$clone = clone $obj_classname;
							foreach ( $obj as $key => $value ) {
								$clone->$key = $value;
							}
							$data_arr [] = $clone;
						}
						break;
				}
				break;

			// 【扩展功能】静态选择数据
			default :
				$provider = $args ['connect_provider'];
				if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
					$sql = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $sql );
				}
				$ref = array ('page' => &$page, 'class_arr' => $class_arr, 'classkey' => $classkey, 'classkey_arr' => $classkey_arr, 'classname' => $classname, 'calledclass' => $calledclass );
				list ( $data_arr, $data_all ) = call_user_func ( array ($provider, 'selects' ), $dbh, $args, __CLASS__, $sql, $param, $ref );

		}
		// 整理
		if ($class_arr === array (null ) || $class_arr === array ('' )) {
			return $data_arr;
		}
		$return = array ();
		foreach ( $data_arr as $key => $data ) {
			$point1 = &$return;
			foreach ( $class_arr as $struct ) {
				$point2 = array ();
				if ($struct === null || $struct === '') {
					$point1 [] = &$point2;
				} else {
					if (isset ( $data_all [$key] [$struct] )) {
						$point3 = $data_all [$key] [$struct];
					} else {
						$point3 = '';
					}
					if (isset ( $point1 [$point3] )) {
						$point2 = &$point1 [$point3];
					} else {
						$point1 [$point3] = &$point2;
					}
				}
				unset ( $point1 );
				$point1 = &$point2;
				unset ( $point2 );
			}
			$point1 = $data;
		}
		return $return;

	}

	/**
	 * 静态插入函数（可继承）
	 *
	 * + 作用：1.静态插入函数，返回影响记录数；2.扩展方式。
	 * + 示例：
	 * <code>
	 * // 插入一条记录。 
	 *$int = core::inserts('pre_test',array('id'=>1,'name'=>'a')); //返回成功插入的个数
	 *
	 * // 直接使用SQL语句插入一条记录。 
	 *$int = core::inserts('INSERT INTO pre_test (id,name) VALUES (?,?)',array(1,'a'),true); //返回成功插入的个数
	 *
	 * // 从其他表选择插入多条记录。 
	 *$int = core::inserts('pre_test',array('id','name'),null,'SELECT id,name FROM pre_test2'); //返回成功插入的个数
	 *
	 * // 插入多条记录。 
	 *$arr = array(
	 *	array('id'=>1,'name'=>'a'),
	 *	array('id'=>2,'name'=>'b'),
	 *);
	 *
	 *$int = core::inserts('pre_test','id,name',$arr); //返回成功插入的个数
	 *
	 * // 插入需计算的记录。 
	 *$arr = array(
	 *	array(1,"md5('a')"),
	 *	array(2,"md5('b')"),
	 *);
	 *$int = core::inserts('pre_test',array('id','name'),$arr); //返回成功插入的个数
	 *
	 * // 使用类名插入记录。 
	 *$int = core::inserts(null,array('id'=>1,'name'=>'a'),null,null,'test'); //使用前缀+类名作为表名，返回成功插入的个数
	 *
	 * // 使用类名插入记录。 
	 *class test extends core { }
	 *$int = test::inserts(null,array('id'=>1,'name'=>'a')); //使用前缀+类名作为表名（PHP 5.3以上有效），返回成功插入的个数
	 * </code>
	 * @param mix $table_sql
	 * @param mix $column_set_param
	 * @param mix $value_bool
	 * @param mix $other
	 * @param string $class
	 * @return int
	 */
	public static function inserts($table_sql = null, $column_set_param = null, $value_bool = null, $other = null, $class = null) {

		// 【基础功能】静态插入数据
		$dbh = self::connect ( true, $args );
		// 参数
		if (is_bool ( $value_bool )) {
			$sql = $table_sql;
			$param = $column_set_param;
		} else {
			if ($table_sql === null) {
				if ($class === null) {
					if (function_exists ( 'get_called_class' )) {
						$class = get_called_class ();
					} else {
						$class = __CLASS__;
					}
				}
				if ($args ['prefix_search'] === '') {
					$table = $class;
				} else {
					$table = $args ['prefix_search'] . $class;
				}
			} else {
				$table = $table_sql;
			}
			$column_set = $column_set_param;
			$value = $value_bool;
			list ( $sql, $param ) = self::prepare ( 'inserts', array ($table, $column_set, $value, $other ) );
		}
		// 执行
		switch ($args ['connect_provider']) {
			case '' :
			case 'mysql' :
				self::execute ( $sql, $param, $ref );
				return $ref ['affected_rows'];

			// 【扩展功能】静态插入数据
			default :
				$provider = $args ['connect_provider'];
				if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
					$sql = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $sql );
				}
				return call_user_func ( array ($provider, 'inserts' ), $dbh, $args, __CLASS__, $sql, $param );

		}

	}

	/**
	 * 静态修改函数（可继承）
	 *
	 * + 作用：1.静态修改函数，返回影响记录数；2.扩展方式。
	 * <code>
	 * // 修改记录。 
	 *$int = core::updates('pre_test',array('name'=>'a'),array('id'=>1)); //返回成功修改的个数
	 *
	 * // 直接使用SQL语句修改记录。 
	 *$int = core::updates('UPDATE pre_test SET name=? WHERE id=?)',array('a',1),true); //返回成功修改的个数
	 *
	 * // 修改排序的一条记录。 
	 *$int = core::updates('pre_test',array('name'=>'c'),array('name'=>'a'),array('ORDER BY id','LIMIT 1')); //返回成功修改的个数
	 *
	 * // 使用类名修改记录。 
	 *$int = core::updates(null,array('name'=>'a'),array('id'=>1),null,'test'); //使用前缀+类名作为表名，返回成功修改的个数
	 *
	 * // 使用类名修改记录。 
	 *class test extends core { }
	 *$int = test::updates(null,array("name='a'"),array('id'=>1)); //使用前缀+类名作为表名（PHP 5.3以上有效），返回成功修改的个数
	 * </code>
	 * @param mix $table_sql
	 * @param mix $set_param
	 * @param mix $where_bool
	 * @param mix $other
	 * @param string $class
	 * @return int
	 */
	public static function updates($table_sql = null, $set_param = null, $where_bool = null, $other = null, $class = null) {

		// 【基础功能】静态修改数据
		$dbh = self::connect ( true, $args );
		// 参数
		if (is_bool ( $where_bool )) {
			$sql = $table_sql;
			$param = $set_param;
		} else {
			if ($table_sql === null) {
				if ($class === null) {
					if (function_exists ( 'get_called_class' )) {
						$class = get_called_class ();
					} else {
						$class = __CLASS__;
					}
				}
				if ($args ['prefix_search'] === '') {
					$table = $class;
				} else {
					$table = $args ['prefix_search'] . $class;
				}
			} else {
				$table = $table_sql;
			}
			$set = $set_param;
			$where = $where_bool;
			list ( $sql, $param ) = self::prepare ( 'updates', array ($table, $set, $where, $other ) );
		}
		// 执行
		switch ($args ['connect_provider']) {
			case '' :
			case 'mysql' :
				self::execute ( $sql, $param, $ref );
				return $ref ['affected_rows'];

			// 【扩展功能】静态修改数据
			default :
				$provider = $args ['connect_provider'];
				if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
					$sql = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $sql );
				}
				return call_user_func ( array ($provider, 'updates' ), $dbh, $args, __CLASS__, $sql, $param );

		}

	}

	/**
	 * 静态删除函数（可继承）
	 *
	 * + 作用：1.静态删除函数，返回影响记录数；2.扩展方式。
	 * <code>
	 * // 删除记录。 
	 *$int = core::deletes(null,'pre_test',array('id'=>1)); //返回成功删除的个数
	 *
	 * // 直接使用SQL语句删除记录。 
	 *$int = core::deletes('DELETE FROM pre_test where id=?)',array(1),true); //返回成功删除的个数
	 *
	 * // 删除排序的一条记录。 
	 *$int = core::deletes(null,'pre_test',array('name'=>'a'),array('ORDER BY id','LIMIT 1')); //返回成功删除的个数
	 *
	 * // 使用类名删除记录。 
	 *$int = core::deletes(null,null,array('id'=>1),null,'test'); //使用前缀+类名作为表名，返回成功删除的个数
	 *
	 * // 使用类名删除记录。 
	 *class test extends core { }
	 *$int = test::deletes(null,null,array('id'=>1)); //使用前缀+类名作为表名（PHP 5.3以上有效），返回成功删除的个数
	 * </code>
	 * @param mix $field_sql
	 * @param mix $table_param
	 * @param mix $where_bool
	 * @param mix $other
	 * @param string $class
	 * @return int
	 */
	public static function deletes($field_sql = null, $table_param = null, $where_bool = null, $other = null, $class = null) {

		// 【基础功能】静态删除数据
		$dbh = self::connect ( true, $args );
		// 参数
		if (is_bool ( $where_bool )) {
			$sql = $field_sql;
			$param = $table_param;
		} else {
			if ($table_param === null) {
				if ($class === null) {
					if (function_exists ( 'get_called_class' )) {
						$class = get_called_class ();
					} else {
						$class = __CLASS__;
					}
				}
				if ($args ['prefix_search'] === '') {
					$table = $class;
				} else {
					$table = $args ['prefix_search'] . $class;
				}
			} else {
				$table = $table_param;
			}
			$field = $field_sql;
			$where = $where_bool;
			list ( $sql, $param ) = self::prepare ( 'deletes', array ($field, $table, $where, $other ) );
		}
		// 执行
		switch ($args ['connect_provider']) {
			case '' :
			case 'mysql' :
				self::execute ( $sql, $param, $ref );
				return $ref ['affected_rows'];

			// 【扩展功能】静态删除数据
			default :
				$provider = $args ['connect_provider'];
				if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
					$sql = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $sql );
				}
				return call_user_func ( array ($provider, 'deletes' ), $dbh, $args, __CLASS__, $sql, $param );

		}

	}

	/**
	 * 静态更新函数（可继承）
	 *
	 * + 作用：1. 静态更新函数，返回影响记录数；2.扩展方式。
	 * <code>
	 * // 更新一条记录。 
	 *$int = core::replaces('pre_test',array('id'=>1,'name'=>'a')); //返回成功更新的个数(修改的按两倍算)
	 *
	 * // 直接使用SQL语句更新一条记录。 
	 *$int = core::replaces('REPLACE INTO pre_test (id,name) VALUES (?,?)',array(1,'a'),true); //返回成功更新的个数(修改的按两倍算)
	 *
	 * // 从其他表选择插入多条记录。 
	 *$int = core::replaces('pre_test',array('id','name'),null,'SELECT id,name FROM pre_test2'); //返回成功更新的个数(修改的按两倍算)
	 *
	 * // 更新多条记录。 
	 *$arr = array(
	 *	array('id'=>1,'name'=>'a'),
	 *	array('id'=>2,'name'=>'b'),
	 *);
	 *
	 *$int = core::replaces('pre_test','id,name',$arr); //返回成功更新的个数(修改的按两倍算)
	 *
	 * // 更新需计算的记录。 
	 *$arr = array(
	 *	array(1,"md5('a')"),
	 *	array(2,"md5('b')"),
	 *);
	 *$int = core::replaces('pre_test',array('id','name'),$arr); //返回成功更新的个数(修改的按两倍算)
	 *
	 * // 使用类名更新记录。 
	 *$int = core::replaces(null,array('id'=>1,'name'=>'a'),null,null,'test'); //使用前缀+类名作为表名，返回成功更新的个数(修改的按两倍算)
	 *
	 * // 使用类名更新记录。 
	 *class test extends core { }
	 *$int = test::replaces(null,array('id'=>1,'name'=>'a')); //使用前缀+类名作为表名（PHP 5.3以上有效），返回成功更新的个数(修改的按两倍算)
	 * </code>
	 * @param mix $table_sql
	 * @param mix $column_set_param
	 * @param mix $value_bool
	 * @param mix $other
	 * @param string $class
	 * @return int
	 */
	public static function replaces($table_sql = null, $column_set_param = null, $value_bool = null, $other = null, $class = null) {

		// 【基础功能】静态更新数据
		$dbh = self::connect ( true, $args );
		// 参数
		if (is_bool ( $value_bool )) {
			$sql = $table_sql;
			$param = $column_set_param;
		} else {
			if ($table_sql === null) {
				if ($class === null) {
					if (function_exists ( 'get_called_class' )) {
						$class = get_called_class ();
					} else {
						$class = __CLASS__;
					}
				}
				if ($args ['prefix_search'] === '') {
					$table = $class;
				} else {
					$table = $args ['prefix_search'] . $class;
				}
			} else {
				$table = $table_sql;
			}
			$column_set = $column_set_param;
			$value = $value_bool;
			list ( $sql, $param ) = self::prepare ( 'replaces', array ($table, $column_set, $value, $other ) );
		}
		// 执行
		switch ($args ['connect_provider']) {
			case '' :
			case 'mysql' :
				self::execute ( $sql, $param, $ref );
				return $ref ['affected_rows'];

			// 【扩展功能】静态更新数据
			default :
				$provider = $args ['connect_provider'];
				if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
					$sql = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $sql );
				}
				return call_user_func ( array ($provider, 'replaces' ), $dbh, $args, __CLASS__, $sql, $param );

		}

	}

	/**
	 * 实例构造函数（可继承）
	 *
	 * + 作用：1.返回实例数组；2.返回实例数据；3.载入实例数组
	 * + 示例：
	 * <code>
	 * // 返回实例数组 
	 *$row = $obj->struct();
	 *
	 * // 返回实例数据
	 *$id = $obj->struct(0);
	 *$id = $obj->struct('id');
	 *
	 * // 实例载入数组。 
	 *$obj->struct($row); //$row为数组或对象
	 * </code>
	 * @param mix $row
	 * @return mix
	 */
	public function struct($row = null) {

		// 【基础功能】返回实例数组
		if ($row === null || $row === '') {
			return get_object_vars ( $this );
		}

		// 【基础功能】返回实例数据
		if (is_int ( $row )) {
			$i = 0;
			foreach ( $this as $value ) {
				if ($row === $i) {
					return $value;
				}
				$i ++;
			}
			return;
		} elseif (is_string ( $row )) {
			foreach ( $this as $key => $value ) {
				if ($row === $key) {
					return $value;
				}
			}
			return;
		}

		// 【基础功能】载入实例数组
		if (is_object ( $row )) {
			foreach ( $row as $key => $value ) {
				$this->$key = $value;
			}
			return get_object_vars ( $this );
		} elseif (is_array ( $row )) {
			foreach ( $row as $key => $value ) {
				if (is_int ( $key )) {
					$i = 0;
					foreach ( $this as $key2 => $value2 ) {
						if ($key === $i) {
							$this->$key2 = $value;
							break;
						}
						$i ++;
					}
				} elseif ($key !== '') {
					$this->$key = $value;
				}
			}
			return get_object_vars ( $this );
		}
		return;

	}

	/**
	 * 实例选择函数（可继承）
	 *
	 * + 作用：1.选择实例数据，返回是否成功；2.扩展方式。
	 * + 示例：
	 * <code>
	 * // 选择account表id=2的一条记录，第一个属性为主键
	 *$obj = new core;
	 *$obj->id = 2;
	 *$obj->select('account');
	 *
	 * // 选择account表的一条记录，不使用主键
	 *$obj = new core;
	 *$obj->select('account', -1);
	 *
	 * // 选择account表id=2的一条记录，第二个属性为主键
	 *$obj = new core;
	 *$obj->rid = 1;
	 *$obj->id = 2;
	 *$obj->select('account', 1);
	 *
	 * // 选择account表id=2的一条记录，使用继承类
	 *$account = new account;
	 *$account->id = 2;
	 *$account->select();
	 * </code>
	 * @param string $tablename
	 * @param mix $primary_index
	 * @return bool
	 */
	public function select($tablename = '', $primary_index = 0) {

		// 【基础功能】选择实例数据
		$dbh = self::connect ( true, $args );
		// 表名
		if ($tablename === '') {
			$tablename = get_class ( $this );
			if ($tablename === __CLASS__) {
				return false;
			}
			if ($args ['prefix_search'] !== '') {
				$tablename = $args ['prefix_search'] . $tablename;
			}
		}
		if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
			$tablename = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $tablename );
		}
		// 字段
		$fieldvars = get_object_vars ( $this );
		$primary_name = null;
		if (is_int ( $primary_index ) && $primary_index >= 0 && $primary_index < count ( $fieldvars )) {
			$field_index = 0;
			foreach ( $fieldvars as $primary_name => $primary_value ) {
				if ($primary_index === $field_index ++) {
					break;
				}
			}
		} elseif (is_string ( $primary_index ) && array_key_exists ( $primary_index, $fieldvars )) {
			$primary_name = $primary_index;
			$primary_value = $fieldvars [$primary_name];
		}
		// 执行
		switch ($args ['connect_provider']) {
			case '' :
			case 'mysql' :
				if ($primary_name !== null) {
					$sql = 'SELECT * FROM ' . $tablename . ' WHERE ' . $primary_name . '=? LIMIT 1';
					$paramvars = array($primary_value);
				} else {
					$sql = 'SELECT * FROM ' . $tablename . ' LIMIT 1';
					$paramvars = null;
				}
				$result = self::execute ( $sql, $paramvars );
				if ($result === false) {
					return false;
				}
				if (mysql_num_rows ( $result ) == 0) {
					mysql_free_result ( $result );
					return false;
				}
				$row = mysql_fetch_assoc ( $result );
				mysql_free_result ( $result );
				foreach ( $row as $key => $value ) {
					$this->$key = $value;
				}
				return true;

			// 【扩展功能】选择实例数据
			default :
				$provider = $args ['connect_provider'];
				$params = compact ( 'primary_name', 'primary_value', 'fieldname', 'valuename', 'paramvars' );
				return call_user_func ( array ($provider, 'select' ), $dbh, $args, $this, $tablename, $primary_index, $params );

		}

	}

	/**
	 * 实例插入函数（可继承）
	 *
	 * + 作用：1.插入实例数据，返回是否成功；2.扩展方式。
	 * + 示例：
	 * <code>
	 * // 插入account表一条记录，第一个属性为自增主键
	 *$obj = new core;
	 *$obj->id = null;
	 *$obj->name = 'a';
	 *$obj->insert('account');
	 *echo $obj->id;
	 *
	 * // 插入account表一条记录，不使用自增主键
	 *$obj = new core;
	 *$obj->id = 1;
	 *$obj->name = 'a';
	 *$obj->insert('account', -1);
	 *
	 * // 插入account表一条记录，第二个属性为自增主键
	 *$obj = new core;
	 *$obj->name = 'a';
	 *$obj->id = null;
	 *$obj->insert('account', 1);
	 *echo $obj->id;
	 *
	 * // 插入account表一条记录，使用继承类，第一个属性为自增主键
	 *$account = new account;
	 *$account->id = null;
	 *$account->name = 'a';
	 *$account->insert();
	 *echo $account->id;
	 *
	 * // 插入account表一条记录，使用继承类，无自增主键
	 *$account = new account;
	 *$account->id = 1;
	 *$account->name = 'a';
	 *$account->insert('', -1);
	 * </code>
	 * @param string $tablename
	 * @param mix $primary_index
	 * @return bool
	 */
	public function insert($tablename = '', $primary_index = 0) {

		// 【基础功能】插入实例数据
		$dbh = self::connect ( true, $args );
		// 表名
		if ($tablename === '') {
			$tablename = get_class ( $this );
			if ($tablename === __CLASS__) {
				return false;
			}
			if ($args ['prefix_search'] !== '') {
				$tablename = $args ['prefix_search'] . $tablename;
			}
		}
		if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
			$tablename = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $tablename );
		}
		// 字段
		$fieldvars = get_object_vars ( $this );
		$primary_name = null;
		if (is_int ( $primary_index ) && $primary_index >= 0 && $primary_index < count ( $fieldvars )) {
			$field_index = 0;
			foreach ( $fieldvars as $primary_name => $primary_value ) {
				if ($primary_index === $field_index ++) {
					unset ( $fieldvars [$primary_name] );
					break;
				}
			}
		} elseif (is_string ( $primary_index ) && array_key_exists ( $primary_index, $fieldvars )) {
			$primary_name = $primary_index;
			$primary_value = $fieldvars [$primary_name];
			unset ( $fieldvars [$primary_name] );
		}
		// 分析
		$fieldname = implode ( ',', array_keys ( $fieldvars ) );
		$valuename = implode ( ',', array_fill ( 0, count ( $fieldvars ), '?' ) );
		$paramvars = array_values ( $fieldvars );
		// 执行
		switch ($args ['connect_provider']) {
			case '' :
			case 'mysql' :
				$sql = 'INSERT INTO ' . $tablename . ' (' . $fieldname . ') VALUES (' . $valuename . ')';
				if ($primary_name !== null) {
					$result = ( bool ) self::execute ( $sql, $paramvars, $ref );
					if ($result) {
						$this->$primary_name = $ref ['insert_id'];
					}
				} else {
					$result = ( bool ) self::execute ( $sql, $paramvars );
				}
				break;

			// 【扩展功能】插入实例数据
			default :
				$provider = $args ['connect_provider'];
				$params = compact ( 'primary_name', 'primary_value', 'fieldname', 'valuename', 'paramvars' );
				$result = call_user_func ( array ($provider, 'insert' ), $dbh, $args, $this, $tablename, $primary_index, $params );
				break;

		}
		return $result;

	}

	/**
	 * 实例修改函数（可继承）
	 *
	 * + 作用：1.修改实例数据，返回是否成功；2.扩展方式。
	 * + 示例：
	 * <code>
	 * // 修改account表id=1的一条记录
	 *$obj = new core;
	 *$obj->id = 1;
	 *$obj->name = 'b';
	 *$obj->update('account');
	 *
	 * // 修改account表的一条记录
	 *$obj = new core;
	 *$obj->name = 'b';
	 *$obj->update('account', -1);
	 *
	 * // 修改account表id=2的一条记录
	 *$obj = new core;
	 *$obj->name = 'a';
	 *$obj->id = 2;
	 *$obj->update('account', 1);
	 *
	 * // 修改account表id=1的一条记录，使用继承类
	 *$account = new account;
	 *$account->id = 1;
	 *$account->name = 'b';
	 *$account->update();
	 *
	 * // 修改account表的一条记录，使用继承类
	 *$account = new account;
	 *$account->name = 'b';
	 *$account->update('', -1);
	 * </code>
	 * @param string $tablename
	 * @param mix $primary_index
	 * @return bool
	 */
	public function update($tablename = '', $primary_index = 0) {

		// 【基础功能】修改实例数据
		$dbh = self::connect ( true, $args );
		// 表名
		if ($tablename === '') {
			$tablename = get_class ( $this );
			if ($tablename === __CLASS__) {
				return false;
			}
			if ($args ['prefix_search'] !== '') {
				$tablename = $args ['prefix_search'] . $tablename;
			}
		}
		if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
			$tablename = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $tablename );
		}
		// 字段
		$fieldvars = get_object_vars ( $this );
		$primary_name = null;
		if (is_int ( $primary_index ) && $primary_index >= 0 && $primary_index < count ( $fieldvars )) {
			$field_index = 0;
			foreach ( $fieldvars as $primary_name => $primary_value ) {
				if ($primary_index === $field_index ++) {
					unset ( $fieldvars [$primary_name] );
					break;
				}
			}
		} elseif (is_string ( $primary_index ) && array_key_exists ( $primary_index, $fieldvars )) {
			$primary_name = $primary_index;
			$primary_value = $fieldvars [$primary_name];
			unset ( $fieldvars [$primary_name] );
		}
		// 分析
		$fieldname = array_keys ( $fieldvars );
		foreach ( $fieldname as &$fieldname_value ) {
			$fieldname_value = $fieldname_value . '=?';
		}
		$valuename = implode ( ',', $fieldname );
		$paramvars = array_values ( $fieldvars );
		if ($primary_name !== null) {
			array_push ( $paramvars, $primary_value );
		}
		// 执行
		switch ($args ['connect_provider']) {
			case '' :
			case 'mysql' :
				if ($primary_name !== null) {
					$sql = 'UPDATE ' . $tablename . ' SET ' . $valuename . ' WHERE ' . $primary_name . '=? LIMIT 1';
				} else {
					$sql = 'UPDATE ' . $tablename . ' SET ' . $valuename . ' LIMIT 1';
				}
				$result = ( bool ) self::execute ( $sql, $paramvars, $ref );
				if ($result && $ref ['affected_rows'] == 0) {
					return false;
				}
				break;

			// 【扩展功能】修改实例数据
			default :
				$provider = $args ['connect_provider'];
				$params = compact ( 'primary_name', 'primary_value', 'fieldname', 'valuename', 'paramvars' );
				$result = call_user_func ( array ($provider, 'update' ), $dbh, $args, $this, $tablename, $primary_index, $params );
				break;

		}
		return $result;

	}

	/**
	 * 实例删除函数（可继承）
	 *
	 * + 作用：1.删除实例数据，返回是否成功；2.扩展方式。
	 * + 示例：
	 * <code>
	 * // 删除account表id=1的一条记录
	 *$obj = new core;
	 *$obj->id = 1;
	 *$obj->delete('account');
	 *
	 * // 删除account表的一条记录
	 *$obj = new core;
	 *$obj->delete('account', -1);
	 *
	 * // 删除account表id=2的一条记录
	 *$obj = new core;
	 *$obj->name = 'a';
	 *$obj->id = 2;
	 *$obj->delete('account', 1);
	 *
	 * // 删除account表id=1的一条记录，使用继承类
	 *$account = new account;
	 *$account->id = 1;
	 *$account->delete();
	 *
	 * // 删除account表的一条记录，使用继承类
	 *$account = new account;
	 *$account->delete('', -1);
	 * </code>
	 * @param string $tablename
	 * @param mix $primary_index
	 * @return bool
	 */
	public function delete($tablename = '', $primary_index = 0) {

		// 【基础功能】删除实例数据
		$dbh = self::connect ( true, $args );
		// 表名
		if ($tablename === '') {
			$tablename = get_class ( $this );
			if ($tablename === __CLASS__) {
				return false;
			}
			if ($args ['prefix_search'] !== '') {
				$tablename = $args ['prefix_search'] . $tablename;
			}
		}
		if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
			$tablename = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $tablename );
		}
		// 字段
		$fieldvars = get_object_vars ( $this );
		$primary_name = null;
		if (is_int ( $primary_index ) && $primary_index >= 0 && $primary_index < count ( $fieldvars )) {
			$field_index = 0;
			foreach ( $fieldvars as $primary_name => $primary_value ) {
				if ($primary_index === $field_index ++) {
					break;
				}
			}
		} elseif (is_string ( $primary_index ) && array_key_exists ( $primary_index, $fieldvars )) {
			$primary_name = $primary_index;
			$primary_value = $fieldvars [$primary_name];
		}
		// 执行
		switch ($args ['connect_provider']) {
			case '' :
			case 'mysql' :
				if ($primary_name !== null) {
					$sql = 'DELETE FROM ' . $tablename . ' WHERE ' . $primary_name . '=? LIMIT 1';
					$paramvars = array($primary_value);
				} else {
					$sql = 'DELETE FROM ' . $tablename . ' LIMIT 1';
					$paramvars = null;
				}
				$result = ( bool ) self::execute ( $sql, $paramvars, $ref );
				if ($result && $ref['affected_rows'] == 0) {
					$result = false;
				}
				break;

			// 【扩展功能】删除实例数据
			default :
				$provider = $args ['connect_provider'];
				$params = compact ( 'primary_name', 'primary_value', 'fieldname', 'valuename', 'paramvars' );
				$result = call_user_func ( array ($provider, 'delete' ), $dbh, $args, $this, $tablename, $primary_index, $params );
				break;

		}
		return $result;

	}

	/**
	 * 实例更新函数（可继承）
	 *
	 * + 作用：1.更新实例数据，返回是否成功；2.扩展方式。
	 * + 示例：
	 * <code>
	 * // 更新account表id=1的一条记录
	 *$obj = new core;
	 *$obj->id = 1;
	 *$obj->name = 'b';
	 *$obj->replace('account');
	 *
	 * // 更新account表的一条记录
	 *$obj = new core;
	 *$obj->name = 'b';
	 *$obj->replace('account', -1);
	 *
	 * // 更新account表id=2的一条记录
	 *$obj = new core;
	 *$obj->name = 'a';
	 *$obj->id = 2;
	 *$obj->replace('account', 1);
	 *
	 * // 更新account表id=1的一条记录，使用继承类
	 *$account = new account;
	 *$account->id = 1;
	 *$account->name = 'b';
	 *$account->replace();
	 *
	 * // 更新account表的一条记录，使用继承类
	 *$account = new account;
	 *$account->name = 'b';
	 *$account->replace('', -1);
	 * </code>
	 * @param string $tablename
	 * @param mix $primary_index
	 * @return bool
	 */
	public function replace($tablename = '', $primary_index = 0) {

		// 【基础功能】更新实例数据
		$dbh = self::connect ( true, $args );
		// 表名
		if ($tablename === '') {
			$tablename = get_class ( $this );
			if ($tablename === __CLASS__) {
				return false;
			}
			if ($args ['prefix_search'] !== '') {
				$tablename = $args ['prefix_search'] . $tablename;
			}
		}
		if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
			$tablename = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $tablename );
		}
		// 字段
		$fieldvars = get_object_vars ( $this );
		$primary_name = null;
		if (is_int ( $primary_index ) && $primary_index >= 0 && $primary_index < count ( $fieldvars )) {
			$field_index = 0;
			foreach ( $fieldvars as $primary_name => $primary_value ) {
				if ($primary_index === $field_index ++) {
					break;
				}
			}
		} elseif (is_string ( $primary_index ) && array_key_exists ( $primary_index, $fieldvars )) {
			$primary_name = $primary_index;
			$primary_value = $fieldvars [$primary_name];
		}
		// 分析
		$fieldname = implode ( ',', array_keys ( $fieldvars ) );
		$valuename = implode ( ',', array_fill ( 0, count ( $fieldvars ), '?' ) );
		$paramvars = array_values ( $fieldvars );
		// 执行
		switch ($args ['connect_provider']) {
			case '' :
			case 'mysql' :
				$sql = 'REPLACE INTO ' . $tablename . ' (' . $fieldname . ') VALUES (' . $valuename . ')';
				if ($primary_name !== null) {
					$result = ( bool ) self::execute ( $sql, $paramvars, $ref );
					if ($result) {
						$this->$primary_name = $ref ['insert_id'];
					}
				} else {
					$result = ( bool ) self::execute ( $sql, $paramvars );
				}
				break;

			// 【扩展功能】更新实例数据
			default :
				$provider = $args ['connect_provider'];
				$params = compact ( 'primary_name', 'primary_value', 'fieldname', 'valuename', 'paramvars' );
				$result = call_user_func ( array ($provider, 'replace' ), $dbh, $args, $this, $tablename, $primary_index, $params );
				break;

		}
		return $result;

	}

}

/**
 * 执行(execute)
 */
core::stub () and core::main ();
?>