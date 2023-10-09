<?php

namespace core\modules;

class Site
{
    const MAIN_PAGE_FILE = 'index.php';

    public function __construct()
    {

    }

    public function getMainPage()
    {
        try {
            include( template_dir(self::MAIN_PAGE_FILE) );
        } catch (\Exception $e) {
            dd('Ошибка при подключении главной страницы ' . template_dir(self::MAIN_PAGE_FILE) . ':');
        }

    }

    public function getArticlePage()
    {
        dd('Страница статьи');
    }

    public function get404Page()
    {
        dd('Страница 404');
    }
}