<?php

/**
 * Provides reflection required for interrogating Phake_Scripts
 */
class Phake_Reflection {
	
	private	$class_name;
	
	function __construct($class_name) {
		$this->class_name = $class_name;
		Autoloader::load($class_name);
	//	$this->parse();
		
		// get public methods
		$class 	= new ReflectionClass($class_name);
		
		$methods 	= $class->getMethods();
		
		foreach ($methods as $m) {
			if($m->isPublic() && ($m->getDeclaringClass()->getName()==$class_name)) { 
				//echo "\nGot method: ".$m->getName();//." (".$m->getDeclaringClass()->getName().")";
				
				//echo "\nParams";
				
				$params = $m->getParameters();
				
				$required = array();
				$optional = array();
				
				foreach($params as $p) {
					//echo "\n  > $p";
					if($p->isOptional()) {
						//echo "\n? --".$p->getName().' = '.$p->getDefaultValue();
						$default	= $p->getDefaultValue();
						
						if($default===true) {
							$default = 'true';
						}
						if($default===false) {
							$default = 'false';
						}
						if($default===null) {
							$default = 'null';
						}
						
						$optional[] = '  --'.$p->getName().' = '.$default;
					} else {
						//echo "\n! --".$p->getName();
						$required[] = $p->getName();
					}
				}
				
				$method = $m->getName();
				
				echo "\n\n!!\nphake $class_name:$method ".implode(' ', $required)."\n".implode("\n", $optional)."";
				
			}
			//else 
			//	echo "\n!Got method: ".$m->getName()." (".$m->getDeclaringClass()->getName().")";
			
		}
		return;
		//$method = new ReflectionMethod($class_name, 'index');
		
		
		
		/**
		Usage:
			phake command:method	requiredparams...	[optional params]
				--opt1	default		description
		**/
		print_r($m);
		// Print out basic information
		printf(
		    "===> The %s%s%s%s%s%s%s method '%s' (which is %s)\n" .
		    "     declared in %s\n" .
		    "     lines %d to %d\n" .
		    "     having the modifiers %d[%s]\n",
		        $method->isInternal() ? 'internal' : 'user-defined',
		        $method->isAbstract() ? ' abstract' : '',
		        $method->isFinal() ? ' final' : '',
		        $method->isPublic() ? ' public' : '',
		        $method->isPrivate() ? ' private' : '',
		        $method->isProtected() ? ' protected' : '',
		        $method->isStatic() ? ' static' : '',
		        $method->getName(),
		        $method->isConstructor() ? 'the constructor' : 'a regular method',
		        $method->getFileName(),
		        $method->getStartLine(),
		        $method->getEndline(),
		        $method->getModifiers(),
		        implode(' ', Reflection::getModifierNames($method->getModifiers()))
		);
		
		$p = $method->getParameters();
		print_r($p[0]->isOptional() ? 'yes' : 'no');
		
		die(__METHOD__);
	}
	
	function getActions() {
		echo "\nGetting $name\n";
		
		print_r(get_class_methods($this->class_name));
	}
	
	
}
// start
/*
get command for class (vv)	Command::getClass(); Command::getCommand()

get command docs (class) - Command::getDocs()

get actions				- Command::getActions()

get actions params		- for printing usage.
						  replace with Action::getUsage()

get action docs			- Action::getDocs()
*/
// end


/**
 * Provides inspection for commands and their actions.
 * 
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_Inspector {
	
	/**
	 * List all phake scripts
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
						//$cmd = str_replace('.php', '', $cmd);
						
						preg_match("/([A-Z][a-zA-Z_]*)\.php/", $cmd, $arr);
						//echo "\n>	$cmd\n";print_r($arr);
						if($arr[1]) {
							$cmd = $arr[1];
							$cmd = str_replace('.php', '', $cmd);
							
							// #n: Finish reflection and decide how to use it
							//$reflector = new Phake_Reflection("Phake_Script_$cmd");
							//print_r($reflector);
							//die(__METHOD__);
							//$reflector->getMethod('index');
							
							$cmd = strtolower($cmd);
							if($cmd!='phaker') {
								$commands[] = $cmd;
							}
						}
					}
			    }
			    closedir($handle);
			}
		}
		return $commands;
	}
	
	/**
	 * Get all actions for a given script. An action is any public method (excluding __construct, dispatchActino and __toString)
	 */
	function getActions($cmd) {
		
		$command = strtolower($cmd);
		$command{0} = strtoupper($command{0});
		$class = 'Phake_Script_'.$command;
		
		$cmd = new $class();
		
		$actions = array();
		foreach(get_class_methods($cmd) as $c) {
			if(is_callable(array($class, $c)) && !in_array($c, array('__construct', 'dispatchAction', '__toString'))) {
				//echo "\n> Callable: $c";
				$actions[] = $c;
			} else {
				//echo "\n> NOT Callable: $c";
			}
		}
		return $actions;
	}
	
	/**
	 * Get the documentation for a given action. This uses the PhpDocumentor convention of the first 
	 * part of the method's documentation.
	 * 
	 * @todo 	!!! This is broken since switching from doxygen-style docs to phpdocumentor
	 */
	function getActionDocs($cmd, $action) {
		$command = strtolower($cmd);
		$command{0} = strtoupper($command{0});
		$class = 'Phake_Script_'.$command;
		
		$f = Autoloader::get_class_file($class);
		
		$php = file_get_contents($f);
		$x = explode(PHP_EOL, $php);
		
		$last_brief = '';
		$record_next_line = false;
		foreach($x as $line) {
			if($record_next_line) {
				//echo "\n>> $line";
				$record_next_line = false;
				preg_match("_\*(.*)_", $line, $arr);
				$last_brief	= trim(str_replace('\\brief', '', $arr[1]));
			}
			
			preg_match("_/\*\*_", $line, $arr);
			if(@$arr[0]) {
				$record_next_line = true;
			}
			
			preg_match("_\*/_", $line, $arr);
			if(@$arr[0]) {
				$record_next_line = false;
			}
			
			preg_match("/function[ ]*$action\(/", $line, $arr);
			if(trim(@$arr[0])) {
				$ret_brief = $last_brief;
			} 
			
			preg_match("/function[^\(]*\(/", $line, $arr);
			if(@$arr[0]) {
				$last_brief = '';
			}
		}
		return $ret_brief;
	}
	
	function getCommandDocs($cmd) {
		$command = strtolower($cmd);
		$command{0} = strtoupper($command{0});
		$class = 'Phake_Script_'.$command;
		
		$o = new $class();
		
		$f = Autoloader::get_class_file($class);
		
		$php = file_get_contents($f);
		$x = explode(PHP_EOL, $php);
		
		$tokens = token_get_all($php);
		$record = false;
		$last_doc = array();
		foreach($tokens as $t) {
			if(@$t[0]==T_DOC_COMMENT) {
				$last_doc = $t[1];
			}
			if(@$t[0]==T_CLASS) {
				$class_doc = $last_doc;
				break;
			}
		}
		
		$x = explode(PHP_EOL, $class_doc);
		$ret = array();
		$is_code = false;
		foreach($x as $line) {
			if(in_array(trim($line), array('/**', '*/'))) {
				continue;
			}
			
			preg_match("_\*(.*)_", $line, $arr);
			$line = trim($arr[1]);
			
			preg_match("_<code>_", $line, $arr);
			if($arr[0]) {
				$is_code = true;
				continue;
			}
			preg_match("_</code>_", $line, $arr);
			if($arr[0]) {
				$is_code = false;
				continue;
			}
						
			if($is_code) {
				$line = ">  $line";
			}
			
			$ret[] = $line;
		}
		return '  '.implode(PHP_EOL.'  ', $ret);
	}
}


?>