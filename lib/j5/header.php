<?php

// Config

require_once dirname(dirname(__FILE__)).'/header.php';

define('J5_DIR', 			dirname(dirname(__FILE__)));

define('J5_PWD', 			$_SERVER['PWD']);

$extraTmplDir = (isset($_SERVER['J5_TEMPLATE_DIR'])) ? $_SERVER['J5_TEMPLATE_DIR'] : '';
	
$x = explode(':', $extraTmplDir);

$dirs = array();
foreach ($x as $k=>$v) {
	$dir = (substr($v,0,1)=='/') ? $v : (J5_PWD.'/'.$v);
	$dirs[] = $dir;
}
$extraTmplDir = implode(':', $extraTmplDir);

define('J5_TEMPLATE_DIR', 	J5_DIR.'/templates:.:'.$extraTmplDir);

die('Get J5_TEMPLATE_DIR working');

require_once J5_DIR.'/lib/class.J5_File.php';
require_once J5_DIR.'/lib/class.J5_Input.php';
require_once J5_DIR.'/lib/class.J5_Config.php';
require_once J5_DIR.'/lib/class.J5_Dispatcher.php';
require_once J5_DIR.'/lib/class.J5_Generator.php';
require_once J5_DIR.'/lib/class.J5.php';

require_once J5_DIR.'/lib/Commands/class.J5_Command.php';
require_once J5_DIR.'/lib/Commands/class.J5_Command_Config.php';
require_once J5_DIR.'/lib/Commands/class.J5_Command_Generate.php';
require_once J5_DIR.'/lib/Commands/class.J5_Command_Help.php';



?>