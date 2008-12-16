<?php

class Phake_Script_Help extends Phake_Script {
	
	/**
	 * \brief	Do some stuff....
	 */
	function index() {
		phake('phaker welcome-short');
		echo "
  Phake is a bit like make in PHP, but with some extra.
  
		";
		phake('help commands');
	}
	
	/**
	 * \brief	List available commands
	 */
	function commands() {}
	
	/**
	 * \brief	Show the config
	 */
	function config() {
		phake('phaker welcome-short');
	}
	
	function howto() {}
	
	/*private function include_doc($doc_file) {
		$f = PHAKER_DOC_DIR.$doc_file;
		$f = file_get_contents($f);
		$f = str_replace("\t", '  ', $f);
		return PHP_EOL.PHP_EOL.$f;
	}*/
}

?>