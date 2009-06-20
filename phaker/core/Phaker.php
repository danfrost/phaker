<?php

/**
 * \brief	Set of arbitrary welcome, more info etc commands. No real functionality here.
 */
class Phake_Script_Phaker extends Phake_Script {
	
	/**
	 * \brief	Short intro
	 */
	function welcome() {}
	
	function welcomeshort() {}
	
	function about() {
		phake('phaker welcome');
		echo "
	Phaker is like make migrated to PHP but with some other bits as well.
			
	For more info, see code.google.com/p/phaker.
			
	Git hosted at github.com
			
		";
	}
}

?>