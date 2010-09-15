<?php
/**
 * Sae的Smarty缓存协议
 * 
 * 这是CoreMVC扩展文件。
 */

/**
 * 定义(define)
 */
class SaeMemcacheWrapper2 {
	
	/**
	 * Stream变量
	 */
	public static $memcache;
	private $handle;
	private $data_cache_id;
	private $stat_cache_id;
	
	/**
	 * Stream函数组
	 * @param string $path
	 * @return string
	 */
	private function init_cache_id($path) {
		$this->data_cache_id = str_replace('saemc2://smarty_data', 'smarty_data', $path);
		$this->stat_cache_id = str_replace('saemc2://smarty_data', 'smarty_stat', $path);
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
		$this->data_cache_id = null;
		$this->stat_cache_id = null;
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
		$this->init_cache_id($path);
		$file = str_replace('saemc2://smarty_data/', SAE_TMP_PATH . '/smarty_data_', $path);
		$data_variable = memcache_get(self::$memcache, $this->data_cache_id);
		if ($data_variable !== false) {
			file_put_contents ($file, $data_variable);
		}
		$this->handle = fopen ($file, $mode);
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
		memcache_set(self::$memcache, $this->stat_cache_id, time());
		memcache_set(self::$memcache, $this->data_cache_id, $data);
		return fwrite ( $this->handle, $data );
	}
	
	/**
	 * url_stat
	 * @param string $path
	 * @param int $flag
	 * @return array
	 */
	public function url_stat($path, $flag) {
		$data_cache_id = str_replace('saemc2://smarty_data', 'smarty_data', $path);
		$stat_cache_id = str_replace('saemc2://smarty_data', 'smarty_stat', $path);
		$data_variable = memcache_get(self::$memcache, $data_cache_id);
		$stat_variable = memcache_get(self::$memcache, $stat_cache_id);
		$keys = array(
			'dev'     => 0,
			'ino'     => 0,
			'mode'    => 16895,
			//'mode'    => 33216,
			'nlink'   => 0,
			'uid'     => 0,
			'gid'     => 0, 
			'rdev'    => 0,
			'size'    => $data_variable ? strlen($data_variable) : 0,
			'atime'   => 0,
			'mtime'   => $stat_variable ? $stat_variable : 0,
			'ctime'   => 0,
			'blksize' => 0,
			'blocks'  => 0
		);
		return array_merge(array_values($keys), $keys);
	}
	
	/**
	 * rename
	 * @param string $path_from
	 * @param string $path_to
	 * @return bool
	 */
	public function rename ($path_from, $path_to) {
		$from_cache_id = str_replace('saemc2://smarty_data', 'smarty_data', $path_from);
		$data_variable = memcache_get(self::$memcache, $from_cache_id);
		if ($data_variable !== false) {
			$to_cache_id = str_replace('saemc2://smarty_data', 'smarty_data', $path_to);
			memcache_set(self::$memcache, $to_cache_id, $data_variable);
			return true;
		}
		return false;
	}

	/**
	 * rename
	 * @param string $path
	 * @return bool
	 */
	public function unlink ($path) {
		$data_cache_id = str_replace('saemc2://smarty_data', 'smarty_data', $path);
		memcache_delete(self::$memcache, $data_cache_id);
		return true;
	}

}


/**
 * 执行(execute)
 */
if (! in_array ( 'saemc2', stream_get_wrappers () )) {
	stream_wrapper_register ( 'saemc2', 'SaeMemcacheWrapper2' );
}
?>