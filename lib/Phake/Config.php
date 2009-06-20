<?php

class Phake_Config 
{
    
    static private $settings   = array(
        'pretend'   =>  false,
        'verbose'   =>  false
        );
    
    function set($var, $value)
    {
        self::$settings[$var]   = $value;
    }
    
    function get($var)
    {
        return self::$settings[$var];
    }
}

?>