<?php

/**
 * PHP version of the phake CLI.
 * 
 * Usage is just like phake on the commandline:
 * <code>
 * phake('somescript', 'someaction', 'someargs...');
 * </code>
 *
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
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
	
	$args = array_values($args);

	Phake_Dispatcher::dispatch_command($controller, $action, $args);
}

/**
 * Dis
 * 
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_Dispatcher {
	
	/**
	 * @deprecated
	 */
	static	private	$cli_command 	= null;
	
	/**
	 * @deprecated
	 */
	static	private	$cli_action 	= null;
	
	/**
	 * @deprecated
	 */
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
	
	/**
	 * Stack of commands being processed
	 */
	static	public $dispatched_commands	= array();
	
	/**
	 * Stack of commands that _have been_ processed
	 */
	static	public $completed_commands		= array(); //  - can be used for debugging
	
	/**
	 * Contains how many commands are currently dispatched. 
	 */
	static	public $dispatched_commands_counter	= 0; // To keep track of commands
	
	/**
	 * Dispatches $action in $command with $args. 
	 * 
	 * This will work out which class to use, create an instance of it and, if it 
	 * completed successfully the command object will move it to self::$completed_commands.
	 * 
	 * If the command throws an exception, this is printed by self::print_error()
	 */
	static final function dispatch_command($command, $action, $args) {
		self::$dispatched_commands_counter++;
		
		$count = self::$dispatched_commands_counter;
		// echo "\n> Depth = ".count(self::$dispatched_commands)."; Command: $command\n";
		
		$command = strtolower($command);
		$command{0} = strtoupper($command{0});
		$class = 'Phake_Script_'.$command;
		//echo "\nCommand: $class\n";
		
		if(!class_exists($class)) {
			throw new Exception("Command '$command' unknown");
		}
        $action = str_replace('-', '_', $action);
		
		// Begin: parse args 
		/*
		1. get args from class
		2. parse given args
		3. if args don't match, show usage
		*/
		
		// Find required...
		$reflect	= new ReflectionClass($class);
		$method     = $reflect->getMethod($action);
		$paramObjs 	= $method->getParameters();
		$params		= array();
		
		// Create the config for Zend_Console_Getopt
		foreach($paramObjs as $p) {
			// Type
			$name		= $p->getName();
			$default	= $p->isDefaultValueAvailable()	? $p->getDefaultValue() : null;
			
			$required	= $p->isOptional();
			
			// work out type
			$type = null;
			if($default===true || $default===false) {
				$type = 'b';
				$default = $default===true ? 'true' : 'false';
			} else
			if($default==="") {
				$type = 's';
				$default = 'empty string';
			} else
			if($default===0 || is_int($default)) {
				$type = 'i';
				$default = $default===0 ? '0': $default;
			}
			
			$params[$name.'-'.$type] = "Docs for $name. [$default]";
			
		}
		
		$argv = $GLOBALS['argv'];
		unset($argv[0], $argv[1]);
		
		$opts = new Zend_Console_Getopt($params, $argv);

		try {
			$opts->parse();
		} catch(Zend_Console_Getopt_Exception $e) {
            
			// A specific field was wrong
			//print_r($e);
			//echo $e->getUsageMessage();
            
			echo "\nPRINT ERROR!\n";
			echo "\nPRINT USAGE!\n";
		}
		// Check that required fields are filled

		$args = array();
		
		// Remaining arguments are used ahead of asigned arguments
		try {
		    $remain = $opts->getRemainingArgs();
	    } catch(Exception $e) {}
		$count = 0;
		foreach(array_keys($params) as $a) {
			$x = explode('-',$a);
			$a = $x[0];
			
			$r = array_shift($remain);
			//echo "\n.$r";
			if($r) {
			    $value = $r;
			} else {
			    $value = $opts->$a;
			}
			//echo "\nArgument $a = [".($value).']';
			$args[$a] = $opts->$a;
			
			$count++;
		}
		
		// End: parse args
		
		
		
		#dbg("Running: $command::$action");
		
		try {
			$obj = new $class($action, $args);
			self::$dispatched_commands[$count] = & $obj;
		
			self::$dispatched_commands[$count]->dispatchAction();
		} catch(Phake_Script_ScriptException $e) {
			//print_r(self::$dispatched_commands);
			self::print_error($e);
		}
		
		// Move completed command to the other array
		$obj = & self::$dispatched_commands[$count];
		unset(self::$dispatched_commands[$count]);
		
		self::$completed_commands[$count] = & $obj;
		self::$completed_commands[$count]->call_depth = count(self::$dispatched_commands)+1;
		//echo "\nDoing action: $action\n";
		
		//return c($action);
	}
	
	/**
	 * Print an error.
	 * 
	 * @see Phake_Script::error()
	 */
	static final function print_error(Phake_Script_ScriptException $e) {
		
		// Move this to ... not sure where??
		
		echo PHP_EOL.PHP_EOL."Completed commands:";
		
		$cmds = array();
		$i=0;
		foreach(self::$completed_commands as $cmd) {
			$i++;
			$cmds[] = "  [$i]  ".str_pad(
				( (str_repeat('> ', $cmd->call_depth-1)).
				((string) $cmd)), 30);
		}
		echo PHP_EOL.implode(PHP_EOL, $cmds);
		
		echo PHP_EOL.PHP_EOL."Failed: ";
		
		$cmds = array();
		$j=1;
		foreach(self::$dispatched_commands as $cmd) {
			$i++;
			$cmds[] = "  [$i]  ".str_pad(
				( (str_repeat('> ', $j++)).
				((string) $cmd)), 30).((($i)==count(self::$dispatched_commands)? ' !! Exception: '.$e->getMessage() : ''));
		}
		echo PHP_EOL.implode(PHP_EOL, $cmds);
		
		throw new Phake_Script_EndAllException("Please fix the above error before continuing");
		
	}
}

?>