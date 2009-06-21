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
function phake()
{
	$args = func_get_args();
	$args = implode(' ', $args);
	$x = explode(' ', $args);
	foreach ($x as $k => $v) {
	    $x[$k] = trim($v);
	}
	Phake_Dispatcher::dispatchCommand($x);
	return;
}


class Phake_Dispatcher
{
    private static  $default_command    = 'help';
    
    private static  $default_action     = 'index';
    
    private static  $original_arguments = array();
    
    /**
     *    ### Dispatch from CLI (::dispatch_cli()) ###
     *   - This is used *only* in the phaker.php script.
     *
        The dispatcher is called from the phaker script, which passes the command line arguments to it:

        Removes the 'php phaker' stuff and passes just the interesting stuff (i.e. user arguments).

        > Phake_Dispatcher::dispatch_cli($argv)

        Cli dispatcher then parses $argv into an array.

        Use "parser" (below) to extract the core arguments (--verbose, --pretend, --pwd, ... etc) and store them in
          Phake_Dispatcher::core_args[]  (e.g.)
     
     */
    public static function dispatchCli($cli_arguments)
    {
        $use_remaining = false;
        $use_cli_arguments = $cli_arguments;
        foreach ($cli_arguments as $k => $v) {
            if (!$use_remaining) {
                unset($use_cli_arguments[$k]);
            }
            
            $v = str_replace(PHAKE_DIR_SRC, '',realpath($v));
            //echo "\n\$v = $v";
            if(trim($v)=='phaker.php') {
                $use_remaining = true;
            }
        }
        
        if(!$use_remaining) {
            $use_cli_arguments = $cli_arguments;
        }
        
        $request    = self::parse($use_cli_arguments);
        
        self::dispatch($request);
    }
    
    
    /**
     *### Dispatch from PHP (::dispatch_command()) ###

    Used to parse calls to phake() - this might just use the _cli parser??


    */
    public static function dispatchCommand($dispatchArguments)
    {
        $request    = self::parse($dispatchArguments);
        self::dispatch($request);
    }
    
    /**
     *  ### Dispatch parser (private) ###

        This function parses the arguments into an object that contains command, action 
        and arguments.

        Params:
          $dispatch_array   An array that may contain command and action and args

          Pull command from the beginning of the array.
          Pull action from beginning of array

        If the command isn't known, defaults to self::default_command (help) and the
        command is pushed onto the $arguments array.

        !! Q: How do we find out if the command is known without loading. Perhaps the __autoload()er can
        !! use something like Phake_Finder::find_class($class_name), which this class can also use??

        If the action isn't known, defaults to self::default_action (index) and the
        action is pushed onto the $arguments array.

        All remaining arguments are added to the $arguments array.

        This returns an object that defines what is to be dispatched:
          new Phake_Dispatcher_Request (
            $command,
            $action,
            $args)
     */
    private static function parse($arguments)
    {
        //echo PHP_EOL."Args = ".implode(', ', $arguments).PHP_EOL;
        $known_commands = Phake_Finder::getKnownCommands();
        //print_r($known_commands);
        
        // Clean up array, just in case
        $arguments = array_values($arguments);
        
        //die();
        $command    = null;
        $action     = null;
        $args       = array();
        
        if(in_array($arguments[0], $known_commands)) {
            $command    = $arguments[0];
            array_shift($arguments);
            $arguments = array_values($arguments);
        } else {
            $command    = self::$default_command;
        }
        
        $actions = Phake_Inspector::getActions($command);
        //print_r($actions);
        //echo PHP_EOL.$arguments[0].PHP_EOL;
        if(in_array($arguments[0], $actions)) {
            $action    = $arguments[0];
            array_shift($arguments);
            $arguments = array_values($arguments);
        } else {
            $action    = self::$default_action;
        }
        
        $ret = new Phake_Dispatcher_Request(
            $command, 
            $action,
            $arguments
            );
        return $ret;
    }
    
    
    /**
     * 
    ### Dispatcher (private) ###

    Does the actual dispatching of the command.

    Requires argument:
      Phake_Dispatcher_Request (as returned by the parser argument, above)

    This will create the command object, pick the method and use reflection to
    find the method's arguments.

    The the passed arguments known, the default ones are added to the configuration:
      verbose
      quiet
      notify
      ... etc 

    If the method asks for these arguments, the it will be passed them - but only 
    if the method's declaration AGREES with the default arguments. E.g. if the 
    method declares "$verbose=''" (i.e. a string), then an exception is thrown:
      (Phake..ScriptIsBad)

    With the required arguments known, the passed arguments are checked using the 
    Zend Opt parser.
    
    This method could be moved to Phake_Dispatcher_Request::dispatch()??
    */
    private static function dispatch(Phake_Dispatcher_Request $dispatchRequest)
    {
        //echo "Dispatching: $dispatchRequest->command:$dispatchRequest->action.".PHP_EOL;
        $class = Phake_Finder::getClassForCommand($dispatchRequest->command);
        
        // To find out:
        $options = self::findOptions($class, $dispatchRequest->action);
        
        // BEGIN: I'm not sure where to put the option parsing stuff
        
		// BEGIN: Run the parser
		$opts = new Phake_Console_Getopt($options, $dispatchRequest->arguments);
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
		foreach(array_keys($options) as $a) {
			$x = explode('-',$a);
			$a = $x[0];
			//echo "Getting opt: $a\n";
			
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
        
        $scriptObject = new $class($dispatchRequest->action, $args);
        self::dispatchAction($scriptObject);
        
        //$scriptObject->dispatchAction();
    }
    
    // Actions are kept in these arrays, but don't ever try getting them - they're for internal purposes only.
    private static $scripts_completed  = array();
    private static $scripts_inprogress = array();
    
    /**
     * Fires the dispatchAction method on the script object, but keeps track of
     * all dispatched and completed actions.
     */
    private static function dispatchAction(Phake_Script & $scriptObject) {
        $id = md5(serialize($scriptObject).time().rand(1000,9999));
        self::$scripts_inprogress[$id] = & $scriptObject;
        self::$scripts_inprogress[$id]->dispatchAction();
        
        self::$scripts_completed[$id] = & self::$scripts_inprogress[$id];
        unset(self::$scripts_inprogress[$id]);
    }
    
    
    /**
     * Extract options from method and return in Zend Opt format
     */
    private function findOptions($class, $method)
    {
        //echo "class:method = $class : $method".PHP_EOL;
        
		$reflect	= new ReflectionClass($class);
		try {
		    $method     = $reflect->getMethod($method);
	    } catch(ReflectionException $e) {
	        die("Exception: ".$e->getMessage(). " for class '$class'");
	    }
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
			
			$params[$name.'-'.$type] = "Docs for $name go here. [$default]";
			
		}
		
		// !!!! Why are these here??
		$params['verbose|v-b'] = 'Verbose. Linked to Phake_Log';
        $params['help|h-b'] = 'Help. Linked to Phake_Help';

        // Other core options:
        $params['notify|n-s'] = 'Notify <email>';
        
		return $params;
    }
}


?>