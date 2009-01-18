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
	function index(
	        $command='',
			$somevar=required_int,
			$a_bool=false, // use true / false 
			$a_string="", 
			$a_int=0,
			$an_int=1) {
		/*
		$int = 0
		$string = ""
		*/
		phake('phaker welcome-short');
		if($command) {
		    $this->commands;
		}
		//phake('help', 'commands', @$command);
	}
	
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
	 * List available commands
	 */
	private function commands() {}
	
	/**
	 * \brief	Show the config
	 */
	function config() {
		phake('phaker welcome-short');
	}
	
}

?>