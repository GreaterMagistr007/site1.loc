<?php
// Настройки сайта:
const PARAMS = [
    'DEBUG' => true,

    // Настройки базы данных
    // file - JSON файлы в директории data
    // mysql - база данных. Для него потребуется заполнить настройки подключения к БД
    'DB_DRIVER' => 'file', // file - JSON файл в директории data

    // токен сайта для обращения к серверу по api
    'token' => 'token',
    'serverUrl' => 'http://server.loc'
];
