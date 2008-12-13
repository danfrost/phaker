<?php
/**
 * Shared stuff
 */

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



// TODO: Make __autoload pluggable so this can be used in other places (e.g. in J5)

class Autoloader {
	static	$class_cache = array();
	
	function get_class_dir($class_name) {
		return dirname(self::$class_cache[$class_name]).'/';
	}
	
	function get_class_file($class_name) {
		return self::$class_cache[$class_name];
	}
}


function __autoload($class_name) {
	
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
		
		require_once $try_file;
		
		if(!class_exists($class_name)) {
			throw new Exception("'$class_name' does not exist after including likely file: '$try_file'");
		}
	} else {
		print_r(debug_backtrace());
		throw new Exception("$class_name does not exist");
	}
}


?>