<?php 
namespace Riyu\Database\Connection;

use PDO;

abstract class Storage
{
    protected static $config = [];

    protected static $connection = [];

    protected static $options = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    protected static $verbs = [
        'driver', 'host', 'username', 'password', 'dbname', 'charset', 'port'
    ];

    protected static $dsn;

    public static function setDsn($dsn)
    {
        self::$dsn = $dsn;
    }

    public static function setConfig($config)
    {
        $data = [];
        foreach (self::$verbs as $key => $value) {
            if (array_key_exists($value, $config)) {
                $data[] = $config[$value];
            }
        }
        self::$config = $data;
    }

    public static function getConfig()
    {
        return self::$config;
    }

    public static function setOptions($options)
    {
        self::$options = $options;
    }

    public static function getOptions()
    {
        return self::$options;
    }

    public static function setConnection($connection)
    {
        self::$connection = $connection;
    }

    public static function getConnection()
    {
        return self::$connection;
    }

    public static function clear()
    {
        self::$config = null;
        self::$connection = null;
        self::$options = null;
    }
}