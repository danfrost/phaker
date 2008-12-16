<?php 

/**
 * \brief 	This will stop everything.
 */
class Phake_Script_EndAllException extends Exception {
	
	function __construct($msg) {
		//die(PHP_EOL.PHP_EOL.__METHOD__.PHP_EOL);
		echo PHP_EOL.PHP_EOL."!! Fatal error in phake: $msg !!".PHP_EOL;
		die();
	}
	
}

?>