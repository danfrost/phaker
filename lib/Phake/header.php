<?php

// Config
define('PHAKE_DIR', 			dirname(__FILE__));

define('PHAKE_PWD', 			$_SERVER['PWD']);

require_once dirname(dirname(__FILE__)).'/header.php';

#require_once PHAKE_DIR.'/class.PhakeDispatcher.php';
#require_once PHAKE_DIR.'/class.PhakeScript.php';
#require_once PHAKE_DIR.'/class.PhakeEvent.php';
#require_once PHAKE_DIR.'/class.p.php';

#require_once PHAKE_DIR.'/class.CliContext.php';

/**
 * \brief	Return the directory in $dirs that $file exists in. Last directory wins.
 * @param $file	Filename
 * @param $dirs Array of dirs
 */
function findFileInDirs($file, $dirs) {
	//echo "\nFinding: $file\n";
	//print_r($dirs);
	$use_file = '';
	foreach ($dirs as $d) {
		//echo "\nTrying: $d.'/'.$file'\n";
		if(file_exists($d.'/'.$file)) {
			$use_file = $d.'/'.$file;
		}
	}
	return $use_file;
}

?>