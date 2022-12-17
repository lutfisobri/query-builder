<?php

use Riyu\Database\Connection\Connection;

$connection = new Connection;
$connection->config([
    'driver' => 'mysql',
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname' => 'test',
    'charset' => 'utf8',
    'port' => 3306
]);

User::create([
    'name' => 'Riyu',
    'email' => 'Riyu@example.com',
    'password' => 'password',
])->save();

User::where('id', 1)->update([
    'name' => 'Riyu',
    'email' => 'riyu@example.com',
    'password' => '123456',
]);

User::where('id', 1)->delete();

User::where('id', 1)->get();

User::find(1);

User::all();

User::where('id', 1)->first();

User::where('id', 1)->count();