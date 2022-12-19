<?php
namespace Riyu\Database\Connection;


class Connection
{
    /**
     * Default connection
     * 
     */
    private $pdoc = [
        'driver', 'host', 'username', 'password', 'dbname', 'charset', 'port'
    ];

    /**
     * Set connection
     *
     * @param array $config
     * @return void
     */
    public static function config(array $config)
    {
        $data = [];
        foreach ((new static)->pdoc as $key => $value) {
            if (array_key_exists($value, $config)) {
                $data[] = $config[$value];
            }
        }
        Storage::setConfig($data);
    }
}
