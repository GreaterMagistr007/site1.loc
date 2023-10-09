<?php
use core\modules\Route;

// Перечислим доступные страницы ошибок:
Route::add('/404', 'Site@get404Page');

// Страницы сайта
Route::add('/', 'Site@getMainPage');
Route::add('/{article}', 'Site@getArticlePage');

// роутинг API