<?php
namespace Riyu\Database\Utils\Query;

use Closure;
use Exception;
use Riyu\Database\Connection\Manager;
use Riyu\Database\Interfaces\QueryInterface;

class Builder extends Grammar implements QueryInterface
{
    use Query;

    protected $queryType = 'select';

    protected $bindings = [];

    protected $selects;

    protected $insert = [];

    protected $update = [];

    protected $isDelete;

    protected $joins = [];

    protected $where = [];

    protected $groups;

    protected $orders;

    protected $having = [];

    protected $limit;

    protected $offset;

    protected $query;

    protected $timestamp = [];

    protected $fillable = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function debug()
    {
        return $this->buildQuery();
    }

    public function setTimestamp(array $column)
    {
        $this->timestamp = $column;
        return $this;
    }

    public function create($data)
    {
        $data = is_array($data) ? $data : func_get_args();
        $this->insert($data);
        return $this->save();
    }

    public function buildQuery()
    {
        $query = '';

        if ($this->queryType == 'select') {
            $query = $this->buildSelect();
        } elseif ($this->queryType == 'insert') {
            $query = $this->buildInsert();
        } elseif ($this->queryType == 'update') {
            $query = $this->buildUpdate();
        } elseif ($this->queryType == 'delete') {
            $query = $this->buildDelete();
        } else {
            $query = $this->buildSelect();
        }

        return $query;
    }

    public function get()
    {
        $query = $this->buildQuery();

        $exec = new Manager($this->connection);

        try {
            return $exec->queryGet($query, $this->bindings);
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public function all()
    {
        $query = $this->buildQuery();

        $exec = new Manager($this->connection);

        try {
            return $exec->queryAll($query, $this->bindings);
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public function first()
    {
        $this->limit(1);
        $query = $this->buildQuery();

        $exec = new Manager($this->connection);

        try {
            return $exec->queryFirst($query, $this->bindings);
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public function count()
    {
        $this->select('COUNT(*) as count');
        $query = $this->buildQuery();

        $exec = new Manager($this->connection);

        try {
            return $exec->queryCount($query, $this->bindings);
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public function save()
    {
        $query = $this->buildQuery();

        $exec = new Manager($this->connection);

        try {
            return $exec->exec($query, $this->bindings);
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public function execInsert(array $attributes)
    {
        $this->insert($attributes);
        $query = $this->buildQuery();

        $exec = new Manager($this->connection);

        try {
            return $exec->exec($query, $this->bindings);
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public function paginate($page = 1, $limit = 10)
    {
        $this->limit($limit);
        $this->offset(($page - 1) * $limit);
        $query = $this->buildQuery();

        $exec = new Manager($this->connection);

        try {
            return $exec->queryAll($query, $this->bindings);
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public function find($id, $column = null)
    {
        if ($column != null) {
            $this->where($column, $id);
            $this->queryType = 'select';
            return $this->first();
        }

        if (isset($this->primaryKey)) {
            $column = $this->primaryKey;
        } else {
            $column = 'id';
        }

        $this->where($column, $id);
        return $this->first();
    }

    public function findOrFail($id, $column = 'id', Closure $callback = null)
    {
        if ($column instanceof Closure) {
            $tmp = $column;
            $callback = $tmp;
            $column = 'id';
        }
        $this->where($column, $id);
        $this->queryType = 'select';
        $result = $this->first();
        if (empty($result)) {
            if ($callback instanceof Closure) {
                return $callback();
            }
        }
        return $result;
    }

    public function firstWhere($column, $operator = null, $value = null, $boolean = 'AND')
    {
        $this->where($column, $operator, $value, $boolean);
        return $this->first();
    }

    public function truncate()
    {
        $query = "TRUNCATE TABLE {$this->table}";

        $exec = new Manager($this->connection);

        try {
            return $exec->exec($query);
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }
}
