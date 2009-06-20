<?php

class Phake_File_Factory {
    
    static private $file_objects   = array();
    
    /**
     * 'new'
     */
    static  function & n($file) {
        //echo "\n\n:$file:";
        if(!isset(self::$file_objects[$file])) {
            self::$file_objects[$file] = new Phake_File($file);
        }
        return self::$file_objects[$file];
    }
    
    static  function known($file) 
    {
        return @isset(self::$file_objects[$file]);
    }
    
}

?>