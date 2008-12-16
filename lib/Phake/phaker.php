<?php

require_once dirname(__FILE__).'/header.php';

echo Phake_Dispatcher::dispatch_cli($argv);

//print_r(Autoloader::$class_cache);
exit;

?>