<?php 
namespace Riyu\Database\Connection;

use PDO;
use PDOException;

class Event
{
    /**
     * Connection for database
     * 
     * @var pdo
     */
    protected $connection;

    /**
     * Raw config for database
     * 
     * @var string
     */
    private $config;

    /**
     * Dsn for database
     * 
     * @var string
     */
    private $dsn;

    /**
     * Constructor for Event
     * 
     * @return void
     */
    public function __construct()
    {
        $this->config = Storage::getConfig();
    }

    /**
     * Connect to database
     * 
     * @return object pdo
     */
    public static function connect()
    {
        $instance = new static;
        $instance->setDsn();
        $instance->setConnection();
        return $instance->connection;
    }

    /**
     * Set dsn for database
     * 
     * @return void
     */
    public function setDsn()
    {
        $config = Storage::getConfig();
        $this->config = $config;
        $this->dsn = $config['driver'] . ':host=' . $config['host'] . ';dbname=' . $config['dbname'] . ';charset=' . $config['charset'] . ';port=' . $config['port'];
    }

    /**
     * Set connection for database
     * 
     * @return void
     */
    public function setConnection()
    {
        try {
            $this->connection = new PDO($this->dsn, $this->config['username'], $this->config['password']);
            return $this->connection;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    public function raw()
    {
        $this->setDsn();
        $connection = $this->dsn;
        $connection = new PDO($this->dsn, $this->config['username'], $this->config['password']);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    }
}