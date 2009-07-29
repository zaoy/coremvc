<?php
/**
 * 定义
 * @name core类 
 * @version 0.6.4
 * @author Z(QQ号602000，QQ群5193883)
 */
class core {
	
	/**
	 * stub设置
	 * stub_router_enable			正向路由支持开关		true/false
	 * stub_router_pattern			正向路由程序特征		正则表达式
	 * stub_router_reverse_enable	反向路由功能开关		true/false
	 * stub_router_reverse_pattern	反向路由程序特征		正则表达式
	 * stub_router_reverse_redirect	反向路由跳转地址		URL，[module]表示模块名称
	 */
	const stub_router_enable = true;
	const stub_router_pattern = '/router\.php$/i';
	const stub_router_reverse_enable = false;
	const stub_router_reverse_pattern = '/router\.php$/i';
	const stub_router_reverse_redirect = 'router.php?go=[module]';
	
	/**
	 * view设置
	 * view_default_type			模板类型组合			include/string/smarty/nophp，可用小数点组合
	 * view_default_echo			输出或者返回			true/false
	 * 
	 * 注：	本函数支持include/string
	 * 		smarty模板需要core_smarty.php支持
	 * 		组合模板需要core_wrapper.php支持
	 */
	const view_default_type = 'include';
	const view_default_echo = true;
	
	/**
	 * db设置
	 * db_connect_provider			数据提供类型			'mysql'/'pdo'/'adodb'，默认mysql
	 * db_connect_dsn				连接字符串			''/dsn字符串/'mysql'/'mysqli'/'mysqlt'等，（对pdo/adodb有效）
	 * db_connect_type				连接类型				''/'persist'，默认链接/持久连接，（对mysql/adodb有效）
	 * db_connect_server			服务器				''/主机字符串，（对mysql/adodb有效）
	 * db_connect_username			帐号					''/帐号字符串，（对mysql/pdo/adodb有效）
	 * db_connect_password			密码					''/密码字符串，（对mysql/pdo/adodb有效）
	 * db_connect_new_link			新连接				''/true，是否新连接，（对mysql/adodb有效）
	 * db_connect_client_flags		客户端				''/整数，客户标识值，（对mysql有效）
	 * db_connect_dbname			数据库				''/数据库字符串，（对mysql/adodb有效）
	 * db_connect_charset			编码					''/编码字符串，（对mysql/adodb有效）
	 * db_connect_port				端口					''/端口字符串，（对adodb有效）
	 * db_connect_socket			socket				''/socket字符串，（对adodb有效）
	 * db_table_prefix				table前缀			''/前缀字符串，（对mysql/pdo/adodb有效）
	 * db_sequence_table			序列表类型			'InnoDB'/'MyISAM'，默认InnoDB，（对mysql/pdo有效）
	 * db_sequence_field			序列表字段类型		''/字段类型，默认INT，（对mysql/pdo有效）
	 *
	 * 注：	数据提供类型为adodb时需要core_adodb.php支持
	 */
	const db_connect_provider = '';
	const db_connect_dsn = '';
	const db_connect_type = '';
	const db_connect_server = '';
	const db_connect_username = '';
	const db_connect_password = '';
	const db_connect_new_link = '';
	const db_connect_client_flags = '';
	const db_connect_dbname = '';
	const db_connect_charset = '';
	const db_connect_port = '';
	const db_connect_socket = '';
	const db_table_prefix = '';
	const db_sequence_table = '';
	const db_sequence_field = '';
	
	/**
	 * 存根函数
	 * @return bool
	 */
	public static function stub() {
		
		// 【基础功能】判断访问或者引用
		$function_arr = array ('include', 'require', 'include_once', 'require_ocne' );
		$is_visit = true;
		$file = null;
		$file_arr = array ();
		foreach ( debug_backtrace ( false ) as $row ) {
			if (in_array ( $row ['function'], $function_arr )) {
				$file_arr [] = $row ['file'];
				$is_visit = false;
			} elseif (is_null ( $file ) && $row ['function'] == 'stub') {
				$file = $row ['file'];
			}
		}
		
		// 【扩展功能】实现正向路由支持
		if (self::stub_router_enable) {
			$is_visit = true;
			foreach ( $file_arr as $row ) {
				if (! preg_match ( self::stub_router_pattern, $row )) {
					$is_visit = false;
					break;
				}
			}
		}
		
		// 【扩展功能】实现反向路由功能
		if (self::stub_router_reverse_enable && $is_visit) {
			$is_reverse = true;
			foreach ( $file_arr as $row ) {
				$file = $row;
				if (preg_match ( self::stub_router_reverse_pattern, $row )) {
					$is_reverse = false;
					break;
				}
			}
			if ($is_reverse) {
				$route_reverse_redirect = str_replace ( '[module]', basename ( $file, '.php' ), self::stub_router_reverse_redirect );
				header ( 'Location: ' . $route_reverse_redirect );
				$is_visit = false;
			}
		}
		
		return $is_visit;
	
	}
	
	/**
	 * 入口函数
	 */
	public static function main() {
		
		// 【基础功能】隐藏程序
		if (PHP_SAPI == 'cli') {
			// 命令行模式
			echo ('Could not open input file: ' . basename ( $_SERVER ['SCRIPT_FILENAME'] ) . PHP_EOL);
		} else {
			// 浏览器模式
			header ( "HTTP/1.0 404 Not Found" );
		}
	
	}
	
	/**
	 * 视图函数
	 * @param string $_view_file
	 * @param array $_view_vars
	 * @param string $_view_type
	 * @param string $_view_echo
	 * @return string
	 */
	public static function view($_view_file, $_view_vars = array(), $_view_type = null, $_view_echo = null) {
		
		// 【基础功能】简单模板
		is_null ( $_view_type ) and $_view_type = self::view_default_type;
		is_null ( $_view_echo ) and $_view_echo = self::view_default_echo;
		switch ($_view_type) {
			case 'include' :
				extract ( $_view_vars );
				ob_start ();
				require ($_view_file);
				$_view_type_str = ob_get_clean ();
				break;
			case 'string' :
				extract ( $_view_vars );
				$_view_type_str = eval ( 'return <<<_END_OF_EVAL' . PHP_EOL . file_get_contents ( $_view_file ) . PHP_EOL . '_END_OF_EVAL;' . PHP_EOL );
				break;
			
			// 【扩展功能】smarty模板
			case 'smarty' :
				class_exists ( 'core_smarty' ) or require ('core_smarty.php');
				$smarty = core_smarty::init ( new smarty ( ) );
				$smarty->_tpl_vars = $_view_vars;
				$_view_type_str = $smarty->fetch ( $_view_file );
				break;
			
			// 【扩展功能】组合模板
			default :
				class_exists ( 'core_wrapper' ) or require ('core_wrapper.php');
				$_view_type_str = core_wrapper::init ( 'core.wrapper.' . $_view_type . '://' . $_view_file, $_view_vars );
		
		}
		
		if ($_view_echo) {
			echo $_view_type_str;
			return $_view_type_str;
		} else {
			return $_view_type_str;
		}
	
	}
	
	/**
	 * 数据库连接函数
	 * @param mix $args
	 * @return $dbh
	 */
	public static function connect($args = true) {
		
		static $dbh = null;
		
		// 【基础功能】返回连接句柄
		if ($args !== false) {
			if (is_resource ( $dbh )) {
				return $dbh;
			}
			if ($args === true) {
				$args = array ();
			}
			if (is_string ( $args )) {
				$args = array ('dsn' => $args );
			}
			isset ( $args ['dsn'] ) or $args ['dsn'] = self::db_connect_dsn;
			isset ( $args ['type'] ) or $args ['type'] = self::db_connect_type;
			isset ( $args ['server'] ) or $args ['server'] = self::db_connect_server;
			isset ( $args ['username'] ) or $args ['username'] = self::db_connect_username;
			isset ( $args ['password'] ) or $args ['password'] = self::db_connect_password;
			isset ( $args ['new_link'] ) or $args ['new_link'] = self::db_connect_new_link;
			isset ( $args ['client_flags'] ) or $args ['client_flags'] = self::db_connect_client_flags;
			isset ( $args ['dbname'] ) or $args ['dbname'] = self::db_connect_dbname;
			isset ( $args ['charset'] ) or $args ['charset'] = self::db_connect_charset;
			isset ( $args ['port'] ) or $args ['port'] = self::db_connect_port;
			isset ( $args ['socket'] ) or $args ['socket'] = self::db_connect_socket;
		}
		
		// 【基础功能】连接数据库
		if ($args !== false) {
			switch (self::db_connect_provider) {
				default :
				case 'mysql' :
					if ($args ['type'] == 'persist') {
						$dbh = mysql_pconnect ( $args ['server'], $args ['username'], $args ['password'], ( int ) $args ['client_flags'] );
					} else {
						$dbh = mysql_connect ( $args ['server'], $args ['username'], $args ['password'], ( bool ) $args ['new_link'], ( int ) $args ['client_flags'] );
					}
					if ($args ['dbname'] != '') {
						mysql_select_db ( $args ['dbname'], $dbh );
					}
					if ($args ['charset'] != '') {
						mysql_set_charset ( $args ['charset'], $dbh );
					}
					return $dbh;
				case 'pdo' :
					$dbh = new PDO ( $args ['dsn'], $args ['username'], $args ['password'] );
					return $dbh;
				case 'adodb' :
					class_exists ( 'core_adodb' ) or require ('core_adodb.php');
					$dbh = ADONewConnection ( $args ['dsn'] );
					if ($args ['server'] != '') {
						if ($args ['type'] == 'persist') {
							$dbh->PConnect ( $args ['server'], $args ['username'], $args ['password'], $args ['dbname'] );
						} elseif ($args ['new_link']) {
							$dbh->NConnect ( $args ['server'], $args ['username'], $args ['password'], $args ['dbname'] );
						} else {
							$dbh->Connect ( $args ['server'], $args ['username'], $args ['password'], $args ['dbname'] );
						}
						if ($args ['charset'] != '' && preg_match ( '/msyql/i', $args ['dsn'] )) {
							$dbh->Execute ( 'SET NAMES ' . $args ['charset'] );
						}
					}
					return $dbh;
			}
		}
		
		// 【基础功能】断开数据库
		if ($args === false) {
			if (is_resource ( $dbh )) {
				switch (self::db_connect_provider) {
					default :
					case 'mysql' :
						$return = mysql_close ( $dbh );
						$dbh = null;
						return $return;
					case 'pdo' :
						$dbh = null;
						return true;
					case 'adodb' :
						$return = $dbh->Close ();
						$dbh = null;
						return true;
				}
			} else {
				return false;
			}
		}
	
	}
	
	/**
	 * 数据库执行函数
	 * @param string $sql
	 * @param array $param
	 * @param array $ref
	 * @return $result
	 */
	public static function execute($sql, $param = array(), $ref = array()) {
		
		// 【基础功能】执行语句
		$dbh = self::connect ();
		switch (self::db_connect_provider) {
			default :
			case 'mysql' :
				if (count ( $param ) > 0) {
					$rand = rand ();
					$using = '';
					mysql_query ( 'PREPARE stmt' . $rand . ' FROM \'' . mysql_real_escape_string ( $sql ) . '\'', $dbh );
					foreach ( $param as $key => $value ) {
						if (is_null ( $value )) {
							mysql_query ( 'SET @param' . $rand . $key . '=NULL', $dbh );
						} elseif (is_int ( $value ) || is_float ( $value )) {
							mysql_query ( 'SET @param' . $rand . $key . '=' . $value, $dbh );
						} else {
							mysql_query ( 'SET @param' . $rand . $key . '=\'' . mysql_real_escape_string ( $value ) . '\'', $dbh );
						}
						if ($using == '') {
							$using = ' USING @param' . $rand . $key;
						} else {
							$using .= ',@param' . $rand . $key;
						}
					}
					$result = mysql_query ( 'EXECUTE stmt' . $rand . $using, $dbh );
					if ($result && count ( $ref ) > 0) {
						if (isset ( $ref ['insert_id'] )) {
							$ref ['insert_id'] = mysql_insert_id ( $dbh );
						}
						if (isset ( $ref ['affected_rows'] )) {
							$ref ['affected_rows'] = mysql_affected_rows ( $dbh );
						}
						if (isset ( $ref ['num_fields'] )) {
							$ref ['num_fields'] = mysql_num_fields ( $result );
						}
						if (isset ( $ref ['num_rows'] )) {
							$ref ['num_rows'] = mysql_num_rows ( $result );
						}
					}
					foreach ( $param as $key => $value ) {
						mysql_query ( 'SET @param' . $rand . $key . '=NULL', $dbh );
					}
					mysql_query ( 'DEALLOCATE PREPARE stmt' . $rand, $dbh );
					return $result;
				} else {
					return mysql_query ( $sql, $dbh );
				}
			case 'pdo' :
				if (count ( $param ) > 0) {
					if (count ( $ref ) > 0) {
						$sth = $dbh->prepare ( $sql, $ref );
						$sth->execute ( $param );
						return $sth;
					} else {
						$sth = $dbh->prepare ( $sql );
						$sth->execute ( $param );
						return $sth;
					}
				} else {
					if (count ( $ref ) > 0) {
						array_unshift ( $ref, $sql );
						return call_user_func_array ( array ($dbh, 'query' ), $ref );
					} else {
						return $dbh->query ( $sql );
					}
				}
			case 'adodb' :
				if (count ( $param ) > 0) {
					return $dbh->Execute ( $sql, $param );
				} else {
					return $dbh->Execute ( $sql );
				}
		}
	
	}
	
	/**
	 * SQL语句生成函数
	 * @param string $syntax
	 * @param array $param
	 * @param array $ref
	 * @return string
	 */
	public static function prepare($syntax, $param = array(), &$ref = array()) {
		
		// 【基础功能】生成语句和参数
		$syntax_explode = explode ( PHP_EOL, $syntax );
		$syntax_search = true;
		$syntax_relate = false;
		$syntax_array = array ();
		$syntax_param = array ();
		$syntax_clear = array ();
		$syntax_index = 0;
		foreach ( $syntax_explode as $syntax_line ) {
			if (isset ( $syntax_line [0] ) && $syntax_line [0] == chr ( 9 )) {
				if ($syntax_search) {
					if ($syntax_relate === false) {
						$syntax_clear [$syntax_index] = false;
					} else {
						$syntax_clear [$syntax_index] = $syntax_relate;
					}
					$syntax_array [$syntax_index] = '';
					$syntax_param [] = $syntax_index;
					$syntax_search = false;
					$syntax_index ++;
				}
			} else {
				if (isset ( $syntax_line [0] ) && $syntax_line [0] == '[') {
					$syntax_line = preg_replace ( '/^\[(.*?)((\||\]).*)?/', '$1', $syntax_line );
					$syntax_relate = $syntax_index;
				} elseif (isset ( $syntax_line [0] ) && $syntax_line [0] == '{') {
					$syntax_line = preg_replace ( '/^\{(.*?)((\||\}).*)/', '$1', $syntax_line );
					$syntax_relate = - 1;
				} else {
					$syntax_relate = - 1;
				}
				$syntax_array [$syntax_index] = $syntax_line;
				$syntax_search = true;
				$syntax_index ++;
			}
		}
		$param = ( array ) $param;
		$ref = array ();
		$param_index = 0;
		foreach ( $param as $param_key => $param_value ) {
			$param_case = strtok ( $param_key, ' _0123456789' );
			switch ($param_case) {
				case 'field2' :
				case 'field' :
				case 'table' :
					if (is_array ( $param_value )) {
						$param_text = '';
						$param_flag = false;
						foreach ( $param_value as $param_key2 => $param_value2 ) {
							if ($param_flag && ! preg_match ( '/^[,|\s|\t|\r|\n]/i', $param_value2 )) {
								$param_text .= ',' . $param_value2;
							} else {
								$param_text .= $param_value2;
							}
							$param_flag = ! preg_match ( '/[,|\s|\t|\r|\n]$/i', $param_value2 );
						}
						$param_value = $param_text;
					}
					if ($param_case == 'field2' && strlen ( $param_value ) > 0) {
						$param_value = '(' . $param_value . ')';
					}
					break;
				case 'where' :
					if (is_array ( $param_value )) {
						$param_text = '';
						foreach ( $param_value as $param_key2 => $param_value2 ) {
							$param_text2 = '';
							if (is_int ( $param_key2 )) {
								if (is_array ( $param_value2 )) {
									if (count ( $param_value2 ) > 0) {
										$param_text2 = '((' . implode ( ') OR (', $param_value2 ) . '))';
									}
								} else {
									$param_text2 = $param_value2;
								}
							} else {
								if (is_array ( $param_value2 )) {
									if (count ( $param_value2 ) > 0) {
										if (preg_match ( '/\?/i', $param_key2 )) {
											$param_text2 = $param_key2;
										} else {
											$param_text2 = $param_key2 . ' IN (' . implode ( ',', array_fill ( 0, count ( $param_value2 ), '?' ) ) . ')';
										}
										foreach ( $param_value2 as $param_value3 ) {
											$ref [] = $param_value3;
										}
									}
								} elseif (is_null ( $param_value2 )) {
									$param_text2 = $param_key2 . ' IS NULL';
								} else {
									if (preg_match ( '/\?/i', $param_key2 )) {
										$param_text2 = $param_key2;
									} else {
										$param_text2 = $param_key2 . '=?';
									}
									$ref [] = $param_value2;
								}
							}
							if ($param_text2 != '') {
								if ($param_text != '') {
									$param_text .= ' AND ' . $param_text2;
								} else {
									$param_text .= $param_text2;
								}
							}
						}
						$param_value = $param_text;
					}
					break;
				case 'other' :
					if (is_array ( $param_value )) {
						$param_text = '';
						foreach ( $param_value as $param_key2 => $param_value2 ) {
							$param_text2 = '';
							if (is_int ( $param_key2 )) {
								if (is_array ( $param_value2 )) {
									if (count ( $param_value2 ) > 0) {
										$param_text2 = implode ( ',', $param_value2 );
									}
								} else {
									$param_text2 = $param_value2;
								}
							} else {
								if (is_array ( $param_value2 )) {
									if (count ( $param_value2 ) > 0) {
										if (preg_match ( '/\?/i', $param_key2 )) {
											$param_text2 = $param_key2;
										} else {
											$param_text2 = $param_key2 . ' ' . implode ( ',', array_fill ( 0, count ( $param_value2 ), '?' ) );
										}
										foreach ( $param_value2 as $param_value3 ) {
											$ref [] = $param_value3;
										}
									}
								} else {
									if (preg_match ( '/\?/i', $param_key2 )) {
										$param_text2 = $param_key2;
									} else {
										$param_text2 = $param_key2 . ' ?';
									}
									$ref [] = $param_value2;
								}
							}
							if ($param_text2 != '') {
								if ($param_text != '') {
									$param_text .= ' ' . $param_text2;
								} else {
									$param_text .= $param_text2;
								}
							}
						}
						$param_value = $param_text;
					}
					break;
				case 'value' :
					if (is_array ( $param_value )) {
						$param_text = '';
						$param_flag = true;
						foreach ( $param_value as $param_key2 => $param_value2 ) {
							$param_text2 = '';
							if (is_array ( $param_value2 )) {
								$param_flag = false;
								$param_text3 = '';
								foreach ( $param_value2 as $param_key3 => $param_value3 ) {
									if (is_int ( $param_key3 )) {
										if ($param_text3 != '') {
											$param_text3 .= ',' . $param_value3;
										} else {
											$param_text3 .= $param_value3;
										}
									} else {
										if ($param_text3 != '') {
											$param_text3 .= ',?';
										} else {
											$param_text3 .= '?';
										}
										$ref [] = $param_value3;
									}
								}
								$param_text2 = '(' . $param_text3 . ')';
							} elseif (is_int ( $param_key2 )) {
								$param_text2 = $param_value2;
							} else {
								$param_text2 = '?';
								$ref [] = $param_value2;
							}
							if ($param_text2 != '') {
								if ($param_text != '') {
									$param_text .= ',' . $param_text2;
								} else {
									$param_text .= $param_text2;
								}
							}
						}
						if ($param_flag) {
							$param_value = '(' . $param_text . ')';
						} else {
							$param_value = $param_text;
						}
					}
					break;
				case 'set' :
					if (is_array ( $param_value )) {
						$param_text = '';
						foreach ( $param_value as $param_key2 => $param_value2 ) {
							$param_text2 = '';
							if (is_int ( $param_key2 )) {
								$param_text2 = $param_value2;
							} else {
								if (preg_match ( '/\?/i', $param_key2 )) {
									$param_text2 = $param_key2;
								} else {
									$param_text2 = $param_key2 . '=?';
								}
								$ref [] = $param_value2;
							}
							if ($param_text2 != '') {
								if ($param_text != '') {
									$param_text .= ',' . $param_text2;
								} else {
									$param_text .= $param_text2;
								}
							}
						}
						$param_value = $param_text;
					}
					break;
				default :
			}
			if ($param_value == '') {
				if ($syntax_clear [$syntax_param [$param_index]] !== false) {
					$syntax_array [$syntax_clear [$syntax_param [$param_index]]] = '';
				}
			} else {
				$syntax_array [$syntax_param [$param_index]] = chr ( 9 ) . $param_value;
			}
			$param_index ++;
		}
		return implode ( PHP_EOL, $syntax_array );
	
	}
	
	/**
	 * 生成唯一序列号
	 * @param string $tablename
	 * @param bool $use_prefix
	 * @param int $start_index
	 * @return int
	 */
	public static function sequence($tablename = 'sequence', $use_prefix = true, $start_index = 1) {
		
		// 【基础功能】生成序列号
		// 参数
		if ($use_prefix) {
			$tablename = self::db_table_prefix . $tablename;
		}
		// 执行
		$dbh = self::connect ();
		switch (self::db_connect_provider) {
			default :
			case 'mysql' :
				if (self::db_sequence_table == '') {
					$table_type = 'InnoDB';
				} else {
					$table_type = self::db_sequence_table;
				}
				if (self::db_sequence_field == '') {
					$field_type = 'INT';
				} else {
					$field_type = self::db_sequence_field;
				}
				$sql = 'CREATE TABLE IF NOT EXISTS ' . $tablename . PHP_EOL;
				$sql .= '(id ' . $field_type . ') ENGINE=' . $table_type;
				mysql_query ( $sql, $dbh );
				mysql_query ( 'LOCK TABLES ' . $tablename . ' WRITE,' . $tablename . ' AS r READ', $dbh );
				$rs = mysql_query ( 'SELECT id FROM ' . $tablename . ' AS r', $dbh );
				if (mysql_num_rows ( $rs ) > 0) {
					$return = mysql_result ( $rs, 0 ) + 1;
					mysql_query ( 'UPDATE ' . $tablename . ' SET id=' . $return, $dbh );
				} else {
					$return = $start_index;
					mysql_query ( 'INSERT INTO ' . $tablename . ' VALUES (' . $return . ')', $dbh );
				}
				mysql_query ( 'UNLOCK TABLES' );
				return $return;
			case 'pdo' :
				if (self::db_sequence_table == '') {
					$table_type = 'InnoDB';
				} else {
					$table_type = self::db_sequence_table;
				}
				if (self::db_sequence_field == '') {
					$field_type = 'INT';
				} else {
					$field_type = self::db_sequence_field;
				}
				$sql = 'CREATE TABLE IF NOT EXISTS ' . $tablename . PHP_EOL;
				$sql .= '(id ' . $field_type . ') ENGINE=' . $table_type;
				$count = $dbh->exec ( $sql );
				$dbh->exec ( 'LOCK TABLES ' . $tablename . ' WRITE,' . $tablename . ' AS r READ' );
				if ($count > 0) {
					$sth = $dbh->query ( 'SELECT id FROM ' . $tablename . ' AS r' );
					$return = $sth->fetchColumn () + 1;
					$dbh->query ( 'UPDATE ' . $tablename . ' SET id=' . $return );
				} else {
					$return = $start_index;
					$dbh->exec ( 'INSERT INTO ' . $tablename . ' VALUES (' . $return . ')' );
				}
				$dbh->exec ( 'UNLOCK TABLES' );
				return $return;
			case 'adodb' :
				return $dbh->GenID ( $tablename, $start_index );
		}
	}
	
	/**
	 * 变量载入函数
	 * @param mix $row
	 * @return bool
	 */
	public function struct($row) {
		
		// 【基础功能】实例载入数组
		if (is_array ( $row ) || is_object ( $row )) {
			foreach ( $row as $key => $value ) {
				$this->$key = $value;
			}
			return true;
		} else {
			return false;
		}
	
	}
	
	/**
	 * 数据库选择函数
	 * @param string $tablename
	 * @param bool $use_prefix
	 * @param int $primary_index
	 * @return bool
	 */
	public function select($tablename = '', $use_prefix = true, $primary_index = 1) {
		
		// 【基础功能】查询数据
		// 参数
		if ($tablename == '') {
			$tablename = get_class ( $this );
			if ($tablename == __CLASS__) {
				return false;
			}
		}
		if ($use_prefix) {
			$tablename = self::db_table_prefix . $tablename;
		}
		$fieldvars = get_object_vars ( $this );
		if (count ( $fieldvars ) == 0) {
			return false;
		}
		if ($primary_index > 0) {
			$field_index = 0;
			foreach ( $fieldvars as $primary_name => $primary_value ) {
				$field_index ++;
				if ($primary_index == $field_index) {
					break;
				}
			}
		}
		// 执行
		$dbh = self::connect ();
		switch (self::db_connect_provider) {
			default :
			case 'mysql' :
				if ($primary_index > 0) {
					$sql = sprintf ( 'SELECT * FROM ' . $tablename . ' WHERE ' . $primary_name . '=\'%s\' LIMIT 1', mysql_real_escape_string ( $primary_value ) );
				} else {
					$sql = 'SELECT * FROM ' . $tablename . ' LIMIT 1';
				}
				$result = mysql_query ( $sql, $dbh );
				if (! $result) {
					return false;
				}
				$row = mysql_fetch_assoc ( $result );
				mysql_free_result ( $result );
				foreach ( $row as $key => $value ) {
					$this->$key = $value;
				}
				return true;
			case 'pdo' :
				if ($primary_index > 0) {
					$sql = 'SELECT * FROM ' . $tablename . ' WHERE ' . $primary_name . '=? LIMIT 1';
					$sth = $dbh->prepare ( $sql, array (PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY ) );
					$sth->execute ( array ($primary_value ) );
				} else {
					$sql = 'SELECT * FROM ' . $tablename . ' LIMIT 1';
					$sth = $dbh->prepare ( $sql, array (PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY ) );
					$sth->execute ();
				}
				if ($sth->columnCount () == 0) {
					return false;
				}
				$row = $sth->fetch ( PDO::FETCH_ASSOC );
				$sth->closeCursor ();
				foreach ( $row as $key => $value ) {
					$this->$key = $value;
				}
				return true;
			case 'adodb' :
				if ($primary_index > 0) {
					$sql = 'SELECT * FROM ' . $tablename . ' WHERE ' . $primary_name . '=?';
					$rs = $dbh->SelectLimit ( $sql, 1, - 1, array ($primary_value ) );
				} else {
					$sql = 'SELECT * FROM ' . $tablename;
					$rs = $dbh->SelectLimit ( $sql, 1, - 1 );
				}
				if (! $rs) {
					return false;
				}
				$row = $rs->GetRowAssoc ( 2 );
				$rs->Close ();
				foreach ( $row as $key => $value ) {
					$this->$key = $value;
				}
				return true;
		}
	
	}
	
	/**
	 * 数据库插入函数
	 * @param string $tablename
	 * @param bool $use_prefix
	 * @param int $primary_index
	 * @return bool
	 */
	public function insert($tablename = '', $use_prefix = true, $primary_index = 1) {
		
		// 【基础功能】插入数据
		// 参数
		if ($tablename == '') {
			$tablename = get_class ( $this );
			if ($tablename == __CLASS__) {
				return false;
			}
		}
		if ($use_prefix) {
			$tablename = self::db_table_prefix . $tablename;
		}
		$fieldvars = get_object_vars ( $this );
		if (count ( $fieldvars ) == 0) {
			return false;
		}
		if ($primary_index > 0) {
			$field_index = 0;
			foreach ( $fieldvars as $primary_name => $primary_value ) {
				$field_index ++;
				if ($primary_index == $field_index) {
					unset ( $fieldvars [$primary_name] );
					break;
				}
			}
		}
		// 分析
		$fieldname = implode ( ',', array_keys ( $fieldvars ) );
		$valuename = implode ( ',', array_fill ( 0, count ( $fieldvars ), '?' ) );
		$paramvars = array_values ( $fieldvars );
		// 执行
		$dbh = self::connect ();
		switch (self::db_connect_provider) {
			default :
			case 'mysql' :
				$sql = 'INSERT INTO ' . $tablename . ' (' . $fieldname . ') VALUES (' . $valuename . ')';
				if ($primary_index > 0) {
					$result = self::execute ( $sql, $paramvars, array ('insert_id' => &$this->$primary_name ) );
				} else {
					$result = self::execute ( $sql, $paramvars );
				}
				return ( bool ) $result;
			case 'pdo' :
				$sql = 'INSERT INTO ' . $tablename . ' (' . $fieldname . ') VALUES (' . $valuename . ')';
				$sth = $dbh->prepare ( $sql );
				$result = $sth->execute ( $paramvars );
				if ($primary_index > 0 && $result) {
					$this->$primary_name = $dbh->lastInsertId ();
				}
				return ( bool ) $result;
			case 'adodb' :
				$sql = 'INSERT INTO ' . $tablename . ' (' . $fieldname . ') VALUES (' . $valuename . ')';
				$result = $dbh->Execute ( $sql, $paramvars );
				if ($primary_index > 0 && $result) {
					$this->$primary_name = $dbh->Insert_ID ();
				}
				return ( bool ) $result;
		}
	
	}
	
	/**
	 * 数据库更新函数
	 * @param string $tablename
	 * @param bool $use_prefix
	 * @param int $primary_index
	 * @return bool
	 */
	public function update($tablename = '', $use_prefix = true, $primary_index = 1) {
		
		// 【基础功能】修改数据
		// 参数
		if ($tablename == '') {
			$tablename = get_class ( $this );
			if ($tablename == __CLASS__) {
				return false;
			}
		}
		if ($use_prefix) {
			$tablename = self::db_table_prefix . $tablename;
		}
		$fieldvars = get_object_vars ( $this );
		if (count ( $fieldvars ) == 0) {
			return false;
		}
		if ($primary_index > 0) {
			$field_index = 0;
			foreach ( $fieldvars as $primary_name => $primary_value ) {
				$field_index ++;
				if ($primary_index == $field_index) {
					unset ( $fieldvars [$primary_name] );
					break;
				}
			}
		}
		// 分析
		$fieldname = array_keys ( $fieldvars );
		foreach ( $fieldname as &$fieldname_value ) {
			$fieldname_value = $fieldname_value . '=?';
		}
		$valuename = implode ( ',', $fieldname );
		$paramvars = array_values ( $fieldvars );
		if ($primary_index > 0) {
			array_push ( $paramvars, $primary_value );
		}
		// 执行
		$dbh = self::connect ();
		switch (self::db_connect_provider) {
			default :
			case 'mysql' :
				if ($primary_index > 0) {
					$sql = 'UPDATE ' . $tablename . ' SET ' . $valuename . ' WHERE ' . $primary_name . '=? LIMIT 1';
				} else {
					$sql = 'UPDATE ' . $tablename . ' SET ' . $valuename . ' LIMIT 1';
				}
				$result = self::execute ( $sql, $paramvars );
				return ( bool ) $result;
			case 'pdo' :
				if ($primary_index > 0) {
					$sql = 'UPDATE ' . $tablename . ' SET ' . $valuename . ' WHERE ' . $primary_name . '=? LIMIT 1';
				} else {
					$sql = 'UPDATE ' . $tablename . ' SET ' . $valuename . ' LIMIT 1';
				}
				$sth = $dbh->prepare ( $sql );
				$result = $sth->execute ( $paramvars );
				return ( bool ) $result;
			case 'adodb' :
				if ($primary_index > 0) {
					$sql = 'UPDATE ' . $tablename . ' SET ' . $valuename . ' WHERE ' . $primary_name . '=? LIMIT 1';
				} else {
					$sql = 'UPDATE ' . $tablename . ' SET ' . $valuename . ' LIMIT 1';
				}
				$result = $dbh->Execute ( $sql, $paramvars );
				return ( bool ) $result;
		}
	
	}
	
	/**
	 * 数据库删除函数
	 * @param string $tablename
	 * @param bool $use_prefix
	 * @param int $primary_index
	 * @return bool
	 */
	public function delete($tablename = '', $use_prefix = true, $primary_index = 1) {
		
		// 【基础功能】删除数据
		// 参数
		if ($tablename == '') {
			$tablename = get_class ( $this );
			if ($tablename == __CLASS__) {
				return false;
			}
		}
		if ($use_prefix) {
			$tablename = self::db_table_prefix . $tablename;
		}
		$fieldvars = get_object_vars ( $this );
		if (count ( $fieldvars ) == 0) {
			return false;
		}
		if ($primary_index > 0) {
			$field_index = 0;
			foreach ( $fieldvars as $primary_name => $primary_value ) {
				$field_index ++;
				if ($primary_index == $field_index) {
					break;
				}
			}
		}
		// 执行
		$dbh = self::connect ();
		switch (self::db_connect_provider) {
			default :
			case 'mysql' :
				if ($primary_index > 0) {
					$sql = 'DELETE FROM ' . $tablename . ' WHERE ' . $primary_name . '=? LIMIT 1';
					$result = self::execute ( $sql, array ($primary_value ) );
				} else {
					$sql = 'DELETE FROM ' . $tablename . ' LIMIT 1';
					$result = self::execute ( $sql );
				}
				return ( bool ) $result;
			case 'pdo' :
				if ($primary_index > 0) {
					$sql = 'DELETE FROM ' . $tablename . ' WHERE ' . $primary_name . '=? LIMIT 1';
					$sth = $dbh->prepare ( $sql );
					$result = $sth->execute ( array ($primary_value ) );
				} else {
					$sql = 'DELETE FROM ' . $tablename . ' LIMIT 1';
					$sth = $dbh->prepare ( $sql );
					$result = $sth->execute ();
				}
				return ( bool ) $result;
			case 'adodb' :
				if ($primary_index > 0) {
					$sql = 'DELETE FROM ' . $tablename . ' WHERE ' . $primary_name . '=? LIMIT 1';
					$result = $dbh->Execute ( $sql, array ($primary_value ) );
				} else {
					$sql = 'DELETE FROM ' . $tablename . ' LIMIT 1';
					$result = $dbh->Execute ( $sql );
				}
				return ( bool ) $result;
		}
	
	}
	
	/**
	 * 数据库更新函数
	 * @param string $tablename
	 * @param bool $use_prefix
	 * @param int $primary_index
	 * @return bool
	 */
	public function replace($tablename = '', $use_prefix = true, $primary_index = 1) {
		
		// 【基础功能】更新数据
		// 参数
		if ($tablename == '') {
			$tablename = get_class ( $this );
			if ($tablename == __CLASS__) {
				return false;
			}
		}
		if ($use_prefix) {
			$tablename = self::db_table_prefix . $tablename;
		}
		$fieldvars = get_object_vars ( $this );
		if (count ( $fieldvars ) == 0) {
			return false;
		}
		if ($primary_index > 0) {
			$field_index = 0;
			foreach ( $fieldvars as $primary_name => $primary_value ) {
				$field_index ++;
				if ($primary_index == $field_index) {
					unset ( $fieldvars [$primary_name] );
					break;
				}
			}
		}
		// 分析
		$fieldname = implode ( ',', array_keys ( $fieldvars ) );
		$valuename = implode ( ',', array_fill ( 0, count ( $fieldvars ), '?' ) );
		$paramvars = array_values ( $fieldvars );
		// 执行
		$dbh = self::connect ();
		switch (self::db_connect_provider) {
			default :
			case 'mysql' :
				$sql = 'REPLACE INTO ' . $tablename . ' (' . $fieldname . ') VALUES (' . $valuename . ')';
				$result = self::execute ( $sql, $paramvars );
				if ($primary_index > 0 && $result) {
					$this->$primary_name = mysql_insert_id ( $dbh );
				}
				return ( bool ) $result;
			case 'pdo' :
				$sql = 'REPLACE INTO ' . $tablename . ' (' . $fieldname . ') VALUES (' . $valuename . ')';
				$sth = $dbh->prepare ( $sql );
				$result = $sth->execute ( $paramvars );
				if ($primary_index > 0 && $result) {
					$this->$primary_name = $dbh->lastInsertId ();
				}
				return ( bool ) $result;
			case 'adodb' :
				$sql = 'REPLACE INTO ' . $tablename . ' (' . $fieldname . ') VALUES (' . $valuename . ')';
				$result = $dbh->Execute ( $sql, $paramvars );
				if ($primary_index > 0 && $result) {
					$this->$primary_name = $dbh->Insert_ID ();
				}
				return ( bool ) $result;
		}
	
	}
	
	/**
	 * 载入数组函数
	 * @param string $class
	 * @param array $array
	 * @return array
	 */
	public static function structs($class, $array) {
		
		// 【基础功能】对象载入数组
		if (is_array ( $array )) {
			$return = array ();
			foreach ( $array as $row ) {
				if (is_array ( $row ) || is_object ( $row )) {
					$obj = new $class ( );
					foreach ( $row as $key => $value ) {
						$obj->$key = $value;
					}
					array_push ( $return, $obj );
				}
			}
			return $return;
		} else {
			return false;
		}
	
	}
	
	/**
	 * 数据库选择函数
	 * @param mix $class
	 * @param mix $field
	 * @param mix $table
	 * @param mix $where
	 * @param mix $other
	 * @return $data
	 */
	public static function selects($class = '', $field = '', $table = '', $where = '', $other = '') {
		
		$syntax = <<<SYNTAX
SELECT
	[ALL | DISTINCT | DISTINCTROW ]
	[HIGH_PRIORITY]
	[STRAIGHT_JOIN]
	[SQL_SMALL_RESULT] [SQL_BIG_RESULT] [SQL_BUFFER_RESULT]
	[SQL_CACHE | SQL_NO_CACHE] [SQL_CALC_FOUND_ROWS]
	select_expr [, select_expr ...]
[FROM
	table_references
[WHERE
	where_condition]

	[GROUP BY {col_name | expr | position}
	[ASC | DESC], ... [WITH ROLLUP]]
	[HAVING where_condition]
	[ORDER BY {col_name | expr | position}
	[ASC | DESC], ...]
	[LIMIT {[offset,] row_count | row_count OFFSET offset}]
	[PROCEDURE procedure_name(argument_list)]
	[INTO OUTFILE 'file_name' export_options
	| INTO DUMPFILE 'file_name'
	| INTO var_name [, var_name]]
	[FOR UPDATE | LOCK IN SHARE MODE]]
SYNTAX;
		
		// 【基础功能】查询数据
		// 类名
		if (is_array ( $class )) {
			$class_arr = $class;
			foreach ( $class_arr as $class_arr_key => $class_arr_value ) {
				$classkey = $class_arr_key;
				$classname = $class_arr_value;
			}
			if (is_int ( $classkey )) {
				$classkey = '';
			}
			array_pop ( $class_arr );
		} else {
			$classkey = '';
			$classname = $class;
			$class_arr = array ('' );
		}
		// 参数
		if ($table == '') {
			$table = self::db_table_prefix . $classname;
		}
		if ($field == '') {
			$field = '*';
		}
		if ($other == '' || ! isset ( $other ['page'] )) {
			$page_f = false;
			$param = array ('field' => $field, 'table' => $table, 'where' => $where, 'other' => $other );
		} else {
			$page_f = true;
			$field2 = 'COUNT(*)';
			$other1 = array ();
			$other2 = array ();
			foreach ( $other as $other_key => $other_value ) {
				if ($other_key === 'page') {
					$page = &$other ['page'];
					$page ['size'] = ( int ) $page ['size'];
					$page ['page'] = ( int ) $page ['page'];
					if ($page ['size'] < 1) {
						$page ['size'] = 1;
					}
					if ($page ['page'] < 1) {
						$page ['page'] = 1;
					}
					$other1 ['LIMIT ?,?'] = array ($page ['size'] * ($page ['page'] - 1), $page ['size'] );
				} else {
					$other1 [$other_key] = $other_value;
					$other2 [$other_key] = $other_value;
				}
			}
			$param = array ('field' => $field, 'table' => $table, 'where' => $where, 'other' => $other1 );
			$param2 = array ('field' => $field2, 'table' => $table, 'where' => $where, 'other' => $other2 );
		}
		// 执行
		$dbh = self::connect ();
		switch (self::db_connect_provider) {
			default :
			case 'mysql' :
				if ($page_f) {
					$sql = self::prepare ( $syntax, $param2, $ref );
					$result = self::execute ( $sql, $ref );
					$page ['count'] = mysql_result ( $result, 0 );
					$page ['total'] = ceil ( $page ['count'] / $page ['size'] );
				}
				$sql = self::prepare ( $syntax, $param, $ref );
				$result = self::execute ( $sql, $ref );
				if ($result === false) {
					return false;
				}
				$data_arr = array ();
				if ($classname == '') {
					while ( $obj = mysql_fetch_assoc ( $result ) ) {
						array_push ( $data_arr, $obj );
					}
				} else {
					while ( $obj = mysql_fetch_object ( $result, $classname ) ) {
						array_push ( $data_arr, $obj );
					}
				}
				break;
			case 'pdo' :
				if ($page_f) {
					$sql = self::prepare ( $syntax, $param2, $ref );
					$sth = $dbh->prepare ( $sql, array (PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY ) );
					$sth->execute ( $ref );
					$page ['count'] = $sth->fetchColumn ( 0 );
					$page ['total'] = ceil ( $page ['count'] / $page ['size'] );
					$sth->closeCursor ();
				}
				$sql = self::prepare ( $syntax, $param, $ref );
				$sth = $dbh->prepare ( $sql, array (PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY ) );
				$result = $sth->execute ( $ref );
				if ($result === false) {
					return false;
				}
				$data_arr = array ();
				if ($classname == '') {
					while ( $obj = $sth->fetch ( PDO::FETCH_ASSOC ) ) {
						array_push ( $data_arr, $obj );
					}
				} else {
					while ( $obj = $sth->fetchObject ( $classname ) ) {
						array_push ( $data_arr, $obj );
					}
				}
				$sth->closeCursor ();
				break;
			case 'adodb' :
				if ($page_f) {
					$sql = self::prepare ( $syntax, $param2, $ref );
					$page ['count'] = $dbh->GetOne ( $sql, $ref );
					$page ['total'] = ceil ( $page ['count'] / $page ['size'] );
				}
				$sql = self::prepare ( $syntax, $param, $ref );
				$ADODB_FETCH_MODE = $GLOBALS ['ADODB_FETCH_MODE'];
				$GLOBALS ['ADODB_FETCH_MODE'] = ADODB_FETCH_ASSOC;
				$arr = $dbh->GetAll ( $sql, $ref );
				$GLOBALS ['ADODB_FETCH_MODE'] = $ADODB_FETCH_MODE;
				if ($arr === false) {
					return false;
				}
				$data_arr = array ();
				if ($classname == '') {
					foreach ( $arr as $row ) {
						array_push ( $data_arr, $row );
					}
				} else {
					foreach ( $arr as $row ) {
						$obj = new $classname ( );
						foreach ( $row as $key => $value ) {
							$obj->$key = $value;
						}
						array_push ( $data_arr, $obj );
					}
				}
				break;
		}
		// 整理结果
		$return = array ();
		foreach ( $data_arr as $data ) {
			$point1 = &$return;
			foreach ( $class_arr as $class ) {
				$point2 = array ();
				if ($class == '') {
					$point1 [] = &$point2;
				} else {
					if ($classname == '') {
						$point3 = $data [$class];
					} else {
						$point3 = $data->$class;
					}
					if (! isset ( $point1 [$point3] )) {
						$point1 [$point3] = &$point2;
					} else {
						$point2 = &$point1 [$point3];
					}
				}
				unset ( $point1 );
				$point1 = &$point2;
				unset ( $point2 );
			}
			if ($classkey == '') {
				$point1 = $data;
			} else {
				if ($classname == '') {
					$point1 = $data [$classkey];
				} else {
					$point1 = $data->$classkey;
				}
			}
		}
		return $return;
	
	}
	
	/**
	 * 数据库插入函数
	 * @param string $class
	 * @param mix $table
	 * @param mix $field2_set
	 * @param mix $value
	 * @param mix $other
	 * @return int
	 */
	public static function inserts($class = '', $table = '', $field2_set = '', $value = '', $other = '') {
		
		$syntax1 = <<<SYNTAX
INSERT
	[LOW_PRIORITY | DELAYED | HIGH_PRIORITY] [IGNORE]
	[INTO] tbl_name
	
	[(col_name,...)]
{VALUES | VALUE}
	({expr | DEFAULT},...),(...),...
	
	[ ON DUPLICATE KEY UPDATE
	col_name=expr
	[, col_name=expr] ... ]
SYNTAX;
		
		$syntax2 = <<<SYNTAX
INSERT
	[LOW_PRIORITY | DELAYED | HIGH_PRIORITY] [IGNORE]
	[INTO] tbl_name
SET
	col_name={expr | DEFAULT}, ...

    [ ON DUPLICATE KEY UPDATE
	col_name=expr
	[, col_name=expr] ... ]
SYNTAX;
		
		$syntax3 = <<<SYNTAX
INSERT
	[LOW_PRIORITY | HIGH_PRIORITY] [IGNORE]
    [INTO] tbl_name

	[(col_name,...)]

	SELECT ...
	[ ON DUPLICATE KEY UPDATE
	col_name=expr
	[, col_name=expr] ... ]
SYNTAX;
		
		// 【基础功能】插入数据
		// 参数
		if ($table == '') {
			$table = self::db_table_prefix . $class;
		}
		if ($value == '') {
			$break = false;
			foreach ( $field2_set as $field2_set_key => $field2_set_value ) {
				if (is_int ( $field2_set_key )) {
					$break = true;
					break;
				}
			}
			if ($break) {
				$syntax = $syntax3;
				$param = array ('table' => $table, 'field2' => $field2_set, '', 'other' => $other );
			} else {
				$syntax = $syntax2;
				$param = array ('table' => $table, 'set' => $field2_set, '', 'other' => $other );
			}
		} else {
			$syntax = $syntax1;
			$param = array ('table' => $table, 'field2' => $field2_set, 'value' => $value, 'other' => $other );
		}
		// 执行
		$dbh = self::connect ();
		switch (self::db_connect_provider) {
			default :
			case 'mysql' :
				$sql = self::prepare ( $syntax, $param, $ref );
				$result = self::execute ( $sql, $ref );
				return mysql_affected_rows ( $dbh );
			case 'pdo' :
				$sql = self::prepare ( $syntax, $param, $ref );
				$sth = $dbh->prepare ( $sql );
				$sth->execute ( $ref );
				return $sth->rowCount ();
			case 'adodb' :
				$sql = self::prepare ( $syntax, $param, $ref );
				$rs = $dbh->Execute ( $sql, $ref );
				return $rs->affectedrows;
		}
	
	}
	
	/**
	 * 数据库更新函数
	 * 数据库插入函数
	 * @param string $class
	 * @param mix $table
	 * @param mix $set
	 * @param mix $where
	 * @param mix $other
	 * @return int
	 */
	public static function updates($class = '', $table = '', $set = '', $where = '', $other = '') {
		
		$syntax1 = <<<SYNTAX
UPDATE
	[LOW_PRIORITY] [IGNORE] table_reference
SET
	col_name1={expr1|DEFAULT} [, col_name2={expr2|DEFAULT}] ...
[WHERE
	where_condition]

    [ORDER BY ...]
    [LIMIT row_count]
SYNTAX;
		
		$syntax2 = <<<SYNTAX
UPDATE
	[LOW_PRIORITY] [IGNORE] table_references
SET
	col_name1={expr1|DEFAULT} [, col_name2={expr2|DEFAULT}] ...

[WHERE
	where_condition]
SYNTAX;
		
		// 【基础功能】插入数据
		// 参数
		if ($table == '') {
			$table = self::db_table_prefix . $class;
		}
		if ($other == '') {
			$syntax = $syntax2;
			$param = array ('table' => $table, 'set' => $set, 'where' => $where, '' );
		} else {
			$syntax = $syntax1;
			$param = array ('table' => $table, 'set' => $set, 'where' => $where, 'other' => $other );
		}
		// 执行
		$dbh = self::connect ();
		switch (self::db_connect_provider) {
			default :
			case 'mysql' :
				$sql = self::prepare ( $syntax, $param, $ref );
				$result = self::execute ( $sql, $ref );
				return mysql_affected_rows ( $dbh );
			case 'pdo' :
				$sql = self::prepare ( $syntax, $param, $ref );
				$sth = $dbh->prepare ( $sql );
				$sth->execute ( $ref );
				return $sth->rowCount ();
			case 'adodb' :
				$sql = self::prepare ( $syntax, $param, $ref );
				$rs = $dbh->Execute ( $sql, $ref );
				return $rs->affectedrows;
		}
	
	}
	
	/**
	 * 数据库删除函数
	 * @param string $class
	 * @param mix $field
	 * @param mix $table
	 * @param mix $where
	 * @param mix $other
	 * @return int
	 */
	public static function deletes($class = '', $field = '', $table = '', $where = '', $other = '') {
		
		$syntax1 = <<<SYNTAX
DELETE
	[LOW_PRIORITY] [QUICK] [IGNORE]
FROM
	tbl_name
[WHERE
	where_condition]

	[ORDER BY ...]
	[LIMIT row_count]
SYNTAX;
		
		$syntax2 = <<<SYNTAX
DELETE
	[LOW_PRIORITY] [QUICK] [IGNORE]
	tbl_name[.*] [, tbl_name[.*]] ...
FROM
	table_references
[WHERE
	where_condition]
SYNTAX;
		
		$syntax3 = <<<SYNTAX
DELETE
	[LOW_PRIORITY] [QUICK] [IGNORE]
FROM
	tbl_name[.*] [, tbl_name[.*]] ...
USING
	table_references
[WHERE
	where_condition]
SYNTAX;
		
		// 【基础功能】插入数据
		// 参数
		if ($table == '') {
			$table = self::db_table_prefix . $class;
		}
		if ($other == '') {
			$syntax = $syntax2;
			$param = array ('field' => $field, 'table' => $table, 'where' => $where, '' );
		} else {
			$syntax = $syntax1;
			$param = array ('field' => $field, 'table' => $table, 'where' => $where, 'other' => $other );
		}
		// 执行
		$dbh = self::connect ();
		switch (self::db_connect_provider) {
			default :
			case 'mysql' :
				$sql = self::prepare ( $syntax, $param, $ref );
				$result = self::execute ( $sql, $ref );
				return mysql_affected_rows ( $dbh );
			case 'pdo' :
				$sql = self::prepare ( $syntax, $param, $ref );
				$sth = $dbh->prepare ( $sql );
				$sth->execute ( $ref );
				return $sth->rowCount ();
			case 'adodb' :
				$sql = self::prepare ( $syntax, $param, $ref );
				$rs = $dbh->Execute ( $sql, $ref );
				return $rs->affectedrows;
		}
	
	}
	
	/**
	 * 数据库更新函数
	 * @param string $class
	 * @param mix $table
	 * @param mix $field2_set
	 * @param mix $value
	 * @param mix $other
	 * @return int
	 */
	public static function replaces($class = '', $table = '', $field2_set = '', $value = '', $other = '') {
		
		$syntax1 = <<<SYNTAX
REPLACE
	[LOW_PRIORITY | DELAYED]
	[INTO] tbl_name

	[(col_name,...)]
{VALUES | VALUE}
	({expr | DEFAULT},...),(...),...
SYNTAX;
		
		$syntax2 = <<<SYNTAX
REPLACE
	[LOW_PRIORITY | DELAYED]
	[INTO] tbl_name
SET
	col_name={expr | DEFAULT}, ...
SYNTAX;
		
		$syntax3 = <<<SYNTAX
REPLACE
	[LOW_PRIORITY | DELAYED]
	[INTO] tbl_name

	[(col_name,...)]


	SELECT ...
SYNTAX;
		
		// 【基础功能】更新数据
		// 参数
		if ($table == '') {
			$table = self::db_table_prefix . $class;
		}
		if ($field2_set == '') {
			if ($other == '') {
				$syntax = $syntax2;
				$param = array ('table' => $table, 'set' => $field2_set, '', '' );
			} else {
				$syntax = $syntax3;
				$param = array ('table' => $table, 'field2' => $field2_set, '', 'other' => $other );
			}
		} else {
			$syntax = $syntax1;
			$param = array ('table' => $table, 'field2' => $field2_set, 'value' => $value, '' );
		}
		// 执行
		$dbh = self::connect ();
		switch (self::db_connect_provider) {
			default :
			case 'mysql' :
				$sql = self::prepare ( $syntax, $param, $ref );
				$result = self::execute ( $sql, $ref );
				return mysql_affected_rows ( $dbh );
			case 'pdo' :
				$sql = self::prepare ( $syntax, $param, $ref );
				$sth = $dbh->prepare ( $sql );
				$sth->execute ( $ref );
				return $sth->rowCount ();
			case 'adodb' :
				$sql = self::prepare ( $syntax, $param, $ref );
				$rs = $dbh->Execute ( $sql, $ref );
				return $rs->affectedrows;
		}
	
	}

}

/**
 * 存根
 */
core::stub () and core::main ();
?>
