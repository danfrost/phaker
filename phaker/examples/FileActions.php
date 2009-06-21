<?php

class Phake_Script_FileActions extends Phake_Script {
	
	/*
	
	touch
	move
	remove
	mkdir
	copy
	
	chmod
	chown
	chgrp
	symlink
	
	 */
	function index() {
		
		$this->touch();
		$this->move();
		$this->remove();
		$this->mkdir();
		$this->copy();
		$this->chmod();
		$this->set_content();
		
		return;
	}
	
	function touch() {
		$f = f('test.txt')->touch();
	}
	
	function move() {
		$m = f('test.txt')->move('target.txt');
	}
	
	function remove() {
		f('target.txt')->remove();
	}
	
	function mkdir() {
		//$p = $this->getContext();
		//$m = $p->mkdir('testdir/subdir/');
		//Phake_Pwd::get()->mkdir('testdir/subdir/');
	}
	
	function copy() {
		//$p = $this->getContext();
		//$p->touch('copy_test.txt');
		//$m = $p->copy('copy_test.txt', 'copy_of_test.txt');
		//f('copy_test.txt')->copy('copy_of_test.txt');
	}
	
	function chmod() {
	    /*
		$p = $this->getContext();
		$p->touch('chmod_test.txt');
		$p->chmod('chmod_test.txt', 0700);
		*/
	}
	
	function set_content() {
	    /*
		$p = $this->getContext();
		// test: set content
		$p->setContent('set_content_test.txt', 'This is some example content');
		*/
	}
	
}

?>