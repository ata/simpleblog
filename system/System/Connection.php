<?php
// file: /system/System/Connection.php
namespace System;

class Connection
{
    private static $instance = null;
    private function __construct()
    {
    }
    private function __clone()
    {
    }
    public static function getInstance($connectionString,$username,$password)
    {
        if (self::$instance == null) {
            try{
                self::$instance = new \PDO($connectionString,$username,$password);
            } catch (\PDOException $e) {
                die($e->getMessage());
            } catch (\Exception $e) {
                die($e->getMessage());
            }
            
        } 
        return self::$instance;
    }
    
}
