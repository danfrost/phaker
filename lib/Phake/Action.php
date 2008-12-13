<?php


abstract class Phake_Action {
	
	private	$args	= array();
	
	protected	$context	= null;
	
	function __construct(& $context) {
		$this->context = & $context;
	}
	
	/**
	 * \brief	setArguments
	 */
	final function setArgs($args) {
		$vars = Phake_ActionHelper::get_class_vars($this);
		
		$i = 0;
		foreach($vars as $var_name=>$var_type) {
			$var_value = & $args[$i];
			if(class_exists($var_type)) {
				if(is_a($var_value, $var_type)) {
					$this->$var_name =  & $args[$i];
				} else {
					$this->$var_name = new $var_type($var_value);
				}
			} else {
				$this->$var_name =  & $args[$i];
			}
			$i++;
		}
		return;
		
		print_r($this);
		die();
		print_r(get_class_vars(get_class($this)));
		
		$this->source = $this->context->dir.$args[0];
		$this->target = $this->context->dir.$args[1];
	}
	
	
	function getName() {
		return str_replace('CliAction_', '', get_class($this));
	}
	
	function runAction() {
		echo "\nRunning action: ".$this->getName();
		if(!method_exists($this, 'undoAction')) {
			echo "\n!!! This is not undoable";
		}
		$this->doAction();
		echo "\nDone action: ".$this->getName();
	}
	
	abstract function doAction();
	
	function undo() {
		if(!method_exists($this, 'undoAction')) {
			throw new Exception("Cannot undo action: ". $this->getName);
		}
		
		echo "\nUndoing action: ".$this->getName();
		$this->undoAction();
		echo "\nUndone action: ".$this->getName();
	}
}



/**
 * todo:
* basename — Returns filename component of path
* chgrp — Changes file group
* chmod — Changes file mode
* chown — Changes file owner
* clearstatcache — Clears file status cache
* copy — Copies file
* delete — See unlink or unset
* dirname — Returns directory name component of path
* disk_free_space — Returns available space in directory
* disk_total_space — Returns the total size of a directory
* diskfreespace — Alias of disk_free_space
* fclose — Closes an open file pointer
* feof — Tests for end-of-file on a file pointer
* fflush — Flushes the output to a file
* fgetc — Gets character from file pointer
* fgetcsv — Gets line from file pointer and parse for CSV fields
* fgets — Gets line from file pointer
* fgetss — Gets line from file pointer and strip HTML tags
* file_exists — Checks whether a file or directory exists
* file_get_contents — Reads entire file into a string
* file_put_contents — Write a string to a file
* file — Reads entire file into an array
* fileatime — Gets last access time of file
* filectime — Gets inode change time of file
* filegroup — Gets file group
* fileinode — Gets file inode
* filemtime — Gets file modification time
* fileowner — Gets file owner
* fileperms — Gets file permissions
* filesize — Gets file size
* filetype — Gets file type
* flock — Portable advisory file locking
* fnmatch — Match filename against a pattern
* fopen — Opens file or URL
* fpassthru — Output all remaining data on a file pointer
* fputcsv — Format line as CSV and write to file pointer
* fputs — Alias of fwrite
* fread — Binary-safe file read
* fscanf — Parses input from a file according to a format
* fseek — Seeks on a file pointer
* fstat — Gets information about a file using an open file pointer
* ftell — Returns the current position of the file read/write pointer
* ftruncate — Truncates a file to a given length
* fwrite — Binary-safe file write
* glob — Find pathnames matching a pattern
* is_dir — Tells whether the filename is a directory
* is_executable — Tells whether the filename is executable
* is_file — Tells whether the filename is a regular file
* is_link — Tells whether the filename is a symbolic link
* is_readable — Tells whether the filename is readable
* is_uploaded_file — Tells whether the file was uploaded via HTTP POST
* is_writable — Tells whether the filename is writable
* is_writeable — Alias of is_writable
* lchgrp — Changes group ownership of symlink
* lchown — Changes user ownership of symlink
* link — Create a hard link
* linkinfo — Gets information about a link
* lstat — Gives information about a file or symbolic link
* mkdir — Makes directory
* move_uploaded_file — Moves an uploaded file to a new location
* parse_ini_file — Parse a configuration file
* pathinfo — Returns information about a file path
* pclose — Closes process file pointer
* popen — Opens process file pointer
* readfile — Outputs a file
* readlink — Returns the target of a symbolic link
* realpath — Returns canonicalized absolute pathname
* rename — Renames a file or directory
* rewind — Rewind the position of a file pointer
* rmdir — Removes directory
* set_file_buffer — Alias of stream_set_write_buffer
* stat — Gives information about a file
* symlink — Creates a symbolic link
* tempnam — Create file with unique file name
* tmpfile — Creates a temporary file
* touch — Sets access and modification time of file
* umask — Changes the current umask
* unlink — Deletes a file
 */


?>