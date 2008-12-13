<?php

/**
 * \brief	Set of arbitrary welcome, more info etc commands. No real functionality here.
 */
class PhakeScript_Phaker extends Phake_Script {
	function welcome() {
		echo 
"
  # 
  # Phaker - scripts
  # (c) 2008 - Dan Frost
  #
";
	}
	
	function welcome_short() {
		echo 
"
  # Phaker 
";
	}
	
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