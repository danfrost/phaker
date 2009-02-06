<?php

define('PHAKE_VERSION', '0.0.1');

define('PHAKE_DIR_SRC', dirname(__FILE__).'/');

define('PHAKE_DIR_APP', dirname(dirname(dirname(__FILE__))).'/');

ini_set('include_path',ini_get('include_path').':'.dirname(dirname(__FILE__)).'/');


require_once 'Zend/Loader.php';

// d F

class Phake_AutoLoader_Exception extends Exception {}

class Phake_AutoLoader_Exception_ClassUnknown extends Phake_AutoLoader_Exception {}

class Phake_AutoLoader_Exception_LoaderConflict extends Phake_AutoLoader_Exception {}

require_once PHAKE_DIR_SRC.'Autoloader.php';

Zend_Loader::registerAutoload('Phake_AutoLoader');


abstract class Phake_AutoLoader_Loader {
    
    final protected function convertClassToFilename($class, $base_directory) {
        $file = str_replace('_', '/', $class);
        $file = $base_directory.$file.'.php';
        return $file;
    }
    
    final public function __toString() {
        return get_class($this);
    }
    
    abstract function knowsClass($class);
    
    abstract function getClassFile($class);
    
    final function loadClass($class) {
        
        if(!$this->knowsClass($class)) {
            throw new Phake_AutoLoader_Exception_ClassUnknown("When trying to load class from loader");
        }
        
        if(!$file=$this->getClassFile($class)) {
            throw new Phake_AutoLoader_Exception_ClassUnknown("When trying to load class from loader");
        }
        
        require_once $file;
    }    
}

class Phake_AutoLoader_Loader_Phake extends Phake_AutoLoader_Loader {
    function knowsClass($class) {
        if(substr($class,0,6)=='Phake_') {
            $file = $this->convertClassToFilename($class, dirname(PHAKE_DIR_SRC).'/');
            return file_exists($file);
        }
        
        return false;
    }
    
    function getClassFile($class) {
        if($this->knowsClass($class)) {
            return $this->convertClassToFilename($class, dirname(PHAKE_DIR_SRC).'/');
        }
    }
}

Phake_AutoLoader::addAutoloader(new Phake_AutoLoader_Loader_Phake());

class Phake_AutoLoader_Loader_Scripts extends Phake_AutoLoader_Loader {
    private static $class_cache = array();
    
    function knowsClass($class) {
        
        if(substr($class,0,13)=='Phake_Script_') {
            $file = str_replace('Phake_Script_', '', $class).'.php';
            try {
                $file = Phake_Finder::findFile($file);
                self::$class_cache[$class] = $file;
                return true;
            } catch(Exception $e) {
                return false;
            }
        }
        
        return false;
    }
    
    function getClassFile($class) {
        if($this->knowsClass($class)) {
            return self::$class_cache[$class];
        }
    }
}
Phake_AutoLoader::addAutoloader(new Phake_AutoLoader_Loader_Scripts());



// d F

?>