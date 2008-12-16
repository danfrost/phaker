<?php
/**
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
require_once dirname(__FILE__).'/header.php';

echo Phake_Dispatcher::dispatch_cli($argv);

//print_r(Autoloader::$class_cache);
exit;

?>