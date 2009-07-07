<?php

class Phake_Vars
{
    
    const phakeDirName = 'phake';
    
    private static $vars    = array();
    
    public static function get($var)
    {
        // This should go into a "User" API
        if(self::$vars[$var]) {
            return self::$vars[$var];
        }
        $value = readline("  $var [default $default]\n\t[Current ".self::$vars[$var]."]:");
        if($value) {
            self::$vars[$var] = $value;
        }
        
        return self::$vars[$var];
    }
    
    /**
     * Make new phake dir - this should only happen in the make 'project' dir - like .git/
     ** This should move to a 'dir handler' for phake
     */
    public static function makePhakeDir()
    {
        $base = $_SERVER['PWD'].'/';
        @mkdir($base.self::phakeDirName.'/vars/', 0777, true);
        @mkdir($base.self::phakeDirName.'/logs/', 0777, true);
    }
    
    static function load()
    {
        Phake_Vars::makePhakeDir();
        
        $base = $_SERVER['PWD'].'/';
        $f = f($base.self::phakeDirName.'/vars/cache');
        $f->touch();
        self::$vars = unserialize($f->contents());
    }
    
    static function save()
    {
        Phake_Vars::makePhakeDir();
        
        $base = $_SERVER['PWD'].'/';
        $f = f($base.self::phakeDirName.'/vars/cache');
        $f->touch();
        $f->setcontent(serialize(self::$vars));
    }
}

?>