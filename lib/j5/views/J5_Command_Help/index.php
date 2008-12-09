Available commands:
<?php

$cmds = J5_Command::getCommands();
foreach($cmds as $cmd) {
	echo "\n\t$cmd";
}

?>