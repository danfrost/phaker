<?php

/**
 * \brief	Alias for 'new Phake_File($file)'
 * @see	Phake_File
 * Usage:

Either single file which does or doesn't exist.

	f('blar.txt');
	
Or, a expression. E.g.

	f('sql/*.sql');
...which will return a Phake_File_Collection object of the files. This is useful for doing batch tasks, E.g.:

	f('sql/*.sql')->backup();


 */
function f($file) {
	if(is_a($file, 'Phake_File')) {
		return $file;
	}
	return new Phake_File($file);
}

function fs($file_expression) {
	echo "\nFind files like: $file_expression\n";
	return new Phake_File_Collection($file_expression);
	die();
}

class Phake_File_Collection {
	
	private	$file_expression	= '';
	
	private	$files	= array();
	
	function __construct($file_expression) {
		$this->file_expression = $file_expression;
		$this->findFiles();
	}
	
	function & add($f) {
		$this->files[$f] = f($f);
		return $this;
	}
	
	/**
	 * \brief	Find the files. This won't create the file objects - just the file paths
	 */
	function findFiles() {
		$exec = "ls ".Phake_File::$context->dir.$this->file_expression;
		//echo $exec;
		exec($exec, $arr);
		
		foreach($arr as $f) {
			$fname = str_replace(Phake_File::$context->dir, '', $f);
			$this->files[$fname] = f($fname);
		}
	}
	
	private	$counter	= 0;
	
	function & each() {
		$keys = array_keys($this->files);
		
		if(!isset($keys[$this->counter])) {
			return false;
		}
		
		$filename = $keys[$this->counter];
		
		$f = & $this->files[$filename];
		$this->counter++;
		return $f;
	}
	
	function reset() {
		$this->counter = 0;
	}
	
	/**
	 * \brief	All calls to a file object are passed to Phake_Context and then to a Phake_Action. 
	 * For example, if you call '$fileObject->touch()', this is passed down to the action 'Phake_Action_Touch'.
	 */
	function & __call($method, $args=array()) {
		echo "\nCalling method: $method\n";
		
		foreach($this->files as $k=>$f) {
			$f = & $this->files[$k];
			if(is_array($args)) {
				$args = "'".implode("', '", $args)."'";
			} else
			if($args) {
				//$args = "'".$args."'";
			} else {
				$args = '';
			}
			$php = '$f->'.$method.'('.$args.');';
			//echo "\n>>$php";
			eval($php);
		}
		return $this;
	}
	
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