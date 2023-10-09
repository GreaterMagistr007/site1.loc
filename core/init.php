<?php

// Подключим файл конфигурации
require_once 'config.php';

// Файл с функциями:
require_once 'global-functions.php';

// Контейнер
$container = $_SERVER['container'] = new core\modules\Container();
$container->autoload();

// Хелперы
require_once 'helpers.php';

// маршрутизация:
require_once 'router.php';

// Запускаем обработку роутинга:
container('Route')::run();

//Route::list();


dd(container('Route')::list());





//
dd($request);

sayHello();