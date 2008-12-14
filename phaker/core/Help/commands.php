<?php
$cmds = Phake_Inspector::getScripts();
echo PHP_EOL.PHP_EOL.'  Available commands - phake help "command" for more info'.PHP_EOL;;
foreach ($cmds as $c) {
	echo PHP_EOL."  $c";
	$actions = Phake_Inspector::getActions($c);
	foreach ($actions as $a) {
		if($a!='index') {
			//echo PHP_EOL."    ".str_pad($c, 15)."\t".str_pad($a, 20)."\t".Phake_Inspector::getActionDocs($c,$a);
			//echo PHP_EOL."    ".str_pad("$c:$a", 20)."\t".Phake_Inspector::getActionDocs($c,$a);
			echo PHP_EOL."    ".str_pad(":$a", 15)."\t".Phake_Inspector::getActionDocs($c,$a);
		}
	}
	echo PHP_EOL;
}
?>