<?php

namespace Convee\Core;

use PDO;
use PDOException;


class Db
{
    public static $configs;
    public static $pdo;

    public function __construct()
    {
    }

    public static function init($configs)
    {
        if (empty($configs) || !is_array($configs)) {
            throw new PDOException('pdo config error.');
        }
        foreach ($configs as $db => $config) {
            if (!isset($config['host'], $config['dbname'], $config['user'],$config['password'])) {
                throw new PDOException('pdo config error.');
            }
        }
        self::$configs = $configs;
    }

    /**
     * @param string $db
     * @return PDO
     */
    public static function pdo($db = 'default')
    {
        if (!self::$pdo) {
            $host = self::$configs[$db]['host'];
            $dbname = self::$configs[$db]['dbname'];
            $user = self::$configs[$db]['user'];
            $password = self::$configs[$db]['password'];
            $option = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
            self::$pdo = new PDO(sprintf('mysql:host=%s;dbname=%s;charset=utf8', $host, $dbname), $user, $password, $option);
        }
        return self::$pdo;
    }
}