<?php

class Phake_Script_Dummy extends Phake_Script {
	
	function index() {
		echo "Hello there ... you can do things here... ";
	}
	
	function action1() {
		echo "This is action 1";
		phake('dummy', 'action1_1');
		phake('dummy', 'action2');
		phake('dummy', 'action3');
	}
	
	function action1_1() {
		echo "Done this...";
	}
	
	function action2() {
		phake('dummy', 'action2_1');
	}
	
	function action2_1() {
		
	}
	
	function action3() {
		phake('dummy', 'action4');
	}
	
	function action4() {
		echo "This is action 1";
		$this->error("Could not do something");
	}
}

?>