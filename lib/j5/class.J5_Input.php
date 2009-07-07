<?php


if(!function_exists('readline')) {
	function readline($prompt="") {
	   print $prompt;
	   $out = "";
	   $key = "";
	   $key = fgetc(STDIN);        //read from standard input (keyboard)
	   while ($key!="\n")        //if the newline character has not yet arrived read another
	   {
	       $out.= $key;
	       $key = fread(STDIN, 1);
	   }
	   return trim($out);
	}
}


// Thrown if user requests help
class J5_Input_HelpException extends Exception {}

// Contains value for given variable. Allows us to hook in when the values are printed.
class J5_InputValue {
	private $name;
	private $value;
	function __construct($name, $value) {
		$this->name = $name;
		$this->value = $value;
	}
	
	function __toString() {
		return $this->asVariable();
	}
	
	/**
	 * \brief	Returns variable value as a variable name. Usage in template is:
	 * \code
	 * $this->myvar->asVariable()
	 * \endcode
	 */
	function asVariable() {
		$len = strlen($this->value);
		$value = '';
		for ($i=0; $i < $len; $i++) { 
			//
			$ch = ($this->value{$i});
			//echo "\n>$ch.";
			if($ch==' ') {
				$capNext = true;
			} else {
				if($capNext) {
					$value .= strtoupper($this->value{$i}); 
				} else {
					$value .= $this->value{$i};
				}
				$capNext = false;
			}
		}
		
		$v = str_replace(array(
			' ', '-', ',', '.'
			), '', $value);
//		die($v);
		return new J5_InputValue($this->name, $v);
	}
}

class J5_Input {
	
	static private $cache	= array(); // run time cache
	static private $cache_from_file	= array(); // run time cache
	static private $cache_file	= '';
	
	static private $have_tried_cache_file = false;
	
	static function get($var_name, $prompt=null) {
		
		self::load_from_cache();
		
		// TODO: Use an input method to get this
		// print_r(self::$cache);
		if(!isset(self::$cache[$var_name])) {
			$prompt = $prompt ? $prompt : $var_name;
			
			$default = isset(self::$cache_from_file[$var_name]) ? self::$cache_from_file[$var_name] : '';
			
			echo "\nWhat is '".$prompt."' ".($default ? "[default '$default']" : '')." \t";
			
			$var = readline();
			if($var=='?') {
				throw new J5_Input_HelpException();
			}
			
			self::$cache[$var_name] = trim($var) ? trim($var) : $default;
			
		} else {
			echo "\n\t[Using cached value for $var_name - ".self::$cache[$var_name]."]";
		}
		self::update_cache();
		
		return new J5_InputValue($var_name, self::$cache[$var_name]);
	}
	
	static function load_from_cache() {
		if(self::$have_tried_cache_file) return;
		self::$have_tried_cache_file = true;
		
		//$ini = parse_ini_file($_SERVER['PWD'].'/'.J5::cache_dir.'J5_Input.ini');
		//$ini = parse_ini_file($_SERVER['PWD'].'/'.J5::cache_dir.'J5_Input.ini');
		$ini = J5::get_cache_ini('JS_Input.ini');
		//parse_ini_file($_SERVER['PWD'].'/'.J5::cache_dir.'J5_Input.ini');
		
		self::$cache_from_file = $ini;
		
		if(readline("You have a cache file - use it?")=='y') {
			self::$cache = self::$cache_from_file;
		}
	}
	
	static function update_cache() {
		$c = array();
		foreach(self::$cache as $k=>$v) {
			$c[] = "$k = $v";
		}
		$c = implode("\n", $c)."\n\n";
		//file_put_contents($_SERVER['PWD'].'/'.J5::cache_dir.'J5_Input.ini', $c);
		J5::set_cache_ini('JS_Input.ini', $c);
	}
	
	static function write_config() {
		$c = array();
		foreach(self::$cache as $k=>$v) {
			$c[] = "$k = $v";
		}
		$c = implode("\n", $c)."\n\n";
		file_put_contents($_SERVER['PWD'].'/'.J5::config_dir.'config.ini', $c);
	}
}


?>