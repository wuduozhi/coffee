<?php
namespace Common;

class DBCommon{
		protected static $user = 'xuegong';
		protected static $passwd  = 'xuegong2015';
		protected static $host = '202.197.100.9/orcl';
		protected static $charset = 'utf8';
		protected static $conn;


		//保存例实例在此属性中
		private static $_instance;

		//构造函数声明为private,防止直接创建对象
		private function __construct()
		{
			// echo 'I am Construceted';
		}

		//单例方法
		public static function getDB()
		{
			if(!isset(self::$_instance))
			{
				self::$conn = oci_connect(self::$user,self::$passwd,self::$host, self::$charset) or die("CONNECT ERROR");
				$c=__CLASS__;
				self::$_instance = new $c;
			}
			return self::$_instance;
		}

		//阻止用户复制对象实例
		public function __clone()
		{
			trigger_error('Clone is not allow' ,E_USER_ERROR);
		}

		/*
			执行一条sql语句，返回单条结果
		*/

		public function get($sql){
			$res = oci_parse(self::$conn, $sql);
			oci_execute($res, OCI_DEFAULT) or die("SQL ERROR");
			$out = oci_fetch_assoc($res);
			return $out;
		}

		/*
			执行一条sql语句，返回多条结果
		*/

		// public function gets($sql){
		// 		$res = oci_parse(self::$conn, $sql);
		// 		oci_execute($res, OCI_DEFAULT) or die("SQL ERROR");
		// 		$outs = array();
		// 		while ($out = oci_fetch_assoc($res)) {
		// 			$outs[] = $out;
		// 		}
		// 		return $outs;
		// }

		public function gets($sql,$array=array()){
				//编译SQL语句
				$stmt = oci_parse(self::$conn, $sql);

				//设置绑定变量的值     此处有个大大的bug
				foreach ($array as $key => $value) {
					oci_bind_by_name($stmt, $key, $array[$key]);  //很关键!! 不能用$value  详情请看 http://www.mjix.com/archives/223.html
				}
				//执行语句
				oci_execute($stmt);
				//取结果数据
				$outs = array();
				while ($out = oci_fetch_array($stmt, OCI_ASSOC)) {
					$outs[] = $out;
				}
				//释放资源
				oci_free_statement($stmt);
				return $outs;
		}

		public function __destruct(){
				oci_close(self::$conn);
				self::$_instance = null;
		}
}





















?>
