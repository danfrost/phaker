<?php

/**
 * Collection to Phake_File objects
 * 
 * @todo 	!!! re-implement findFiles - don't use exec / find. Likely to cause problem on different platforms.
 * 
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_File_Collection {
	
	/**
	 * Expression for finding files. 
	 */
	private	$file_expression	= '';
	
	/**
	 * List of files
	 */
	private	$files	= array();
	
	/**
	 * Sets the expression and runs Phake_File->findFiles()
	 * @param 	String	Expression for finding files. At present, this is just passed to cli 'find'.
	 */
	function __construct($file_expression) {
		$this->file_expression = $file_expression;
		$this->findFiles();
	}
	
	/**
	 * Add file to the collection. 
	 * @param	String|Phake_File	Filename or a file object (instance of Phake_file)
	 */
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
	
	/**
	 * Iterator for each()
	 */
	private	$counter	= 0;
	
	/**
	 * Iterator
	 */
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
	
	/**
	 * Reset the iteration
	 */
	function reset() {
		$this->counter = 0;
	}
	
	/**
	 * All calls to a file object are passed to Phake_Context and then to a Phake_Action. 
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

?>