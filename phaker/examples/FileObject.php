<?php

/**
 * fileobject demonstrates how phaker deals with files via the "Phake_File" class.
 */
class Phake_Script_FileObject extends Phake_Script {
	
	function index() {
		phake('commands fileobject');
	}
	
	/**
	 * create and remove a file
	 */
	function touch()
	{
	    $f = f('there.txt')->touch();
	}
	
	/**
	 * create and move a file
	 */
	function move()
	{
	    $f = f('blar.txt');
		$f->touch();
		$f->move('moved-here.txt');
	}
	
	/**
	 * using chaining
	 */
	function chaining()
	{
	    f('blar2.txt')->touch()->backup();
	}
	
	/**
	 * Backup all files called "*.txt" in the current dir
	 */
	function backup()
	{
	    $fs = fs("*.txt")->backup();
	    //print_r($fs);
	}
	
	/**
	 * Creates a few sql files
	 */
	function createdbfiles() {
		$fs = fs('sql/*.sql');
		// Add some new files
		$fs->add('sql/pages.sql')
			->add('sql/users.sql')
			->add('sql/departments.sql')
			->add('sql/log.sql');
		
		$fs->touch();
	}
	
	/**
	 * Set the content in lots of files at once
	 */
	function content() {
		$f = fs('sql/*.sql')->
			setContent('# This is a skeleton SQL file... nothing to see here');
	}
	
	/**
	 * Remove files 
	 */
	function dropdbfiles() {
		$f = fs('sql/*.sql')->
			backup()->
			remove();
	}
	
	/**
	 * Example of looping through files
	 */
	function loop() {
		$i = 0;
		$fs = fs('sql/*.sql');
		while($f = $fs->each()) {
			$f->setContent('# this file is '.$f->getFilename());
		}
	}
	
	/**
	 * (Incomplete) parse skel files 
	 */
	function skel() {
		$i = 0;
		$fs = fs('sql/*.sql');
		while($f = $fs->each()) {
			$f->skelparser();
		}
		
	}
}

?>