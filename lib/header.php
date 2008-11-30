<?php

// util function
function dbg($msg) {
	echo "\n  $msg";
}

/**
 * \file	lib/header.php 
 * \brief	Overall header for phaker project - defines where all the core templates etc are. Includes shared code.
 */

define('PHAKER_DOC_DIR', 	dirname(dirname(__FILE__)).'/doc/');

define('PHAKER_APP_DIR', 	dirname(dirname(__FILE__)));


# Shared code
# (TODO)


# Global phake scripts

$custom_dirs = @$GLOBALS['_ENV']['PHAKE_SCRIPTS_DIR'];
if(trim($custom_dirs))	$custom_dirs = ":$custom_dirs";
define('PHAKE_SCRIPTS_DIR',		PHAKER_APP_DIR.'/phaker/core:'.PHAKER_APP_DIR.'/phaker/plugin'.$custom_dirs);



# Global J5 templates 
# (TODO)


?>