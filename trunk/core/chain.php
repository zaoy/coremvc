<?php
/**
 * 定义(define)
 */
class chain {

	private $param_array = array();

	/**
	 * 构造函数
	 *
	 * @param mixed $self
	 */
	public function __construct($self = null) {
		if (is_object ($self)) {
			$this->param_array ['self'] = get_class($self);
		} elseif (is_string ($self)) {
			$this->param_array ['self'] = $self;
		} else {
			foreach (debug_backtrace() as $row) {
				if (isset ($row['class'])) {
					if ($row['class'] === __CLASS__) {
						continue;
					} else {
						$this->param_array ['self'] = $row['class'];
						break;
					}
				}
				break;
			}
		}
		if (! isset ($this->param_array ['self']) || ! class_exists ($this->param_array ['self'])) {
			trigger_error('Cannot create ' . __CLASS__ . ' object, try the parameter.', E_USER_ERROR);
		}
	}

	/**
	 * 创建对象
	 *
	 * @param mixed $self
	 * @return chain
	 */
	public static function getInstance($self = null) {
		return new self($self);
	}

	/**
	 * 字段赋值
	 *
	 * @param mixed $field
	 * @return self
	 */
	public function field($field) {
		$this->param_array ['field'] = $field;
		return $this;
	}

	/**
	 * 表名赋值
	 *
	 * @param mixed $table
	 * @return self
	 */
	public function table($table) {
		$this->param_array ['table'] = $table;
		return $this;
	}

	/**
	 * 条件赋值
	 *
	 * @param mixed $where
	 * @return self
	 */
	public function where($where) {
		$this->param_array ['where'] = $where;
		return $this;
	}

	/**
	 * 其他赋值
	 *
	 * @param mixed $other
	 * @return self
	 */
	public function other($other) {
		$this->param_array ['other'] = $other;
		return $this;
	}

	/**
	 * GROUP BY赋值
	 *
	 * @param string $group
	 * @return self
	 */
	public function group($group) {
		$this->param_array ['group'] = $group;
		return $this;
	}

	/**
	 * HAVING赋值
	 *
	 * @param string $having
	 * @return self
	 */
	public function having($having) {
		$this->param_array ['having'] = $having;
		return $this;
	}

	/**
	 * ORDER BY赋值
	 *
	 * @param string $order
	 * @return self
	 */
	public function order($order) {
		$this->param_array ['order'] = $order;
		return $this;
	}

	/**
	 * LIMIT赋值
	 *
	 * @param string $limit
	 * @return self
	 */
	public function limit($limit) {
		$this->param_array ['limit'] = $limit;
		return $this;
	}

	/**
	 * 分页赋值
	 *
	 * @param mixed &$page
	 * @return self
	 */
	public function page(&$page) {
		$this->param_array ['page'] = &$page;
		return $this;
	}

	/**
	 * 构造赋值
	 *
	 * @param mixed $struct
	 * @return self
	 */
	public function struct($struct) {
		$this->param_array ['struct'] = $struct;
		return $this;
	}

	/**
	 * 列名赋值
	 *
	 * @param mixed $column
	 * @return self
	 */
	public function column($column) {
		$this->param_array ['column'] = $column;
		return $this;
	}

	/**
	 * 插值赋值
	 *
	 * @param mixed $column
	 * @return self
	 */
	public function value($value) {
		$this->param_array ['value'] = $value;
		return $this;
	}

	/**
	 * 赋值赋值
	 *
	 * @param mixed $set
	 * @return self
	 */
	public function set($set) {
		$this->param_array ['set'] = $set;
		return $this;
	}

	/**
	 * SQL语句赋值
	 *
	 * @param mixed $sql
	 * @return self
	 */
	public function sql($sql) {
		$this->param_array ['sql'] = $sql;
		return $this;
	}

	/**
	 * 参数赋值
	 *
	 * @param mixed $sql
	 * @return self
	 */
	public function param($param) {
		$this->param_array ['param'] = $param;
		return $this;
	}


	/**
	 * 选择操作
	 *
	 * @param mixed $struct
	 * @return mixed
	 */
	public function select($struct = null) {
		if (isset ($struct)) {
			$this->param_array ['struct'] = $struct;
		}
		$function = array ($this->param_array ['self'], __FUNCTION__.'s');
		$param_arr = self::analyze (__FUNCTION__);
		return call_user_func_array ($function, $param_arr);
	}

	/**
	 * 插入操作
	 *
	 * @param string $class
	 * @return int
	 */
	public function insert($class = null) {
		if (isset ($class)) {
			$this->param_array ['class'] = $class;
		}
		$function = array ($this->param_array ['self'], __FUNCTION__.'s');
		$param_arr = self::analyze (__FUNCTION__);
		return call_user_func_array ($function, $param_arr);
	}

	/**
	 * 修改操作
	 *
	 * @param string $class
	 * @return int
	 */
	public function update($class = null) {
		if (isset ($class)) {
			$this->param_array ['class'] = $class;
		}
		$function = array ($this->param_array ['self'], __FUNCTION__.'s');
		$param_arr = self::analyze (__FUNCTION__);
		return call_user_func_array ($function, $param_arr);
	}

	/**
	 * 删除操作
	 *
	 * @param string $class
	 * @return int
	 */
	public function delete($class = null) {
		if (isset ($class)) {
			$this->param_array ['class'] = $class;
		}
		$function = array ($this->param_array ['self'], __FUNCTION__.'s');
		$param_arr = self::analyze (__FUNCTION__);
		return call_user_func_array ($function, $param_arr);
	}

	/**
	 * 更新操作
	 *
	 * @param string $class
	 * @return int
	 */
	public function replace($class = null) {
		if (isset ($class)) {
			$this->param_array ['class'] = $class;
		}
		$function = array ($this->param_array ['self'], __FUNCTION__.'s');
		$param_arr = self::analyze (__FUNCTION__);
		return call_user_func_array ($function, $param_arr);
	}

	/**
	 * 准备语句
	 *
	 * @param string $flag
	 * @param bool $format
	 * @return array
	 */
	public function prepare($flag, $format = null) {
		$function = array ($this->param_array ['self'], __FUNCTION__);
		$analyze = self::analyze (rtrim ($flag, 's'));
		if (isset ($this->param_array ['sql'])) {
			list ($sql, $param) = $analyze;
			$param_arr = array ($sql, $param, $format);
		} else {
			$flag = substr($flag,-1) === 's' ? $flag : $flag . 's';
			$param_arr = array ($flag, $analyze, $format);
		}
		return call_user_func_array ($function , $param_arr);
	}

	/**
	 * 设置结构键值
	 *
	 * @param string $column
	 * @return int
	 */
	public function setKey($column = null) {
		if (isset ($this->param_array ['struct'])) {
			$struct = &$this->param_array ['struct'];
			if (is_array ($struct)){
				if (count ($struct) > 0){
					array_splice ($struct, -1, 0, $column);
				} else {
					$struct = array ($column, null);
				}
			} else {
				$struct = array ($column, $struct);
			}
		} else {
			$this->param_array ['struct'] = array ($column, null);
		}
		return $this;
	}

	/**
	 * 设置结构返回值
	 *
	 * @param string $value
	 * @return int
	 */
	public function setValue($value = null) {
		if (isset ($this->param_array ['struct'])) {
			$struct = &$this->param_array ['struct'];
			if (is_array ($struct)){
				if (count ($struct) > 0){
					if (is_array ($value)) {
						foreach ($value as $key2=>$value2) {
							array_splice ($struct, -1, 1);
							if (is_int ($key2)) {
								$struct [] = $value2;
							} else {
								$struct [$key2] = $value2;
							}
							break;
						}
					} else {
						array_splice ($struct, -1, 1);
						$struct [] = $value;
					}
				} else {
					if (is_array ($value)) {
						$struct = $value;
					} else {
						$struct = array ($value);
					}
				}
			} else {
				$struct = $value;
			}
		} else {
			$this->param_array ['struct'] = $value;
		}
		return $this;
	}

	/**
	 * 查询返回数组
	 *
	 * @return mixed
	 */
	public function getAssoc() {
		$this->setValue(array ('assoc'=>null));
		return $this->select();
	}

	/**
	 * 查询返回数组
	 *
	 * @return mixed
	 */
	public function getBoth() {
		$this->setValue(array ('both'=>null));
		return $this->select();
	}

	/**
	 * 查询返回数组
	 *
	 * @return mixed
	 */
	public function getNum() {
		$this->setValue(array ('num'=>null));
		return $this->select();
	}

	/**
	 * 查询返回数组
	 *
	 * @param array $array
	 * @return mixed
	 */
	public function getArray($array = array()) {
		$this->setValue(array ('array'=>$array));
		return $this->select();
	}

	/**
	 * 查询返回字段
	 *
	 * @param mixed $column
	 * @return mixed
	 */
	public function getColumn($column = 0) {
		$this->setValue(array ('column'=>$column));
		return $this->select();
	}

	/**
	 * 查询返回对象
	 *
	 * @param string $class
	 * @return mixed
	 */
	public function getClass($class = null) {
		$this->setValue(array ('class'=>$class));
		return $this->select();
	}

	/**
	 * 查询返回对象
	 *
	 * @param string $class
	 * @return mixed
	 */
	public function getClassByType($class) {
		$this->setValue(array ('class|classtype'=>$class));
		return $this->select();
	}

	/**
	 * 查询返回对象
	 *
	 * @param object $clone
	 * @return mixed
	 */
	public function getClone($clone) {
		$this->setValue(array ('clone'=>$clone));
		return $this->select();
	}

	/**
	 * 分析命令返回参数
	 *
	 * @param string $command
	 * @return mixed
	 */
	private function analyze($command) {
		if (isset ($this->param_array ['sql'])) {
			$sql = $this->param_array ['sql'];
			$param = isset($this->param_array ['param']) ? $this->param_array ['param'] : null;
			if ($command === 'select') {
				$other = isset($this->param_array ['other']) ? $this->param_array ['other'] : null;
				$struct = isset($this->param_array ['struct']) ? $this->param_array ['struct'] : null;
				return array ($sql, $param, true, $other, $struct);
			} else {
				$class = isset($this->param_array ['class']) ? $this->param_array ['class'] : null;
				return array ($sql, $param, true, null, $class);
			}
		} else {
			switch ($command) {
				case 'select':
					$field = isset($this->param_array ['field']) ? $this->param_array ['field'] : null;
					$table = isset($this->param_array ['table']) ? $this->param_array ['table'] : null;
					$where = isset($this->param_array ['where']) ? $this->param_array ['where'] : null;
					$other = isset($this->param_array ['other']) ? (array)$this->param_array ['other'] : array();
					isset ($this->param_array ['group']) and $other = array ($other, 'GROUP BY ' . $this->param_array ['group']);
					isset ($this->param_array ['having']) and $other = array ($other, 'HAVING ' . $this->param_array ['having']);
					isset ($this->param_array ['order']) and $other = array ($other, 'ORDER BY ' . $this->param_array ['order']);
					isset ($this->param_array ['limit']) and $other = array ($other, 'LIMIT ' . $this->param_array ['limit']);
					isset ($this->param_array ['page']) and $other = array ($other, 'page' => &$this->param_array ['page']);
					$struct = isset($this->param_array ['struct']) ? $this->param_array ['struct'] : null;
					return array ($field, $table, $where, $other, $struct);
					break;
				case 'insert':
				case 'replace':
					$table = isset($this->param_array ['table']) ? $this->param_array ['table'] : null;
					$other = isset($this->param_array ['other']) ? $this->param_array ['other'] : null;
					$class = isset($this->param_array ['class']) ? $this->param_array ['class'] : null;
					if (isset ($this->param_array ['set'])) {
						$set = $this->param_array ['set'];
						return array ($table, $set, null, $other, $class);
					} else {
						$column = isset($this->param_array ['column']) ? $this->param_array ['column'] : null;
						$value = isset($this->param_array ['value']) ? $this->param_array ['value'] : null;
						return array ($table, $column, $value, $other, $class);
					}
					break;
				case 'update':
					$table = isset($this->param_array ['table']) ? $this->param_array ['table'] : null;
					$set = isset($this->param_array ['set']) ? $this->param_array ['set'] : null;
					$where = isset($this->param_array ['where']) ? $this->param_array ['where'] : null;
					$other = isset($this->param_array ['other']) ? (array)$this->param_array ['other'] : array();
					isset ($this->param_array ['order']) and $other = array ($other, 'ORDER BY ' . $this->param_array ['order']);
					isset ($this->param_array ['limit']) and $other = array ($other, 'LIMIT ' . $this->param_array ['limit']);
					$class = isset($this->param_array ['class']) ? $this->param_array ['class'] : null;
					return array ($table, $set, $where, $other, $class);
					break;
				case 'delete':
					$field = isset($this->param_array ['field']) ? $this->param_array ['field'] : null;
					$table = isset($this->param_array ['table']) ? $this->param_array ['table'] : null;
					$where = isset($this->param_array ['where']) ? $this->param_array ['where'] : null;
					$other = isset($this->param_array ['other']) ? (array)$this->param_array ['other'] : array();
					isset ($this->param_array ['order']) and $other = array ($other, 'ORDER BY ' . $this->param_array ['order']);
					isset ($this->param_array ['limit']) and $other = array ($other, 'LIMIT ' . $this->param_array ['limit']);
					$class = isset($this->param_array ['class']) ? $this->param_array ['class'] : null;
					return array ($field, $table, $where, $other, $class);
					break;
				default:
					break;
			}
		}
	}

}
?>