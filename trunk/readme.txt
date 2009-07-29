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
	实例方法	
		$obj->struct	
		$obj->select
		$obj->insert
		$obj->update
		$obj->delete
		$obj->replace
	静态方法
		core::structs	
		core::selects
		core::inserts
		core::updates
		core::deletes
		core::replaces