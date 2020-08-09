<?php

namespace Convee\Core;

class Log
{
    const TRACE = 'trace';
    const ALERT = 'alert';
    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
    const DEBUG = 'debug';

    /**
     * @param $message
     */
    public static function alert($message)
    {
        self::write(self::ALERT, $message);
    }

    /**
     * @param $message
     */
    public static function notice($message)
    {
        self::write(self::NOTICE, $message);
    }

    /**
     * @param $message
     */
    public static function error($message)
    {
        self::write(self::ERROR, $message);
    }

    /**
     * @param $message
     */
    public static function warn($message)
    {
        self::write(self::WARNING, $message);
    }

    /**
     * 流程追踪
     * @param $message
     */
    public static function trace($message)
    {
        self::write(self::TRACE, $message);
    }

    /**
     * 重要事件
     * @param $message
     */
    public static function info($message)
    {
        self::write(self::INFO, $message);
    }

    /**
     * debug
     * @param $message
     */
    public static function debug($message)
    {
        self::write(self::DEBUG, $message);
    }

    /**
     * 写日志
     * @param $message
     * @param $level
     */
    public static function write($level, $message)
    {
        if (is_object($message)) {
            $message = json_decode(json_encode($message), true);
        }

        if (is_array($message)) {
            $message = json_encode($message, JSON_UNESCAPED_UNICODE);
        }

        $trace = debug_backtrace();

        $caller = isset($trace[1]) ? $trace[1] : [];
        if (isset($caller['file'])) {
            $file = pathinfo($caller['file'], PATHINFO_BASENAME);
            $line = $caller['line'];
        } else {
            $file = $line = '';
        }
        $path = LOG_PATH . date('Y-m-d') . '.log';
        $message = sprintf('[%s][%s][%s][%s]%s', date('Y-m-d H:i:s'), $level, $file, $line, $message);
        file_put_contents($path, $message . PHP_EOL, FILE_APPEND);
    }
}