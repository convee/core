<?php

namespace Library\Core;

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
     * @param string $message
     * @param array  $context
     */
    public static function alert($message, array $context = array())
    {
        self::write(self::ALERT, $message, $context);
    }

    /**
     * @param $message
     * @param array $context
     */
    public static function notice($message, array $context = array())
    {
        self::write(self::NOTICE, $message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public static function error($message, array $context = array())
    {
        self::write(self::ERROR, $message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public static function warn($message, array $context = array())
    {
        self::write(self::WARNING, $message, $context);
    }

    /**
     * 流程追踪
     * @param string $message
     * @param array  $context
     */
    public static function trace($message, array $context = array())
    {
        self::write(self::TRACE, $message, $context);
    }

    /**
     * 重要事件
     * @param string $message
     * @param array  $context
     */
    public static function info($message, array $context = array())
    {
        self::write(self::INFO, $message, $context);
    }

    /**
     * debug
     * @param string $message
     * @param array  $context
     */
    public static function debug($message, array $context = array())
    {
        self::write(self::DEBUG, $message, $context);
    }
    /**
     * 写日志
     * @param $message
     * @param $level
     * @param array $context
     */
    public static function write($message, $level, $context = array())
    {
        if (is_object($message)) {
            $message = json_decode(json_encode($message), true);
        }

        if (is_array($message)) {
            $message = json_encode($message, JSON_UNESCAPED_UNICODE);
        }

        if (!empty($context)) {
            foreach ($context as $key => $val) {
                $message = str_replace('{' . $key . '}', $val, $message);
            }
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