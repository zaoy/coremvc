<?php
/**
 * SmartyZip 1.6
 * 
 * 作者：
 * Z(QQ号602000 QQ群5193883)
 * 
 * 代码示例：
 * include_once 'SmartyZip.php';
 * $smarty = SmartyZip::init(new Smarty);
 * $smarty->assign ( 'name', 'SmartyZip' );
 * $smarty->display ( 'test.html' );
 * 
 * 流程说明：
 * 1. 如果$extract_dir里的Smarty程序文件存在，并且$zip_file不存在；则使用$extract_dir里的Smarty程序文件。
 * 2. 如果$extract_dir里的Smarty程序文件存在，并且$zip_file存在；则比较修改时间，$extract_dir里的Smarty程序文件更新为较新的。
 * 3. 如果$extract_dir里的Smarty程序文件不存在，并且$zip_file存在；则$extract_dir里的Smarty程序文件从$zip_file解压获得。
 * 4. 如果 $extract_dir里的Smarty程序文件不存在，并且$zip_file不存在；则从$zip_url下载Smarty的Zip文件，并解压Smarty的程序文件。
 * 
 * 其他说明：
 * 1. $extract_dir可自定义。如果将Smarty的Zip包完全解压到此目录，则可忽略$zip_url和$zip_file设置项，这和传统使用Smarty一样。
 * 2. $zip_file可自定义。如果$zip_file存在，则可忽略$zip_url，这样可整站统一使用$zip_file。
 * 3. $zip_url可自定义。可随时修改Smarty版本，此时$zip_file和$extract_dir最好使用默认值，各版本互不干扰。
 * 4. $template_dir可自定义。默认是SmartyZip程序文件目录，此项只有调用SmartyZip::init方法后才起效果。
 * 5. $compile_dir可自定义。默认是临时文件里的一个目录，此项只有调用SmartyZip::init方法后才起效果。
 * 6. SmartyZip::init方法里可增加smarty初始化值，如cache_dir、config_dir等。
 */

/**
 * SmartyZip启动项
 */
// 设定参数
SmartyZip::$zip_url = 'http://www.smarty.net/distributions/Smarty-2.6.26.zip'; //［设置项］Smarty的Zip文件下载地址
SmartyZip::$zip_file = sys_get_temp_dir () . preg_replace ( '/^.*\/(Smarty-.*.zip)$/i', 'smarty/$1', SmartyZip::$zip_url ); //［设置项］Smarty的Zip文件缓存位置
SmartyZip::$entry_dir = preg_replace ( '/^.*\/(Smarty-.*).zip$/i', '$1/libs', SmartyZip::$zip_file );
SmartyZip::$extract_dir = sys_get_temp_dir () . 'smarty/' . SmartyZip::$entry_dir; //［设置项］Smarty程序文件缓存位置
SmartyZip::$template_dir = dirname ( realpath ( __FILE__ ) ); //［设置项］Smarty模板文件 所在位置	
SmartyZip::$compile_dir = sys_get_temp_dir () . 'smarty/template_c/' . md5 ( SmartyZip::$template_dir ); //［设置项］Smarty编译文件缓存位置


// 注册协议
if (! in_array ( 'SmartyZip', stream_get_wrappers () )) {
	stream_wrapper_register ( 'SmartyZip', 'SmartyZip' );
}
// 定义常量
if (! defined ( 'SMARTY_DIR' )) {
	define ( 'SMARTY_DIR', 'SmartyZip://' );
}
// 包含程序
require_once (SMARTY_DIR . 'Smarty.class.php');
// $smarty = SmartyZip::init(new Smarty); // ［选择项］引用即定义$smarty
// return SmartyZip::init(new Smarty); // ［选择项］引用即返回$smarty，注意只可引用一次。


/**
 * SmartyZip类定义
 */
class SmartyZip {
	
	/**
	 * Smarty变量
	 */
	public static $zip_url;
	public static $zip_file;
	public static $entry_dir;
	public static $extract_dir;
	public static $template_dir;
	public static $compile_dir;
	
	/**
	 * Stream变量
	 */
	private $handle;
	public $context;
	
	/**
	 * Smarty函数组
	 */
	
	/**
	 * init
	 * @param Smarty &$smarty
	 * @return Smarty
	 */
	public static function init(&$smarty) {
		$smarty->template_dir = self::$template_dir;
		if (! is_dir ( self::$compile_dir )) {
			if (mkdir ( self::$compile_dir, 0777, true ) === false) {
				header ( 'Content-type: text/html;charset=utf-8' );
				die ( '请创建目录 ' . self::$compile_dir );
			}
		}
		$smarty->compile_dir = self::$compile_dir;
		return $smarty;
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
						die ( '请下载文件 <a href="' . self::$zip_url . '">' . self::$zip_url . '</a > 保存为 ' . self::$zip_file );
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
			$file_stat = lstat ( __FILE__ );
			$mode = $file_stat ['mode'];
			// smarty plugins adjust
			if (strpos ( $matches [1], 'plugins' . DIRECTORY_SEPARATOR . 'compiler.' ) !== false) {
				if (strpos ( $matches [1], 'plugins' . DIRECTORY_SEPARATOR . 'compiler.assign.php' ) === false) {
					$mode = 0;
				}
			} elseif (strpos ( $matches [1], 'plugins' . DIRECTORY_SEPARATOR . 'block.' ) !== false) {
				if (strpos ( $matches [1], 'plugins' . DIRECTORY_SEPARATOR . 'block.textformat.php' ) === false) {
					$mode = 0;
				}
			}
			$arr = array ('dev' => 0, 'ino' => 0, 'mode' => $mode, 'nlink' => 0, 'uid' => 0, 'gid' => 0, 'rdev' => 0, 'size' => 0, 'atime' => 0, 'mtime' => 0, 'ctime' => 0, 'blksize' => 0, 'blocks' => 0 );
			return array_merge ( array_values ( $arr ), $arr );
		}
		return false;
	}
}
?>