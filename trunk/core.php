<?php
/**
 * CoreMVC核心模块
 * 
 * @version 1.1.0 alpha 6
 * @author Z <602000@gmail.com>
 * @link http://code.google.com/p/coremvc/
 */
/**
 * 定义(define)
 */
class core {
	
	/**
	 * 配置文件
	 *
	 * + 作用：载入配置文件。
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const config = ''; //默认不使用配置文件
	 *const config = '@config.php'; //载入相对核心文件的配置文件
	 *const config = 'config.php'; //载入绝对路径的配置文件
	 *const config = '/config.php'; //载入绝对路径的配置文件
	 * </code>
	 */
	const config = '';
	
	/**
	 * 自动载入开关
	 *
	 * + 作用：自动载入功能是否打开。
	 * + 定义：该值范围为逻辑值或空串。
	 * <code>
	 *const stub_autoload_enable = ''; //默认关闭
	 *const stub_autoload_enable = true; //打开自动载入功能
	 * </code>
	 */
	const stub_autoload_enable = '';
	
	/**
	 * 自动载入路径
	 *
	 * + 作用：自动载入的路径。
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const stub_autoload_path = ''; //默认不添加路径到include_path
	 *const stub_autoload_path = '@'; //添加相对核心文件路径到include_path
	 *const stub_autoload_path = 'class'; //添加相对路径到include_path
	 *const stub_autoload_path = '/class'; //添加绝对路径到include_path
	 * </code>
	 */
	const stub_autoload_path = '';
	
	/**
	 * 自动载入后缀
	 *
	 * + 作用：自动载入的后缀
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const stub_autoload_extensions = ''; //默认后缀'.inc,.php'
	 *const stub_autoload_extensions = '.php,.inc'; //改变后缀优先级
	 * </code>
	 */
	const stub_autoload_extensions = '';
	
	/**
	 * 自动载入顺序
	 *
	 * + 作用：自动载入的顺序，包括路径顺序
	 * + 定义：该值范围为逻辑值或空串。
	 * <code>
	 *const stub_autoload_prepend = ''; //默认顺序在最后面
	 *const stub_autoload_prepend = true; //顺序在最前面
	 * </code>
	 */
	const stub_autoload_prepend = '';
	
	/**
	 * 使用框架开关
	 *
	 * + 作用：使用框架功能是否打开。
	 * + 定义：该值范围为逻辑值或空串。
	 * <code>
	 *const main_framework_enable = ''; //默认关闭
	 *const main_framework_enable = true; //打开使用框架功能，返回true/false
	 *const main_framework_enable = 'require'; //返回require路径或false
	 *const main_framework_enable = 'module'; //返回module类名或false
	 *const main_framework_enable = 'action'; //返回action方法径或false
	 *const main_framework_enable = 'module,action'; //返回数组形式的module类名和action方法名或false
	 *const main_framework_enable = 'manual'; //打开使用框架功能，但不自动隐藏，返回true/false
	 *const main_framework_enable = 'return'; //打开使用框架功能，但不自动隐藏，返回方法的返回值
	 * </code>
	 */
	const main_framework_enable = '';
	
	/**
	 *  使用框架的包含文件
	 *
	 * + 作用：设置使用框架的包含文件。
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const main_framework_require = ''; //默认不包含文件，注意包含文件里不能有“|”和“!”
	 *const main_framework_require = '@module/[go].php'; //包含相对核心文件路径的module/[go].php参数文件名
	 *const main_framework_require = '[go]/[to].php'; //包含相对路径的[go]/[to].php文件名
	 *const main_framework_require = '/module/[p:2]/[p:1].php'; //包含绝对路径的/module/[p:2]/[p:1].php文件
	 * </code>
	 */
	const main_framework_require = '';
	
	/**
	 * 使用框架的模块参数
	 *
	 * + 作用：设置使用框架的模块参数。
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const main_framework_module = ''; //默认为'{static}|[f:1]!{self}'，'{static}'仅PHP5.3支持，[f:n]表示调用main的程序文件从右数起第n个名称
	 *const main_framework_module = '[go]!{self}'; //[go]表示GET参数，当前类被排除在外
	 *const main_framework_module = 'project\[go]|project\index'; //project命名空间加首字母大写的go参数为模块类，未找到则用project\index类，
	 *const main_framework_module = '[p:3]\[p:2]|project\index'; //PATH_INFO前两位为模块类，未找到则用project\index类，
	 * </code>
	 */
	const main_framework_module = '';
	
	/**
	 * 使用框架的行为参数
	 *
	 * + 作用：设置使用框架的行为参数。
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const main_framework_action = ''; //默认为'[do]!main'
	 *const main_framework_action = '[do]|default'; //do参数为行为方法，未找到则用default，注意行为只能是静态方法
	 *const main_framework_action = '[p:1]|default!main![p:2]'; //PATH_INFO右边第一个为行为方法，未找到则用default，main和[p:2]方法排除在外
	 * </code>
	 */
	const main_framework_action = '';
	
	/**
	 * 扩展模块路径
	 *
	 * + 作用：自动载入类功能是否打开。
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const path_extension_path = ''; //默认相对核心文件类名路径
	 *const path_extension_path = '@ext'; //为当前核心文件所在的"extension"目录，“@”开头相对核心文件路径
	 *const path_extension_path = 'ext'; //为当前目录所在的"extension"目录
	 *const path_extension_path = '/ext'; //为根目录上的"extension"目录
	 * </code>
	 */
	const path_extension_path = '';
	
	/**
	 * 视图模板路径
	 *
	 * + 作用：自动载入类功能是否打开。
	 * + 定义：该值范围为逻辑值或空串。
	 * <code>
	 *const path_template_path = ''; //默认当前目录相对路径
	 *const path_template_path = '@tpl'; //为当前核心文件所在的"template"目录，“@”开头相对核心文件路径
	 *const path_template_path = 'tpl'; //为当前目录所在的"template"目录
	 *const path_template_path = '/tpl'; //为根目录上的"template"目录
	 * </code>
	 */
	const path_template_path = '';
	
	/**
	 * 视图模板路径标识符
	 *
	 * + 作用：视图模板路径的标识符。
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const view_template_search = ''; //默认为不使用模板路径标识符
	 *const view_template_search = '.php'; //模板路径使用".php"为标识符
	 * </code>
	 */
	const view_template_search = '';
	
	/**
	 * 视图模板路径替换值
	 *
	 * + 作用：视图模板路径的标识符替换成该值。
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const view_template_replace = ''; //默认不进行模板路径替换值为空
	 *const view_template_replace = '.tpl'; //如果使用模板路径标识符，则模板路径标识符替换成".tpl"
	 * </code>
	 */
	const view_template_replace = '';
	
	/**
	 * 视图模板类型
	 *
	 * + 作用：定义默认的视图模板类型。
	 * + 定义：该值范围为字符串或空串，多级模板用小数点连接。
	 * <code>
	 *const view_template_type = ''; //默认是'include'模板，等效于include函数。
	 *const view_template_type = 'string'; //使用字符串模板，等效于""字符串定义。
	 *const view_template_type = 'smarty'; //使用smarty模板扩展库。
	 * </code>
	 * + 注意：include和string是内置模板，其他模板会自动在扩展库里寻找“view_模板类型.php”的程序。
	 */
	const view_template_type = '';
	
	/**
	 * 视图输出方式
	 *
	 * + 作用：视图默认是否直接显示输出结果。
	 * + 定义：该值范围为逻辑或空串。
	 * <code>
	 *const view_template_show = ''; //默认为true,直接输出结果
	 *const view_template_show = false; //不直接输出，仅返回结果
	 * </code>
	 */
	const view_template_show = '';
	
	/**
	 * 视图所在路径
	 *
	 * + 作用：定义默认的视图相对路径。
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const view_default_dir = ''; //默认为当前路径
	 *const view_default_dir = '@tpl'; //当前核心文件所在的"tpl"目录，'@'开头的都相对于核心文件路径
	 *const view_default_dir = 'tpl'; //当前目录所在的"tpl"目录
	 *const view_default_dir = '/tpl'; //根目录上的"tpl"目录
	 * </code>
	 */
	const view_default_dir = '';
	
	/**
	 * 数据库提供类型
	 *
	 * + 作用：数据库提供类型值，目前支持mysql、pdo、adodb，其中pdo和adodb仅支持mysql数据库。
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const db_connect_provider = ''; //默认使用"mysql"
	 *const db_connect_provider = 'mysql'; //使用"mysql"
	 *const db_connect_provider = 'pdo'; //使用"pdo"
	 *const db_connect_provider = 'adodb'; //使用"adodb"
	 * </code>
	 * + 注意：adodb是扩展库，需要有model_adodb.php和AdodbZip.php支持
	 */
	const db_connect_provider = '';
	
	/**
	 * 数据库连接字符串
	 *
	 * + 作用：数据库连接字符串值。（对pdo/adodb有效）
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const db_connect_dsn = ''; //adodb下默认使用默认连接方式
	 *const db_connect_dsn = 'mysql:dbname=testdb;host=127.0.0.1'; //标准连接方式(pdo)
	 *const db_connect_dsn = 'mysql'; //adodb下使用mssql
	 *const db_connect_dsn = 'mysqli'; //adodb下使用mssqli
	 *const db_connect_dsn = 'mysqlt'; //adodb下使用mssqlt
	 *const db_connect_dsn = 'mysqlt://user@pass:host/path?port=3307'; //使用连接字符串，请将下面的server设成空值(adodb)
	 * </code>
	 */
	const db_connect_dsn = '';
	
	/**
	 * 数据库连接类型
	 *
	 * + 作用：数据库连接类型值。（对mysql/adodb有效）
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const db_connect_type = ''; //默认不使用持久连接
	 *const db_connect_type = 'persist'; //使用持久连接
	 * </code>
	 */
	const db_connect_type = '';
	
	/**
	 * 数据库连接服务器
	 *
	 * + 作用：服务器值。（对mysql/adodb有效）
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const db_connect_server = ''; //默认服务器为"localhost"
	 *const db_connect_server = '127.0.0.1'; //服务器"127.0.0.1"
	 *const db_connect_server = '127.0.0.1:3307'; //指定服务器和端口(mysql)
	 * </code>
	 */
	const db_connect_server = '';
	
	/**
	 * 数据库连接帐号
	 *
	 * + 作用：帐号。（对mysql/pdo/adodb有效）
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const db_connect_username = ''; //默认帐号为"ODBC"
	 *const db_connect_username = 'root'; //帐号使用"root"
	 * </code>
	 */
	const db_connect_username = '';
	
	/**
	 * 数据库连接密码
	 *
	 * + 作用：密码。（对mysql/pdo/adodb有效）
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const db_connect_password = ''; //默认密码为空
	 *const db_connect_password = 'admin'; //密码使用"admin"
	 * </code>
	 */
	const db_connect_password = '';
	
	/**
	 * 数据库连接新连接参数
	 *
	 * + 作用：客户端值。（对mysql/adodb有效）
	 * + 定义：该值范围为逻辑值或空串。
	 * <code>
	 *const db_connect_new_link = ''; //默认不使用新连接
	 *const db_connect_new_link = true; //使用新连接
	 * </code>
	 */
	const db_connect_new_link = '';
	
	/**
	 * 数据库连接客户端参数
	 *
	 * + 作用：客户端参数。（对mysql/adodb有效）
	 * + 定义：该值范围为整数或空串。
	 * <code>
	 *const db_connect_client_flags = ''; //默认为0
	 *const db_connect_client_flags = 128; //可使用LOAD DATA LOCAL语句
	 * </code>
	 */
	const db_connect_client_flags = '';
	
	/**
	 * 数据库连接初始数据库
	 *
	 * + 作用：初始数据库。（对mysql/adodb有效）
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const db_connect_dbname = ''; //默认不连接特定数据库
	 *const db_connect_dbname = 'test'; //连接到test数据库
	 * </code>
	 */
	const db_connect_dbname = '';
	
	/**
	 * 数据库连接编码
	 *
	 * + 作用：编码值。（对mysql/adodb有效）
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const db_connect_charset = ''; //默认使用默认的编码连接
	 *const db_connect_charset = 'GBK'; //使用"GBK"编码连接
	 * </code>
	 */
	const db_connect_charset = '';
	
	/**
	 * 数据库连接端口号
	 *
	 * + 作用：端口号。（对adodb有效）
	 * + 定义：该值范围为整数或空串。
	 * <code>
	 *const db_connect_port = ''; //默认使用默认的3306端口
	 *const db_connect_port = 3307; //设置端口为3307(adodb)
	 * </code>
	 */
	const db_connect_port = '';
	
	/**
	 * 数据库连接socket值
	 *
	 * + 作用：socket值。（对adodb有效）
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const db_connect_socket = ''; //默认不特定使用socket
	 *const db_connect_socket = '/tmp/mysql.sock'; //设置socket为"/tmp/mysql.sock"(adodb的MySQLi)
	 * </code>
	 */
	const db_connect_socket = '';
	
	/**
	 * 表名前缀标识符
	 *
	 * + 作用：SQL语句里会将该值作为表名前缀标识符。（对mysql/pdo/adodb有效）
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const db_prefix_search = ''; //默认为不使用表名前缀
	 *const db_prefix_search = 'prefix_'; //表名前缀使用"prefix_"为标识符
	 * </code>
	 */
	const db_prefix_search = '';
	
	/**
	 * 表名前缀替换值
	 *
	 * + 作用：SQL语句里会将表名前缀标识符替换成该值。（对mysql/pdo/adodb有效）
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const db_prefix_replace = ''; //默认不进行表名前缀替换值为空
	 *const db_prefix_replace = 'sample_'; //如果使用表名前缀标识符，则前缀标识符替换成"sample_"
	 * </code>
	 */
	const db_prefix_replace = '';
	
	/**
	 * 存根函数（可继承）
	 *
	 * + 作用：1.设置存根参数；2.自动载入功能，默认关闭；3.判断访问或者引用，返回true/false(访问/引用)。
	 * + 示例：
	 * <code>
	 *sample::stub (array('autoload_enable'=>true,'autoload_path'=>'@')); //设置存根参数
	 *sample::stub (true) and sample::main (); //在直接访问sample模块时手动设置启用自动载入功能
	 *sample::stub () and sample::main (); //只有直接访问sample模块时才执行入口函数（通常写法）
	 * </code>
	 * @param bool $autoload_enable
	 * @param string $autoload_path
	 * @param string $autoload_extensions
	 * @param bool $autoload_prepend
	 * @return bool
	 */
	static public function stub($autoload_enable = null, $autoload_path = null, $autoload_extensions = null, $autoload_prepend = null) {
		
		// 【基础功能】设置存根参数
		static $static_config = null;
		if ($static_config === null) {
			$static_config = self::init ( - 3 );
			$static_config = array ('autoload_enable' => $static_config ['autoload_enable'], 
					'autoload_path' => $static_config ['autoload_path'], 
					'autoload_extensions' => $static_config ['autoload_extensions'], 
					'autoload_prepend' => $static_config ['autoload_prepend'] );
		}
		static $static_autoload_realpath = null;
		if (is_array ( $autoload_enable )) {
			$static_autoload_realpath = null;
			foreach ( $static_config as $key => $value ) {
				isset ( $autoload_enable [$key] ) and $static_config [$key] = $autoload_enable [$key];
			}
			return $static_config;
		}
		isset ( $autoload_enable ) and $static_config ['autoload_enable'] = $autoload_enable;
		isset ( $autoload_path ) and $static_config ['autoload_path'] = $autoload_path;
		isset ( $autoload_extensions ) and $static_config ['autoload_extensions'] = $autoload_extensions;
		isset ( $autoload_prepend ) and $static_config ['autoload_prepend'] = $autoload_prepend;
		
		// 【基础功能】判断访问或者引用
		foreach ( debug_backtrace ( false ) as $row ) {
			switch ($row ['function']) {
				case 'include' :
				case 'require' :
				case 'include_once' :
				case 'require_once' :
					return false;
			}
		}
		
		// 【基础功能】自动载入功能
		if ($autoload_enable || $static_config ['autoload_enable'] && $static_autoload_realpath === null) {
			$static_autoload_realpath = self::path ( $static_config ['autoload_path'] );
			if ($static_autoload_realpath != '') {
				if ( $static_config ['autoload_prepend'] === true ) {
					set_include_path ( $static_autoload_realpath . PATH_SEPARATOR . get_include_path () );
				} else {
					set_include_path ( get_include_path () . PATH_SEPARATOR . $static_autoload_realpath );
				}
			}
			if ($static_config ['autoload_extensions'] != '') {
				spl_autoload_extensions ( $static_config ['autoload_extensions'] );
			}
			if ( $static_config ['autoload_prepend'] === true ) {
				spl_autoload_register ( null, true, true );
			} else {
				spl_autoload_register ();
			}
		}
		
		return true;
	
	}
	
	/**
	 * 入口函数（可继承）
	 *
	 * + 作用：1.设置入口参数；2.使用框架功能，默认关闭；3.模拟文件隐藏效果，返回true/false(框架/隐藏)。
	 * + 示例：
	 * <code>
	 *sample::main (array('framework_enable'=>true,'framework_action'=>'[action]Action')); //设置入口参数
	 *sample::stub () and sample::main (true,null,null,'[action]Action'); //只有直接访问sample模块时使用自定义框架
	 * </code>
	 * @param mix $framework_enable
	 * @param string $framework_require
	 * @param string $framework_mdoule
	 * @param string $framework_action
	 * @return bool
	 */
	static public function main($framework_enable = null, $framework_require = null, $framework_module = null, $framework_action = null) {
		
		// 【基础功能】设置入口参数
		static $static_config = null;
		if ($static_config === null) {
			$static_config = self::init ( - 3 );
			$static_config = array ('framework_enable' => $static_config ['framework_enable'], 'framework_require' => $static_config ['framework_require'], 
					'framework_module' => $static_config ['framework_module'], 'framework_action' => $static_config ['framework_action'] );
		}
		if (is_array ( $framework_enable )) {
			foreach ( $static_config as $key => $value ) {
				isset ( $framework_enable [$key] ) and $static_config [$key] = $framework_enable [$key];
			}
			return $static_config;
		}
		isset ( $framework_enable ) and $static_config ['framework_enable'] = $framework_enable;
		isset ( $framework_require ) and $static_config ['framework_require'] = $framework_require;
		isset ( $framework_module ) and $static_config ['framework_module'] = $framework_module;
		isset ( $framework_action ) and $static_config ['framework_action'] = $framework_action;
		
		// 【基础功能】使用框架功能
		while ( $static_config ['framework_enable'] ) {
			// 1. 默认
			$require = $static_config ['framework_require'];
			$module = $static_config ['framework_module'] === '' ? '{static}|[f:1]!{self}' : $static_config ['framework_module'];
			$action = $static_config ['framework_action'] === '' ? '[do]!main' : $static_config ['framework_action'];
			// 2. 数组
			if (strpos ( $require, '[f:' ) !== false || strpos ( $module, '[f:' ) !== false || strpos ( $action, '[f:' ) !== false) {
				foreach ( debug_backtrace ( false ) as $row ) {
					$file_array = array ();
					strtok ( $row ['file'], '/\\' );
					while ( ($tok = strtok ( '/\\' )) !== false ) {
						array_unshift ( $file_array, $tok );
					}
					$file_array [0] = strtok ( $file_array [0], '.' );
					break;
				}
				array_unshift ( $file_array, null );
			}
			if (strpos ( $require, '[p:' ) !== false || strpos ( $module, '[p:' ) !== false || strpos ( $action, '[p:' ) !== false) {
				$path_array = array ();
				if (isset ( $_SERVER ['PATH_INFO'] )) {
					$tok = strtok ( $_SERVER ['PATH_INFO'], '/\\' );
					while ( $tok !== false ) {
						array_unshift ( $path_array, $tok );
						$tok = strtok ( '/\\' );
					}
				}
				array_unshift ( $path_array, null );
			}
			$return_false = in_array ( $static_config ['framework_enable'], array ('require', 'module', 'action', 'module,action', 'manual', 'return' ), true );
			// 3. 引用
			if ($require != '') {
				$result = '';
				$pos1 = 0;
				while ( true ) {
					$pos2 = strpos ( $require, '[', $pos1 );
					if ($pos2 === false) {
						$result .= substr ( $require, $pos1 );
						break;
					}
					$result .= substr ( $require, $pos1, $pos2 - $pos1 );
					$pos1 = $pos2 + 1;
					$pos2 = strpos ( $require, ']', $pos1 );
					if ($pos2 === false) {
						break;
					}
					$tok = substr ( $require, $pos1, $pos2 - $pos1 );
					$pos1 = $pos2 + 1;
					if (strncmp ( $tok, 'f:', 2 ) == 0) {
						$sub = substr ( $tok, 2 );
						if (isset ( $file_array [$sub] )) {
							$str = $file_array [$sub];
						} else {
							if ($return_false) {
								return false;
							}
							break 2;
						}
					} elseif (strncmp ( $tok, 'p:', 2 ) == 0) {
						$sub = substr ( $tok, 2 );
						if (isset ( $path_array [$sub] )) {
							$str = $path_array [$str];
						} else {
							if ($return_false) {
								return false;
							}
							break 2;
						}
					} else {
						$sub = $tok;
						if (isset ( $_GET [$sub] )) {
							$str = $_GET [$sub];
						} else {
							if ($return_false) {
								return false;
							}
							break 2;
						}
					}
					if (preg_match ( '/^[a-zA-Z0-9_\x7f-\xff]+$/', $str ) === false)
						break 2;
					$result .= $str;
				}
				$result = self::path ( $result );
				if (! is_file ( $result )) {
					if ($return_false) {
						return false;
					}
					break;
				} else {
					if ($static_config ['framework_enable'] === 'require') {
						return $result;
					}
				}
				require_once $result;
			}
			// 4. 模块
			$result = '';
			$pos1 = 0;
			while ( true ) {
				$pos2 = strpos ( $module, '[', $pos1 );
				if ($pos2 === false) {
					$result .= substr ( $module, $pos1 );
					break;
				}
				$result .= substr ( $module, $pos1, $pos2 - $pos1 );
				$pos1 = $pos2 + 1;
				$pos2 = strpos ( $module, ']', $pos1 );
				if ($pos2 === false) {
					break;
				}
				$tok = substr ( $module, $pos1, $pos2 - $pos1 );
				$pos1 = $pos2 + 1;
				if (strncmp ( $tok, 'f:', 2 ) == 0) {
					$sub = substr ( $tok, 2 );
					if (isset ( $file_array [$sub] )) {
						$str = $file_array [$sub];
					} else {
						if ($return_false) {
							return false;
						}
						break 2;
					}
				} elseif (strncmp ( $tok, 'p:', 2 ) == 0) {
					$sub = substr ( $tok, 2 );
					if (isset ( $path_array [$sub] )) {
						$str = $path_array [$str];
					} else {
						if ($return_false) {
							return false;
						}
						break 2;
					}
				} else {
					$sub = $tok;
					if (isset ( $_GET [$sub] )) {
						$str = $_GET [$sub];
					} else {
						if ($return_false) {
							return false;
						}
						break 2;
					}
				}
				if (preg_match ( '/^[a-zA-Z0-9_\x7f-\xff]+$/', $str ) === false)
					break 2;
				$result .= $str;
			}
			if (strpos ( $result, '{self}' ) !== false) {
				$result = str_replace ( '{self}', __CLASS__, $result );
			}
			if (strpos ( $result, '{static}' ) !== false) {
				if (function_exists ( 'get_called_class' )) {
					$result = str_replace ( '{static}', get_called_class (), $result );
				} else {
					$result = str_replace ( '{static}', '', $result );
				}
			}
			$module_not = explode ( '!', $result );
			$module_arr = explode ( '|', array_shift ( $module_not ) );
			$module_now = '';
			foreach ( $module_arr as $module_name ) {
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
				if ($class->isUserDefined ()) {
					$module_now = $module_name;
					break;
				}
			}
			if ($module_now === '') {
				if ($return_false) {
					return false;
				}
				break;
			} else {
				if ($static_config ['framework_enable'] === 'module') {
					return $module_now;
				}
			}
			// 5. 动作
			$result = '';
			$pos1 = 0;
			while ( true ) {
				$pos2 = strpos ( $action, '[', $pos1 );
				if ($pos2 === false) {
					$result .= substr ( $action, $pos1 );
					break;
				}
				$result .= substr ( $action, $pos1, $pos2 - $pos1 );
				$pos1 = $pos2 + 1;
				$pos2 = strpos ( $action, ']', $pos1 );
				if ($pos2 === false) {
					break;
				}
				$tok = substr ( $action, $pos1, $pos2 - $pos1 );
				$pos1 = $pos2 + 1;
				if (strncmp ( $tok, 'f:', 2 ) == 0) {
					$sub = substr ( $tok, 2 );
					if (isset ( $file_array [$sub] )) {
						$str = $file_array [$sub];
					} else {
						if ($return_false) {
							return false;
						}
						break 2;
					}
				} elseif (strncmp ( $tok, 'p:', 2 ) == 0) {
					$sub = substr ( $tok, 2 );
					if (isset ( $path_array [$sub] )) {
						$str = $path_array [$str];
					} else {
						if ($return_false) {
							return false;
						}
						break 2;
					}
				} else {
					$sub = $tok;
					if (isset ( $_GET [$sub] )) {
						$str = $_GET [$sub];
					} else {
						if ($return_false) {
							return false;
						}
						break 2;
					}
				}
				if (preg_match ( '/^[a-zA-Z0-9_\x7f-\xff]+$/', $str ) === false) {
					if ($return_false) {
						return false;
					}
					break 2;
				}
				$result .= $str;
			}
			$action_not = explode ( '!', $result );
			$action_arr = explode ( '|', array_shift ( $action_not ) );
			$action_now = '';
			foreach ( $action_arr as $action_name ) {
				if (in_array ( $action_name, $action_not )) {
					continue;
				}
				if (! method_exists ( $module_now, $action_name )) {
					continue;
				}
				$method = new ReflectionMethod ( $module_now, $action_name );
				if ($method->isStatic () && $method->isPublic ()) {
					$action_now = $action_name;
					break;
				}
			}
			if ($action_now === '') {
				if ($return_false) {
					return false;
				}
				break;
			} else {
				if ($static_config ['framework_enable'] === 'action') {
					return $action_now;
				} elseif ($static_config ['framework_enable'] === 'module,action') {
					return array ($module_now, $action_now );
				}
			}
			// 6. 执行
			if ($static_config ['framework_enable'] === 'return') {
				return call_user_func ( array ($module_now, $action_now ) );
			} else {
				call_user_func ( array ($module_now, $action_now ) );
			}
			return true;
		}
		
		// 【基础功能】模拟文件隐藏效果
		if ($static_config ['framework_enable'] !== 'manual') {
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
	 * + 作用：1.设置路径参数；2.返回转换路径；3.返回扩展路径；4.返回视图路径。
	 * + 示例：
	 * <code>
	 *sample::main (array('extension_path'=>'@ext','template_path'=>'@tpl')); //设置路径参数
	 *require_once core::path('sample.php','extension');
	 *require core::path('@sample.tpl','template');
	 * </code>
	 * @param string $filename
	 * @param string $filetype
	 * @return string
	 */
	static public function path($filename, $filetype = null) {
		
		// 【基础功能】设置路径参数
		static $static_config = null;
		if ($static_config === null) {
			$static_config = self::init ( - 3 );
			$static_config = array ('extension_path' => $static_config ['extension_path'], 'template_path' => $static_config ['template_path'] );
		}
		static $static_extension_realpath = null;
		static $static_template_realpath = null;
		$filename_is_array = is_array ( $filename );
		if ($filename_is_array && isset ( $filename ['extension_path'] ) || $static_extension_realpath === null) {
			$filename_is_array && isset ( $filename ['extension_path'] ) and $static_config ['extension_path'] = $filename ['extension_path'];
			if ($static_config ['extension_path'] === '') {
				$static_extension_realpath = dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR;
			} elseif (strncmp ( $static_config ['extension_path'], '@', 1 ) == 0) {
				$static_extension_realpath = dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . substr ( $static_config ['extension_path'], 1 ) . DIRECTORY_SEPARATOR;
			} else {
				$static_extension_realpath = $static_config ['extension_path'] . DIRECTORY_SEPARATOR;
			}
		}
		if ($filename_is_array && isset ( $filename ['template_path'] ) || $static_template_realpath === null) {
			$filename_is_array && isset ( $filename ['template_path'] ) and $static_config ['template_path'] = $filename ['template_path'];
			if ($static_config ['template_path'] === '') {
				$static_template_realpath = dirname ( __FILE__ ) . DIRECTORY_SEPARATOR;
			} elseif (strncmp ( $static_config ['template_path'], '@', 1 ) == 0) {
				$static_template_realpath = dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . substr ( $static_config ['template_path'], 1 ) . DIRECTORY_SEPARATOR;
			} else {
				$static_template_realpath = $static_config ['template_path'] . DIRECTORY_SEPARATOR;
			}
		}
		if ($filename_is_array) {
			return $static_config;
		}
		
		switch ($filetype) {
			default :
				
				// 【基础功能】返回转换路径
				if (strncmp ( $filename, '@', 1 ) == 0) {
					return dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . substr ( $filename, 1 );
				} else {
					return $filename;
				}
			
			case 'extension' :
				
				// 【基础功能】返回扩展路径
				if (strncmp ( $filename, '@', 1 ) == 0) {
					return dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . substr ( $filename, 1 );
				} elseif (strncmp ( $filename, '/', 1 ) == 0 || strncmp ( $filename, '\\', 1 ) == 0 || strncmp ( $filename, './', 2 ) == 0 || strncmp ( $filename, '.\\', 2 ) == 0 || strpos ( $filename, ':' ) !== false) {
					return $filename;
				} else {
					return $static_extension_realpath . $filename;
				}
			
			case 'template' :
				
				// 【基础功能】返回模板路径
				if (strncmp ( $filename, '@', 1 ) == 0) {
					return dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . substr ( $filename, 1 );
				} elseif (strncmp ( $filename, '\\', 1 ) == 0 || strncmp ( $filename, '/', 1 ) == 0 || strncmp ( $filename, './', 2 ) == 0 || strncmp ( $filename, '.\\', 2 ) == 0 || strpos ( $filename, ':' ) !== false) {
					return $filename;
				} else {
					return $static_template_realpath . $filename;
				}
		
		}
	
	}
	
	/**
	 * 初始化函数
	 *
	 * + 作用：1.设置各类参数，返回参数数组。
	 * + 示例：
	 * <code>
	 *core::init(); //仅返回各类参数数组
	 *core::init(1); //设置并返回各类参数为空值
	 *core::init(-1); //仅返回各类参数的空值
	 *core::init(2); //设置并返回各类参数为默认值
	 *core::init(-2); //仅返回各类参数的默认值
	 *core::init(3); //设置并返回各类参数为配置文件值
	 *core::init(-3); //仅返回各类参数为配置文件值
	 *core::init(4); //设置并返回各类参数为之前数组值
	 *core::init(-4); //仅返回各类参数为之前数组值
	 *core::init(array('template_path'=>'@tpl')); //按数组设置参数
	 *core::init('@config.php'); //读取文件设置参数
	 *core::init('prefix_search'); //取当前表前缀参数
	 * </code>
	 * @param mix $config
	 * @return array
	 */
	static public function init($config = null) {
		
		// 【基础功能】设置各类参数
		do {
			if ($config === null) {
				$config_array = array ();
				break;
			}
			static $static_config1 = array ('autoload_enable' => '', 'autoload_path' => '', 'autoload_extensions' => '', 'autoload_prepend' => '', 'framework_enable' => '', 
					'framework_require' => '', 'framework_module' => '', 'framework_action' => '', 'extension_path' => '', 'template_path' => '', 
					'template_search' => '', 'template_replace' => '', 'template_type' => '', 'template_show' => '', 'connect_provider' => '', 
					'connect_dsn' => '', 'connect_type' => '', 'connect_server' => '', 'connect_username' => '', 'connect_password' => '', 
					'connect_new_link' => '', 'connect_client_flags' => '', 'connect_dbname' => '', 'connect_charset' => '', 'connect_port' => '', 
					'connect_socket' => '', 'connect_driver_options' => '', 'prefix_search' => '', 'prefix_replace' => '' );
			if ($config === 1) {
				$config_array = $static_config1;
				break;
			} elseif ($config === - 1) {
				return $static_config1;
			}
			static $static_config2 = array ('autoload_enable' => self::stub_autoload_enable, 'autoload_path' => self::stub_autoload_path, 
					'autoload_extensions' => self::stub_autoload_extensions, 'autoload_prepend' => self::stub_autoload_prepend, 'framework_enable' => self::main_framework_enable, 
					'framework_require' => self::main_framework_require, 'framework_module' => self::main_framework_module, 
					'framework_action' => self::main_framework_action, 'extension_path' => self::path_extension_path, 
					'template_path' => self::path_template_path, 'template_search' => self::view_template_search, 
					'template_replace' => self::view_template_replace, 'template_type' => self::view_template_type, 
					'template_show' => self::view_template_show, 'connect_provider' => self::db_connect_provider, 'connect_dsn' => self::db_connect_dsn, 
					'connect_type' => self::db_connect_type, 'connect_server' => self::db_connect_server, 'connect_username' => self::db_connect_username, 
					'connect_password' => self::db_connect_password, 'connect_new_link' => self::db_connect_new_link, 
					'connect_client_flags' => self::db_connect_client_flags, 'connect_dbname' => self::db_connect_dbname, 
					'connect_charset' => self::db_connect_charset, 'connect_port' => self::db_connect_port, 'connect_socket' => self::db_connect_socket, 
					'connect_driver_options' => '', 'prefix_search' => self::db_prefix_search, 'prefix_replace' => self::db_prefix_replace );
			if ($config === 2) {
				$config_array = $static_config2;
				break;
			} elseif ($config === - 2) {
				return $static_config2;
			}
			static $static_config3 = null;
			if ($static_config3 === null) {
				$static_config3 = $static_config2;
				if (self::config !== '') {
					$config_file = self::config;
					if (strncmp ( $config_file, '@', 1 ) == 0) {
						$config_file = dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . substr ( $config_file, 1 );
					}
					if (is_file ( $config_file )) {
						$config_result = require $config_file;
						if (is_array ( $config_result )) {
							foreach ( $static_config3 as $key => $value ) {
								if (isset ( $config_result [$key] )) {
									$static_config3 [$key] = $config_result [$key];
								}
							}
						}
					}
				}
			}
			if ($config === 3) {
				$config_array = $static_config3;
				break;
			} elseif ($config === - 3) {
				return $static_config3;
			}
			static $static_config4 = null;
			if ($static_config4 === null) {
				$static_config4 = $static_config3;
			}
			if ($config === 4) {
				$config_array = $static_config4;
				break;
			} elseif ($config === - 4) {
				return $static_config4;
			}
			if (is_array ( $config )) {
				$static_config4 = $static_config3;
				foreach ( $static_config4 as $key => $value ) {
					if (isset ( $config [$key] )) {
						$static_config4 [$key] = $config [$key];
					}
				}
				$config_array = $static_config4;
				break;
			}
			if (is_string ( $config )) {
				if (array_key_exists ( $config , $static_config4)) {
					return $static_config4 [$config];
				}
				$static_config4 = $static_config3;
				$config_file = $config;
				if (strncmp ( $config_file, '@', 1 ) == 0) {
					$config_file = dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . substr ( $config_file, 1 );
				}
				if (is_file ( $config_file )) {
					$config_result = require $config_file;
					if (is_array ( $config_result )) {
						foreach ( $static_config4 as $key => $value ) {
							if (isset ( $config_result [$key] )) {
								$static_config4 [$key] = $config_result [$key];
							}
						}
					}
				}
				$config_array = $static_config4;
				break;
			}
			return;
		} while ( true );
		return array_merge ( self::stub ( $config_array ), self::main ( $config_array ), self::path ( $config_array ), self::view ( $config_array ), self::connect ( $config_array ) );
	
	}
	
	/**
	 * 视图函数（可继承）
	 *
	 * + 作用：1.设置视图参数；1.原生模板和字符串模板；2.其他类型模板。
	 * + 示例：
	 * <code>
	 *self::view (array('template_type'=>'smarty')); //设置视图参数
	 *self::view ( 'sample.tpl' ); //使用sample.tpl模板，不传入变量，使用核心类里定义模板类型和输出方式
	 *self::view ( 'sample.tpl', compact('id', 'name') ); //传入两个变量，使用核心类里定义模板类型和输出方式
	 *$content = self::view ( 'sample.tpl', array('id'=>1, 'name'=>'sample'), 'smarty', false); //用另一种方式传入两个变量，自定义smarty模板类型、返回方式
	 * </code>
	 * @param string $_view_file
	 * @param array $_view_vars
	 * @param string $_view_type
	 * @param bool $_view_show
	 * @return string
	 */
	static public function view($_view_file, $_view_vars = null, $_view_type = null, $_view_show = null) {
		
		// 【基础功能】设置视图参数
		static $_static_config = null;
		if ($_static_config === null) {
			$_static_config = self::init ( - 3 );
			$_static_config = array ('template_search' => $_static_config ['template_search'], 'template_replace' => $_static_config ['template_replace'], 
					'template_type' => $_static_config ['template_type'], 'template_show' => $_static_config ['template_show'] );
		}
		if (is_array ( $_view_file )) {
			foreach ( $_static_config as $key => $value ) {
				isset ( $_view_file [$key] ) and $_static_config [$key] = $_view_file [$key];
			}
			return $_static_config;
		}
		isset ( $_view_type ) and $_static_config ['template_type'] = $_view_type;
		isset ( $_view_show ) and $_static_config ['template_show'] = $_view_show;
		
		// 【基础功能】原生模板和字符串模板
		if ($_static_config ['template_search'] !== '' && $_static_config ['template_search'] !== $_static_config ['template_replace']) {
			$_view_file2 = self::path ( str_replace ( $_static_config ['template_search'], $_static_config ['template_replace'], $_view_file ), 'template' );
		} else {
			$_view_file2 = self::path ( $_view_file, 'template' );
		}
		$_view_vars2 = is_array ( $_view_vars ) ? $_view_vars : array ();
		$_view_type2 = $_static_config ['template_type'] === '' ? 'include' : $_static_config ['template_type'];
		$_view_show2 = $_static_config ['template_show'] === '' ? true : $_static_config ['template_show'];
		switch ($_view_type2) {
			case 'include' :
				extract ( $_view_vars2 );
				if ($_view_show2) {
					return require $_view_file2;
				} else {
					ob_start ();
					require $_view_file2;
					return ob_get_clean ();
				}
			case 'string' :
				extract ( $_view_vars2 );
				if ($_view_show2) {
					return eval ( 'echo <<<_END_OF_EVAL' . PHP_EOL . file_get_contents ( $_view_file2, FILE_USE_INCLUDE_PATH ) . PHP_EOL . '_END_OF_EVAL;' . PHP_EOL );
				} else {
					return eval ( 'return <<<_END_OF_EVAL' . PHP_EOL . file_get_contents ( $_view_file2, FILE_USE_INCLUDE_PATH ) . PHP_EOL . '_END_OF_EVAL;' . PHP_EOL );
				}
			
			// 【扩展功能】其他类型模板
			default :
				extract ( $_view_vars2 );
				static $_static_extension = array ();
				if (! isset ( $_static_extension [$_view_type2] )) {
					$_static_extension [$_view_type2] = self::path ( 'view_' . $_view_type2 . '.php', 'extension' );
					if (! is_file ( $_static_extension [$_view_type2] )) {
						$_static_extension [$_view_type2] = false;
					}
				}
				if ($_static_extension [$_view_type2] === false) {
					return;
				} else {
					return require $_static_extension [$_view_type2];
				}
		
		}
	
	}
	
	/**
	 * 数据库连接
	 *
	 * + 作用：1.设置数据库参数；2.返回连接序号；3.选择指定连接；4.连接数据库；5.断开数据库；6.扩展方式
	 * + 示例：
	 * <code>
	 * //设置数据库参数，使用扩展方式。
	 *$args = array(
	 *	'provider' => 'pdo'
	 *	'dsn' => 'mysql:host=localhost;dbname=test',
	 *	'username' => 'root',
	 *	'password' => '',
	 *	'drive_options' => array( PDO::ATTR_PERSISTENT => true ), //只有该项只能通过动态配置实现	
	 *);
	 *core::connect($args);
	 *
	 * //返回连接序号。
	 *echo core::connect();
	 *echo core::connect(null, $ref);
	 *
	 * //选择使用指定数据库，连接数据库并返回句柄，断开数据库
	 *core::connect(0); //选择默认连接
	 *$dbh0 = core::connect(true); //连接数据库
	 *core::connect(1); //选择一号连接
	 *$dbh1 = core::connect(true); //连接数据库
	 * // TODO 使用连接$dbh0和$dbh1进行数据库底层操作，或者通过切换连接使用模型方法
	 *core::connect(0); //选择默认连接
	 *core::connect(false); //断开默认连接
	 *core::connect(1); //选择一号连接
	 *core::connect(false); //断开一号连接
	 *
	 * //获得连接配置数组。 
	 *core::connect(true, $ref); //第二个传址参数将获得配置数组
	 * </code>
	 * @param mix $args
	 * @param array &$ref
	 * @return $dbh
	 */
	static public function connect($args = null, &$ref = null) {
		
		static $db_pos = 0;
		static $db_arr = array (null );
		static $db_ref = array (null );
		
		// 【基础功能】设置数据库参数
		static $static_config = null;
		if ($static_config === null) {
			$static_config = self::init ( - 3 );
			$static_config = array ('connect_provider' => $static_config ['connect_provider'], 'connect_dsn' => $static_config ['connect_dsn'], 
					'connect_type' => $static_config ['connect_type'], 'connect_server' => $static_config ['connect_server'], 
					'connect_username' => $static_config ['connect_username'], 'connect_password' => $static_config ['connect_password'], 
					'connect_new_link' => $static_config ['connect_new_link'], 'connect_client_flags' => $static_config ['connect_client_flags'], 
					'connect_dbname' => $static_config ['connect_dbname'], 'connect_charset' => $static_config ['connect_charset'], 
					'connect_port' => $static_config ['connect_port'], 'connect_socket' => $static_config ['connect_socket'], 
					'connect_driver_options' => $static_config ['connect_driver_options'], 'prefix_search' => $static_config ['prefix_search'], 
					'prefix_replace' => $static_config ['prefix_replace'] );
		}
		if ($db_ref [$db_pos] === null) {
			$db_ref [$db_pos] = $static_config;
		}
		if (is_array ( $args )) {
			foreach ( $db_ref [$db_pos] as $key => $value ) {
				isset ( $args [$key] ) and $db_ref [$db_pos] [$key] = $args [$key];
			}
			return $db_ref [$db_pos];
		}
		
		// 【基础功能】返回连接序号
		if ($args === null) {
			return $db_pos;
		}
		
		// 【基础功能】选择指定连接
		if (is_int ( $args )) {
			$db_pos = $args;
			isset ( $db_arr [$db_pos] ) or $db_arr [$db_pos] = null;
			isset ( $db_ref [$db_pos] ) or $db_ref [$db_pos] = $static_config;
			$ref = $db_ref [$db_pos];
			return $db_arr [$db_pos];
		}
		
		$dbh = &$db_arr [$db_pos];
		$ref = $db_ref [$db_pos];
		
		// 【基础功能】断开数据库
		if ($args === false) {
			$db_ref [$db_pos] = $static_config;
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
					static $static_extension = array ();
					if (! isset ( $static_extension [$provider] )) {
						$static_extension [$provider] = self::path ( 'db_' . $provider . '.php', 'extension' );
						if (is_file ( $static_extension [$provider] )) {
							require_once $static_extension [$provider];
						} else {
							$static_extension [$provider] = false;
						}
					}
					if ($static_extension [$provider] === false) {
						$return = false;
					} else {
						$return = call_user_func ( array ('db_' . $provider, 'disconnect' ), $dbh, $ref );
					}
					break;
			
			}
			$dbh = null;
			$ref = $static_config;
			return $return;
		}
		
		// 【基础功能】连接数据库
		if ($dbh !== null) {
			return $dbh;
		}
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
				static $static_extension = array ();
				if (! isset ( $static_extension [$provider] )) {
					$static_extension [$provider] = self::path ( 'db_' . $provider . '.php', 'extension' );
					if (is_file ( $static_extension [$provider] )) {
						require_once $static_extension [$provider];
					} else {
						$static_extension [$provider] = false;
					}
				}
				if ($static_extension [$provider] === false) {
					$dbh = null;
				} else {
					$dbh = call_user_func ( array ('db_' . $provider, 'connect' ), $ref );
				}
				break;
		
		}
		return $dbh;
	
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
	static public function execute($sql, $param = null, &$ref = null) {
		
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
					$result = call_user_func_array ( array ('db_' . $provider, 'execute' ), array ($dbh, $args, $sql, $param, &$ref ) );
				} else {
					$result = call_user_func_array ( array ('db_' . $provider, 'execute' ), array ($dbh, $args, $sql, $param ) );
				}
				break;
		
		}
		return $result;
	
	}
	
	/**
	 * 准备SQL语句
	 *
	 * + 作用：1.准备SQL语句，返回SQL和参数的数组；2.扩展方式
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
	 * </code>
	 * @param string $sql
	 * @param array $param
	 * @param bool $bool
	 * @return mix
	 */
	static public function prepare($sql, $param = null, $bool = null) {
		
		// 【基础功能】准备SQL语句
		if (strncmp ( $sql, 'mysql_escape_', 13 ) === 0) {
			if ($bool) {
				$mysql_escape_search = array ("\\", "\x00", "\n", "\r", "'", "\"", "\x1a" );
				$mysql_escape_replace = array ("\\\\", "\\0", "\\n", "\\r", "\\'", "\\\"", "\\Z" );
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
				return $return_sql;
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
				return array ($return_sql, $return_param );
			}
		}
		if (strncmp ( $sql, 'mysql_', 6 ) !== 0) {
			$dbh = self::connect ( true, $args );
			switch ($args ['connect_provider']) {
				case '' :
				case 'mysql' :
					$sql = 'mysql_' . $sql;
					break;
				default :
					
					// 【扩展功能】准备SQL语句
					$provider = $args ['connect_provider'];
					if ($args ['prefix_search'] !== '' && $args ['prefix_search'] !== $args ['prefix_replace']) {
						$sql = str_replace ( $args ['prefix_search'], $args ['prefix_replace'], $sql );
					}
					return call_user_func ( array ('db_' . $provider, 'prepare' ), $dbh, $args, __CLASS__, $sql, $param, $bool );
			
			}
		}
		switch ($sql) {
			case 'mysql_selects' :
				isset ( $param [0] ) or $param [0] = null;
				isset ( $param [1] ) or $param [1] = null;
				isset ( $param [2] ) or $param [2] = null;
				isset ( $param [3] ) or $param [3] = null;
				if ($bool) {
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
					return rtrim ( $return_sql );
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
					return array (rtrim ( $return_sql ), $return_param );
				}
			case 'mysql_inserts' :
			case 'mysql_replaces' :
				isset ( $param [0] ) or $param [0] = null;
				isset ( $param [1] ) or $param [1] = null;
				isset ( $param [2] ) or $param [2] = null;
				isset ( $param [3] ) or $param [3] = null;
				$mysql_sql = strtoupper ( substr ( substr ( $sql, 6 ), 0, - 1 ) );
				if ($bool) {
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
					return rtrim ( $return_sql );
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
					return array (rtrim ( $return_sql ), $return_param );
				}
			case 'mysql_updates' :
				isset ( $param [0] ) or $param [0] = null;
				isset ( $param [1] ) or $param [1] = null;
				isset ( $param [2] ) or $param [2] = null;
				isset ( $param [3] ) or $param [3] = null;
				if ($bool) {
					$table_sql = self::prepare ( 'mysql_table', $param [0], true );
					$set_sql = self::prepare ( 'mysql_set', $param [1], true );
					$where_sql = self::prepare ( 'mysql_where', $param [2], true );
					$other_sql = self::prepare ( 'mysql_other', $param [3], true );
					if ($where_sql === '') {
						$return_sql = "UPDATE $table_sql SET $set_sql $other_sql";
					} else {
						$return_sql = "UPDATE $table_sql SET $set_sql WHERE $where_sql $other_sql";
					}
					return rtrim ( $return_sql );
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
					return array (rtrim ( $return_sql ), $return_param );
				}
			case 'mysql_deletes' :
				isset ( $param [0] ) or $param [0] = null;
				isset ( $param [1] ) or $param [1] = null;
				isset ( $param [2] ) or $param [2] = null;
				isset ( $param [3] ) or $param [3] = null;
				if ($bool) {
					$field_sql = self::prepare ( 'mysql_field', $param [0], true );
					$table_sql = self::prepare ( 'mysql_table', $param [1], true );
					$where_sql = self::prepare ( 'mysql_where', $param [2], true );
					$other_sql = self::prepare ( 'mysql_other', $param [3], true );
					if ($where_sql === '') {
						$return_sql = "DELETE $field_sql FROM $table_sql $other_sql";
					} else {
						$return_sql = "DELETE $field_sql FROM $table_sql WHERE $where_sql $other_sql";
					}
					return rtrim ( $return_sql );
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
					return array (rtrim ( $return_sql ), $return_param );
				}
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
				return $bool ? $return_sql : array ($return_sql, array () );
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
				return $bool ? $return_sql : array ($return_sql, array () );
			case 'mysql_set' :
			case 'mysql_other' :
				$escape_sql = substr_replace ( $sql, 'escape_', 6, 0 );
				if (is_array ( $param )) {
					if ($bool) {
						$return_sql = implode ( ',', self::prepare ( $escape_sql, $param, true ) );
					} else {
						list ( $return_sql, $return_param ) = self::prepare ( $escape_sql, $param );
						$return_sql = implode ( ',', $return_sql );
					}
				} else {
					$return_sql = ( string ) $param;
					$return_param = array ();
				}
				return $bool ? $return_sql : array ($return_sql, $return_param );
			case 'mysql_where' :
			case 'mysql_where2' :
				$escape_sql = substr_replace ( $sql, 'escape_', 6, 0 );
				$escape_sep = $sql === 'mysql_where2' ? ' OR ' : ' AND ';
				if (is_array ( $param )) {
					if ($bool) {
						$return_sql = implode ( $escape_sep, self::prepare ( $escape_sql, $param, true ) );
					} else {
						list ( $return_sql, $return_param ) = self::prepare ( $escape_sql, $param );
						$return_sql = implode ( $escape_sep, $return_sql );
					}
				} else {
					$return_sql = ( string ) $param;
					$return_param = array ();
				}
				if ($sql === 'mysql_where2' && $return_sql !== '') {
					$return_sql = '(' . $return_sql . ')';
				}
				return $bool ? $return_sql : array ($return_sql, $return_param );
			case 'mysql_value' :
				if (is_array ( $param )) {
					if (isset ( $param [0] ) && is_array ( $param [0] )) {
						$return_sql = '';
						$return_param = array ();
						$expect = '';
						foreach ( $param as $key => $value ) {
							if ($bool) {
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
						if ($bool) {
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
				return $bool ? $return_sql : array ($return_sql, $return_param );
			default :
				break;
		}
	
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
	static public function sequence($tablename = 'sequence', $start_index = 1) {
		
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
				$return = call_user_func ( array ('db_' . $provider, 'sequence' ), $dbh, $args, $tablename, $start_index );
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
	static public function structs($array = null, $class = null) {
		
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
	 * + 作用：1.静态选择函数，返回选择数组；2.扩展方式。
	 * + 示例：
	 * <code>
	 * // 查询生成默认对象的数组。 
	 *$result = core::selects('*','pre_test'); //返回array($object, ...)，$object为默认对象
	 *
	 * // 直接使用SQL语句查询生成默认对象的数组。 
	 *$result = core::selects('SELECT * FROM pre_test',array(),true); //返回array($object, ...)，$object为默认对象
	 *
	 * // 传入数组生成指定对象的数组。 
	 *class test extends core { }
	 *$result = test::selects(); //返回array($object, ...)，$object为test对象，PHP 5.3以上支持，PHP 5.3以下需要自己重载方法
	 *
	 * // 使用条件和排序生成默认对象的数组。 
	 *$result = core::selects('id,name','pre_test','id>1','ORDER BY id'); //返回array($object, ...)，$object为对应数组值的默认对象
	 *
	 * // 使用条件和排序生成数组的数组。 
	 *$result = core::selects('id,name','pre_test','id>1','ORDER BY id',array(null,array())); //返回array(array(), ...)
	 *
	 * // 生成指定下标的对象的数组。 
	 *class test extends core { }
	 *$result = core::selects(null,null,null,null,array('id','test')); //返回array(1=>$object, ...)，$object为对应数组值的默认对象
	 *
	 * // 生成指定下标的对象属性值。 
	 *class test extends core { }
	 *$result = test::selects(null,null,null,null,array('id','column'=>'name')); //返回array(1=>'a', ...)，id和name的关联数组
	 *$result = core::selects(null,'pre_test',null,null,array('id','column'=>'name')); //不使和test类的另一种写法
	 *
	 * // 参数可以使用数组，带分页。 
	 *$field = array('id','name');
	 *$table = array('pre_test');
	 *$where = array('id'=>1,'name'=>'a');
	 *$other = array('page'=>&$page);
	 *$result = core::selects($field,$table,$where,$other); //$page为分页信息
	 * </code>
	 * @param mix $field_sql
	 * @param mix $table_param
	 * @param mix $where_bool
	 * @param mix $other
	 * @param mix $class
	 * @return array
	 */
	static public function selects($field_sql = null, $table_param = null, $where_bool = null, $other = null, $class = null) {
		
		// 【基础功能】静态选择数据
		$dbh = self::connect ( true, $args );
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
		// 参数
		if (is_bool ( $where_bool )) {
			$sql = $field_sql;
			$param = is_array ( $table_param ) ? $table_param : array ();
		} else {
			if ($table_param === null) {
				if (is_string ( $classname )) {
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
				if (mysql_num_rows ( $result ) === 0) {
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
						if (class_exists ( $classname )) {
							$obj_classname = $classname;
						} else {
							$obj_classname = $calledclass;
						}
						while ( $obj = mysql_fetch_object ( $result, $obj_classname ) ) {
							$data_arr [] = $obj;
						}
						break;
					case 'class|classtype' :
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
				$ref = array ('page' => &$page, 'class_arr' => $class_arr, 'classkey' => $classkey, 'classname' => $classname, 'calledclass' => $calledclass );
				list ( $data_arr, $data_all ) = call_user_func ( array ('db_' . $provider, 'selects' ), $dbh, $args, __CLASS__, $sql, $param, $ref );
		
		}
		// 整理
		if ($class_arr === array (null ) || $class_arr === array ('' )) {
			return $data_arr;
		}
		$return = array ();
		foreach ( $data_arr as $key => $data ) {
			$point1 = &$return;
			foreach ( $class_arr as $class ) {
				$point2 = array ();
				if ($class === null || $class === '') {
					$point1 [] = &$point2;
				} else {
					if (isset ( $data_all [$key] [$class] )) {
						$point3 = $data_all [$key] [$class];
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
	static public function inserts($table_sql = null, $column_set_param = null, $value_bool = null, $other = null, $class = null) {
		
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
				return call_user_func ( array ('db_' . $provider, 'inserts' ), $dbh, $args, __CLASS__, $sql, $param );
		
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
	static public function updates($table_sql = null, $set_param = null, $where_bool = null, $other = null, $class = null) {
		
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
				return call_user_func ( array ('db_' . $provider, 'updates' ), $dbh, $args, __CLASS__, $sql, $param );
		
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
	static public function deletes($field_sql = null, $table_param = null, $where_bool = null, $other = null, $class = null) {
		
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
				return call_user_func ( array ('db_' . $provider, 'deletes' ), $dbh, $args, __CLASS__, $sql, $param );
		
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
	static public function replaces($table_sql = null, $column_set_param = null, $value_bool = null, $other = null, $class = null) {
		
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
				return call_user_func ( array ('db_' . $provider, 'replaces' ), $dbh, $args, __CLASS__, $sql, $param );
		
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
					$sql = sprintf ( 'SELECT * FROM ' . $tablename . ' WHERE ' . $primary_name . '=\'%s\' LIMIT 1', mysql_real_escape_string ( $primary_value, $dbh ) );
				} else {
					$sql = 'SELECT * FROM ' . $tablename . ' LIMIT 1';
				}
				$result = mysql_query ( $sql, $dbh );
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
				return call_user_func ( array ('db_' . $provider, 'select' ), $dbh, $args, $this, $tablename, $primary_index, $params );
		
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
				$result = call_user_func ( array ('db_' . $provider, 'insert' ), $dbh, $args, $this, $tablename, $primary_index, $params );
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
				$result = call_user_func ( array ('db_' . $provider, 'update' ), $dbh, $args, $this, $tablename, $primary_index, $params );
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
					$sql = sprintf ( 'DELETE FROM ' . $tablename . ' WHERE ' . $primary_name . '=\'%s\' LIMIT 1', mysql_real_escape_string ( $primary_value, $dbh ) );
				} else {
					$sql = 'DELETE FROM ' . $tablename . ' LIMIT 1';
				}
				$result = ( bool ) mysql_query ( $sql, $dbh );
				if ($result && mysql_affected_rows ( $dbh ) == 0) {
					$result = false;
				}
				break;
			
			// 【扩展功能】删除实例数据
			default :
				$provider = $args ['connect_provider'];
				$params = compact ( 'primary_name', 'primary_value', 'fieldname', 'valuename', 'paramvars' );
				$result = call_user_func ( array ('db_' . $provider, 'delete' ), $dbh, $args, $this, $tablename, $primary_index, $params );
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
				$result = call_user_func ( array ('db_' . $provider, 'replace' ), $dbh, $args, $this, $tablename, $primary_index, $params );
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