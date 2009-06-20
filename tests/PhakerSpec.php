<?php

//require_once dirname(dirname(__FILE__)).'/lib/Phake/header.php';

/**
 * To execute this:
 * > 	phpspec PhakerSpec --specdoc
 */
class DescribePhaker extends PHPSpec_Context {
	
	function before() {
	    if(!defined('PhakerSpec_Phake_SRC_DIR')) {
	        define('PhakerSpec_Phake_SRC_DIR', dirname(dirname(__FILE__)).'/lib/Phake/');
        }
	}
	
	function itShouldProvideABootstrapFile() {
	    $this->spec(file_exists(PhakerSpec_Phake_SRC_DIR.'bootstrap.php'))->should->beTrue();
	}
	
	
	function itShouldNotHaveAutoloadSetBeforeIncluding() {
	    $this->spec(function_exists('__autoload'))->should->beFalse();
	}
	
	function itShouldHaveAutoloadSetAfterIncluding() {
	    $this->include_bootstrap();
	    $this->spec(function_exists('__autoload'))->should->beTrue();
	}
	
	function itShouldDefinePhake_version() {
	    $this->include_bootstrap();
	    
	    $this->spec(defined('PHAKE_VERSION'))->should->beTrue();
	}
	
	function itShouldDefineSourceLocationInBootstrapFile() {
	    $this->include_bootstrap();
	    
	    $this->spec(defined('PHAKE_DIR_SRC'))->should->beTrue();
	}
	
	
	function itShouldSetPhake_autoloaderInBootstrapFile() {
	    $this->include_bootstrap();
	    
	    $this->spec(class_exists('Phake_Autoloader'))->should->beTrue();
	}
	
	/*** Autoloader specification - will only pass if the above tests have passed ***/
	
	
	function itShouldReturnTrueIfExistantClassIsClass() {//itShouldThrowExceptionIfNonExistantClassIsCalled() {
	    $this->spec(Phake_Autoloader::autoload('Phake_Autoloader'))->should->beTrue();
	}
	
	function itShouldReturnFalseIfNonExistantClassIsClass() {//itShouldThrowExceptionIfNonExistantClassIsCalled() {
	    $this->spec(Phake_Autoloader::autoload('Phake_ThisClassWillNeverExist'))->should->beFalse();
	}
	
	function itShouldIncludeAutoloaderHelperClass() {
	    $this->spec(class_exists('Phake_Autoloader'))->should->beTrue();
	    $this->spec(class_exists('Phake_AutoLoader_Exception'))->should->beTrue();
	    $this->spec(class_exists('Phake_AutoLoader_Loader'))->should->beTrue();
	}
	
	function itShouldHaveTwoAutoloadersReady() {
	    
	    $loaders = Phake_Autoloader::getAutoloaders();
	    
	    $this->spec(count($loaders))->should->be(2);
	    
	    foreach($loaders as $l) {
	        $this->spec(is_a($l, 'Phake_AutoLoader_Loader'))->should->beTrue();
	    }
	}
	
	function itShouldAllowPluggableAutoloaders() {
	    require_once 'PhakerSpec/Autoloader.php';
	    
	    $id = Phake_Autoloader::addAutoloader(new PhakerSpec_Autoloader());
	            
	    $this->spec(
	        get_class(Phake_Autoloader::getAutoloader($id)) == 'PhakerSpec_Autoloader' 
	        )->should->beTrue();
	}
	
	/**
	 * We check so see if our the autoloader asks our loader where dummy class is.
	 */
	function itShouldTryEachAutoloaderForAGivenClass() {
	    require_once 'PhakerSpec/Autoloader.php';
	    $id = Phake_Autoloader::addAutoloader(new PhakerSpec_Autoloader());
	    
	    PhakerSpec_Autoloader::$test_counter = 0;
	    
	    try {
	        Phake_Autoloader::getClassFile('PhakerSpec_Dummyclass');
        } catch(Exception $e) {}
	    
	    $this->spec(PhakerSpec_Autoloader::$test_counter)->should->beEqualTo(1);
	}
	
	
	function itShouldHaveAutoloadersThrowExceptionsWhenLoadingUnknownClasses() {
	    // We pass an unknown class to the autoloaders to make sure they throw exceptions.
	    $loaders = Phake_Autoloader::getAutoloaders();
	      
	    $l = each($loaders);
	    $l = $l['value'];
	    
	    $e = null;
        try {
           $l->loadClass('ThisClassWillSurelyNeverExist1234567890');
        } catch (Exception $e) {
           
        }
        
        $this->spec(is_a($e, 'Exception'))->should->beTrue();
	}
	
	function itShouldHaveAutoloadersReturnFalseForUnknownClasses() {
	    require_once 'PhakerSpec/Autoloader.php';
	    $id = Phake_Autoloader::addAutoloader(new PhakerSpec_Autoloader());
	    
	    $loader = Phake_Autoloader::getAutoloader($id);
	    
	    $this->spec(
	        $loader->knowsClass('ThisClassWillSurelyNeverExist1234567890')
	        )->should->beFalse();
	}
	
	function isShouldHaveAutoloadersReturnTrueForKnownClasses() {
	    $id = Phake_Autoloader::addAutoloader(new PhakerSpec_Autoloader());
	    
	    $loader = Phake_Autoloader::getAutoloader($id);
	    
	    $this->spec($loader->knowsClass('PhakerSpec_Dummyclass'))->should->beFalse();
	}
	
	function itShouldReturnTheFileForAClass() {
	    // this has a known location
	    $this->spec(
	        Phake_Autoloader::getClassFile('Phake_Action')
	        )->should->be(PHAKE_DIR_SRC.'Action.php');
	}
	
	function itShouldIncludeClasses() {
	    // Calling 'class_exists' should fire a call to the autoloader
	    $this->spec(class_exists('Phake_Script'))->should->beTrue();
	    $this->spec(class_exists('Phake_Log'))->should->beTrue();
	    $this->spec(class_exists('Phake_Action'))->should->beTrue();
	}
	
	
	
	function itShouldKnowWhereToFindScripts() {
	    $dirs = Phake_Finder::getScriptDirs();
	    $rand = rand(0, count($dirs)-1);
	    $this->spec(is_dir($dirs[$rand]))->should->beTrue();
	}
	
	function itShouldAllowScriptDirsToBeAdded() {
	    $my_dir = dirname(__FILE__).'/PhakerSpecScripts/';
	    Phake_Finder::addScriptDir($my_dir);
	    $dirs = Phake_Finder::getScriptDirs();
	    $is_found = false;
	    foreach ($dirs as $d) {
	       if($d==$my_dir) {
	           $is_found = true;
	       }
	    }
	    $this->spec($is_found)->should->beTrue();
	}
	
	
	function itShouldIncludeScriptClasses() {
	    // Calling 'class_exists' should fire a call to the autoloader
	    try {
	        $this->spec(class_exists('Phake_Script_Dummy'))->should->beTrue();
        } catch(Exception $e) {
            print_r($e->getMessage());
        }
	}
    
    
    
    /** Run-time Configuration **/
    
    function itShouldProvideAPretendMode()
    {
        $value  = true;
        
        Phake_Config::set('pretend', $value);
        
        $this->spec(Phake_Config::get('pretend'))->should->be($value);
    }
    
    
    
    /** Phake_Pwd **/
    
    function itShouldProvideADClass() {
        $this->spec(class_exists('Phake_Pwd'))->should->beTrue();
    }
    
    function itShouldProvideAccessToTheCurrentPwdObject() {
        $this->spec(
            is_a(Phake_Pwd::get(), 'Phake_Pwd_Directory'))->should->beTrue();
    }
    
    function isShouldProvideToStringMethodForTheWorkingDirectory() {
        $this->spec(is_dir(Phake_Pwd::get()->__toString()))->should->beTrue();
    }
    
    function itShouldHaveASinglePwd() {
        $this->spec(Phake_Pwd::get())->should->be(dirname(__FILE__));
    }
    
    function itShouldBeAbleToChangeWorkingDirectory() {
        Phake_Pwd::get()->setPwd(dirname(dirname(__FILE__)));
        $this->spec(
            trim(Phake_Pwd::get()->getPwd())
            )->should->be(trim(dirname(dirname(__FILE__))));
    }
    
    
    function itShouldNotHaveActionsInThePwdObjectItself() 
    {
        // this makes sure that the Pwd object can't do anything itself
        $allowedMethods = array(
            '__construct__toStringgetPwdsetPwd'
            );
            
        $this->spec(
            implode('', get_class_methods(Phake_Pwd::get()))
            )->should->be(implode('', $allowedMethods));
        
    }
	
	/**
	 * This is just to make sure we have all the right classes
	 */
	function itShouldHaveAllTheseClasses() 
	{
	    $core_classes   = array(
	        'Action',
	        'ActionHelper',
	        'Autoloader',
	        'File',
	        'Finder',
	        'FINSHTHISLATER'
	        );
	    foreach ($core_classes as $c) {
	       $this->spec(class_exists('Phake_'.$c))->should->beTrue();
	    }
	    
	}
	
	function itShouldPassOnFileActionsToActionObjects()
	{
	    Phake_Config::set('pretend', true);
	    
	    $action = Phake_Pwd::get()->touch('testfile.txt');
	    
	    $this->spec(is_a($action, 'Phake_Action_Touch'))->should->beTrue();	    
	}
	
	
	function itShouldProvideShortFunctionF() {
	    Phake_Config::set('pretend', true);
	    
	    $this->spec(is_a(f('test'), 'Phake_File'))->should->beTrue();
	}
	
	
	function itShouldReturnFilesAsSingleton() 
	{
	    Phake_Config::set('pretend', true);
	    
	    $f1 = f('test');
	    
	    $f2 = f('test');
	    
	    $this->spec($f1->_)->should->equal($f2->_);
	}
	
	function itShouldAlwaysPassActionsToObjects() {
	    Phake_Config::set('pretend', true);
	    
	    f('test')->touch();
	    f('test')->move('asdf');
	}
	
	
	/** Dispatcher **/
    
//    function itShouldProvide
	
	
	
	
	private function include_bootstrap() {
	    require_once PhakerSpec_Phake_SRC_DIR.'bootstrap.php';
	}
    
}

?>