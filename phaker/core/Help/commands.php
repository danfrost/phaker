<?php
$verbose = false;
if($this->args[0]) {
	$cmds = array($this->args[0]);
	$verbose = true;
} else {
	$cmds = Phake_Inspector::getScripts();
}

if(!$verbose) {
	echo PHP_EOL.'  Available commands - phake help "command" for more info'.PHP_EOL;
}

foreach ($cmds as $c) {
	if($verbose) {
		$doc = Phake_Inspector::getCommandDocs($c);
		echo PHP_EOL.$doc.PHP_EOL;
		
		echo PHP_EOL."  Actions available for '$c':";
	} else {
		echo PHP_EOL."  $c";
	}
	$actions = Phake_Inspector::getActions($c);
	foreach ($actions as $a) {
		if($a=='index') {
			$action = '(default)';
		} else {
			$action = ":$a";
		}
		echo PHP_EOL."    ".str_pad($action, 15)."\t".Phake_Inspector::getActionDocs($c,$a);
	}
	echo PHP_EOL;
}
?>