<?php


require_once dirname(__FILE__).'/header.php';

J5::init_j5_dir(); // This should ascend the file structure to find the 'j5' directory

// 

echo J5_Dispatcher::dispatch_cli($argv);

exit;


// Begin: Generating
$templates	= $argv[1];
$output		= $argv[2];
$name		= $argv[3];

new J5(
	$templates,//'templates/example/', // This is relative to some J5_TEMPLATE_PATH variable
	$output, // This is relative to PWD
	$name
	);

J5_GenerationLog::write_to_cache();

?>