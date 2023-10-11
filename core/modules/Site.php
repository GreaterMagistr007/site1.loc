<?php

namespace core\modules;

use core\modules\repositories\SiteRepository;

final class Site
{
    protected SiteRepository $repository;

    public string $title;
    public string $description;
    public string $keywords;

    public function __construct()
    {
        $this->repository = new SiteRepository(PARAMS['DB_DRIVER']);

        $this->title = $this->repository->getTitle();
        $this->description = $this->repository->getDescription();
        $this->keywords = $this->repository->getKeywords();
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
        $view = new View($template, $variables);
    }


}