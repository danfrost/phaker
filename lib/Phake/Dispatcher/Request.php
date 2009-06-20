<?php

/**
 * Represents the original request in terms of command, action and arguments. Purely used to 
 * pass around the info - doesn't do any dispatching etc.
 */
class Phake_Dispatcher_Request
{
    
    private $command    = null;
    private $action     = null;
    private $arguments  = array();
    
    function __construct($command, $action, $arguments)
    {
        $this->command  = $command;
        $this->action   = $action;
        $this->arguments    = $arguments;
    }
    
    function __get($var)
    {
        return $this->$var;
    }
}

?>