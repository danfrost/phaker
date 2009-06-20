<?php

class DescribeDispatcher extends PHPSpec_Context {
    
	function before() {
	    if(!defined('PhakerSpec_Phake_SRC_DIR')) {
	        define('PhakerSpec_Phake_SRC_DIR', dirname(dirname(__FILE__)).'/lib/Phake/');
	        require_once PhakerSpec_Phake_SRC_DIR.'bootstrap.php';
        }
	}
	
	function itShouldProvideADispatcher() {
	    $this->spec(class_exists('Phake_Dispatcher'))->should->beTrue();
	}
	
	function itShouldParseCommandLineArguments()
	{
	    $args   = array(
	        'php',
	        'phaker.php',
	        'help',
	        'index',
	        'arg1',
	        'arg2',
	        'arg3'
	        );
	    
	    Phake_Dispatcher::dispatchCli($args);
	    
	}
	
}

?>