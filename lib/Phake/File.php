<?php

/**
 * \brief	File wrapper object. This implements no _verbs_ as such - only access functions to nouns and status information. 
 * 	E.g. is file, getFilename()... All the verbs are implemented by Phake_Action classes.
 */
class Phake_File {
	
	/**
	 * Instance of Phake_Context. 
	 * @see Phake_Context
	 */
	static $context = null;
	
	/**
	 * Filename, not including the path.
	 */
	private	$file;
	
	/**
	 * Constructor - just sets filename in object
	 */
	function __construct($file) {
		$this->file = $file;
	}
	
	/**
	 * All calls to a file object are passed to Phake_Context and then to a Phake_Action. 
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
	 * Returns full path to the file
	 */
	function getFullPath() {
		return self::$context->dir.$this->getFilename();
	}
	
	/**
	 * Returns filename only
	 */
	function getFilename() {
		return $this->file;
	}
	
	/**
	 * Returns true if the file exists
	 */
	function is_file() {
		return (bool) file_exists($this->getFullPath());
	}
	
	/**
	 * Returns true if the file doesn't exist
	 */
	function is_not_file() {
		return ! $this->is_file();
	}
	
	/**
	 * Alias of Phake_File::getFullPath()
	 */
	function __toString() {
		return $this->getFullPath();
	}
}

/**
 * Returns file (Phake_File) object
 * 
 * @see	Phake_File
 * 
 * Usage:
 * Pass a filename of a file which does or doesn't exist.
 * <code>
 * $f = f('blar.txt');
 * $f->touch();
 * </code>
 * 
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
function f($file) {
	if(is_a($file, 'Phake_File')) {
		return $file;
	}
	return new Phake_File($file);
}

/**
 * Returns file collection (Phake_File_Collection) object
 * 
 * Usage: Pass an expression:
 * <code>
 * $fs = fs('sql/*.sql');
 * $fs->touch(); // This has touched all of the files.
 * </code>
 * 
 * This is useful for doing batch tasks, E.g.:
 * <code>
 * f('sql/*.sql')->backup();
 * </code>
 */
function fs($file_expression) {
	echo "\nFind files like: $file_expression\n";
	return new Phake_File_Collection($file_expression);
}

?>