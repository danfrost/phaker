<?php

$verbose = false;
//die('$command = '.$command);
if($command) {
	$cmds = array($command);
	$verbose = true;
} else {
	$cmds = Phake_Finder::getKnownCommands();
}

if(!$verbose) {
	echo '  Available commands'. PHP_EOL .'  "phake help command-name" for more info'.PHP_EOL;
}

$packages = array();

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
		echo red( PHP_EOL.str_pad("  $c", 15)).blue( "[from package: $dir]" );
		
		$doc = Phake_Inspector::getCommandDocs($c);
		echo trim($doc) ? PHP_EOL.$doc.PHP_EOL : '';
		
		echo "  Actions available for '".red($c)."':".PHP_EOL;
		
    	foreach ($actions as $a) {
    		if($a=='index') {
    			$action = '(default)';
    		} else {
    			$action = $a;
    		}
    		
    		$docs = Phake_Inspector::getActionDocs($c,$a);
    		
    		$line = "   - ".red(str_pad($action, 15)).'    ';
    		print_r($_SERVER);
        	$exec = 'echo "$COLUMNS"';
    		echo $exec;
    		$cols = `$exec`;
    		echo($cols);
    		
    		//echo ("   - ".red(str_pad($action, 15))."\t". ).PHP_EOL;
    	}
	} else {
		$dir = dirname($path);
		$dir = str_replace('//', '/', $dir);
		$x = explode('/', $dir);
		$dir = $x[count($x)-1];
		$packages[$dir][] = red( str_pad("    $c", 10));//.blue( "[$dir]" );
	}
}

if(!$verbose) {
    foreach($packages as $dir => $lines) {
        echo PHP_EOL.'  Package: '.blue($dir);
        echo implode('', $lines).PHP_EOL;
    }
}
?>