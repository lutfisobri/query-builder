<?php
namespace Riyu\Database\Interfaces;

interface Build
{
    public function buildInsert();
    public function buildSelect();
    public function buildUpdate();
    public function buildDelete();

    public function buildSelects();
    public function buildFrom();
    public function buildJoins();
    public function buildWheres();
    public function buildGroups();
    public function buildHaving();
    public function buildOrders();
    public function buildLimit();
    public function buildOffset();
    public function buildTable();
    public function buildColumns();
    public function buildValues();
    public function buildSet();
}