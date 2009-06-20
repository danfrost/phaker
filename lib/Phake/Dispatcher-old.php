<?php
die(__FILE__.__LINE__.'REMOVE THIS');

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

// Move this to somewhere else

/**
 * Holds the core settings - verbose, help, quiet, ...
  * (Could also hold all other settings??)
 */
class Phake_Options {
    
    private static $options = array();  
    
    public function set($option, $value) {
        self::$options[$option] = $value;
    }
    
    public function get($option) {
        return self::$options[$option];
    }
    
    
}


/**
 * 
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
		
		try {
		    $ret = self::dispatch_command($cli_command, $cli_action, $cli_args);
	    } catch(Phake_Script_EndAllException $e) {
	        
	    }
		
		if(Phake_Options::get('summary')) {
		    echo PHP_EOL.'Show a summary'.PHP_EOL;
		}
		
		
		if(Phake_Options::get('notify')) {
		    
		    $recipient = Phake_Options::get('notify');
		    Phake_Log::log("Sending a notification to: <$recipient>");
		    
		    $cmds = array();
    		$i=0;
    		foreach(self::$completed_commands as $cmd) {
    			$i++;
    			$cmds[] = "  [$i]  ".str_pad(
    				( (str_repeat('> ', $cmd->call_depth-1)).
    				((string) $cmd)), 30);
    		}
    		
    		$msg = PHP_EOL.implode(PHP_EOL, $cmds).PHP_EOL;
    		
    		$tr = new Zend_Mail_Transport_Sendmail('-fdan@danfrost.co.uk');
            Zend_Mail::setDefaultTransport($tr);
            
            $mail = new Zend_Mail();
            $mail->setBodyText($msg);
            $mail->setFrom('dan@danfrost.co.uk', 'Dan');
            $mail->addTo('danielfrost@gmail.com', 'DF');
            $mail->setSubject('Phaker');
            $mail->send();
    		
    		/*
            $mail = new PHPMailer();

            $mail->From = "phaker@3ev.com";
            $mail->FromName = "Phaker";
            $mail->AddAddress($recipient);
            
            $mail->IsHTML(false);
            
            $mail->Subject = "Phake report: ...";
            $mail->Body    = $msg;
            #$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
            
            if(!$mail->Send())
            {
               echo "Message could not be sent. <p>";
               echo "Mailer Error: " . $mail->ErrorInfo;
               die();
            } else {
                echo "EMAIL SENT";
            }
            print_r($mail);
    		*/
    		die(__METHOD__);
            
		}
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
		
		$use_args = array();
		
		if(!class_exists($class)) {
			// log a error: throw new Exception("Command '$command' unknown");
			$use_args[] = $class;
			$class = 'Phake_Script_Help';
		}
        $action = str_replace('-', '_', $action);
		
		foreach($args as $k=>$v) {
		    $use_args[$k] = $v;
		}
		$args = $use_args;
		
		
		
		
		// Find required...
		
		// BEGIN: Loading params for parser
		$reflect	= new ReflectionClass($class);
		try {
		    $method     = $reflect->getMethod($action);
	    } catch(ReflectionException $e) {
	        $method     = $reflect->getMethod('index');
	    }
		$paramObjs 	= $method->getParameters();
		$params		= array();
		
		$params['verbose|v-b'] = 'Verbose. Linked to Phake_Log';
        $params['help|h-b'] = 'Help. Linked to Phake_Help';

        // Other core options:
        $params['notify|n-s'] = 'Notify <email>';
        
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
		// End: Loading params for parser
		
		
		// BEGIN: Prepare args for parser
		$argv = $GLOBALS['argv'];
		unset($argv[0], $argv[1]);
		// END: Prepare args for parser
		
		
		
		
		// BEGIN: Run the parser
		$opts = new Phake_Console_Getopt($params, $argv);
		try {
			$opts->parse();
		} catch(Zend_Console_Getopt_Exception $e) {
            
			// A specific field was wrong
			//print_r($e);
			echo $e->getUsageMessage();
            
			echo "\nPRINT ERROR!\n";
			echo "\nPRINT USAGE!\n";
		}
		// END: Run the parser
		
		
		// 
		// BEGIN: Remaining arguments are used ahead of asigned arguments
		try {
		    $remain = $opts->getRemainingArgs();
	    } catch(Exception $e) {}
	    // END: Remaining arguments are used ahead of asigned arguments
	    
	    
	    // BEGIN: Deal with params
	    
	    // > BEGIN: Core things - Verbosity, help etc. 
	    if($opts->verbose) {
	        Phake_Log::$level   = Phake_Log::verbose;
	        Phake_Options::set('verbose', true);
	    }
	    
	    // ...
	    
	    if($opts->notify) {
	        Phake_Options::set('notify', $opts->notify);
        }
	    
	    // > END: Core things - Verbosity, help etc. 
	    
	    // > END: Prepare args for the command
		$args = array();
		//print_r($opts);
        
		$arg_count = 0;
		foreach(array_keys($params) as $a) {
			$x = explode('-',$a);
			$a = $x[0];
			
			$r = array_shift($remain);
			
			if(trim($r)) {
			    $value = $r;
			} else {
			    $value = $opts->$a;
			}
			//echo "\nArgument $a = [".($value).']';
			$args[$a] = $value;
			
			$arg_count++;
		}
	    // > END: Prepare args for the command
	    		
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