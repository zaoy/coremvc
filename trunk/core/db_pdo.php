<?php
/**
 * 定义(define)
 */
class db_pdo {
	
	/**
	 * 连接数据库
	 * @param array $args
	 * @return dbh
	 */
	public static function connect($args) {
		$dsn = $args ['connect_dsn'];
		$username = $args ['connect_username'];
		$password = $args ['connect_password'];
		$driver_options = $args ['connect_driver_options'];
		if ( is_array( $driver_options ) ) {
			return new PDO ( $dsn, $username, $password, $driver_options );
		} else {
			return new PDO ( $dsn, $username, $password );
		}
	}
	
	/**
	 * 断开数据库
	 * @param dbh $dbh
	 * @param array $args
	 * @return bool
	 */
	public static function disconnect($dbh, $args) {
		if(is_object ( $dbh ) && get_class($dbh) === 'PDO'){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 执行语句
	 * @param dbh $dbh
	 * @param array $args
	 * @param string $class
	 * @param string $sql
	 * @param array $param
	 * @param array &$ref
	 * @return sth
	 */
	public static function execute($dbh, $args, $class, $sql, $param = null, &$ref = null) {
		if (is_array ( $param )) {
			if (is_array ( $ref )) {
				unset($ref['insert_id']);
				unset($ref['affected_rows']);
				unset($ref['num_fields']);
				unset($ref['num_rows']);
				$sth = $dbh->prepare ( $sql, $ref );
				$result = $sth->execute ( $param );
			} else {
				$sth = $dbh->prepare ( $sql );
				$result = $sth->execute ( $param );
			}
		} else {
			if (is_array ( $ref )) {
				unset($ref['insert_id']);
				unset($ref['affected_rows']);
				unset($ref['num_fields']);
				unset($ref['num_rows']);
				$ref = array_values($ref);
				array_unshift ( $ref, $sql );
				$result = $sth = call_user_func_array ( array ($dbh, 'query' ), $ref );
			} else {
				$result = $sth = $dbh->query ( $sql );
			}
		}
		if ($args ['debug_enable'] === true) {
			if ($result === false) {
				$err = $dbh->errorInfo();
				$extra = array('errno'=>$err[1],'error'=>$err[2]);
			} else {
				$extra = null;
			}
			call_user_func ( array($class,'prepare'), $sql, $param, null, true, $args ['debug_file'], $extra );
		}
		if(func_num_args()>4){
			$ref = array();
			$ref ['insert_id'] = $dbh->lastInsertId();
			$ref ['affected_rows'] = is_object($sth)?$sth->rowCount():0;
			$ref ['num_fields'] = is_object($sth)?$sth->columnCount():0;
			$ref ['num_rows'] = is_object($sth)?$sth->rowCount():0;
		}
		return $sth;
	}
	
	/**
	 * 准备SQL语句
	 * @param dbh $dbh
	 * @param array $args
	 * @param string $class
	 * @param string $sql
	 * @param array $param
	 * @param bool $format
	 * @param bool $debug
	 * @param string $output
	 * @param array $extra
	 * @return mix
	 */
	public static function prepare($dbh, $args, $class, $sql, $param = null, $format = null, $debug = null, $output = null, $extra = null) {
		return call_user_func ( array($class,'prepare'), 'mysql_' . $sql, $param, $format, $debug, $output, $extra );
	}
	
	/**
	 * 自增序列
	 * @param dbh $dbh
	 * @param array $args
	 * @param string $class
	 * @param string $tablename
	 * @param int $start_index
	 * @return int
	 */
	public static function sequence($dbh, $args, $class, $tablename, $start_index) {
		$result = $dbh->exec ( 'UPDATE ' . $tablename . ' SET id=LAST_INSERT_ID(id+1)' );
		if ( $result === false ) {
			$dbh->exec ( 'CREATE TABLE ' . $tablename . ' (id INT NOT NULL)');
			$sth = $dbh->query ( 'SELECT COUNT(*) FROM ' . $tablename . ' LIMIT 1' );
			if ($sth->fetchColumn () == 0) {
				$dbh->exec ( 'INSERT INTO ' . $tablename . ' VALUES (' . ($start_index-1) . ')' );
			}
			$dbh->exec ( 'UPDATE ' . $tablename . ' SET id=LAST_INSERT_ID(id+1)' );
		}
		$return = $dbh->lastInsertId ();
		if ( $return === false ) {
			return false;
		}
		if ( $start_index>$return ) {
			$dbh->exec ( 'UPDATE ' . $tablename . ' SET id=' . $start_index);
			$return = $start_index;
		}
		return (int)$return;
	}
	
	/**
	 * 对象选择
	 * @param dbh $dbh
	 * @param array $args
	 * @param string $class
	 * @param string $sql
	 * @param array $param
	 * @param array $ref
	 * @return array
	 */
	public static function selects($dbh, $args, $class, $sql, $param, $ref) {
		$page = &$ref['page'];
		$class_arr = $ref['class_arr'];
		$classkey = $ref['classkey'];
		$classkey_arr = $ref['classkey_arr'];
		$classname = $ref['classname'];
		$calledclass = $ref['calledclass'];
		if($page !== null){
			if($page['count'] === null){
				$sql = preg_replace('/SELECT/i','SELECT SQL_CALC_FOUND_ROWS',$sql,1);
			}
			$limit = 'LIMIT '.($page['size']*($page['page']-1)).','.$page['size'];
			if(isset($page['limit'])){
				$sql = preg_replace('/(.*)'.$page['limit'].'/i','$1'.$limit,$sql,1);
			} else {
				$sql .= ' '.$limit;
			}
		}
		$data_key = array ();
		foreach($class_arr as $value){
			if($value!==null && $value!=='' && !in_array($value,$data_key,true)){
				$data_key[] = $value;
			}
		}
		$sth = $dbh->prepare ( $sql, array (PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY  ) );
		foreach($param as $key=>$value){
			if(is_int($key)){
				$key++;
			}
			if(is_null($value)){
				$sth->bindValue($key, null, PDO::PARAM_NULL);
			}elseif(is_bool($value)){
				$sth->bindValue($key, $value?1:0, PDO::PARAM_INT);
			}elseif(is_int($value) || is_float($value)){
				$sth->bindValue($key, $value, PDO::PARAM_INT);
			}else{
				$sth->bindValue($key, (string)$value, PDO::PARAM_STR);
			}
		}
		$result = $sth->execute ();
		if ($args ['debug_enable'] === true) {
			if ($result === false) {
				$err = $dbh->errorInfo();
				$extra = array('errno'=>$err[1],'error'=>$err[2]);
			} else {
				$extra = null;
			}
			call_user_func ( array($class,'prepare'), $sql, $param, null, true, $args ['debug_file'], $extra );
		}
		if ($result === false) {
			return false;
		}
		if($page !== null){
			if($page['count'] === null){
				$result_count = $dbh->query("SELECT FOUND_ROWS()");
				$page ['count'] = (int)$result_count->fetchColumn();
				$result_count->closeCursor();
				$result_count = null;
			}
			$page ['total'] = (int)ceil ( $page ['count'] / $page ['size'] );
		}
		// 数据
		if ($sth->rowCount()===0){
			return array(array (),array ());
		}
		$data_all = array ();
		if($data_key!==array()){
			while ( $obj = $sth->fetch ( PDO::FETCH_BOTH ) ) {
				$obj_arr = array();
				foreach($data_key as $value){
					if(array_key_exists($value,$obj)){
						$obj_arr[$value] = $obj[$value];
					}
				}
				$data_all[] = $obj_arr ;
			}
			$result = $sth->execute ();
		}
		$data_arr = array ();
		switch ($classkey) {
			case 'assoc' :
				$data_arr = $sth->fetchAll(PDO::FETCH_ASSOC);
				break;
			case 'num' :
				$data_arr = $sth->fetchAll(PDO::FETCH_NUM);
				break;
			case 'both' :
			case 'array' :
				$data_arr = $sth->fetchAll(PDO::FETCH_BOTH);
				break;
			case 'column' :
				while ( $obj = $sth->fetch ( PDO::FETCH_BOTH ) ){
					if (isset ( $obj [$classname] )) {
						$data_arr[] = $obj [$classname] ;
					} else {
						$data_arr[] = null ;
					}
				}
				break;
			default :
			case 'class' :
				if ( isset($classkey_arr) && in_array('classtype',$classkey_arr) ) {
					while ( $obj = $sth->fetch ( PDO::FETCH_ASSOC ) ){
						$obj_classname = $classname;
						foreach($obj as $key=>$obj_classname){
							unset($obj[$key]);
							break;
						}
						if(preg_match ( '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $obj_classname ) && class_exists($obj_classname)){
							$clone = new $obj_classname ();
						}elseif(class_exists ( $classname )){
							$clone = new $classname ();
						}else{
							$clone = new $calledclass ();
						}
						foreach($obj as $key=>$value){
							$clone->$key = $value;
						}
						$data_arr[] = $clone ;
					}
				} else {
					if (class_exists ( $classname )) {
						$obj_classname = $classname;
					}else{
						$obj_classname = $calledclass;
					}
					$data_arr = $sth->fetchAll(PDO::FETCH_CLASS,$obj_classname);
				}
				break;
			case 'clone' :
				if (is_object ( $classname )) {
					$obj_classname = $classname;
				}else{
					$obj_classname = new $calledclass ( );
				}
				while ( $obj = $sth->fetch ( PDO::FETCH_ASSOC ) ){
					$clone = clone $obj_classname ;
					foreach($obj as $key=>$value){
						$clone->$key = $value;
					}
					$data_arr[] = $clone ;
				}
				break;
		}
		return array($data_arr,$data_all);
	}
	
	/**
	 * 对象插入
	 * @param dbh $dbh
	 * @param array $args
	 * @param string $class
	 * @param string $sql
	 * @param array $param
	 * @return int
	 */
	public static function inserts($dbh, $args, $class, $sql, $param) {
		$sth = $dbh->prepare ( $sql );
		foreach ( $param as $key=>$value ) {
			if( is_int ($key) ) {
				$key++;
			}
			if( $value === null ) {
				$sth->bindValue($key, $value, PDO::PARAM_NULL);
			} elseif ( is_bool ($value) ){
				$sth->bindValue($key, $value, PDO::PARAM_BOOL);
			} elseif ( is_int ($value) || is_float ($value) ) {
				$sth->bindValue($key, $value, PDO::PARAM_INT);
			} else {
				$sth->bindValue($key, $value, PDO::PARAM_STR);
			}
		}
		$result = $sth->execute ();
		if ($args ['debug_enable'] === true) {
			if ($result === false) {
				$err = $dbh->errorInfo();
				$extra = array('errno'=>$err[1],'error'=>$err[2]);
			} else {
				$extra = null;
			}
			call_user_func ( array($class,'prepare'), $sql, $param, null, true, $args ['debug_file'], $extra );
		}
		return is_object($sth)?$sth->rowCount ():0;
	}
	
	/**
	 * 对象修改
	 * @param dbh $dbh
	 * @param array $args
	 * @param string $class
	 * @param string $sql
	 * @param array $param
	 * @return int
	 */
	public static function updates($dbh, $args, $class, $sql, $param) {
		$sth = $dbh->prepare ( $sql );
		foreach ( $param as $key=>$value ) {
			if( is_int ($key) ) {
				$key++;
			}
			if( $value === null ) {
				$sth->bindValue($key, $value, PDO::PARAM_NULL);
			} elseif ( is_bool ($value) ){
				$sth->bindValue($key, $value, PDO::PARAM_BOOL);
			} elseif ( is_int ($value) || is_float ($value) ) {
				$sth->bindValue($key, $value, PDO::PARAM_INT);
			} else {
				$sth->bindValue($key, $value, PDO::PARAM_STR);
			}
		}
		$result = $sth->execute ();
		if ($args ['debug_enable'] === true) {
			if ($result === false) {
				$err = $dbh->errorInfo();
				$extra = array('errno'=>$err[1],'error'=>$err[2]);
			} else {
				$extra = null;
			}
			call_user_func ( array($class,'prepare'), $sql, $param, null, true, $args ['debug_file'], $extra );
		}
		return is_object($sth)?$sth->rowCount ():0;
	}
	
	/**
	 * 对象删除
	 * @param dbh $dbh
	 * @param array $args
	 * @param string $class
	 * @param string $sql
	 * @param array $param
	 * @return int
	 */
	public static function deletes($dbh, $args, $class, $sql, $param) {
		$sth = $dbh->prepare ( $sql );
		foreach ( $param as $key=>$value ) {
			if( is_int ($key) ) {
				$key++;
			}
			if( $value === null ) {
				$sth->bindValue($key, $value, PDO::PARAM_NULL);
			} elseif ( is_bool ($value) ){
				$sth->bindValue($key, $value, PDO::PARAM_BOOL);
			} elseif ( is_int ($value) || is_float ($value) ) {
				$sth->bindValue($key, $value, PDO::PARAM_INT);
			} else {
				$sth->bindValue($key, $value, PDO::PARAM_STR);
			}
		}
		$result = $sth->execute ();
		if ($args ['debug_enable'] === true) {
			if ($result === false) {
				$err = $dbh->errorInfo();
				$extra = array('errno'=>$err[1],'error'=>$err[2]);
			} else {
				$extra = null;
			}
			call_user_func ( array($class,'prepare'), $sql, $param, null, true, $args ['debug_file'], $extra );
		}
		return is_object($sth)?$sth->rowCount ():0;
	}
	
	/**
	 * 对象更新
	 * @param dbh $dbh
	 * @param array $args
	 * @param string $class
	 * @param string $sql
	 * @param array $param
	 * @return int
	 */
	public static function replaces($dbh, $args, $class, $sql, $param) {
		$sth = $dbh->prepare ( $sql );
		foreach ( $param as $key=>$value ) {
			if( is_int ($key) ) {
				$key++;
			}
			if( $value === null ) {
				$sth->bindValue($key, $value, PDO::PARAM_NULL);
			} elseif ( is_bool ($value) ){
				$sth->bindValue($key, $value, PDO::PARAM_BOOL);
			} elseif ( is_int ($value) || is_float ($value) ) {
				$sth->bindValue($key, $value, PDO::PARAM_INT);
			} else {
				$sth->bindValue($key, $value, PDO::PARAM_STR);
			}
		}
		$result = $sth->execute ();
		if ($args ['debug_enable'] === true) {
			if ($result === false) {
				$err = $dbh->errorInfo();
				$extra = array('errno'=>$err[1],'error'=>$err[2]);
			} else {
				$extra = null;
			}
			call_user_func ( array($class,'prepare'), $sql, $param, null, true, $args ['debug_file'], $extra );
		}
		return is_object($sth)?$sth->rowCount ():0;
	}
	
	/**
	 * 实例选择
	 * @param dbh $dbh
	 * @param array $args
	 * @param object $that
	 * @param string $tablename
	 * @param int $primary_index
	 * @param array $params
	 * @return bool
	 */
	public static function select($dbh, $args, $that, $tablename, $primary_index, $params) {
		extract($params);
		if ($primary_name !== null) {
			$sql = 'SELECT * FROM ' . $tablename . ' WHERE ' . $primary_name . '=? LIMIT 1';
			$sth = $dbh->prepare ( $sql, array (PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY ) );
			$paramvars = array ($primary_value );
			$result = $sth->execute ( $paramvars );
		} else {
			$sql = 'SELECT * FROM ' . $tablename . ' LIMIT 1';
			$paramvars = null;
			$sth = $dbh->prepare ( $sql, array (PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY ) );
			$result = $sth->execute ();
		}
		if ($args ['debug_enable'] === true) {
			if ($result === false) {
				$err = $dbh->errorInfo();
				$extra = array('errno'=>$err[1],'error'=>$err[2]);
			} else {
				$extra = null;
			}
			call_user_func ( array(get_class($that),'prepare'), $sql, $paramvars, null, true, $args ['debug_file'], $extra );
		}
		if ($sth->rowCount () == 0) {
			$sth->closeCursor ();
			return false;
		}
		$row = $sth->fetch ( PDO::FETCH_ASSOC );
		$sth->closeCursor ();
		foreach ( $row as $key => $value ) {
			$that->$key = $value;
		}
		return true;
	}
	
	/**
	 * 实例插入
	 * @param dbh $dbh
	 * @param array $args
	 * @param object $that
	 * @param string $tablename
	 * @param int $primary_index
	 * @param array $params
	 * @return bool
	 */
	public static function insert($dbh, $args, $that, $tablename, $primary_index, $params) {
		extract($params);
		$sql = 'INSERT INTO ' . $tablename . ' (' . $fieldname . ') VALUES (' . $valuename . ')';
		$sth = $dbh->prepare ( $sql );
		$result = ( bool ) $sth->execute ( $paramvars );
		if ($args ['debug_enable'] === true) {
			if ($result === false) {
				$err = $dbh->errorInfo();
				$extra = array('errno'=>$err[1],'error'=>$err[2]);
			} else {
				$extra = null;
			}
			call_user_func ( array(get_class($that),'prepare'), $sql, $paramvars, null, true, $args ['debug_file'], $extra );
		}
		if ($result && $primary_name !== null) {
			$that->$primary_name = $dbh->lastInsertId ();
		}
		return $result;
	}
	
	/**
	 * 实例修改
	 * @param dbh $dbh
	 * @param array $args
	 * @param object $that
	 * @param string $tablename
	 * @param int $primary_index
	 * @param array $params
	 * @return bool
	 */
	public static function update($dbh, $args, $that, $tablename, $primary_index, $params) {
		extract($params);
		if ($primary_name !== null) {
			$sql = 'UPDATE ' . $tablename . ' SET ' . $valuename . ' WHERE ' . $primary_name . '=? LIMIT 1';
		} else {
			$sql = 'UPDATE ' . $tablename . ' SET ' . $valuename . ' LIMIT 1';
		}
		$sth = $dbh->prepare ( $sql );
		$result = ( bool ) $sth->execute ( $paramvars );
		if ($args ['debug_enable'] === true) {
			if ($result === false) {
				$err = $dbh->errorInfo();
				$extra = array('errno'=>$err[1],'error'=>$err[2]);
			} else {
				$extra = null;
			}
			call_user_func ( array(get_class($that),'prepare'), $sql, $paramvars, null, true, $args ['debug_file'], $extra );
		}
		if($result && $sth->rowCount()===0){
			return false;
		}
		return $result;
	}
	
	/**
	 * 实例删除
	 * @param dbh $dbh
	 * @param array $args
	 * @param object $that
	 * @param string $tablename
	 * @param int $primary_index
	 * @param array $params
	 * @return bool
	 */
	public static function delete($dbh, $args, $that, $tablename, $primary_index, $params) {
		extract($params);
		if ($primary_name !== null) {
			$sql = 'DELETE FROM ' . $tablename . ' WHERE ' . $primary_name . '=? LIMIT 1';
			$paramvars = array ($primary_value );
			$sth = $dbh->prepare ( $sql );
			$result = ( bool ) $sth->execute ( $paramvars );
		} else {
			$sql = 'DELETE FROM ' . $tablename . ' LIMIT 1';
			$paramvars = null;
			$sth = $dbh->prepare ( $sql );
			$result = ( bool ) $sth->execute ();
		}
		if ($args ['debug_enable'] === true) {
			if ($result === false) {
				$err = $dbh->errorInfo();
				$extra = array('errno'=>$err[1],'error'=>$err[2]);
			} else {
				$extra = null;
			}
			call_user_func ( array(get_class($that),'prepare'), $sql, $paramvars, null, true, $args ['debug_file'], $extra );
		}
		if($result && $sth->rowCount()===0){
			return false;
		}
		return $result;
	}
	
	/**
	 * 实例更新
	 * @param dbh $dbh
	 * @param array $args
	 * @param object $that
	 * @param string $tablename
	 * @param int $primary_index
	 * @param array $params
	 * @return bool
	 */
	public static function replace($dbh, $args, $that, $tablename, $primary_index, $params) {
		extract($params);
		$sql = 'REPLACE INTO ' . $tablename . ' (' . $fieldname . ') VALUES (' . $valuename . ')';
		$sth = $dbh->prepare ( $sql );
		$result = ( bool ) $sth->execute ( $paramvars );
		if ($args ['debug_enable'] === true) {
			if ($result === false) {
				$err = $dbh->errorInfo();
				$extra = array('errno'=>$err[1],'error'=>$err[2]);
			} else {
				$extra = null;
			}
			call_user_func ( array(get_class($that),'prepare'), $sql, $paramvars, null, true, $args ['debug_file'], $extra );
		}
		if ($result && $primary_name !== null) {
			$that->$primary_name = $dbh->lastInsertId ();
		}
		return $result;
	}
	
}
?>