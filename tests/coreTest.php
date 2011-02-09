<?php

require_once 'PHPUnit\Framework\TestCase.php';

/**
 * core test case.
 */
class coreTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var core
	 */
	private $db_arr;
	private $view_arr;
	private $lib_arr;
	private $log_file;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// 1. adodb和smarty系列由于类名相同，只能选择一个类测试。
		// 2. adodb5、smarty2、smarty3、Zend、PHPExcel等，需要先自行安装相应类库才可测试。
		
		$this->db_arr = array(
			'mysql' => array((require 'config_db_mysql.php'),'resource','resource'),
			'pdo5' => array((require 'config_db_pdo5.php'),'PDO','PDOStatement'),
			'adodb5zip' => array((require 'config_db_adodb5zip.php'),'ADODB_mysqlt','ADORecordSet_mysqlt'),
			//'adodb5' => array((require 'config_db_adodb5.php'),'ADORecordSet_mysqlt'),
			//'SaeMysql5' => array((require 'config_db_SaeMysql5.php'),'resource','resource'),//此项需要创建app_test数据库并可完全匿名访问，并需要修改hosts文件
		);
		$this->view_arr = array(
			'include' => array(array('z'=>'b'),array('z'=>'b')),
			'string' =>  array(array('z'=>'b')),
			'smarty2zip' =>  array(array('z'=>'b'),array('z'=>'b')),
			//'smarty2' =>  array(array('z'=>'b'),array('z'=>'b')),
			//'smarty3' =>  array(array('z'=>'b'),array('z'=>'b')),
		);
		$this->lib_arr = array(
			//'Zend' => array('Zend_Acl'),
			//'PHPExcel' =>  array('PHPExcel_Reader_Excel2007'),
			//'PHPMailer' =>  array('PHPMailer','SMTP','POP3'),
			//'PHPCharts' =>  array('Axis','GridChart'),
		);
		$this->log_file = 'tmp.log';
	
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		
		$this->db_arr = null;
		$this->view_arr = null;
		$this->lib_arr = null;
		$this->log_file = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests core::init()
	 */
	public function testInit() {
		
		// 1. 【基础功能】设置各类参数，返回参数数组。
		//设置、取值、清空等
		$this->assertSame(array(),core::init(true));
		$this->assertSame('',core::init('a'));
		$this->assertSame(array('a'=>'b'),core::init(array('a'=>'b')));
		$this->assertSame('b',core::init('a'));
		$this->assertSame('',core::init('c'));
		$this->assertSame(array('a'=>'c'),core::init(array('a'=>'c')));
		$this->assertSame('c',core::init('a'));
		$this->assertSame(array('a'=>'c','c'=>'d'),core::init(array('c'=>'d')));
		$this->assertSame('c',core::init('a'));
		$this->assertSame('d',core::init('c'));
		$this->assertSame(array('a'=>'b','c'=>'d'),core::init(array('a'=>'b')));
		$this->assertSame('b',core::init('a'));
		$this->assertSame('d',core::init('c'));
		$this->assertSame(array('a'=>'b','c'=>'d'),core::init(false));
		$this->assertSame(array('b'),core::init(array('a')));
		$this->assertSame(array('b','d'),core::init(array('a','c')));
		$this->assertSame(array('b','d',''),core::init(array('a','c','e')));
		//读取配置文件
		$this->assertSame(array(),core::init(true));
		$this->assertSame(array('e'=>true),core::init('@tests/init_1.php'));
		//配置指定变量
		$value = '';
		$this->assertSame(array(),core::init(null,$value));
		$this->assertSame(array(),$value);
		$this->assertSame(array('a'=>'b'),core::init(array('a'=>'b'),$value));
		$this->assertSame(array('a'=>'b'),$value);
		$this->assertSame(array('a'=>'b'),core::init(false,$value));
		$this->assertSame(array('a'=>'b'),$value);
		$this->assertSame(array(),core::init(true,$value));
		$this->assertSame(array(),$value);
		$this->assertSame(array('e'=>true),core::init('@tests/init_1.php',$value));
		$this->assertSame(array('e'=>true),$value);
		
	}
	
	/**
	 * Tests core::stub()
	 */
	public function testStub() {
		// 2. 【基础功能】自动载入功能，默认关闭。
		//设置路径
		$include_path = get_include_path ();
		core::init(array('autoload_path'=>'abc'));
		$this->assertSame($include_path.PATH_SEPARATOR.'abc',get_include_path ());
		core::init(array('autoload_path'=>'xyz'));
		$this->assertSame($include_path.PATH_SEPARATOR.'xyz',get_include_path ());
		core::init(array('autoload_prepend'=>true));
		$this->assertSame('xyz'.PATH_SEPARATOR.$include_path,get_include_path ());
		core::init(array('autoload_prepend'=>''));
		$this->assertSame($include_path.PATH_SEPARATOR.'xyz',get_include_path ());
		core::init(array('autoload_path'=>''));
		$this->assertSame($include_path,get_include_path ());
		//设置后缀
		$autoload_extensions = spl_autoload_extensions ();
		core::init(array('autoload_extensions'=>'.inc.php'));
		$this->assertSame('.inc.php',spl_autoload_extensions ());
		core::init(array('autoload_extensions'=>'.class.php'));
		$this->assertSame('.class.php',spl_autoload_extensions ());
		core::init(array('autoload_extensions'=>''));
		$this->assertSame($autoload_extensions,spl_autoload_extensions ());
		//自动载入
		$autoload_functions = spl_autoload_functions ();
		core::init(array('autoload_enable'=>true));
		$this->assertSame(array_merge($autoload_functions,array('spl_autoload')),spl_autoload_functions ());
		core::init(array('autoload_prepend'=>true));
		if ( version_compare(PHP_VERSION,'5.3.0','>=') ) {
			$this->assertSame(array_merge(array('spl_autoload'),$autoload_functions),spl_autoload_functions ());
		} else {
			$this->assertSame(array_merge($autoload_functions,array('spl_autoload')),spl_autoload_functions ());
		}
		core::init(array('autoload_enable'=>'','autoload_prepend'=>''));
		$this->assertSame($autoload_functions,spl_autoload_functions ());

		//自动载入
		$this->assertFalse(class_exists('stub_2_1',false));
		core::stub(true);
		$this->assertTrue(class_exists('stub_2_1'));
		core::stub('','','','');
		//设置路径
		$this->assertFalse(class_exists('stub_2_2',false));
		core::stub(true,'@tests/stub_2');
		$this->assertTrue(class_exists('stub_2_2'));
		core::stub('','','','');
		//设置后缀
		$this->assertFalse(class_exists('stub_2_3',false));
		core::stub(true,'','.inc.php');
		$this->assertTrue(class_exists('stub_2_3'));
		core::stub('','','','');
		//设置顺序
		$this->assertFalse(class_exists('stub_2_2x',false));
		core::stub(true,'@tests/stub_2a');
		core::stub(true,'@tests/stub_2b',null,true);
		$this->assertTrue(class_exists('stub_2_2x'));
		$this->assertSame('stub_2b',stub_2_2x::test());
		core::stub('','','','');
		
		// 3. 【基础功能】判断访问或者引用，返回true/false(访问/引用)。
		$this->assertTrue(core::stub());
		$this->assertFalse(require('stub_3_1.php'));
		
	}
	
	/**
	 * Tests core::main()
	 */
	public function testMain() {
		
		// 2. 【基础功能】使用框架功能，默认关闭。
		//默认值
		$_GET['do']='testAction';
		ob_start();
		$result = require core::path('@tests/main_2_1.php');
		$this->assertSame('main_2_1_a', ob_get_clean());
		$this->assertTrue($result);
		unset($_GET['do']);
		//版本差异
		$_GET['do']='testAction';
		ob_start();
		$result = main_2_1::main(true);
		if(function_exists('get_called_class')){
			$this->assertSame('main_2_1_a', ob_get_clean());
			$this->assertTrue($result);
		}else{
			$this->assertSame('Could not open input file: '.basename($_SERVER ['SCRIPT_FILENAME']).PHP_EOL, ob_get_clean());
			$this->assertFalse($result);
		}
		unset($_GET['do']);
		//引用测试
		$_GET['go']='main_2_2';
		$_GET['do']='test_2_2';
		ob_start();
		$result = core::main(true,'@tests/[go].php','[go]','[do]');
		$this->assertSame('main_2_2_a', ob_get_clean());
		$this->assertTrue($result);
		unset($_GET['go']);
		unset($_GET['do']);
		//错误测试
		$this->assertTrue(core::main(true,'@tests/main_2_3.php','main_2_3','test_2_3'));
		ob_start();
		$this->assertFalse(core::main(true,'@tests/main_2_3.php','main_2_3','test_2_3_b'));
		$this->assertFalse(core::main(true,'@tests/main_2_3.php','main_2_3','test_2_3_c'));
		$this->assertFalse(core::main(true,'@tests/main_2_3.php','main_2_3','test_2_3_d'));
		$this->assertFalse(core::main(true,'@tests/main_2_3.php','0a','test_2_3_3'));
		$this->assertFalse(core::main(true,'@tests/main_2_3.php','main_2_3','1'));
		$this->assertFalse(core::main(true,'@tests/main_2_3.php','main_2_3\\0','test_2_3'));
		ob_end_clean();
		//命名空间
		if(function_exists('get_called_class')){
			$this->assertTrue(core::main(true,'@tests/main_2_4.php','namespace_2_4\main_2_4','test_2_4'));
		}
		//新增测试
		$this->assertSame('@tests/main_2_2.php',core::main('require','@tests/main_2_2.php'));
		$this->assertSame('@tests/main_2_2.php',core::main('require','@tests/main_2_2.php&*2_2.php'));
		$this->assertSame('@tests/main_2_2.php',core::main('require','@tests/main_2_2.php!*1_1.php'));
		$this->assertFalse(core::main('require','@tests/main_2_2_xxx.php'));
		$this->assertFalse(core::main('require','@tests/main_2_2.php&*1_1.php'));
		$this->assertFalse(core::main('require','@tests/main_2_2.php!*2_2.php'));

		$this->assertSame('main_2_2',core::main('module','@tests/main_2_2.php','main_2_2'));
		$this->assertFalse(core::main('module','@tests/main_2_2.php','xxx'));
		$this->assertFalse(core::main('module','@tests/main_2_2_xxx.php','main_2_2'));
		$this->assertFalse(core::main('module','@tests/main_2_2_xxx.php','xxx'));
		$this->assertSame('main_2_2',core::main('module','@tests/main_2_2.php','main_2_2&main_2_2'));
		$this->assertSame('main_2_2',core::main('module','@tests/main_2_2.php','main_2_2&main_2_*'));
		$this->assertSame('main_2_2',core::main('module','@tests/main_2_2.php','main_2_2&main_*_2'));
		$this->assertFalse(core::main('module','@tests/main_2_2.php','main_2_2&main_1_1'));
		$this->assertFalse(core::main('module','@tests/main_2_2.php','main_2_2&main_1_*'));
		$this->assertFalse(core::main('module','@tests/main_2_2.php','main_2_2&main_*_1'));
		$this->assertSame('main_2_2',core::main('module','@tests/main_2_2.php','main_2_2!main_1_1'));
		$this->assertSame('main_2_2',core::main('module','@tests/main_2_2.php','main_2_2!main_1_*'));
		$this->assertSame('main_2_2',core::main('module','@tests/main_2_2.php','main_2_2!main_*_1'));
		$this->assertFalse(core::main('module','@tests/main_2_2.php','main_2_2!main_2_2'));
		$this->assertFalse(core::main('module','@tests/main_2_2.php','main_2_2!main_2_*'));
		$this->assertFalse(core::main('module','@tests/main_2_2.php','main_2_2!main_*_2'));

		$this->assertSame(array('@tests/main_2_2.php','main_2_2'),core::main('require,module','@tests/main_2_2.php','main_2_2'));
		$this->assertSame(array('main_2_2','@tests/main_2_2.php'),core::main('module,require','@tests/main_2_2.php','main_2_2'));
		$this->assertSame(array('@tests/main_2_2.php',false),core::main('require,module','@tests/main_2_2.php','xxx'));
		$this->assertSame(array(false,'@tests/main_2_2.php'),core::main('module,require','@tests/main_2_2.php','xxx'));
		$this->assertSame(array(false,false),core::main('require,module','@tests/main_2_2_xxx.php','main_2_2'));
		$this->assertSame(array(false,false),core::main('module,require','@tests/main_2_2_xxx.php','main_2_2'));
		$this->assertSame(array(false,false),core::main('require,module','@tests/main_2_2_xxx.php','xxx'));
		$this->assertSame(array(false,false),core::main('module,require','@tests/main_2_2_xxx.php','xxx'));

		$this->assertSame('test_2_2',core::main('action','@tests/main_2_2.php','main_2_2','test_2_2'));
		$this->assertFalse(core::main('action','@tests/main_2_2.php','main_2_2','xxx'));
		$this->assertFalse(core::main('action','@tests/main_2_2.php','xxx','test_2_2'));
		$this->assertFalse(core::main('action','@tests/main_2_2_xxx.php','main_2_2','test_2_2'));
		$this->assertFalse(core::main('action','@tests/main_2_2_xxx.php','xxx','test_2_2'));
		$this->assertFalse(core::main('action','@tests/main_2_2_xxx.php','main_2_2','xxx'));
		$this->assertFalse(core::main('action','@tests/main_2_2_xxx.php','xxx','xxx'));

		$this->assertSame(array('main_2_2','test_2_2'),core::main('module,action','@tests/main_2_2.php','main_2_2','test_2_2'));
		$this->assertSame(array('test_2_2','main_2_2'),core::main('action,module','@tests/main_2_2.php','main_2_2','test_2_2'));
		$this->assertSame(array('@tests/main_2_2.php','main_2_2','test_2_2'),core::main('require,module,action','@tests/main_2_2.php','main_2_2','test_2_2'));
		$this->assertSame(array('test_2_2','main_2_2','@tests/main_2_2.php'),core::main('action,module,require','@tests/main_2_2.php','main_2_2','test_2_2'));
		$this->assertSame(array(false,'main_2_2','@tests/main_2_2.php'),core::main('action,module,require','@tests/main_2_2.php','main_2_2','xxx'));
		$this->assertSame(array(false,false,'@tests/main_2_2.php'),core::main('action,module,require','@tests/main_2_2.php','xxx','test_2_2'));
		$this->assertSame(array(false,false,false),core::main('action,module,require','@tests/main_2_2_xxx.php','main_2_2','test_2_2'));
		$this->assertSame(array('test_2_2','main_2_2',''),core::main('action,module,require','','main_2_2','test_2_2'));
		$this->assertSame(array(false,'main_2_2',''),core::main('action,module,require','','main_2_2','xxx'));
		$this->assertSame(array(false,false,''),core::main('action,module,require','','xxx','test_2_2'));

		$this->assertSame('test_2_3',core::main('action','@tests/main_2_3.php','main_2_3','test_2_3'));
		$this->assertSame('test_2_3_a',core::main('action','@tests/main_2_3.php','main_2_3','test_2_3_a'));
		$this->assertSame('test_2_3',core::main('action,static','@tests/main_2_3.php','main_2_3','test_2_3'));
		$this->assertFalse(core::main('action,static','@tests/main_2_3.php','main_2_3','test_2_3_a'));
		$this->assertFalse(core::main('action,object','@tests/main_2_3.php','main_2_3','test_2_3'));
		$this->assertSame('test_2_3_a',core::main('action,object','@tests/main_2_3.php','main_2_3','test_2_3_a'));
		$this->assertSame('test_2_3',core::main('action,static,object','@tests/main_2_3.php','main_2_3','test_2_3'));
		$this->assertSame('test_2_3_a',core::main('action,static,object','@tests/main_2_3.php','main_2_3','test_2_3_a'));
		$this->assertFalse(core::main('action,final','@tests/main_2_5.php','main_2_5','index'));
		$this->assertFalse(core::main('action,final','@tests/main_2_5.php','main_2_5','message'));
		$this->assertSame('index2',core::main('action,final','@tests/main_2_5.php','main_2_5','index2'));
		$this->assertSame('message2',core::main('action,final','@tests/main_2_5.php','main_2_5','message2'));
		$this->assertSame(array('main_2_3','test_2_3'),core::main('module,action','','main_2_3','main_2_3::test_2_3'));
		$this->assertSame(array('main_2_5','index'),core::main('module,action','','main_2_3','main_2_5::index'));
		$this->assertSame(array('main_2_5','index'),core::main('module,action','','main_2_3','test_2_3_xxx|main_2_5::index'));
		$this->assertSame(array('main_2_3','test_2_3'),core::main('module,action','','main_2_3','test_2_3&test_2_*|main_2_5::index'));
		$this->assertSame(array('main_2_5','index'),core::main('module,action','','main_2_3','test_2_3&test_1_*|main_2_5::index'));

		$this->assertSame(array('main_2_5','index'),core::main('module,action','','main_2_3','test_2_3&main_2_5::*|main_2_5::index'));
		$this->assertSame(array('main_2_3','test_2_3'),core::main('module,action','','main_2_3','test_2_3&main_2_3::*|main_2_5::index'));
		$this->assertSame(array('main_2_5','index'),core::main('module,action','','main_2_3','test_2_3&*::test_2_5|main_2_5::index'));
		$this->assertSame(array('main_2_3','test_2_3'),core::main('module,action','','main_2_3','test_2_3&*::test_2_3|main_2_5::index'));
		$this->assertSame(array('main_2_5','index'),core::main('module,action','','main_2_3','test_2_3&test_2_5|main_2_5::index'));
		$this->assertSame(array('main_2_3','test_2_3'),core::main('module,action','','main_2_3','test_2_3&test_2_3|main_2_5::index'));
		$this->assertSame(array('main_2_3','test_2_3'),core::main('module,action','','main_2_3','test_2_3&main_2_3::|main_2_5::index'));

		$this->assertSame(array('main_2_3','test_2_3'),core::main('module,action','','main_2_3','test_2_3!main_2_5::*|main_2_5::index'));
		$this->assertSame(array('main_2_5','index'),core::main('module,action','','main_2_3','test_2_3!main_2_3::*|main_2_5::index'));
		$this->assertSame(array('main_2_3','test_2_3'),core::main('module,action','','main_2_3','test_2_3!*::test_2_5|main_2_5::index'));
		$this->assertSame(array('main_2_5','index'),core::main('module,action','','main_2_3','test_2_3!*::test_2_3|main_2_5::index'));
		$this->assertSame(array('main_2_3','test_2_3'),core::main('module,action','','main_2_3','test_2_3!test_2_5|main_2_5::index'));
		$this->assertSame(array('main_2_5','index'),core::main('module,action','','main_2_3','test_2_3!test_2_3|main_2_5::index'));
		$this->assertSame(array('main_2_5','index'),core::main('module,action','','main_2_3','test_2_3!main_2_3::|main_2_5::index'));

		$this->assertSame(array('main_2_5','add',array('1','2')),core::main('module,action,parameter','','main_2_5','add','1,2'));
		$this->assertSame(array('main_2_5',false,false),core::main('module,action,parameter','','main_2_5','xxx','1,2'));
		$this->assertSame(array(false,false,false),core::main('module,action,parameter','','main_2_5_xxx','add','1,2'));
		$this->assertSame(3,core::main('return','','main_2_5','add','1,2'));
		$this->assertSame(3,core::main('return','','main_2_5','add','1,2&add'));
		$this->assertSame(3,core::main('return','','main_2_5','add','1,2&main_2_5::add'));
		$this->assertSame(5,core::main('return','','main_2_5','add','1,2&main_2_3::add|2,3'));
		$this->assertSame(5,core::main('return','','main_2_5','add','1,2&main_2_5::xxx|2,3'));
		$this->assertSame(5,core::main('return','','main_2_5','add','1,2!main_2_5::add|2,3'));



		ob_start();
		$this->assertNull(core::main('return','@tests/main_2_2.php','main_2_2','test_2_2'));
		$this->assertSame('main_2_2_a', ob_get_clean());
		ob_start();
		$this->assertTrue(core::main('manual','@tests/main_2_2.php','main_2_2','test_2_2'));
		$this->assertSame('main_2_2_a', ob_get_clean());
		ob_start();
		$this->assertTrue(core::main('manual','@tests/main_2_2.php','main_2_2','[do]|test_2_2'));
		$this->assertSame('main_2_2_a', ob_get_clean());
		ob_start();
		$this->assertTrue(core::main('manual','@tests/main_2_2.php','[go]|main_2_2','[do]|test_2_2'));
		$this->assertSame('main_2_2_a', ob_get_clean());
		ob_start();
		$this->assertTrue(core::main('manual','@tests/[go].php|@tests/main_2_2.php','[go]|main_2_2','[do]|test_2_2'));
		$this->assertSame('main_2_2_a', ob_get_clean());
		$this->assertSame(array('main_2_2',false),core::main('module,action','@tests/main_2_2.php','main_2_2',''));
		$this->assertSame('index',core::main('action','@tests/main_2_5.php','main_2_5',''));
		$this->assertSame('updates',core::main('action','@tests/main_2_5.php','main_2_5','updates'));
		$this->assertFalse(core::main('action','@tests/main_2_5.php','main_2_5','updates&(self)::*'));
		$this->assertFalse(core::main('action','@tests/main_2_5.php','main_2_5','updates&core::*'));
		$this->assertFalse(core::main('action','@tests/main_2_5.php','main_2_5','updates&CORE::*'));
		$this->assertSame('updates',core::main('action','@tests/main_2_5.php','main_2_5','core::updates'));
		$this->assertSame('updates',core::main('action','@tests/main_2_5.php','main_2_5','CORE::updates'));
		$this->assertFalse(core::main('action','@tests/main_2_5.php','main_2_5','updates!main_2_5::*'));
		$this->assertFalse(core::main('action','@tests/main_2_5.php','main_2_5','updates&MAIN_2_5::*'));
		$this->assertSame('add',core::main('action','@tests/main_2_5.php','main_2_5','add&main_2_5::*'));
		$this->assertFalse(core::main('action','@tests/main_2_5.php','main_2_5','add&CORE::*'));
		$_SERVER['QUERY_STRING']='index';
		$this->assertSame('index',core::main('action','@tests/main_2_5.php','main_2_5','[query:0]'));
		unset($_SERVER['QUERY_STRING']);
		$_GET['aa']='index';
		$this->assertSame('index',core::main('action','@tests/main_2_5.php','main_2_5',''));
		$this->assertSame('index',core::main('action','@tests/main_2_5.php','main_2_5','[get:1]'));
		$this->assertSame('Index',core::main('action','@tests/main_2_5.php','main_2_5','[Get:1]'));
		$this->assertSame('INDEX',core::main('action','@tests/main_2_5.php','main_2_5','[GET:1]'));
		$this->assertSame('iNDEX',core::main('action','@tests/main_2_5.php','main_2_5','[gET:1]'));
		$this->assertSame('index',core::main('action','@tests/main_2_5.php','main_2_5','[geT:1]'));
		$this->assertSame('index',core::main('action','@tests/main_2_5.php','main_2_5','[get:aa]'));
		$this->assertSame('Index',core::main('action','@tests/main_2_5.php','main_2_5','[Get:aa]'));
		$this->assertSame('INDEX',core::main('action','@tests/main_2_5.php','main_2_5','[GET:aa]'));
		$this->assertSame('iNDEX',core::main('action','@tests/main_2_5.php','main_2_5','[gET:aa]'));
		$this->assertSame('index',core::main('action','@tests/main_2_5.php','main_2_5','[geT:aa]'));
		$this->assertFalse(core::main('action','@tests/main_2_5.php','main_2_5','[get:bb]'));
		unset($_GET['aa']);
		$_POST['aa']='index';
		$this->assertSame('index',core::main('action','@tests/main_2_5.php','main_2_5','[post:1]'));
		$this->assertSame('Index',core::main('action','@tests/main_2_5.php','main_2_5','[Post:1]'));
		$this->assertSame('INDEX',core::main('action','@tests/main_2_5.php','main_2_5','[POST:1]'));
		$this->assertSame('iNDEX',core::main('action','@tests/main_2_5.php','main_2_5','[pOST:1]'));
		$this->assertSame('index',core::main('action','@tests/main_2_5.php','main_2_5','[posT:1]'));
		$this->assertSame('index',core::main('action','@tests/main_2_5.php','main_2_5','[post:aa]'));
		$this->assertSame('Index',core::main('action','@tests/main_2_5.php','main_2_5','[Post:aa]'));
		$this->assertSame('INDEX',core::main('action','@tests/main_2_5.php','main_2_5','[POST:aa]'));
		$this->assertSame('iNDEX',core::main('action','@tests/main_2_5.php','main_2_5','[pOST:aa]'));
		$this->assertSame('index',core::main('action','@tests/main_2_5.php','main_2_5','[posT:aa]'));
		$this->assertFalse(core::main('action','@tests/main_2_5.php','main_2_5','[posT:AA]'));
		unset($_POST['aa']);
		$this->assertFalse(core::main('static,action','@tests/main_2_5.php','main_2_5','message'));
		$this->assertSame('message',core::main('object,action','@tests/main_2_5.php','main_2_5','message'));
		$this->assertFalse(core::main('object,action','@tests/main_2_5.php','main_2_5','index'));
		$this->assertFalse(core::main('action','@tests/main_2_6.php','main_2_6','index'));
		$this->assertFalse(core::main('action','@tests/main_2_7.php','main_2_7','index'));
		$this->assertSame(array(),core::main('parameter','','main_2_5','index'));
		$this->assertSame(array('id'),core::main('parameter','','main_2_5','index','id'));
		$_GET['id']='1';
		$this->assertSame(array('1'),core::main('parameter','','main_2_5','index','[id]'));
		$this->assertSame(array('1','1'),core::main('parameter','','main_2_5','index','[id],[get:1]'));
		$this->assertSame(array(),core::main('parameter','','main_2_5','index','[id],[get:1]!index'));
		$this->assertSame(array('1','1'),core::main('parameter','','main_2_5','add','[id],[get:1]'));
		$this->assertSame(array(),core::main('parameter','','main_2_5','add','[id],[get:1]!add'));
		$this->assertSame(array('1','1'),core::main('parameter','','main_2_5','add','[id],[get:1]!ADD'));
		$this->assertSame(array('1','1'),core::main('parameter','','main_2_5','add','[id],[get:1]!index'));
		$this->assertSame(array('1','1'),core::main('parameter','','main_2_5','add','[id],[get:1]!INDEX'));
		$this->assertSame(array('1','1'),core::main('parameter','','main_2_5','add','[id],[get:1]&add!INDEX'));
		$this->assertSame(array('1'),core::main('parameter','','main_2_5','add','[id],[get:1]&INDEX|[id]'));
		$this->assertSame(array('index',array('1','1')),core::main('action,parameter','','main_2_5','index','[id],[get:1]'));
		unset($_GET['id']);
		$this->assertSame(3,core::main('return','','main_2_5','add','1,2'));
		$this->assertSame(3,core::main('return','','main_2_5','add','1,2,4'));
		$this->assertSame('index',core::main('action','','main_2_5','index'));
		$this->assertFalse(core::main('final,action','','main_2_5','index'));
		$this->assertSame('index2',core::main('action','','main_2_5','index2'));
		$this->assertSame('index2',core::main('final,action','','main_2_5','index2'));
		$this->assertSame('message',core::main('object,action','','main_2_5','message'));
		$this->assertFalse(core::main('final,object,action','','main_2_5','message'));
		$this->assertSame('index2',core::main('action','','main_2_5','index2'));
		$this->assertSame('message2',core::main('action','','main_2_5','message2'));
		$this->assertFalse(core::main('object,action','','main_2_5','index2'));
		$this->assertFalse(core::main('static,action','','main_2_5','message2'));
		$this->assertSame('message2',core::main('object,action','','main_2_5','message2'));
		$this->assertSame('message2',core::main('final,object,action','','main_2_5','message2'));


		// 1.3数组测试
		$this->assertSame(array('main_2_3','test_2_3'),core::main('module,action','','main_2_3','test_2_3'));
		$this->assertEquals(array(new main_2_3,'test_2_3_a'),core::main('module,action','','main_2_3','test_2_3_a'));
		$this->assertSame(array('main_2_3',false),core::main('module,action','','main_2_3','test_2_3_x'));
		$this->assertSame(array('main_2_3',''),core::main('module,action','','main_2_3','*|test_2_3_x'));
		$this->assertSame(array('main_2_3',''),core::main('module,action','','main_2_3','*|(function)::test_2_3_x'));
		$this->assertSame(array('','substr'),core::main('module,action','','main_2_3','*|(function)::substr'));
		$this->assertSame(array('','substr'),core::main('module,action','','main_2_3','(function)::substr'));
		$this->assertSame(array('main_2_3',false),core::main('module,action','','main_2_3','(function)::substr_x'));

		$this->assertSame(array('main_2_5','arr',array('a','b')),core::main('module,action,parameter','','main_2_5','arr','a,b'));
		$this->assertSame(array('main_2_5','arr',array(',','()')),core::main('module,action,parameter','','main_2_5','arr','(comma),(open_paren)(close_paren)'));
		$this->assertSame(array('main_2_5','arr',array('main_2_5-arr')),core::main('module,action,parameter','','main_2_5','arr','(module)-(action)'));
		$this->assertEquals(array('main_2_5','arr',array(null,'main_2_5-arr')),core::main('module,action,parameter','','main_2_5','arr',',(module)-(action)'));

		$this->assertSame(array('main_2_3',array(array('main_2_3','test_2_3'),array('main_2_5','index'))),core::main('module,action','','main_2_3',array('main_2_3::test_2_3','main_2_5::index')));
		$this->assertSame(array('main_2_3',array()),core::main('module,action','','main_2_3',array('main_2_3::test_2_3_x','main_2_5::index')));
		$this->assertSame(array('main_2_3',array(array('main_2_3','test_2_3'),array('main_2_5','index'))),core::main('module,action','','main_2_3',array('*|main_2_3::test_2_3','main_2_5::index')));
		$this->assertSame(array('main_2_3',array('',array('main_2_5','index'))),core::main('module,action','','main_2_3',array('*|main_2_3::test_2_3_x','main_2_5::index')));
		$this->assertSame(array('main_2_3',array('substr',array('main_2_5','index'))),core::main('module,action','','main_2_3',array('(function)::substr','main_2_5::index')));
		$this->assertSame(array('main_2_3',array(array('main_2_5','arr')),array(array())),core::main('module,action,parameter','','main_2_3',array('main_2_5::arr'),'1,2'));
		$this->assertSame(array('main_2_3',array(array('main_2_5','arr')),array(array('1','2'))),core::main('module,action,parameter','','main_2_3',array('main_2_5::arr'),array('1,2')));
		$this->assertSame(array('main_2_3',array(array('main_2_5','arr')),array(array())),core::main('module,action,parameter','','main_2_3',array('main_2_5::arr'),array(1=>'1,2')));
		$this->assertSame(array('main_2_3',array('substr',array('main_2_5','arr')),array(array('abc','1','1'),array('1','2'))),core::main('module,action,parameter','','main_2_3',array('(function)::substr','main_2_5::arr'),array('abc,1,1','1,2')));

		$this->assertSame('b',core::main('return','','main_2_3','(function)::substr','abc,1,1'));
		$this->assertSame(array('b',array('1','2')),core::main('return','','main_2_3',array('(function)::substr','main_2_5::arr'),array('abc,1,1','1,2')));
		$this->assertSame(array(null,array('1','2')),core::main('return','','main_2_3',array('*|(function)::aaa','main_2_5::arr'),array('abc,1,1','1,2')));
		$this->assertSame(array(),core::main('return','','main_2_3',array('(function)::aaa','main_2_5::arr'),array('abc,1,1','1,2')));

		//跳转功能
		require_once core::path('@tests/main_2_8.php');
		core::init(array('framework_function'=>'main_2_8::main','hide_info'=>'aaa'));
		ob_start();
		$result = core::main();
		$this->assertSame('mainaaa'.PHP_EOL, ob_get_clean());
		$this->assertSame('', core::init('framework_function'));
		core::init(array('framework_function'=>'','hide_info'=>''));

		core::init(array('framework_function'=>array('main_2_8::main1','main_2_8::main2'),'hide_info'=>'aaa'));
		ob_start();
		$result = core::main();
		$this->assertSame('main1main2aaa'.PHP_EOL, ob_get_clean());
		$this->assertSame('', core::init('framework_function'));
		core::init(array('framework_function'=>'','hide_info'=>''));


		//恢复原来值
		core::init(array(
			'framework_function'=>'',
			'framework_enable'=>'',
			'framework_require'=>'',
			'framework_module'=>'',
			'framework_action'=>'',
			'framework_parameter'=>'',
		));
		
		// 3. 【基础功能】模拟文件隐藏效果，返回true/false(框架/隐藏)。
		//验证返回
		//$this->assertTrue(core::main(true));
		ob_start();
		$this->assertFalse(core::main(''));
		ob_end_clean();
		//验证输出
		ob_start();
		$this->assertFalse(core::main());
		$this->assertSame('Could not open input file: '.basename($_SERVER ['SCRIPT_FILENAME']).PHP_EOL, ob_get_clean());
		//恢复原来值
		core::init(array(
			'hide_info'=>'aaa',
		));
		ob_start();
		$this->assertFalse(core::main());
		$this->assertSame('aaa'.PHP_EOL, ob_get_clean());
		core::init(array(
			'hide_info_cli'=>'bbb',
		));
		ob_start();
		$this->assertFalse(core::main());
		$this->assertSame('bbb'.PHP_EOL, ob_get_clean());
		//恢复原来值
		core::init(array(
			'hide_info'=>'',
			'hide_info_cli'=>'',
			'hidd_info_web'=>'',
		));
		
	}
	
	/**
	 * Tests core::path()
	 */
	public function testPath() {
		
		
		// 2. 【基础功能】返回转换路径，'@'开头相对核心文件路径。
		//不变
		$this->assertSame('test.php',core::path('test.php'));
		$this->assertSame('../test.php',core::path('../test.php'));
		//不变
		$this->assertSame('/test.php',core::path('/test.php'));
		$this->assertSame('\\test.php',core::path('\\test.php'));
		$this->assertSame('./test.php',core::path('./test.php'));
		$this->assertSame('.\\test.php',core::path('.\\test.php'));
		//转换
		$this->assertSame(realpath(dirname(__FILE__)).'/test.php',core::path('@tests/test.php'));
		
		// 3. 【基础功能】返回扩展路径，默认相对核心文件类名路径。
		//(1)初始化空值
		core::init(array(
			'extension_path'=>'',
		));
		//前缀
		$this->assertSame(realpath(dirname(__FILE__).'/../core').DIRECTORY_SEPARATOR.'test.php',core::path('test.php','extension'));
		$this->assertSame(realpath(dirname(__FILE__).'/../core').DIRECTORY_SEPARATOR.'../test.php',core::path('../test.php','extension'));
		//不变
		$this->assertSame('/test.php',core::path('/test.php','extension'));
		$this->assertSame('\\test.php',core::path('\\test.php','extension'));
		$this->assertSame('./test.php',core::path('./test.php','extension'));
		$this->assertSame('.\\test.php',core::path('.\\test.php','extension'));
		//转换
		$this->assertSame(realpath(dirname(__FILE__)).'/test.php',core::path('@tests/test.php','extension'));
		
		//(2)初始化转换值
		core::init(array(
			'extension_path'=>'@tests',
		));
		//前缀
		$this->assertSame(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'test.php',core::path('test.php','extension'));
		$this->assertSame(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'../test.php',core::path('../test.php','extension'));
		//不变
		$this->assertSame('/test.php',core::path('/test.php','extension'));
		$this->assertSame('\\test.php',core::path('\\test.php','extension'));
		$this->assertSame('./test.php',core::path('./test.php','extension'));
		$this->assertSame('.\\test.php',core::path('.\\test.php','extension'));
		//转换
		$this->assertSame(realpath(dirname(__FILE__)).'/test.php',core::path('@tests/test.php','extension'));
		
		//(3)初始化其他值
		core::init(array(
			'extension_path'=>dirname(__FILE__),
		));
		//前缀
		$this->assertSame(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'test.php',core::path('test.php','extension'));
		$this->assertSame(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'../test.php',core::path('../test.php','extension'));
		//不变
		$this->assertSame('/test.php',core::path('/test.php','extension'));
		$this->assertSame('\\test.php',core::path('\\test.php','extension'));
		$this->assertSame('./test.php',core::path('./test.php','extension'));
		$this->assertSame('.\\test.php',core::path('.\\test.php','extension'));
		//转换
		$this->assertSame(realpath(dirname(__FILE__)).'/test.php',core::path('@tests/test.php','extension'));
		
		//恢复原来值
		core::init(array(
			'extension_path'=>'',
		));
		
		// 4. 【基础功能】使用扩展类库。
		$include_path = get_include_path();
		$include_path_prepend = core::path('@tests/path_4','extension').PATH_SEPARATOR.$include_path;
		$include_path_append = $include_path.PATH_SEPARATOR.core::path('@tests/path_4','extension');
		core::init(array('extension_enable'=>true,'extension_path'=>'@tests/path_4'));
		$this->assertSame($include_path_append,get_include_path());
		$this->assertTrue(require 'path_4_1.php');
		core::init(array('extension_prepend'=>true));
		$this->assertSame($include_path_prepend,get_include_path());
		core::init(array('extension_prepend'=>''));
		$this->assertSame($include_path_prepend,get_include_path());
		core::init(array('extension_prepend'=>false));
		$this->assertSame($include_path_append,get_include_path());
		core::init(array('extension_prepend'=>''));
		$this->assertSame($include_path_append,get_include_path());
		core::init(array('extension_enable'=>'','extension_path'=>'','extension_prepend'=>''));
		
		core::init(array('extension_enable'=>true,'extension_path'=>'@tests/path_4','extension_prepend'=>true));
		$this->assertSame($include_path_prepend,get_include_path());
		core::init(array('extension_enable'=>'','extension_path'=>'','extension_prepend'=>''));
		
		core::init(array('extension_enable'=>'Foo','extension_path'=>'@tests/path_4'));
		$this->assertTrue(class_exists('Foo_Foo'));
		$this->assertTrue(class_exists('Foo_Foo_Foo',false));
		core::init(array('extension_enable'=>'','extension_path'=>'','extension_prepend'=>''));

		// 5. 【基础功能】其他类库测试
		foreach($this->lib_arr as $provider=>$lib_arr){
			for($i=0;$i<count($lib_arr);$i++){
				$this->assertFalse(class_exists($lib_arr[$i],false));
			}
			core::init(array('extension_enable'=>$provider));
			for($i=0;$i<count($lib_arr);$i++){
				$this->assertTrue(class_exists($lib_arr[$i]));
			}
			core::init(array('extension_enable'=>''));
		}

		// 6. 【基础功能】返回视图路径，默认相对于当前的程序路径。
		//(1)初始化空值
		core::init(array(
			'template_path'=>'aaa',
		));
		//前缀
		$this->assertSame('aaa'.DIRECTORY_SEPARATOR.'test.php',core::path('test.php','template'));
		$this->assertSame('aaa'.DIRECTORY_SEPARATOR.'../test.php',core::path('../test.php','template'));
		//不变
		$this->assertSame('/test.php',core::path('/test.php','template'));
		$this->assertSame('\\test.php',core::path('\\test.php','template'));
		$this->assertSame('./test.php',core::path('./test.php','template'));
		$this->assertSame('.\\test.php',core::path('.\\test.php','template'));
		//转换
		$this->assertSame(realpath(dirname(__FILE__)).'/test.php',core::path('@tests/test.php','template'));
		
		//(2)初始化转换值
		core::init(array(
			'template_path'=>'@tests',
		));
		//前缀
		$this->assertSame(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'test.php',core::path('test.php','template'));
		$this->assertSame(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'../test.php',core::path('../test.php','template'));
		//不变
		$this->assertSame('/test.php',core::path('/test.php','template'));
		$this->assertSame('\\test.php',core::path('\\test.php','template'));
		$this->assertSame('./test.php',core::path('./test.php','template'));
		$this->assertSame('.\\test.php',core::path('.\\test.php','template'));
		//转换
		$this->assertSame(realpath(dirname(__FILE__)).'/test.php',core::path('@tests/test.php','template'));
		
		//(3)初始化其他值
		core::init(array(
			'template_path'=>dirname(__FILE__),
		));
		//前缀
		$this->assertSame(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'test.php',core::path('test.php','template'));
		$this->assertSame(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'../test.php',core::path('../test.php','template'));
		//不变
		$this->assertSame('/test.php',core::path('/test.php','template'));
		$this->assertSame('\\test.php',core::path('\\test.php','template'));
		$this->assertSame('./test.php',core::path('./test.php','template'));
		$this->assertSame('.\\test.php',core::path('.\\test.php','template'));
		//转换
		$this->assertSame(realpath(dirname(__FILE__)).'/test.php',core::path('@tests/test.php','template'));
		
		//恢复原来值
		core::init(array(
			'template_path'=>'',
		));
		
	}
	
	/**
	 * Tests core::view()
	 */
	public function testView() {
		
		// 2. 【基础功能】【扩展功能】模板。
		foreach ( $this->view_arr as $provider=>$view_arr ) {
			for($i=0;$i<count($view_arr);$i++){
				$tpl = '@tests/view_'.$provider.'_'.($i+1).'_tpl.php';
				ob_start();
				require core::path('@tests/view_'.$provider.'_'.($i+1).'_rtn.php');
				$result = ob_get_clean();
				//仅显示
				ob_start();
				core::view(array('i'=>$i+1));
				core::view($tpl,$view_arr[$i],$provider);
				$this->assertSame($result, ob_get_clean());
				//仅返回
				$this->assertSame($result, core::view($tpl,$view_arr[$i],$provider,false));
				//设置值
				core::init(array(
					'template_search'=>'.tpl',
					'template_replace'=>'.php',
					'template_type'=>$provider,
				));
				$this->assertSame($result, core::view($tpl,$view_arr[$i],null,false));
				//恢复值
				core::init(array(
					'template_search'=>'',
					'template_replace'=>'',
					'template_type'=>'',
				));
			}
		}
		
	}
	
	/**
	 * Tests core::connect()
	 */
	public function testConnect() {
		
		// 1. 【基础功能】设置数据库参数，返回参数数组。
		$config = array(
			'connect_provider' => '',
			'connect_dsn' => '',
			'connect_type' => '',
			'connect_server' => '',
			'connect_username' => '',
			'connect_password' => '',
			'connect_new_link' => '',
			'connect_client_flags' => '',
			'connect_dbname' => '',
			'connect_charset' => '',
			'connect_port' => '',
			'connect_socket' => '',
			'connect_driver_options' => '',
			'prefix_search' => '',
			'prefix_replace' => '',
			'debug_enable' => '',
			'debug_file' => '',
			'sql_format' => '',
		);
		$config2 = $config;
		$config2['connect_username'] = 'ODBC';
		$config2['connect_new_link'] = true;

		$this->assertSame(array('current'=>0,'configs'=>array(array()),'connections'=>array(null)),core::connect());
		$this->assertNull(core::connect(0,$ref));
		$this->assertSame(array(),$ref);
		$this->assertSame(array('current'=>0,'configs'=>array(array()),'connections'=>array(null)),core::connect());
		$this->assertNull(core::connect(1,$ref));
		$this->assertSame(array(),$ref);
		$this->assertSame(array('current'=>1,'configs'=>array(array(),array()),'connections'=>array(null,null)),core::connect());
		$this->assertNull(core::connect(0,$ref));
		$this->assertSame(array(),$ref);
		$this->assertSame(array('current'=>0,'configs'=>array(array(),array()),'connections'=>array(null,null)),core::connect());
		$this->assertNull(core::connect($config,$ref));
		$this->assertSame($config,$ref);
		$this->assertSame(array('current'=>0,'configs'=>array($config,array()),'connections'=>array(null,null)),core::connect());
		$this->assertType('resource',core::connect(true,$ref));
		$this->assertSame($config,$ref);
		$this->assertSame(array('current'=>0,'configs'=>array($config,array()),'connections'=>array(core::connect(true),null)),core::connect());
		$this->assertNull(core::connect(false,$ref));
		$this->assertSame(array(),$ref);
		$this->assertSame(array('current'=>0,'configs'=>array(array(),array()),'connections'=>array(null,null)),core::connect());
		$this->assertNull(core::connect(1,$ref));
		$this->assertSame(array(),$ref);
		$this->assertSame(array('current'=>1,'configs'=>array(array(),array()),'connections'=>array(null,null)),core::connect());
		core::init($config2);
		$this->assertSame(array('current'=>1,'configs'=>array(array(),array()),'connections'=>array(null,null)),core::connect());
		$this->assertType('resource',core::connect(true,$ref));
		$this->assertSame($config2,$ref);
		$this->assertSame(array('current'=>1,'configs'=>array(array(),$config2),'connections'=>array(null,core::connect(true))),core::connect());
		$this->assertNull(core::connect(false,$ref));
		$this->assertSame(array(),$ref);
		$this->assertSame(array('current'=>1,'configs'=>array(array(),array()),'connections'=>array(null,null)),core::connect());
		$this->assertNull(core::connect(0,$ref));
		$this->assertSame(array(),$ref);
		$this->assertSame(array('current'=>0,'configs'=>array(array(),array()),'connections'=>array(null,null)),core::connect());
		$this->assertNull(core::connect($config2,$ref));
		$this->assertSame($config2,$ref);
		$this->assertSame(array('current'=>0,'configs'=>array($config2,array()),'connections'=>array(null,null)),core::connect());
		$this->assertType('resource',core::connect(true,$ref));
		$this->assertSame($config2,$ref);
		$this->assertSame(array('current'=>0,'configs'=>array($config2,array()),'connections'=>array(core::connect(true),null)),core::connect());
		$this->assertNull(core::connect(false,$ref));
		$this->assertSame(array(),$ref);
		$this->assertSame(array('current'=>0,'configs'=>array(array(),array()),'connections'=>array(null,null)),core::connect());
		
		// 3. 【基础功能】【扩展功能】连接数据库、断开数据库。
		foreach ( $this->db_arr as $provider=>$db_arr ) {
			list($arr,$con,$cls) = $db_arr;
			core::connect($arr);
			$this->assertSame($con,is_resource($result = core::connect(true))?'resource':get_class($result));
			$this->assertNull(core::connect(false));
		}
		
	}
	
	/**
	 * Tests core::execute()
	 */
	public function testExecute() {
		
		// 1. 【基础功能】【扩展功能】执行SQL语句，返回结果集。
		foreach ( $this->db_arr as $provider=>$db_arr ) {
			list($arr,$con,$cls) = $db_arr;
			core::connect($arr);
			$this->assertSame($cls, is_resource($result = core::execute("SELECT 1"))?'resource':get_class($result));
			$this->assertSame($cls, is_resource($result = core::execute("SELECT ?",array('2')))?'resource':get_class($result));
			$this->assertSame($cls, is_resource($result = core::execute("SELECT 1",null,$ref))?'resource':get_class($result));
			$this->assertSame(1, $ref['num_rows']);
			$this->assertSame($cls, is_resource($result = core::execute("SELECT ? UNION select ?",array(1,2),$ref))?'resource':get_class($result));
			$this->assertSame(1, $ref['num_fields']);
			$this->assertSame(2, $ref['num_rows']);
			core::execute("DROP TABLE IF EXISTS pre1_test");
			core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
			core::execute("INSERT INTO pre1_test (name) VALUES ('a')",null,$ref);
			$this->assertSame('1', $ref['insert_id']);
			$this->assertSame(1, $ref['affected_rows']);
			core::execute("DROP TABLE pre1_test");

			core::connect(array('debug_enable'=>true));

			ob_start();
			$this->assertSame($cls, is_resource($result = core::execute("SELECT 1"))?'resource':get_class($result));
			$this->assertSame($cls, is_resource($result = core::execute("SELECT ?",array('2')))?'resource':get_class($result));
			$this->assertSame($cls, is_resource($result = core::execute("SELECT 1",null,$ref))?'resource':get_class($result));
			$this->assertSame(1, $ref['num_rows']);
			$this->assertSame($cls, is_resource($result = core::execute("SELECT ? UNION select ?",array(1,2),$ref))?'resource':get_class($result));
			$this->assertSame(1, $ref['num_fields']);
			$this->assertSame(2, $ref['num_rows']);
			core::execute("DROP TABLE IF EXISTS pre1_test");
			core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
			core::execute("INSERT INTO pre1_test (name) VALUES ('a')",null,$ref);
			$this->assertSame('1', $ref['insert_id']);
			$this->assertSame(1, $ref['affected_rows']);
			core::execute("DROP TABLE pre1_test");
			ob_get_clean();

			core::connect(array('debug_file'=>$this->log_file));
			@unlink($this->log_file);
			$this->assertSame($cls, is_resource($result = core::execute("SELECT 1"))?'resource':get_class($result));
			$this->assertSame($cls, is_resource($result = core::execute("SELECT ?",array('2')))?'resource':get_class($result));
			$this->assertSame($cls, is_resource($result = core::execute("SELECT 1",null,$ref))?'resource':get_class($result));
			$this->assertSame(1, $ref['num_rows']);
			$this->assertSame($cls, is_resource($result = core::execute("SELECT ? UNION select ?",array(1,2),$ref))?'resource':get_class($result));
			$this->assertSame(1, $ref['num_fields']);
			$this->assertSame(2, $ref['num_rows']);
			core::execute("DROP TABLE IF EXISTS pre1_test");
			core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
			core::execute("INSERT INTO pre1_test (name) VALUES ('a')",null,$ref);
			$this->assertSame('1', $ref['insert_id']);
			$this->assertSame(1, $ref['affected_rows']);
			core::execute("DROP TABLE pre1_test");
			@unlink($this->log_file);
			core::connect(array('debug_file'=>''));

			ob_start();
			core::execute('SELECT 1');
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): SELECT 1'.PHP_EOL,ob_get_clean());

			ob_start();
			core::execute('SELECT ?,?',array(1,'a'));
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): SELECT ?,?'.PHP_EOL.'#0: int(1)'.PHP_EOL.'#1: string(1) a'.PHP_EOL,ob_get_clean());

			core::connect(array('sql_format'=>true));
			ob_start();
			core::execute('SELECT ?,?',array(1,'a'));
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): SELECT 1,\'a\''.PHP_EOL,ob_get_clean());
			core::connect(array('sql_format'=>''));

			core::connect(array('debug_file'=>$this->log_file));
			@unlink($this->log_file);
			core::execute('SELECT 1');
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): SELECT 1'.PHP_EOL,file_get_contents($this->log_file));
			@unlink($this->log_file);
			core::connect(array('debug_file'=>''));

			ob_start();
			core::execute('SELECT aaa');
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): SELECT aaa'.PHP_EOL.'1054: Unknown column \'aaa\' in \'field list\''.PHP_EOL,ob_get_clean());
			ob_start();
			core::execute('SELECT aaa',null,$ref);
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): SELECT aaa'.PHP_EOL.'1054: Unknown column \'aaa\' in \'field list\''.PHP_EOL,ob_get_clean());
			$this->assertSame(array('insert_id' => '','affected_rows' => 0,'num_fields' => 0,'num_rows' => 0),$ref);
			core::connect(false);
		}
		
	}

	/**
	 * Tests core::prepare()
	 */
	public function testPrepare() {
		
		// 1. 【基础功能】准备SQL语句。
		$input = array('a1','b1'=>'b','b2'=>100,'b3'=>null,'c1'=>array('c',100,null),'d1 ?'=>'d','d2 ?'=>100,'d3 ?'=>null,'e1 ?,?,?'=>array('e',100,null),'f1'=>array());
		$this->assertSame(array(
			array('a1','?','?','?','CONCAT_WS(\',\',?,?,?)','d1 ?','d2 ?','d3 ?','e1 ?,?,?'),
			array('b',100,null,'c',100,null,'d',100,null,'e',100,null)
			),core::prepare('mysql_escape_value',$input));
		$this->assertSame(array(
			array('a1','b1=?','b2=?','b3=?','c1 IN (?,?,?)','d1 ?','d2 ?','d3 ?','e1 ?,?,?'),
			array('b',100,null,'c',100,null,'d',100,null,'e',100,null)
			),core::prepare('mysql_escape_where',$input));
		$this->assertSame(array(
			array('a1','b1 ?','b2 ?','b3 ?','c1 ?,?,?','d1 ?','d2 ?','d3 ?','e1 ?,?,?'),
			array('b',100,null,'c',100,null,'d',100,null,'e',100,null)
			),core::prepare('mysql_escape_other',$input));
		$this->assertSame(
			array('a1','\'b\'','100','NULL','CONCAT_WS(\',\',\'c\',100,NULL)','d1 \'d\'','d2 100','d3 NULL','e1 \'e\',100,NULL'),
			core::prepare('mysql_escape_value',$input,true));
		$this->assertSame(
			array('a1','b1=\'b\'','b2=100','b3=NULL','c1 IN (\'c\',100,NULL)','d1 \'d\'','d2 100','d3 NULL','e1 \'e\',100,NULL'),
			core::prepare('mysql_escape_where',$input,true));
		$this->assertSame(
			array('a1','b1 \'b\'','b2 100','b3 NULL','c1 \'c\',100,NULL','d1 \'d\'','d2 100','d3 NULL','e1 \'e\',100,NULL'),
			core::prepare('mysql_escape_other',$input,true));
		$this->assertSame(
			array("'\\\\'","'\\0'","'\\n'","'\\r'","'\\''", "'\\\"'","'\\Z'"),
			core::prepare('mysql_escape_value',array('a'=>"\\",'b'=>"\x00",'c'=>"\n",'d'=>"\r",'e'=>"'",'f'=>"\"",'g'=>"\x1a"),true));

		$this->assertSame(array('',array()), core::prepare('mysql_field'));
		$this->assertSame(array('a',array()), core::prepare('mysql_field','a'));
		$this->assertSame(array('',array()), core::prepare('mysql_field',array()));
		$this->assertSame(array('a',array()), core::prepare('mysql_field',array('a')));
		$this->assertSame(array('a,b',array()), core::prepare('mysql_field',array('a','b')));
		$this->assertSame(array('a b',array()), core::prepare('mysql_field',array(array('a','b'))));
		$this->assertSame(array('a b c,d',array()), core::prepare('mysql_field',array(array('a','b'),'c','d')));
		$this->assertSame(array('a,b,c d',array()), core::prepare('mysql_field',array('a','b',array('c','d'))));
		$this->assertSame(array('',array()), core::prepare('mysql_table'));
		$this->assertSame(array('a',array()), core::prepare('mysql_table','a'));
		$this->assertSame(array('',array()), core::prepare('mysql_table',array()));
		$this->assertSame(array('a',array()), core::prepare('mysql_table',array('a')));
		$this->assertSame(array('a,b',array()), core::prepare('mysql_table',array('a','b')));
		$this->assertSame(array('a b',array()), core::prepare('mysql_table',array(array('a','b'))));
		$this->assertSame(array('a b c,d',array()), core::prepare('mysql_table',array(array('a','b'),'c','d')));
		$this->assertSame(array('a,b,c d',array()), core::prepare('mysql_table',array('a','b',array('c','d'))));
		$this->assertSame(array('',array()), core::prepare('mysql_column'));
		$this->assertSame(array('(a)',array()), core::prepare('mysql_column','a'));
		$this->assertSame(array('',array()), core::prepare('mysql_column',array()));
		$this->assertSame(array('(a)',array()), core::prepare('mysql_column',array('a')));
		$this->assertSame(array('(a,b)',array()), core::prepare('mysql_column',array('a','b')));
		$this->assertSame(array('',array()), core::prepare('mysql_set'));
		$this->assertSame(array('a',array()), core::prepare('mysql_set','a'));
		$this->assertSame(array('',array()), core::prepare('mysql_set',array()));
		$this->assertSame(array('a',array()), core::prepare('mysql_set',array('a')));
		$this->assertSame(array('a,b',array()), core::prepare('mysql_set',array('a','b')));
		$this->assertSame(array(
			'a1,b1=?,b2=?,b3=?,c1=CONCAT_WS(\',\',?,?,?),d1 ?,d2 ?,d3 ?,e1 ?,?,?',
			array('b',100,null,'c',100,null,'d',100,null,'e',100,null)
			),core::prepare('mysql_set',$input));
		$this->assertSame(
			'a1,b1=\'b\',b2=100,b3=NULL,c1=CONCAT_WS(\',\',\'c\',100,NULL),d1 \'d\',d2 100,d3 NULL,e1 \'e\',100,NULL',
			core::prepare('mysql_set',$input,true));
		$this->assertSame(array('',array()), core::prepare('mysql_value'));
		$this->assertSame(array('(a)',array()), core::prepare('mysql_value','a'));
		$this->assertSame(array('',array()), core::prepare('mysql_value',array()));
		$this->assertSame(array('(a)',array()), core::prepare('mysql_value',array('a')));
		$this->assertSame(array('(a,b)',array()), core::prepare('mysql_value',array('a','b')));
		$this->assertSame(array(
			'(a1,?,?,?,CONCAT_WS(\',\',?,?,?),d1 ?,d2 ?,d3 ?,e1 ?,?,?)',
			array('b',100,null,'c',100,null,'d',100,null,'e',100,null)
			),core::prepare('mysql_value',$input));
		$this->assertSame(
			'(a1,\'b\',100,NULL,CONCAT_WS(\',\',\'c\',100,NULL),d1 \'d\',d2 100,d3 NULL,e1 \'e\',100,NULL)',
			core::prepare('mysql_value',$input,true));
		$this->assertSame(
			'(a1,\'b\',100,NULL,CONCAT_WS(\',\',\'c\',100,NULL),d1 \'d\',d2 100,d3 NULL,e1 \'e\',100,NULL),'.
			'(a1,\'b\',100,NULL,CONCAT_WS(\',\',\'c\',100,NULL),d1 \'d\',d2 100,d3 NULL,e1 \'e\',100,NULL)',
			core::prepare('mysql_value',array($input,$input),true));
		$this->assertSame(array('',array()), core::prepare('mysql_where'));
		$this->assertSame(array('a',array()), core::prepare('mysql_where','a'));
		$this->assertSame(array('',array()), core::prepare('mysql_where',array()));
		$this->assertSame(array('a',array()), core::prepare('mysql_where',array('a')));
		$this->assertSame(array('a AND b',array()), core::prepare('mysql_where',array('a','b')));
		$this->assertSame(array(
			'a1 AND b1=? AND b2=? AND b3=? AND c1 IN (?,?,?) AND d1 ? AND d2 ? AND d3 ? AND e1 ?,?,?',
			array('b',100,null,'c',100,null,'d',100,null,'e',100,null)
			),core::prepare('mysql_where',$input));
		$this->assertSame(
			'a1 AND b1=\'b\' AND b2=100 AND b3=NULL AND c1 IN (\'c\',100,NULL) AND d1 \'d\' AND d2 100 AND d3 NULL AND e1 \'e\',100,NULL',
			core::prepare('mysql_where',$input,true));
		$this->assertSame(array('',array()), core::prepare('mysql_where2'));
		$this->assertSame(array('(a)',array()), core::prepare('mysql_where2','a'));
		$this->assertSame(array('',array()), core::prepare('mysql_where2',array()));
		$this->assertSame(array('(a)',array()), core::prepare('mysql_where2',array('a')));
		$this->assertSame(array('(a OR b)',array()), core::prepare('mysql_where2',array('a','b')));
		$this->assertSame(array('',array()), core::prepare('mysql_where',array(array())));
		$this->assertSame(array('(a)',array()), core::prepare('mysql_where',array(array('a'))));
		$this->assertSame(array('(a OR b)',array()), core::prepare('mysql_where',array(array('a','b'))));
		$this->assertSame(array('(a OR b) AND c',array()), core::prepare('mysql_where',array(array('a','b'),'c')));
		$this->assertSame(array('(a OR b OR c AND d) AND e',array()), core::prepare('mysql_where',array(array('a','b',array('c','d')),'e')));
		$this->assertSame(array('',array()), core::prepare('mysql_other'));
		$this->assertSame(array('a',array()), core::prepare('mysql_other','a'));
		$this->assertSame(array('',array()), core::prepare('mysql_other',array()));
		$this->assertSame(array('a',array()), core::prepare('mysql_other',array('a')));
		$this->assertSame(array('a b',array()), core::prepare('mysql_other',array('a','b')));
		$this->assertSame(array(
			'a1 b1 ? b2 ? b3 ? c1 ?,?,? d1 ? d2 ? d3 ? e1 ?,?,?',
			array('b',100,null,'c',100,null,'d',100,null,'e',100,null)
			),core::prepare('mysql_other',$input));
		$this->assertSame(
			'a1 b1 \'b\' b2 100 b3 NULL c1 \'c\',100,NULL d1 \'d\' d2 100 d3 NULL e1 \'e\',100,NULL',
			core::prepare('mysql_other',$input,true));

		// 2. 【基础功能】【扩展功能】准备SQL语句。
		foreach ( $this->db_arr as $provider=>$db_arr ) {
			list($arr,$con,$cls) = $db_arr;
			core::connect($arr);
			$this->assertSame(array('INSERT tbl (col1,col2) VALUES (DEFAULT,DEFAULT)',array()), core::prepare('inserts',array('tbl','col1,col2','DEFAULT,DEFAULT')));
			$this->assertSame(array('INSERT tbl (col1,col2) VALUES (DEFAULT,?)',array('txt1')), core::prepare('inserts',array('tbl',array('col1','col2'),array('DEFAULT','col2'=>'txt1'))));
			$this->assertSame(array('INSERT tbl  VALUES (DEFAULT,?),(DEFAULT,?)',array('txt1','txt2')), 
				core::prepare('inserts',array('tbl',null,array(array('DEFAULT','col2'=>'txt1'),array('DEFAULT','col2'=>'txt2')))));
			$this->assertSame(array('INSERT tbl (col1,col2) SELECT ...',array()), core::prepare('inserts',array('tbl',array('col1','col2'),null,'SELECT ...')));
			$this->assertSame(array('INSERT tbl SET col1=DEFAULT,col2=?',array('txt1')), core::prepare('inserts',array('tbl',array('col1=DEFAULT','col2'=>'txt1'))));
			$this->assertSame('INSERT tbl SET col1=DEFAULT,col2=\'txt1\'', core::prepare('inserts',array('tbl',array('col1=DEFAULT','col2'=>'txt1')),true));
			$this->assertSame(array('UPDATE tbl SET col1=DEFAULT,col2=?',array('txt1')), core::prepare('updates',array('tbl',array('col1=DEFAULT','col2'=>'txt1'))));
			$this->assertSame(array('UPDATE tbl SET col1=DEFAULT,col2=? WHERE col3=?',array('txt1','txt2')), 
				core::prepare('updates',array('tbl',array('col1=DEFAULT','col2'=>'txt1'),array('col3'=>'txt2'))));
			$this->assertSame('UPDATE tbl SET col1=DEFAULT,col2=\'txt1\' WHERE col3=\'txt2\'', 
				core::prepare('updates',array('tbl',array('col1=DEFAULT','col2'=>'txt1'),array('col3'=>'txt2')),true));
			$this->assertSame(array('UPDATE tbl SET col1=DEFAULT,col2=? WHERE col3=? AND col4=? AND (col5=? OR col6=?)',array('txt1','txt2','txt3','txt4','txt5')), 
				core::prepare('updates',array('tbl',array('col1=DEFAULT','col2'=>'txt1'),array('col3'=>'txt2','col4'=>'txt3',array('col5'=>'txt4','col6'=>'txt5')))));
			$this->assertSame('UPDATE tbl SET col1=DEFAULT,col2=\'txt1\' WHERE col3=\'txt2\' AND col4=\'txt3\' AND (col5=0 OR col6 IS NULL)', 
				core::prepare('updates',array('tbl',array('col1=DEFAULT','col2'=>'txt1'),array('col3'=>'txt2','col4'=>'txt3',array('col5'=>false,'col6 IS NULL'))),true));
			$this->assertSame(array('DELETE  FROM tbl WHERE col3=? AND col4=? AND (col5=? OR col6=?)',array('txt2','txt3','txt4','txt5')), 
				core::prepare('deletes',array(null,'tbl',array('col3'=>'txt2','col4'=>'txt3',array('col5'=>'txt4','col6'=>'txt5')))));
			$this->assertSame('DELETE  FROM tbl WHERE col3=\'txt2\' AND col4=\'txt3\' AND (col5=0 OR col6 IS NULL)', 
				core::prepare('deletes',array(null,'tbl',array('col3'=>'txt2','col4'=>'txt3',array('col5'=>false,'col6 IS NULL'))),true));
			$this->assertSame(array('SELECT * FROM tbl',array()), 
				core::prepare('selects',array(null,'tbl',null,array('page'=>array('page'=>1,'size'=>2,'total'=>3,'count'=>4)))));
			$this->assertSame(array('SELECT a,c AS b,100,NULL,1,0 LIMIT ?,?',array(10,20)), 
				core::prepare('selects',array(array('a','b'=>'c',100,null,true,false),null,null,array('LIMIT'=>array(10,20)))));
			$this->assertSame('SELECT a,c AS b,100,NULL,1,0 LIMIT 10,20', 
				core::prepare('selects',array(array('a','b'=>'c',100,null,true,false),null,null,array('LIMIT'=>array(10,20))),true));
			$this->assertSame(array('SELECT a,c AS b,100,NULL,1,0 FROM tbl LIMIT ?,?',array(10,20)), 
				core::prepare('selects',array(array('a','b'=>'c',100,null,true,false),array('tbl'),null,array('LIMIT'=>array(10,20)))));
			$this->assertSame('SELECT a,c AS b,100,NULL,1,0 FROM tbl LIMIT 10,20', 
				core::prepare('selects',array(array('a','b'=>'c',100,null,true,false),array('tbl'),null,array('LIMIT'=>array(10,20))),true));
			$this->assertSame(array('SELECT a,c AS b,100,NULL,1,0 FROM tbl WHERE a=? AND c LIKE ? AND e AND 1 LIMIT ?,?',array('b','d',10,20)), 
				core::prepare('selects',array(array('a','b'=>'c',100,null,true,false),array('tbl'),array('a'=>'b','c LIKE ?'=>'d','e',true),array('LIMIT'=>array(10,20)))));
			$this->assertSame('SELECT a,c AS b,100,NULL,1,0 FROM tbl WHERE a=\'b\' AND c LIKE \'d\' AND e AND 1 LIMIT 10,20', 
				core::prepare('selects',array(array('a','b'=>'c',100,null,true,false),array('tbl'),array('a'=>'b','c LIKE ?'=>'d','e',true),array('LIMIT'=>array(10,20))),true));
			core::connect(false);
		}
		
		// 3. 【基础功能】【扩展功能】调试SQL语句。
		foreach ( $this->db_arr as $provider=>$db_arr ) {
			list($arr,$con,$cls) = $db_arr;
			core::connect($arr);
			ob_start();
			core::prepare('SELECT aaa',null,null,true);
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): SELECT aaa'.PHP_EOL,ob_get_clean());
			ob_start();
			core::prepare('SELECT ?',array('aaa'),null,true);
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): SELECT ?'.PHP_EOL.'#0: string(3) aaa'.PHP_EOL,ob_get_clean());
			ob_start();
			core::prepare('SELECT ?',array('aaa'),true,true);
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): SELECT \'aaa\''.PHP_EOL,ob_get_clean());
			ob_start();
			core::prepare('SELECT aaa',null,null,true, null,array('errno'=>1000,'error'=>'bbb'));
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): SELECT aaa'.PHP_EOL.'1000: bbb'.PHP_EOL,ob_get_clean());
			@unlink($this->log_file);
			core::prepare('SELECT aaa',null,null,true, $this->log_file);
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): SELECT aaa'.PHP_EOL,file_get_contents($this->log_file));
			@unlink($this->log_file);
			core::connect(false);
		}
	
	}

	/**
	 * Tests core::sequence()
	 */
	public function testSequence() {
		
		// 1. 【基础功能】【扩展功能】生成指定自增序列，返回序列号。
		foreach ( $this->db_arr as $provider=>$db_arr ) {
			list($arr,$con,$cls) = $db_arr;
			core::connect($arr);
			core::execute("DROP TABLE IF EXISTS sequence");
			$this->assertSame(1, core::sequence());
			$this->assertSame(2, core::sequence());
			core::execute("DROP TABLE sequence");
			core::execute("DROP TABLE IF EXISTS pre_sequence");
			$this->assertSame(2, core::sequence('pre_sequence',2));
			$this->assertSame(3, core::sequence('pre_sequence',2));
			$this->assertSame(9, core::sequence('pre_sequence',9));
			$this->assertSame(10, core::sequence('pre_sequence',10));
			core::execute("DROP TABLE pre_sequence");
			core::connect(false);
		}
	
	}
	
	/**
	 * Tests core::structs()
	 */
	public function testStructs() {
		
		require_once 'test.php';
		
		// 1. 【基础功能】返回实例数组。
		$arr1 = array('b','d');
		$arr2 = array('b','d','f');
		$arr3 = array('a'=>'b','c'=>'d');
		$arr4 = array('a'=>'b','c'=>'d','e'=>'f');
		$arr5 = array('a'=>'b','b','c'=>'d','d');
		$arr6 = array('a'=>'b','b','c'=>'d','d','e'=>'f','f');
		$arr7 = array('a'=>'b','b','c'=>'d','d','f');
		$arr8 = array('a'=>'','','c'=>'','');
		$arr9 = array('class'=>'core','a'=>'b','c'=>'d');
		$arr10 = array('class'=>'test','a'=>'b','c'=>'d');
		$obj1 = new core;
		$obj1->a = 'b';
		$obj1->c = 'd';
		$obj2 = new core;
		$obj2->a = 'b';
		$obj2->c = 'd';
		$obj2->e = 'f';
		$obj3 = new test;
		$obj3->a = 'b';
		$obj3->c = 'd';
		$obj4 = new test;
		$obj4->a = 'b';
		$obj4->c = 'd';
		$obj4->e = 'f';
		//基本型
		$this->assertEquals(array($arr3,$arr4), core::structs(array($arr7,$arr6),array(null, 'assoc'=>null)) );
		$this->assertEquals(array($arr2,$arr2), core::structs(array($arr7,$arr6),array(null, 'num'=>null)) );
		$this->assertEquals(array($arr5,$arr6), core::structs(array($arr7,$arr6),array(null, 'both'=>null)) );
		$this->assertEquals(array($arr5,$arr5), core::structs(array($arr7,$arr6),array(null, 'array'=>$arr8)) );
		$this->assertEquals(array('d','d'), core::structs(array($arr7,$arr6),array(null, 'column'=>1)) );
		$this->assertEquals(array('d','d'), core::structs(array($arr7,$arr6),array(null, 'column'=>'c')) );
		if(function_exists('get_called_class')){
			$this->assertEquals(array($obj3,$obj4), test::structs(array($arr7,$arr6),array(null, 'class'=>null)) );
		}else{
			$this->assertEquals(array($obj1,$obj2), test::structs(array($arr7,$arr6),array(null, 'class'=>null)) );
		}
		$this->assertEquals(array($obj3,$obj4), core::structs(array($arr7,$arr6),array(null, 'class'=>'test')) );
		$this->assertEquals(array($obj1), core::structs(array($arr9),array(null, 'class|classtype'=>null)) );
		$this->assertEquals(array($obj3,$obj4), core::structs(array($arr7,$arr6),array(null, 'clone'=>new test)) );
		//默认型
		if(function_exists('get_called_class')){
			$this->assertEquals(array($obj3,$obj4), test::structs(array($arr7,$arr6),null) );
		}else{
			$this->assertEquals(array($obj1,$obj2), test::structs(array($arr7,$arr6),null) );
		}
		$this->assertEquals(array($obj3,$obj4), core::structs(array($arr7,$arr6),'test') );
		$this->assertEquals(array($obj3,$obj4), core::structs(array($arr7,$arr6),new test) );
		//扩展型
		$this->assertEquals(array('f'=>$obj4), core::structs(array($arr7,$arr6),array(2, 'class'=>'test')) );
		$this->assertEquals(array(''=>$obj3,'f'=>$obj4), core::structs(array($arr7,$arr6),array('e', 'class'=>'test')) );
		$this->assertEquals(array('f'=>array($obj3,$obj4)), core::structs(array($arr7,$arr6),array(2, null, 'class'=>'test')) );
		$this->assertEquals(array(array('f'=>$obj3),array('f'=>$obj4)), core::structs(array($arr7,$arr6),array(null, 2, 'class'=>'test')) );
		$this->assertEquals('d', core::structs(array($arr7,$arr6),array('column'=>1)) );
		
	}
	
	/**
	 * Tests core::selects()
	 */
	public function testSelects() {
		
		require_once 'test.php';
		
		// 1. 【基础功能】【扩展功能】选择对象数据。
		foreach ( $this->db_arr as $provider=>$db_arr ) {
			list($arr,$con,$cls) = $db_arr;
			$arr['prefix_search'] = 'pre_';
			$arr['prefix_replace'] = 'pre1_';
			core::connect($arr);
			core::execute("DROP TABLE IF EXISTS pre1_test");
			core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
			core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'core'),(2,'test'),(3,'test')");
			$arr1 = array('id'=>1,1,'name'=>'core','core');
			$arr2 = array('id'=>2,2,'name'=>'test','test');
			$arr3 = array('id'=>3,3,'name'=>'test','test');
			$arr4 = array('id'=>null,null,null,'other'=>'xxx');
			$arr41 = array('id'=>1,1,'core','other'=>'xxx');
			$arr42 = array('id'=>2,2,'test','other'=>'xxx');
			$arr43 = array('id'=>3,3,'test','other'=>'xxx');
			$obj1 = new core;
			$obj1->id = 1;
			$obj1->name = 'core';
			$obj2 = new core;
			$obj2->id = 2;
			$obj2->name = 'test';
			$obj3 = new core;
			$obj3->id = 3;
			$obj3->name = 'test';
			$test1 = new test;
			$test1->id = 1;
			$test1->name = 'core';
			$test2 = new test;
			$test2->id = 2;
			$test2->name = 'test';
			$test3 = new test;
			$test3->id = 3;
			$test3->name = 'test';
			$obj1a = new core;
			$obj1a->id = 1;
			$test2a = new test;
			$test2a->id = 2;
			$test3a = new test;
			$test3a->id = 3;

			if(function_exists('get_called_class')){
				$this->assertEquals(array($test1,$test2,$test3),test::selects(null,'pre_test'));
			}
			$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,'pre_test'));
			$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,'pre_test',null,null,'core'));
			$this->assertEquals(array($test1,$test2,$test3),core::selects(null,'pre_test',null,null,'test'));
			$this->assertEquals(array(array('id'=>1,'name'=>'core'),array('id'=>2,'name'=>'test'),array('id'=>3,'name'=>'test')),
				core::selects(null,'pre_test',null,null,array(null,'assoc'=>null)));
			$this->assertEquals(array(array(1,'core'),array(2,'test'),array(3,'test')),
				core::selects(null,'pre_test',null,null,array(null,'num'=>null)));
			$this->assertEquals(array($arr1,$arr2,$arr3),core::selects(null,'pre_test',null,null,array(null,'both'=>null)));
			$this->assertEquals(array($arr1,$arr2,$arr3),core::selects(null,'pre_test',null,null,array(null,'array'=>null)));
			//$this->assertEquals(array($arr41,$arr42,$arr43),core::selects(null,'pre_test',null,null,array(null,'array'=>$arr4)));
			$this->assertEquals(array('core','test','test'),core::selects(null,'pre_test',null,null,array(null,'column'=>1)));
			$this->assertEquals(array('core','test','test'),core::selects(null,'pre_test',null,null,array(null,'column'=>'name')));
			$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,'pre_test',null,null,array(null,'class'=>null)));
			$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,'pre_test',null,null,array(null,'class'=>'core')));
			$this->assertEquals(array($test1,$test2,$test3),core::selects(null,'pre_test',null,null,array(null,'class'=>'test')));
			$this->assertEquals(array($obj1a,$test2a,$test3a),core::selects('name,id','pre_test',null,null,array(null,'class|classtype'=>null)));
			$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,'pre_test',null,null,array(null,'clone'=>new core)));
			$this->assertEquals(array($test1,$test2,$test3),core::selects(null,'pre_test',null,null,array(null,'clone'=>new test)));

			$this->assertEquals(array('id'=>3,'name'=>'test'),core::selects(null,'pre_test',null,null,array('assoc'=>null)));
			$this->assertEquals(array(3,'test'),core::selects(null,'pre_test',null,null,array('num'=>null)));
			$this->assertEquals($arr3,core::selects(null,'pre_test',null,null,array('both'=>null)));
			$this->assertEquals('test',core::selects(null,'pre_test',null,null,array('column'=>1)));
			$this->assertEquals('test',core::selects(null,'pre_test',null,null,array('column'=>'name')));
			$this->assertEquals($obj3,core::selects(null,'pre_test',null,null,array('class'=>null)));
			$this->assertEquals($obj3,core::selects(null,'pre_test',null,null,array('class'=>'core')));
			$this->assertEquals($test3,core::selects(null,'pre_test',null,null,array('class'=>'test')));
			$this->assertEquals($test3a,core::selects('name,id','pre_test',null,null,array('class|classtype'=>null)));
			$this->assertEquals($obj3,core::selects(null,'pre_test',null,null,array('clone'=>new core)));
			$this->assertEquals($test3,core::selects(null,'pre_test',null,null,array('clone'=>new test)));

			$this->assertEquals(array(array(array('id'=>1,'name'=>'core')),array(array('id'=>2,'name'=>'test')),array(array('id'=>3,'name'=>'test'))),
				core::selects(null,'pre_test',null,null,array(null,null,'assoc'=>null)));
			$this->assertEquals(array('core'=>array(1,'core'),'test'=>array(3,'test')),
				core::selects(null,'pre_test',null,null,array(1,'num'=>null)));
			$this->assertEquals(array('core'=>array($arr1),'test'=>array($arr2,$arr3)),
				core::selects(null,'pre_test',null,null,array('name',null,'both'=>null)));
			$this->assertEquals(array(1=>array('core'=>'core'),2=>array('test'=>'test'),3=>array('test'=>'test')),
				core::selects(null,'pre_test',null,null,array(0,1,'column'=>1)));
			$this->assertEquals(array('core'=>array(1=>'core'),'test'=>array(2=>'test',3=>'test')),
				core::selects(null,'pre_test',null,null,array('name','id','column'=>'name')));
			$this->assertEquals(array('core'=>array(1=>$obj1),'test'=>array(2=>$obj2,3=>$obj3)),
				core::selects(null,'pre_test',null,null,array('name',0,'class'=>null)));
			$this->assertEquals(array(''=>$obj3),core::selects(null,'pre_test',null,null,array('name1','class'=>'core')));
			$this->assertEquals(array(''=>array($test1,$test2,$test3)),core::selects(null,'pre_test',null,null,array('name1',null,'class'=>'test')));
			$this->assertEquals(array('core'=>array(1=>array($obj1a)),'test'=>array(2=>array($test2a),3=>array($test3a))),
				core::selects('name,id','pre_test',null,null,array('name','id','','class|classtype'=>null)));
			$this->assertEquals(array(''=>array('core'=>$obj1,'test'=>$obj3)),
				core::selects(null,'pre_test',null,null,array('id1','name','clone'=>new core)));
			$this->assertEquals(array('core'=>array(''=>$test1),'test'=>array(''=>$test3)),
				core::selects(null,'pre_test',null,null,array('name','id1','clone'=>new test)));

			$this->assertEquals(array($test1,$test2,$test3),core::selects(null,null,null,null,'test'));
			$this->assertEquals(array($test1,$test2,$test3),core::selects(null,null,null,null,array(null,'class|table=test'=>'test')));
			$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,null,null,null,array(null,'class|table=test'=>null)));
			if(function_exists('get_called_class')){
				$this->assertEquals(array($test1,$test2,$test3),test::selects());
				$this->assertEquals(array($test1,$test2,$test3),test::selects(null,null,null,null,array(null,'class|table=test'=>null)));
				$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,null,null,null,array(null,'class|table=test'=>null)));
			}
			$this->assertEquals(array($obj3,$obj2),core::selects(null,'pre_test',array('name'=>'test'),array('ORDER BY id DESC')));
			$this->assertEquals(array($obj1,$obj2,$obj3),core::selects('SELECT * FROM pre_test',null,true));
			$this->assertEquals(array($obj2,$obj2,$obj2),core::selects('SELECT ? AS id,? AS name FROM pre_test',array(2,'test'),true));
			$this->assertEquals(array($obj1,$obj2),core::selects('SELECT * FROM pre_test LIMIT 0,2',array(),true));
			$page = array('page'=>2,'size'=>1);$GLOBALS['aaa']=true;
			$this->assertEquals(array($obj2),core::selects('SELECT * FROM pre_test',null,true,array('page'=>&$page)));unset($GLOBALS['aaa']);
			$this->assertEquals(array('page'=>2,'size'=>1,'count'=>3,'total'=>3),$page);
			$page = array('page'=>2,'size'=>1,'count'=>4);
			$this->assertEquals(array($obj2),core::selects('SELECT * FROM pre_test',null,true,array('page'=>&$page)));
			$this->assertEquals(array('page'=>2,'size'=>1,'count'=>4,'total'=>4),$page);
			$page = array('page'=>2,'size'=>1,'count'=>null,'limit'=>'LIMIT 10');
			$this->assertEquals(array($obj2),core::selects('SELECT * FROM pre_test LIMIT 10',null,true,array('page'=>&$page)));
			$this->assertEquals(array('page'=>2,'size'=>1,'count'=>3,'limit'=>'LIMIT 10','total'=>3),$page);

			core::connect(array('debug_enable'=>true));
			ob_start();
			core::selects('SELECT ?,?',array(1,'a'),true);
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): SELECT ?,?'.PHP_EOL.'#0: int(1)'.PHP_EOL.'#1: string(1) a'.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>''));

			core::connect(array('debug_enable'=>true,'sql_format'=>true));
			ob_start();
			core::selects('SELECT ?,?',array(1,'a'),true);
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): SELECT 1,\'a\''.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>'','sql_format'=>''));

			// 链式操作
			core::init(array('extension_enable'=>'chain'));
			$this->assertEquals('1',chain::getInstance('core')->sql('SELECT 1 AS id')->getColumn());
			core::init(array('extension_enable'=>''));

			core::execute("DROP TABLE pre1_test");
			core::connect(false);
		}
	
	}
	
	/**
	 * Tests core::inserts()
	 */
	public function testInserts() {
		
		require_once 'test.php';
		
		// 1. 【基础功能】【扩展功能】插入对象数据。
		foreach ( $this->db_arr as $provider=>$db_arr ) {
			list($arr,$con,$cls) = $db_arr;
			$arr['prefix_search'] = 'pre_';
			$arr['prefix_replace'] = 'pre1_';
			core::connect($arr);
			core::execute("DROP TABLE IF EXISTS pre1_test");
			core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
			$obj1 = new core;
			$obj1->id = 1;
			$obj1->name = 'core';
			$obj2 = new core;
			$obj2->id = 2;
			$obj2->name = 'test';
			$obj3 = new core;
			$obj3->id = 3;
			$obj3->name = 'test';
			$this->assertSame(1,core::inserts('pre_test',array('id'=>1,'name'=>'core')));
			$this->assertSame(2,core::inserts('pre_test',array('name'),array(array('name'=>'test'),array('name'=>'test'))));
			$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,'pre_test'));
			core::execute("TRUNCATE pre1_test");

			if(function_exists('get_called_class')){
				$this->assertSame(1,test::inserts(null,array('id'=>1,'name'=>'core')));
				$this->assertSame(2,test::inserts(null,array('name'),array(array('name'=>'test'),array('name'=>'test'))));
				$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,'pre_test'));
				core::execute("TRUNCATE pre1_test");
			}

			$this->assertSame(1,core::inserts('INSERT INTO pre_test VALUES (?,?)',array(1,'core'),true));
			$this->assertSame(2,core::inserts('INSERT INTO pre_test (name) VALUES (?),(?)',array('test','test'),true));
			$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,'pre_test'));
			core::execute("TRUNCATE pre1_test");

			core::connect(array('debug_enable'=>true));
			ob_start();
			core::inserts('INSERT pre1_test(id,name) VALUES(?,?)',array(1,'a'),true);
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): INSERT pre1_test(id,name) VALUES(?,?)'.PHP_EOL.'#0: int(1)'.PHP_EOL.'#1: string(1) a'.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>''));
			core::execute("TRUNCATE pre1_test");

			core::connect(array('debug_enable'=>true,'sql_format'=>true));
			ob_start();
			core::inserts('INSERT pre1_test(id,name) VALUES(?,?)',array(1,'a'),true);
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): INSERT pre1_test(id,name) VALUES(1,\'a\')'.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>'','sql_format'=>''));
			core::execute("TRUNCATE pre1_test");

			// 链式操作
			core::init(array('extension_enable'=>'chain'));
			$this->assertSame(1,chain::getInstance('core')->table('pre_test')->set(array('id'=>1,'name'=>'core'))->insert());
			$this->assertSame(2,chain::getInstance('core')->table('pre_test')->column('name')->value(array(array('name'=>'test'),array('name'=>'test')))->insert());
			$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,'pre_test'));
			core::execute("TRUNCATE pre1_test");
			core::init(array('extension_enable'=>''));

			core::execute("DROP TABLE pre1_test");
			core::connect(false);
		}
	
	}
	
	/**
	 * Tests core::updates()
	 */
	public function testUpdates() {
		
		require_once 'test.php';
		
		// 1. 【基础功能】【扩展功能】修改对象数据。
		foreach ( $this->db_arr as $provider=>$db_arr ) {
			list($arr,$con,$cls) = $db_arr;
			$arr['prefix_search'] = 'pre_';
			$arr['prefix_replace'] = 'pre1_';
			core::connect($arr);
			core::execute("DROP TABLE IF EXISTS pre1_test");
			core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
			core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'core'),(2,'test'),(3,'test')");
			$obj1 = new core;
			$obj1->id = 1;
			$obj1->name = 'core1';
			$obj2 = new core;
			$obj2->id = 2;
			$obj2->name = 'test1';
			$obj3 = new core;
			$obj3->id = 3;
			$obj3->name = 'test1';
			$this->assertSame(1,core::updates('pre_test',array('name'=>'core1'),array('id'=>1)));
			$this->assertSame(2,core::updates('pre_test',array('name'=>'test1'),array('id'=>array(2,3))));
			$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,'pre_test'));
			core::execute("TRUNCATE pre1_test");

			if(function_exists('get_called_class')){
				core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'core'),(2,'test'),(3,'test')");
				$this->assertSame(1,test::updates(null,array('name'=>'core1'),array('id'=>1)));
				$this->assertSame(2,test::updates(null,array('name'=>'test1'),array('id'=>array(2,3))));
				$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,'pre_test'));
				core::execute("TRUNCATE pre1_test");
			}

			core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'core'),(2,'test'),(3,'test')");
			$this->assertSame(1,core::updates('UPDATE pre_test SET name=? WHERE id=?',array('core1',1),true));
			$this->assertSame(2,core::updates('UPDATE pre_test SET name=? WHERE id IN (?,?)',array('test1',2,3),true));
			$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,'pre_test'));
			core::execute("TRUNCATE pre1_test");

			core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'core')");
			core::connect(array('debug_enable'=>true));
			ob_start();
			core::updates('UPDATE pre1_test SET id=?,name=?',array(1,'a'),true);
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): UPDATE pre1_test SET id=?,name=?'.PHP_EOL.'#0: int(1)'.PHP_EOL.'#1: string(1) a'.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>''));
			core::execute("TRUNCATE pre1_test");

			core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'core')");
			core::connect(array('debug_enable'=>true,'sql_format'=>true));
			ob_start();
			core::updates('UPDATE pre1_test SET id=?,name=?',array(1,'a'),true);
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): UPDATE pre1_test SET id=1,name=\'a\''.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>'','sql_format'=>''));
			core::execute("TRUNCATE pre1_test");

			// 链式操作
			core::init(array('extension_enable'=>'chain'));
			core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'core'),(2,'test'),(3,'test')");
			$this->assertSame(1,chain::getInstance('core')->table('pre_test')->set(array('name'=>'core1'))->where(array('id'=>1))->update());
			$this->assertSame(2,chain::getInstance('core')->sql('UPDATE pre_test SET name=? WHERE id IN (?,?)')->param(array('test1',2,3))->update());
			$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,'pre_test'));
			core::execute("TRUNCATE pre1_test");
			core::init(array('extension_enable'=>''));

			core::execute("DROP TABLE pre1_test");
			core::connect(false);
		}
	
	}
	
	/**
	 * Tests core::deletes()
	 */
	public function testDeletes() {
		
		require_once 'test.php';
		
		// 1. 【基础功能】【扩展功能】删除对象数据。
		foreach ( $this->db_arr as $provider=>$db_arr ) {
			list($arr,$con,$cls) = $db_arr;
			$arr['prefix_search'] = 'pre_';
			$arr['prefix_replace'] = 'pre1_';
			core::connect($arr);
			core::execute("DROP TABLE IF EXISTS pre1_test");
			core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
			core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'core'),(2,'test'),(3,'test')");
			$obj1 = new core;
			$obj1->id = 1;
			$obj1->name = 'core';
			$obj2 = new core;
			$obj2->id = 2;
			$obj2->name = 'test';
			$obj3 = new core;
			$obj3->id = 3;
			$obj3->name = 'test';
			$this->assertSame(1,core::deletes(null,'pre_test',array('id'=>1)));
			$this->assertEquals(array($obj2,$obj3),core::selects(null,'pre_test'));
			$this->assertSame(2,core::deletes(null,'pre_test',array('id'=>array(2,3))));
			$this->assertEquals(array(),core::selects(null,'pre_test'));
			core::execute("TRUNCATE pre1_test");

			if(function_exists('get_called_class')){
				core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'core'),(2,'test'),(3,'test')");
				$this->assertSame(1,test::deletes(null,null,array('id'=>1)));
				$this->assertEquals(array($obj2,$obj3),core::selects(null,'pre_test'));
				$this->assertSame(2,test::deletes(null,null,array('id'=>array(2,3))));
				$this->assertEquals(array(),core::selects(null,'pre_test'));
				core::execute("TRUNCATE pre1_test");
			}

			core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'core'),(2,'test'),(3,'test')");
			$this->assertSame(1,core::deletes('DELETE FROM pre_test WHERE id=?',array(1),true));
			$this->assertEquals(array($obj2,$obj3),core::selects(null,'pre_test'));
			$this->assertSame(2,core::deletes('DELETE FROM pre_test WHERE id IN (?,?)',array(2,3),true));
			$this->assertEquals(array(),core::selects(null,'pre_test'));
			core::execute("TRUNCATE pre1_test");

			core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'core')");
			core::connect(array('debug_enable'=>true));
			ob_start();
			core::deletes('DELETE FROM pre1_test WHERE id=? OR name=?',array(1,'a'),true);
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): DELETE FROM pre1_test WHERE id=? OR name=?'.PHP_EOL.'#0: int(1)'.PHP_EOL.'#1: string(1) a'.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>''));
			core::execute("TRUNCATE pre1_test");

			core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'core')");
			core::connect(array('debug_enable'=>true,'sql_format'=>true));
			ob_start();
			core::deletes('DELETE FROM pre1_test WHERE id=? OR name=?',array(1,'a'),true);
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): DELETE FROM pre1_test WHERE id=1 OR name=\'a\''.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>'','sql_format'=>''));
			core::execute("TRUNCATE pre1_test");

			// 链式操作
			core::init(array('extension_enable'=>'chain'));
			core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'core'),(2,'test'),(3,'test')");
			$this->assertSame(1,chain::getInstance('core')->table('pre_test')->where('id=1')->delete());
			$this->assertEquals(array($obj2,$obj3),core::selects(null,'pre_test'));
			$this->assertSame(2,chain::getInstance('core')->table('pre_test')->where(array('id'=>array(2,3)))->delete());
			$this->assertEquals(array(),core::selects(null,'pre_test'));
			core::execute("TRUNCATE pre1_test");
			core::init(array('extension_enable'=>''));

			core::execute("DROP TABLE pre1_test");
			core::connect(false);
		}
	
	}
	/**
	 * Tests core::replaces()
	 */
	public function testReplaces() {
		
		require_once 'test.php';
		
		// 1. 【基础功能】【扩展功能】插入对象数据。
		foreach ( $this->db_arr as $provider=>$db_arr ) {
			list($arr,$con,$cls) = $db_arr;
			$arr['prefix_search'] = 'pre_';
			$arr['prefix_replace'] = 'pre1_';
			core::connect($arr);
			core::execute("DROP TABLE IF EXISTS pre1_test");
			core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
			$obj1 = new core;
			$obj1->id = 1;
			$obj1->name = 'core';
			$obj2 = new core;
			$obj2->id = 2;
			$obj2->name = 'test';
			$obj3 = new core;
			$obj3->id = 3;
			$obj3->name = 'test';
			$this->assertSame(1,core::replaces('pre_test',array('id'=>1,'name'=>'core')));
			$this->assertSame(2,core::replaces('pre_test',array('id'=>1,'name'=>'core')));
			$this->assertSame(2,core::replaces('pre_test',array('name'),array(array('name'=>'test'),array('name'=>'test'))));
			$this->assertSame(4,core::replaces('pre_test',array('id','name'),array(array('id'=>2,'name'=>'test'),array('id'=>3,'name'=>'test'))));
			$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,'pre_test'));
			core::execute("TRUNCATE pre1_test");

			if(function_exists('get_called_class')){
				$this->assertSame(1,test::replaces(null,array('id'=>1,'name'=>'core')));
				$this->assertSame(2,test::replaces(null,array('id'=>1,'name'=>'core')));
				$this->assertSame(2,test::replaces(null,array('name'),array(array('name'=>'test'),array('name'=>'test'))));
				$this->assertSame(4,test::replaces(null,array('id','name'),array(array('id'=>2,'name'=>'test'),array('id'=>3,'name'=>'test'))));
				$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,'pre_test'));
				core::execute("TRUNCATE pre1_test");
			}

			$this->assertSame(1,core::replaces('REPLACE INTO pre_test VALUES (?,?)',array(1,'core'),true));
			$this->assertSame(2,core::replaces('REPLACE INTO pre_test VALUES (?,?)',array(1,'core'),true));
			$this->assertSame(2,core::replaces('REPLACE INTO pre_test (name) VALUES (?),(?)',array('test','test'),true));
			$this->assertSame(4,core::replaces('REPLACE INTO pre_test (id,name) VALUES (2,?),(3,?)',array('test','test'),true));
			$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,'pre_test'));
			core::execute("TRUNCATE pre1_test");

			core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'core')");
			core::connect(array('debug_enable'=>true));
			ob_start();
			core::replaces('REPLACE INTO pre1_test VALUES (?,?)',array(1,'a'),true);
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): REPLACE INTO pre1_test VALUES (?,?)'.PHP_EOL.'#0: int(1)'.PHP_EOL.'#1: string(1) a'.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>''));
			core::execute("TRUNCATE pre1_test");

			core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'core')");
			core::connect(array('debug_enable'=>true,'sql_format'=>true));
			ob_start();
			core::replaces('REPLACE INTO pre1_test VALUES (?,?)',array(1,'a'),true);
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): REPLACE INTO pre1_test VALUES (1,\'a\')'.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>'','sql_format'=>''));
			core::execute("TRUNCATE pre1_test");

			// 链式操作
			core::init(array('extension_enable'=>'chain'));
			$this->assertSame(1,chain::getInstance('core')->table('pre_test')->set(array('id'=>1,'name'=>'core'))->replace());
			$this->assertSame(2,chain::getInstance('core')->table('pre_test')->set(array('id'=>1,'name'=>'core'))->replace());
			$this->assertSame(2,chain::getInstance('core')->table('pre_test')->column('name')->value(array(array('\'test\''),array('\'test\'')))->replace());
			$this->assertSame(4,chain::getInstance('core')->sql('REPLACE INTO pre_test (id,name) VALUES (2,?),(3,?)')->param(array('test','test'))->replace());
			$this->assertEquals(array($obj1,$obj2,$obj3),core::selects(null,'pre_test'));
			core::execute("TRUNCATE pre1_test");
			core::init(array('extension_enable'=>''));

			core::execute("DROP TABLE pre1_test");
			core::connect(false);
		}
	
	}
	
	/**
	 * Tests core->struct()
	 */
	public function testStruct() {
		
		// 1. 【基础功能】返回实例数组。
		$arr = array(
			'a' => 1,
			'b' => 'b',
			'c' => array('c'),
		); 
		$obj = new core;
		$obj->a = 1;
		$obj->b = 'b';
		$obj->c = array('c');
		$this->assertSame($arr,$obj->struct());
		
		// 2. 【基础功能】返回实例数据。
		$this->assertSame("b",$obj->struct(1));
		$this->assertSame("b",$obj->struct("b"));
		
		// 3. 【基础功能】载入实例数组。
		$obj2 = new core;
		$this->assertSame($arr,$obj2->struct($obj));
		$this->assertEquals($obj,$obj2);
		$obj3 = new core;
		$this->assertSame($arr,$obj3->struct($arr));
		$this->assertEquals($obj,$obj3);
		$obj4 = new core;
		$obj4->a = null;
		$obj4->b = null;
		$obj4->c = null;
		$this->assertSame($arr,$obj4->struct(array_values($arr)));
		$this->assertEquals($obj,$obj4);
		
	}
	
	/**
	 * Tests core->select()
	 */
	public function testSelect() {
		
		require_once 'test.php';
		
		// 1. 【基础功能】【扩展功能】选择实例数据。
		foreach ( $this->db_arr as $provider=>$db_arr ) {
			list($arr,$con,$cls) = $db_arr;
			$arr['prefix_search'] = 'pre_';
			$arr['prefix_replace'] = 'pre1_';
			core::connect($arr);
			core::execute("DROP TABLE IF EXISTS pre1_test");
			core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
			core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'a'),(2,'b')");
			$obj = new core;
			$obj->id = 2;
			$this->assertTrue($obj->select('pre_test'));
			$this->assertSame('b', $obj->name);
			$obj->id = 3;
			$this->assertFalse($obj->select('pre_test'));
			$test = new test;
			$test->id = 2;
			$this->assertTrue($test->select());
			$test->id = 3;
			$this->assertFalse($test->select());
			$test->id = 2;
			$this->assertTrue($test->select(null));
			$test->id = 3;
			$this->assertFalse($test->select(null));

			core::connect(array('debug_enable'=>true));
			ob_start();
			$obj->id = 1;
			$obj->select('pre_test');
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): SELECT * FROM pre1_test WHERE id=? LIMIT 1'.PHP_EOL.'#0: int(1)'.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>''));

			core::connect(array('debug_enable'=>true,'sql_format'=>true));
			ob_start();
			$obj->id = 1;
			$obj->select('pre_test');
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): SELECT * FROM pre1_test WHERE id=1 LIMIT 1'.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>'','sql_format'=>''));

			core::execute("DROP TABLE pre1_test");
			core::connect(false);
		}
		
	}
	
	/**
	 * Tests core->insert()
	 */
	public function testInsert() {
		
		require_once 'test.php';
		
		// 1. 【基础功能】【扩展功能】插入实例数据。
		foreach ( $this->db_arr as $provider=>$db_arr ) {
			list($arr,$con,$cls) = $db_arr;
			$arr['prefix_search'] = 'pre_';
			$arr['prefix_replace'] = 'pre1_';
			core::connect($arr);
			core::execute("DROP TABLE IF EXISTS pre1_test");
			core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
			$obj = new core;
			$obj->id = null;
			$obj->name = 'a';
			$this->assertTrue($obj->insert('pre_test'));
			$this->assertSame('1',$obj->id);
			$this->assertTrue($obj->insert('pre_test'));
			$this->assertSame('2',$obj->id);
			$this->assertFalse($obj->insert('pre_test',-1));
			$test = new test;
			$test->id = null;
			$test->name = 'd';
			$this->assertTrue($test->insert());
			$this->assertSame('3',$test->id);
			$this->assertTrue($test->insert());
			$this->assertSame('4',$test->id);
			$this->assertFalse($test->insert('',-1));

			core::connect(array('debug_enable'=>true));
			ob_start();
			$obj->id = null;
			$obj->name = 'a';
			$obj->insert('pre_test');
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): INSERT INTO pre1_test (name) VALUES (?)'.PHP_EOL.'#0: string(1) a'.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>''));

			core::connect(array('debug_enable'=>true,'sql_format'=>true));
			ob_start();
			$obj->id = null;
			$obj->name = 'a';
			$obj->insert('pre_test');
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): INSERT INTO pre1_test (name) VALUES (\'a\')'.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>'','sql_format'=>''));

			core::execute("DROP TABLE pre1_test");
			core::connect(false);
		}
	}
	
	/**
	 * Tests core->update()
	 */
	public function testUpdate() {
		
		require_once 'test.php';
		
		// 1. 【基础功能】【扩展功能】修改实例数据。
		foreach ( $this->db_arr as $provider=>$db_arr ) {
			list($arr,$con,$cls) = $db_arr;
			$arr['prefix_search'] = 'pre_';
			$arr['prefix_replace'] = 'pre1_';
			core::connect($arr);
			core::execute("DROP TABLE IF EXISTS pre1_test");
			core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
			core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
			$obj = new core;
			$obj->id = 2;
			$obj->name = 'c';
			$this->assertTrue($obj->update('pre_test'));
			$obj->name = 'c2';
			$this->assertTrue($obj->update('pre_test',0));
			$obj->name = 'c3';
			$this->assertTrue($obj->update('pre_test','id'));
			$obj->name = 'd';
			$this->assertTrue($obj->update('pre_test',-1));
			$obj->id = 3;
			$this->assertFalse($obj->update('pre_test'));
			$test = new test;
			$test->id = 2;
			$test->name = 'e';
			$this->assertTrue($test->update());
			$test->name = 'f';
			$this->assertTrue($test->update('',-1));
			$test->id = 3;
			$this->assertFalse($test->update());

			core::connect(array('debug_enable'=>true));
			ob_start();
			$obj->id = 1;
			$obj->name = 'a';
			$obj->update('pre_test');
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): UPDATE pre1_test SET name=? WHERE id=? LIMIT 1'.PHP_EOL.'#0: string(1) a'.PHP_EOL.'#1: int(1)'.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>''));

			core::connect(array('debug_enable'=>true,'sql_format'=>true));
			ob_start();
			$obj->id = 1;
			$obj->name = 'a';
			$obj->update('pre_test');
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): UPDATE pre1_test SET name=\'a\' WHERE id=1 LIMIT 1'.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>'','sql_format'=>''));

			core::execute("DROP TABLE pre1_test");
			core::connect(false);
		}
		
	}

	/**
	 * Tests core->delete()
	 */
	public function testDelete() {
		
		require_once 'test.php';
		
		// 1. 【基础功能】【扩展功能】删除实例数据。
		foreach ( $this->db_arr as $provider=>$db_arr ) {
			list($arr,$con,$cls) = $db_arr;
			$arr['prefix_search'] = 'pre_';
			$arr['prefix_replace'] = 'pre1_';
			core::connect($arr);
			core::execute("DROP TABLE IF EXISTS pre1_test");
			core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
			core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
			$obj = new core;
			$obj->id = 3;
			$this->assertFalse($obj->delete('pre_test'));
			$obj->id = 2;
			$this->assertTrue($obj->delete('pre_test'));
			$this->assertFalse($obj->delete('pre_test'));
			core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
			$this->assertTrue($obj->delete('pre_test',-1));
			$this->assertFalse($obj->delete('pre_test',-1));
			core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
			$test = new test;
			$test->id = 3;
			$this->assertFalse($test->delete());
			$test->id = 2;
			$this->assertTrue($test->delete());
			$this->assertFalse($test->delete());

			core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'b')");
			core::connect(array('debug_enable'=>true));
			ob_start();
			$obj->id = 1;
			$obj->name = 'a';
			$obj->delete('pre_test');
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): DELETE FROM pre1_test WHERE id=? LIMIT 1'.PHP_EOL.'#0: int(1)'.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>''));

			core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'b')");
			core::connect(array('debug_enable'=>true,'sql_format'=>true));
			ob_start();
			$obj->id = 1;
			$obj->name = 'a';
			$obj->delete('pre_test');
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): DELETE FROM pre1_test WHERE id=1 LIMIT 1'.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>'','sql_format'=>''));

			core::execute("DROP TABLE pre1_test");
			core::connect(false);
		}
		
	}

	/**
	 * Tests core->replace()
	 */
	public function testReplace() {
		
		require_once 'test.php';
		
		// 1. 【基础功能】【扩展功能】更新实例数据。
		foreach ( $this->db_arr as $provider=>$db_arr ) {
			list($arr,$con,$cls) = $db_arr;
			$arr['prefix_search'] = 'pre_';
			$arr['prefix_replace'] = 'pre1_';
			core::connect($arr);
			core::execute("DROP TABLE IF EXISTS pre1_test");
			core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
			core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
			$obj = new core;
			$obj->id = null;
			$obj->name = 'c';
			$this->assertTrue($obj->replace('pre_test'));
			$this->assertSame('3',$obj->id);
			$obj->id = 3;
			$obj->name = 'd';
			$this->assertTrue($obj->replace('pre_test'));

			core::connect(array('debug_enable'=>true));
			ob_start();
			$obj->id = 1;
			$obj->name = 'a';
			$obj->replace('pre_test');
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): REPLACE INTO pre1_test (id,name) VALUES (?,?)'.PHP_EOL.'#0: int(1)'.PHP_EOL.'#1: string(1) a'.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>''));

			core::connect(array('debug_enable'=>true,'sql_format'=>true));
			ob_start();
			$obj->id = 1;
			$obj->name = 'a';
			$obj->replace('pre_test');
			$this->assertSame(PHP_EOL.'('.$arr['connect_provider'].'): REPLACE INTO pre1_test (id,name) VALUES (1,\'a\')'.PHP_EOL,ob_get_clean());
			core::connect(array('debug_enable'=>'','sql_format'=>''));

			core::execute("DROP TABLE pre1_test");
			core::connect(false);
		}
		
	}
	
}

