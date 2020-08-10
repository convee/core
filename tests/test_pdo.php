<?php

use Convee\Core\Config;
use Convee\Core\Db;
use Convee\Core\Sql;

require dirname(__DIR__) . '/vendor/autoload.php';

Config::load('./database.php');
Db::init(Config::get('default'));

$mysql = new Mysql();
$mysql->fetchAll();


class Mysql extends Sql
{
    public $table = 'user';
}
