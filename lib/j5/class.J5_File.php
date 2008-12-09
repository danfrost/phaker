<?php

/**
 * \brief 	Used to track all files / directories created
 */
class J5_GenerationLog {
	
	static 	$log	= array();
	
	function add(& $item) {
		self::$log[] = & $item;
	}
	
	/**
	 * \brief	Writes what was generated and from what files. Can be used in future for tracking generated files.
	 */
	function write_to_cache() {
		$cache = array();
		foreach(self::$log as $i) {
			$cache[] = str_replace(J5_TEMPLATE_DIR, '', $i->getSource()).' = '.$i->getTarget();
		}
		$cache = implode("\n", $cache);
		J5::set_cache('GenerateLog', $cache);
	}
}

class J5_Directory {
	
	public	$directory_name;
	public $target_directory_name;
	
	public $source;
	public $target;	
	
	function __construct($directory_name, $source, $target) {
		J5_GenerationLog::add($this);
		echo "\n\t[J5_Directory: $directory_name, $source, $target]";
		$this->directory_name = $directory_name;
		$this->source = $source;
		$this->target = $target;
		
		$this->directory_name();
		$this->write();
		$this->directory_contents();
	}
	
	function getSource() {
		return $this->source.$this->filename;
	}
	
	function getTarget() {
		return $this->target.$this->filename;
	}
	
	function directory_name() {
		
		echo "\n> File: $this->directory_name\n";
		
		// find marker in filename
		preg_match_all("_([A-Z]{2,100})_", $this->directory_name, $arr);
		
		$this->target_directory_name = $this->directory_name;
		foreach($arr[1] as $upper_var) {
			$var = strtolower($upper_var);
			$got_value = false;
			while($got_value==false) {
				try {
					$value = J5_Input::get($var);
					$got_value=true;
				} catch(J5_Input_HelpException $e) {
					echo "\nHELP: Trying to get value for part of the filename $this->directory_name";
				}
			}
			$this->target_directory_name = str_replace($upper_var, $value, $this->target_directory_name);
		}
	}
	
	function directory_contents() {
		if ($handle = opendir($this->source.$this->directory_name)) {
		    echo "Directory handle: $this->source$this->directory_name\n";
		    echo "Files:\n";
			
		    /* This is the correct way to loop over the directory. */
		    while (false !== ($file = readdir($handle))) {
		        echo "\n\t[$file - ".$this->source.$this->directory_name.'/'.$file."]\n";
				
				if(!in_array($file, array('.', '..'))) {
					//$this->doFile($file);
					if(is_file($this->source.$this->directory_name.'/'.$file)) {
						$this->files[] = new J5_File($file, $this->source.$this->directory_name.'/', $this->target.$this->target_directory_name.'/');
					} else 
					if(is_dir($this->source.$this->directory_name.'/'.$file)) {
						$this->files[] = new J5_Directory($file, $this->source.$this->directory_name.'/', $this->target.$this->target_directory_name.'/');
					} else {
						echo "\n\t[Cannot process: $this->source$this->directory_name / $file]";
					}
				}
		    }
			
		    closedir($handle);
		}
	}
	
	
	function write() {
		echo "\n\t[Creating directory : $this->target$this->target_directory_name (from template dir $this->directory_name)]\n";
		mkdir($this->target.$this->target_directory_name, 0777, true);
	}
}

class J5_File {
	
	private	$filename;
	private $target_filename;
	
	private	$target_content;
	
	private $source;
	private $target;
	
	function __construct($filename, $source, $target) {
		J5_GenerationLog::add($this);
		echo "\n\t[J5_File: $filename, $source, $target]";
		$this->filename = $filename;
		$this->source = $source;
		$this->target = $target;
		//print_r($this);
		//return;
		$this->filename();
		$this->contents();
		$this->write();
	}
	
	function getSource() {
		return $this->source.$this->filename;
	}
	
	function getTarget() {
		return $this->target.$this->filename;
	}
	
	function filename() {
		
		echo "\n> File: $this->filename\n";
		
		// find marker in filename
		preg_match_all("_([A-Z]{2,100})_", $this->filename, $arr);
		
		$this->target_filename = $this->filename;
		foreach($arr[1] as $upper_var) {
			$var = strtolower($upper_var);
			$got_value = false;
			while($got_value==false) {
				try {
					$value = J5_Input::get($var)->asVariable();
					$got_value=true;
				} catch(J5_Input_HelpException $e) {
					echo "\nHELP: Trying to get value for part of the filename $this->filename";
				}
			}
			$this->target_filename = str_replace($upper_var, $value, $this->target_filename);
		}
	}
	
	function contents() {
		if(!$this->target_filename) {
			throw new Exception('$this->target_filename is not set');
		}
		
		$parser = new J5_File_ContentParser($this->source.$this->filename);
		$this->target_content = $parser->get();
	}
	
	function write() {
		//echo "\nWriting to $this->target"."$this->target_filename";
		echo "\n\t[Writing to file : $this->target_filename (from template $this->filename)]\n";
		file_put_contents($this->target.$this->target_filename, $this->target_content);
	}
}

class J5_File_ContentParser {
	
	private $___filename;
	private $___output;
	
	function __construct($___filename) {
		$this->___filename = $___filename;
		
	}
	
	/**
	 * \brief	Used for string variables
	 */
	function __get($var) {
		$this->ob_pause();
		//$ret = J5_Input::get($var);
		$got_value = false;
		while($got_value==false) {
			try {
				$ret = J5_Input::get($var);
				$got_value=true;
			} catch(J5_Input_HelpException $e) {
				$bt = debug_backtrace();
				$bt = $bt[0];
				
				$file = $bt['file'];
				$line = $bt['line'];
				
				$php = file_get_contents($file);
				$x = explode("\n", $php);
				
				$varloc = '$this->'.$var;
				$extract_line = $x[($line-1)];
				$extract_line = str_replace("\t", '    ', $extract_line);
				$strpos = strpos($extract_line, $varloc);
				$prefix = "  EXTRACT:  ";
				
				echo "\n".$prefix.'Here\'s some help:';
				echo "\n".$prefix.$x[($line-2)];
				echo "\n".$prefix.$extract_line;
				echo "\n".str_repeat(' ', (strlen($prefix))).str_repeat(' ',$strpos).str_repeat('^',strlen($varloc))."\n";
				
			}
		}
		
		$this->ob_start();
		return $ret;
	}
	
	function ob_start() {
		ob_start();
	}
	
	function ob_pause() {
		$this->___output .= ob_get_clean();
	}
	
	/**
	 * \brief	Example of 
	 */
	function __call($func, $opts) {
		$this->ob_pause();
		
		$str = "$func (".implode(', ', $opts).")";
		//$ret = J5_Input::get($func, $str);
		$got_value = false;
		while($got_value==false) {
			try {
				$ret = J5_Input::get($func, $str);
				$got_value=true;
			} catch(J5_Input_HelpException $e) {
				//echo "\nHELP: Trying to get value ==== $func";
				$bt = debug_backtrace();
				//print_r($bt);die();
				$bt = $bt[1];
				
				$file = $bt['file'];
				$line = $bt['line'];
				
				$php = file_get_contents($file);
				$x = explode("\n", $php);
				
				$varloc = '$this->'.$func;
				//$strpos = strpos($x[($line-1)], $varloc);
				$extract_line = $x[($line-1)];
				$extract_line = str_replace("\t", '    ', $extract_line);
				$strpos = strpos($extract_line, $varloc);
				$prefix = "  EXTRACT:  ";
				
				echo "\n".$prefix.'Here\'s some help:';
				echo "\n".$prefix.$x[($line-2)];
				echo "\n".$prefix.$extract_line;
				echo "\n".str_repeat(' ', (strlen($prefix))).str_repeat(' ',($strpos+3)).str_repeat('^',strlen($varloc))."\n";
			}
		}
		
		$this->ob_start();
		return $ret;
	}
	
	function get() {
		//echo("\n!!$this->___filename\n");
		
		$this->___output = '';
		
		$this->ob_start();
		require $this->___filename;
		
		//$this->___output .= ob_get_clean();
		$this->ob_pause();
		
		$this->___output = str_replace('&lt;?php', '<?php', $this->___output);
		$this->___output = str_replace('?&gt;', '?>', $this->___output);
		
		return $this->___output;
	}
}


?>