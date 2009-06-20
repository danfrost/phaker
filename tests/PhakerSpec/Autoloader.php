<?php

class PhakerSpec_Autoloader extends Phake_AutoLoader_Loader {
    
    static $test_counter = 0;
    
    function knowsClass($class) {
        self::$test_counter = 1;
        return false;
    }
    
    function getClassFile($class) {
        
    }
    
    function requireClass($class) {
        
    }
    
}

?>