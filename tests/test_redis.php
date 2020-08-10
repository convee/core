<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$config = [
    'master' => ['host' => '127.0.0.1', 'port' => 6379],
    'slaves' => [
        ['host' => '127.0.0.1', 'port' => 6379],
        ['host' => '127.0.0.1', 'port' => 6379],
    ]
];

try {
    $redis =  new \Convee\Core\Redis($config);
    $redis->set('a', 123);
    echo $redis->get('a') . PHP_EOL;

    $redis->hSet('a_', 'b', 'c');
    $a = $redis->hGetAll('a_');
    var_dump($a);die;
} catch (\RedisException $e) {
    echo $e->getMessage();
}
