<?php
namespace Convee\Core;


trait Singleton
{
    private static $instanceList = [];

    /**
     * @return static
     */
    public static function instance() {
        $className = get_called_class();
        if (!isset(self::$instanceList[$className])) {
            self::$instanceList[$className] = new static();
        }
        return  self::$instanceList[$className];
    }

}
