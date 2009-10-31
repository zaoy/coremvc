程序文件说明
===============================================================
core.php			核心程序，可拷到任意目录，放在和程序同级目录较好，自身会隐藏访问
core/AdodbZip.php		扩展程序，使用adodb时拷到core所在的core目录（AdodbZip 1.0）
core/SmartyZip.php		扩展程序，使用smarty时拷到core所在的core目录（SmartyZip 1.6）
core/ViewWrapper.php		扩展程序，使用多级模板时拷到core所在的core目录（ViewWrap 1.0）
core/view_smarty.php		连接程序，连接core和smarty，使用smarty时拷到core所在的core目录
core/view_default.php		连接程序，连接core和多级模板，使用多级模板时拷到core所在的core目录
core/.htaccess			配置文件，使用扩展库时拷到core所在的core目录

readme.txt			简要说明
tests/				单元测试


单元测试说明
===============================================================
测试数据库：test
测试帐号：ODBC(密码空)
测试脚本：PHPUnit-3.4\phpunit.bat --bootstrap PHPUnit-3.4/bootstrap.php --include-path ../src/svn/trunk ../src/svn/trunk/tests


版本更新说明
===============================================================
0.7.1
------------------------
1. 修正了core::stub对于require_once的一个错误。


0.7.0
------------------------
1. 建立了core目录用于存放核心扩展模块
2. 增加了global_extension_dir常量，用于指定扩展目录，默认为类名目录
3. 增加了或修改了其他的一些常量
4. 扩展模块改用独立的SmartyZip.php、AdodbZip.php、ViewWrapper.php产品，自身不再隐藏访问，需要.htaccess配合
5. 增加了扩展模块连接器view_smarty.php、view_default.php等
6. 扩展模块增加了文件是否存在的判断。
7. 统一使用导入(import)、定义(define)、执行(execute)来注释程序三大段
8. core::connect等各静态方法动态方法都做了较大的修改
9. 所有core::xxxs的ORM函数第一个类名参数移到了最后
10. 修改了表前缀定义，分成查找值和替换值
11. 增加了测试用例
12. 增加了文档说明
13. 增加了大量程序注释
14. 与上一版比有很大的改动，有些不向下兼容的情况，比如参数顺序。
15. core::stub删除了正向路由功能，路由程序需通过main启动。


0.6.4
------------------------
1. 修正了core::selects中$other部份的一个错误


0.6.3
------------------------
1. 修改了core::sequence方法的实现方法


0.6.2
------------------------
1. 修正了core::selects方法里分页的错误


0.6.1
------------------------
1. 修正了core::selects方法里表名的错误


0.6.0
------------------------
1. CoreMVC
	core.php		核心
	core_smarty.php		smarty扩展
	core_wrapper.php	多级模板扩展
	core_adodb.php		adodb扩展

2. core.php相对于0.5版本做了较大的调整，所有方法都带上了固定参数
流程控制
	core::stub
	core::main
模板显示
	core::view
数据生成
	辅助方法
		core::connect
		core::execute
		core::prepare
		core::sequence
	静态方法
		core::structs
		core::selects
		core::inserts
		core::updates
		core::deletes
		core::replaces
	实例方法	
		$obj->struct
		$obj->select
		$obj->insert
		$obj->update
		$obj->delete
		$obj->replace
		


文档手册
===============================================================
位置：http://coremvc.520sz.com/coremvc/manual/index.html


计划与展望
===============================================================
1. 数据库驱动扩展化，pdo和adodb将作为扩展模块，可增加新的数据库扩展
2. ORM静态方法将支持完整的SQL和参数。