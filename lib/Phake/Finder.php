<?php

/**
 * 
 */
class Phake_Finder {
    
    private static $dirs    = array();
    
    private static function loadScriptDirs() 
    {
        static $has_run = false;
        if($has_run) {
            //return;
        }
        $has_run = true;
        
        // Default / core dirs
        self::addScriptDir(PHAKE_DIR_APP.'/phaker/core');
        self::addScriptDir(PHAKE_DIR_APP.'/phaker/examples');
        
        $custom_dirs = @$GLOBALS['_ENV']['PHAKE_SCRIPTS_DIR'];
        
        $x = explode(':', $custom_dirs);
        
        foreach ($x as $d) {
            self::addScriptDir($d);
        }
    }
    
    function addScriptDir($dir)
    {
        //echo PHP_EOL."Adding dir: $dir";
        if(is_dir($dir)) {
            self::$dirs[] = $dir;
        }
        self::$dirs = array_unique(self::$dirs);
    } 
    
    function getScriptDirs() 
    {
        self::loadScriptDirs();
        return self::$dirs;
    }
    
    public function findFile($file)
    {
        $use_file = '';
        foreach (self::$dirs as $d) {
            $d = realpath($d).'/';
            
            $f = $d.$file;
            
            if(file_exists($f)) {
                $use_file = $f;
            }
        }
        
        if(!$use_file && !is_file($use_file)) {
            throw new Exception("'\$use_file' is not set or is not a file");
        }
        
        return $use_file;
    }
    
    
}


?>