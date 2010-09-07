<?php
/**
 * CoreMVC核心模块
 * 
 * @version 1.3.0 alpha 3
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
			$self_flag = true;
			$current_config = &self::$config;
		} else {
			$self_flag = false;
			$current_config = &$variable;
		}

		// 导入配置文件
		if (! is_array ($current_config)){
			if (empty ($current_config) || ! is_string ($current_config)){
				$current_config = array();
			} else {
				if ($self_flag){
					$import_config = self::_init_file ($current_config);
				} else {
					$import_config = self::_init_file ($current_config, isset (self::$config ['config_path']) ? self::$config ['config_path'] : null);
				}
				if ($import_config === null) {
					$current_config = array();
				} else {
					$current_config = $import_config;
				}
			}
		}

		// 导入环境变量
		if ($self_flag){
			$import_config = self::_init_environment (isset ($current_config ['config_path']) ? $current_config ['config_path'] : null);
		} else {
			$import_config = self::_init_environment (isset (self::$config ['config_path']) ? self::$config ['config_path'] : null);
		}
		if ($import_config !== null) {
			$current_config = array_merge ($current_config, $import_config);
		}

		// 配置参数处理
		if (is_bool ($config)) {
			if ($config) {
				// 完全清空配置
				$current_config = array ();
			} else {
				// 直接返回配置
				return $current_config;
			}
		} elseif (is_array ($config)) {
			// 导入参数数组
			$current_config = array_merge ($current_config, $config);
		} elseif (is_string ($config)) {
			$fileext = self::_init_fileext($config);
			if ($fileext) {
				// 导入配置文件
				if ($self_flag){
					$import_config = self::_init_file ($config, isset ($current_config ['config_path']) ? $current_config ['config_path'] : null);
				} else {
					$import_config = self::_init_file ($config, isset (self::$config ['config_path']) ? self::$config ['config_path'] : null);
				}
				if ($import_config !== null) {
					$current_config = array_merge ($current_config, $import_config);
				}
			} else {
				// 直接返回属性
				return isset($current_config [$config]) ? $current_config [$config] : '';
			}
		}

		// 需要初始化的函数
		if ($self_flag) {
			self::_init_extension ( $current_config );
			self::_init_autoload ( $current_config );
		}

		return $current_config;

	}

	/**
	 * 存根函数（可继承）
	 *
	 * @link http://www.coremvc.cn/api/core/stub.php
	 * @param bool $autoload_enable
	 * @param string $autoload_path
	 * @param string $autoload_extensions
	 * @param bool $autoload_prepend
	 * @return bool
	 */
	public static function stub($autoload_enable = null, $autoload_path = null, $autoload_extensions = null, $autoload_prepend = null) {

		// 初始化自动载入功能
		self::_stub_autoload (array (
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

	/**
	 * 入口函数（可继承）
	 *
	 * @link http://www.coremvc.cn/api/core/main.php
	 * @param bool $framework_enable
	 * @param string $framework_require
	 * @param string $framework_mdoule
	 * @param string $framework_action
	 * @param string $framework_parameter
	 * @return bool
	 */
	public static function main($framework_enable = null, $framework_require = null, $framework_module = null, $framework_action = null, $framework_parameter = null) {

		// 一次跳转功能
		$config = self::init (false);
		if (isset ($config['framework_function']) && is_callable ($config['framework_function'])) {
			self::init (array ('framework_function'=>''));
			return call_user_func ($framework_function, $framework_enable, $framework_require, $framework_module, $framework_action, $framework_parameter);
		}

		// 入口参数处理
		isset ($framework_enable) and $config ['framework_enable'] = $framework_enable;
		if (empty($config ['framework_enable']) || !is_string($config ['framework_enable'])) {
			$return_array = array();
		} else {
			$return_array = explode (',', $config ['framework_enable']);
		}

		// 框架控制功能
		if (! empty($config ['framework_enable'])) {
			isset ($framework_require) and $config ['framework_require'] = $framework_require;
			isset ($framework_module) and $config ['framework_module'] = $framework_module;
			isset ($framework_action) and $config ['framework_action'] = $framework_action;
			isset ($framework_parameter) and $config ['framework_parameter'] = $framework_parameter;
			return self::_main_framework ($config, $return_array);
		}

		// 模拟文件隐藏效果
		if (! in_array( 'manual', $return_array ) ) {
			self::_main_hide ($config);
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

		switch ($filetype) {
			case 'extension' :
				$filepath = self::_path_extension ($filename, self::init ('extension_path'));
				break;
			case 'config' :
				$filepath = self::_path_config ($filename, self::init ('config_path'));
				break;
			case 'template' :
				$filepath = self::_path_template ($filename, self::init ('template_path'));
				break;
			default :
				$filepath = self::_path_file ($filename);
				if ($filepath === null) {
					$filepath = $filename;
				}
				break;
		}
		return $filepath;

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
		$_view_init = self::init (false);
		$_view_config = array (
			'template_search' => isset ($_view_init ['template_search']) ? $_view_init ['template_search'] : '', 
			'template_replace' => isset ($_view_init ['template_replace']) ? $_view_init ['template_replace'] : '', 
			'template_type' => isset ($_view_init ['template_type']) ? $_view_init ['template_type'] : '', 
		);
		isset ( $_view_type ) and $_view_config ['template_type'] = $_view_type;

		// 视图数据处理
		if ($_view_config ['template_search'] !== '' && $_view_config ['template_search'] !== $_view_config ['template_replace']) {
			$_view_file2 = self::_path_template (str_replace ($_view_config ['template_search'], $_view_config ['template_replace'], $_view_file), isset ($_view_init['template_path']) ? $_view_init['template_path'] : null);
		} else {
			$_view_file2 = self::_path_template ($_view_file, isset ($_view_init['template_path']) ? $_view_init['template_path'] : null);
		}
		$_view_vars2 = is_array ($_view_vars) ? $_view_vars : array ();
		$_view_type2 = $_view_config ['template_type'] === '' ? 'include' : $_view_config ['template_type'];
		$_view_show2 = $_view_show === null ? true : $_view_show;

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
				$_view_extension = self::_path_extension ($_view_type2 . '.php', isset ($_view_init['extension_path']) ? $_view_init['extension_path'] : null);
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
	 * @param mixed $args
	 * @param array &$ref
	 * @param array $info
	 * @return $dbh
	 */
	public static function connect($args = null, &$ref = null, $info = null) {


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
			$pos = $connect ['current'];
			$ref = $connect ['configs'] [$pos];
			$dbh = &$connect ['connections'] [$pos];
			switch ($ref ['connect_provider']) {

				// 【扩展功能】重连数据库
				default :
					$callback = array ( $ref ['connect_provider'], 'reconnect' );
					if (! is_callable ($callback)) {
						$provider_file = self::_path_extension ( $ref ['connect_provider'] . '.php', self::init('extension_path') );
						if (is_file ($provider_file)) {
							require_once $provider_file;
						}
					}
					if (is_callable ($callback)) {
						$dbh = call_user_func ( $callback, $dbh, $ref, $pos, $info );
						break;
					}

				case '' :
				case 'mysql' :
					if ($dbh === null || ! mysql_ping($dbh)) {
						self::connect (false, $ref, $info);
						$dbh = self::connect (true, $ref, $info);
					}
					break;
			}
			return $dbh;
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
				$config_file = self::_path_config ($args,  self::init('config_path'));
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

				// 【扩展功能】断开数据库
				default :
					$callback = array ($ref ['connect_provider'], 'disconnect');
					if (! is_callable ($callback)) {
						$provider_file = self::_path_extension ( $ref ['connect_provider'] . '.php',  self::init('extension_path') );
						if (is_file ($provider_file)) {
							require_once $provider_file;
						}
					}
					if (is_callable ($callback)) {
						$return = call_user_func ( $callback, $dbh, $ref, $pos, $info );
						break;
					}

				case '' :
				case 'mysql' :
					if (is_resource ( $dbh ) && get_resource_type ( $dbh ) == 'mysql link') {
						$return = mysql_close ( $dbh );
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

				// 【扩展功能】连接数据库
				default :
					$callback = array ($ref ['connect_provider'], 'connect');
					if (! is_callable ($callback)) {
						$provider_file = self::_path_extension ( $ref ['connect_provider'] . '.php',  self::init('extension_path') );
						if (is_file ($provider_file)) {
							require_once $provider_file;
						}
					}
					if (is_callable ($callback)) {
						$dbh = call_user_func ( $callback, $ref, $pos,$info );
						break;
					}

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
			}

			return $dbh;

		}

	}

	/**
	 * 执行SQL语句
	 *
	 * @link http://www.coremvc.cn/api/core/execute.php
	 * @param string $sql
	 * @param array $param
	 * @param array &$ref
	 * @return mixed
	 */
	public static function execute($sql, $param = null, &$ref = null) {

		// 【基础功能】执行语句
		$ref_flag = (func_num_args () > 2);
		$dbh = self::connect ( true, $args, array('execute', $sql, $param, $ref) );
		if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
			$sql = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $sql );
		}
		switch ($args ['connect_provider']) {

			// 【扩展功能】执行语句
			default :
				$callback = array ($provider = $args ['connect_provider'], 'execute');
				if (is_callable ($callback)) {
					if ($ref_flag) {
						$result = call_user_func_array ( $callback, array ($dbh, $args, __CLASS__, $sql, $param, &$ref ) );
					} else {
						$result = call_user_func_array ( $callback, array ($dbh, $args, __CLASS__, $sql, $param ) );
					}
					break;
				}

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
				if ($args ['debug_enable']) {
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
		}
		return $result;

	}

	/**
	 * 准备SQL语句
	 *
	 * @link http://www.coremvc.cn/api/core/prepare.php
	 * @param string $sql
	 * @param array $param
	 * @param bool $format
	 * @param bool $debug
	 * @param string $output
	 * @param array $extra
	 * @return mixed
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
				$dbh = self::connect ( true, $args, array('prepare', $sql, $param, $format, $debug, $output, $extra) );
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
					$callback = array ($args ['connect_provider'], 'prepare');
					if (! is_callable ($callback)) {
						$provider_file = self::_path_extension ( $args ['connect_provider'] . '.php', self::init('extension_path') );
						if (is_file ($provider_file)) {
							require_once $provider_file;
						}
					}
					if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
						$sql = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $sql );
					}
					if (is_callable ($callback)) {
						$return = call_user_func ( $callback, $dbh, $args, __CLASS__, $sql, $param, $format );
					} else {
						$return = self::prepare ( 'mysql_' . $sql, $param, $format);
					}
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
	 * @link http://www.coremvc.cn/api/core/sequence.php
	 * @param string $tablename
	 * @param int $start_index
	 * @return int
	 */
	public static function sequence($tablename = 'sequence', $start_index = 1) {

		// 【基础功能】生成自增序列
		$dbh = self::connect ( true, $args, array('sequence', $tablename, $start_index) );
		// 表名
		if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
			$tablename = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $tablename );
		}
		// 执行
		switch ($args ['connect_provider']) {

			// 【扩展功能】生成自增序列
			default :
				$callback = array ($args ['connect_provider'], 'sequence' );
				if (is_callable ($callback)) {
					$return = call_user_func ( $callback, $dbh, $args, __CLASS__, $tablename, $start_index );
					break;
				}

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
		}
		return $return;
	}

	/**
	 * 静态构造函数（可继承）
	 *
	 * @link http://www.coremvc.cn/api/core/structs.php
	 * @param array $array
	 * @param mixed $struct
	 * @return array
	 */
	public static function structs($array = null, $struct = null) {

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
	 * @link http://www.coremvc.cn/api/core/selects.php
	 * @param mixed $field_sql
	 * @param mixed $table_param
	 * @param mixed $where_bool
	 * @param mixed $other
	 * @param mixed $struct
	 * @return array
	 */
	public static function selects($field_sql = null, $table_param = null, $where_bool = null, $other = null, $struct = null) {

		// 【基础功能】静态选择数据
		$dbh = self::connect ( true, $args, array ('selects', $field_sql, $table_param, $where_bool, $other, $struct) );
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

			// 【扩展功能】静态选择数据
			default :
				$callback = array ($args ['connect_provider'], 'selects' );
				if (is_callable ($callback)) {
					if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
						$sql = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $sql );
					}
					$ref = array ('page' => &$page, 'class_arr' => $class_arr, 'classkey' => $classkey, 'classkey_arr' => $classkey_arr, 'classname' => $classname, 'calledclass' => $calledclass );
					list ( $data_arr, $data_all ) = call_user_func ( $callback, $dbh, $args, __CLASS__, $sql, $param, $ref );
					break;
				}

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
	 * @link http://www.coremvc.cn/api/core/inserts.php
	 * @param mixed $table_sql
	 * @param mixed $column_set_param
	 * @param mixed $value_bool
	 * @param mixed $other
	 * @param string $class
	 * @return int
	 */
	public static function inserts($table_sql = null, $column_set_param = null, $value_bool = null, $other = null, $class = null) {

		// 【基础功能】静态插入数据
		$dbh = self::connect ( true, $args, array ('inserts', $table_sql, $column_set_param, $value_bool, $other, $class) );
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

			// 【扩展功能】静态插入数据
			default :
				$callback = array ($args ['connect_provider'], 'inserts');
				if (is_callable ($callback)) {
					if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
						$sql = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $sql );
					}
					return call_user_func ( $callback, $dbh, $args, __CLASS__, $sql, $param );
				}

			case '' :
			case 'mysql' :
				self::execute ( $sql, $param, $ref );
				return $ref ['affected_rows'];
		}

	}

	/**
	 * 静态修改函数（可继承）
	 *
	 * @link http://www.coremvc.cn/api/core/updates.php
	 * @param mixed $table_sql
	 * @param mixed $set_param
	 * @param mixed $where_bool
	 * @param mixed $other
	 * @param string $class
	 * @return int
	 */
	public static function updates($table_sql = null, $set_param = null, $where_bool = null, $other = null, $class = null) {

		// 【基础功能】静态修改数据
		$dbh = self::connect ( true, $args, array('updates',$table_sql, $set_param, $where_bool, $other, $class) );
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

			// 【扩展功能】静态修改数据
			default :
				$callback = array ($args ['connect_provider'], 'updates');
				if (is_callable ($callback)) {
					if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
						$sql = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $sql );
					}
					return call_user_func ( $callback, $dbh, $args, __CLASS__, $sql, $param );
				}

			case '' :
			case 'mysql' :
				self::execute ( $sql, $param, $ref );
				return $ref ['affected_rows'];
		}

	}

	/**
	 * 静态删除函数（可继承）
	 *
	 * @link http://www.coremvc.cn/api/core/deletes.php
	 * @param mixed $field_sql
	 * @param mixed $table_param
	 * @param mixed $where_bool
	 * @param mixed $other
	 * @param string $class
	 * @return int
	 */
	public static function deletes($field_sql = null, $table_param = null, $where_bool = null, $other = null, $class = null) {

		// 【基础功能】静态删除数据
		$dbh = self::connect ( true, $args, array ('deletes', $field_sql, $table_param, $where_bool, $other, $class) );
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

			// 【扩展功能】静态删除数据
			default :
				$callback = array ($args ['connect_provider'], 'deletes');
				if (is_callable ($callback)) {
					if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
						$sql = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $sql );
					}
					return call_user_func ( $callback, $dbh, $args, __CLASS__, $sql, $param );
				}

			case '' :
			case 'mysql' :
				self::execute ( $sql, $param, $ref );
				return $ref ['affected_rows'];
		}

	}

	/**
	 * 静态更新函数（可继承）
	 *
	 * @link http://www.coremvc.cn/api/core/replaces.php
	 * @param mixed $table_sql
	 * @param mixed $column_set_param
	 * @param mixed $value_bool
	 * @param mixed $other
	 * @param string $class
	 * @return int
	 */
	public static function replaces($table_sql = null, $column_set_param = null, $value_bool = null, $other = null, $class = null) {

		// 【基础功能】静态更新数据
		$dbh = self::connect ( true, $args, array ('replaces', $table_sql, $column_set_param, $value_bool, $other, $class) );
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

			// 【扩展功能】静态更新数据
			default :
				$callback = array ($args ['connect_provider'], 'replaces');
				if (is_callable ($callback)) {
					if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
						$sql = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $sql );
					}
					return call_user_func ( $callback, $dbh, $args, __CLASS__, $sql, $param );
				}

			case '' :
			case 'mysql' :
				self::execute ( $sql, $param, $ref );
				return $ref ['affected_rows'];
		}

	}

	/**
	 * 实例构造函数（可继承）
	 *
	 * @link http://www.coremvc.cn/api/core/struct.php
	 * @param mixed $row
	 * @return mixed
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
	 * @link http://www.coremvc.cn/api/core/select.php
	 * @param string $tablename
	 * @param mixed $primary_index
	 * @return bool
	 */
	public function select($tablename = '', $primary_index = 0) {

		// 【基础功能】选择实例数据
		$dbh = self::connect ( true, $args, array ('select', $tablename, $primary_index) );
		// 表名
		if (empty ($tablename)) {
			$tablename = $args ['prefix_search'] . get_class ( $this );
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

			// 【扩展功能】选择实例数据
			default :
				$callback = array ($args ['connect_provider'], 'select');
				if (is_callable ($callback)) {
					$params = compact ( 'primary_name', 'primary_value', 'fieldname', 'valuename', 'paramvars' );
					$result = call_user_func ( $callback, $dbh, $args, $this, $tablename, $primary_index, $params );
					break;
				}

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
					break;;
				}
				if (mysql_num_rows ( $result ) == 0) {
					mysql_free_result ( $result );
					$result = false;
					break;
				}
				$row = mysql_fetch_assoc ( $result );
				mysql_free_result ( $result );
				foreach ( $row as $key => $value ) {
					$this->$key = $value;
				}
				$result = true;
				break;
		}
		return $result;

	}

	/**
	 * 实例插入函数（可继承）
	 *
	 * @link http://www.coremvc.cn/api/core/insert.php
	 * @param string $tablename
	 * @param mixed $primary_index
	 * @return bool
	 */
	public function insert($tablename = '', $primary_index = 0) {

		// 【基础功能】插入实例数据
		$dbh = self::connect ( true, $args, array ('insert', $tablename, $primary_index) );
		// 表名
		if (empty ($tablename)) {
			$tablename = $args ['prefix_search'] . get_class ( $this );
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

			// 【扩展功能】插入实例数据
			default :
				$callback = array ($args ['connect_provider'], 'insert');
				if (is_callable ($callback)) {
					$params = compact ( 'primary_name', 'primary_value', 'fieldname', 'valuename', 'paramvars' );
					$result = call_user_func ( $callback, $dbh, $args, $this, $tablename, $primary_index, $params );
					break;
				}

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
		}
		return $result;

	}

	/**
	 * 实例修改函数（可继承）
	 *
	 * @link http://www.coremvc.cn/api/core/update.php
	 * @param string $tablename
	 * @param mixed $primary_index
	 * @return bool
	 */
	public function update($tablename = '', $primary_index = 0) {

		// 【基础功能】修改实例数据
		$dbh = self::connect ( true, $args, array ('update', $tablename, $primary_index) );
		// 表名
		if (empty ($tablename)) {
			$tablename = $args ['prefix_search'] . get_class ( $this );
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

			// 【扩展功能】修改实例数据
			default :
				$callback = array ($args ['connect_provider'], 'update');
				if (is_callable ($callback)) {
					$params = compact ( 'primary_name', 'primary_value', 'fieldname', 'valuename', 'paramvars' );
					$result = call_user_func ( $callback, $dbh, $args, $this, $tablename, $primary_index, $params );
					break;
				}

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
		}
		return $result;

	}

	/**
	 * 实例删除函数（可继承）
	 *
	 * @link http://www.coremvc.cn/api/core/delete.php
	 * @param string $tablename
	 * @param mixed $primary_index
	 * @return bool
	 */
	public function delete($tablename = '', $primary_index = 0) {

		// 【基础功能】删除实例数据
		$dbh = self::connect ( true, $args, array ('delete', $tablename, $primary_index) );
		// 表名
		if (empty ($tablename)) {
			$tablename = $args ['prefix_search'] . get_class ( $this );
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

			// 【扩展功能】删除实例数据
			default :
				$callback = array ($args ['connect_provider'], 'delete');
				if (is_callable ($callback)) {
					$params = compact ( 'primary_name', 'primary_value', 'fieldname', 'valuename', 'paramvars' );
					$result = call_user_func ( $callback, $dbh, $args, $this, $tablename, $primary_index, $params );
					break;
				}

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
		}
		return $result;

	}

	/**
	 * 实例更新函数（可继承）
	 *
	 * @link http://www.coremvc.cn/api/core/replace.php
	 * @param string $tablename
	 * @param mixed $primary_index
	 * @return bool
	 */
	public function replace($tablename = '', $primary_index = 0) {

		// 【基础功能】更新实例数据
		$dbh = self::connect ( true, $args, array ('replace', $tablename, $primary_index) );
		// 表名
		if (empty ($tablename)) {
			$tablename = $args ['prefix_search'] . get_class ( $this );
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

			// 【扩展功能】更新实例数据
			default :
				$callback = array ($args ['connect_provider'], 'replace');
				if (is_callable ($callback)) {
					$params = compact ( 'primary_name', 'primary_value', 'fieldname', 'valuename', 'paramvars' );
					$result = call_user_func ( $callback, $dbh, $args, $this, $tablename, $primary_index, $params );
					break;
				}

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
		}
		return $result;

	}

	/**
	 * 返回文件扩展名
	 *
	 * @param string $filename
	 * @return bool
	 */
	private static function _init_fileext($filename) {

		if ($filename) {
			$fileext = strtolower (strrchr ($filename, '.'));
			if ($fileext === '.php' || $fileext === '.ini') {
				return $fileext;
			}
		}
		return null;

	}

	/**
	 * 载入配置文件
	 *
	 * @param string $filename
	 * @param string $config_path
	 * @return array
	 */
	private static function _init_file($filename, $config_path = null) {

		$fileext = self::_init_fileext($filename);
		if ($fileext) {
			$filepath = self::_path_config ($filename, $config_path);
			if (is_file ($filepath)) {
				if ($fileext === '.php') {
					$import_config = require $filepath;
				} elseif ($fileext === '.ini') {
					$import_config = parse_ini_file ($filepath);
				}
			}
		}
		if (empty ($import_config) || ! is_array($import_config)) {
			$import_config = null;
		}

		return $import_config;

	}

	/**
	 * 载入环境变量配置
	 *
	 * @param string $config_path
	 * @return array
	 */
	private static function _init_environment($config_path = null) {

		static $static_config;
		if ($static_config === null) {
			$static_config = array ();
			$env_flag = __CLASS__ . '_config';
			if (isset ($_SERVER [$env_flag])) {
				//导入环境变量
				$env_config = $_SERVER [$env_flag];
				if ($env_config) {
					$env_prefix = $env_flag . '_';
					$env_length = strlen($env_prefix);
					foreach ($_SERVER as $key=>$value) {
						if (strncmp ($key, $env_prefix, $env_length) === 0) {
							$static_config [substr ($key, $env_length)] = $value;
						}
					}
					$import_config = self::_init_file ($env_config, isset ($static_config ['config_path']) ? $static_config ['config_path'] : $config_path);
					if (is_array ($import_config) ){
						$static_config = array_merge ($static_config, $import_config);
					}
				}
			} elseif (is_file ('.htaccess')) {
				//导入配置文件
				$content = file_get_contents ('.htaccess');
				if (preg_match ('/^\s*SetEnv\s+core_config\s+(.*)\s*$/im', $content, $matches)) {
					$env_config = rtrim ($matches[1]);
					if ($env_config) {
						if (preg_match_all ('/^\s*SetEnv\s+core_config_(.+?)\s+(.*)\s*$/im',$content,$matches)) {
							$static_config = array_combine($matches[1],array_map("rtrim",$matches[2]));
						}
						$import_config = self::_init_file ($env_config, isset ($static_config ['config_path']) ? $static_config ['config_path'] : $config_path);
						if (is_array ($import_config) ){
							$static_config = array_merge ($static_config, $import_config);
						}
					}
				}
			}
		}

		return $static_config;

	}

	/**
	 * 类库载入功能
	 *
	 * @param array $array
	 */
	private static function _init_extension($array) {

		// 数组参数处理
		$config = array (
			'extension_enable' => isset ($array ['extension_enable']) ? $array ['extension_enable'] : '', 
			'extension_path' => isset ($array ['extension_path']) ? $array ['extension_path'] : '', 
			'extension_prepend' => isset ($array ['extension_prepend']) ? $array ['extension_prepend'] : '', 
		);

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
				$extension_path = rtrim (self::_path_extension ('', isset ($array['extension_path']) ? $array['extension_path'] : null), '/\\');
				$include_path_array = explode (PATH_SEPARATOR, get_include_path ());
				if ( is_bool ($extension_prepend)) {
					if ( in_array ($extension_path, $include_path_array) ) {
						$include_path_array = array_values(array_diff($include_path_array, array($extension_path)));
					}
					if ($extension_prepend) {
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
						$extension_file = self::_path_extension ( trim($extension) . '.php', isset ($array['extension_path']) ? $array['extension_path'] : null );
						if (is_file ( $extension_file )) {
							require_once $extension_file;
						}
					}
				}
			}
			$static_config = $config;
		}

	}

	/**
	 * 自动载入功能
	 *
	 * @param array $array
	 */
	private static function _init_autoload($array) {

		// 数组参数处理
		$config = array (
			'autoload_enable' => isset ($array ['autoload_enable']) ? $array ['autoload_enable'] : '', 
			'autoload_path' => isset ($array ['autoload_path']) ? $array ['autoload_path'] : '', 
			'autoload_extensions' => isset ($array ['autoload_extensions']) ? $array ['autoload_extensions'] : '', 
			'autoload_prepend' =>isset ($array ['autoload_prepend']) ? $array ['autoload_prepend'] : '', 
		);

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
				if (! in_array($static_last ['autoload_realname'],(array)$static_last ['spl_autoload_functions']) ) {
					spl_autoload_unregister ( $static_last ['autoload_realname'] );
				}
				if (! empty($config ['autoload_enable'])) {
					if (is_callable ($config ['autoload_enable'])) {
						$static_last ['autoload_realname'] = $config ['autoload_enable'];
					} else {
						$static_last ['autoload_realname'] = 'spl_autoload';
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

	}

	/**
	 * 第一次自动载入
	 *
	 * @param array $array
	 */
	private static function _stub_autoload($array) {

		static $static_config;
		if ($static_config === null || $static_config !== $array){
			$static_config = array (
				'autoload_enable' => null,
				'autoload_path' => null,
				'autoload_extensions' => null,
				'autoload_prepend' => null,
			);
			self::init ($array);
		}

	}

	/**
	 * 入口函数（可继承）
	 *
	 * @param array $array
	 */
	private static function _main_hide($array) {

		$hide_info = isset ($array ['hide_info']) ? $array ['hide_info'] : '';
		$hide_info_cli = isset ($array ['hide_info_cli']) ? $array ['hide_info_cli'] : $hide_info;
		$hide_info_web = isset ($array ['hide_info_web']) ? $array ['hide_info_web'] : $hide_info;
		if (PHP_SAPI == 'cli') {
			if (empty ($hide_info_cli)) {
				echo ('Could not open input file: ' . basename ( $_SERVER ['SCRIPT_FILENAME'] ) . PHP_EOL);
			} elseif (is_callable ($hide_info_cli)) {
				call_user_func ($hide_info_cli);
				echo PHP_EOL;
			} else {
				echo $hide_info_cli . PHP_EOL;
			}
		} else {
			if (empty ($hide_info_web)) {
				header ('HTTP/1.0 404 Not Found');
			} elseif (is_callable ($hide_info_web)) {
				call_user_func ($hide_info_web);
			} elseif (filter_var ($hide_info_web, FILTER_VALIDATE_URL)) {
				header ('Location: ' . $hide_info_web);
			} else {
				header ( "Content-type:text/html;charset=UTF-8" );
				echo $hide_info_web;
			}
		}

	}

	/**
	 * 框架控制函数
	 *
	 * @param array $array
	 * @param array $return_array
	 * @return mixed
	 */
	private static function _main_framework($array, $return_array) {

		// 1. 设置默认值、替换内置宏
		if (function_exists ('get_called_class')) {
			$classname_static =  get_called_class ();
		} else {
			$classname_static = '[file:1]';
		}
		if (empty($array ['framework_require'])) {
			$require = '';
		} else {
			$require = $array ['framework_require'];
			if (strpos ($require, '(self)') !== false) {
				$require = str_replace ('(self)', __CLASS__, $require);
			}
			if (strpos ($require, '(static)') !== false) {
				$require = str_replace ('(static)', $classname_static, $require);
			}
		}
		if (empty($array ['framework_module'])) {
			$module = $classname_static . '!' . __CLASS__; //默认值：(static)!(self)
		} else {
			$module = $array ['framework_module'];
			if (strpos ($module, '(self)') !== false) {
				$module = str_replace ('(self)', __CLASS__, $module);
			}
			if (strpos ($module, '(static)') !== false) {
				$module = str_replace ('(static)', $classname_static, $module);
			}
		}
		if (empty($array ['framework_action'])) {
			$action = '[get:1]|index'; //默认值：[get:1]|index
		} else {
			$action = $array ['framework_action'];
			if (strpos ($action, '(self)') !== false) {
				$action = str_replace ('(self)', __CLASS__, $action);
			}
			if (strpos ($action, '(static)') !== false) {
				$action = str_replace ('(static)', $classname_static, $action);
			}
		}
		if (empty($array ['framework_parameter'])) {
			$parameter = '';
		} else {
			$parameter = $array ['framework_parameter'];
			if (strpos ($parameter, '(self)') !== false) {
				$parameter = str_replace ('(self)', __CLASS__, $parameter);
			}
			if (strpos ($parameter, '(static)') !== false) {
				$parameter = str_replace ('(static)', $classname_static, $parameter);
			}
		}

		// 2. 生成替换数组
		$value_array = array();
		$string = $require . ' ' . $module . ' ' . $action . ' ' . $parameter;
		if (stripos ( $string, '[get:' ) !== false) {
			$get_array = array_values($_GET) + $_GET;
			array_unshift ( $get_array, null);
			$value_array ['get'] = $get_array;
		}
		if (stripos ( $string, '[post:' ) !== false) {
			$post_array = array_values($_POST) + $_POST;
			array_unshift ( $post_array, null);
			$value_array ['post'] = $post_array;
		}
		if (stripos ( $string, '[query:' ) !== false) {
			$query_array = array ();
			if (isset ( $_SERVER ['QUERY_STRING'] )) {
				$query_string = $_SERVER['QUERY_STRING'];
				$query_array = explode('&',$query_string);
				array_unshift ( $query_array, $query_string );
			}
			$value_array ['query'] = $query_array;
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
			$path_array ['path'] = $path_array;
			$value_array ['path'] = $path_array;
		}
		if (stripos ( $string, '[file:' ) !== false) {
			$file_array = array ();
			// 测试时要小心此处查找的是上上个调用者
			list(,$row) = debug_backtrace ( false );
			strtok ( $row ['file'], '/\\' );
			while ( ($tok = strtok ( '/\\' )) !== false ) {
				array_unshift ( $file_array, $tok );
			}
			$file_array [0] = strtok ( $file_array [0], '.' );
			array_unshift ( $file_array, strtok ( '.' ) );
			$value_array ['file'] = $file_array;
		}

		// 3. 生成返回数组
		$return4_array = array_values (array_intersect ($return_array, array('require','module','action','parameter')));
		if (empty ($return4_array)) {
			$return4_result = array ();
			$return4_final = '';
		} else {
			$return4_result = array_flip ($return4_array);
			if (isset ($return4_result ['require'])) {
				$return4_result ['require'] = false;
				$return4_final = 'require';
			}
			if (isset ($return4_result ['module'])) {
				$return4_result ['module'] = false;
				$return4_final = 'module';
			}
			if (isset ($return4_result ['action'])) {
				$return4_result ['action'] = false;
				$return4_final = 'action';
			}
			if (isset ($return4_result ['parameter'])) {
				$return4_result ['parameter'] = false;
				$return4_final = 'parameter';
			}
		}

		// 4. 处理引用部份
		$require_now = '';
		if ($require) {
			$result_array = self::_main_framework_resolve ($require, $value_array, 'require');
			foreach ($result_array as $value){
				$value_file = self::_path_file ( $value );
				if (is_file ( $value_file )) {
					$require_now = $value;
					$require_name = $value_file;
					break;
				}
			}
			if ($require_now === '') {
				if ($return4_final === '') {
					if (! in_array ('manual',$return_array)) {
						self::_main_hide ($array);
					}
					return false;
				} elseif (count($return4_array) === 1) {
					return false;
				} else {
					return array_values($return4_result);
				}
			}
			require_once $require_name;
		}
		if (isset ($return4_result ['require'])) {
			$return4_result ['require'] = $require_now;
		}
		if ($return4_array === array('require')) {
			return $require_now;
		} elseif ($return4_final === 'require') {
			return array_values($return4_result);
		}

		// 5. 处理模块部份
		$module_now = '';
		$result_array = self::_main_framework_resolve ($module, $value_array, 'module');
		foreach ($result_array as $value){
			if (preg_match ( '/(^|\\\\)[0-9]/', $value ) === 1) {
				continue;
			}
			try {
				if (! class_exists ($value)){
					continue;
				}
			} catch ( Exception $e ) {
				continue;
			}
			$class = new ReflectionClass ( $value );
			if ( $class->isInternal () || $class->isAbstract () || $class->isInterface () ) {
				continue;
			}
			$module_now = $value;
			break;
		}
		if ($module_now === '') {
			if ($return4_final === '') {
				if (! in_array ('manual',$return_array)) {
					self::_main_hide ($array);
				}
				return false;
			} elseif (count($return4_array) === 1) {
				return false;
			} else {
				return array_values($return4_result);
			}
		} else {
			if (isset ($return4_result ['module'])) {
				$return4_result ['module'] = $module_now;
			}
			if ($return4_array === array('module')) {
				return $module_now;
			} elseif ($return4_final === 'module') {
				return array_values($return4_result);
			}
		}

		// 6. 处理动作部份
		$action_now = '';
		$result_array = self::_main_framework_resolve ($action, $value_array, 'action',$module_now);
		foreach ($result_array as $value){
			list ($module_new, $action_new) = $value;
			if ($module_new !== $module_now) {
				if (preg_match ( '/(^|\\\\)[0-9]/', $module_new ) === 1) {
					continue;
				}
				try {
					if (! class_exists ($module_new)){
						continue;
					}
				} catch ( Exception $e ) {
					continue;
				}
				$class = new ReflectionClass ( $module_new );
				if ( $class->isInternal () || $class->isAbstract () || $class->isInterface () ) {
					continue;
				}
			}
			if (! method_exists ( $module_new, $action_new )) {
				continue;
			}
			$method = new ReflectionMethod ( $module_new, $action_new );
			if (! $method->isPublic () || $method->isConstructor () || $method->isDestructor () ) {
				continue;
			}
			if ( in_array('static', $return_array) && ! in_array('object', $return_array) ) {
				if (! $method->isStatic ()) {
					continue;
				}
			} elseif ( in_array('object', $return_array) && ! in_array('static', $return_array) ) {
				if ($method->isStatic ()) {
					continue;
				}
			}
			if ( in_array( 'final', $return_array ) ) {
				if (! $method->isFinal () ) {
					continue;
				}
			}
			$isstatic = $method->isStatic ();
			$module_now = $module_new;
			$action_now = $action_new;
			if (isset ($return4_result ['module'])) {
				$return4_result ['module'] = $module_now;
			}
			break;
		}
		if ($action_now === '') {
			if ($return4_final === '') {
				if (! in_array ('manual',$return_array)) {
					self::_main_hide ($array);
				}
				return false;
			} elseif (count($return4_array) === 1) {
				return false;
			} else {
				return array_values($return4_result);
			}
		} else {
			if (isset ($return4_result ['action'])) {
				$return4_result ['action'] = $action_now;
			}
			if ($return4_array === array('action')) {
				return $action_now;
			} elseif ($return4_final === 'action') {
				return array_values($return4_result);
			}
		}

		// 7. 处理参数部份
		$parameter_array = array();
		if ($parameter) {
			$result_array = self::_main_framework_resolve ($parameter, $value_array, 'parameter',array($module_now,$action_now));
			foreach ($result_array as $value){
				$parameter_array = array_slice ($value, 2);
				break;
			}
		}
		if (isset ($return4_result ['parameter'])) {
			$return4_result ['parameter'] = $parameter_array;
		}
		if ($return4_array === array('parameter')) {
			return $parameter_array;
		} elseif ($return4_final === 'parameter') {
			return array_values($return4_result);
		}

		// 8. 执行
		if ( ! $isstatic ) {
			$module_now = new $module_now;
		}
		$return = call_user_func_array ( array ($module_now, $action_now ) , $parameter_array );
		if ( in_array( 'return', $return_array ) ) {
			return $return;
		} else {
			return true;
		}

	}

	/**
	 * 框架规则解析
	 *
	 * @param string $string
	 * @param array $value_array
	 * @param string $flag
	 * @param mixed $prefix
	 * @return array
	 */
	private static function _main_framework_resolve ($string, $value_array, $flag, $prefix = null) {
		$result_array = array();
		$length = strlen($string);
		$last_pos = 0;
		$last_bracket = '|';
		$left_pos1 = 0;
		$left_pos2 = 0;
		$left_pos3 = 0;
		while (true) {

			// 寻找“或且非”截断的字符串
			if($last_pos >= $length) {
				break;
			}
			$left_pos1 === false or $left_pos1 = strpos ($string, '|', $last_pos);
			$left_pos2 === false or $left_pos2 = strpos ($string, '&', $last_pos);
			$left_pos3 === false or $left_pos3 = strpos ($string, '!', $last_pos);
			if ($left_pos1 === false && $left_pos2 === false && $left_pos3 === false){
				$str = substr ($string, $last_pos);
				$left_bracket = '';
				$last_pos = $length + 1;
			} else {
				$left_pos = false;
				if ($left_pos === false || $left_pos1 !== false && $left_pos1 < $left_pos) {
					$left_pos = $left_pos1;
					$left_bracket = '|';
				}
				if ($left_pos === false || $left_pos2 !== false && $left_pos2 < $left_pos) {
					$left_pos = $left_pos2;
					$left_bracket = '&';
				}
				if ($left_pos === false || $left_pos3 !== false && $left_pos3 < $left_pos) {
					$left_pos = $left_pos3;
					$left_bracket = '!';
				}
				$str = substr ($string, $last_pos, $left_pos - $last_pos);
				if ($left_bracket === '') {
					break;
				}
				$last_pos = $left_pos + 1;
			}
			$deal_bracket = $last_bracket;
			$last_bracket = $left_bracket;
			if ($str === '') {
				continue;
			}
			if ($flag === 'parameter' && $deal_bracket === '|') {
				$arr = explode(',', $str);
				$str = $prefix;
				foreach ($arr as $value) {
					$str [] = self::_main_framework_replace ($value, $value_array, false);
				}
			} else {
				$str = self::_main_framework_replace ($str, $value_array, true);
				if ($str === false || $str === '') {
					continue;
				}
			}

			// 处理“或且非”截断的字符串
			switch ($deal_bracket) {
				case '|':
					// “或”的处理
					if ($flag === 'action') {
						if (strpos($str,'::') === false) {
							$result_array [] = array ($prefix, $str);
						} else {
							list ($str1, $str2) = explode ('::', $str);
							if ($str1 === '' || $str2 === '') {
								continue;
							}
							$result_array [] = array ($str1, $str2);
						}
					} else {
						$result_array [] = $str;
					}
					break;
				case '&':
					// “且”的处理
					if ($flag === 'action' || $flag === 'parameter') {
						if (strpos($str,'::') === false) {
							$str1 = '';
							$str2 = $str;
						} else {
							list ($str1, $str2) = explode ('::', $str);
						}
						$bool_reg1 = strpos($str1,'*') !== false;
						if ($bool_reg1) {
							$str1 = '/'.str_replace('\\*', '.*?', preg_quote($str1, '/')).'/';
						}
						$bool_reg2 = strpos($str2,'*') !== false;
						if ($bool_reg2) {
							$str2 = '/'.str_replace('\\*', '.*?', preg_quote($str2, '/')).'/';
						}
						$right_array = array();
						foreach ($result_array as $value) {
							if (($str1 === '' || $bool_reg1 && preg_match ($str1,$value[0]) === 1 || $str1 === $value[0]) && 
								($str2 === '' || $bool_reg2 && preg_match ($str2,$value[1]) === 1 || $str2 === $value[1])) {
								$right_array [] = $value;
							}
						}
						$result_array = $right_array;
					} else {
						$bool_reg = strpos($str,'*') !== false;
						if ($bool_reg) {
							$str = '/'.str_replace('\\*', '.*?', preg_quote($str, '/')).'/';
						}
						$right_array = array();
						foreach ($result_array as $value) {
							if ($bool_reg && preg_match ($str,$value) === 1 || !$bool_reg && $str === $value) {
								$right_array [] = $value;
							}
						}
						$result_array = $right_array;
					}
					break;
				case '!':
					// “非”的处理
					if ($flag === 'action' || $flag === 'parameter') {
						if (strpos($str,'::') === false) {
							$str1 = '';
							$str2 = $str;
						} else {
							list ($str1, $str2) = explode ('::', $str);
						}
						$bool_reg1 = strpos($str1,'*') !== false;
						if ($bool_reg1) {
							$str1 = '/'.str_replace('\\*', '.*?', preg_quote($str1, '/')).'/';
						}
						$bool_reg2 = strpos($str2,'*') !== false;
						if ($bool_reg2) {
							$str2 = '/'.str_replace('\\*', '.*?', preg_quote($str2, '/')).'/';
						}
						$right_array = array();
						foreach ($result_array as $value) {
							if ($str1 !== '' && ($bool_reg1 && preg_match ($str1,$value[0]) !== 1 || !$bool_reg1 && $str1 !== $value[0]) || 
								$str2 !== '' && ($bool_reg2 && preg_match ($str2,$value[1]) !== 1 || !$bool_reg2 && $str2 !== $value[1])) {
								$right_array [] = $value;
							}
						}
						$result_array = $right_array;
					} else {
						$bool_reg = strpos($str,'*') !== false;
						if ($bool_reg) {
							$str = '/'.str_replace('\\*', '.*?', preg_quote($str, '/')).'/';
						}
						$right_array = array();
						foreach ($result_array as $value) {
							if ($bool_reg && preg_match ($str,$value) !== 1 || !$bool_reg && $str !== $value) {
								$right_array [] = $value;
							}
						}
						$result_array = $right_array;
					}
					break;
			}
		}
		return $result_array;
	}

	/**
	 * 框架字串替换
	 *
	 * @param string $string
	 * @param array $value_array
	 * @param bool $strict
	 * @return array
	 */
	private static function _main_framework_replace ($string, $value_array, $strict) {
		$result = '';
		$length = strlen($string);
		$last_pos = 0;
		$left_pos1 = 0;
		$left_pos2 = 0;
		while (true) {

			// 寻找需要替换字串
			if($last_pos >= $length) {
				break;
			}
			$left_pos1 === false or $left_pos1 = strpos ($string, '[', $last_pos);
			$left_pos2 === false or $left_pos2 = strpos ($string, '{', $last_pos);
			if ($left_pos1 === false && $left_pos2 === false){
				$result .= substr ($string, $last_pos);
				break;
			} elseif ($left_pos1 === false) {
				$left_pos = $left_pos2;
				$right_bracket = '}';
			} elseif ($left_pos2 === false) {
				$left_pos = $left_pos1;
				$right_bracket = ']';
			} elseif ($left_pos1 > $left_pos2) {
				$left_pos = $left_pos2;
				$right_bracket = '}';
			} else {
				$left_pos = $left_pos1;
				$right_bracket = ']';
			}
			$result .= substr ($string, $last_pos, $left_pos - $last_pos);
			$last_pos = $left_pos + 1;
			$right_pos = strpos ($string, $right_bracket, $last_pos);
			if ($right_pos === false) {
				return false;
			}
			$keyword = substr ($string, $last_pos, $right_pos - $last_pos);
			if ($keyword === '') {
				continue;
			}
			$last_pos = $right_pos + 1;

			// 替换成POST值
			if ($right_bracket === '}') {
				if (isset ( $_POST [$keyword] )) {
					$valueword = $_POST [$keyword];
				} else {
					if($strict) {
						return false;
					}
					continue;
				}

			// 替换成GET值
			} elseif (strpos ( $keyword, ':' ) === false) {
				if (isset ( $_GET [$keyword] )) {
					$valueword = $_GET [$keyword];
				} else {
					if($strict) {
						return false;
					}
					continue;
				}

			// 替换成其他定义值
			} else {
				list ($key, $sub) = explode ( ':', $keyword );
				if ($key === '' || $sub === ''){
					continue;
				}
				$key_lower = strtolower($key);
				$key_upper = strtoupper($key);
				$key_ucfirst = ucfirst($key_lower);
				$key_lcfirst = $key_upper;
				if ( strlen($key_lcfirst) > 0 ) {
					$key_lcfirst[0] = strtolower($key_lcfirst[0]);
				}
				switch ($key_lower){
					case 'get':
					case 'post':
					case 'query':
					case 'path':
					case 'file':
						$valueword = isset ( $value_array [$key_lower] [$sub] ) ? $value_array [$key_lower] [$sub] : '';
						break;
					default:
						$valueword = '';
						break;
				}
				switch ($key) {
					case $key_lower:
						break;
					case $key_upper:
						$valueword = strtoupper($valueword);
						break;
					case $key_ucfirst:
						$valueword = ucfirst(strtolower($valueword));
						break;
					case $key_lcfirst:
						$valueword = strtoupper($valueword);
						if ( strlen($valueword) > 0 ) {
							$valueword[0] = strtolower($valueword[0]);
						}
						break;
					default:
						$valueword = strtolower($valueword);
						break;
				}
			}

			// 验证命名规则
			if ($strict && preg_match ( '/^[a-zA-Z0-9_\x7f-\xff]+$/', $valueword ) !== 1) {
				return false;
			}

			$result .= $valueword;
		}
		return $result;
	}

	/**
	 * 扩展转义路径
	 *
	 * @param string $filename
	 * @param string $extension_path
	 * @return string
	 */
	private static function _path_extension($filename, $extension_path = null) {

		$filepath = self::_path_file ($filename);
		if ($filepath === null) {
			if (empty ($extension_path)) {
				$filepath = dirname (__FILE__) . DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . $filename;
			} elseif ($extension_path [0] === '@') {
				$filepath =  dirname (__FILE__) . DIRECTORY_SEPARATOR . substr ($extension_path, 1) . DIRECTORY_SEPARATOR . $filename;
			} else {
				$filepath = $extension_path . DIRECTORY_SEPARATOR . $filename;
			}
		}
		return $filepath;

	}

	/**
	 * 模板转义路径
	 *
	 * @param string $filename
	 * @param string $template_path
	 * @return string
	 */
	private static function _path_template($filename, $template_path = null) {

		$filepath = self::_path_file ($filename);
		if ($filepath === null) {
			if (empty ($template_path)) {
				$filepath = $filename;
			} elseif ($template_path [0] === '@') {
				$filepath =  dirname (__FILE__) . DIRECTORY_SEPARATOR . substr ($template_path, 1) . DIRECTORY_SEPARATOR . $filename;
			} else {
				$filepath = $template_path . DIRECTORY_SEPARATOR . $filename;
			}
		}
		return $filepath;

	}

	/**
	 * 配置转义路径
	 *
	 * @param string $filename
	 * @param string $config_path
	 * @return string
	 */
	private static function _path_config($filename, $config_path = null) {

		$filepath = self::_path_file ($filename);
		if ($filepath === null) {
			if (empty ($config_path)) {
				$filepath = $filename;
			} elseif ($config_path [0] === '@') {
				$filepath =  dirname (__FILE__) . DIRECTORY_SEPARATOR . substr ($config_path, 1) . DIRECTORY_SEPARATOR . $filename;
			} else {
				$filepath = $config_path . DIRECTORY_SEPARATOR . $filename;
			}
		}
		return $filepath;

	}

	/**
	 * 文件转义路径
	 *
	 * @param string $filename
	 * @return string
	 */
	private static function _path_file($filename) {

		$first = strlen($filename)>0 ? $filename[0] : '';
		if ($first === '@') {
			$filepath = dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . substr ($filename, 1);
		} elseif ($first === '\\' || $first === '/' || strncmp ( $filename, './', 2 ) === 0 || strncmp ( $filename, '.\\', 2 ) === 0 || strpos ( $filename, ':' ) !== false) {
			$filepath = $filename;
		} else {
			$filepath = null;
		}
		return $filepath;

	}

}

/**
 * 执行(execute)
 */
core::stub () and core::main ();
?>