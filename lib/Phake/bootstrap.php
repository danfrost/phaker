<?php

define('PHAKE_VERSION', '0.0.1');

define('PHAKE_DIR_SRC', dirname(__FILE__).'/');

define('PHAKE_DIR_APP', dirname(dirname(dirname(__FILE__))).'/');

define('PHAKE_DIR_INSTALL', PHAKE_DIR_APP.'phaker/installed');

ini_set('include_path',ini_get('include_path').':'.dirname(dirname(__FILE__)).'/');

require_once 'Zend/Loader.php';

require_once PHAKE_DIR_SRC.'Autoloader.php';

Zend_Loader::registerAutoload('Phake_AutoLoader');

require_once PHAKE_DIR_SRC . '/AutoLoader/Exception.php';
require_once PHAKE_DIR_SRC . '/AutoLoader/Exception/ClassUnknown.php';

require_once PHAKE_DIR_SRC . '/AutoLoader/Loader.php';

require_once PHAKE_DIR_SRC . '/AutoLoader/Loader/Phake.php';
Phake_AutoLoader::addAutoloader(new Phake_AutoLoader_Loader_Phake());

require_once PHAKE_DIR_SRC . '/AutoLoader/Loader/Scripts.php';
Phake_AutoLoader::addAutoloader(new Phake_AutoLoader_Loader_Scripts());


function red($a) {
	$exec = 'echo -e "\\033[1m'.$a.'\\033[0m"';
	exec("$exec", $arr);
	return implode(PHP_EOL, $arr);
}
function blue($a) {
	//$exec = 'echo -e "\\033[1m'.$a.'\\033[0m"';
	$exec = 'echo -e \'\E[34;1m'.$a.'.\'; tput sgr0; tput sgr0';//';echo -e "\\033[1m'.$a.'\\033[0m"';
	exec("$exec", $arr);
	return implode(PHP_EOL, $arr);
}

if(!function_exists('readline')) {
	function readline($prompt="") {
	   print $prompt;
	   $out = "";
	   $key = "";
	   $key = fgetc(STDIN);        //read from standard input (keyboard)
	   while ($key!="\n")        //if the newline character has not yet arrived read another
	   {
	       $out.= $key;
	       $key = fread(STDIN, 1);
	   }
	   return trim($out);
	}
}


?>