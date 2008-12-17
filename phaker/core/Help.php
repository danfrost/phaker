<?php

class Phake_Script_Help extends Phake_Script {
	
	/**
	 * \brief	Do some stuff....
	 */
	function index() {
		phake('phaker welcome-short');
		phake('help', 'commands', @$this->args[0]);
	}
	
	/**
	 * \brief	List available commands
	 */
	function commands() {
	}
	
	/**
	 * \brief	Show the config
	 */
	function config() {
		phake('phaker welcome-short');
	}
	
	function howto() {}
}

?>