<?php

use Riyu\Database\Utils\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password'];

    protected $prefix = 'example_';

    protected $timestamp = true;
}