<?php

class J5_Config {
	
	static private	$is_loaded  = false;	
	static private	$config 	= false;
	
	static function set($var, $value) {
		self::load();
		
		self::$config[$var] = $value;
		
	}
	
	/**
	 * \brief	Return config value. If $var==null, array of all values are returned.
	 */
	static function get($var=null) {
		self::load();
		if($var===null) {
			return self::$config;
		} else {
			return self::$config[$var];
		}
	}
	
	static function load() {
		if(self::$is_loaded) return;
		
		$ini = J5::get_cache_ini('JS_Input.ini');
		//print_r($ini);
		self::$config = $ini;
		self::$is_loaded = true;
	}
	
	static function save() {
		self::load();
		
		J5::set_cache_ini('JS_Input.ini', self::$config);
		//print_r($ini);
		//self::$config = $ini;
		self::$is_loaded = false;
	}	
}


?>