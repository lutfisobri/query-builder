<?php

namespace Riyu\Database\Interfaces;

interface QueryInterface
{
    public function select($column = ['*']);
    public function insert($insert);
    public function update($update);
    public function delete();
    public function join($table, $column, $operator = null, $value = null, $type = 'INNER');
    public function leftJoin($table, $column, $operator = null, $value = null);
    public function rightJoin($table, $column, $operator = null, $value = null);
    public function fullJoin($table, $column, $operator = null, $value = null);
    public function crossJoin($table, $column, $operator = null, $value = null);
    public function outerJoin($table, $column, $operator = null, $value = null);
    public function where($column, $operator = null, $value = null, $boolean = 'AND');
    public function groupBy($column);
    public function orderBy($column);
    public function having($column, $operator = null, $value = null, $boolean = 'AND');
    public function limit($limit);
    public function offset($offset);
}
