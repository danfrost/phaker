<?php

function f($filename) {
	echo "\nFile: $filename\n";
	
	return new Phake_File($filename);
	
}

class Phake_File {
	
	static $context = null;
	
	private	$file;
	
	function __construct($file) {
		$this->file = $file;
	}
	
	function & __call($method, $args=array()) {
		if($args) {
			$args = "'".implode("', '", $args)."'";
			$php = 'self::$context->$method($this->file, '.$args.');';
			eval($php);
		} else {
			self::$context->$method($this->file);
		}
		
		return $this;
	}
	
	function getFullPath() {
		return self::$context->dir.$this->getFilename();
	}
	
	function getFilename() {
		return $this->file;
	}
}

?>