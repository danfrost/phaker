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
}


class Phake_Script {
	
	static public $current;
	
	protected	$action;
	protected	$args = array();
	
	function __construct($action=null, $args=array()) {
		self::$current = $this;
		
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
		$this->include_action_view();
		
	}
	
	
	function include_action_view() {
		$dir = Autoloader::get_class_dir(get_class($this));
		$view_file = $dir.$this->get_cmd().'/'.$this->action.'.php';
		
		if(!file_exists($view_file)) {
			return;
		}
		
		ob_start();
		require $view_file;
		$ret = ob_get_clean();
		
		$x = explode(PHP_EOL, $ret);
		$ret = implode(PHP_EOL.'> ', $x);
		
		echo PHP_EOL.$ret;
	}
	
	/**
	 * \brief	Return the cmd that $this provides 
	 */
	function get_cmd() {
		return str_replace('Phake_Script_', '', get_class($this));
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