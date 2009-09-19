<?php
/**
 * 多级模板
 * 
 * 这是CoreMVC扩展文件，用于连接核心文件和多级模板等。
 * core.php ---> <b>core/view_default.php</b> --> core/ViewWrapper.php ---> core/view_smarty.php等
 * @author Z <602000@gmail.com>
 * @package CoreMVC
 */
/**
 * 连接ViewWrapper.php
 * 
 * 可用变量：
 * $_view_type
 * $_view_file
 * $_view_vars
 */
require_once ( dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ViewWrapper.php' );
return ViewWrapper::init ( 'ViewWrapper.' . $_view_type . '://' . $_view_file, $_view_vars );

?>