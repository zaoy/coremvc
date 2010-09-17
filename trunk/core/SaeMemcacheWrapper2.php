<?php
/**
 * saemc2协议(使用如saemc2://key可操作memcache)
 * 
 * @author Z <602000@gmail.com>
 * @version 1.0
 */

/**
 * 定义(define)
 */
class SaeMemcacheWrapper2 {
	
	/**
	 * Stream变量
	 */
	private static $memcache;
	private $path;
	private $handle;
	
	/**
	 * Stream函数组
	 * @param string $path
	 * @return string
	 */
	private function memcache_init() {
		if (self::$memcache !== null) {
			return self::$memcache;
		}
		self::$memcache = memcache_init ();
		return self::$memcache;
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
		$mc = self::memcache_init();
		if ($mc === false) {
			return false;
		}
		$this->path = $path;
		$data = memcache_get($mc, $path);
		if ($data === false) {
			$data = '';
		}
		$file = str_replace('saemc2:/', SAE_TMP_PATH, $path);
		file_put_contents ($file, $data);
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
		$mc = self::memcache_init();
		if ($mc === false) {
			return false;
		}
		memcache_set($mc, $this->path, $data);
		return fwrite ( $this->handle, $data );
	}
	
	/**
	 * url_stat
	 * @param string $path
	 * @param int $flag
	 * @return array
	 */
	public function url_stat($path, $flag) {
		$mc = self::memcache_init();
		if ($mc === false) {
			return false;
		}
		$data = memcache_get($mc, $path);
		if ($data === false) {
			$mode = 16895;
			$size = 0;
			$mtime = 0;
		} else {
			$mode = 16895;
			$size = strlen ($data);
			$mtime = time ();
		}
		$keys = array(
			'dev'		=> 0,
			'ino'		=> 0,
			'mode'		=> $mode,
			'nlink'		=> 0,
			'uid'		=> 0,
			'gid'		=> 0, 
			'rdev'		=> 0,
			'size'		=> $size,
			'atime'		=> 0,
			'mtime'		=> $mtime,
			'ctime'		=> 0,
			'blksize'	=> 0,
			'blocks'	=> 0
		);
		return array_merge (array_values($keys), $keys);
	}
	
	/**
	 * rename
	 * @param string $path_from
	 * @param string $path_to
	 * @return bool
	 */
	public function rename ($path_from, $path_to) {
		$mc = self::memcache_init();
		if ($mc === false) {
			return false;
		}
		$data = memcache_get($mc, $path_from);
		if ($data === false) {
			return false;
		}
		memcache_set($mc, $path_to, $data);
		return true;
	}

	/**
	 * rename
	 * @param string $path
	 * @return bool
	 */
	public function unlink ($path) {
		$mc = self::memcache_init();
		if ($mc === false) {
			return false;
		}
		memcache_delete($mc, $path);
		return true;
	}

}


/**
 * 执行(execute)
 */
stream_wrapper_register ( 'saemc2', 'SaeMemcacheWrapper2' );
?>