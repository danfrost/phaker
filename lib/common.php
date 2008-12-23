<?php
/**
 * Shared functions
 * @package Phaker
 * @filesource
 */

if(!function_exists('readline')) {
	/**
	 * PHP version of 'readline()' function. 
	 */
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

// 

/**
 * Class for finding out where classes were loaded from.
 * @todo Replace Autoloader with Zend's autoloader
 */
class Autoloader {
	static	$class_cache = array();
	
	function get_class_dir($class_name) {
		//print_r(self::$class_cache);
		//echo "[".self::$class_cache[$class_name]."]";
		//echo "[".dirname(self::$class_cache[$class_name])."]";
		return dirname(self::$class_cache[$class_name]).'/';
	}
	
	function get_class_file($class_name) {
		return self::$class_cache[$class_name];
	}
}

/**
 * Autoloader function for Phake core and Phake scripts. 
 * @see  Autoloader
 * @todo Replace Autoloader with Zend's autoloader
 */
function __autoload($class_name) {
	//echo "\nLoading: $class_name\n";
	// 1. Try to load normal file
	$file_name = str_replace('_', '/', $class_name).'.php';
	//echo "::1Loading $class_name from file $file_name\n";
	if(file_exists(PHAKER_LIB_DIR.$file_name)) {
		Autoloader::$class_cache[$class_name] = PHAKER_LIB_DIR.$file_name;
		require_once PHAKER_LIB_DIR.$file_name;
		return;
	}
	
	// 2. If that didn't work, it might be a phake script:
	
	// Look up installed Phake Scripts...
	$f = str_replace('Phake_Script_', '', $class_name).'.php';
	//echo "\n::[2] Loading $class_name from file $file_name\n";
	
	$dirs = explode(':', PHAKE_SCRIPTS_DIR);
	
	$try_file = findFileInDirs($f, $dirs);
	//echo "\nFound file: $try_file\n";
	
	if(file_exists($try_file)) {
		Autoloader::$class_cache[$class_name] = $try_file;
		
		// #n: don't include the file if there's no 'class'
		$php = file_get_contents($try_file);
		$tokens = token_get_all($php);
		$contains_class = false;
		foreach($tokens as $t) {
			if(@$t[0]==T_CLASS) {
				$contains_class = true;
			}
		}
		if($contains_class) {
			require_once $try_file;
		}
		
		if(!class_exists($class_name)) {
			// If this is the case, we try to create a class on the fly for the command
			$action_code = file_get_contents($try_file);
			
			$x = explode(PHP_EOL, $action_code);
			if(trim($x[0])=='<?php') {
				unset($x[0]);
			}
			if($x[count($x)]=='?>') {
				unset($x[count($x)]);
			}
			$action_code = implode(PHP_EOL, $x);
			
			$class_code = "class $class_name extends Phake_Script { 
				function index() {
					$action_code
				}
			}";
			
			eval($class_code);
			//echo "[$class_name]";
			if(!class_exists($class_name)) {
				throw new Exception("$class_name does not exist");
			}
		}
	} else {
		print_r(debug_backtrace());
		throw new Exception("$class_name does not exist");
	}
}


?>