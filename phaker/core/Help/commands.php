<?php

$verbose = false;
if($command) {
	$cmds = array($command);
	$verbose = true;
} else {
	$cmds = Phake_Inspector::getScripts();
}

if(!$verbose) {
	echo PHP_EOL.'  Available commands - phake help "command" for more info'.PHP_EOL;
}

foreach ($cmds as $c) {
	$actions = Phake_Inspector::getActions($c);
	
	$cCap = $c;
	$cCap{0} = strtoupper($cCap{0});
	$path = Autoloader::get_class_file("Phake_Script_$cCap");
	
	if($verbose) {
		$doc = Phake_Inspector::getCommandDocs($c);
		echo PHP_EOL.$doc.PHP_EOL;
		
		echo PHP_EOL."  Actions available for '$c':".PHP_EOL;
	} else {
		$dir = dirname($path);
		$dir = str_replace('//', '/', $dir);
		$x = explode('/', $dir);
		$dir = $x[count($x)-1];
		echo red( PHP_EOL.str_pad("  $c", 15)).blue( "[from dir: .../$dir]" );
	}
	
	foreach ($actions as $a) {
		if($a=='index') {
			$action = '(default)';
		} else {
			$action = ":$a";
		}
		echo ("    ".str_pad($action, 15)."\t".Phake_Inspector::getActionDocs($c,$a) ).PHP_EOL;
	}
}
?>