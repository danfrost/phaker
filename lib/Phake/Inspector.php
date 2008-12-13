<?php


class Phake_Inspector {
	
	/**
	 * \brief	list all scripts
	 */
	function getScripts() {
		$dirs = explode(':', PHAKE_SCRIPTS_DIR);
		
		$commands = array();
		
		foreach ($dirs as $d) {
			$d .= '/';
			
			if ($handle = opendir($d)) {
				
			    /* This is the correct way to loop over the directory. */
			    while (false !== ($file = readdir($handle))) {
			        if(is_file("$d$file")) {
						//echo "\nIncluding file: $d$file";
						$cmd = str_replace('class.PhakeScript_', '', $file);
						$cmd = str_replace('.php', '', $cmd);
						$cmd = strtolower($cmd);
						//echo "\n>	$cmd\n";
						$commands[] = $cmd;
					}
			    }
			    closedir($handle);
			}
		}
		return $commands;
	}
	
	function getActions($cmd) {
		
		$command = strtolower($cmd);
		$command{0} = strtoupper($command{0});
		$class = 'Phake_Script_'.$command;
		
		$cmd = new $class();
		
		$actions = array();
		foreach(get_class_methods($cmd) as $c) {
			if(is_callable(array($class, $c)) && !in_array($c, array('__construct', 'dispatchAction'))) {
				//echo "\n> Callable: $c";
				$actions[] = $c;
			} else {
				//echo "\n> NOT Callable: $c";
			}
		}
		return $actions;
	}
	
	function getActionDocs($cmd, $action) {
		$command = strtolower($cmd);
		$command{0} = strtoupper($command{0});
		$class = 'Phake_Script_'.$command;
		
		$f = Autoloader::get_class_file($class);
		
		$php = file_get_contents($f);
		$x = explode(PHP_EOL, $php);
		
		$last_brief = '';
		foreach($x as $line) {
			
			/*
			
			If line is 'brief', set last brief
			
			if line is $action function, set return brief
			
			if line is function, set last_brief to 
			
			 
			*/
			
			preg_match("_\\\\brief(.*)_", $line, $arr);
			if(trim($arr[1]))
				$last_brief = trim($arr[1]);
			
			preg_match("/function[ ]*$action/", $line, $arr);
			if(trim($arr[0])) {
				$ret_brief = $last_brief;
			} 
			
			preg_match("/function[^\(]*\(/", $line, $arr);
			if($arr[0]) {
				$last_brief = '';
			}
		}
		return $ret_brief;
	}
}


?>