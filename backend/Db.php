<?php

	class  Db{
		private static $conexion=NULL;
		private function __construct (){}

		public static function conectar(){
			$pdo_options[PDO::ATTR_ERRMODE]=PDO::ERRMODE_EXCEPTION;
			self::$conexion= new PDO('mysql:host=localhost;dbname=killaric_siteec','killaric_mdspaec','7!lla_ric3c',$pdo_options);
			//self::$conexion= new PDO('mysql:host=localhost;dbname=killaric_siteec','root','',$pdo_options);
			return self::$conexion;
		}		
	} 

?>