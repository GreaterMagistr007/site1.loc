<?php

namespace core\modules;

use core\modules\repositories\SiteRepository;

final class Site
{
    protected SiteRepository $repository;

    public string $title;
    public string $description;
    public string $keywords;
    public string $lang; // Локализация сайта

    public function __construct()
    {
        $this->repository = new SiteRepository(PARAMS['DB_DRIVER']);

        $this->title = $this->repository->title;
        $this->description = $this->repository->description;
        $this->keywords = $this->repository->keywords;
        $this->lang = $this->repository->lang;
    }

    const MAIN_PAGE_FILE = 'index';

    public function getMainPage()
    {
        $variables = [
            'site' => $this
        ];
        $this->render(self::MAIN_PAGE_FILE, $variables);
    }

    public function getArticlePage()
    {
        dd('Страница статьи');
    }

    public function get404Page()
    {
        dd('Страница 404');
    }

    private function render(string $template = '', array $variables = [])
    {
        render($template, $variables);
    }


}