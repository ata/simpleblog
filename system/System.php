<?php

class System
{
    const LIB_DIR  = __DIR__;
    
    private static $pathDirs = array();
    
    public static function autoload($class)
    {
        if (class_exists($class)) {
            return false;
        } 
        foreach (self::$pathDirs as $dir) {
            
            $class_path = $dir.'/'.str_replace('\\','/',$class) . '.php';
            if (file_exists($class_path)) {
                require $class_path;
                return true;
            } 
        }
        
        return false;
        
    }
    
    
    
    public static function load()
    {
        self::$pathDirs[] = __DIR__;
        
        $dirs = func_get_args();
        foreach($dirs as $dir) {
            self::$pathDirs[] = $dir;
        }
        
        spl_autoload_register(array('System','autoload'));
        
    }

}
