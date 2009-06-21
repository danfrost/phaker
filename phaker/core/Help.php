<?php


/*
 * @package Phaker
 */
class Phake_Script_Help extends Phake_Script {
	
	/**
	 * List available commands (can also be run by 'phake commands')
	 */
	function index($command='')
	{
	}
	
	/**
	 * FOR TESTING THE PARAMS SYSTEM. 
	 */
	function testing(
	    $try=0,
	    $message=""
	    ) {
	    for($i=0;$i<=$try;$i++) {
	        echo "\nTry: $i - $message";
	    }
	    echo "\nDone";
	}
	
	/**
	 * How to create a phake script
	 */
	function howto() {}
	
	/**
	 * Short tutorial on phake scripts
	 */
	function tutorial() {}
	
	/**
	 * Show the config
	 */
	function config() {
		phake('phaker welcome-short');
	}
	
}

?>