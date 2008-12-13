<?php

/**
 * 
 */
class Phake_Script_FileObject extends Phake_Script {
	
	function index() {
		Phake_File::$context = new Phake_Context(dirname(__FILE__).'/tmp/');
		
		// Example 0 - create and remove a file
		$f = f('there.txt')->touch();
		$f->remove();
		
		// Example 1 - create and move a file:
		$f = f('blar.txt');
		$f->touch();
		$f->move('there.txt');
		
		// Example 2 - using chaining
		$f = f('blar2.txt')->touch()->backup();
		
	}
}

?>