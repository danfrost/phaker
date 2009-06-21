<?php

class Phake_Pwd 
{
    
    static private  $pwd    = null;
    
    function & get()
    {
        self::initializePwd();
        return self::$pwd;
    }
    
    private function initializePwd()
    {
        if(self::$pwd) {
            return;
        }
        
        self::$pwd = new Phake_Pwd_Directory($GLOBALS['_ENV']['PWD']).'/';
    }
}
?>