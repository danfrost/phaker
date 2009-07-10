<?php

/**
 * Finds phake scripts based on the known / configured 'phake dirs'
 */
class Phake_Finder {
    
    private static $dirs    = array();
    
    private static function loadScriptDirs() 
    {
        static $has_run = false;
        if($has_run) {
            return;
        }
        $has_run = true;
        
        // Default / core dirs
        self::addScriptDir(PHAKE_DIR_APP.'phaker/core');
        self::addScriptDir(PHAKE_DIR_APP.'phaker/examples');
        
        // create a handler for the directory
        $handler = opendir(PHAKE_DIR_APP.'phaker/installed/');

        // keep going until all files in directory have been read
        while ($file = readdir($handler)) {

            // if $file isn't this directory or its parent, 
            // add it to the results array
            if ($file != '.' && $file != '..' && is_dir(PHAKE_DIR_APP.'phaker/installed/'.$file)) {
                self::addScriptDir(PHAKE_DIR_APP.'phaker/installed/'.$file);
            }
        }
        
        // tidy up: close the handler
        closedir($handler);
        
        $custom_dirs = @$GLOBALS['_ENV']['PHAKE_SCRIPTS_DIR'];
        
        if(trim($custom_dirs)) {
            $x = explode(':', $custom_dirs);
        } else {
            $x = array();
        }
        
        // Now add local custom dirs
        $localDirs = @file_get_contents(Phake_Pwd::get().'/.phake/config');
        $localDirs = array_unique(explode(PHP_EOL, $localDirs));
        foreach($localDirs as $dir) {
            if(trim($dir)) {
                $x[] = $dir;
            }
        }
        
        foreach ($x as $d) {
            try {
                self::addScriptDir($d);
            } catch(Exception $e) {
                echo PHP_EOL. 'WARNING: Cannot add non existant script dir "'.$d.'"'.PHP_EOL.
                    ' (in '.__FILE__.__LINE__.')';
            }
        }
    }
    
    function addScriptDir($dir)
    {
        //echo PHP_EOL."Adding dir: $dir";
        if(!is_dir($dir)) {
            throw new Exception("'$dir' is not a directory");
        }
        
        self::$dirs[] = $dir;
        
        self::$dirs = array_unique(self::$dirs);
    } 
    
    function getScriptDirs() 
    {
        self::loadScriptDirs();
        return self::$dirs;
    }
    
    /**
     * Finds the file in the phake script dirs.
     */
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
    
    /**
     * Returns complete list of known commands
     */
    public function getKnownCommands()
    {
        self::loadScriptDirs();
        
        $commands = array();
        
        foreach(self::$dirs as $d) {
            if ($handle = opendir($d)) {
                
                /* This is the correct way to loop over the directory. */
                while (false !== ($file = readdir($handle))) {
                    //echo "$file\n";
                    
                    if(substr($file, -4)=='.php') {
                        $file = substr($file, 0, strlen($file)-4);
                        if(!preg_match("_\._", $file)) {
                            $commands[] = $file;
                        }
                    }
                }
                closedir($handle);
            }
        }
        
        foreach($commands as $k=>$v) {
            $commands[$k] = strtolower($v);
        }
        return $commands;
    }
    
    
    /**
     * Returns complete list of known commands => classes
     */
    public function getKnownCommandClasses()
    {
        self::loadScriptDirs();
        
        $commands = array();
        
        foreach(self::$dirs as $d) {
            if ($handle = opendir($d)) {
                
                /* This is the correct way to loop over the directory. */
                while (false !== ($file = readdir($handle))) {
                    //echo "$file\n";
                    
                    if(substr($file, -4)=='.php') {
                        $file = substr($file, 0, strlen($file)-4);
                        if(!preg_match("_\._", $file)) {
                            $commands[] = $file;
                        }
                    }
                }
                closedir($handle);
            }
        }
        
        foreach($commands as $k=>$v) {
            $commands[$k] = strtolower($v);
        }
        return $commands;
    }
    
    /**
     * Needs to be optimised a bit.
     */
    public function getClassForCommand($command)
    {
        if(!in_array($command, self::getKnownCommands())) {
            die("Unknown command: $command");
        }
        $command{0} = strtoupper($command{0});
        return 'Phake_Script_'.$command;
        
    }
    
    
}


?>