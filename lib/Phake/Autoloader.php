<?php


class Phake_AutoLoader extends Zend_Loader
{
    
    static  $loaders    = array();
    
    public static function autoload($class)
    {
        try {
            self::loadClass($class);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public static function loadClass($class, $dirs = null)
    {
        //if() {
		try {
		    $loader = self::findAudoloaderForClass($class);
		    //echo PHP_EOL."! >> Loading using: ".$loader;
		    try {
                $loader->loadClass($class); 
            } catch(Exception $e) {
                echo PHP_EOL.">> Problem: ".$e->getMessage();
            }
		} catch(Phake_AutoLoader_Exception $e) {
		    //echo "TRYING";
		    parent::loadClass($class, $dirs);
		}
		//echo PHP_EOL . 'Done';
		if(!class_exists($class)) {
		    //echo "THROWING!";
		    throw new Phake_AutoLoader_Exception_ClassUnknown();
		}
    }
    
    public static function getClassFile($class)
    {
        $loader = self::findAudoloaderForClass($class);
        return $loader->getClassFile($class);
    }
    
    /**
     * Returns the autoloader that will load the class. 
     * @throws  Phake_AutoLoader_Exception_ClassUnknown     If no autoloaders know this class
     * @throws  Phake_AutoLoader_Exception_LoaderConflict   If more than one autoloader knows this class
     */
    private static function & findAudoloaderForClass($class)
    {
        $eligable_loaders   = array();
        foreach (self::$loaders as $k=>$_) {
            if(self::$loaders[$k]->knowsClass($class)) {
                $eligable_loaders[] = $k;
            }
        }
        
        // If no loaders were found, we don't know about the class
        if(count($eligable_loaders)==0) {
            throw new Phake_AutoLoader_Exception_ClassUnknown("No loaders know about the class '$class'.");
        }
        
        // If more than one loader was found, we don't know what to do
        if(count($eligable_loaders)>1) {
            throw new Phake_AutoLoader_Exception_LoaderConflict("More than one loader knows about the class '$class': ".implode(", ", self::getAutoloaders($eligable_loaders)));
        }
        
        return self::getAutoloader($eligable_loaders[0]);
    }
    
    public static function addAutoloader(Phake_AutoLoader_Loader & $loader)
    {
        $id = md5(get_class($loader));
        self::$loaders[$id] = & $loader;
        return $id;
    }
    
    public static function & getAutoloader($loader_id)
    {
        return self::$loaders[$loader_id];
    }
    
    public static function & getAutoloaders($loader_ids=array())
    {
        if(!$loader_ids) {
            return self::$loaders;
        }
        $loaders = array();
        foreach($loader_ids as $id) {
            $loaders[$id] = & self::getAutoloader($id);
        }
        return $loaders;
    }
}
?>