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

<<<<<<< HEAD:lib/header.php
define('PHAKER_APP_DIR', 	dirname(dirname(__FILE__)));
=======
define('PHAKER_APP_DIR', 	dirname(dirname(__FILE__)).'/');

define('PHAKER_LIB_DIR', 	dirname(__FILE__).'/');
>>>>>>> c296d51b54800e876bbd8bebd1f23f9d0df7d9b4:lib/header.php


# Shared code
# (TODO)
<<<<<<< HEAD:lib/header.php

=======
require_once	dirname(__FILE__).'/common.php';
>>>>>>> c296d51b54800e876bbd8bebd1f23f9d0df7d9b4:lib/header.php

# Global phake scripts

$custom_dirs = @$GLOBALS['_ENV']['PHAKE_SCRIPTS_DIR'];
if(trim($custom_dirs))	$custom_dirs = ":$custom_dirs";
define('PHAKE_SCRIPTS_DIR',		PHAKER_APP_DIR.'/phaker/core:'.PHAKER_APP_DIR.'/phaker/plugin'.$custom_dirs);



# Global J5 templates 
# (TODO)


?>