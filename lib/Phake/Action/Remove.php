<?php

class Phake_Action_Remove extends Phake_Action {
	var $file = Phake_File;
	
	function doAction() {
		echo "\nRemoving file: $this->file";
		unlink($this->file->getFullPath());
	}
}


?>