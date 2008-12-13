<?php


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
		$action = $this->action;
		$this->$action();
		$this->include_action_view();
		
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
		
		ob_start();
		require $view_file;
		$ret = ob_get_clean();
		
		$x = explode(PHP_EOL, $ret);
		$ret = implode(PHP_EOL.'> ', $x);
		
		echo PHP_EOL.$ret;
	}
	
	/**
	 * \brief	Return the cmd that $this provides 
	 */
	protected function get_cmd() {
		return str_replace('Phake_Script_', '', get_class($this));
	}
	
	/*
	function _index() {
		p::touch('myfile.txt');
	}
	
	function _example() {
		p::touch('myfile.txt');
	}*/
}


?>