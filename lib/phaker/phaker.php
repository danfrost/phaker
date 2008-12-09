<?php

require_once dirname(__FILE__).'/header.php';

echo PhakeDispatcher::dispatch_cli($argv);

exit;

?>