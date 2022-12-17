<?php

namespace Riyu\Database\Connection;

use Exception;
use Riyu\Database\Interfaces\ConnectionManager;
use PDO;
use PDOException;

class Manager implements ConnectionManager
{
    /**
     * Connection for query
     * 
     * @var object
     */
    private $connection;

    /**
     * Set Connection for query
     * 
     * @return this
     */
    public function __construct($connection)
    {
        try {
            $this->connection = $connection;
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    /**
     * Execute query for select all
     * 
     * @param string $query
     * @param array $options
     * @return array
     */
    public function queryAll($query, $options = null)
    {
        try {
            $stmt = $this->connection->prepare($query);
            if (!is_null($options)) {
                $this->bindValue($stmt, $query, $options);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    /**
     * Execute query for select get
     * 
     * @param string $query
     * @param array $options
     * @return array
     */
    public function queryGet($query, $options = null)
    {
        try {
            $stmt = $this->connection->prepare($query);
            if (!is_null($options)) {
                $this->bindValue($stmt, $query, $options);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $th) {
            throw new Exception($th);
        }
    }

    /**
     * Execute query for select first
     * 
     * @param string $query
     * @param array $options
     * @return object
     */
    public function queryFirst($query, $options = null)
    {
        try {
            $stmt = $this->connection->prepare($query);
            if (!is_null($options)) {
                $this->bindValue($stmt, $query, $options);
            }
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $th) {
            throw new Exception($th);
        }
    }

    /**
     * Execute query for insert update delete
     * 
     * @param string $query
     * @param array $options
     * @return mixed
     */
    public function execute($query, $options = null)
    {
        try {
            $stmt = $this->connection->prepare($query);
            if (!is_null($options)) {
                $this->bindValue($stmt, $query, $options);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public function exec($query, $options = null)
    {
        try {
            $stmt = $this->connection->prepare($query);
            if (!is_null($options)) {
                $this->bindValue($stmt, $query, $options);
            }
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    /**
     * Execute query for count
     * 
     * @param string $query
     * @param array $options
     * @return int
     */
    public function count($query, $options = null)
    {
        try {
            $stmt = $this->connection->prepare($query);
            if (!is_null($options)) {
                $this->bindValue($stmt, $query, $options);
            }
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    /**
     * Execute query for count
     * 
     * @param string $query
     * @param array $options
     * @return int
     */
    public function queryCount($query, $options = null)
    {
        try {
            $stmt = $this->connection->prepare($query);
            if (!is_null($options)) {
                $this->bindValue($stmt, $query, $options);
            }
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    /**
     * Bind value for query
     * 
     * @param object $stmt
     * @param string $query
     * @param array $options
     * @return void
     */
    public function bindValue($stmt, $query, $options)
    {
        try {
            foreach ($options as $key => $value) {
                switch ($options) {
                    case is_int($value):
                        $stmt->bindValue($key, $value, PDO::PARAM_INT);
                        break;
                    case is_bool($value):
                        $stmt->bindValue($key, $value, PDO::PARAM_BOOL);
                        break;
                    case is_null($value):
                        $stmt->bindValue($key, $value, PDO::PARAM_NULL);
                        break;
                    default:
                        $stmt->bindValue($key, $value, PDO::PARAM_STR);
                        break;
                }
            }
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }
}
