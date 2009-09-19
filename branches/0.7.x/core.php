<?php
/**
 * CoreMVC核心文件
 * 
 * @author Z <602000@gmail.com>
 * @version 0.7.0
 * @package CoreMVC
 */
/**
 * CoreMVC核心类
 * 
 * + 注意：核心文件可复制多个，但类名要与文件名保持一致。
 */
class core {
	
	/**
	 * 扩展模块路径设置
	 * 
	 * + 作用：可自定义扩展模块路径。
	 * + 定义：该值范围为字符串或默认空值。
	 * <code>
	 *const global_extension_dir = ''; //默认扩展模块路径为当前核心文件所在的当前类名目录
	 *const global_extension_dir = './ext'; //扩展模块路径为当前核心文件所在的"ext"目录，'./'开头的都相对于核心文件路径
	 *const global_extension_dir = 'ext'; //扩展模块路径为当前目录所在的"ext"目录
	 *const global_extension_dir = '/ext'; //扩展模块路径为根目录上的"ext"目录
	 * </code>
	 */
	const global_extension_dir = '';
	
	/**
	 * 延迟静态绑定设置
	 *
	 * + 作用：延迟静态绑定可以在调用继承类的静态模型函数时，类名参数自动使用继承类类名。
	 * <code>
	 *class sample extends core {}
	 *sample::selects(); //类名参数自动使用"sample"，表名也将是前缀+"sample"
	 * </code>
	 * + 定义：该值范围为逻辑值或默认空值。
	 * <code>
	 *const global_static_binding = ''; //默认自动，即PHP 5.3以上使用，否则不使用
	 *const global_static_binding = true; //使用延迟静态绑定，需PHP 5.3以上
	 *const global_static_binding = false; //不用使延迟静态绑定
	 * </code>
	 */
	const global_static_binding = '';
	
	/**
	 * 反向路由功能开关
	 *
	 * + 作用：反向路由功能是否打开
	 * + 定义：该值范围为逻辑值或空串。
	 * <code>
	 *const stub_router_enable = ''; //默认关闭
	 *const stub_router_enable = true; //打开反向路由
	 * </code>
	 */
	const stub_router_enable = '';
	
	/**
	 * 反向路由跳转地址
	 *
	 * + 作用：反向路由跳转地址。
	 * + 定义：该值范围为字符串或空串，模块名用[module]代替。
	 * <code>
	 *const stub_router_url = ''; //默认为"./?go=[module]"
	 *const stub_router_url = 'router.php?go=[module]'; //反向路由跳转地址为"router.php?go="+模块名
	 * </code>
	 */
	const stub_router_url = '';
	
	/**
	 * 视图模板类型
	 *
	 * + 作用：定义默认的视图模板类型。
	 * + 定义：该值范围为字符串或空串，多级模板用小数点连接。
	 * <code>
	 *const view_default_type = ''; //默认是'include'模板，等效于include函数。
	 *const view_default_type = 'string'; //使用字符串模板，等效于""字符串定义。
	 *const view_default_type = 'smarty'; //使用smarty模板扩展库。
	 *const view_default_type = 'include.string'; //使用多级模板扩展库
	 * </code>
	 * + 注意：include和string是内置模板，其他模板会自动在扩展库里寻找“view_模板名.php”的程序，否则使用view_default.php多级模板扩展库。
	 */
	const view_default_type = '';
	
	/**
	 * 视图输出方式
	 *
	 * + 作用：视图默认是否直接显示输出结果。
	 * + 定义：该值范围为逻辑或空串。
	 * <code>
	 *const view_default_show = ''; //默认为true,直接输出结果
	 *const view_default_show = false; //不直接输出，仅返回结果
	 * </code>
	 */
	const view_default_show = '';
	
	/**
	 * 视图所在路径
	 *
	 * + 作用：定义默认的视图相对路径。
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const view_default_dir = ''; //默认为当前路径
	 *const view_default_dir = './tpl'; //当前核心文件所在的"ext"目录，'./'开头的都相对于核心文件路径
	 *const view_default_dir = 'tpl'; //当前目录所在的"ext"目录
	 *const view_default_dir = '/tpl'; //根目录上的"tpl"目录
	 * </code>
	 */
	const view_default_dir = '';
	
	/**
	 * 数据库配置文件
	 *
	 * + 作用：使用数据库配置文件名，会优先使用配置文件返回的相应数组作为参数，数组下标不需要前缀"db_connect_"或"db_"。
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const db_connect_config = ''; //默认不使用数据训配置文件
	 *const db_connect_config = './config.php'; //使用核心文件所在位置的数据库配置文件"config.php"
	 *const db_connect_config = 'config.php'; //使用当前目录的数据库配置文件"config.php"
	 *const db_connect_config = '/config.php'; //使用根目录上的数据库配置文件"config.php"
	 * </code>
	 */
	const db_connect_config = '';
	
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
	 *const db_prefix_replace = ''; //默认不进行表名前缀替换
	 *const db_prefix_replace = 'sample_'; //如果使用表名前缀标识符，则前缀标识符替换成"sample_"
	 * </code>
	 */
	const db_prefix_replace = '';
	
	/**
	 * 序列表类型
	 *
	 * + 作用：序列表的类型，如InnoDB型、MyISAM型等。（对mysql/pdo有效）
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const db_sequence_type = ''; //默认为InnoDB型
	 *const db_sequence_type = 'MyISAM'; //表类型使用"MyISAM"型
	 * </code>
	 */
	const db_sequence_engine = '';
	
	/**
	 * 序列表字段类型
	 *
	 * + 作用：序列表字段类型，如INT型、BIGINT型等。（对mysql/pdo有效）
	 * + 定义：该值范围为字符串或空串。
	 * <code>
	 *const db_sequence_type = ''; //默认为INT型
	 *const db_sequence_type = 'BIGINT'; //字段类型使用"BIGINT"型
	 * </code>
	 */
	const db_sequence_type = '';
	
	/**
	 * 存根函数
	 *
	 * + 作用：1.判断模块是访问(true)还是引用(false)；2.访问模块可以定义使用反向路由功能。
	 * + 示例：
	 * <code>
	 *class sample extends core {} //sample模块继承核心类
	 *sample::stub () and sample::main (); //访问sample模块时执行入口函数，引用sample模块时不执行
	 * </code>
	 * @return bool
	 */
	public static function stub() {
		
		// 【基础功能】判断访问或者引用
		$is_visit = true;
		$file = null;
		foreach ( debug_backtrace ( false ) as $row ) {
			switch($row ['function']){
				case 'include':
				case 'require':
				case 'include_once':
				case 'require_ocne':
					$is_visit = false;
					break 2;
				case 'stub':
					if(is_null($file)){
						$file = $row ['file'];
					}
			}
		}
		
		// 【扩展功能】实现反向路由功能
		if (self::stub_router_enable && $is_visit) {
			if ( self::stub_router_url == '' ){
				$url = './?go=[module]';
			}else{
				$url = self::stub_router_url;
			}
			header ( 'Location: ' . str_replace( '[module]', basename ( $file, '.php' ), $url ) );
			$is_visit = false;
		}
		
		return $is_visit;
	
	}
	
	/**
	 * 入口函数
	 *
	 * + 作用：模拟文件不存在的效果。
	 * + 示例：
	 * <code>
	 *class sample extends core {
	 *	public static function main() {
	 *		// 只能在浏览器模式下访问sample模块
	 *		if( PHP_SAPI == 'cli' ) {
	 *			parent::main ();
	 *			return;
	 *		}
	 *	}
	 *}
	 * </code>
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
	 *
	 * + 作用：1.使用原生视图模块输出；2.使用扩展模板或多级模板方式输出
	 * + 示例：
	 * <code>
	 *parent::view ( 'sample.tpl' ); //使用sample.tpl模板，不传入变量，使用核心类里定义模板类型和输出方式
	 *
	 *$id = 1;
	 *$name = 'sample';
	 *parent::view ( 'sample.tpl', compact('id', 'name') ); //传入两个变量，使用核心类里定义模板类型和输出方式
	 *
	 *$content = parent::view ( 'sample.tpl', array('id'=>1, 'name'=>'sample'), 'smarty', false, './' ); //用另一种方式传入两个变量，自定义smarty模板类型、返回方式及模板路径
	 * </code>
	 * @param string $_view_file
	 * @param array $_view_vars
	 * @param string $_view_type
	 * @param bool $_view_show
	 * @param string $_view_dir
	 * @return string
	 */
	public static function view($_view_file, $_view_vars = null, $_view_type = null, $_view_show = null, $_view_dir = null) {
		
		// 【基础功能】视图模板
		if ( !is_array ( $_view_vars ) ){
		    $_view_vars = array();
		}
		if ( is_null ( $_view_type ) ){
		    $_view_type = self::view_default_type;
		}
		if ( !is_string ( $_view_type ) || $_view_type == '' ){
		    $_view_type = 'include';
		}
		if ( is_null ( $_view_show ) ){
		    $_view_show = self::view_default_show;
		}
		if ( !is_bool ( $_view_show ) ){
		    $_view_show = true;
		}
		if ( is_null ( $_view_dir ) ){
		    $_view_dir = self::view_default_dir;
		}
		if ( !is_string ( $_view_dir ) ){
		    $_view_dir = '';
		}
		if (strpos($_view_file, ':') !== false){
			$_view_dir2 = getcwd();
			$_view_file2 = $_view_file;
		} elseif (strpos($_view_file, '/') == 0){
			$_view_dir2 = getcwd();
			$_view_file2 = $_view_file;
		} elseif ($_view_dir == ''){
			$_view_dir2 = getcwd();
			$_view_file2 = $_view_file;
		} elseif (strtok($_view_dir, '\/') == '.'){
			$_view_dir2 = dirname(__FILE__);
			$_view_file2 = dirname(__FILE__) . DIRECTORY_SEPARATOR . $_view_file;
		} else {
			$_view_dir2 = $_view_dir;
			$_view_file2 = $_view_dir . DIRECTORY_SEPARATOR . $_view_file;
		}
		switch ($_view_type) {
			case 'include' :
				extract ( $_view_vars );
				ob_start ();
				require ($_view_file2);
				$_view_echo = ob_get_clean ();
				break;
			case 'string' :
				extract ( $_view_vars );
				$_view_echo = eval ( 'return <<<_END_OF_EVAL' . PHP_EOL . file_get_contents ( $_view_file2, FILE_USE_INCLUDE_PATH ) . PHP_EOL . '_END_OF_EVAL;' . PHP_EOL );
				break;

			// 【扩展功能】扩展模板
			default:
				if (self::global_extension_dir == ''){
					$_view_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR;
				} elseif (strtok(self::global_extension_dir, '\/') == '.'){
					$_view_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . self::global_extension_dir . DIRECTORY_SEPARATOR;
				} else {
					$_view_path = self::global_extension_dir . DIRECTORY_SEPARATOR;
				}
				if(file_exists($_view_path . 'view_'.$_view_type.'.php')){
					$_view_echo = require( $_view_path . 'view_'.$_view_type.'.php' );
				}elseif(file_exists($_view_path . 'view_default.php')){
					$_view_echo = require( $_view_path . 'view_default.php' );
				}else{
					$_view_echo = false;
				}

		}
		if ($_view_show) {
			echo $_view_echo;
		}
		return $_view_echo;
		
	}
	
	/**
	 * 数据库连接函数
	 *
	 * + 作用：
	 * 1 多数据库连接的选择连接方式。
	 * 2 返回已连接句柄。
	 * 3 传入连接配置参数。
	 * 4 断开连接。
	 * 5 返回连接参数。
	 * + 参数：
	 * 1 $args 为整数时，选择某个连接（0为默认连接），同时返回总连接数。
	 * 2 $args = true 时，已经连接的直接返回连接；尚未连接的根据各常量 db_connect_XXX 连接数据库，成功的返回连接，失败的返回false。
	 * 3 $args = false 时，断开连接，成功的返回true，失败的返回false。
	 * 4 $args 为字符串时，$args 替换常量 db_connect_config ，其他与 $args = true 情况相同。
	 * 5 $args 为数组时，$args 按键值替换各常量 db_connect_XXX ，其他与 $args = true 情况相同。
	 * 6 $args 为其他类型时，返回false。
	 * 7 $ref 传入时，会将当前连接配置数组赋值给$ref，断开连接时赋值null。
	 * + 注意：
	 * 1 各常量 db_connect_XXX 在core.php里定义，默认连接时会使用这些值。
	 * 2 该方法会被其他模型方法自动调用以获得数据库连接。
	 * + 示例：
	 * <code>
	 * // 使用数据库连接操作的情况。 
	 *$dbh = core::connect(); //连接数据库
	 * // TODO 使用连接$dbh进行数据库底层操作
	 *core::connect(false); //断开数据库
	 *
	 * // 使用多数据库连接操作的情况。 
	 *core::connect(0); //选择默认连接
	 *$dbh0 = core::connect(); //连接数据库
	 *core::connect(1); //选择一号连接
	 *$dbh1 = core::connect(); //连接数据库
	 * // TODO 使用连接$dbh0和$dbh1进行数据库底层操作，或者通过切换连接使用模型方法
	 *core::connect(0); //选择默认连接
	 *core::connect(false); //断开默认连接
	 *core::connect(1); //选择一号连接
	 *core::connect(false); //断开一号连接
	 *
	 * // 使用动态配置连接数据库。 
	 *$args = array(
	 *	'provider' => 'pdo'
	 *	'dsn' => 'mysql:host=localhost;dbname=test',
	 *	'username' => 'root',
	 *	'password' => '',
	 *	'drive_options' => array( PDO::ATTR_PERSISTENT => true ), //只有该项只能通过动态配置实现	
	 *);
	 *core::connect($args); //动态配置连接数据库
	 * // TODO 使用模型方法进行操作
	 *
	 * // 使用配置文件连接数据库。 
	 *core::connect(require('./config.php')); //配置文件连接数据库（显式相对路径以当前为基准）
	 *core::connect('./config.php'); //配置文件连接数据库（显式相对路径以core为基准）
	 *core::connect(array('config' => './config.php')); //配置文件连接数据库（显式相对路径以core为基准）
	 *
	 * // 获得连接配置数组。 
	 *core::connect(true, $ref); //第二个传址参数将获得配置数组
	 * </code>
	 * @param mix $args
	 * @param array &$ref
	 * @return $dbh
	 */
	public static function connect($args = true, &$ref = null) {
		
	    static $db_pos = 0;
	    static $db_arr = array(null);
	    static $db_ref = array(null);
	    
	    // 【基础功能】选择指定连接
	    if (is_int( $args )){
	        $db_pos = $args;
	        isset( $db_arr [$db_pos] ) or $db_arr[$db_pos] = null;
	        isset( $db_ref [$db_pos] ) or $db_ref[$db_pos] = null;
	        $ref = $db_ref[$db_pos];
	        return count($db_arr);
	    }

	    $dbh = &$db_arr[$db_pos];
	    $ref = $db_ref[$db_pos];
	    
		// 【基础功能】返回连接句柄
		if ($args !== false) {
			if (!is_null ( $dbh )) {
				return $dbh;
			}
		}
		
		// 【基础功能】连接数据库
		if ($args !== false) {
		    // 参数处理
			if ($args === true) {
				$args = array ();
			} elseif (is_string ( $args )) {
				$args = array ('config' => $args );
			} elseif (is_array ( $args )) {
			} else {
			    return false;
			}
			isset ( $args ['config'] ) or $args ['config'] = self::db_connect_config;
			isset ( $args ['provider'] ) or $args ['provider'] = self::db_connect_provider;
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
			isset ( $args ['driver_options']) or $args ['driver_options'] = array();
			isset ( $args ['prefix_search']) or $args ['prefix_search'] = self::db_prefix_search;
			isset ( $args ['prefix_replace']) or $args ['prefix_replace'] = self::db_prefix_replace;
			isset ( $args ['sequence_engine']) or $args ['sequence_engine'] = self::db_sequence_engine;
			isset ( $args ['sequence_type']) or $args ['sequence_type'] = self::db_sequence_type;
			if($args ['config'] != '') {
			    if(preg_match ( '/^\\.[\\\\|\\/]/i', $args ['config'] )){
			        $args ['config'] = dirname(__FILE__) . DIRECTORY_SEPARATOR . $args ['config']; 
			    }
			    $args = array_merge($args, require($args ['config']));
			}
			$ref = $db_ref[$db_pos] = $args;
		    // 连接处理
		    switch ($args ['provider']) {
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
					$dbh = new PDO ( $args ['dsn'], $args ['username'], $args ['password'], $args ['driver_options']);
					return $dbh;
			    // 【扩展功能】adodb连接
				case 'adodb' :
				    if (self::global_extension_dir == ''){
				        $extension_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . get_class();
				    } else {
    			        if(preg_match ( '/^\\.[\\\\|\\/]/i', self::global_extension_dir )){
    			            $extension_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . self::global_extension_dir;
    			        }else{
    			            $extension_dir = self::global_extension_dir;
    			        }
				    }
				    require_once( $extension_dir . DIRECTORY_SEPARATOR . 'AdodbZip.php');
					$dbh = ADONewConnection( $args ['dsn'] );
					if ($args ['server'] != '') {
						if ($args ['client_flags'] != '' && preg_match ( '/msyql/i', $args ['dsn'] )) {
							$dbh->clientFlags = $args ['client_flags'];
						}
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
			    if(get_resource_type( $dbh ) == 'mysql link'){
					$return = mysql_close ( $dbh );
					$dbh = null;
					$ref = $db_ref[$db_pos] = null;
					return $return;			        
			    }
			} elseif(is_object( $dbh )) {
			    if(get_class($dbh) == 'PDO'){
					$dbh = null;
					$ref = $db_ref[$db_pos] = null;
					return true;			        
				}
				if(preg_match ( '/^ADODB/i', get_class($dbh))){
    				$dbh->Close ();
    				$dbh = null;
					$ref = $db_ref[$db_pos] = null;
    				return true;				
				}
			}
			return false;
		}
	
	}
	
	/**
	 * 数据库执行函数
	 *
	 * + 作用：
	 * 1 执行SQL语句可传入参数。
	 * 2 可获得执行信息，如影响记录数等。
	 * + 参数：
	 * 1 $sql 为字符串时，表示要执行的SQL语句。
	 * 2 $param 为数组时，会作为SQL语句中的参数。
	 * 3 $ref 传入时，会将当前执行信息数组赋值给$ref，键值有'insert_id'、'affected_rows'、'num_fields'、'num_rows'。
	 * 4 使用pdo连接数据库时，$ref可以是一个数组值作为PDO::query()（当$param为null时）或PDO::prepare()（当$param为数组时）的附加参数。
	 * 5 返回结果集或逻辑值，视SQL语句而定。
	 * + 注意：
	 * 1 该方法会被core::selects等方法调用（mysql连接方式下）。
	 * 2 参数 $sql 使用表前缀替换规则。
	 * + 示例：
	 * <code>
	 * // 执行SQL语句 
	 *core::execute('SET NAMES GBK');
	 *
	 * // 执行带参数的SQL语句 
	 *$result = core::execute('SELECT * FROM test WHERE id=?', array(1));
	 * // TODO 对结果集$result进行操作
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
	 * // TODO 对结果集$result进行操作
	 * </code>
	 * @param string $sql
	 * @param array $param
	 * @param array $ref
	 * @return $result
	 */
	public static function execute($sql, $param = null, &$ref = null) {
		
		// 【基础功能】执行语句
		$ref_flag = (func_num_args()>2);		
	    $dbh = self::connect (true, $args);
		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
		    $sql = str_replace($args['prefix_search'], $args['prefix_replace'], $sql);
		}
		switch ($args['provider']) {
			default :
			case 'mysql' :
				if (is_array ( $param )) {
					$rand = rand ();
					$using = '';
					mysql_query ( 'PREPARE stmt' . $rand . ' FROM \'' . mysql_real_escape_string ( $sql, $dbh ) . '\'', $dbh );
					foreach ( $param as $key => $value ) {
						if (is_null ( $value )) {
							mysql_query ( 'SET @param' . $rand . $key . '=NULL', $dbh );
						} elseif (is_bool ( $value )) {
							mysql_query ( 'SET @param' . $rand . $key . '=' . (string)$value, $dbh );
						} elseif (is_int ( $value ) || is_float ( $value )) {
							mysql_query ( 'SET @param' . $rand . $key . '=' . $value, $dbh );
						} else {
							mysql_query ( 'SET @param' . $rand . $key . '=\'' . mysql_real_escape_string ( $value, $dbh ) . '\'', $dbh );
						}
						if ($using == '') {
							$using = ' USING @param' . $rand . $key;
						} else {
							$using .= ',@param' . $rand . $key;
						}
					}
					$result = mysql_query ( 'EXECUTE stmt' . $rand . $using, $dbh );
				} else {
					$result = mysql_query ( $sql, $dbh );
				}
				if($ref_flag){
    			    $ref = array();
    				$ref ['insert_id'] = (string)mysql_insert_id ( $dbh );
    				$ref ['affected_rows'] = max(mysql_affected_rows ( $dbh ),0);
    				$ref ['num_fields'] = is_resource($result)?mysql_num_fields ( $result ):0;
    				$ref ['num_rows'] = is_resource($result)?mysql_num_rows ( $result ):0;
				}
				if (is_array ( $param )) {
				    foreach ( $param as $key => $value ) {
						mysql_query ( 'SET @param' . $rand . $key . '=NULL', $dbh );
					}
					mysql_query ( 'DEALLOCATE PREPARE stmt' . $rand, $dbh );
				}
				return $result;
			case 'pdo' :
				if (is_array ( $param )) {
					if (is_array ( $ref )) {
					    unset($ref['insert_id']);
					    unset($ref['affected_rows']);
					    unset($ref['num_fields']);
					    unset($ref['num_rows']);
   						$sth = $dbh->prepare ( $sql, $ref );
					    $sth->execute ( $param );
					} else {
						$sth = $dbh->prepare ( $sql );
						$sth->execute ( $param );
					}
				} else {
					if (is_array ( $ref )) {
					    unset($ref['insert_id']);
					    unset($ref['affected_rows']);
					    unset($ref['num_fields']);
					    unset($ref['num_rows']);
					    $ref = array_values($ref);
					    array_unshift ( $ref, $sql );
						$sth = call_user_func_array ( array ($dbh, 'query' ), $ref );
					} else {
						$sth = $dbh->query ( $sql );
					}
				}
				if($ref_flag){
    		        $ref = array();
    				$ref ['insert_id'] = $dbh->lastInsertId();
    				$ref ['affected_rows'] = $sth->rowCount();
    				$ref ['num_fields'] = $sth->columnCount();
    				$ref ['num_rows'] = $sth->rowCount();
				}
				return $sth;
			case 'adodb' :
				if (is_array( $param )) {
					$rs = $dbh->Execute ( $sql, $param );
				} else {
					$rs = $dbh->Execute ( $sql );
				}
				if($ref_flag){
    				$ref = array();
    				$ref ['insert_id'] = $dbh->Insert_ID();
    				$ref ['affected_rows'] = (int)$dbh->Affected_Rows();
    				$ref ['num_fields'] = is_object($rs)?$rs->FieldCount():0;
    				$ref ['num_rows'] = is_object($rs)?$rs->RecordCount():0;
				}
				return $rs;
		}
	
	}
	
	/**
	 * SQL语句生成函数
	 *
	 * + 作用：
	 * 1 按约定数组生成SQL语句。
	 * 2 可获得实际参数。
	 * + 参数：
	 * 1 $syntax 为字符串时，表示要SQL语法格式字符串
	 * 2 $param 为数组时，会作为SQL语句中的参数。 
	 * 3 $ref 传入时，会将SQL语句实际的参数数组赋值给$ref。
	 * 4 当参数 $ref 不传入时，生成的SQL语句将不带任何参数。
	 * 5 返回SQL语句。 
	 * + 注意：
	 * 1 该方法会被多个静态模型方法调用。
	 * + $syntax格式规则：
	 * 1 规则1：格式字符串只用于分段和取关键字，而不管内容。
	 * 2 规则2：顶头的一行为一段开始，并且为该段的关键字。
	 * 3 规则3：缩进的多行只要无空行，紧接着上面为同一段。
	 * 4 规则4：空行表示分段，无论下面是顶头的还是缩进的。
	 * 5 规则5：关键字以“[”开头的，如果参数无内容将不出现。
	 * 6 规则6：关键字以“{”开头的，将按“|”分隔取第一个为关键字。
	 * 7 示例1：
	 *<pre>
	 *SELECT	<span style="color:red">此处为第 1 段开始，关键字“SELECT”</span>
	 *	[ALL | DISTINCT | DISTINCTROW ]
	 *	[HIGH_PRIORITY]
	 *	[STRAIGHT_JOIN]
	 *	[SQL_SMALL_RESULT] [SQL_BIG_RESULT] [SQL_BUFFER_RESULT]
	 *	[SQL_CACHE | SQL_NO_CACHE] [SQL_CALC_FOUND_ROWS]
	 *	select_expr [, select_expr ...]
	 *[FROM	<span style="color:red">此处为第 2 段开始，参数有内容关键字“FROM”，参数无内容无关键字</span>
	 *	table_references
	 *[WHERE	<span style="color:red">此处为第 3 段开始，参数有内容关键字“WHERE”，参数无内容无关键字</span>
	 *	where_condition]
	 *
	 *	[GROUP BY {col_name | expr | position}	<span style="color:red">此处为第 4 段开始，无关键字</span>
	 *	[ASC | DESC], ... [WITH ROLLUP]]
	 *	[HAVING where_condition]
	 *	[ORDER BY {col_name | expr | position}
	 *	[ASC | DESC], ...]
	 *	[LIMIT {[offset,] row_count | row_count OFFSET offset}]
	 *	[PROCEDURE procedure_name(argument_list)]
	 *	[INTO OUTFILE 'file_name' export_options
	 *	| INTO DUMPFILE 'file_name'
	 *	| INTO var_name [, var_name]]
	 *	[FOR UPDATE | LOCK IN SHARE MODE]]
	 *</pre>	 	
	 * 8 示例2：
	 *<pre>
	 *INSERT	<span style="color:red">此处为第 1 段开始，关键字“INSERT”</span>
	 *	[LOW_PRIORITY | DELAYED | HIGH_PRIORITY] [IGNORE]
	 *	[INTO] tbl_name
	 *	
	 *	[(col_name,...)]	<span style="color:red">此处为第 2 段开始，无关键字</span>
	 *{VALUES | VALUE}	<span style="color:red">此处为第 3 段开始，关键字“VALUES”</span>
	 *	({expr | DEFAULT},...),(...),...
	 *	
	 *	[ ON DUPLICATE KEY UPDATE	<span style="color:red">此处为第 4 段开始，无关键字</span>
	 *	col_name=expr
	 *	[, col_name=expr] ... ]
	 *</pre>  	 	
	 * + $param数组规则：
	 * 1 $param数组为关联数组，数组下标指定相应的该值的处理方法。
	 * 2 规则2：$param数组顺序为关联数组遍历时的前后顺序。
	 * 3 规则3：相同的处理方法，下标可用“处理标识_数字”区分。
	 * 4 参数处理标识规则对照表：
	 *<pre>
	 *  	 	<table border="1">
	 *  	 	<tr><th>处理名称</th><th>处理标识</th><th>规则说明</th></tr>
	 *  	 	<tr><td>［字段］</td><td>field</td><td><ul style="margin-top:5px;margin-bottom:5px">
	 *  	 		<li>字符串直接使用</li>
	 *  	 		<li>数组用半角逗号“,”连接（边界有逗号、空格、制表符、回车的直接连接）</li>
	 *  	 	</ul></td></tr>
	 *  	 	<tr>
	 *  	 	<td>［列名］</td>
	 *  	 	<td>column</td><td><ul style="margin-top:5px;margin-bottom:5px">
	 *  	 		<li>字符串直接使用</li>
	 *  	 		<li>数组用半角逗号“,”连接（边界有逗号、空格、制表符、回车的直接连接）</li>
	 *  	 		<li>非空串时前后加上半角括号“(”、“)”</li>
	 *  	 	</ul></td></tr>
	 *  	 	<tr><td>［表名］</td><td>table</td><td><ul style="margin-top:5px;margin-bottom:5px">
	 *  	 		<li>字符串直接使用</li>
	 *  	 		<li>数组用半角逗号“,”连接（边界有逗号、空格、制表符、回车的直接连接）</li>
	 *  	 	</ul></td></tr>
	 *  	 	<tr><td>［条件］</td><td>where</td><td><ul style="margin-top:5px;margin-bottom:5px">
	 *  	 		<li>字符串直接使用</li>
	 *  	 		<li>数组用字符串“ AND ”连接，以下情况需要先做处理</li><ul>
	 *  	 			<li>键为文本，值为数组，键有“?”，则使用“键”，同时值存为参数</li>
	 *  	 			<li>键为文本，值为数组，键无“?”，则使用“键 IN (?,?,...)”，同时值存为参数</li>
	 *  	 			<li>键为文本，值为null，键无“?”，则使用“键 IS NULL”，同时值存为参数</li>
	 *  	 			<li>键为文本，键有“?”，则使用“键”，同时值存为参数</li>
	 *  	 			<li>键为文本，键无“?”，则使用“键=?”，同时值存为参数</li>
	 *  	 			<li>键为整数，值为数组，则用“) OR (”连接，前后加上“((”、“))”</li>
	 *
	 *  	 	</ul></ul></td></tr>
	 *  	 	<tr><td>［其他］</td><td>other</td><td><ul style="margin-top:5px;margin-bottom:5px">
	 *  	 		<li>字符串直接使用</li>
	 *  	 		<li>数组用半角空格“ ”连接，以下情况需要先做处理</li><ul>
	 *  	 			<li>键为整数，值为数组，则用半角逗号“,”连接</li>
	 *  	 			<li>键为文本，值为数组，键有“?”，则使用“键”，同时值存为参数</li>
	 *  	 			<li>键为文本，值为数组，键无“?”，则使用“键 ?,?,...”，同时值存为参数</li>
	 *  	 			<li>键为文本，键有“?”，则使用“键”，同时值存为参数</li>
	 *  	 			<li>键为文本，键无“?”，则使用“键 ?”，同时值存为参数</li>
	 *  	 	</ul></ul></td></tr>
	 *  	 	<tr><td>［数据］</td><td>value</td><td><ul style="margin-top:5px;margin-bottom:5px">
	 *  	 		<li>字符串直接使用</li>
	 *  	 		<li>数组用半角逗号“,”连接，值无数组时前后加上半角括号“(”、“)”，以下情况需要先做处理</li><ul>
	 *  	 			<li>值为数组，则用半角逗号“,”连接，前后加上半角括号“(”、“)”，以下情况需要先做处理</li><ul>
	 *					<li>键为文本，则使用“?”，同时值存为参数</li></ul>
	 *  	 			<li>键为文本，则使用“?”，同时值存为参数</li>
	 *  	 	</ul></ul></td></tr>
	 *  	 	<tr><td>［赋值］</td><td>set</td><td><ul style="margin-top:5px;margin-bottom:5px">
	 *  	 		<li>字符串直接使用</li>
	 *  	 		<li>数组用半角逗号“,”连接，以下情况需要先做处理</li><ul>
	 *  	 			<li>键为文本，键有“?”，则使用“键”，同时值存为参数</li>
	 *  	 			<li>键为文本，键无“?”，则使用“键=?”，同时值存为参数</li>
	 *  	 	</ul></ul></td></tr>
	 *  	 	</table>
	 *</pre>
	 * + $sql合成规则：
	 * 1 规则1：$param关联数组按顺序将$syntax格式分段替换成处理后的值。
	 * 2 规则2：$syntax格式分段的关键字保留
	 * 3 模型方法语法参数对照表：
	 *<pre>
	 *  	 	<table border="1">
	 *  	 	<tr><th>模型方法</th><th>关键字1</th><th>参数1</th><th>关键字 2</th><th>参数2</th><th>关键字 3</th><th>参数3</th><th>关键字 4</th><th>参数4</th></tr>
	 *  	 	<col align="center"></col><col align="center"></col><col align="center" style="color:blue"></col><col align="center"></col><col align="center" style="color:blue"></col><col align="center"></col><col align="center" style="color:blue"></col><col align="center"></col><col align="center" style="color:blue"></col>
	 *  	 	<tr><th>core::selects</th><td>SELECT</td><td>［字段］</td><td>FROM</td><td>［表名］</td><td>WHERE</td><td>［条件］</td><td>&nbsp;</td><td>［其他］</td></tr>
	 *  	 	<tr><th rowspan="3">core::inserts</th><td>INSERT</td><td>［表名］</td><td>&nbsp;</td>
	 *  	 	<td>［列名］</td><td>VALUES</td><td>［数据］</td><td>&nbsp;</td><td>［其他］</td></tr>
	 *  	 	<tr><td>INSERT</td><td>［表名］</td><td>SET</td><td>［赋值］</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>［其他］</td></tr>
	 *  	 	<tr><td>INSERT</td><td>［表名］</td><td>&nbsp;</td><td>［列名］</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>［其他］</td></tr>
	 *  	 	<tr><th rowspan="2">core::updates</th><td>UPDATE</td><td>［表名］</td><td>SET</td><td>［赋值］</td><td>WHERE</td><td>［条件］</td><td>&nbsp;</td><td>［其他］</td></tr>
	 *  	 	<tr><td>UPDATE</td><td>［表名］</td><td>SET</td><td>［赋值］</td><td>WHERE</td><td>［条件］</td><td>&nbsp;</td><td>&nbsp;</td></tr>
	 *  	 	<tr><th rowspan="2">core::deletes</th><td>DELETE</td><td>［字段］</td><td>FROM</td><td>［表名］</td><td>WHERE</td><td>［条件］</td><td>&nbsp;</td><td>［其他］</td></tr>
	 *  	 	<tr><td>DELETE</td><td>［字段］</td><td>FROM</td><td>［表名］</td><td>WHERE</td><td>［条件］</td><td>&nbsp;</td><td>&nbsp;</td></tr>
	 *  	 	<tr><th rowspan="3">core::replaces</th><td>REPLACE</td><td>［表名］</td><td>&nbsp;</td><td>［列名］</td><td>VALUES</td><td>［数据］</td><td>&nbsp;</td><td>&nbsp;</td></tr>
	 *  	 	<tr><td>REPLACE</td><td>［表名］</td><td>SET</td><td>［赋值］</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
	 *  	 	<tr><td>REPLACE</td><td>［表名］</td><td>&nbsp;</td><td>［列名］</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>［其他］</td></tr>
	 *  	 	</table>
	 *</pre>
	 * @param string $syntax
	 * @param array $param
	 * @param array $ref
	 * @return string
	 */
	public static function prepare($syntax , $param = null , &$ref = null) {
		
		// 【基础功能】生成语句和参数
		$ref_flag = (func_num_args()>2);		
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
			}
			$syntax_index ++;
		}
		if(!is_array($param)){
		    $param = array();
		}
		$ref = array ();
		$param_index = 0;
		foreach ( $param as $param_key => $param_value ) {
			$param_case = strtok ( $param_key, ' _0123456789' );
			switch ($param_case) {
				case 'field' :
				case 'table' :
				case 'column' :
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
					if ($param_case == 'column' && strlen ( $param_value ) > 0) {
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
			if (is_null($param_value) || $param_value === '') {
			    if (isset($syntax_param [$param_index]) && $syntax_clear [$syntax_param [$param_index]] !== false) {
			        $syntax_array [$syntax_clear [$syntax_param [$param_index]]] = '';
				}
			} else {
			    if (isset($syntax_param [$param_index])) {
			    	$syntax_array [$syntax_param [$param_index]] = chr ( 9 ) . $param_value;
			    }else{
			    	$syntax_array [] = chr ( 9 ) . $param_value;
			    }
			}
			$param_index ++;
		}
		return implode ( PHP_EOL, $syntax_array );
	
	}
	
	/**
	 * 自增序列函数
	 *
	 * + 作用：
	 * 1 获得指定的自增序列。
	 * + 参数：
	 * 1 $tablename 传入时，使用给定的表名，不存在时会自动创建。
	 * 2 $start_index 传入时，自增序列会给出不小于$start_index开始的值。
	 * 3 返回自增序列号。
	 * + 注意：
	 * 1 参数 $tablename 使用表前缀替换规则。
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
		
		// 【基础功能】生成序列号
		$dbh = self::connect (true, $args);
	    // 参数
		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
		    $tablename = str_replace($args['prefix_search'], $args['prefix_replace'], $tablename);
		}

		// 执行
		switch ($args['provider']) {
			default :
			case 'mysql' :
				if ($args['sequence_engine'] == '') {
					$table_type = 'InnoDB';
				} else {
					$table_type = $args['sequence_engine'];
				}
				if ($args['sequence_type'] == '') {
					$field_type = 'INT';
				} else {
					$field_type = $args['sequence_type'];
				}
				$sql = 'CREATE TABLE IF NOT EXISTS ' . $tablename . PHP_EOL;
				$sql .= '(id ' . $field_type . ') ENGINE=' . $table_type;
				mysql_query ( $sql, $dbh );
				mysql_query ( 'LOCK TABLES ' . $tablename . ' WRITE,' . $tablename . ' AS r READ', $dbh );
				$rs = mysql_query ( 'SELECT id FROM ' . $tablename . ' AS r', $dbh );
				if (mysql_num_rows ( $rs ) > 0) {
					$return = mysql_result ( $rs, 0 ) + 1;
					if($start_index>$return){
					    $return = $start_index;
					}
					mysql_query ( 'UPDATE ' . $tablename . ' SET id=' . $return, $dbh );
				} else {
					$return = $start_index;
					mysql_query ( 'INSERT INTO ' . $tablename . ' VALUES (' . $return . ')', $dbh );
				}
				mysql_query ( 'UNLOCK TABLES' );
				return $return;
			case 'pdo' :
				if ($args['sequence_engine'] == '') {
					$table_type = 'InnoDB';
				} else {
					$table_type = $args['sequence_engine'];
				}
				if ($args['sequence_type'] == '') {
					$field_type = 'INT';
				} else {
					$field_type = $args['sequence_type'];
				}
				$sql = 'CREATE TABLE IF NOT EXISTS ' . $tablename . PHP_EOL;
				$sql .= '(id ' . $field_type . ') ENGINE=' . $table_type;
				$dbh->exec ( $sql );
				$dbh->exec ( 'LOCK TABLES ' . $tablename . ' WRITE,' . $tablename . ' AS r READ' );
				$sth = $dbh->query ( 'SELECT id FROM ' . $tablename . ' AS r' );
				if ($sth->rowCount() > 0) {
					$return = $sth->fetchColumn () + 1;
					if($start_index>$return){
					    $return = $start_index;
					}
					$dbh->query ( 'UPDATE ' . $tablename . ' SET id=' . $return );
				} else {
					$return = $start_index;
					$dbh->exec ( 'INSERT INTO ' . $tablename . ' VALUES (' . $return . ')' );
				}
				$dbh->exec ( 'UNLOCK TABLES' );
				return $return;
			case 'adodb' :
				$return = $dbh->GenID ( $tablename, $start_index );
				if($start_index>$return){
				    $dbh->Execute('UPDATE '.$tablename.' SET id='.$start_index.' LIMIT 1');
				    $return = $start_index;
				}
				return $return;
		}
	}

	/**
	 * 载入数组函数
	 *
	 * + 作用：
	 * 1 从二维数组得到实例数组函数。
	 * + 参数：
	 * 1 $array 为数组时，数组中的数组或对象将按 $class 定义返回相应值。
	 * 2 $array 为对象时，转成对象的数组，然后处理同上。
	 * 3 $array 为正整数时，自动生成参数个数的数组，然后处理同上。
	 * 4 $array 为空值或其他时，自动生成空数组，然后处理同上。
	 * 5 $class 为数组时，最后一个值之前的值表示返回数组的下标层次，空串表示按顺序，非空串表示属性值为下标，最后一个值有下标时返回属性值 ，无下标空串时返回数组，无下标非空串时返回对象，无下标空值时根据global_static_binding常量返回。
	 * 6 $class 为字符串时，自动生成 array('',$class) 形式的数组，然后处理同上。
	 * 7 当参数 $class 为空值时，自动生成 array('',null) 形式的数组，然后处理同上。
	 * + 注意：
	 * 1 参数 $class 规则同 core::selects 。
	 * + 示例：
	 * <code>
	 * // 生成默认对象的数组。 
	 *$result = core::structs(); //返回array($object)，$object为默认对象
	 *
	 * // 生成多个默认对象的数组。 
	 *$result = core::structs(10); //返回array($object, ...)，$object为默认对象传入数组
	 *
	 * // 生成默认对象的数组。 
	 *$array = array(
	 *	array('id'=>1,name=>'a'),
	 *	array('id'=>2,name=>'b'),
	 *);
	 *$result = core::structs($array); //返回array($object, ...)，$object为对应数组值的默认对象
	 *
	 * // 传入对象数组生成默认对象的数组。 
	 *$class1 = new class1(1, 'a');
	 *$class2 = new class2(1, 'a');    
	 *$array = array($class1, $class2);
	 *$result = core::structs($array); //返回array($object, ...)，$object为对应数组值的默认对象
	 *
	 * // 传入数组生成指定对象的数组。 
	 *$array = array(
	 *	array('id'=>1,name=>'a'),
	 *	array('id'=>2,name=>'b'),
	 *);
	 *
	 *$result = core::structs($array, 'class1'); //返回array($object, ...)，$object为对应数组值的指定对象
	 *
	 * // 传入数组生成指定下标的对象的数组。 
	 *$array = array(
	 *	array('id'=>1,name=>'a'),
	 *	array('id'=>2,name=>'b'),
	 *);
	 *$result = core::structs($array, array('name','class1')); //返回array('a'=>$object, 'b'=>$object)，$object为对应数组值的指定对象
	 *
	 * // 传入对象生成指定的数组的数组。 
	 *$class1 = new class1(1, 'a');
	 *$class2 = new class2(1, 'a');    
	 *$array = array($class1, $class2);
	 *$result = core::structs($array, array('','')); //返回array($array, $array)，$array为对应对象的数组
	 *
	 * // 传入数组生成指对象属性值的数组。 
	 *$array = array(
	 *	array('id'=>1,name=>'a'),
	 *	array('id'=>2,name=>'b'),
	 *);
	 *$result = core::structs($array, array('','name'=>'')); //返回array('a', 'b')，$object对应数组值的为指定对象
	 * </code>
	 * @param mix $array
	 * @param mix $class
	 * @return array
	 */
	public static function structs($array = null, $class = null) {
		
		// 【基础功能】对象载入数组
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
		if (is_null($classname)){
			if(self::global_static_binding === true){
			    if(function_exists('get_called_class')) {
	    	        $classname = get_called_class();
			    }else{
			        return false;
			    }
			}elseif(self::global_static_binding === false){
    			$classname = __CLASS__;
			}else{
			    if(function_exists('get_called_class')) {
	    	        $classname = get_called_class();
			    }else{
			        $classname = __CLASS__;
			    }
			}
		}
		// 执行
		if (is_array ( $array )) {
		    $data_arr = $array;
		}elseif(is_object($array)){
			$data_arr = array($array);
		}elseif(is_int($array) && $array>0){
		    $data_arr = array_fill(0,$array,null);
		}elseif(is_null($array)){
		    $data_arr = array(null);
		}else{
			$data_arr = array();
		}
		// 整理
		$return = array ();
		foreach ( $data_arr as $data ) {
			$point1 = &$return;
			foreach ( $class_arr as $class ) {
				$point2 = array ();
				if ($class == '') {
					$point1 [] = &$point2;
				} else {
				    if (is_object($data)){
				        if(property_exists($data,$class)){
    				        $point3 = $data->$class;			            
				        }else{
    				        $point3 = '';			            
				        }
				    } elseif (is_array($data)){
				        if(isset($data[$class])){
				            $point3 = $data[$class];
				        }else{
				            $point3 = '';
				        }
				    }else{
				        $point3 = '';
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
				if ($classname == '') {
				    if (is_object($data)){
				        $point1 = get_object_vars($data);
				    } elseif (is_array($data)){
				        $point1 = $data;
				    } else {
				        $point1 = array();
				    }
				} else {
				    $obj = new $classname ( );
				    if (is_object($data)){
    					foreach ( $data as $key => $value ) {
    						$obj->$key = $value;
    					}
				    } elseif (is_array($data)){
    					foreach ( $data as $key => $value ) {
    					    if(is_string($key) && $key!=''){
        						$obj->$key = $value;
    					    }
    					}
				    }
				    $point1 = $obj;
				}
			} else {
			    if (is_object($data)){
			        if(isset($data->$classkey)){
			            $point1 = $data->$classkey;
			        }else{
			            $point1 = null;
			        }
			    } elseif (is_array($data)){
			        if(isset($data[$classkey])){
			            $point1 = $data[$classkey];
			        }else{
			            $point1 = null;
			        }
			    } else {
			        $point1 = null;
			    }
		    }
		}
		return $return;
	
	}
	
	/**
	 * 数据库选择函数
	 *
	 * + 作用：
	 * 1 查询数据并得到生成ORM。
	 * 2 支持分页，分页遵从CPP.PSTC规范。
	 * + 参数：
	 * 1 $field 为传入时，参见 core::prepare 的 $param 参数规则 field 段，默认为'*'。
	 * 2 $table 为传入时，参见 core::prepare 的 $param 参数规则 table 段，默认使用 $class 参数数组最后一个值经前缀处理后的值，或该值为空值，则根据global_static_binding常量取得继承类名经前缀处理后的值。
	 * 3 $where 为传入时，参见 core::prepare 的 $param 参数规则 where 段。
	 * 4 $other 为传入时，参见 core::prepare 的 $param 参数规则 other 段，可使用下标为'page'，值为传址的方式回取到分页信息。
	 * 5 $class 为数组时，最后一个值之前的值表示返回数组的下标层次，空串表示按顺序，非空串表示属性值为下标，最后一个值有下标时返回属性值 ，无下标空串时返回数组，无下标非空串时返回对象，无下标空值时根据global_static_binding常量返回。 
	 * 6 $class 为字符串时，自动生成 array('',$class) 形式的数组，然后处理同上。
	 * 7 $class 为空值时，自动生成 array('',null) 形式的数组，然后处理同上。
	 * + 注意：
	 * 1 参数 $class 规则同 core::structs 。
	 * + 示例：
	 * <code>
	 * // 查询生成默认对象的数组。 
	 *$result = core::selects('*','pre_test'); //返回array($object, ...)，$object为默认对象
	 *
	 * // 传入数组生成指定对象的数组。 
	 *class test extends core { }
	 *$result = test::selects(); //返回array($object, ...)，$object为test对象，PHP 5.3以上支持，PHP 5.3以下需要自己重载方法
	 *
	 * // 使用条件和排序生成默认对象的数组。 
	 *$result = core::structs('id,name','pre_test','id>1','ORDER BY id'); //返回array($object, ...)，$object为对应数组值的默认对象
	 *
	 * // 使用条件和排序生成数组的数组。 
	 *$result = core::structs('id,name','pre_test','id>1','ORDER BY id',''); //返回array(array(), ...)
	 *
	 * // 生成指定下标的对象的数组。 
	 *class test extends core { }
	 *$result = core::structs(null,null,null,null,array('id','test')); //返回array(1=>$object, ...)，$object为对应数组值的默认对象
	 *
	 * // 生成指定下标的对象属性值。 
	 *class test extends core { }
	 *$result = core::structs(null,null,null,null,array('id','name'=>'test')); //返回array(1=>'a', ...)，id和name的关联数组
	 *$result = core::structs(null,'pre_test',null,null,array('id','name'=>'')); //不使和test类的另一种写法
	 *
	 * // 参数可以使用数组，带分页。 
	 *$field = array('id','name');
	 *$table = array('pre_test');
	 *$where = array('id'=>1,'name'=>'a');
	 *$other = array('page'=>&$page);
	 *$result = core::structs($field,$table,$where,$other); //$page为分页信息
	 * </code>
	 * @param mix $field
	 * @param mix $table
	 * @param mix $where
	 * @param mix $other
	 * @param mix $class
	 * @return $data
	 */
	public static function selects($field = null, $table = null, $where = null, $other = null, $class = null) {
		
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
		if (is_null($classname)){
			if(self::global_static_binding === true){
			    if(function_exists('get_called_class')) {
	    	        $classname = get_called_class();
			    }else{
			        return false;
			    }
			}elseif(self::global_static_binding === false){
    			$classname = __CLASS__;
			}else{
			    if(function_exists('get_called_class')) {
	    	        $classname = get_called_class();
			    }else{
			        $classname = __CLASS__;
			    }
			}
		}
		$dbh = self::connect (true, $args);
	    // 参数
		if (is_null($table)) {
		    if($classname == ''){
		        return false;
		    }
			$table = $classname;
			if ($table == __CLASS__) {
				return false;
			}
			if($args['prefix_search']!=''){
			    $table = $args['prefix_search'] . $table;
			}
		}
		if (is_null($field)) {
			$field = '*';
		}
		if (is_array($other) && array_key_exists('page',$other)) {
		    $page_f = true;
			$field2 = 'COUNT(*)';
			$other1 = array ();
			$other2 = array ();
			foreach ( $other as $other_key => $other_value ) {
				if ($other_key === 'page') {
					$page = &$other ['page'];
					if(!is_array($page)){
					    $page = array();
					}
					if(isset($page ['size'])){
					    $page ['size'] = ( int ) $page ['size'];
    					if ($page ['size'] < 1) {
    						$page ['size'] = 1;
    					}
					}else{
						$page ['size'] = 1;
					}
					if(isset($page ['page'])){
					    $page ['page'] = ( int ) $page ['page'];
    					if ($page ['page'] < 1) {
    						$page ['page'] = 1;
    					}
					}else{
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
		} else {
			$page_f = false;
			$param = array ('field' => $field, 'table' => $table, 'where' => $where, 'other' => $other );
		}
		// 执行
		switch ($args['provider']) {
			default :
			case 'mysql' :
				if ($page_f) {
					$sql = self::prepare ( $syntax, $param2, $ref );
					$result = self::execute ( $sql, $ref );
					$page ['count'] = (int)mysql_result ( $result, 0 );
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
            		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
            		    $sql = str_replace($args['prefix_search'], $args['prefix_replace'], $sql);
            		}
					$sth = $dbh->prepare ( $sql, array (PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY ) );
    				foreach($ref as $ref_key=>$ref_value){
    				    if(is_int($ref_key)){
    				        $ref_key++;
    				    }
    				    if(is_null($ref_value)){
            				$ref_param = PDO::PARAM_NULL;
    				    }elseif(is_bool($ref_value)){
            				$ref_param = PDO::PARAM_BOOL;
    				    }elseif(is_int($ref_value) || is_float($ref_value)){
            				$ref_param = PDO::PARAM_INT;
    				    }else{
            				$ref_param = PDO::PARAM_STR;
    				    }
           				$sth->bindValue($ref_key, $ref_value, $ref_param);
    				}
					$sth->execute ();
					$page ['count'] = (int)$sth->fetchColumn ( 0 );
					$page ['total'] = ceil ( $page ['count'] / $page ['size'] );
					$sth->closeCursor ();
				}
				$sql = self::prepare ( $syntax, $param, $ref );
        		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
        		    $sql = str_replace($args['prefix_search'], $args['prefix_replace'], $sql);
        		}
				$sth = $dbh->prepare ( $sql, array (PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY ) );
				foreach($ref as $ref_key=>$ref_value){
				    if(is_int($ref_key)){
				        $ref_key++;
				    }
				    if(is_null($ref_value)){
        				$ref_param = PDO::PARAM_NULL;
				    }elseif(is_bool($ref_value)){
        				$ref_param = PDO::PARAM_BOOL;
				    }elseif(is_int($ref_value) || is_float($ref_value)){
        				$ref_param = PDO::PARAM_INT;
				    }else{
        				$ref_param = PDO::PARAM_STR;
				    }
       				$sth->bindValue($ref_key, $ref_value, $ref_param);
				}
				$result = $sth->execute ();
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
            		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
            		    $sql = str_replace($args['prefix_search'], $args['prefix_replace'], $sql);
            		}
					$page ['count'] = (int)$dbh->GetOne ( $sql, $ref );
					$page ['total'] = (int)ceil ( $page ['count'] / $page ['size'] );
				}
				$sql = self::prepare ( $syntax, $param, $ref );
        		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
        		    $sql = str_replace($args['prefix_search'], $args['prefix_replace'], $sql);
        		}
				if(isset($GLOBALS ['ADODB_FETCH_MODE'])){
				    $ADODB_FETCH_MODE = $GLOBALS ['ADODB_FETCH_MODE'];
				}
				$GLOBALS ['ADODB_FETCH_MODE'] = ADODB_FETCH_ASSOC;
				$arr = $dbh->GetAll ( $sql, $ref );
				if(isset($ADODB_FETCH_MODE)){
				    $GLOBALS ['ADODB_FETCH_MODE'] = $ADODB_FETCH_MODE;
				}else{
				    unset($GLOBALS ['ADODB_FETCH_MODE']);
				}
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
	 *
	 * + 作用：
	 * 1 数据插入函数。
	 * + 参数：
	 * 1 $table 为传入时，参见 core::prepare 的 $param 参数规则 table 段，默认使用 $class 参数处理。 
	 * 2 $column_set 为传入时，参见 core::prepare 的 $param 参数规则 column 段，和 set 段，当$value为空值并且$column_set为关联数组时和使用set规则，其他情况使用column规则。
	 * 3 $value 为传入时，参见 core::prepare 的 $param 参数规则 value 段，当$column_set为空值并且$value为二维关联数组时，$column_set会使用第一个关联数组的下标
	 * 4 $other 为传入时，参见 core::prepare 的 $param 参数规则 other 段。 
	 * 5 $class 为字符串时，$table 为空串时，$table 将被替换成 $class 前缀处理后的值。
	 * 6 class 为空值时，$table 为空串时，$table 将被替换成继承类名前缀处理后的值 。
	 * + 注意：
	 * 1 参数 $class 规则 core::inserts，core::updates，core::deletes，core::replaces 相同。
	 * + 示例：
	 * <code>
	 * // 插入一条记录。 
	 *$int = core::inserts('pre_test',array('id'=>1,'name'=>'a')); //返回成功插入的个数
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
	 *$int = core::inserts('pre_test',null,$arr); //返回成功插入的个数
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
	 *$int = core::inserts(null,array('id'=>1,'name'=>'a')); //使用前缀+类名作为表名（PHP 5.3以上有效），返回成功插入的个数
	 * </code>
	 * @param mix $table
	 * @param mix $column_set
	 * @param mix $value
	 * @param mix $other
	 * @param string $class
	 * @return int
	 */
	public static function inserts($table = null, $column_set = null, $value = null, $other = null, $class = null) {
		
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
		$dbh = self::connect (true, $args);
	    // 参数
		if (is_null($class)){
			if(self::global_static_binding === true){
			    if(function_exists('get_called_class')) {
	    	        $class = get_called_class();
			    }else{
			        return false;
			    }
			}elseif(self::global_static_binding === false){
    			$class = __CLASS__;
			}else{
			    if(function_exists('get_called_class')) {
	    	        $class = get_called_class();
			    }else{
			        $class = __CLASS__;
			    }
			}
		}
		if (is_null($table)) {
		    if($class == ''){
		        return false;
		    }
			$table = $class;
			if ($table == __CLASS__) {
				return false;
			}
			if($args['prefix_search']!=''){
			    $table = $args['prefix_search'] . $table;
			}
		}
		if (is_null($value)) {
			$break = false;
			if(is_array($column_set)){
    			foreach ( $column_set as $column_set_key => $column_set_value ) {
    				if (is_int ( $column_set_key )) {
    					$break = true;
    					break;
    				}
    			}
			}
			if ($break) {
				$syntax = $syntax3;
				$param = array ('table' => $table, 'column' => $column_set, '', 'other' => $other );
			} else {
				$syntax = $syntax2;
				$param = array ('table' => $table, 'set' => $column_set, '', 'other' => $other );
			}
		} else {
		    if(is_null($column_set)){
    			if(is_array($value)){
        			foreach ( $value as $value_key => $value_value ) {
        			    if(is_array($value_value) && count($value_value)>0){
                			$break = true;            			    
        			        foreach($value_value as $value_value_key=>$value_value_value){
                				if (is_int ( $value_value_key )) {
                					$break = false;
                					break;
                				}
        			        }
        			        if($break){
        			            $column_set = array_keys($value_value);
        			        }
        			    }
        			    break;
        			}
    			}
		    }
			$syntax = $syntax1;
			$param = array ('table' => $table, 'column' => $column_set, 'value' => $value, 'other' => $other );
		}
		// 执行
		switch ($args['provider']) {
			default :
			case 'mysql' :
				$sql = self::prepare ( $syntax, $param, $ref );
				$result = self::execute ( $sql, $ref, $ref2 );
				return $ref2['affected_rows'];
			case 'pdo' :
				$sql = self::prepare ( $syntax, $param, $ref );
        		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
        		    $sql = str_replace($args['prefix_search'], $args['prefix_replace'], $sql);
        		}
				$sth = $dbh->prepare ( $sql );
				foreach($ref as $ref_key=>$ref_value){
				    if(is_int($ref_key)){
				        $ref_key++;
				    }
				    if(is_null($ref_value)){
        				$ref_param = PDO::PARAM_NULL;
				    }elseif(is_bool($ref_value)){
        				$ref_param = PDO::PARAM_BOOL;
				    }elseif(is_int($ref_value) || is_float($ref_value)){
        				$ref_param = PDO::PARAM_INT;
				    }else{
        				$ref_param = PDO::PARAM_STR;
				    }
       				$sth->bindValue($ref_key, $ref_value, $ref_param);
				}
				$sth->execute ();
				return $sth->rowCount ();
			case 'adodb' :
				$sql = self::prepare ( $syntax, $param, $ref );
        		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
        		    $sql = str_replace($args['prefix_search'], $args['prefix_replace'], $sql);
        		}
				$rs = $dbh->Execute ( $sql, $ref );
				return (int)$dbh->Affected_Rows();
		}
	
	}
	
	/**
	 * 数据库修改函数
	 *
	 * + 作用：
	 * 1 数据修改函数。
	 * + 参数：
	 * 1 $table 为传入时，参见 core::prepare 的 $param 参数规则 table 段，默认使用 $class 参数处理。 
	 * 2 $set 为传入时，参见 core::prepare 的 $param 参数规则 set 段。
	 * 3 $where 为传入时，参见 core::prepare 的 $param 参数规则 where 段。
	 * 4 $other 为传入时，参见 core::prepare 的 $param 参数规则 other 段。 
	 * 5 $class 为字符串时，$table 为空串时，$table 将被替换成 $class 前缀处理后的值。
	 * 6 class 为空值时，$table 为空串时，$table 将被替换成继承类名前缀处理后的值 。
	 * + 注意：
	 * 1 参数 $class 规则 core::inserts，core::updates，core::deletes，core::replaces 相同。
	 * + 示例：
	 * <code>
	 * // 修改记录。 
	 *$int = core::updates('pre_test',array('name'=>'a'),array('id'=>1)); //返回成功修改的个数
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
	 * @param mix $table
	 * @param mix $set
	 * @param mix $where
	 * @param mix $other
	 * @param string $class
	 * @return int
	 */
	public static function updates($table = null, $set = null, $where = null, $other = null, $class = null) {
		
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
		
		// 【基础功能】修改数据
		$dbh = self::connect (true, $args);
	    // 参数
		if (is_null($class)){
			if(self::global_static_binding === true){
			    if(function_exists('get_called_class')) {
	    	        $class = get_called_class();
			    }else{
			        return false;
			    }
			}elseif(self::global_static_binding === false){
    			$class = __CLASS__;
			}else{
			    if(function_exists('get_called_class')) {
	    	        $class = get_called_class();
			    }else{
			        $class = __CLASS__;
			    }
			}
		}
		if (is_null($table)) {
		    if($class == ''){
		        return false;
		    }
			$table = $class;
			if ($table == __CLASS__) {
				return false;
			}
			if($args['prefix_search']!=''){
			    $table = $args['prefix_search'] . $table;
			}
		}
		if (is_null($other)) {
			$syntax = $syntax2;
			$param = array ('table' => $table, 'set' => $set, 'where' => $where, '' );
		} else {
			$syntax = $syntax1;
			$param = array ('table' => $table, 'set' => $set, 'where' => $where, 'other' => $other );
		}
		// 执行
		switch ($args['provider']) {
			default :
			case 'mysql' :
				$sql = self::prepare ( $syntax, $param, $ref );
				$result = self::execute ( $sql, $ref, $ref2 );
				return $ref2['affected_rows'];
			case 'pdo' :
				$sql = self::prepare ( $syntax, $param, $ref );
        		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
        		    $sql = str_replace($args['prefix_search'], $args['prefix_replace'], $sql);
        		}
				$sth = $dbh->prepare ( $sql );
				$sth->execute ( $ref );
				return $sth->rowCount ();
			case 'adodb' :
				$sql = self::prepare ( $syntax, $param, $ref );
        		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
        		    $sql = str_replace($args['prefix_search'], $args['prefix_replace'], $sql);
        		}
				$rs = $dbh->Execute ( $sql, $ref );
				return (int)$dbh->Affected_Rows();
		}
	
	}
	
	/**
	 * 数据库删除函数
	 *
	 * + 作用：
	 * 1 数据删除函数。
	 * + 参数：
	 * 1 $field 为传入时，参见 core::prepare 的 $param 参数规则 field 段。
	 * 2 $table 为传入时，参见 core::prepare 的 $param 参数规则 table 段，默认使用 $class 参数处理。 
	 * 3 $where 为传入时，参见 core::prepare 的 $param 参数规则 where 段。
	 * 4 $other 为传入时，参见 core::prepare 的 $param 参数规则 other 段。 
	 * 5 $class 为字符串时，$table 为空串时，$table 将被替换成 $class 前缀处理后的值。
	 * 6 class 为空值时，$table 为空串时，$table 将被替换成继承类名前缀处理后的值 。
	 * + 注意：
	 * 1 参数 $class 规则 core::inserts，core::updates，core::deletes，core::replaces 相同。
	 * + 示例：
	 * <code>
	 * // 修改记录。 
	 *$int = core::deletes(null,'pre_test',array('id'=>1)); //返回成功删除的个数
	 *
	 * // 删除排序的一条记录。 
	 *$int = core::deletes(null,'pre_test',array('name'=>'a'),array('ORDER BY id','LIMIT 1')); //返回成功删除的个数
	 *
	 * // 使用类名修改记录。 
	 *$int = test::deletes(null,null,array('id'=>1),'test'); //使用前缀+类名作为表名，返回成功删除的个数
	 *
	 * // 使用类名插入记录。 
	 *class test extends core { }
	 *$int = test::deletes(null,null,array('id'=>1)); //使用前缀+类名作为表名（PHP 5.3以上有效），返回成功删除的个数
	 * </code>
	 * @param mix $field
	 * @param mix $table
	 * @param mix $where
	 * @param mix $other
	 * @param string $class
	 * @return int
	 */
	public static function deletes($field = null, $table = null, $where = null, $other = null, $class = null) {
		
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
		
		// 【基础功能】删除数据
		$dbh = self::connect (true, $args);
	    // 参数
		if (is_null($class)){
			if(self::global_static_binding === true){
			    if(function_exists('get_called_class')) {
	    	        $class = get_called_class();
			    }else{
			        return false;
			    }
			}elseif(self::global_static_binding === false){
    			$class = __CLASS__;
			}else{
			    if(function_exists('get_called_class')) {
	    	        $class = get_called_class();
			    }else{
			        $class = __CLASS__;
			    }
			}
		}
		if (is_null($table)) {
		    if($class == ''){
		        return false;
		    }
			$table = $class;
			if ($table == __CLASS__) {
				return false;
			}
			if($args['prefix_search']!=''){
			    $table = $args['prefix_search'] . $table;
			}
		}
		if (is_null($other)) {
			$syntax = $syntax2;
			$param = array ('field' => $field, 'table' => $table, 'where' => $where, '' );
		} else {
			$syntax = $syntax1;
			$param = array ('field' => $field, 'table' => $table, 'where' => $where, 'other' => $other );
		}
		// 执行
		switch ($args['provider']) {
			default :
			case 'mysql' :
				$sql = self::prepare ( $syntax, $param, $ref );
				$result = self::execute ( $sql, $ref, $ref2 );
				return $ref2['affected_rows'];
			case 'pdo' :
				$sql = self::prepare ( $syntax, $param, $ref );
        		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
        		    $sql = str_replace($args['prefix_search'], $args['prefix_replace'], $sql);
        		}
				$sth = $dbh->prepare ( $sql );
				$sth->execute ( $ref );
				return $sth->rowCount ();
			case 'adodb' :
				$sql = self::prepare ( $syntax, $param, $ref );
        		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
        		    $sql = str_replace($args['prefix_search'], $args['prefix_replace'], $sql);
        		}
				$rs = $dbh->Execute ( $sql, $ref );
				return (int)$dbh->Affected_Rows();
		}
	
	}
	
	/**
	 * 数据库更新函数
	 *
	 * + 作用：
	 * 1 数据更新函数。
	 * + 参数：
	 * 1 $table 为传入时，参见 core::prepare 的 $param 参数规则 table 段，默认使用 $class 参数处理。 
	 * 2 $column_set 为传入时，参见 core::prepare 的 $param 参数规则 column 段，和 set 段，当$value为空值并且$column_set为关联数组时和使用set规则，其他情况使用column规则。
	 * 3 $value 为传入时，参见 core::prepare 的 $param 参数规则 value 段，当$column_set为空值并且$value为二维关联数组时，$column_set会使用第一个关联数组的下标
	 * 4 $other 为传入时，参见 core::prepare 的 $param 参数规则 other 段。 
	 * 5 $class 为字符串时，$table 为空串时，$table 将被替换成 $class 前缀处理后的值。
	 * 6 class 为空值时，$table 为空串时，$table 将被替换成继承类名前缀处理后的值 。
	 * + 注意：
	 * 1 参数 $class 规则 core::inserts，core::updates，core::deletes，core::replaces 相同。
	 * + 示例：
	 * <code>
	 * // 更新一条记录。 
	 *$int = core::replaces('pre_test',array('id'=>1,'name'=>'a')); //返回成功更新的个数(修改的按两倍算)
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
	 *$int = core::replaces('pre_test',null,$arr); //返回成功更新的个数(修改的按两倍算)
	 *
	 * // 更新需计算的记录。 
	 *$arr = array(
	 *	array(1,"md5('a')"),
	 *	array(2,"md5('b')"),
	 *);
	 *$int = core::inserts('pre_test',array('id','name'),$arr); //返回成功更新的个数(修改的按两倍算)
	 *
	 * // 使用类名更新记录。 
	 *$int = core::replaces(null,array('id'=>1,'name'=>'a'),null,null,'test'); //使用前缀+类名作为表名，返回成功更新的个数(修改的按两倍算)
	 *
	 * // 使用类名更新记录。 
	 *class test extends core { }
	 *$int = core::replaces(null,array('id'=>1,'name'=>'a')); //使用前缀+类名作为表名（PHP 5.3以上有效），返回成功更新的个数(修改的按两倍算)
	 * </code>
	 * @param mix $table
	 * @param mix $column_set
	 * @param mix $value
	 * @param mix $other
	 * @param string $class
	 * @return int
	 */
	public static function replaces($table = null, $column_set = null, $value = null, $other = null, $class = null) {
		
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
		$dbh = self::connect (true, $args);
	    // 参数
		if (is_null($class)){
			if(self::global_static_binding === true){
			    if(function_exists('get_called_class')) {
	    	        $class = get_called_class();
			    }else{
			        return false;
			    }
			}elseif(self::global_static_binding === false){
    			$class = __CLASS__;
			}else{
			    if(function_exists('get_called_class')) {
	    	        $class = get_called_class();
			    }else{
			        $class = __CLASS__;
			    }
			}
		}
		if (is_null($table)) {
		    if($class == ''){
		        return false;
		    }
			$table = $class;
			if ($table == __CLASS__) {
				return false;
			}
			if($args['prefix_search']!=''){
			    $table = $args['prefix_search'] . $table;
			}
		}
		if (is_null($value)) {
			$break = false;
			if(is_array($column_set)){
    			foreach ( $column_set as $column_set_key => $column_set_value ) {
    				if (is_int ( $column_set_key )) {
    					$break = true;
    					break;
    				}
    			}
			}
			if ($break) {
				$syntax = $syntax3;
				$param = array ('table' => $table, 'column' => $column_set, '', 'other' => $other );
			} else {
				$syntax = $syntax2;
				$param = array ('table' => $table, 'set' => $column_set, '', '' );
			}
		} else {
		    if(is_null($column_set)){
    			if(is_array($value)){
        			foreach ( $value as $value_key => $value_value ) {
        			    if(is_array($value_value) && count($value_value)>0){
                			$break = true;            			    
        			        foreach($value_value as $value_value_key=>$value_value_value){
                				if (is_int ( $value_value_key )) {
                					$break = false;
                					break;
                				}
        			        }
        			        if($break){
        			            $column_set = array_keys($value_value);
        			        }
        			    }
        			    break;
        			}
    			}
		    }
			$syntax = $syntax1;
			$param = array ('table' => $table, 'column' => $column_set, 'value' => $value, '' );
		}
		// 执行
		switch ($args['provider']) {
			default :
			case 'mysql' :
				$sql = self::prepare ( $syntax, $param, $ref );
				$result = self::execute ( $sql, $ref, $ref2 );
				return $ref2['affected_rows'];
			case 'pdo' :
				$sql = self::prepare ( $syntax, $param, $ref );
        		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
        		    $sql = str_replace($args['prefix_search'], $args['prefix_replace'], $sql);
        		}
				$sth = $dbh->prepare ( $sql );
				$sth->execute ( $ref );
				return $sth->rowCount ();
			case 'adodb' :
				$sql = self::prepare ( $syntax, $param, $ref );
        		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
        		    $sql = str_replace($args['prefix_search'], $args['prefix_replace'], $sql);
        		}
				$rs = $dbh->Execute ( $sql, $ref );
				return (int)$dbh->Affected_Rows();
		}
	
	}
	
	/**
	 * 变量载入函数
	 *
	 * + 作用：
	 * 1 实例载入数据函数。
	 * + 参数：
	 * 1 $row 为对象时，载入，并返回实例。
	 * 2 $row 为数组时，载入，并返回实例。
	 * 3 $row 为整数时，返回顺序属性值。
	 * 4 $row 为字符串时，返回属性值。
	 * 5 $row 为其他类型值时，返回数组。
	 * + 示例：
	 * <code>
	 * // 实例载入数组。 
	 *$obj = new core;
	 *$obj->struct($row); //$row为数组
	 *
	 * // 实例返回数据 
	 *$id = $obj->struct(1);
	 *
	 * // 实例返回数据 
	 *$id = $obj->struct('id');
	 *
	 * // 实例返回数组 
	 *$row = $obj->struct();
	 * </code>
	 * @param mix $row
	 * @return mix
	 */
	public function struct($row = null) {
		
		// 【基础功能】实例载入数组
		if (is_object ( $row )) {
		    $row = get_object_vars($row);
		}
		if (is_array ( $row )) {
		    $arr = array();
			foreach ( $row as $key => $value ) {
			    if(is_int($key)){
			        $arr[$key] = $value;
			    }else{
				    $this->$key = $value;
			    }
			}
			if(count($arr)>0){
    			$vars = get_object_vars($this);
    			$i=0;
    			foreach($vars as $key=>$value){
    			    if(isset($arr[$i])){
    			        $this->$key = $arr[$i];
    			    }
    			    $i++;
    			}
			}
			return $this;
		}
		
		// 【基础功能】返回数据或数组
		if (is_int($row)){
			$vars = get_object_vars($this);
    			$i=0;
    			foreach($vars as $key=>$value){
    			    $i++;
    			    if($i==$row){
    			        return $value;
    			    }
    			}
				return null;
		}
		if (is_string($row)){
		    if(isset($this->$row)){
		        return $this->$row;
		    }
		    return null;
		}
		return get_object_vars ( $this );
	
	}
	
	/**
	 * 数据库选择函数
	 *
	 * + 作用：
	 * 1 实例查询函数。
	 * + 参数：
	 * 1 $tablename 为默认空串时，使用继承类对应的表名，非继承类返回false。
	 * 2 $primary_index 为0时，不使用主键直接从数据库里取一条记录。
	 * 3 $primary_index 为正整数时，使用第 $primary_index 顺序的属性作为主键从数据库里取一条记录。
	 * + 注意：
	 * 1 参数 $tablename 使用表前缀替换规则。
	 * + 示例：
	 * <code>
	 * // 基类指定表查询 
	 *$obj = new core;
	 *$obj->id = 2;
	 *$obj->select('account') or die('not found'); //载入acount表id=2的记录到实例
	 *
	 * // 查询一条记录，无主键 
	 *$obj = new core;
	 *$obj->select('account', 0) or die('not found'); //载入acount表的一条记录到实例
	 *
	 * // 查询一条记录，指定主键 
	 *$obj = new core;
	 *$obj->rid = 1;
	 *$obj->id = 2;
	 *$obj->select('account', 2) or die('not found'); //载入acount表id=2的记录到实例
	 *
	 * // 继承类查询 
	 *class account extends core{} //继承core类
	 *$account = new account;
	 *$account->id = 2;
	 *$account->select() or die('not found'); //载入acount表id=2的记录到实例
	 * </code>
	 * @param string $tablename
	 * @param int $primary_index
	 * @return bool
	 */
	public function select($tablename = '', $primary_index = 1) {
		
		// 【基础功能】查询数据
		$dbh = self::connect (true, $args);
	    // 参数
		if ($tablename == '') {
			$tablename = get_class ( $this );
			if ($tablename == __CLASS__) {
				return false;
			}
			if($args['prefix_search']!=''){
			    $tablename = $args['prefix_search'] . $tablename;
			}
		}
		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
		    $tablename = str_replace($args['prefix_search'], $args['prefix_replace'], $tablename);
		}
		$fieldvars = get_object_vars ( $this );
		if (count ( $fieldvars ) < $primary_index) {
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
		switch ($args['provider']) {
			default :
			case 'mysql' :
				if ($primary_index > 0) {
					$sql = sprintf ( 'SELECT * FROM ' . $tablename . ' WHERE ' . $primary_name . '=\'%s\' LIMIT 1', mysql_real_escape_string ( $primary_value, $dbh ) );
				} else {
					$sql = 'SELECT * FROM ' . $tablename . ' LIMIT 1';
				}
				$result = mysql_query ( $sql, $dbh );
				if ($result===false) {
					return false;
				}
				if(mysql_num_rows($result)==0){
				    mysql_free_result ( $result );
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
				if ($sth->rowCount () == 0) {
					$sth->closeCursor ();
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
				if ($rs->RecordCount() == 0) {
					$rs->Close ();
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
	 *
	 * + 作用：
	 * 1 实例插入函数。
	 * + 参数：
	 * 1 $tablename 为默认空串时，使用继承类对应的表名，非继承类返回false。
	 * 2 $primary_index 为0时，不使用主键直接插入到数据库里。 
	 * 3 $primary_index 为正整数时，使用第 $primary_index 顺序的属性作为主键插入数据库。 
	 * + 注意：
	 * 1 参数 $tablename 使用表前缀替换规则。
	 * + 示例：
	 * <code>
	 * // 基类指定表插入，有主键 
	 *$obj = new core;
	 *$obj->id = null;
	 *$obj->name = 'a';
	 *$obj->insert('account') or die('failed'); //插入account表，返回是否成功
	 *echo $obj->id; //取回插入的id值
	 *
	 * // 插入一条记录，无主键 
	 *$obj = new core;
	 *$obj->id = 1;
	 *$obj->name = 'a';
	 *$obj->insert('account',0) or die('failed'); //插入account表，返回是否成功
	 *
	 * // 插入一条记录，指定主键 
	 *$obj = new core;
	 *$obj->name = 'a';
	 *$obj->id = 2;
	 *$obj->insert('account', 2) or die('failed'); //插入account表，返回是否成功
	 *
	 * // 继承类插入，有主键 
	 *class account extends core{} //继承core类
	 *$account = new account;
	 *$account->id = null;
	 *$account->name = 'a';
	 *$account->insert() or die('failed'); //插入account表，返回是否成功
	 *echo $account->id; //取回插入的id值
	 *
	 * // 继承类插入，无主键 
	 *class account extends core{} //继承core类
	 *$account = new account;
	 *$account->id = 1;
	 *$account->name = 'a';
	 *$account->insert('',0) or die('failed'); //插入account表，返回是否成功
	 * </code>
	 * @param string $tablename
	 * @param int $primary_index
	 * @return bool
	 */
	public function insert($tablename = '', $primary_index = 1) {
		
		// 【基础功能】插入数据
		$dbh = self::connect (true, $args);
	    // 参数
		if ($tablename == '') {
			$tablename = get_class ( $this );
			if ($tablename == __CLASS__) {
				return false;
			}
			if($args['prefix_search']!=''){
			    $tablename = $args['prefix_search'] . $tablename;
			}
		}
		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
		    $tablename = str_replace($args['prefix_search'], $args['prefix_replace'], $tablename);
		}
		$fieldvars = get_object_vars ( $this );
		if (count ( $fieldvars ) == 0 || count ( $fieldvars ) < $primary_index) {
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
		switch ($args['provider']) {
			default :
			case 'mysql' :
				$sql = 'INSERT INTO ' . $tablename . ' (' . $fieldname . ') VALUES (' . $valuename . ')';
				if ($primary_index > 0) {
				    $result = self::execute ( $sql, $paramvars, $ref );
				    if($result){
				        $this->$primary_name = $ref['insert_id'];
				    }
				}else{
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
	 * 数据库修改函数
	 *
	 * + 作用：
	 * 1 实例修改函数。
	 * + 参数：
	 * 1 $tablename 为默认空串时，使用继承类对应的表名，非继承类返回false。
	 * 2 $primary_index 为0时，不使用主键直接修改数据库里的一条记录。
	 * 3 $primary_index 为正整数时，使用第 $primary_index 顺序的属性作为主键修改一条记录。 
	 * 4 返回数据是否修改成功，注意未修改的也会返回false。
	 * + 注意：
	 * 1 参数 $tablename 使用表前缀替换规则。
	 * + 示例：
	 * <code>
	 * // 基类指定表修改，有主键 
	 *$obj = new core;
	 *$obj->id = 1;
	 *$obj->name = 'b';
	 *$obj->update('account') or die('failed'); //修改account表一条记录(id=1)，返回是否成功
	 *
	 * // 修改一条记录，无主键 
	 *$obj = new core;
	 *$obj->name = 'b';
	 *$obj->update('account',0) or die('failed'); //修改account表一条记录，返回是否成功
	 *
	 * // 修改一条记录，指定主键 
	 *$obj = new core;
	 *$obj->name = 'a';
	 *$obj->id = 2;
	 *$obj->update('account', 2) or die('failed'); //修改account表一条记录(id=2)，返回是否成功
	 *
	 * // 继承类修改，有主键 
	 *class account extends core{} //继承core类
	 *$account = new account;
	 *$account->id = 1;
	 *$account->name = 'b';
	 *$account->update(); //修改account表一条记录(id=1)，返回是否成功
	 *
	 * // 继承类修改，无主键 
	 *class account extends core{} //继承core类
	 *$account = new account;
	 *$account->name = 'b';
	 *$account->update('',0) or die('failed'); //修改account表一条记录，返回是否成功
	 * </code>
	 * @param string $tablename
	 * @param int $primary_index
	 * @return bool
	 */
	public function update($tablename = '', $primary_index = 1) {
		
		// 【基础功能】修改数据
		$dbh = self::connect (true, $args);
	    // 参数
		if ($tablename == '') {
			$tablename = get_class ( $this );
			if ($tablename == __CLASS__) {
				return false;
			}
			if($args['prefix_search']!=''){
			    $tablename = $args['prefix_search'] . $tablename;
			}
		}
		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
		    $tablename = str_replace($args['prefix_search'], $args['prefix_replace'], $tablename);
		}
		$fieldvars = get_object_vars ( $this );
		if (count ( $fieldvars ) == 0 || count ( $fieldvars ) < $primary_index) {
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
		switch ($args['provider']) {
			default :
			case 'mysql' :
				if ($primary_index > 0) {
					$sql = 'UPDATE ' . $tablename . ' SET ' . $valuename . ' WHERE ' . $primary_name . '=? LIMIT 1';
				} else {
					$sql = 'UPDATE ' . $tablename . ' SET ' . $valuename . ' LIMIT 1';
				}
				$result = self::execute ( $sql, $paramvars, $ref);
				if($result && $ref['affected_rows']==0){
				    return false;
				}
				return ( bool ) $result;
			case 'pdo' :
				if ($primary_index > 0) {
					$sql = 'UPDATE ' . $tablename . ' SET ' . $valuename . ' WHERE ' . $primary_name . '=? LIMIT 1';
				} else {
					$sql = 'UPDATE ' . $tablename . ' SET ' . $valuename . ' LIMIT 1';
				}
				$sth = $dbh->prepare ( $sql );
				$result = $sth->execute ( $paramvars );
				if($result && $sth->rowCount()==0){
				    return false;
				}
				return ( bool ) $result;
			case 'adodb' :
				if ($primary_index > 0) {
					$sql = 'UPDATE ' . $tablename . ' SET ' . $valuename . ' WHERE ' . $primary_name . '=? LIMIT 1';
				} else {
					$sql = 'UPDATE ' . $tablename . ' SET ' . $valuename . ' LIMIT 1';
				}
				$result = $dbh->Execute ( $sql, $paramvars );
				if($result && $dbh->Affected_Rows()==0){
				    return false;
				}
				return ( bool ) $result;
		}
	
	}
	
	/**
	 * 数据库删除函数
	 *
	 * + 作用：
	 * 1 实例删除函数。
	 * + 参数：
	 * 1 $tablename 为默认空串时，使用继承类对应的表名，非继承类返回false。
	 * 2 $primary_index 为0时，不使用主键直接删除数据库里的一条记录。
	 * 3 $primary_index 为正整数时，使用第 $primary_index 顺序的属性作为主键删除一条记录。
	 * 4 返回数据是否删除成功，注意未修改的也会返回false。
	 * + 注意：
	 * 1 参数 $tablename 使用表前缀替换规则。
	 * + 示例：
	 * <code>
	 * // 基类指定表删除，有主键 
	 *$obj = new core;
	 *$obj->id = 1;
	 *$obj->delete('account') or die('failed'); //删除account表一条记录(id=1)，返回是否成功
	 *
	 * // 删除一条记录，无主键 
	 *$obj = new core;
	 *$obj->delete('account',0) or die('failed'); //删除account表一条记录，返回是否成功
	 *
	 * // 删除一条记录，指定主键 
	 *$obj = new core;
	 *$obj->name = 'a';
	 *$obj->id = 2;
	 *$obj->delete('account', 2) or die('failed'); //删除account表一条记录(id=2)，返回是否成功
	 *
	 * // 继承类删除，有主键 
	 *class account extends core{} //继承core类
	 *$account = new account;
	 *$account->id = 1;
	 *$account->delete(); //删除account表一条记录(id=1)，返回是否成功
	 *
	 * // 继承类删除，无主键 
	 *class account extends core{} //继承core类
	 *$account = new account;
	 *$account->update('',0) or die('failed'); //删除account表一条记录，返回是否成功	 * </code>
	 * @param string $tablename
	 * @param int $primary_index
	 * @return bool
	 */
	public function delete($tablename = '', $primary_index = 1) {
		
		// 【基础功能】删除数据
		$dbh = self::connect (true, $args);
	    // 参数
		if ($tablename == '') {
			$tablename = get_class ( $this );
			if ($tablename == __CLASS__) {
				return false;
			}
			if($args['prefix_search']!=''){
			    $tablename = $args['prefix_search'] . $tablename;
			}
		}
		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
		    $tablename = str_replace($args['prefix_search'], $args['prefix_replace'], $tablename);
		}
		$fieldvars = get_object_vars ( $this );
		if (count ( $fieldvars ) == 0 || count ( $fieldvars ) < $primary_index) {
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
		switch ($args['provider']) {
			default :
			case 'mysql' :
				if ($primary_index > 0) {
					$sql = sprintf ( 'DELETE FROM ' . $tablename . ' WHERE ' . $primary_name . '=\'%s\' LIMIT 1', mysql_real_escape_string ( $primary_value, $dbh ) );
				} else {
					$sql = 'DELETE FROM ' . $tablename . ' LIMIT 1';
				}
				$result = mysql_query ( $sql, $dbh );
				if ($result && mysql_affected_rows($dbh)==0) {
					return false;
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
				if($result && $sth->rowCount()==0){
				    return false;
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
				if($result && $dbh->Affected_Rows()==0){
				    return false;
				}
				return ( bool ) $result;
		}
	
	}
	
	/**
	 * 数据库更新函数
	 *
	 * + 作用：
	 * 1 实例更新函数。
	 * + 参数：
	 * 1 $tablename 为默认空串时，使用继承类对应的表名，非继承类返回false。
	 * 2 $primary_index 为0时，不使用主键直接更新数据库里的一条记录。
	 * 3 $primary_index 为正整数时，使用第 $primary_index 顺序的属性作为主键更新一条记录。
	 * 4 返回数据是否更新成功，注意未修改的也会返回false。
	 * + 注意：
	 * 1 参数 $tablename 使用表前缀替换规则。
	 * + 示例：
	 * <code>
	 * // 基类指定表更新，有主键 
	 *$obj = new core;
	 *$obj->id = 1;
	 *$obj->name = 'b';
	 *$obj->replace('account') or die('failed'); //更新account表一条记录(id=1)，返回是否成功
	 *
	 * // 更新一条记录，无主键 
	 *$obj = new core;
	 *$obj->name = 'b';
	 *$obj->replace('account',0) or die('failed'); //更新account表一条记录，返回是否成功
	 *
	 * // 更新一条记录，指定主键 
	 *$obj = new core;
	 *$obj->name = 'a';
	 *$obj->id = 2;
	 *$obj->replace('account', 2) or die('failed'); //更新account表一条记录(id=2)，返回是否成功
	 *
	 * // 继承类更新，有主键 
	 *class account extends core{} //继承core类
	 *$account = new account;
	 *$account->id = 1;
	 *$account->name = 'b';
	 *$account->replace(); //更新account表一条记录(id=1)，返回是否成功
	 *
	 * // 继承类更新，无主键 
	 *class account extends core{} //继承core类
	 *$account = new account;
	 *$account->name = 'b';
	 *$account->replace('',0) or die('failed'); //更新account表一条记录，返回是否成功
	 * </code>
	 * @param string $tablename
	 * @param int $primary_index
	 * @return bool
	 */
	public function replace($tablename = '', $primary_index = 1) {
		
		// 【基础功能】更新数据
		$dbh = self::connect (true, $args);
	    // 参数
		if ($tablename == '') {
			$tablename = get_class ( $this );
			if ($tablename == __CLASS__) {
				return false;
			}
			if($args['prefix_search']!=''){
			    $tablename = $args['prefix_search'] . $tablename;
			}
		}
		if($args['prefix_search']!='' && $args['prefix_search']!=$args['prefix_replace']){
		    $tablename = str_replace($args['prefix_search'], $args['prefix_replace'], $tablename);
		}
		$fieldvars = get_object_vars ( $this );
		if (count ( $fieldvars ) == 0 || count ( $fieldvars ) < $primary_index) {
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
		// 分析
		$fieldname = implode ( ',', array_keys ( $fieldvars ) );
		$valuename = implode ( ',', array_fill ( 0, count ( $fieldvars ), '?' ) );
		$paramvars = array_values ( $fieldvars );
		// 执行
		switch ($args['provider']) {
			default :
			case 'mysql' :
				$sql = 'REPLACE INTO ' . $tablename . ' (' . $fieldname . ') VALUES (' . $valuename . ')';
				if ($primary_index > 0) {
				    $result = self::execute ( $sql, $paramvars, $ref );
				    if($result){
				        $this->$primary_name = $ref['insert_id'];
				    }
				}else{
				    $result = self::execute ( $sql, $paramvars );
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
	

}

/**
 * 执行(execute)
 */
core::stub () and core::main ();
?>
