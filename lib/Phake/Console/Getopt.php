<?php

class Phake_Console_Getopt extends Zend_Console_Getopt
{
    
    protected   $_unknown_options   = array();
    
    /**
     * Override Zend_Console_Getopt::_parseSingleOption to ignore unknown params silently
     */
    protected function _parseSingleOption($flag, &$argv)
    {
        if ($this->_getoptConfig[self::CONFIG_IGNORECASE]) {
            $flag = strtolower($flag);
        }
        if (!isset($this->_ruleMap[$flag])) {
            
            Phake_Log::log("Option \"$flag\" is not recognized. This is probably not a problem.");
            
            $param = array_shift($argv);
            $this->_unknown_options[$flag] = $param;
            /*
            This is what the original function did:
            
            * @see Zend_Console_Getopt_Exception
            
            throw new Zend_Console_Getopt_Exception(
                "Option \"$flag\" is not recognized.",
                $this->getUsageMessage());
            */
            return;
        }
        $realFlag = $this->_ruleMap[$flag];
        switch ($this->_rules[$realFlag]['param']) {
            case 'required':
                if (count($argv) > 0) {
                    $param = array_shift($argv);
                    $this->_checkParameterType($realFlag, $param);
                } else {
                    /**
                     * @see Zend_Console_Getopt_Exception
                     */
                    throw new Zend_Console_Getopt_Exception(
                        "Option \"$flag\" requires a parameter.",
                        $this->getUsageMessage());
                }
                break;
            case 'optional':
                if (count($argv) > 0 && substr($argv[0], 0, 1) != '-') {
                    $param = array_shift($argv);
                    $this->_checkParameterType($realFlag, $param);
                } else {
                    $param = true;
                }
                break;
            default:
                $param = true;
        }
        $this->_options[$realFlag] = $param;
    }
}
?>