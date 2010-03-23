<?php
/**
 * Smarty连接器
 * 
 * 这是CoreMVC扩展文件，用于连接核心文件和Smarty装载器。
 * core.php ---> <b>core/view_smarty.php</b> ---> core/SmartyZip.php
 * @author Z <602000@gmail.com>
 * @package CoreMVC
 */
/**
 * 连接SmartyZip.php
 * 
 * 可用变量：
 * $_view_file
 * $_view_vars
 */
require_once ( dirname(__FILE__) . DIRECTORY_SEPARATOR . 'SmartyZip.php' );
SmartyZip::$template_dir = $_view_dir2;
SmartyZip::$compile_dir = sys_get_temp_dir () . 'smarty/template_c/' . md5 ( SmartyZip::$template_dir );
$smarty = SmartyZip::init ( new smarty ( ) );
$smarty->_tpl_vars = $_view_vars;
return $smarty->fetch ( $_view_file );

?>