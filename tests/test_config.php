<?php

use Convee\Core\Config;

define('APP_PATH', dirname(__DIR__));

require APP_PATH . '/vendor/autoload.php';

Config::load(APP_PATH . '/tests/config/config.php');

$masterConfig = Config::get('master');

var_dump($masterConfig);die;
