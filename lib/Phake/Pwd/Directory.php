<?php

class Phake_Pwd_Directory {
    
    private $dir    = '';
    
    function __construct($dir) 
    {
        //$this->dir = trim($dir);
        $this->setPwd($dir);
    }
    
    function __toString() 
    {
        return $this->getPwd();
    }
    
    public function getPwd()
    {
        return $this->dir;
    }
    
    public function setPwd($dir) 
    {
        $this->dir = trim($dir);
    }
    
    /**
     * PWD action - this is an alias of a file action:
     *  $pwdObject->doStuff(filename)
     * = 
     *  $f = f('filename')
     *  $f->doStuff();
     */
    function __call($action, $args)
    {
        $file = $args[0];
        $f = f($file);
        
        unset($args[0]);
        $args = array_values($args);
        reset($args);
        return $f->$action($args);
        
        return;
        /*
        print_r($f);
        print_r($args);
        die("Action = $action, ".@implode(', '.$args));
        
        
        echo PHP_EOL."Calling: ".$action;
        
        $action{0} = strtoupper($action{0});
		$class = 'Phake_Action_'.$action;
		echo "\n\nCalling: $class / $this\n\n";
		
		if(!class_exists($class)) {
		    throw new Exception("'$class' does not exist");
		}
		
		$o = new $class($this);
		
		try {
    		$o->setArgs($args);
    		$o->print_docs();
    		$o->runAction($args);
    		$this->actions[] = & $o;
		} catch(Exception $e) {
		    // This should be removed - phpspec was throwing all kinds of errors
		    echo "\nProblem: ".$e->getMessage();
		}
		
		return $o;
        */
    }
    
}

?>