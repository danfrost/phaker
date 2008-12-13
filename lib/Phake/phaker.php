<?php

require_once dirname(__FILE__).'/header.php';

echo Phake_Dispatcher::dispatch_cli($argv);

exit;

?>