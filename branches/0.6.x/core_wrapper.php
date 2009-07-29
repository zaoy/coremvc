<?php
/**
 * 引用
 */
class_exists ( 'core' ) or require ('core.php');

/**
 * 存根
 */
core::stub () and core::main ();

/**
 * 定义
 */
class core_wrapper {
	
	public static $vars = array ();
	
	private $handle;
	public $context;
	
	/**
	 * init
	 * @param string $file
	 * @param array $vars
	 * @return bool
	 */
	public static function init($file, $vars = array()) {
		// 基本验证
		if (! preg_match ( '/^(.+?):\/\/(.+)$/', $file, $matches )) {
			return false;
		}
		$wrapper_arr = explode ( '.', $matches [1] );
		if (count ( $wrapper_arr ) < 2) {
			return false;
		}
		// 注册协议	
		if (! in_array ( $matches [1], stream_get_wrappers () )) {
			stream_wrapper_register ( $matches [1], __CLASS__ );
		}
		// 获得内容
		self::$vars [$matches [0]] = $vars;
		return file_get_contents ( $matches [0] );
	}
	
	/**
	 * __construct
	 */
	public function __construct() {
	}
	
	/**
	 * stream_cast
	 * @param int $cast_as
	 * @return resource
	 */
	public function stream_cast($cast_as) {
		return false;
	}
	
	/**
	 * stream_close
	 */
	public function stream_close() {
		fclose ( $this->handle );
	}
	
	/**
	 * stream_eof
	 * @return bool
	 */
	public function stream_eof() {
		return feof ( $this->handle );
	}
	
	/**
	 * stream_flush
	 * @return bool
	 */
	public function stream_flush() {
		return fflush ( $this->handle );
	}
	
	/**
	 * stream_lock
	 * @param mode $options
	 * @return bool
	 */
	public function stream_lock($options) {
		return flock ( $this->handle, $options );
	}
	
	/**
	 * stream_open
	 * @param string $path
	 * @param string $mode
	 * @param int $options
	 * @param string &$opend_path
	 * @return bool
	 */
	public function stream_open($path, $mode, $options, &$opend_path) {
		// 基本验证
		if (! preg_match ( '/^(.+?):\/\/(.+)$/', $path, $_wrapper_matches )) {
			return false;
		}
		$_wrapper_arr = explode ( '.', $_wrapper_matches [1] );
		if (count ( $_wrapper_arr ) < 2) {
			return false;
		}
		// 句柄处理
		if (count ( $_wrapper_arr ) == 2) {
			$this->handle = fopen ( $_wrapper_matches [2], $mode );
		} else {
			$_wrapper_flag = array_pop ( $_wrapper_arr );
			$_wrapper_protocol = implode ( '.', $_wrapper_arr );
			if (! in_array ( $_wrapper_protocol, stream_get_wrappers () )) {
				stream_wrapper_register ( $_wrapper_protocol, __CLASS__ );
			}
			$_wrapper_path = $_wrapper_protocol . '://' . $_wrapper_matches [2];
			switch ($_wrapper_flag) {
				case 'include' :
					$this->handle = fopen ( 'php://temp', 'w+' );
					extract ( self::$vars [$_wrapper_matches [0]] );
					self::$vars [$_wrapper_path] = self::$vars [$_wrapper_matches [0]];
					ob_start ();
					require ($_wrapper_path);
					$_wrapper_var = ob_get_clean ();
					fwrite ( $this->handle, $_wrapper_var );
					rewind ( $this->handle );
					unset ( $_wrapper_var );
					break;
				case 'string' :
					$this->handle = fopen ( 'php://temp', 'w+' );
					extract ( self::$vars [$_wrapper_matches [0]] );
					self::$vars [$_wrapper_path] = self::$vars [$_wrapper_matches [0]];
					$_wrapper_var = eval ( 'return <<<_END_OF_EVAL' . PHP_EOL . file_get_contents ( $_wrapper_path ) . PHP_EOL . '_END_OF_EVAL;' . PHP_EOL );
					fwrite ( $this->handle, $_wrapper_var );
					rewind ( $this->handle );
					unset ( $_wrapper_var );
					break;
				case 'smarty' :
					$this->handle = fopen ( 'php://temp', 'w+' );
					class_exists ( 'core_smarty' ) or require ('core_smarty.php');
					$smarty = core_smarty::init ( new smarty ( ) );
					$smarty->template_dir = $_wrapper_protocol . '://' . $smarty->template_dir;
					$smarty->_tpl_vars = self::$vars [$_wrapper_matches [0]];
					self::$vars [$smarty->template_dir . DIRECTORY_SEPARATOR . $_wrapper_matches [2]] = self::$vars [$_wrapper_matches [0]];
					$_wrapper_var = $smarty->fetch ( $_wrapper_matches [2] );
					fwrite ( $this->handle, $_wrapper_var );
					rewind ( $this->handle );
					unset ( $_wrapper_var );
					break;
				case 'nobom' :
					self::$vars [$_wrapper_path] = self::$vars [$_wrapper_matches [0]];
					$_wrapper_var = file_get_contents ( $_wrapper_path );
					if (strlen ( $_wrapper_var ) > 3 && ord ( $_wrapper_var [0] ) == 239 && ord ( $_wrapper_var [1] ) == 187 && ord ( $_wrapper_var [2] ) == 191) {
						$this->handle = fopen ( 'php://temp', 'w+' );
						$_wrapper_var = substr ( $_wrapper_var, 3 );
						fwrite ( $this->handle, $_wrapper_var );
						rewind ( $this->handle );
					} else {
						$this->handle = fopen ( $_wrapper_path, $mode );
					}
					unset ( $_wrapper_var );
					break;
				case 'nophp' :
					self::$vars [$_wrapper_path] = self::$vars [$_wrapper_matches [0]];
					$_wrapper_var = file_get_contents ( $_wrapper_path );
					if (preg_match ( '/^<\?php\s.*?\?>\s{0,2}(.*)$/si', $_wrapper_var, $_wrapper_php_matches )) {
						$this->handle = fopen ( 'php://temp', 'w+' );
						$_wrapper_var = substr ( $_wrapper_var, 3 );
						fwrite ( $this->handle, $_wrapper_php_matches [1] );
						rewind ( $this->handle );
						unset ( $_wrapper_php_matches );
					} else {
						$this->handle = fopen ( $_wrapper_path, $mode );
					}
					unset ( $_wrapper_var );
					break;
				default :
					self::$vars [$_wrapper_path] = self::$vars [$_wrapper_matches [0]];
					$this->handle = fopen ( $_wrapper_path, $mode );
					break;
			}
		}
		// 验证返回
		if (! is_resource ( $this->handle )) {
			return false;
		}
		return true;
	}
	
	/**
	 * stream_read
	 * @param int $count
	 * @return string
	 */
	public function stream_read($count) {
		return fread ( $this->handle, $count );
	}
	
	/**
	 * stream_seek
	 * @param int $offset
	 * @param int $whence=SEEK_SET
	 * @return bool
	 */
	public function stream_seek($offset, $whence = SEEK_SET) {
		return fseek ( $this->handle, $offset, $whence );
	}
	
	/**
	 * stream_set_option
	 * @param int $option
	 * @param int $arg1
	 * @param int $arg2
	 * @return bool
	 */
	public function stream_set_option($option, $arg1, $arg2) {
		return false;
	}
	
	/**
	 * stream_stat
	 * @return array
	 */
	public function stream_stat() {
		return fstat ( $this->handle );
	}
	
	/**
	 * stream_tell
	 * @return int
	 */
	public function stream_tell() {
		return ftell ( $this->handle );
	}
	
	/**
	 * stream_write
	 * @param string $data
	 * @return int
	 */
	public function stream_write($data) {
		return fwrite ( $this->handle, $data );
	}
	
	/**
	 * url_stat
	 * @param string $path
	 * @param int $flag
	 * @return array
	 */
	public function url_stat($path, $flag) {
		// 基本验证
		if (! preg_match ( '/^(.+?):\/\/(.*)$/', $path, $matches )) {
			return false;
		}
		$wrapper_arr = explode ( '.', $matches [1] );
		if (count ( $wrapper_arr ) < 2) {
			return false;
		}
		// 分析数组
		$lstat_arr = array ('dev' => 0, 'nlink' => 0, 'uid' => 0, 'gid' => 0, 'rdev' => 0, 'blksize' => 0 );
		$stat_arr = array ('dev' => 0, 'ino' => 0, 'mode' => 0, 'nlink' => 0, 'uid' => 0, 'gid' => 0, 'rdev' => 0, 'size' => 0, 'atime' => 0, 'mtime' => 0, 'ctime' => 0, 'blksize' => 0, 'blocks' => 0 );
		$stat_flag = false;
		if (is_file ( $matches [2] )) {
			$stat_flag = true;
			$lstat_arr = lstat ( $matches [2] );
			$stat_arr = stat ( $matches [2] );
			if (count ( $wrapper_arr ) > 2) {
				$stat_arr ['size'] = 0;
			}
		}
		// 返回数组
		if ($stat_flag) {
			if ($flag & STREAM_URL_STAT_LINK) {
				return array_merge ( array_values ( $lstat_arr ), $lstat_arr );
			} else {
				return array_merge ( array_values ( $stat_arr ), $stat_arr );
			}
		}
		if ($flag & STREAM_URL_STAT_QUIET) {
			return array_merge ( array_values ( $stat_arr ), $stat_arr );
		}
		return false;
	}
}
?>