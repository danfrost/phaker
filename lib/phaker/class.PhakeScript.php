<?php

class Phake {
	
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
		$class = 'PhakeScript_'.$command;
		
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
}


class PhakeScript {
	
	static public $current;
	
	private	$action;
	private	$args = array();
	
	function __construct($action=null, $args=array()) {
		PhakeScript::$current = $this;
		
		if(!method_exists($this, $action)) {
			//echo "\n> Action doesn't exist: $action\n";
			$action = 'index';
		}
		
		$this->action = $action;
		
		$this->args = $args;
		
		//$this->dispatchAction();
	}
	
	function dispatchAction() {
		$action = $this->action;
		$this->$action();
	}
	/*
	function _index() {
		p::touch('myfile.txt');
	}
	
	function _example() {
		p::touch('myfile.txt');
	}*/
}


?>