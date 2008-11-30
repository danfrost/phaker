<?php

//die("\n\tNEXT: move things out of J5 into J5_Generate\n");
class J5 {
	
	private	$source;
	private $target;
	static  public $noun = 'default';
	
	private $files = array();
	
	const	dir			= 'j5/';
	const	cache_dir	= 'j5/cache/';
	const	config_dir	= 'j5/config/';
	
	function __construct($source, $target, $name) {  // < Name to be renamed "Noun" ??
		if(!($source && $target && $name)) {
			throw new Exception("Need source, target and name");
		}
		
		echo "\nStarting with source=$source, target=$target, name=$name";
		
		$this->source 	= J5_TEMPLATE_DIR.'/'.$source.'/';
		$this->target 	= J5_PWD.'/'.$target.'/';
		//$this->noun 	= $name;
		self::$noun		= $name; // static -- but this suggests that source and target should be static and that concurrent generations cannot happen (???)
		
		@mkdir($this->target, 0777, true);
		
		$this->generate_code();
	}
	
	function generate_code() {
		
		if ($handle = opendir($this->source)) {
		    echo "Directory handle: $handle\n";
		    echo "Files:\n";
			
		    /* This is the correct way to loop over the directory. */
		    while (false !== ($file = readdir($handle))) {
		        echo "$file\n";
				if(!in_array($file, array('.', '..'))) {
					//$this->doFile($file);
					if(is_file($this->source.$file)) {
						$this->files[] = new J5_File($file, $this->source, $this->target);
					} else 
					if(is_dir($this->source.$file)) {
						$this->files[] = new J5_Directory($file, $this->source, $this->target);
					}
				}
		    }
			
		    /* This is the WRONG way to loop over the directory. */
		    while ($file = readdir($handle)) {
		        //echo "$file\n";
				if(!in_array($file, array('.', '..'))) {
					//$this->doFile($file);
				}
		    }
			
		    closedir($handle);
		}
		
		J5_Input::write_config();
	}
	
	static function init_j5_dir() {
		@mkdir($_SERVER['PWD'].'/'.J5::dir, 0777, true);
		@mkdir($_SERVER['PWD'].'/'.J5::cache_dir, 0777, true);
		@mkdir($_SERVER['PWD'].'/'.J5::config_dir, 0777, true);
	}
	
	static function set_cache($name, $data) {
		$name = str_replace(' ', '', $name);
		$name = J5::$noun.'.'.$name;
		file_put_contents(J5::cache_dir.$name, $data);
	}
	
	static function get_cache($name, $data) {
		$name = str_replace(' ', '', $name);
		$name = J5::$noun.'.'.$name;
		file_put_contents(J5::cache_dir.$name, $data);
	}
	
	static function get_cache_ini($name) {
		$name = str_replace(' ', '', $name);
		$file = J5_PWD.'/'.J5::cache_dir.J5::$noun.'.'.$name;
		if(!file_exists($file)) {
			touch($file);
		}
		return parse_ini_file($file);
	}
	
	static function set_cache_ini($name, $data) {
		$name = str_replace(' ', '', $name);
		$file = J5_PWD.'/'.J5::cache_dir.J5::$noun.'.'.$name;
		
		if(is_array($data)) {
			$d = array();
			foreach ($data as $k=>$v) {
				$d[] = "$k = $v";
			}
			$data = implode("\n", $d);
		}
		file_put_contents($file, $data);
	}
}

?>