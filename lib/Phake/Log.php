<?php

/**
 * 
 * @todo    POC: finish this off
 */
class Phake_Log {
    
    const   quiet       = '__quiet__';
    const   verbose     = '__verbose__';
    
    static  $level  = null;
    
    static  $logger = null;
    
    static function initLog() {
        static $has_run = false;
        if($has_run) {
            return;
        }
        
        self::$logger = new Zend_Log();
        $writer = new Zend_Log_Writer_Stream('php://output');
        
        self::$logger->addWriter($writer);
        
        $has_run = true;
    }
    
    function log($msg) {
        self::initLog();
        if(self::$level==self::verbose) {
            self::$logger->log($msg, Zend_Log::EMERG);
        }
    }
    
}

?>