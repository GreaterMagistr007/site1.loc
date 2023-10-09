<?php
namespace core\modules;

use core\modules\Request;


class Container
{
    public static $modules = [];

    public function __construct()
    {
    }

    public function load($module)
    {
        if (!isset(self::$modules[$module])) {
            $className = 'core\modules\\' . $module;
            self::$modules[$module] = new $className();
        }

        return self::$modules[$module];
    }

    public function __get($name)
    {
        $result = $this->load($name);

        if ($result) {
            return $result;
        }

        throw new \DomainException('В контейнере нет модутя ' . $name);
    }

    /**
     * Автоматически загружаемые модули
     * @return void
     */
    public function autoload()
    {
        self::$modules['Request'] = new Request();
        self::$modules['Route'] = new Route();
    }
}