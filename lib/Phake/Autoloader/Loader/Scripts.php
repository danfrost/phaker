<?php

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

?>