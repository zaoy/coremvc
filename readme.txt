【CoreMVC 产品说明】

CoreMVC是PHP的一款小巧精致的MVC框架。

2010年3月24日

〖版权说明〗

CoreMVC遵从new BSD许可证，所以您可以在法律允许的范围以内任意修改和使用，包括商业应用。
请保留core.php开头的版权信息，但您可以添加其他信息。
/**
 * CoreMVC核心模块
 * 
 * @version 1.1.0
 * @author Z <602000@gmail.com>
 * @link http://code.google.com/p/coremvc/
 */


〖功能说明〗

●　完整功能说明

1. 模块驱动开发，将被访问和被引用的模块统一起来。

2. 通过参数设置，能方便的自动导入类文件。

3. 通过参数设置，能方便的实现单入口模式。

4. 引用模块被访问时，能实现自动隐藏功能。

5. 将MVC统一起来，减少文件操作的工作量。

6. 视图方法可根据不同的模板技术进行扩展。

7. 数据库可根据不同的连接方式进行扩展。

8. 强大的ORM功能，SQL语句和数组参数可灵活选择。

9. 数据库连接自动化，可使用多个连接。

10. 整个核心文件只有：1个类、31项设置、21个方法。


●　版本更新说明

CoreMVC 1.1.0 相对于 CoreMVC 1.0.0版本的改进
--------------------------------------------
1. 增加了模板路径替换的参数设置。

2. 增加了使用框架失败时隐藏设置。


CoreMVC 1.0.0 相对于 CoreMVC 0.7.3版本的改进
--------------------------------------------
1. 增加了core::path和core::init方法。

2. 增加了针对多个方法的动态配置。

3. 去掉了core::stub里的反向路由。

4. 增加了core::main里单入口功能。

5. 去掉了core::view里级连模板功能。

6. 增加了view_smarty.php、db_adodb.php里设置路径的地方。

7. 去掉了db_static_binding常量，恢复自动判断版本。

8. 增加了使用SQL语句操作ORM的功能。

9. 彻底修改了core::prepare函数功能，可以选择生成带与不带参数的SQL语句了。

10.修改了core::sequence的实现方式，更加高效。

11.实例函数字段参数下标从0开始了，原来是从1开始，无主键现在使用-1。

12.增加了config参数设置配置文件。

13.修改了核心文件所在的目录为“@”开头，原来是“./”开头。

14.修改了core::structs和core::selects生成数据的结构参数，更直观和易用。

15.pdo和adodb现在作为扩展模块存在，虽然pdo和adodb暂时只支持mysql数据库，但更容易扩展。


〖测试说明〗

●　单元测试说明

测试数据库：test

测试帐号：ODBC(密码空)

测试目录：tests目录

测试脚本：PHPUnit-3.4\phpunit.bat --bootstrap PHPUnit-3.4/bootstrap.php --include-path ../src/svn/trunk ../src/svn/trunk/tests
（该脚本适合在CoreAMP上执行，并且core.php和tests目录都在svn/trunk目录上）



〖展望计划〗

1. 扩展pdo、adodb和其他的模块，支持更多的数据库和连接方式。

2. 增加CoreMVC的例子和适合CoreMVC的类库。

3. 将CoreMVC编写成一个PHP扩展，以增加运行效率。



【CoreMVC 简易教程（结构）】


〖目录结构〗

core.php			核心程序，CoreMVC核心程序，必须有。

core				扩展目录，CoreMVC扩展目录，可删除。
　│
　├─　db_pdo.php		扩展程序，使用PDO操作数据库时用到，可删除。
　│
　├─　db_adodb.php		扩展程序，使用Adodb操作数据库时用到，可删除。
　│
　├─　AdodbZip.php		独立模块，Adodb自动装载器，可删除。
　│
　├─　view_smarty.php		扩展程序，使用Smarty模板时用到，可删除。
　│
　├─　SmartyZip.php		独立模块，Smarty自动装载器，可删除。
　│
　└─　.htaccess		配置文件，用于Apache服务器的访问限制，可删除。

readme.txt			说明文档，CoreMVC的产品说明和简易教程，可删除。

tests				测试目录，对核心程序进行单元测试的目录，可删除。


〖框架结构〗

文件三类型：模块　　配置　　模板───┐
　(MCT) 　　Module　Config　Template　│
　　　　　　│　　　　　　　　　　　　│
　　　　　　├───┬───┐　　　　│
　　　　　　│　　　│　　　│　　　　│
模块三段式：导入　　定义　　执行　　　│
　(IDE) 　　Import　Define　Execute 　│
　　　　　　　　　　│　　　　　　　　│
　　　　　　┌───┼───┐　　　　│
　　　　　　│　　　│　　　│　　　　│
框架三步曲：模型　　视图　　控制　　　│
　(MVC) 　　Model 　View　　Controler │
　　　　　　　　　　│　　　　　　　　│
　　　　　　　　　　└────────┘

●　模块文件：被用户访问或被程序引用的文件，例如：
foo.php

●　配置文件：被程序引用用于设置参数的文件，例如：
config.php

●　模板文件：视图所需要的单独的文件，例如：
foo/view.tpl


●　导入部份：模块中导入其他模块的部份，例如：
/**
 * 导入(import)
 */
require_once dirname (__FILE__) . '/core.php';

●　定义部份：模块中定义模块的部份，例如：
/**
 * 定义(define)
 */
class foo extends core { ... }

●　执行部份：模块中执行代码的部份，例如：
/**
 * 执行(execute)
 */
foo::stub () and foo::main ();


●　模型方法：模块定义的数据模型的方法。使用动态类型定义方法，例如：
public function foo ( ... ) { ... }

●　视图方法：模块定义的显示模板的方法。可在控制方法中直接使用视图方法，例如：
self::view (__CLASS__ . '/view.tpl');

●　控制方法：模块定义的控制流程的方法。使用静态类型定义方法，例如：
static public function foo ( ... ) { ... }



【CoreMVC 简易教程（模块）】


〖模块驱动开发〗

　┌────┐　　┌────┐
┌┤设计模块├──┤开发模块├┐
│└────┘　　└────┘│
│　　　　　　　　　　　　　　│
└─────重构模块─────┘

设计模块：将需求转化为模块，模块之间保持低耦合度，确定模块要实现的功能。

开发模块：在模块内实现所需的功能，保持功能之间的逻辑关系，厘清模块之间的调用关系。

重构模块：根据需求和用途继续优化模块结构，以达到重用和扩展效果，并提高运行效率。


●　模块的设计原则

需求用途：根据需求和用途分成几个独立的模块。

耦合程度：模块和模块之间的耦合度尽可能疏松。

重用程度：重用度高的部份可以设计成单独模块。

模块大小：大小适宜同时兼顾开发运行管理效率。


●　模块的开发原则

易于设计：模块之间能体现泛化、包含、扩展的关系。

易于开发：尽量用PHP内部函数，减少模块间的调用。

易于测试：代码符合使用单元测试的功能。

易于部署：模块可以很方便的组合，可快速部署和部署多个。


〖模块之间关系〗

┌──────┐　┌────┐
│　应用模块　├─┤　　　　│
│┌────┐│　│独立模块│
││核心模块├┼─┤　　　　│
│└────┘│　└────┘
└──────┘

●　核心模块：用于建立核心框架的模块。

●　应用模块：直接针对业务应用的模块。

●　独立模块：常用类库相对独立的模块。



【CoreMVC 简易教程（框架）】


〖核心模块设计〗


●　访问核心模块(ACM)

┌───────────┐
│　　　应用模块　　　　│
│┌┄┄┄┄┄┄┄┄┄┐│　┌────┐
│┆　访问核心模块＝　├┼┄┤配置文件│
│┆核心模块＋直接访问┆│　└────┘
│└┄┄┄┄┬┄┄┄┄┘├─┐
└─────┼─────┘　│
　　　　　　└───────┘

该设计模式用于将核心模块直接作为单入口文件，需要设置核心模块里的参数。
配置文件是是可选的，在核心模块指定配置文件即可。例子如下：
<?php
	const main_framework_enable = true;			// 启用框架开关，使用单入口方式
	const main_framework_require = 'classes/[go].php';	// 自动导入classes目录下的go参数的php文件
	const main_framework_module = '[go]';			// 自动使用go参数的自定义类
	const main_framework_action = '[do]';			// 自动调用static public function定义的方法
?>



●　桥接核心模块(BCM)

┌──────┐
│　应用模块　│
│┌┄┄┄┄┐│　┌──────┐　┌────┐
│┆核心模块┆├─┤桥接核心模块├┄┤配置文件│
│└┄┬┄┄┘│　└──┬───┘  └────┘
└──┼───┘　　　　│
　　　└────────┘

该设计模式用于多个项目共用一个核心模块，桥接核心模块导入核心模块后负责设置相关参数。
配置文件是是可选的，在桥接核心模块里导入设置即可。例子如下：
<?php
/**
 * 导入(import)
 */
require_once dirname (__FILE__) . '/../core.php';

/**
 * 执行(execute)
 */
core::init('@config.php');
?>


●　配置核心模块(CCM)

┌───────────┐
│　　　应用模块　　　　│
│┌─────────┐│　┌────┐
││　配置核心模块＝　├┼┄┤配置文件│
││核心模块＋配置参数││　└────┘
│└─────────┘│　
└───────────┘

该设计模式用于单个项目，核心模块可设置参数。
配置文件是是可选的，在核心模块指定配置文件即可。例子如下：
<?php
	const config = '@config.php';	// 使用核心模块所在目录下config.php为配置文件
?>


●　增强核心模块(ECM)

┌───────────┐
│　　　应用模块　　　　│
│┌─────────┐│　┌────┐
││　增强核心模块＝　├┼┄┤配置文件│
││核心模块＋增强代码││　└────┘
│└─────────┘│
└───────────┘

该设计模式用于较大规模的项目，可对核心模块进行改造增加方法以获得更强的功能。
配置文件是是可选的，在核心模块指定配置文件即可。


●　继承核心模块(ICM)

┌────────┐
│　　应用模块　　│
│┌──────┐│　┌────┐
││继承核心模块├┼┄┤配置文件│
││┌────┐││  └────┘
│││核心模块│││
││└────┘││
│└──────┘│
└────────┘

该设计模式用于较大规模的项目，可对核心模块进行改造，但保持核心模块不变可随时升级。
配置文件是是可选的，在继承核心模块里导入设置即可。例子如下：
<?php
/**
 * 导入(import)
 */
require_once dirname (__FILE__) . '/core.php';

/**
 * 定义(define)
 */
class base extends core { }

/**
 * 执行(execute)
 */
base::init(require dirname (__FILE__) . '/config.php');
?>


●　外部核心模块(XCM)

┌────┐　┌──────┐　┌────┐
│应用模块├─┤外部核心模块├┄┤配置文件│
└────┘　└──────┘  └────┘

该设计模式用于已有框架或面向过程的项目，仅将核心文件当作ORM工具使用。
配置文件是是可选的，在外部核心模块里导入设置即可。


〖应用模块设计〗


●　继承应用模块(IAM)

┌──────┐
│继承应用模块│
│┌────┐│　┌────┐
││核心模块│├┄┤独立模块│
│└────┘│　└────┘
└──────┘

该设计模式用于开发新的项目，使用CoreMVC框架。
独立模块是可选的，可被继承应用模块使用。例子如下：
<?php
/**
 * 导入(import)
 */
require_once dirname (__FILE__) . '/core.php';

/**
 * 定义(define)
 */
class foo extends core { ... }

/**
 * 执行(execute)
 */
foo::stub () and foo::main ();
?>


●　外部应用模块(XAM)

┌────┐　┌──────┐　┌────┐
│核心模块├─┤外部应用模块├┄┤独立模块│
└────┘　└──────┘　└────┘

该设计模式用于修改已有项目，仅将核心文件当作ORM工具使用：
独立模块是可选的，可被继承应用模块使用。以下是例子：
<?php
/**
 * 导入(import)
 */
require_once dirname (__FILE__) . '/core.php';

/**
 * 执行(execute)
 */
// ......
$table_arr = core::selects( ... );
// ......
?>



〖模板文件设计〗


●　分块模板文件(BTF)

　　　　　　　　　┌──────┐
　　　　　　　┌─┤分块模板文件│
┌────┐　│　└──────┘
│应用模块├─┤
└────┘　│　┌──────┐　　
　　　　　　　└─┤分块模板文件│
　　　　　　　　　└──────┘　

该设计模式用于较为统一的页面显示，带有统一的页头和页尾，例如：
self::view ('common/head.tpl');
self::view (__CLASS__ . '/view.tpl');
self::view ('common/tail.tpl');


●　公用模板文件(CTF)

┌────┐
│模板文件├─┐
└────┘　│　┌──────┐
　　　　　　　├─┤公用模板文件│
┌────┐　│　└──────┘　
│模板文件├─┘
└────┘　

该设计模式用于不同模板显示相同样式的区块，例如：
<?php include_once 'common/pagination.tpl'; ?>



【CoreMVC 简易教程（常量）】


〖核心模块常量一览〗

class core {

	/*
	 * 配置文件参数
	 */
	const config = ''; // 配置文件，仅此参数不可在程序中初始化

	/*
	 * 框架视图参数
	 */
	const stub_autoload_enable = ''; // 自动载入开关

	const stub_autoload_path = ''; // 自动载入路径

	const stub_autoload_extensions = ''; // 自动载入后缀

	const main_framework_enable = ''; // 使用框架开关

	const main_framework_require = ''; // 使用框架的包含文件

	const main_framework_module = ''; // 使用框架的模块参数

	const main_framework_action = ''; // 使用框架的行为参数

	const path_extension_path = ''; // 扩展模块路径

	const path_template_path = ''; // 视图模板路径

	const view_template_type = ''; // 视图模板类型

	const view_template_show = ''; // 视图输出方式

	const view_default_dir = ''; // 视图所在路径

	/*
	 * 数据库参数 
	 */
	const db_connect_provider = ''; // 数据库提供类型

	const db_connect_dsn = ''; // 数据库连接字符串

	const db_connect_type = ''; // 数据库连接类型

	const db_connect_server = ''; // 数据库连接服务器

	const db_connect_username = ''; // 数据库连接帐号

	const db_connect_password = ''; // 数据库连接密码

	const db_connect_new_link = ''; // 数据库连接新连接参数

	const db_connect_client_flags = ''; // 数据库连接客户端参数

	const db_connect_dbname = ''; // 数据库连接初始数据库

	const db_connect_charset = ''; // 数据库连接编码

	const db_connect_port = ''; // 数据库连接端口号

	const db_connect_socket = ''; // 数据库连接socket值

	const db_prefix_search = ''; // 表名前缀标识符

	const db_prefix_replace = ''; // 表名前缀替换值
}


〖核心模块常量说明〗

●　config参数只能在常量中定义，不能在运行时设置。

●　运行时设置参数时，数组下标均去掉第一个前缀，配置文件如：
<?php
return array(
	'autoload_enable' => true,
	'framework_enable' => true,
	'template_path' => '@tpl',
	'template_type' => 'smarty',
	'connect_provider' => 'pdo',
	'connect_dsn => 'mysql:host=localhost;dbname=test',
	'connect_username' => 'root',
	'connect_password' => '',
	'connect_driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''),
);
?>

●　运行时可设置配置参数的有以下方法：
<?php
core::stub($config_array);
core::main($config_array);
core::path($config_array);
core::init($config_array); // 此方法为设置所有参数
core::view($config_array);
core::connect($config_array);

core::stub(requre $config_file);
core::main(requre $config_file);
core::path(requre $config_file);
core::init($config_file); // 此方法为设置所有参数
core::init(requre $config_file); // 此方法为设置所有参数
core::view(requre $config_file);
core::connect(requre $config_file);
?>

●　driver_options参数虽然不能在常量中定义，但可以在运行时设置，并且driver_options可以是数组，该参数仅对PDO有效。

●　详细的参数说明请参见core.php中的注释



【CoreMVC 简易教程（方法）】


〖核心模块方法一览〗

<?php
class core {
	
	/*
	 * 控制方法（静态调用）
	 */
	
	static public function stub() {} // 存根函数（可继承）
	
	static public function main() {} // 入口函数（可继承）
	
	static public function path() {} // 路径函数
	
	static public function init() {} // 初始化函数
	
	
	/*
	 * 视图方法（静态调用）
	 */
	
	static public function view() {} // 视图函数（可继承）
	
	
	/*
	 * 模型方法
	 */
	
	// 第一组（静态调用）
	static public function connect() {} // 数据库连接
	
	static public function execute() {} // 执行SQL语句
	
	static public function prepare() {} // 准备SQL语句
	
	static public function sequence() {} // 生成自增序列（可继承）

	// 第二组（静态调用）
	static public function structs() {} // 静态构造函数（可继承）
	
	static public function selects() {} // 静态选择函数（可继承）
	
	static public function inserts() {} // 静态插入函数（可继承）
	
	static public function updates() {} // 静态修改函数（可继承）
	
	static public function deletes() {} // 静态删除函数（可继承）
	
	static public function replaces() {} // 静态更新函数（可继承）
	
	// 第三组（实例调用）
	public function struct() {} // 实例构造函数（可继承）
	
	public function select() {} // 实例选择函数（可继承）
	
	public function insert() {} // 实例插入函数（可继承）
	
	public function update() {} // 实例修改函数（可继承）
	
	public function delete() {} // 实例删除函数（可继承）
	
	public function replace() {} // 实例更新函数（可继承）

}
?>


〖核心模块方法说明〗


●　方法功能说明

○　Controller
--------------------
core::stub	存根函数（可继承）
		1. 【基础功能】设置存根参数，返回参数数组。
		2. 【基础功能】自动载入功能，默认关闭。
		3. 【基础功能】判断访问或者引用，返回true/false(访问/引用)。

core::main	入口函数（可继承）
		1. 【基础功能】设置入口参数，返回参数数组。
		2. 【基础功能】使用框架功能，默认关闭。
		3. 【基础功能】模拟文件隐藏效果，返回true/false(框架/隐藏)。
		
core::path	路径函数
		1. 【基础功能】设置路径参数，返回参数数组。
		2. 【基础功能】返回转换路径，'@'开头相对核心文件路径。
		3. 【基础功能】返回扩展路径，默认相对核心文件类名路径。
		4. 【基础功能】返回视图路径，默认相对于当前的程序路径。
		
core::init	初始化函数
		1. 【基础功能】设置各类参数，返回参数数组。

○　View
--------------------
core::view	视图函数（可继承）
		1. 【基础功能】设置视图参数，返回参数数组。
		2. 【基础功能】原生模板和字符串模板。
		3. 【扩展功能】其他类型模板。

○　Model
--------------------
core::connect	数据库连接
		1. 【基础功能】设置数据库参数，返回参数数组。
		2. 【基础功能】返回连接序号。
		2. 【基础功能】选择指定连接，返回连接句柄。
		3. 【基础功能】连接数据库，返回连接句柄。
		4. 【基础功能】断开数据库。
		5. 【扩展功能】使用扩展方式连接/断开数据库。
		
core::execute	执行SQL语句
		1. 【基础功能】执行SQL语句，返回结果集。
		2. 【扩展功能】使用扩展方式。
		
core::prepare	准备SQL语句
		1. 【基础功能】准备SQL语句，返回SQL和参数的数组。
		2. 【扩展功能】使用扩展方式。
		
core::sequence	生成自增序列
		1. 【基础功能】生成自增序列，返回序列号。
		2. 【扩展功能】使用扩展方式。
		
core::structs	静态构造数据
		1. 【基础功能】静态构造函数，返回构造数组。
		
core::selects	静态选择数据
		1. 【基础功能】静态选择函数，返回选择数组。
		2. 【扩展功能】使用扩展方式。
		
core::inserts	静态插入数据
		1. 【基础功能】静态插入函数，返回影响记录数。
		2. 【扩展功能】使用扩展方式。
		
core::updates	静态修改数据
		1. 【基础功能】静态修改函数，返回影响记录数。
		2. 【扩展功能】使用扩展方式。
		
core::deletes	静态删除数据
		1. 【基础功能】静态删除函数，返回影响记录数。
		2. 【扩展功能】使用扩展方式。
		
core::replaces	静态更新数据
		1. 【基础功能】静态更新函数，返回影响记录数。
		2. 【扩展功能】使用扩展方式。
		
core->struct	构造实例数据
		1. 【基础功能】返回实例数组。
		2. 【基础功能】返回实例数据。
		3. 【基础功能】载入实例数组。
		
core->select	实例选择数据
		1. 【基础功能】选择实例数据，返回是否成功。
		2. 【扩展功能】使用扩展方式。
		
core->insert	实例插入数据
		1. 【基础功能】插入实例数据，返回是否成功。
		2. 【扩展功能】使用扩展方式。
		
core->update	实例修改数据
		1. 【基础功能】修改实例数据，返回是否成功。
		2. 【扩展功能】使用扩展方式。
		
core->delete	实例删除数据
		1. 【基础功能】删除实例数据，返回是否成功。
		2. 【扩展功能】使用扩展方式。
		
core->replace	实例更新数据
		1. 【基础功能】更新实例数据，返回是否成功。
		2. 【扩展功能】使用扩展方式。


●　core::prepare前两个参数的说明(mysql)，同样适用于相应的静态方法。

┌─────┬────────────────────────────┬───────────────────────┐
│第一个参数│　　　　　　　　第二个参数（数组）　　　　　　　　　　　│　　　　　　　返回SQL语句 　　　　　　　　　　│
├─────┼───┬─────────────┬──────┬───┼───────────────────────┤
│ selects　│$field│$table==''　　　　　　　　│$where　　　│$other│SELECT $field $other　　　　　　　　　　　　　│
├─────┼───┼─────────────┼──────┼───┼───────────────────────┤
│ selects　│$field│$table　　　　　　　　　　│$where==''　│$other│SELECT $field FROM $table $other　　　　　　　│
├─────┼───┼─────────────┼──────┼───┼───────────────────────┤
│ selects　│$field│$table　　　　　　　　　　│$where　　　│$other│SELECT $field FROM $table WHERE $where $other │
┝━━━━━┿━━━┿━━━━━━━━━━━━━┿━━━━━━┿━━━┿━━━━━━━━━━━━━━━━━━━━━━━┥
│ inserts　│$table│$column 　　　　　　　　　│$value!=null│$other│INSERT $table $column VALUES $value $other　　│
├─────┼───┼─────────────┼──────┼───┼───────────────────────┤
│ inserts　│$table│$set==array('x'=>'x',...) │$value==null│$other│INSERT $table SET $set $other 　　　　　　　　│
├─────┼───┼─────────────┼──────┼───┼───────────────────────┤
│ inserts　│$table│$column 　　　　　　　　　│$value==null│$other│INSERT $table $column $other　　　　　　　　　│
┝━━━━━┿━━━┿━━━━━━━━━━━━━┿━━━━━━┿━━━┿━━━━━━━━━━━━━━━━━━━━━━━┥
│ updates　│$table│$set　　　　　　　　　　　│$where==''　│$other│UPDATE $table SET $set $other 　　　　　　　　│
├─────┼───┼─────────────┼──────┼───┼───────────────────────┤
│ updates　│$table│$set　　　　　　　　　　　│$where　　　│$other│UPDATE $table SET $set WHERE $where $other　　│
┝━━━━━┿━━━┿━━━━━━━━━━━━━┿━━━━━━┿━━━┿━━━━━━━━━━━━━━━━━━━━━━━┥
│ deletes　│$field│$table　　　　　　　　　　│$where==''　│$other│DELETE $field FROM $table $other　　　　　　　│
├─────┼───┼─────────────┼──────┼───┼───────────────────────┤
│ deletes　│$field│$table　　　　　　　　　　│$where　　　│$other│DELETE $field FROM $table WHERE $where $other │
┝━━━━━┿━━━┿━━━━━━━━━━━━━┿━━━━━━┿━━━┿━━━━━━━━━━━━━━━━━━━━━━━┥
│ replaces │$table│$column 　　　　　　　　　│$value!=null│$other│REPLACE $table $column VALUES $value $other 　│
├─────┼───┼─────────────┼──────┼───┼───────────────────────┤
│ replaces │$table│$set==array('x'=>'x',...) │$value==null│$other│REPLACE $table SET $set $other　　　　　　　　│
├─────┼───┼─────────────┼──────┼───┼───────────────────────┤
│ replaces │$table│$column 　　　　　　　　　│$value==null│$other│REPLACE $table $column $other 　　　　　　　　│
└─────┴───┴─────────────┴──────┴───┴───────────────────────┘



●　core::selects第5个参数的说明，同样适用于core::structs第2个参数。

┌───────────────────────┬──────────────────────┐
│　　　　　　　　参数类型　　　　　　　　　　　│　　　　　　　返回的结果　　　　　　　　　　│
├───────────────────────┼──────────────────────┤
│空值＝array(null, 'class'=>null)　　　　　　　│对象数组＝array(new static|self, ……)　　　│
├───────────────────────┼──────────────────────┤
│文本＝array(null, 'class'=>文本)　　　　　　　│对象数组＝array(new 文本, ……) 　　　　　　│
├───────────────────────┼──────────────────────┤
│对象＝array(null, 'clone'=>对象)　　　　　　　│对象数组＝array(clone 对象, ……) 　　　　　│
┝━━━━━━━━━━━━━━━━━━━━━━━┿━━━━━━━━━━━━━━━━━━━━━━┥
│array(……, 空值, ……, 值) 　　　　　　　　　│$return ...[]...＝值　　　　　　　　　　　　│
├───────────────────────┼──────────────────────┤
│array(……, 整数, ……, 值) 　　　　　　　　　│$return ...[对象[整数]]...＝值　　　　　　　│
├───────────────────────┼──────────────────────┤
│array(……, 文本, ……, 值) 　　　　　　　　　│$return ...[对象[文本]]...＝值　　　　　　　│
┝━━━━━━━━━━━━━━━━━━━━━━━┿━━━━━━━━━━━━━━━━━━━━━━┥
│array(……, 空值)＝array(……, 'class'=>null) │$return ...[]...＝new static|self 　　　　　│
├───────────────────────┼──────────────────────┤
│array(……, 整数)＝array(……, 'column'=>整数)│$return ...[]...＝数组[整数]　　　　　　　　│
├───────────────────────┼──────────────────────┤
│array(……, 文本)＝array(……, 'column'=>文本)│$return ...[]...＝数组[文本]　　　　　　　　│
├───────────────────────┼──────────────────────┤
│array(……, 对象)＝array(……, 'clone'=>对象) │$return ...[]...＝clone 对象　　　　　　　　│
├───────────────────────┼──────────────────────┤
│array(……, 数组)＝array(……, 'array'=>数组) │$return ...[]...＝数组　　　　　　　　　　　│
┝━━━━━━━━━━━━━━━━━━━━━━━┿━━━━━━━━━━━━━━━━━━━━━━┥
│array(……, 'assoc'=>null)　　　　　　　　　　│$return ...＝array('属性'=>'值', ……)　　　│
├───────────────────────┼──────────────────────┤
│array(……, 'both'=>null) 　　　　　　　　　　│$return ...＝array('属性'=>'值','值', ……) │
├───────────────────────┼──────────────────────┤
│array(……, 'num'=>null)　　　　　　　　　　　│$return ...＝array('值', ……)　　　　　　　│
├───────────────────────┼──────────────────────┤
│array(……, 'array'=>数组)　　　　　　　　　　│$return ...＝数组 　　　　　　　　　　　　　│
├───────────────────────┼──────────────────────┤
│array(……, 'column'=>整数) 　　　　　　　　　│$return ...＝数组[整数] 　　　　　　　　　　│
├───────────────────────┼──────────────────────┤
│array(……, 'column'=>文本) 　　　　　　　　　│$return ...＝数组[文本] 　　　　　　　　　　│
├───────────────────────┼──────────────────────┤
│array(……, 'class'=>空值)　　　　　　　　　　│$return ...＝new static|self　　　　　　　　│
├───────────────────────┼──────────────────────┤
│array(……, 'class'=>文本)　　　　　　　　　　│$return ...＝new 文本|static|self 　　　　　│
├───────────────────────┼──────────────────────┤
│array(……, 'class|classtype'=>文本)　　　　　│$return ...＝new 首个字段|文本|static|self　│
├───────────────────────┼──────────────────────┤
│array(……, 'clone'=>对象)　　　　　　　　　　│$return ...＝clone 对象 　　　　　　　　　　│
└───────────────────────┴──────────────────────┘
注：空值＝null或''，整数＝非负数整，文本＝非空字符串；static|self＝PHP 5.3使用static|PHP 5.2使用self。