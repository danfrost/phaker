<?php

/**
 * 
 */
class Phake_Script_FileObject extends Phake_Script {
	
	function index() {
		// Where should this happen?
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
	
	function createdbfiles() {
		Phake_File::$context = new Phake_Context(dirname(__FILE__).'/tmp/');
		$f = fs('sql/*.sql');
		// Add some new files
		$f->add('sql/pages.sql')
			->add('sql/users.sql')
			->add('sql/departments.sql')
			->add('sql/log.sql');
		
		$f->touch();
	}
	
	/**
	 * \brief	Example of setting the content in lots of files at once
	 */
	function setfiles() {
		Phake_File::$context = new Phake_Context(dirname(__FILE__).'/tmp/');
		$f = fs('sql/*.sql')->
			setContent('# This is a skeleton SQL file... nothing to see here');
		
	}
	
	function dropdbfiles() {
		Phake_File::$context = new Phake_Context(dirname(__FILE__).'/tmp/');
		$f = fs('sql/*.sql')->
			backup()->
			remove();
	}
	
	/**
	 * \brief 	Example of looping through files
	 */
	function loop() {
		Phake_File::$context = new Phake_Context(dirname(__FILE__).'/tmp/');
		$i = 0;
		$fs = fs('sql/*.sql');
		while($f = $fs->each()) {
			$f->setContent('# this file is '.$f->getFilename());
		}
	}
	
	function skel() {
		Phake_File::$context = new Phake_Context(dirname(__FILE__).'/tmp/');
		$i = 0;
		$fs = fs('sql/*.sql');
		while($f = $fs->each()) {
			$f->skelparser();
		}
		
	}
}

?>