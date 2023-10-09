<?php

namespace core\modules;

class Request
{
    const AVAILABLE_REQUEST_METHODS = [
        'GET',
        'HEAD',
        'POST',
        'PUT',
        'DELETE',
        'CONNECT',
        'OPTIONS',
        'TRACE',
        'PATCH',
    ];

    public function __construct()
    {

    }

    public function method()
    {
        $result = isset($_SERVER['REQUEST_METHOD']) ? mb_strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
        if (!isset(self::AVAILABLE_REQUEST_METHODS[$result])) {
            $result = 'GET';
        }
        return $result;
    }

    public static function domain()
    {
        return $_SERVER['HTTP_HOST'];
    }

    public static function protocol()
    {
        return isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
    }

    public static function host($path = '')
    {
        $result = self::protocol() . '://' . self::domain();
        if ($path) {
            if ($path[0] !== '/') {
                $result .= '/';
            }
            $result .= $path;
        }

        return $result;
    }

    public static function href($path)
    {
        $result = 'https://' . self::domain();
        if ($path[0] !== '/') {
            $result .= '/';
        }
        $result .= $path;

        return $result;
    }

    public function ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    public function uri()
    {
        return isset($_SERVER['REDIRECT_URL']) ?
            $_SERVER['REDIRECT_URL'] :
            explode('?', $_SERVER['REQUEST_URI'])[0];
    }
}