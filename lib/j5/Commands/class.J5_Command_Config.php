<?php

/**
 * \brief	J5 config commands
 */
class J5_Command_Config extends J5_Command {
	function index() {
		return 'Nothing implemented - show docs';
	}
	
	function set() {
		foreach ($this->args as $v) {
			$x = explode('=', $v);
			$key = trim($x[0]);
			$value = trim($x[1]);
			echo "\nSetting: $key, $value";
			J5_Config::set($key, $value);
		}
		J5_Config::save();
	}
	
	function get() {
		$config = J5_Config::get($this->args[0]);
		
		return "
J5 Config:
".$this->args[0]."	 = $config
";
	}
	
	function show() {
		$config = J5_Config::get();
		
		$l = array();
		foreach ($config as $key => $value) {
			$l[] = ">    $key = $value";
		}
		return '
J5 Config:
'.implode("\n", $l).'
';
	}
}

?>