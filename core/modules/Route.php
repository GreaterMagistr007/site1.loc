<?php

namespace core\modules;

class Route
{
    public static $routes = [];

    public function __construct()
    {

    }


    private static function addRoute($uri, $action, $method)
    {
        // Проверим наличие параметров:
        preg_match_all('/\{[A-z0-9]*\}/', $uri, $params);
        $uri = preg_replace('/\{[A-z0-9]*\}/', '{a}',  $uri);
        // удалим последний слеш из uri:
        if (strlen($uri) > 1 && mb_substr($uri, -1) === '/') {
            $uri = mb_substr($uri, 0, -1);
        }
        $params = $params[0];

        // Сохраним только названия параметров:
        foreach ($params as $key => $param) {
            $params[$key] = preg_replace('/(\{)|(\})/', '',  $param);
        }

        if (!isset(self::$routes[$uri])) {
            self::$routes[$uri] = [];
        }

        if ($method === 'ANY') {
            foreach (Request::AVAILABLE_REQUEST_METHODS as $oneMethod) {
                self::$routes[$uri][$oneMethod] = [
                    'action' => $action,
                    'params' => $params
                ];
            }
        } else {
            self::$routes[$uri][$method] = [
                'action' => $action,
                'params' => $params
            ];;
        }
    }

    /**
     * @param $uri - относительный УРЛ сайта
     * @param $action - страка вида "Модуль@Метод"
     * @param $method - метод запроса. any - обрабатывает все виды
     * @return void
     */
    public static function add($uri, $action, $method = 'any')
    {
        $method = mb_strtoupper($method);

        if (!$uri) {
            $uri = '/';
        }

        if ($uri[0] !== '/') {
            $uri = '/' . $uri;
        }

        list($module, $function) = explode('@', $action);

        $module = container($module);

        if (!$module) {
            throw new \DomainException('Отсутствует модуль "' . $module . '" в контейнере');
        }

        if (!method_exists($module, $function)) {
            throw new \DomainException('Отсутствует метод "' . $action . '" в модуле ' . $module::class );
        }

        self::addRoute($uri, $action, $method);
    }


    /**
     * Список маршрутизации
     * @return array
     */
    public static function list()
    {
        return self::$routes;
    }

    public static function run()
    {
        // получим текущий урл:
        $request = request();

        $uri = $request->uri();
        $method = $request->method();

        // Если есть прямое вхождение роутинга без параметров
//        if (isset(self::$routes[$uri][$method])) {
//            list($module, $function) = explode('@', self::$routes[$uri][$method]['action']);
//            $module = container($module);
//            return $module->$function();
//        }


        // Попробуем перебрать все сохраненные роуты:
        $similarRoutes = [];
        // уберем последний
        $params = explode('/', $uri);
        if (!end($params)) {
            array_pop($params);
        }
        foreach (self::$routes as $key => $value) {
            $k = explode('/', $key);
            if (!end($k)) {
                array_pop($k);
            }
            if (count($k) === count($params) && isset($value[$method])) {
                $similarRoutes[$key] = $value;
            }
        }

        // Теперь топаем по всем похожим роутам и смотрим на соответствие:
        foreach ($similarRoutes as $uri => $route) {
            $arguments = [];
            $isSimilar = true;
            $argNum = 0;

            $k = explode('/', $uri);
            if (!end($k)) {
                array_pop($k);
            }
            foreach ($k as $key => $value) {
                if ($value !== $params[$key] && $value !== '{a}') {
                    $isSimilar = false;
                }
                if ($isSimilar && $value === '{a}') {
                    $arguments[$route[$method]['params'][$argNum]] = $params[$key];
                    $argNum++;
                }
            }
            if ($isSimilar) {
                // Найден подходящий роут. Передаем управление:
                list($module, $function) = explode('@', $route[$method]['action']);
                $module = container($module);
                return $module->$function($arguments);
            }
        }

        // Похожих роутов не найдено - идем на 404
        return redirect(404);
    }

}