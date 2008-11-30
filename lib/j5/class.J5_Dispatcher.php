<?php


class J5_Dispatcher {
	
	static	private	$cli_command 	= null;
	static	private	$cli_action 	= null;
	static	private $cli_args		= array();
	
	/**
	 * \brief	Dispatch a command line argument
	 */
	static final function dispatch_cli($a) {
		self::$cli_command = $a[1];
		self::$cli_action = @$a[2] ? $a[2] : 'index';
		unset($a[0], $a[1], $a[2]);
		self::$cli_args		= array_values($a);

		echo "\nCMD = ".self::$cli_command;
		echo "\nACTION = ".self::$cli_action;
		echo "\nARGS= ".implode(', ', self::$cli_args);
		
		if(!self::$cli_command) {
			self::$cli_command = 'help';

		}
		
		return self::dispatch_command(self::$cli_command, self::$cli_action, self::$cli_args);
	}
	
	/**
	 * \brief	dispatches $command. This will work out which class to use.
	 */
	static final function dispatch_command($command, $action, $args) {
		$command = strtolower($command);
		$command{0} = strtoupper($command{0});
		$class = 'J5_Command_'.$command;
		
		if(!class_exists($class)) {
			throw new Exception("Command '$command' unknown");
		}
		
		$obj = new $class($args);
		
		return $obj->dispatchAction($action);
	}
}

?>