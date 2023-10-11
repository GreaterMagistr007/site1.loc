<?php

namespace core\modules;

use core\modules\repositories\SiteRepository;

final class Site
{
    protected SiteRepository $repository;

    public function __construct()
    {
        $this->repository = new SiteRepository(PARAMS['DB_DRIVER']);
    }

    const MAIN_PAGE_FILE = 'index.php';

    public function getMainPage()
    {
        dd(
            $this->repository->getTitle(),
            $this->repository->getDescription(),
            $this->repository->getKeywords(),
        );
        $variables = [
            'headerTags' => ''
        ];
        $this->render(self::MAIN_PAGE_FILE);
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
        try {
            include( template_dir(self::MAIN_PAGE_FILE) );
        } catch (\Exception $e) {

        }
    }


}