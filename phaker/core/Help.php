<?php
function red($a) {
	$exec = 'echo -e "\\033[1m'.$a.'\\033[0m"';
	exec("$exec", $arr);
	return implode(PHP_EOL, $arr);
}
function blue($a) {
	//$exec = 'echo -e "\\033[1m'.$a.'\\033[0m"';
	$exec = 'echo -e \'\E[34;1m'.$a.'.\'; tput sgr0; tput sgr0';//';echo -e "\\033[1m'.$a.'\\033[0m"';
	exec("$exec", $arr);
	return implode(PHP_EOL, $arr);
}


/*
 * @package Phaker
 */
class Phake_Script_Help extends Phake_Script {
	
	/**
	 * Default help message
	 */
	function index() {
		phake('phaker welcomeshort');
		phake('help', 'commands');
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
	 * How to create a phake script (can also be run by 'phake howto')
	 */
	function howto() {}
	
	/**
	 * Short tutorial on phake scripts (can also be run by 'phake tutorial')
	 */
	function tutorial() {}
	
	/**
	 * List available commands (can also be run by 'phake commands')
	 */
	function commands($command='') {}
	
	/**
	 * Show the config
	 */
	function config() {
		phake('phaker welcome-short');
	}
	
}

?>