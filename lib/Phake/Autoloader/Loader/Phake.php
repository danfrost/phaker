<?php

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

?>