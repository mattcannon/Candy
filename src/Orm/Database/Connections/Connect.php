<?php

namespace Emeka\Potato\Database\Connections;

use PDO;
use Dotenv\Dotenv;
use Emeka\Potato\Database\Connections\Driver;

class Connect 
{

    protected static
    $dotenv,
    $db_host,
    $db_name,
    $db_user,
    $database,
    $db_password;

    protected static function databaseDriver ()
    {
        self::$dotenv   = new Dotenv($_SERVER['DOCUMENT_ROOT']);
        self::$dotenv->load();
        self::$db_host      = getenv('db_host');
        self::$db_name      = getenv('db_name');
        self::$db_user      = getenv('db_user');
        self::$database     = getenv('database');
        self::$db_password  = getenv('db_password');
    }

    private static function connect()
    {
        self::databaseDriver();
        $options = 
        [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        $dsn = self::$database . ':host=' . self::$db_host . ';dbname=' . self::$db_name;

        try 
        {
            return new PDO($dsn, self::$db_user, self::$db_password, $options);
        }
        catch(PDOException $e) 
        {
            self::$error = $e->getMessage();
            return self::$error;
        }   
    }

    protected static function getDataInstance()
    {
        
        if (  ! self::connect()  ) 
        {
            return false;
        }
        return self::connect();
    }


}
