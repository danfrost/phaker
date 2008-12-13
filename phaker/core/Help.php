<?php

class PhakeScript_Help extends Phake_Script {
	
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
	
	function commands() {
		//phake('phaker welcome-short');
		$cmds = Phake::getScripts();
		echo PHP_EOL.PHP_EOL.'  Available commands - phake help "command" for more info'.PHP_EOL;;
		foreach ($cmds as $c) {
			echo PHP_EOL."    $c";
			$actions = Phake::getActions($c);
			foreach ($actions as $a) {
				if($a!='index') {
					echo PHP_EOL."    $c\t$a";
				}
			}
			echo PHP_EOL;
		}
	}
	
	function config() {
		echo "
  Current config:

    PHAKE_SCRIPTS_DIR = ".PHAKE_SCRIPTS_DIR."
		
		";
	}
	
	function custom_script() {
		echo $this->include_doc('DOC_custom_script.md');
	}
	
	private function include_doc($doc_file) {
		$f = PHAKER_DOC_DIR.$doc_file;
		$f = file_get_contents($f);
		$f = str_replace("\t", '  ', $f);
		return PHP_EOL.PHP_EOL.$f;
	}
}

?>