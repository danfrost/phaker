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
require_once dirname(__FILE__).'/header.php';

echo Phake_Dispatcher::dispatch_cli($argv);

//print_r(Autoloader::$class_cache);
exit;

?>