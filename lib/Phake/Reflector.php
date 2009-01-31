<?php

/**
 * Represents 
 */
class Phake_Reflector_Param {
	
	function __construct($name, $default=null, $required=false) {
		
	}
}
/**
 * Provides reflection required for interrogating Phake_Scripts
 */
class Phake_Reflector {
	
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
	
	/**
	 * Return params for method. 
	 */
	function getMethodParams($method) 
	{
		$class 	= new ReflectionClass($this->class_name);
		
		$method 	= $class->getMethod($method);
		
		$params 		= $method->getParameters();
		
		$required = array();
		$optional = array();
		
		foreach($params as $p) {
			echo "\n  > $p";
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
				
				//$optional[] = '  --'.$p->getName().' = '.$default;
				$params_ret[] = new Phake_Reflector_Param(
					$p->getName(),
					$default,
					false
					);
			} else {
				//echo "\n! --".$p->getName();
				//$required[] = $p->getName();
				$params_ret[] = new Phake_Reflector_Param(
					$p->getName(),
					null,
					true
					);
			}
		}
		
		print_r($params_ret);
		return;
		print_r($required);
	}
	
	function getActions() {
		echo "\nGetting $name\n";
		
		print_r(get_class_methods($this->class_name));
	}
	
	
}

?>