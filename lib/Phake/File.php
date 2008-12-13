<?php

function f($file) {
	if(is_a($file, 'Phake_File')) {
		return $file;
	}
	return new Phake_File($file);
}

/**
 * \brief	File wrapper object. This implements no _verbs_ as such - onto access functions to nouns and status information. 
 * 	E.g. is file, getFilename()...
 */
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
	
	function is_file() {
		return (bool) file_exists($this->getFullPath());
	}
	
	function is_not_file() {
		return ! $this->is_file();
	}
	
	function __toString() {
		return $this->getFullPath();
	}
}

?>