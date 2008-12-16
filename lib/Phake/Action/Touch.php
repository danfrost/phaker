<?php

class Phake_Action_Touch extends Phake_Action {
	var	$file = Phake_File;
	
	function doAction() {
		echo "\nTouching: ".$this->file->getFullPath();
		
		touch($this->file->getFullPath());
	}
}


?>