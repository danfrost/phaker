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
	$actions = Phake_Inspector::getActions($c);
	
	$cCap = $c;
	$cCap{0} = strtoupper($cCap{0});
	$path = Autoloader::get_class_file("Phake_Script_$cCap");
	
	//echo("!Path: /$path:"." for class 'Phake_Script_$cCap'");
	//$path = Autoloader::get_class_dir()
	
	if($verbose) {
		$doc = Phake_Inspector::getCommandDocs($c);
		echo PHP_EOL.$doc.PHP_EOL;
		
		echo PHP_EOL."  Actions available for '$c':";
	} else {
		$dir = dirname($path);
		$dir = str_replace('//', '/', $dir);
		$x = explode('/', $dir);
		$dir = $x[count($x)-1];
		echo red( PHP_EOL.str_pad("  $c", 15)).blue( "[from dir: .../$dir]" );
	}
	//$path = str_replace(dirname($path), '>>', $path);
	
	//echo PHP_EOL."Loaded from $dir | $path".PHP_EOL;
	//echo PHP_EOL."    Loaded from dir /$dir";
	
	foreach ($actions as $a) {
		if($a=='index') {
			$action = '(default)';
		} else {
			$action = ":$a";
		}
		echo ("    ".str_pad($action, 15)."\t".Phake_Inspector::getActionDocs($c,$a) ).PHP_EOL;
	}
	//echo PHP_EOL;
}
?>