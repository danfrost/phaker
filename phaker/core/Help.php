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

/**
 * @package Phaker
 */
class Phake_Script_Help extends Phake_Script {
	
	/**
	 * Default help message
	 */
	function index($command, $req1, $verbose=true, $blar=null) {
		phake('phaker welcome-short');
		phake('help', 'commands', @$this->args[0]);
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
	function commands() {}
	
	/**
	 * \brief	Show the config
	 */
	function config() {
		phake('phaker welcome-short');
	}
	
}

?>