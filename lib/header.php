<?php
die(__FILE__.__LINE__.'REMOVE THIS');
/**
 * Overall header for phaker project - defines where all the core templates etc are. Includes shared code.
 * @package Phaker
 * @filesource
 */

/**
 * Output message to STOUT
 * @todo 	Replace this with better logging / debugging function
 */
function dbg($msg) {
	echo "\n  $msg";
}


/**
 * Location of Phaker docs
 */
define('PHAKER_DOC_DIR', 	dirname(dirname(__FILE__)).'/doc/');

/**
 * Phaker source directory (i.e. as you get it from github) 
 */
define('PHAKER_APP_DIR', 	dirname(dirname(__FILE__)).'/');

/**
 * Phaker lib directory (containing j5 and Phake)
 */
define('PHAKER_LIB_DIR', 	dirname(__FILE__).'/');

# Shared code
require_once	dirname(__FILE__).'/common.php';


// Include Zend things

ini_set('include_path',ini_get('include_path').':'.dirname(dirname(__FILE__)).'/'.':'.dirname(dirname(__FILE__)).'/lib/');

require_once 'lib/Zend/Loader.php';

class Phaker_AutoLoader extends Zend_Loader
{
    public static function loadClass($class, $dirs = null)
    {
		//echo "\n!! Loading: $class\n";
		if(substr($class, 0, 5)=='Phake') {
			Autoloader::load($class);
		} else {
        	parent::loadClass($class, $dirs);
		}
    }

    public static function autoload($class)
    {
        try {
            self::loadClass($class);
            return $class;
        } catch (Exception $e) {
            return false;
        }
    }
}

Zend_Loader::registerAutoload('Phaker_AutoLoader');

//Zend_Loader::registerAutoload();








# Global phake scripts

$custom_dirs = @$GLOBALS['_ENV']['PHAKE_SCRIPTS_DIR'];
if(trim($custom_dirs))	$custom_dirs = ":$custom_dirs";

/**
 * Contains colon-separated directories that Phaker will look in to find Phake scripts.
 * 
 * You can add other directories to this by defining an environment variable 'PHAKE_SCRIPTS_DIR':
 * <code>
 * export PHAKE_SCRIPTS_DIR=~/my_phakes/
 * </code>
 * or
 * <code>
 * export PHAKE_SCRIPTS_DIR=~/my_phakes/:/usr/local/phakes/
 * </code>
 * This directory will then be parsed for custom scripts.
 */
define('PHAKE_SCRIPTS_DIR',		PHAKER_APP_DIR.'/phaker/core:'.PHAKER_APP_DIR.'/phaker/examples'.$custom_dirs);


?>