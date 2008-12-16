<?php

/**
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_Script {
	
	static public $current;
	
	protected	$action;
	protected	$args = array();
	
	function __construct($action=null, $args=array()) {
		self::$current = $this;
		
		if(!method_exists($this, $action)) {
			//echo "\n> Action doesn't exist: $action\n";
			$action = 'index';
		}
		
		$this->action = $action;
		
		$this->args = $args;
		
		//$this->dispatchAction();
	}
	
	function dispatchAction() {
		$enable_caching = false;
		static $depth = 0;
		
		if($depth==0 && $enable_caching) {
			ob_start();
		}
		
		$depth++;
		
		$action = $this->action;
		$this->$action();
		$this->include_action_view();
		
		$depth--;
		
		if($depth==0 && $enable_caching) {
			$ret = ob_get_clean();
		
			$x = explode(PHP_EOL, $ret);
			$ret = implode(PHP_EOL.'> ', $x);
		
			echo PHP_EOL.$ret;
		}
	}
	
	function __toString() {
		return $this->get_cmd().':'.$this->action;
	}
	
	/**
	 * \brief	
	 */
	protected function include_action_view() {
		$dir = Autoloader::get_class_dir(get_class($this));
		$view_file = $dir.$this->get_cmd().'/'.$this->action.'.php';
		
		if(!file_exists($view_file)) {
			return;
		}
		
		require $view_file;
	}
	
	/**
	 * \brief	Return the cmd that $this provides 
	 */
	protected function get_cmd() {
		return str_replace('Phake_Script_', '', get_class($this));
	}
	
	
	
	final protected function error($msg) {
		throw new Phake_Script_ScriptException($msg);
	}
}


?>