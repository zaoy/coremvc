<?php
/**
 * PHPMailer类库扩展
 * 
 * 这是CoreMVC类库扩展文件，需要有PHPMailer类库目录。
 * core.php ---> <b>core/PHPMailer.php</b> ---> PHPMailer类库目录
 */

/**
 * 定义(define)
 */

function PHPMailer_autoload ( $name ) {
	switch($name){
		case 'PHPMailer':
		case 'SMTP':
		case 'POP3':
			$file = dirname(__FILE__) . '/' . basename(__FILE__ , '.php') .  '/class.'. strtolower($name) . '.php';
			if ( !is_file($file) ) {
				return false;
			}
			require_once $file;
			return true;
		default:
			return false;
	}
}

/**
 * 执行(execute)
 */

spl_autoload_register ( 'PHPMailer_autoload' );

?>