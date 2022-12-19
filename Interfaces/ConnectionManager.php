<?php
namespace Riyu\Database\Interfaces;

interface ConnectionManager
{
    public function queryAll($query, $options = null);
    public function queryGet($query, $options = null);
    public function queryFirst($query, $options = null);
    public function execute($query, $options = null);
    public function bindValue($stmt, $query, $options);
}