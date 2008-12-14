<?php

/**
 * \brief	Alias for 'new Phake_File($file)'
 * @see	Phake_File
 */
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
	
	private	$file; /**< File (not including path) */
	
	/**
	 * \brief	Constructor
	 */
	function __construct($file) {
		$this->file = $file;
	}
	
	/**
	 * \brief	All calls to a file object are passed to Phake_Context and then to a Phake_Action. 
	 * For example, if you call '$fileObject->touch()', this is passed down to the action 'Phake_Action_Touch'.
	 */
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
	
	/**
	 * \brief	Returns full path to the file
	 */
	function getFullPath() {
		return self::$context->dir.$this->getFilename();
	}
	
	/**
	 * \brief	Returns filename only
	 */
	function getFilename() {
		return $this->file;
	}
	
	/**
	 * \brief	Returns true if the file exists
	 */
	function is_file() {
		return (bool) file_exists($this->getFullPath());
	}
	
	/**
	 * \brief	Returns true if the file doesn't exist
	 */
	function is_not_file() {
		return ! $this->is_file();
	}
	
	/**
	 * \brief	Alias of Phake_File::getFullPath()
	 */
	function __toString() {
		return $this->getFullPath();
	}
}

?>