<?php

class PhakeScript_Custom extends PhakeScript {
	function index() {
		echo "This is custom.";
		
		//phake('dummy', 'index', 'args...', 'args...');
		phake('help index');
		
		phake('help', 'index');
	}
}

?>