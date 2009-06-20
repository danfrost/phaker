<?php
/**
 * Main phaker script
 * 
 * The only purpose of this is to be called by bin/phake. This passes everything directly on to Phake_Dispatcher::dispatch_cli.
 * 
 * @see 	Phake_Dispatcher::dispatch_cli()
 * 
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 * @filesource
 */
require_once dirname(__FILE__).'/bootstrap.php';

try {
	echo Phake_Dispatcher::dispatchCli($argv);
} catch(Phake_Script_EndAllException $e) {
	echo PHP_EOL.PHP_EOL."Fatal error: ".$e->getMessage().PHP_EOL;
}
//print_r(Autoloader::$class_cache);
exit;

?>