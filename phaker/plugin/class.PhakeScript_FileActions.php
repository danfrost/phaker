<?php

class PhakeScript_FileActions extends PhakeScript {
	
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
		echo "This is custom.";
		
		$this->touch();
		$this->move();
		$this->remove();
		$this->mkdir();
		$this->copy();
		$this->chmod();
		
		$this->set_content();
		
		
		print_r($this->getContext());
		
		return;
	}
	
	function & getContext() {
		static $p;
		if(!$p) $p = new CliContext($this->args[0]);
		return $p;
	}
	
	function touch() {
		$p = $this->getContext();
		$t = $p->touch('test.txt');
	}
	
	function move() {
		$p = $this->getContext();
		$m = $p->move('test.txt', 'target.txt');
		//$m->undo();
	}
	
	function remove() {
		$p = $this->getContext();
		$m = $p->remove('target.txt');
	}
	
	function mkdir() {
		$p = $this->getContext();
		$m = $p->mkdir('testdir/subdir/');
	}
	
	function copy() {
		$p = $this->getContext();
		$p->touch('copy_test.txt');
		$m = $p->copy('copy_test.txt', 'copy_of_test.txt');
	}
	
	function chmod() {
		$p = $this->getContext();
		$p->touch('chmod_test.txt');
		$p->chmod('chmod_test.txt', 0700);
	}
	
	function set_content() {
		$p = $this->getContext();
		// test: set content
		$p->set_content('set_content_test.txt', 'This is some example content');
	}
	
}

?>