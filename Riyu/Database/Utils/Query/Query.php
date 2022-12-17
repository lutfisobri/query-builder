<?php
namespace Riyu\Database\Utils\Query;

use Exception;

trait Query
{
    public function binding($key, $value)
    {
        $this->bindings[$key] = $value;
        return $this;
    }

    public function select($selects = ['*'])
    {
        $selects = is_array($selects) ? $selects : func_get_args();

        foreach($selects as $key => $column) {
            if (is_string($key)) {
                $select[$key] = $column . " AS " . $key;
            } else {
                $select[] = $column;
            }
        }
        $this->selects = implode(", ", $select);
        $this->queryType = 'select';
        return $this;
    }

    public function insert($insert)
    {
        $insert = is_array($insert) ? $insert : func_get_args();

        foreach($insert as $key => $value) {
            if (!in_array($key, $this->fillable)) {
                throw new Exception("The column {$key} is not fillable");
            }
            $this->columns[] = $key;
            $this->values[] = ":".$key;
            $this->binding($key, $value);
        }

        $this->insert = $insert;
        $this->queryType = 'insert';
        return $this;
    }

    public function update($update)
    {
        $update = is_array($update) ? $update : func_get_args();

        foreach($update as $key => $value) {
            if (!in_array($key, $this->fillable)) {
                throw new Exception("The column {$key} is not fillable");
            }
            if (is_string($key)) {
                $this->set[] = "`".$key . "` = :" . $key;
                $this->binding($key, $value);
            } else {
                $binding = "update".count($this->set);
                $this->set[] ="`".$key . "` = :" . $binding;
                $this->binding($binding, $update[$value]);
            }
        }

        $this->update = $update;
        $this->queryType = 'update';
        return $this;
    }

    public function delete()
    {
        $this->isDelete = true;
        $this->queryType = 'delete';
        return $this;
    }

    public function join($table, $first, $operator = null, $second = null, $type = 'INNER')
    {
        // check if the operator is null
        // and set the operator to equal
        if (func_num_args() == 3) {
            list($second, $operator) = [$operator, '='];
        }

        // check if the value is null
        if (is_null($second)) {
            $operator = '=';
        }

        $this->joins[] = compact('table', 'first', 'operator', 'second', 'type');
        return $this;
    }

    public function leftjoin($table, $first, $operator = null, $second = null)
    {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }

    public function rightjoin($table, $first, $operator = null, $second = null)
    {
        return $this->join($table, $first, $operator, $second, 'RIGHT');
    }

    public function fulljoin($table, $first, $operator = null, $second = null)
    {
        return $this->join($table, $first, $operator, $second, 'FULL');
    }

    public function crossjoin($table, $first, $operator = null, $second = null)
    {
        return $this->join($table, $first, $operator, $second, 'CROSS');
    }

    public function outerjoin($table, $first, $operator = null, $second = null)
    {
        return $this->join($table, $first, $operator, $second, 'OUTER');
    }

    public function where($column, $operator = null, $value = null, $boolean = 'AND')
    {
        if (func_num_args() == 1) {
            $this->where[] = $column;
            return $this;
        }

        // check if the operator is null
        if (func_num_args() == 2) {
            list($value, $operator) = [$operator, '='];
        }
        
        // check if the value is null
        if (is_null($value)) {
            list($value, $operator) = [$operator, '='];
        }

        $binding = $value;
        $value = ":where".count($this->where);

        $this->where[] = compact('column', 'operator', 'value', 'boolean');

        $this->binding($value, $binding);

        return $this;
    }

    public function andWhere($column, $operator = null, $value = null)
    {
        return $this->where($column, $operator, $value, 'AND');
    }

    public function orWhere($column, $operator = null, $value = null)
    {
        return $this->where($column, $operator, $value, 'OR');
    }

    public function groupby($groups)
    {
        $groups = is_array($groups) ? $groups : func_get_args();
        $this->groups = $groups;
        return $this;
    }

    public function orderby($orders)
    {
        $orders = is_array($orders) ? $orders : func_get_args();
        $this->orders = $orders;
        return $this;
    }

    public function having($column, $operator = null, $value = null, $boolean = 'and')
    {
        if (func_num_args() == 2) {
            list($value, $operator) = [$operator, '='];
        }

        if (is_null($value)) {
            $operator = '=';
        }

        $this->having[] = compact('column', 'operator', 'value', 'boolean');
        return $this;
    }

    public function orhaving($column, $operator = null, $value = null)
    {
        return $this->having($column, $operator, $value, 'or');
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }
}