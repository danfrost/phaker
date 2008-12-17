<?php
/**
 * Header for Phake source. 
 * 
 * @todo 		Merge with lib/header.php
 * 
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */

/**
 * Phake source directory (i.e. lib/Phake/)
 */
define('PHAKE_DIR', 			dirname(__FILE__));

/**
 * Current working directory
 * 
 * @todo 	This should be merged into the Phake_Context idea - but see also thoughts on reworking Phake_Context
 */
define('PHAKE_PWD', 			$_SERVER['PWD']);

require_once dirname(dirname(__FILE__)).'/header.php';

/**
 * Return the directory in $dirs that $file exists in. Last directory wins.
 * 
 * @todo 	Merge into generic PATH-/file-finding
 * 
 * @param $file	Filename
 * @param $dirs Array of dirs
 * @return Filename		Full path to file, if found.
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