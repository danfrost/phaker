<?php

/**
 * Parse file for placeholders [INCOMPLETE]
 * 
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_Action_Skelparser extends Phake_Action {
	var	$file = Phake_File;
	
	function doAction() {
	    throw new Phake_Exception_Todo('THIS IS NOT YET STABLE - PLEASE DO NOT USE ');
		
		echo "\nParsing: ".$this->file->getFullPath();
		
		$f = file_get_contents($this->file->getFullPath());
		
		preg_match_all("_<[A-Z]*>_", $f, $arr);
		
		foreach($arr[0] as $placeholder) {
			echo PHP_EOL."=== Now I just need to get '$placeholder' from the user or the cache.. ???";
		}
	}
}


?>