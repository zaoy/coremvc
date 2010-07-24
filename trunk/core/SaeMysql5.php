<?php
/**
 * 定义(define)
 */
class SaeMysql5 {

	private static $mdbh = array ();
	private static $sdbh = array ();

	/**
	 * 主从分离函数
	 * @param array $args
	 * @return string
	 */
	private static function replication($info) {
		switch ($info [0]){
			case 'execute':
				if (strncasecmp ($info [1], 'select', 6) === 0) {
					return 's';
				} else {
					return 'm';
				}
			case 'selects':
			case 'select':
				return 's';
			default:
				return 'm';
		}
	}

	/**
	 * 初始化函数
	 * @param array $args
	 * @param string $rep
	 * @return array
	 */
	private static function initialize($args, $rep) {
		$args ['connect_port'] = empty ($args ['connect_port']) ? $_SERVER['HTTP_MYSQLPORT'] : $args ['connect_port'];
		$args ['connect_server'] = $rep . $args ['connect_port'] . '.mysql.sae.sina.com.cn';
		$args ['connect_dbname'] = empty ($args ['connect_dbname']) ? 'app_' . $_SERVER['HTTP_APPNAME'] : 'app_' . $args ['connect_dbname'];
		$args ['connect_charset'] = empty ($args ['connect_charset']) ? 'UTF8' : $args ['connect_charset'];
		$args ['connect_username'] = empty ($args ['connect_username']) ? SAE_ACCESSKEY : $args ['connect_username'];
		$args ['connect_password'] = empty ($args ['connect_password']) ? SAE_SECRETKEY : $args ['connect_password'];
		return $args;
	}

	/**
	 * 连接数据库
	 * @param array $args
	 * @return dbh
	 */
	private static function connection($args) {
		$port = $args ['connect_port'];
		$server = $args ['connect_server'];
		$dbname = $args ['connect_dbname'];
		$charset = $args ['connect_charset'];
		$username = $args ['connect_username'];
		$password = $args ['connect_password'];
		$type = $args ['connect_type'];
		$new_link = $args ['connect_new_link'];
		$client_flags = $args ['connect_client_flags'];
		if ($type === 'persist') {
			$dbh = mysql_pconnect ( $server . ':' .$port, $username, $password, ( int ) $client_flags );
		} else {
			$dbh = mysql_connect ( $server . ':' .$port, $username, $password, ( bool ) $new_link, ( int ) $client_flags );
		}
		if ($dbname !== '') {
			mysql_select_db ( $dbname, $dbh );
		}
		if ($charset !== '') {
			mysql_set_charset ( $charset, $dbh );
		}
		return $dbh;
	}

	/**
	 * 连接数据库
	 * @param array $ref
	 * @param string $pos
	 * @param array $args
	 * @return dbh
	 */
	public static function connect($ref, $pos, $info) {
		$rep = self::replication ($info);
		$args = self::initialize ($ref, $rep);
		$dbh = self::connection($args);
		switch ($rep) {
			case 'm':
				self::$mdbh [$pos] = $dbh;
				break;
			case 's':
				self::$sdbh [$pos] = $dbh;
				break;
		}
		return $dbh;
	}

	/**
	 * 重连数据库
	 * @param dbh $dbh
	 * @param array $ref
	 * @param string $pos
	 * @param array $args
	 * @return dbh
	 */
	public static function reconnect($dbh, $ref, $pos, $info) {
		$rep = self::replication ($info);
		switch ($rep) {
			case 'm':
				if (! isset (self::$mdbh [$pos]) || ! is_resource (self::$mdbh [$pos]) || ! mysql_ping (self::$mdbh [$pos])) {
					if (isset (self::$mdbh) && is_resource (self::$mdbh [$pos])) {
						mysql_close(self::$mdbh [$pos]);
						self::$mdbh [$pos] = null;
					}
					$args = self::initialize ($ref, $rep);
					$dbh = self::$mdbh [$pos] = self::connection($args);
				} else {
					$dbh = self::$mdbh [$pos];
				}
				break;
			case 's':
				if (! isset (self::$sdbh [$pos]) || ! is_resource (self::$sdbh [$pos])  || ! mysql_ping (self::$sdbh [$pos])) {
					if (isset (self::$sdbh [$pos]) && is_resource (self::$sdbh [$pos])) {
						mysql_close(self::$sdbh [$pos]);
						self::$sdbh [$pos] = null;
					}
					$args = self::initialize ($ref, $rep);
					$dbh = self::$sdbh [$pos] = self::connection($args);
				} else {
					$dbh = self::$sdbh [$pos];
				}
				break;
		}
		return $dbh;
	}

}
?>