<?php

namespace Riyu\Database\Utils\Query;

use Riyu\Database\Connection\Event;
use Riyu\Database\Interfaces\Build;

class Grammar implements Build
{
    /**
     * @var string
     */
    protected $insert;

    /**
     * @var array
     */
    protected $joins = [];

    /**
     * @var array
     */
    protected $where = [];

    /**
     * @var bool
     */
    protected $isDelete;

    /**
     * @var array
     */
    protected $groups;

    /**
     * @var array
     */
    protected $orders;

    /**
     * @var string
     */
    protected $update;

    /**
     * @var array
     */
    protected $having;

    /**
     * @var array
     */
    protected $selects;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $limit;

    /**
     * @var string
     */
    protected $offset;

    /**
     * @var string
     */
    protected $select;
    /**
     * @var string
     */
    protected $order;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $values = [];

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var array
     */
    protected $bindings = [];

    /**
     * @var array
     */
    protected $set = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $sets = [];

    /**
     * @var object|string|null
     */
    protected $model;

    /**
     * @var string
     */
    protected $primaryKey;

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var array
     */
    protected $timestamp = [];

    /**
     * @var object|string|null
     */
    protected $connection;

    public function __construct()
    {
        $connection = new Event;
        $this->connection = $connection->connect();
    }

    public function setConfig(array $config)
    {
        $table = $config['table'];
        $fillable = $config['fillable'];
        $prefix = $config['prefix'];
        $primaryKey = $config['primaryKey'];
        $this->table = $prefix . $table;
        $this->fillable = $fillable;
        $this->primaryKey = $primaryKey;
    }

    public function buildInsert()
    {
        $table = $this->buildTable();
        $columns = $this->buildColumns();
        $values = $this->buildValues();
        $query = 'INSERT INTO ' . $table . ' ' . $columns . ' VALUES ' . $values . ';';
        return $query;
    }

    public function buildSelect()
    {
        $select = $this->buildSelects();
        $from = $this->buildFrom();
        $join = $this->buildJoins();
        $where = $this->buildWheres();
        $group = $this->buildGroups();
        $order = $this->buildOrders();
        $limit = $this->buildLimit();
        $offset = $this->buildOffset();
        $having = $this->buildHaving();
        $query = $select . $from . $join . $where . $group . $having . $order . $limit . $offset . ';';
        return $query;
    }

    public function buildUpdate()
    {
        $table = $this->buildTable();
        $set = $this->buildSet();
        $where = $this->buildWheres();
        $query = 'UPDATE `' . $table . '` ' . $set . ' ' . $where . ';';
        return $query;
    }

    public function buildDelete()
    {
        $table = $this->buildTable();
        $where = $this->buildWheres();
        $query = 'DELETE FROM `' . $table . '` ' . $where;
        return $query;
    }

    public function buildSelects()
    {
        $select = $this->selects;
        if (empty($select)) {
            return 'SELECT * ';
        }
        return 'SELECT ' . $select . ' ';
    }


    public function buildTruncate()
    {
        $table = $this->buildTable();
        $query = 'TRUNCATE TABLE ' . $table;
        return $query;
    }

    public function buildFrom()
    {
        $from = $this->from;
        if (empty($from)) {
            $from = $this->table;
        }
        return 'FROM `' . $from . '` ';
    }

    public function buildJoins()
    {
        $join = $this->joins;
        $sql = '';
        if (count($join) == 0 || empty($join)) {
            return '';
        }
        foreach ($join as $key => $value) {
            $sql .= $value['type'] . ' JOIN `' . $value['table'] . '` ON ' . $value['first'] . ' ' . $value['operator'] . ' ' . $value['second'] . ' ';
        }
        return $sql . ' ';
    }

    public function buildWheres()
    {
        $where = $this->where;

        if (count($where) == 0) {
            return '';
        }

        if (count($where) == 1) {
            if (!is_array($where[0])) {
                return 'WHERE ' . $where[0] . ' ';
            } else {
                return 'WHERE ' . $where[0]['column'] . ' ' . $where[0]['operator'] . ' ' . $where[0]['value'] . ' ';
            }
        }

        $query = '';
        foreach ($where as $key => $value) {
            if ($key == 0) {
                if (!is_array($value)) {
                    $query .= $value . ' ';
                } else {
                    $query .= '' . $value['column'] . ' ' . $value['operator'] . ' ' . $value['value'] . ' ';
                }
            } else {
                if (!is_array($value)) {
                    $query .= $value . ' ';
                } else {
                    $query .= $value['boolean'] . ' ' . $value['column'] . ' ' . $value['operator'] . ' ' . $value['value'] . ' ';
                }
            }
        }

        if ($query != '') {
            return ' WHERE ' . $query;
        }
    }

    public function buildGroups()
    {
        $group = $this->groups;
        $sql = '';

        if (count($group) == 0 || empty($group)) {
            return '';
        }

        foreach ($group as $key => $value) {
            $sql .= $value . ' ';
        }

        return 'GROUP BY ' . $sql . ' ';
    }

    public function buildHaving()
    {
        $having = $this->having;
        $sql = '';

        if (count($having) == 0 || empty($having)) {
            return '';
        }

        foreach ($having as $key => $value) {
            $sql .= $value['type'] . ' ' . $value['column'] . ' ' . $value['operator'] . ' ' . $value['value'];
        }

        return 'HAVING ' . $sql . ' ';
    }

    public function buildOrders()
    {
        $order = $this->orders;
        $sql = '';

        if (count($order) == 0 || empty($order)) {
            return '';
        }

        foreach ($order as $key => $value) {
            $sql .= $value . ' ';
        }

        return 'ORDER BY ' . $sql . ' ';
    }

    public function buildLimit()
    {
        $limit = $this->limit;

        if (empty($limit)) {
            return '';
        }

        return 'LIMIT ' . $limit . ' ';
    }

    public function buildOffset()
    {
        $offset = $this->offset;

        if (empty($offset)) {
            return '';
        }

        return 'OFFSET ' . $offset . ' ';
    }

    public function buildTable()
    {
        $table = $this->table;

        if (empty($table)) {
            return '';
        }

        return $table;
    }

    public function buildColumns()
    {
        $columns = $this->columns;

        if (count($columns) == 0) {
            return '';
        }

        $query = '(';
        foreach ($columns as $column) {
            $query .= "`" . $column . "`, ";
        }


        if (count($this->timestamp) > 0) {
            $query .= "`" . $this->timestamp['created_at'] . "`, `" . $this->timestamp['updated_at'] . "`, ";
        }

        $query = substr($query, 0, -2);
        $query .= ')';

        return $query;
    }

    public function buildValues()
    {
        $values = $this->values;

        if (count($values) == 0) {
            return '';
        }

        $query = '(';
        foreach ($values as $value) {
            $query .= $value . ", ";
        }

        if (count($this->timestamp) > 0) {
            $query .= "NOW(), ";
            $query .= "NOW(), ";
        }

        $query = substr($query, 0, -2);
        $query .= ')';

        return $query;
    }

    public function buildSet()
    {
        $set = $this->set;

        if (count($set) == 0) {
            return '';
        }

        $query = 'SET ';
        foreach ($set as $value) {
            $query .= $value . ", ";
        }

        if (count($this->timestamp) > 0) {
            $query .= "`" . $this->timestamp['updated_at'] . "` = NOW(), ";
        }

        $query = substr($query, 0, -2);

        return $query;
    }
}
