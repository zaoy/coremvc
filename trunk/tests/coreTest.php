<?php

require_once 'PHPUnit\Framework\TestCase.php';

/**
 * core test case.
 */
class coreTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var core
	 */
	private $core;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated coreTest::setUp()
		

		$this->core = new core(/* parameters */);
	
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated coreTest::tearDown()
		

		$this->core = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests core::stub()
	 */
	public function testStub() {

		$this->assertTrue(core::stub());
		$return = require('test2.php');
		$this->assertFalse($return);
		$this->assertTrue(test2::stub());

	}
	
	/**
	 * Tests core::main()
	 */
	public function testMain() {
		
		ob_start();
		core::main();
		$this->assertRegExp('/^Could not open input file:/', ob_get_clean());
	
	}
	
	/**
	 * Tests core::view()
	 */
	public function testView() {

		ob_start();
		core::view('tests/template.php',array('z'=>'b'));
		$this->assertSame('abc', ob_get_clean());

		$this->assertSame('abc', core::view('tests/template2.php',array('z'=>'b'),'string',false));

		$this->assertSame('abc', core::view('tests/template3.php',array('z'=>'b'),'smarty',false,'./'));
	
	}
	
	/**
	 * Tests core::connect()
	 */
	public function testConnect() {
	    
		// 1. Switch Connection
	    $this->assertSame(2, core::connect(1), "Switch to second connection and return the number of connections");
	    $this->assertSame(2, core::connect(0, $ref), "Switch to default connection and return the number of connections");
	    $this->assertType("null",$ref);
	    
	    // 2. Connect to mysql, using constant that is mysql/localhost/ODBC@localhost/(empty)
	    $this->assertType("resource", core::connect(), "Connect using constant");
	    $this->assertTrue(core::connect(false), "Disconnect");
	    
	    $this->assertType("resource", core::connect(true, $ref), "Connect using constant");
	    $this->assertType("array",$ref);
	    $this->assertType("resource", core::connect(true, $ref), "Connect using constant");
	    $this->assertType("array",$ref);
	    $this->assertTrue(core::connect(false, $ref), "Disconnect");
	    $this->assertType("null",$ref);
	    
	    // 3. Connect to mysql, using array
	    $args = array(
	        'provider' => 'mysql',
	        'server' => 'localhost',
	    	'username' => 'ODBC',
	        'password' => '',
	        'dbname' => 'test',
	    );
	    $this->assertType("resource", $dbh = core::connect($args), "Connect using array by mysql");
	    $this->assertTrue(core::connect(false), "Disconnect");

	    $args = array(
	        'provider' => 'pdo',
	        'dsn' => 'mysql:host=localhost;dbname=test',
	        'username' => 'ODBC',
	        'password' => '',
	        'driver_options' => array( PDO::ATTR_PERSISTENT => true ),
	    );
	    try{
	        $dbh = core::connect($args);
	    }catch(Exception $e){
	        $this->fail("Connect using array by pdo failed");
	    }
	    $this->assertSame($dbh, core::connect(), "Connect again");
	    $this->assertTrue(core::connect(false), "Disconnect");

	    $GLOBALS['ADODB_CACHE_CLASS'] =  'ADODB_Cache_File';
	    $args = array(
	        'provider' => 'adodb',
	        'dsn' => 'mysqlt',
	    	'username' => 'ODBC',
	        'password' => '',
	        'dbname' => 'test',
	    );
	    $dbh = core::connect($args);
	    if( !preg_match ( '/^ADODB/i', get_class($dbh)) ){
	        $this->fail("Connect using array by adodb failed");   
	    }
	    $this->assertSame($dbh, core::connect(), "Connect again");
	    $this->assertTrue(core::connect(false), "Disconnect");
	    
	    // 4. Connect to mysql, using config file
	    $this->assertType("resource", core::connect(require(dirname(__FILE__).'/config_mysql.php')), "Connect using config by require");
	    $this->assertTrue(core::connect(false), "Disconnect");

	    $this->assertType("resource", core::connect('./tests/config_mysql.php'), "Connect using config by string");
	    $this->assertTrue(core::connect(false), "Disconnect");

	    $this->assertType("resource", core::connect(array('config' => './tests/config_mysql.php')), "Connect using config by array");
	    $this->assertTrue(core::connect(false), "Disconnect");

	    try{
	        $dbh = core::connect('./tests/config_pdo.php');
	    }catch(Exception $e){
	        $this->fail("Connect using array by pdo failed");
	    }
	    $this->assertSame("PDO", get_class($dbh), "Connect using file by pdo");
	    $this->assertTrue(core::connect(false), "Disconnect");

	    $dbh = core::connect('./tests/config_adodb.php');
	    $this->assertRegExp('/^ADODB/i', get_class($dbh), "Connect using file by adodb");
	    $this->assertTrue(core::connect(false), "Disconnect");
	    
	    // 5. Disconnect
	    $this->assertFalse(core::connect(false, $ref), "Disconnect again");
	    $this->assertType("null",$ref);
	    
	}
	
	/**
	 * Tests core::execute()
	 */
	public function testExecute() {

	    core::connect('./tests/config_mysql.php');
	    $this->assertSame(true, core::execute("SET NAMES GBK"), "execute");
	    $this->assertType("resource", $result = core::execute("SELECT ?",array('2')), "execute");
	    $this->assertSame("2", mysql_result($result,0), "execute");
	    $this->assertType("resource", core::execute("SELECT 1",null,$ref), "execute");
	    $this->assertSame(1, $ref['num_rows'], "execute");
	    $this->assertType("resource", core::execute("SELECT ? UNION select ?",array(1,2),$ref), "execute");
	    $this->assertSame(1, $ref['num_fields'], "execute");
	    $this->assertSame(2, $ref['num_rows'], "execute");    
	    core::connect(false);
	    
	    core::connect('./tests/config_pdo.php');
	    $this->assertSame("PDOStatement", get_class(core::execute("SET NAMES GBK")), "execute");
	    $this->assertSame("PDOStatement", get_class(core::execute("SELECT 1")), "execute");
	    $this->assertSame("PDOStatement", get_class(core::execute("SELECT ?",array('2'))), "execute");
	    $this->assertSame("PDOStatement", get_class(core::execute("SELECT 1",null,$ref)), "execute");
	    $this->assertSame(1, $ref['num_rows'], "execute");
	    $this->assertSame("PDOStatement", get_class(core::execute("SELECT ? UNION SELECT ?",array(1,2),$ref)), "execute");
	    $this->assertSame(1, $ref['num_fields'], "execute");
	    $this->assertSame(2, $ref['num_rows'], "execute");
	    core::connect(false);

	    $GLOBALS['ADODB_CACHE_CLASS'] =  'ADODB_Cache_File';
	    core::connect('./tests/config_adodb.php');
	    $this->assertRegExp('/^ADORecordset/i', get_class(core::execute("SELECT 1")), "execute");
	    $this->assertRegExp('/^ADORecordset/i', get_class(core::execute("SELECT ?",array('2'))), "execute");
	    $this->assertRegExp('/^ADORecordset/i', get_class(core::execute("SELECT 1",null,$ref)), "execute");
	    $this->assertSame(1, $ref['num_rows'], "execute");
	    $this->assertRegExp('/^ADORecordset/i', get_class(core::execute("SELECT ? UNION SELECT ?",array(1,2),$ref)), "execute");
	    $this->assertSame(1, $ref['num_fields'], "execute");
	    $this->assertSame(2, $ref['num_rows'], "execute");
	    core::connect(false);
	    
	}
	
	/**
	 * Tests core::prepare()
	 */
	public function testPrepare() {
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
		$this->assertRegexp('/^SELECT(.*?)*(.*?)FROM(.*?)bbb/si',core::prepare($syntax, array('field'=>'*','table'=>'bbb','where'=>''),$ref));
		
	
	}
	
	/**
	 * Tests core::sequence()
	 */
	public function testSequence() {
	    $arr = require(dirname(__FILE__).'/config_mysql.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS sequence");
	    $this->assertSame(1, core::sequence('sequence'), "sequence");
	    $this->assertSame(2, core::sequence('sequence'), "sequence");
	    core::execute("DROP TABLE sequence");
	    core::execute("DROP TABLE IF EXISTS sequence");
	    $this->assertSame(2, core::sequence('sequence',2), "sequence");
	    $this->assertSame(3, core::sequence('sequence',2), "sequence");
	    $this->assertSame(9, core::sequence('sequence',9), "sequence");
	    core::execute("DROP TABLE sequence");
	    core::connect(false);	

	    $arr = require(dirname(__FILE__).'/config_pdo.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS sequence");
	    $this->assertSame(1, core::sequence('sequence'), "sequence");
	    $this->assertSame(2, core::sequence('sequence',false), "sequence");
	    core::execute("DROP TABLE sequence");
	    core::execute("DROP TABLE IF EXISTS sequence");
	    $this->assertSame(2, core::sequence('sequence',2), "sequence");
	    $this->assertSame(3, core::sequence('sequence',2), "sequence");
	    $this->assertSame(9, core::sequence('sequence',9), "sequence");
	    core::execute("DROP TABLE sequence");
	    core::connect(false);	

	    $GLOBALS['ADODB_CACHE_CLASS'] =  'ADODB_Cache_File';
	    $arr = require(dirname(__FILE__).'/config_adodb.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS sequence");
	    $this->assertSame(1, core::sequence('sequence'), "sequence");
	    $this->assertSame(2, core::sequence('sequence'), "sequence");
	    core::execute("DROP TABLE sequence");
	    core::execute("DROP TABLE IF EXISTS sequence");
	    $this->assertSame(2, core::sequence('sequence',2), "sequence");
	    $this->assertSame(3, core::sequence('sequence',2), "sequence");
	    $this->assertSame(9, core::sequence('sequence',9), "sequence");
	    $this->assertSame(10, core::sequence('sequence',10), "sequence");
	    core::execute("DROP TABLE sequence");
	    core::connect(false);	
	    
	}
	
	/**
	 * Tests core->struct()
	 */
	public function testStruct() {
	    $core = new core;
	    $array = array(
	        'a' => 1,
	        'b' => 'b',
	        'c' => array('c'),
	    ); 
		$this->assertType("object",$core->struct($array));
		$this->assertType("array",$core->struct());
		$this->assertSame("b",$core->struct(2));
		$this->assertSame("b",$core->struct("b"));
		$core = null;

	    $core = new core;
	    $object = new core;
	    $object->a = 1;
	    $object->b = 'b';
	    $object->c = array('c');
		$this->assertType("object",$core->struct($object));
		$core = null;
		$object = null;
		
	}
	
	/**
	 * Tests core->select()
	 */
	public function testSelect() {
	    
	    $arr = require_once(dirname(__FILE__).'/test.php');
	    
	    $arr = require(dirname(__FILE__).'/config_mysql.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'a'),(2,'b')");
	    $obj = new core;
	    $obj->id = 2;
	    $this->assertTrue($obj->select('pre_test'), "select");
	    $this->assertSame('b', $obj->name, "select");
	    $obj->id = 3;
	    $this->assertFalse($obj->select('pre_test'), "select");
	    $test = new test;
	    $test->id = 2;
	    $this->assertTrue($test->select(), "select");
	    $test->id = 3;
	    $this->assertFalse($test->select(), "select");
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	

	    $arr = require(dirname(__FILE__).'/config_pdo.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'a'),(2,'b')");
	    $obj = new core;
	    $obj->id = 2;
	    $this->assertTrue($obj->select('pre_test'), "select");
	    $this->assertSame('b', $obj->name, "select");
	    $obj->id = 3;
	    $this->assertFalse($obj->select('pre_test'), "select");
	    $test = new test;
	    $test->id = 2;
	    $this->assertTrue($test->select(), "select");
	    $test->id = 3;
	    $this->assertFalse($test->select(), "select");
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	

	    $GLOBALS['ADODB_CACHE_CLASS'] =  'ADODB_Cache_File';
	    $arr = require(dirname(__FILE__).'/config_adodb.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'a'),(2,'b')");
	    $obj = new core;
	    $obj->id = 2;
	    $this->assertTrue($obj->select('pre_test'), "select");
	    $this->assertSame('b', $obj->name, "select");
	    $obj->id = 3;
	    $this->assertFalse($obj->select('pre_test'), "select");
	    $test = new test;
	    $test->id = 2;
	    $this->assertTrue($test->select(), "select");
	    $test->id = 3;
	    $this->assertFalse($test->select(), "select");
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	    
	}
	
	/**
	 * Tests core->insert()
	 */
	public function testInsert() {
	    
	    $arr = require_once(dirname(__FILE__).'/test.php');
	    
	    $arr = require(dirname(__FILE__).'/config_mysql.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    $obj = new core;
	    $obj->id = null;
	    $obj->name = 'a';
	    $this->assertTrue($obj->insert('pre_test'), "insert");
	    $this->assertSame('1',$obj->id, "insert");
	    $this->assertTrue($obj->insert('pre_test'), "insert");
	    $this->assertSame('2',$obj->id, "insert");
	    $this->assertFalse($obj->insert('pre_test',0), "insert");
	    $test = new test;
	    $test->id = null;
	    $test->name = 'd';
	    $this->assertTrue($test->insert(), "insert");
	    $this->assertSame('3',$test->id, "insert");
	    $this->assertTrue($test->insert(), "insert");
	    $this->assertSame('4',$test->id, "insert");
	    $this->assertFalse($test->insert('',0), "insert");
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	

	    $arr = require(dirname(__FILE__).'/config_pdo.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    $obj = new core;
	    $obj->id = null;
	    $obj->name = 'a';
	    $this->assertTrue($obj->insert('pre_test'), "insert");
	    $this->assertSame('1',$obj->id, "insert");
	    $this->assertTrue($obj->insert('pre_test'), "insert");
	    $this->assertSame('2',$obj->id, "insert");
	    $this->assertFalse($obj->insert('pre_test',0), "insert");
	    $test = new test;
	    $test->id = null;
	    $test->name = 'd';
	    $this->assertTrue($test->insert(), "insert");
	    $this->assertSame('3',$test->id, "insert");
	    $this->assertTrue($test->insert(), "insert");
	    $this->assertSame('4',$test->id, "insert");
	    $this->assertFalse($test->insert('',0), "insert");
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	    
	    $GLOBALS['ADODB_CACHE_CLASS'] =  'ADODB_Cache_File';
	    $arr = require(dirname(__FILE__).'/config_adodb.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    $obj = new core;
	    $obj->id = null;
	    $obj->name = 'a';
	    $this->assertTrue($obj->insert('pre_test'), "insert");
	    $this->assertSame('1',$obj->id, "insert");
	    $this->assertTrue($obj->insert('pre_test'), "insert");
	    $this->assertSame('2',$obj->id, "insert");
	    $this->assertFalse($obj->insert('pre_test',0), "insert");
	    $test = new test;
	    $test->id = null;
	    $test->name = 'd';
	    $this->assertTrue($test->insert(), "insert");
	    $this->assertSame('3',$test->id, "insert");
	    $this->assertTrue($test->insert(), "insert");
	    $this->assertSame('4',$test->id, "insert");
	    $this->assertFalse($test->insert('',0), "insert");
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	}
	
	/**
	 * Tests core->update()
	 */
	public function testUpdate() {
	    
	    $arr = require_once(dirname(__FILE__).'/test.php');
	    
	    $arr = require(dirname(__FILE__).'/config_mysql.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
	    $obj = new core;
	    $obj->id = 2;
	    $obj->name = 'c';
	    $this->assertTrue($obj->update('pre_test'), "update");
	    $obj->name = 'd';
	    $this->assertTrue($obj->update('pre_test',0), "update");
	    $obj->id = 3;
	    $this->assertFalse($obj->update('pre_test'), "update");
	    $test = new test;
	    $test->id = 2;
	    $test->name = 'e';
	    $this->assertTrue($test->update(), "update");
	    $test->name = 'f';
	    $this->assertTrue($test->update('',0), "update");
	    $test->id = 3;
	    $this->assertFalse($test->update(), "update");
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	

	    $arr = require(dirname(__FILE__).'/config_pdo.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
	    $obj = new core;
	    $obj->id = 2;
	    $obj->name = 'c';
	    $this->assertTrue($obj->update('pre_test'), "update");
	    $obj->name = 'd';
	    $this->assertTrue($obj->update('pre_test',0), "update");
	    $obj->id = 3;
	    $this->assertFalse($obj->update('pre_test'), "update");
	    $test = new test;
	    $test->id = 2;
	    $test->name = 'e';
	    $this->assertTrue($test->update(), "update");
	    $test->name = 'f';
	    $this->assertTrue($test->update('',0), "update");
	    $test->id = 3;
	    $this->assertFalse($test->update(), "update");
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	    
	    $GLOBALS['ADODB_CACHE_CLASS'] =  'ADODB_Cache_File';
	    $arr = require(dirname(__FILE__).'/config_adodb.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
	    $obj = new core;
	    $obj->id = 2;
	    $obj->name = 'c';
	    $this->assertTrue($obj->update('pre_test'), "update");
	    $obj->name = 'd';
	    $this->assertTrue($obj->update('pre_test',0), "update");
	    $obj->id = 3;
	    $this->assertFalse($obj->update('pre_test'), "update");
	    $test = new test;
	    $test->id = 2;
	    $test->name = 'e';
	    $this->assertTrue($test->update(), "update");
	    $test->name = 'f';
	    $this->assertTrue($test->update('',0), "update");
	    $test->id = 3;
	    $this->assertFalse($test->update(), "update");
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	    
	}
	
	/**
	 * Tests core->delete()
	 */
	public function testDelete() {
	    
	    $arr = require_once(dirname(__FILE__).'/test.php');
	    
	    $arr = require(dirname(__FILE__).'/config_mysql.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
	    $obj = new core;
	    $obj->id = 3;
	    $this->assertFalse($obj->delete('pre_test'), "delete");
	    $obj->id = 2;
	    $this->assertTrue($obj->delete('pre_test'), "delete");
	    $this->assertFalse($obj->delete('pre_test'), "delete");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
	    $this->assertTrue($obj->delete('pre_test',0), "delete");
	    $this->assertFalse($obj->delete('pre_test',0), "delete");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
	    $test = new test;
	    $test->id = 3;
	    $this->assertFalse($test->delete(), "delete");
	    $test->id = 2;
	    $this->assertTrue($test->delete(), "delete");
	    $this->assertFalse($test->delete(), "delete");
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	

	    $arr = require(dirname(__FILE__).'/config_pdo.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
	    $obj = new core;
	    $obj->id = 3;
	    $this->assertFalse($obj->delete('pre_test'), "delete");
	    $obj->id = 2;
	    $this->assertTrue($obj->delete('pre_test'), "delete");
	    $this->assertFalse($obj->delete('pre_test'), "delete");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
	    $this->assertTrue($obj->delete('pre_test',0), "delete");
	    $this->assertFalse($obj->delete('pre_test',0), "delete");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
	    $test = new test;
	    $test->id = 3;
	    $this->assertFalse($test->delete(), "delete");
	    $test->id = 2;
	    $this->assertTrue($test->delete(), "delete");
	    $this->assertFalse($test->delete(), "delete");
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	    
	    $GLOBALS['ADODB_CACHE_CLASS'] =  'ADODB_Cache_File';
	    $arr = require(dirname(__FILE__).'/config_adodb.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
	    $obj = new core;
	    $obj->id = 3;
	    $this->assertFalse($obj->delete('pre_test'), "delete");
	    $obj->id = 2;
	    $this->assertTrue($obj->delete('pre_test'), "delete");
	    $this->assertFalse($obj->delete('pre_test'), "delete");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
	    $this->assertTrue($obj->delete('pre_test',0), "delete");
	    $this->assertFalse($obj->delete('pre_test',0), "delete");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
	    $test = new test;
	    $test->id = 3;
	    $this->assertFalse($test->delete(), "delete");
	    $test->id = 2;
	    $this->assertTrue($test->delete(), "delete");
	    $this->assertFalse($test->delete(), "delete");
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	    
	}
	
	/**
	 * Tests core->replace()
	 */
	public function testReplace() {
	    
	    $arr = require_once(dirname(__FILE__).'/test.php');
	    
	    $arr = require(dirname(__FILE__).'/config_mysql.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
	    $obj = new core;
	    $obj->id = null;
	    $obj->name = 'c';
	    $this->assertTrue($obj->replace('pre_test'), "replace");
	    $this->assertSame('3',$obj->id, "replace");
	    $obj->id = 3;
	    $obj->name = 'd';
	    $this->assertTrue($obj->replace('pre_test'), "replace");
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	

	    $arr = require(dirname(__FILE__).'/config_pdo.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
	    $obj = new core;
	    $obj->id = null;
	    $obj->name = 'c';
	    $this->assertTrue($obj->replace('pre_test'), "replace");
	    $this->assertSame('3',$obj->id, "replace");
	    $obj->id = 3;
	    $obj->name = 'd';
	    $this->assertTrue($obj->replace('pre_test'), "replace");
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	    	    
	    $GLOBALS['ADODB_CACHE_CLASS'] =  'ADODB_Cache_File';
	    $arr = require(dirname(__FILE__).'/config_adodb.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (2,'b')");
	    $obj = new core;
	    $obj->id = null;
	    $obj->name = 'c';
	    $this->assertTrue($obj->replace('pre_test'), "replace");
	    $this->assertSame('3',$obj->id, "replace");
	    $obj->id = 3;
	    $obj->name = 'd';
	    $this->assertTrue($obj->replace('pre_test'), "replace");
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	    	    
	}
	
	/**
	 * Tests core::structs()
	 */
	public function testStructs() {
	    
	    $arr = require_once(dirname(__FILE__).'/test.php');

	    $arr = array('a'=>'b');
	    $arr2 = array('a'=>'c');
	    $obj = new core;
	    $obj->a = 'b';
	    $obj2 = new core;
	    $obj2->a = 'c';
	    $test = new test;
	    $test->a = 'b';
	    $test2 = new test;
	    $test2->a = 'c';
	    
	    $this->assertEquals(array(new core),core::structs());
	    $this->assertEquals(array(),core::structs(''));
	    $this->assertEquals(array(new core,new core),core::structs(2));
	    $this->assertEquals(array($obj),core::structs(array($arr)));
	    $this->assertEquals(array($obj,$obj2),core::structs(array($arr,$arr2)));
	    $this->assertEquals(array($obj,$obj2),core::structs(array($obj,$arr2)));
	    $this->assertEquals(array(new core,$obj2),core::structs(array('',$arr2)));

	    $this->assertEquals(array(array()),core::structs(null,''));
	    $this->assertEquals(array(),core::structs('',''));
	    $this->assertEquals(array(array(),array()),core::structs(2,''));
	    $this->assertEquals(array($arr),core::structs(array($arr),''));
	    $this->assertEquals(array($arr,$arr2),core::structs(array($arr,$arr2),''));
	    $this->assertEquals(array($arr,$arr2),core::structs(array($obj,$arr2),''));
	    $this->assertEquals(array(array(),$arr2),core::structs(array('',$arr2),''));

	    $this->assertEquals(array(new test),core::structs(null,'test'));
	    $this->assertEquals(array(),core::structs('','test'));
	    $this->assertEquals(array(new test,new test),core::structs(2,'test'));
	    $this->assertEquals(array($test),core::structs(array($arr),'test'));
	    $this->assertEquals(array($test,$test2),core::structs(array($arr,$arr2),'test'));
	    $this->assertEquals(array($test,$test2),core::structs(array($obj,$arr2),'test'));
	    $this->assertEquals(array(new test,$test2),core::structs(array('',$arr2),'test'));

	    $this->assertEquals(new test,core::structs(null,array('test')));
	    $this->assertEquals(array(),core::structs('',array('test')));
	    $this->assertEquals(new test,core::structs(2,array('test')));
	    $this->assertEquals($test,core::structs(array($arr),array('test')));
	    $this->assertEquals($test2,core::structs(array($arr,$arr2),array('test')));
	    $this->assertEquals($test2,core::structs(array($obj,$arr2),array('test')));
	    $this->assertEquals($test2,core::structs(array('',$arr2),array('test')));

	    $this->assertEquals(array(''=>new test),core::structs(null,array('a','test')));
	    $this->assertEquals(array(),core::structs('',array('a','test')));
	    $this->assertEquals(array(''=>new test),core::structs(2,array('a','test')));
	    $this->assertEquals(array('b'=>$test),core::structs(array($arr),array('a','test')));
	    $this->assertEquals(array('b'=>$test,'c'=>$test2),core::structs(array($arr,$arr2),array('a','test')));
	    $this->assertEquals(array('b'=>$test,'c'=>$test2),core::structs(array($obj,$arr2),array('a','test')));
	    $this->assertEquals(array(''=>new test,'c'=>$test2),core::structs(array('',$arr2),array('a','test')));

	    $this->assertEquals(array(''=>array(new test)),core::structs(null,array('a','','test')));
	    $this->assertEquals(array(),core::structs('',array('a','','test')));
	    $this->assertEquals(array(''=>array(new test,new test)),core::structs(2,array('a','','test')));
	    $this->assertEquals(array('b'=>array($test)),core::structs(array($arr),array('a','','test')));
	    $this->assertEquals(array('b'=>array($test),'c'=>array($test2)),core::structs(array($arr,$arr2),array('a','','test')));
	    $this->assertEquals(array('b'=>array($test),'c'=>array($test2)),core::structs(array($obj,$arr2),array('a','','test')));
	    $this->assertEquals(array(''=>array(new test),'c'=>array($test2)),core::structs(array('',$arr2),array('a','','test')));

	    $this->assertEquals(array(null),core::structs(null,array('','a'=>'')));
	    $this->assertEquals(array(),core::structs('',array('','a'=>'')));
	    $this->assertEquals(array(null,null),core::structs(2,array('','a'=>'')));
	    $this->assertEquals(array('b'),core::structs(array($arr),array('','a'=>'')));
	    $this->assertEquals(array('b','c'),core::structs(array($arr,$arr2),array('','a'=>'')));
	    $this->assertEquals(array('b','c'),core::structs(array($obj,$arr2),array('','a'=>'')));
	    $this->assertEquals(array(null,'c'),core::structs(array('',$arr2),array('','a'=>'')));
	    
	    $this->assertEquals(array(''=>null),core::structs(null,array('a','a'=>'')));
	    $this->assertEquals(array(),core::structs('',array('a','a'=>'')));
	    $this->assertEquals(array(''=>null),core::structs(2,array('a','a'=>'')));
	    $this->assertEquals(array('b'=>'b'),core::structs(array($arr),array('a','a'=>'')));
	    $this->assertEquals(array('b'=>'b','c'=>'c'),core::structs(array($arr,$arr2),array('a','a'=>'')));
	    $this->assertEquals(array('b'=>'b','c'=>'c'),core::structs(array($obj,$arr2),array('a','a'=>'')));
	    $this->assertEquals(array(''=>null,'c'=>'c'),core::structs(array('',$arr2),array('a','a'=>'')));
	    
	}
	
	/**
	 * Tests core::selects()
	 */
	public function testSelects() {
	    
	    $arr = require_once(dirname(__FILE__).'/test.php');

	    $arr1 = array('id'=>1,'name'=>'a');
	    $arr2 = array('id'=>2,'name'=>'b');
	    $obj = new core;
	    $obj->id = 1;
	    $obj->name = 'a';
	    $obj2 = new core;
	    $obj2->id = 2;
	    $obj2->name = 'b';
	    $test = new test;
	    $test->id = 1;
	    $test->name = 'a';
	    $test2 = new test;
	    $test2->id = 2;
	    $test2->name = 'b';
	    
	    $arr = require(dirname(__FILE__).'/config_mysql.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'a'),(2,'b')");
        $this->assertFalse(core::selects());
	    $this->assertFalse(core::selects('*'));
	    $this->assertEquals(array($obj,$obj2),core::selects('*','pre_test'));
	    $this->assertEquals(array($obj2),core::selects('*','pre_test',array('id'=>2)));
	    $this->assertEquals(array($obj),core::selects('*','pre_test',null,array('page'=>&$page)));
	    $this->assertEquals(array('size'=>1,'page'=>1,'count'=>2,'total'=>2),$page);
	    $page['page'] = 2;
	    $this->assertEquals(array($obj2),core::selects('*','pre_test',null,array('page'=>&$page)));
	    $this->assertEquals(array('size'=>1,'page'=>2,'count'=>2,'total'=>2),$page);
	    $page['size'] = 2;
	    $page['page'] = 1;
	    $this->assertEquals(array($obj,$obj2),core::selects('*','pre_test',null,array('page'=>&$page)));
	    $this->assertEquals(array('size'=>2,'page'=>1,'count'=>2,'total'=>1),$page);
	    unset($page);
	    $this->assertEquals(array($test,$test2),core::selects('*',null,null,null,'test'));
	    $this->assertEquals(array($arr1,$arr2),core::selects('*','pre_test',null,null,''));
	    $this->assertEquals($arr2,core::selects('*','pre_test',null,null,array('')));
	    $this->assertEquals(array('a','b'),core::selects('*','pre_test',null,null,array('','name'=>'')));
	    $this->assertEquals(array('a','b'),core::selects('*',null,null,null,array('','name'=>'test')));
	    $this->assertEquals(array('b','a'),core::selects('*',null,null,'order by id desc',array('','name'=>'test')));
	    $this->assertEquals('b',core::selects('*',null,null,null,array('name'=>'test')));
    	if (version_compare(PHP_VERSION, '5.3.0','>=')) {
	        $this->assertEquals(array($test,$test2),test::selects());
    	}else{
    	    $this->assertFalse(test::selects());
    	}
        core::execute("DROP TABLE pre1_test");
	    core::connect(false);	

	    $arr = require(dirname(__FILE__).'/config_pdo.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'a'),(2,'b')");
        $this->assertFalse(core::selects());
	    $this->assertFalse(core::selects('*'));
	    $this->assertEquals(array($obj,$obj2),core::selects('*','pre_test'));
	    $this->assertEquals(array($obj2),core::selects('*','pre_test',array('id'=>2)));
	    $this->assertEquals(array($obj),core::selects('*','pre_test',null,array('page'=>&$page)));
	    $this->assertEquals(array('size'=>1,'page'=>1,'count'=>2,'total'=>2),$page);
	    $page['page'] = 2;
	    $this->assertEquals(array($obj2),core::selects('*','pre_test',null,array('page'=>&$page)));
	    $this->assertEquals(array('size'=>1,'page'=>2,'count'=>2,'total'=>2),$page);
	    $page['size'] = 2;
	    $page['page'] = 1;
	    $this->assertEquals(array($obj,$obj2),core::selects('*','pre_test',null,array('page'=>&$page)));
	    $this->assertEquals(array('size'=>2,'page'=>1,'count'=>2,'total'=>1),$page);
	    unset($page);
	    $this->assertEquals(array($test,$test2),core::selects('*',null,null,null,'test'));
	    $this->assertEquals(array($arr1,$arr2),core::selects('*','pre_test',null,null,''));
	    $this->assertEquals($arr2,core::selects('*','pre_test',null,null,array('')));
	    $this->assertEquals(array('a','b'),core::selects('*','pre_test',null,null,array('','name'=>'')));
	    $this->assertEquals(array('a','b'),core::selects('*',null,null,null,array('','name'=>'test')));
	    $this->assertEquals(array('b','a'),core::selects('*',null,null,'order by id desc',array('','name'=>'test')));
	    $this->assertEquals('b',core::selects('*',null,null,null,array('name'=>'test')));
    	if (version_compare(PHP_VERSION, '5.3.0','>=')) {
	        $this->assertEquals(array($test,$test2),test::selects());
    	}else{
    	    $this->assertFalse(test::selects());
    	}
        core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	    
	    $GLOBALS['ADODB_CACHE_CLASS'] =  'ADODB_Cache_File';
	    $arr = require(dirname(__FILE__).'/config_adodb.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'a'),(2,'b')");
        $this->assertFalse(core::selects());
	    $this->assertFalse(core::selects('*'));
	    $this->assertEquals(array($obj,$obj2),core::selects('*','pre_test'));
	    $this->assertEquals(array($obj2),core::selects('*','pre_test',array('id'=>2)));
	    $this->assertEquals(array($obj),core::selects('*','pre_test',null,array('page'=>&$page)));
	    $this->assertEquals(array('size'=>1,'page'=>1,'count'=>2,'total'=>2),$page);
	    $page['page'] = 2;
	    $this->assertEquals(array($obj2),core::selects('*','pre_test',null,array('page'=>&$page)));
	    $this->assertEquals(array('size'=>1,'page'=>2,'count'=>2,'total'=>2),$page);
	    $page['size'] = 2;
	    $page['page'] = 1;
	    $this->assertEquals(array($obj,$obj2),core::selects('*','pre_test',null,array('page'=>&$page)));
	    $this->assertEquals(array('size'=>2,'page'=>1,'count'=>2,'total'=>1),$page);
	    unset($page);
	    $this->assertEquals(array($test,$test2),core::selects('*',null,null,null,'test'));
	    $this->assertEquals(array($arr1,$arr2),core::selects('*','pre_test',null,null,''));
	    $this->assertEquals($arr2,core::selects('*','pre_test',null,null,array('')));
	    $this->assertEquals(array('a','b'),core::selects('*','pre_test',null,null,array('','name'=>'')));
	    $this->assertEquals(array('a','b'),core::selects('*',null,null,null,array('','name'=>'test')));
	    $this->assertEquals(array('b','a'),core::selects('*',null,null,'order by id desc',array('','name'=>'test')));
	    $this->assertEquals('b',core::selects('*',null,null,null,array('name'=>'test')));
    	if (version_compare(PHP_VERSION, '5.3.0','>=')) {
	        $this->assertEquals(array($test,$test2),test::selects());
    	}else{
    	    $this->assertFalse(test::selects());
    	}
        core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	    
	}
	
	/**
	 * Tests core::inserts()
	 */
	public function testInserts() {
	    
	    $arr = require_once(dirname(__FILE__).'/test.php');

	    $arr1 = array('id'=>1,'name'=>'a');
	    $arr2 = array('id'=>2,'name'=>'b');
	    $obj = new core;
	    $obj->id = 1;
	    $obj->name = 'a';
	    $obj2 = new core;
	    $obj2->id = 2;
	    $obj2->name = 'b';
	    $test = new test;
	    $test->id = 1;
	    $test->name = 'a';
	    $test2 = new test;
	    $test2->id = 2;
	    $test2->name = 'b';
	    
	    $arr = require(dirname(__FILE__).'/config_mysql.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
        $this->assertFalse(core::inserts());
        $this->assertSame(1,core::inserts('pre_test',$arr1));
        $this->assertEquals(array($obj),core::selects('*','pre_test'));
        $this->assertSame(0,core::inserts('pre_test',$arr1));
        $this->assertEquals(array($obj),core::selects('*','pre_test'));
        $this->assertSame(1,core::inserts('pre_test',$arr2));
        $this->assertEquals(array($obj,$obj2),core::selects('*','pre_test'));
        core::execute("TRUNCATE TABLE pre1_test");
        $this->assertSame(2,core::inserts('pre_test',array_keys($arr1),array($arr1,$arr2)));
        $this->assertEquals(array($obj,$obj2),core::selects('*','pre_test'));
        core::execute("TRUNCATE TABLE pre1_test");
        $this->assertSame(2,core::inserts('pre_test','',array($arr1,$arr2)));
        $this->assertEquals(array($obj,$obj2),core::selects('*','pre_test'));
        $this->assertSame(4,core::inserts('pre_test','',array($arr1,$arr2),'ON DUPLICATE KEY UPDATE id=id*10'));
        core::execute("DROP TABLE pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20),age int)");
        $this->assertSame(0,core::inserts('pre_test','',array($arr1,$arr2)));
	    $this->assertSame(2,core::inserts('pre_test',null,array($arr1,$arr2)));
        $this->assertEquals(2,(int)core::selects('count(*)','pre_test',null,null,array('count(*)'=>null)));
	    $this->assertSame(4,core::inserts('pre_test',null,array($arr1,$arr2),'ON DUPLICATE KEY UPDATE id=id*10'));
        $this->assertEquals(2,(int)core::selects('count(*)','pre_test',null,null,array('count(*)'=>null)));
	    core::execute("DROP TABLE IF EXISTS pre1_test2");
        core::execute("CREATE TABLE pre1_test2(id int auto_increment primary key,name varchar(20))");
        $this->assertSame(2,core::inserts('pre_test2',array('id','name'),null,'SELECT id,name FROM pre_test'));
        core::execute("DROP TABLE pre1_test2");
        core::execute("DROP TABLE pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    if (version_compare(PHP_VERSION, '5.3.0','>=')) {
	        $this->assertSame(2,test::inserts(null,null,array($arr1,$arr2)));
    	}else{
    	    $this->assertFalse(test::inserts(null,null,array($arr1,$arr2)));
    	}
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	

	    $arr = require(dirname(__FILE__).'/config_pdo.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
        $this->assertFalse(core::inserts());
        $this->assertSame(1,core::inserts('pre_test',$arr1));
        $this->assertEquals(array($obj),core::selects('*','pre_test'));
        $this->assertSame(0,core::inserts('pre_test',$arr1));
        $this->assertEquals(array($obj),core::selects('*','pre_test'));
        $this->assertSame(1,core::inserts('pre_test',$arr2));
        $this->assertEquals(array($obj,$obj2),core::selects('*','pre_test'));
        core::execute("TRUNCATE TABLE pre1_test");
        $this->assertSame(2,core::inserts('pre_test',array_keys($arr1),array($arr1,$arr2)));
        $this->assertEquals(array($obj,$obj2),core::selects('*','pre_test'));
        core::execute("TRUNCATE TABLE pre1_test");
        $this->assertSame(2,core::inserts('pre_test','',array($arr1,$arr2)));
        $this->assertEquals(array($obj,$obj2),core::selects('*','pre_test'));
        $this->assertSame(4,core::inserts('pre_test','',array($arr1,$arr2),'ON DUPLICATE KEY UPDATE id=id*10'));
        core::execute("DROP TABLE pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20),age int)");
        $this->assertSame(0,core::inserts('pre_test','',array($arr1,$arr2)));
	    $this->assertSame(2,core::inserts('pre_test',null,array($arr1,$arr2)));
        $this->assertEquals(2,(int)core::selects('count(*)','pre_test',null,null,array('count(*)'=>null)));
	    $this->assertSame(4,core::inserts('pre_test',null,array($arr1,$arr2),'ON DUPLICATE KEY UPDATE id=id*10'));
        $this->assertEquals(2,(int)core::selects('count(*)','pre_test',null,null,array('count(*)'=>null)));
	    core::execute("DROP TABLE IF EXISTS pre1_test2");
        core::execute("CREATE TABLE pre1_test2(id int auto_increment primary key,name varchar(20))");
        $this->assertSame(2,core::inserts('pre_test2',array('id','name'),null,'SELECT id,name FROM pre_test'));
        core::execute("DROP TABLE pre1_test2");
        core::execute("DROP TABLE pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    if (version_compare(PHP_VERSION, '5.3.0','>=')) {
	        $this->assertSame(2,test::inserts(null,null,array($arr1,$arr2)));
    	}else{
    	    $this->assertFalse(test::inserts(null,null,array($arr1,$arr2)));
    	}
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	    	    	    
	    $GLOBALS['ADODB_CACHE_CLASS'] =  'ADODB_Cache_File';
	    $arr = require(dirname(__FILE__).'/config_adodb.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
        $this->assertFalse(core::inserts());
        $this->assertSame(1,core::inserts('pre_test',$arr1));
        $this->assertEquals(array($obj),core::selects('*','pre_test'));
        $this->assertSame(0,core::inserts('pre_test',$arr1));
        $this->assertEquals(array($obj),core::selects('*','pre_test'));
        $this->assertSame(1,core::inserts('pre_test',$arr2));
        $this->assertEquals(array($obj,$obj2),core::selects('*','pre_test'));
        core::execute("TRUNCATE TABLE pre1_test");
        $this->assertSame(2,core::inserts('pre_test',array_keys($arr1),array($arr1,$arr2)));
        $this->assertEquals(array($obj,$obj2),core::selects('*','pre_test'));
        core::execute("TRUNCATE TABLE pre1_test");
        $this->assertSame(2,core::inserts('pre_test','',array($arr1,$arr2)));
        $this->assertEquals(array($obj,$obj2),core::selects('*','pre_test'));
        $this->assertSame(4,core::inserts('pre_test','',array($arr1,$arr2),'ON DUPLICATE KEY UPDATE id=id*10'));
        core::execute("DROP TABLE pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20),age int)");
        $this->assertSame(0,core::inserts('pre_test','',array($arr1,$arr2)));
	    $this->assertSame(2,core::inserts('pre_test',null,array($arr1,$arr2)));
        $this->assertEquals(2,(int)core::selects('count(*)','pre_test',null,null,array('count(*)'=>null)));
	    $this->assertSame(4,core::inserts('pre_test',null,array($arr1,$arr2),'ON DUPLICATE KEY UPDATE id=id*10'));
        $this->assertEquals(2,(int)core::selects('count(*)','pre_test',null,null,array('count(*)'=>null)));
	    core::execute("DROP TABLE IF EXISTS pre1_test2");
        core::execute("CREATE TABLE pre1_test2(id int auto_increment primary key,name varchar(20))");
        $this->assertSame(2,core::inserts('pre_test2',array('id','name'),null,'SELECT id,name FROM pre_test'));
        core::execute("DROP TABLE pre1_test2");
        core::execute("DROP TABLE pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    if (version_compare(PHP_VERSION, '5.3.0','>=')) {
	        $this->assertSame(2,test::inserts(null,null,array($arr1,$arr2)));
    	}else{
    	    $this->assertFalse(test::inserts(null,null,array($arr1,$arr2)));
    	}
	    core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	    	    	    
	}
	
	/**
	 * Tests core::updates()
	 */
	public function testUpdates() {
	    
	    $arr = require_once(dirname(__FILE__).'/test.php');

	    $arr1 = array('id'=>1,'name'=>'a');
	    $arr2 = array('id'=>2,'name'=>'b');
	    $obj = new core;
	    $obj->id = 1;
	    $obj->name = 'a';
	    $obj2 = new core;
	    $obj2->id = 2;
	    $obj2->name = 'b';
	    $test = new test;
	    $test->id = 1;
	    $test->name = 'a';
	    $test2 = new test;
	    $test2->id = 2;
	    $test2->name = 'b';
	    
	    $arr = require(dirname(__FILE__).'/config_mysql.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'a'),(2,'b')");
        $this->assertSame(0,core::updates('pre_test',array('name'=>'a'),array('id'=>1)));
	    $this->assertSame(1,core::updates('pre_test',array('name'=>'c'),array('id'=>1)));
        $this->assertSame(1,core::updates(null,array('name'=>'a'),array('id'=>1),null,'test'));
    	if (version_compare(PHP_VERSION, '5.3.0','>=')) {
	        $this->assertSame(1,test::updates(null,array('name'=>'c'),array('id'=>1)));
	        $this->assertSame(1,test::updates(null,array("name='a'"),array('id'=>1)));
    	}else{
	        $this->assertFalse(test::updates(null,array('name'=>'c'),array('id'=>1)));
	        $this->assertFalse(test::updates(null,array("name='a'"),array('id'=>1)));    
    	}       
        core::execute("DROP TABLE pre1_test");
	    core::connect(false);	

	    $arr = require(dirname(__FILE__).'/config_pdo.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'a'),(2,'b')");
        $this->assertSame(0,core::updates('pre_test',array('name'=>'a'),array('id'=>1)));
	    $this->assertSame(1,core::updates('pre_test',array('name'=>'c'),array('id'=>1)));
        $this->assertSame(1,core::updates(null,array('name'=>'a'),array('id'=>1),null,'test'));
    	if (version_compare(PHP_VERSION, '5.3.0','>=')) {
	        $this->assertSame(1,test::updates(null,array('name'=>'c'),array('id'=>1)));
	        $this->assertSame(1,test::updates(null,array("name='a'"),array('id'=>1)));
    	}else{
	        $this->assertFalse(test::updates(null,array('name'=>'c'),array('id'=>1)));
	        $this->assertFalse(test::updates(null,array("name='a'"),array('id'=>1)));   
    	}       
        core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	    
	    $GLOBALS['ADODB_CACHE_CLASS'] =  'ADODB_Cache_File';
	    $arr = require(dirname(__FILE__).'/config_adodb.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'a'),(2,'b')");
        $this->assertSame(0,core::updates('pre_test',array('name'=>'a'),array('id'=>1)));
	    $this->assertSame(1,core::updates('pre_test',array('name'=>'c'),array('id'=>1)));
        $this->assertSame(1,core::updates(null,array('name'=>'a'),array('id'=>1),null,'test'));
    	if (version_compare(PHP_VERSION, '5.3.0','>=')) {
	        $this->assertSame(1,test::updates(null,array('name'=>'c'),array('id'=>1)));
	        $this->assertSame(1,test::updates(null,array("name='a'"),array('id'=>1)));
    	}else{
	        $this->assertFalse(test::updates(null,array('name'=>'c'),array('id'=>1)));
	        $this->assertFalse(test::updates(null,array("name='a'"),array('id'=>1)));   
    	}       
        core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	    
	}
	
	/**
	 * Tests core::deletes()
	 */
	public function testDeletes() {
	    
	    $arr = require_once(dirname(__FILE__).'/test.php');

	    $arr1 = array('id'=>1,'name'=>'a');
	    $arr2 = array('id'=>2,'name'=>'b');
	    $obj = new core;
	    $obj->id = 1;
	    $obj->name = 'a';
	    $obj2 = new core;
	    $obj2->id = 2;
	    $obj2->name = 'b';
	    $test = new test;
	    $test->id = 1;
	    $test->name = 'a';
	    $test2 = new test;
	    $test2->id = 2;
	    $test2->name = 'b';
	    
	    $arr = require(dirname(__FILE__).'/config_mysql.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'a'),(2,'b')");
        $this->assertSame(0,core::deletes(null,'pre_test',array('id'=>3)));
        $this->assertSame(1,core::deletes(null,'pre_test',array('id'=>2)));
        $this->assertSame(0,core::deletes(null,'pre_test',array('id'=>2)));
        if (version_compare(PHP_VERSION, '5.3.0','>=')) {
            $this->assertSame(1,test::deletes(null,null,array('id'=>1)));
    	}else{
            $this->assertFalse(test::deletes(null,null,array('id'=>1)));
    	}       
        core::execute("DROP TABLE pre1_test");
	    core::connect(false);	

	    $arr = require(dirname(__FILE__).'/config_pdo.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'a'),(2,'b')");
        $this->assertSame(0,core::deletes(null,'pre_test',array('id'=>3)));
        $this->assertSame(1,core::deletes(null,'pre_test',array('id'=>2)));
        $this->assertSame(0,core::deletes(null,'pre_test',array('id'=>2)));
        if (version_compare(PHP_VERSION, '5.3.0','>=')) {
            $this->assertSame(1,test::deletes(null,null,array('id'=>1)));
    	}else{
            $this->assertFalse(test::deletes(null,null,array('id'=>1)));
    	}       
        core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	    
	    $GLOBALS['ADODB_CACHE_CLASS'] =  'ADODB_Cache_File';
	    $arr = require(dirname(__FILE__).'/config_adodb.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'a'),(2,'b')");
        $this->assertSame(0,core::deletes(null,'pre_test',array('id'=>3)));
        $this->assertSame(1,core::deletes(null,'pre_test',array('id'=>2)));
        $this->assertSame(0,core::deletes(null,'pre_test',array('id'=>2)));
        if (version_compare(PHP_VERSION, '5.3.0','>=')) {
            $this->assertSame(1,test::deletes(null,null,array('id'=>1)));
    	}else{
            $this->assertFalse(test::deletes(null,null,array('id'=>1)));
    	}       
        core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	}
	
	/**
	 * Tests core::replaces()
	 */
	public function testReplaces() {
	    
	    $arr = require_once(dirname(__FILE__).'/test.php');

	    $arr1 = array('id'=>1,'name'=>'a');
	    $arr2 = array('id'=>2,'name'=>'b');
	    $obj = new core;
	    $obj->id = 1;
	    $obj->name = 'a';
	    $obj2 = new core;
	    $obj2->id = 2;
	    $obj2->name = 'b';
	    $test = new test;
	    $test->id = 1;
	    $test->name = 'a';
	    $test2 = new test;
	    $test2->id = 2;
	    $test2->name = 'b';
	    
	    $arr = require(dirname(__FILE__).'/config_mysql.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'a'),(2,'b')");
        $this->assertSame(2,core::replaces('pre_test',array('id'=>1,'name'=>'a')));
	    $this->assertSame(1,core::replaces('pre_test',array('id'=>3,'name'=>'c')));
	    $this->assertSame(1,core::replaces('pre_test',array('id','name'),array(4,"'d'")));
	    $this->assertSame(1,core::replaces('pre_test',array('id','name'),array('id'=>5,'name'=>'e')));
	    $this->assertSame(1,core::replaces('pre_test',array('id','name'),array('id'=>6,'name'=>'f')));
	    $this->assertSame(1,core::replaces('pre_test',null,array('id'=>7,'name'=>'g')));
	    if (version_compare(PHP_VERSION, '5.3.0','>=')) {
	        $this->assertSame(1,test::replaces(null,array('id'=>8,'name'=>'h')));
    	}else{
	        $this->assertFalse(test::replaces(null,array('id'=>8,'name'=>'h')));
    	}      
        core::execute("DROP TABLE pre1_test");
	    core::connect(false);	

	    $arr = require(dirname(__FILE__).'/config_pdo.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'a'),(2,'b')");
        $this->assertSame(2,core::replaces('pre_test',array('id'=>1,'name'=>'a')));
	    $this->assertSame(1,core::replaces('pre_test',array('id'=>3,'name'=>'c')));
	    $this->assertSame(1,core::replaces('pre_test',array('id','name'),array(4,"'d'")));
	    $this->assertSame(1,core::replaces('pre_test',array('id','name'),array('id'=>5,'name'=>'e')));
	    $this->assertSame(1,core::replaces('pre_test',array('id','name'),array('id'=>6,'name'=>'f')));
	    $this->assertSame(1,core::replaces('pre_test',null,array('id'=>7,'name'=>'g')));
	    if (version_compare(PHP_VERSION, '5.3.0','>=')) {
	        $this->assertSame(1,test::replaces(null,array('id'=>8,'name'=>'h')));
    	}else{
	        $this->assertFalse(test::replaces(null,array('id'=>8,'name'=>'h')));
    	}      
        core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	    
	    $GLOBALS['ADODB_CACHE_CLASS'] =  'ADODB_Cache_File';
	    $arr = require(dirname(__FILE__).'/config_adodb.php');
	    $arr['dbname'] = 'test';
	    $arr['prefix_search'] = 'pre_';
	    $arr['prefix_replace'] = 'pre1_';
	    core::connect($arr);
	    core::execute("DROP TABLE IF EXISTS pre1_test");
	    core::execute("CREATE TABLE pre1_test(id int auto_increment primary key,name varchar(20))");
	    core::execute("INSERT INTO pre1_test(id,name) VALUES (1,'a'),(2,'b')");
        $this->assertSame(2,core::replaces('pre_test',array('id'=>1,'name'=>'a')));
	    $this->assertSame(1,core::replaces('pre_test',array('id'=>3,'name'=>'c')));
	    $this->assertSame(1,core::replaces('pre_test',array('id','name'),array(4,"'d'")));
	    $this->assertSame(1,core::replaces('pre_test',array('id','name'),array('id'=>5,'name'=>'e')));
	    $this->assertSame(1,core::replaces('pre_test',array('id','name'),array('id'=>6,'name'=>'f')));
	    $this->assertSame(1,core::replaces('pre_test',null,array('id'=>7,'name'=>'g')));
	    if (version_compare(PHP_VERSION, '5.3.0','>=')) {
	        $this->assertSame(1,test::replaces(null,array('id'=>8,'name'=>'h')));
    	}else{
	        $this->assertFalse(test::replaces(null,array('id'=>8,'name'=>'h')));
    	}      
        core::execute("DROP TABLE pre1_test");
	    core::connect(false);	
	    
	}

}

