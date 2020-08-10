<?php

namespace Convee\Core;

use PDO;
use PDOException;


class Db
{
    public static $config;
    public static $pdo;

    public function __construct()
    {
    }

    public static function init($config)
    {
        if (!isset($config['host'], $config['dbname'], $config['user'],$config['password'])) {
            throw new PDOException('pdo config error.');
        }
        self::$config = $config;
    }

    /**
     * @param string $db
     * @return PDO
     */
    public static function pdo($db = 'default')
    {
        if (!self::$pdo) {
            $host = self::$config[$db]['host'];
            $dbname = self::$config[$db]['dbname'];
            $user = self::$config[$db]['user'];
            $password = self::$config[$db]['password'];
            $option = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
            self::$pdo = new PDO(sprintf('mysql:host=%s;dbname=%s;charset=utf8', $host, $dbname), $user, $password, $option);
        }
        return self::$pdo;
    }
}