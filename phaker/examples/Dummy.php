<?php

/**
 * Dummy is a test class that provides test testing and dependencies. 
 * 
 * The default action does very little:
 * <code>
 * $ phake dummy
 * </code>
 * 
 * To see something even vaguely interesting, run action1:
 * 
 * <code>
 * $ phake dummy action1
 * </code>
 * 
 * Action1 calls action1_1, action2 and action3. Eventually, action4 is called and
 * an error is thrown. This demonstrates how phaker deals with errors.
 * 
 * The error in action4 is thrown by doing:
 * <code>
 * $this->error('Something went wrong..');
 * </code> 
 */
class Phake_Script_Dummy extends Phake_Script {
	
	/**
	 * \brief	The default action
	 */
	function index() {
		echo "Hello there ... you can do things here... ";
	}
    
    function params($p1=0,$p2="",$p3=true) {
        echo "
Hi - this is a demo of simple params.
    p1 = $p1
    p2 = $p2
    p3 = $p3
        
        ";
    }
    
	/**
	 * test action 1
	 */
	function action1() {
		echo "This is action 1";
		phake('dummy', 'action1_1');
		phake('dummy', 'action2');
		phake('dummy', 'action3');
	}
	
	/**
	 * testing 1,1
	 */
	function action1_1() {
		echo "Done this...";
	}
	
	/**
	 * testing 1,2
	 */
	function action2() {
		phake('dummy', 'action2_1');
	}
	
	/**
	 * testing 2.1
	 */
	function action2_1() {
		
	}

	/**
	 * testing 3
	 */
	function action3() {
		phake('dummy', 'action4');
	}
	
	/**
	 * testing 4 - this throws an error
	 */
	function action4() {
		echo "This is action 1";
		$this->error("Could not do something");
	}
}

?>