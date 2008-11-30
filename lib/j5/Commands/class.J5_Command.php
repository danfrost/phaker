<?php

abstract class J5_Command {
	// For instances
	protected $action = null;
	protected $args	= array();
	
	function __construct($args) {
		$this->args = $args;
	}
	
	final function dispatchAction($action) {
		if(!method_exists($this,$action)) {
			//throw new Exception("No such action '$action' in class '".get_class($this)."'");
			$this->args = array_merge(array($action),$this->args);
			$action = 'index';
		}
		$this->action = $action;
		if($ret=$this->$action()) {
			return $ret;
		} else {
			return $this->include_view($this->action);
		}
	}
	
	/**
	 * \brief	Includes the view
	 */
	final function include_view($action) {
		$class = get_class($this);
		$view_file = J5_DIR.'/lib/views/'.$class.'/'.$action.'.php';
		if(!file_exists($view_file)) {
			throw new Exception("Not a file: '$view_file'");
		}
		
		ob_start();
		require $view_file;
		$ret = ob_get_clean();
		return $ret;
	}
	
	/**
	 * \brief	Default action
	 */
	abstract function index();
	
	/**
	 * \brief	Returns array of all valid commands
	 */
	static final function getCommands() {
		$classes = get_declared_classes();	
		$cmds = array();
		foreach($classes as $c) {
			if(is_subclass_of($c, 'J5_Command')) {
				$cmds[] = strtolower(str_replace('J5_Command_', '', $c));
				
			}
		}
		return $cmds;
	}
}

?>