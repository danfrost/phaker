<?php


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

?>