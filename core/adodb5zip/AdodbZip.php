<?php
/**
 * AdodbZip 1.1
 * 
 * 作者：
 * Z(QQ号602000 QQ群5193883)
 * 
 * 代码示例：
 * include_once 'AdodbZip.php';
 * $db = AdodbZip::init(NewADOConnection('mysqlt'));
 * echo $db->GetOne('SELECT NOW()');
 * 
 * 流程说明：
 * 1. 如果$extract_dir里的Adodb程序文件存在，并且$zip_file不存在；则使用$extract_dir里的Adodb程序文件。
 * 2. 如果$extract_dir里的Adodb程序文件存在，并且$zip_file存在；则比较修改时间，$extract_dir里的Adodb程序文件更新为较新的。
 * 3. 如果$extract_dir里的Adodb程序文件不存在，并且$zip_file存在；则$extract_dir里的Adodb程序文件从$zip_file解压获得。
 * 4. 如果 $extract_dir里的Adodb程序文件不存在，并且$zip_file不存在；则从$zip_url下载Adodb的Zip文件，并解压Adodb的程序文件。
 * 
 * 其他说明：
 * 1. $extract_dir可自定义。如果将Adodb的Zip包完全解压到此目录，则可忽略$zip_url和$zip_file设置项，这和传统使用Adodb一样。
 * 2. $zip_file可自定义。如果$zip_file存在，则可忽略$zip_url，这样可整站统一使用$zip_file。
 * 3. $zip_url可自定义。可随时修改Adodb版本，此时$zip_file和$extract_dir最好使用默认值，各版本互不干扰。
 * 4. $server、$user、$pwd、$db可自定义。默认是mysql默认值，此项只有调用AdodbZip::init方法后才起效果。
 * 5. $charset可自定义。默认不改变编码，此项只有调用AdodbZip::init方法后才起效果。
 * 6. AdodbZip::init方法里可增加Adodb初始化值。
 */

/**
 * AdodbZip启动项
 */
// 设定参数
$sys_get_temp_dir = sys_get_temp_dir ();
$sys_get_temp_dir_last_char = substr ($sys_get_temp_dir, -1, 1);
if ($sys_get_temp_dir_last_char !== '/' && $sys_get_temp_dir_last_char !== '\\') {
	$sys_get_temp_dir .= DIRECTORY_SEPARATOR;
}
AdodbZip::$zip_url = 'http://cdnetworks-kr-1.dl.sourceforge.net/project/adodb/adodb-php5-only/adodb-509-for-php5/adodb509.zip'; //［设置项］Adodb的Zip文件下载地址，文件比较大建议先下载或者解压
AdodbZip::$zip_file = $sys_get_temp_dir . preg_replace ( '/^.*\/(adodb.*?\.zip)$/i', 'adodb/$1', AdodbZip::$zip_url ); //［设置项］Adodb的Zip文件缓存位置
AdodbZip::$entry_dir = 'adodb5';
AdodbZip::$extract_dir = $sys_get_temp_dir . 'adodb/' . AdodbZip::$entry_dir; //［设置项］Adodb程序文件缓存位置
AdodbZip::$server = 'localhost'; //［设置项］服务器	
AdodbZip::$user = 'root'; //［设置项］用户名
AdodbZip::$pwd = ''; //［设置项］密码
AdodbZip::$db = 'test'; //［设置项］数据库
AdodbZip::$charset = ''; //［设置项］编码
unset ($sys_get_temp_dir);


// 注册协议
if (! in_array ( 'AdodbZip', stream_get_wrappers () )) {
	stream_wrapper_register ( 'AdodbZip', 'AdodbZip' );
}
// 定义常量
if (! defined ( 'ADODB_DIR' )) {
	define ( 'ADODB_DIR', 'AdodbZip:/' );
}
// 包含程序
require_once (ADODB_DIR . '/adodb.inc.php');
// $db = AdodbZip::init(NewADOConnection('mysqlt')); // ［选择项］引用即定义$db
// return AdodbZip::init(NewADOConnection('mysqlt')); // ［选择项］引用即返回$db，注意只可引用一次。


/**
 * AdodbZip类定义
 */
class AdodbZip {
	
	/**
	 * Adodb变量
	 */
	public static $zip_url;
	public static $zip_file;
	public static $entry_dir;
	public static $extract_dir;
	public static $server;
	public static $user;
	public static $pwd;
	public static $db;
	public static $charset;
	
	/**
	 * Stream变量
	 */
	private $handle;
	public $context;
	
	/**
	 * Adodb函数组
	 */
	
	/**
	 * init
	 * @param adodb &$adodb
	 * @return adodb
	 */
	public static function init(&$adodb) {
		$adodb->Connect ( self::$server, self::$user, self::$pwd, self::$db );
		if(self::$charset!=''){
			$adodb->Execute('SET NAMES '.self::$charset.';');
		}
		return $adodb;
	}
	
	/**
	 * Stream函数组
	 */
	
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
		// 验证文件地址
		if (! preg_match ( '/^.*?:\/\/(.*)$/', $path, $matches )) {
			return false;
		}
		$tmp_file = self::$extract_dir . DIRECTORY_SEPARATOR . $matches [1];
		$entry_file = self::$entry_dir . '/' . str_replace ( '\\', '/', $matches [1] );
		$zip_file = self::$zip_file;
		// 验证程序文件
		if (! file_exists ( $tmp_file ) || file_exists ( $zip_file ) && filectime ( $tmp_file ) < filectime ( $zip_file )) {
			// 下载文件
			if (! file_exists ( $zip_file )) {
				// 目录处理
				if (! is_dir ( dirname ( self::$zip_file ) )) {
					if (mkdir ( dirname ( self::$zip_file ), 0777, true ) === false) {
						header ( 'Content-type: text/html;charset=utf-8' );
						die ( '请创建目录 ' . $zip_dir );
					}
				}
				// 下载文件
				if (! file_exists ( self::$zip_file )) {
					$break = true;
					do {
						$url_arr = parse_url ( self::$zip_url );
						$fp = fsockopen ( $url_arr ['host'], isset ( $url_arr ['port'] ) ? ( int ) $url_arr ['port'] : 80, $errno, $errstr, 10 );
						if ($fp === false) {
							break;
						}
						$out = "GET " . $url_arr ['path'] . " HTTP/1.0\r\nHost: " . $url_arr ['host'] . " \r\nConnection: close\r\n\r\n";
						fputs ( $fp, $out );
						if (feof ( $fp )) {
							break;
						}
						$buffer = fgets ( $fp, 1024 );
						if (! preg_match ( '/^HTTP\/1\.\d 200 /i', $buffer )) {
							break;
						}
						$content_length = false;
						$content_start = false;
						while ( ! feof ( $fp ) ) {
							$buffer = fgets ( $fp, 1024 );
							if ($buffer === "\r\n") {
								$content_start = true;
								break;
							}
							if (preg_match ( '/^Content-Length:\s*(\d+)/i', $buffer, $matches )) {
								$content_length = ( int ) $matches [1];
							}
						}
						if ($content_length === false || $content_start === false) {
							break;
						}
						$content = stream_get_contents ( $fp );
						if ($content === false) {
							break;
						}
						$result = file_put_contents ( self::$zip_file, $content );
						unset ( $content );
						if ($result === false) {
							break;
						}
						fclose ( $fp );
					} while ( $break = false );
					if ($break) {
						header ( 'Content-type: text/html;charset=utf-8' );
						die ( '请下载文件 <a href="' . self::$zip_url . '">' . self::$zip_url . '.zip</a > 保存为 ' . self::$zip_file );
					}
				}
			}
			// 创建目录
			$tmp_dir = dirname ( $tmp_file );
			if (! is_dir ( $tmp_dir )) {
				if (mkdir ( $tmp_dir, 0777, true ) === false) {
					header ( 'Content-type: text/html;charset=utf-8' );
					die ( '请创建目录 ' . $tmp_dir );
				}
			}
			// 打开压缩文件
			$zip = zip_open ( $zip_file );
			if (! is_resource ( $zip )) {
				return false;
			}
			// 寻找解压文件
			do {
				$entry = zip_read ( $zip );
				if (! is_resource ( $entry )) {
					return false;
				}
				if (zip_entry_name ( $entry ) == $entry_file) {
					break;
				}
			} while ( true );
			// 转存压缩文件
			zip_entry_open ( $zip, $entry );
			file_put_contents ( $tmp_file, zip_entry_read ( $entry, zip_entry_filesize ( $entry ) ) );
			zip_entry_close ( $entry );
			zip_close ( $zip );
		}
		// 打开文件
		$this->handle = fopen ( $tmp_file, $mode );
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
		if (! preg_match ( '/^.*?:\/\/(.*)$/', $path, $matches )) {
			return false;
		}
		$tmp_file = self::$extract_dir . DIRECTORY_SEPARATOR . $matches [1];
		if (file_exists ( $tmp_file )) {
			if ($flag & STREAM_URL_STAT_LINK) {
				return lstat ( $tmp_file );
			} else {
				return stat ( $tmp_file );
			}
		}
		if ($flag & STREAM_URL_STAT_QUIET) {
			$arr = array ('dev' => 0, 'ino' => 0, 'mode' => 0, 'nlink' => 0, 'uid' => 0, 'gid' => 0, 'rdev' => 0, 'size' => 0, 'atime' => 0, 'mtime' => 0, 'ctime' => 0, 'blksize' => 0, 'blocks' => 0 );
			return array_merge ( array_values ( $arr ), $arr );
		}
		return false;
	}
}
?>