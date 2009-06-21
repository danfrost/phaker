<?php

class Phake_Script_Plugin extends Phake_Script
{
    /**
     * Help message
     */
    function index()
    {
        phake('help plugin');
    }
    
    /**
     * Install a phake script package from a git URL
     */    
    function install($url)
    {
        $package = str_replace('.git', '', basename($url));
        if(is_dir(PHAKE_DIR_INSTALL.'/'.$package)) {
            echo "Installing package: '$package'".PHP_EOL;
            $cmd = 'cd '.PHAKE_DIR_INSTALL.'/'.$package.' && git pull origin master';
        } else {
            echo "Updating package: '$package' from '$url'".PHP_EOL;
            $cmd = 'cd '.PHAKE_DIR_INSTALL.' && git clone '.$url;
        }
        
        exec($cmd);
        echo "Completed.".PHP_EOL;
    }
    
    /**
     * Uninstalls the plugin
     */
    function uninstall($pluginName)
    {
        $package = str_replace('.git', '', basename($pluginName));
        if(!is_dir(PHAKE_DIR_INSTALL.'/'.$package)) {
            echo "Package '$package' not installed";
            return;
        }
        
        echo "Uninstalling package '$package'".PHP_EOL;
        $cmd = 'mv '.PHAKE_DIR_INSTALL.'/'.$package.' /tmp/'.$package.'-uninstalled-phaker-package-'.time();
        //echo $cmd;
        exec($cmd);
        echo "Completed.".PHP_EOL;
    }
    
}

?>