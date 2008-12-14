<?php

/**
 * \brief	PHP version of the phake CLI.
 */
function phake($controller, $action=null) {
	$args = func_get_args();

	if(count($args)==1) {
		// Parsing CLI-style arguments
		$x = explode(' ', $args[0]);
		$args2 = array();
		foreach ($x as $v) {
			$args2[] = trim($v);
		}
		$controller = $args2[0];
		$action = $args2[1];
		unset($args2[0], $args2[1]);
		$args = array_values($args2);
	}
	//dbg("Controller: $controller, Action: $action");
	unset($args[0], $args[1]);
	
	Phake_Dispatcher::dispatch_command($controller, $action, $args);
}

class Phake_Dispatcher {
	
	static	private	$cli_command 	= null;
	static	private	$cli_action 	= null;
	static	private $cli_args		= array();
	
	/**
	 * \brief	Dispatch a command line argument
	 */
	static final function dispatch_cli($a) {
		/*
		$cli_command = $a[1];
		$cli_action = @$a[2] ? $a[2] : 'index';
		unset($a[0], $a[1], $a[2]);
		$cli_args		= array_values($a);
		*/
		$cli_cmdAction	= $a[1];
		$x	= explode(':', $cli_cmdAction);
		$cli_command 	= $x[0];
		$cli_action 	= @$x[1] ? @$x[1] : 'index';
		
		unset($a[0], $a[1]);
		
		$cli_args		= array_values($a);
		
		if(!$cli_command) {
			//echo "\nDefaulting\n";
			$cli_command = 'help';
		}
		
		return self::dispatch_command($cli_command, $cli_action, $cli_args);
	}
	
	static	public $dispatched_commands	= array(); // Stack of commands being processed - can be used for debugging
	static	public $completed_commands		= array(); // Stack of commands being processed - can be used for debugging
	static	public $dispatched_commands_counter	= 0; // To keep track of commands
	
	/**
	 * \brief	dispatches $command. This will work out which class to use.
	 */
	static final function dispatch_command($command, $action, $args) {
		self::$dispatched_commands_counter++;
		
		$count = self::$dispatched_commands_counter;
		echo "\n> Depth = ".count(self::$dispatched_commands)."; Command: $command\n";
		//echo "\n";
		
		$command = strtolower($command);
		$command{0} = strtoupper($command{0});
		$class = 'Phake_Script_'.$command;
		//echo "\nCommand: $class\n";
		
		if(!class_exists($class)) {
			throw new Exception("Command '$command' unknown");
		}
		
		#dbg("Running: $command::$action");
		
		$action = str_replace('-', '_', $action);
		
		$obj = new $class($action, $args);
		self::$dispatched_commands[$count] = & $obj;
		
		self::$dispatched_commands[$count]->dispatchAction();
		
		// Move completed command to the other array
		$obj = & self::$dispatched_commands[$count];
		unset(self::$dispatched_commands[$count]);
		
		self::$completed_commands[$count] = & $obj;
		
		//echo "\nDoing action: $action\n";
		
		//return c($action);
	}	
}

// TODO: Make __autoload pluggable so this can be used in other places (e.g. in J5)
function ____autoload($class_name) {
	//echo "\nTrying to load: '$class_name' from dir ".PHAKE_SCRIPTS_DIR." \n";
	$f = 'class.'.$class_name.'.php';
	$dirs = explode(':', PHAKE_SCRIPTS_DIR);
	
	$try_file = findFileInDirs($f, $dirs);
	
	if(file_exists($try_file)) {
		require_once $try_file;
		
		if(!class_exists($class_name)) {
			throw new Exception("'$class_name' does not exist after including likely file: '$try_file'");
		}
	} else {
		throw new Exception("$class_name does not exist");
	}
}


?>