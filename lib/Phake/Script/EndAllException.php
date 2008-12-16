<?php 

/**
 * \brief 	This will stop everything.
 * 
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_Script_EndAllException extends Exception {
	
	function __construct($msg) {
		//die(PHP_EOL.PHP_EOL.__METHOD__.PHP_EOL);
		echo PHP_EOL.PHP_EOL."!! Fatal error in phake: $msg !!".PHP_EOL;
		die();
	}
	
}

?>