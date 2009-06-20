<?php

$verbose = false;
//die('$command = '.$command);
if($command) {
	$cmds = array($command);
	$verbose = true;
} else {
	$cmds = Phake_Finder::getKnownCommands();
}

echo PHP_EOL;

if(!$verbose) {
	echo PHP_EOL.'  Available commands - phake help "command" for more info'.PHP_EOL;
}

foreach ($cmds as $c) {
	$actions = Phake_Inspector::getActions($c);
	
	$cCap = $c;
	$cCap{0} = strtoupper($cCap{0});
	$path =  Phake_AutoLoader::getClassFile("Phake_Script_$cCap");
	
	if($verbose) {
	    //throw new Exception("need to re-build Phake_Inspector");
	    $dir = dirname($path);
		$dir = str_replace('//', '/', $dir);
		$x = explode('/', $dir);
		$dir = $x[count($x)-1];
		echo red( PHP_EOL.str_pad("  $c", 15)).blue( "[from dir: .../$dir]" );
		
		$doc = Phake_Inspector::getCommandDocs($c);
		echo trim($doc) ? PHP_EOL.$doc.PHP_EOL : '';
		
		echo "  Actions available for '".red($c)."':".PHP_EOL;
		
    	foreach ($actions as $a) {
    		if($a=='index') {
    			$action = '(default)';
    		} else {
    			$action = $a;
    		}
    		echo ("   - ".red(str_pad($action, 15))."\t".Phake_Inspector::getActionDocs($c,$a) ).PHP_EOL;
    	}
	} else {
		$dir = dirname($path);
		$dir = str_replace('//', '/', $dir);
		$x = explode('/', $dir);
		$dir = $x[count($x)-1];
		echo red( PHP_EOL.str_pad("  $c", 15)).blue( "[from dir: .../$dir]" );
	}
}
?>