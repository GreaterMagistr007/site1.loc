<?php

namespace core\modules;

use core\modules\repositories\SiteRepository;

final class Site
{
    protected SiteRepository $repository;

    public string $title;
    public string $description;
    public string $keywords;
    public string $lang; // язык сайта
    public string $image; // Картинка сайта, для open graphics
    public string $locale; // Локализация сайта
    public string $logo; // Адрес логотипа сайта

    public function __construct()
    {
        $this->repository = new SiteRepository(PARAMS['DB_DRIVER']);

        $this->title = $this->repository->title;
        $this->description = $this->repository->description;
        $this->keywords = $this->repository->keywords;
        $this->lang = $this->repository->lang;
        $this->image = $this->repository->image;
        $this->metrics = $this->repository->metrics;
        $this->locale = $this->repository->locale;
        $this->logo = $this->repository->logo;
    }

    const MAIN_PAGE_FILE = 'index';

    public function getMainPage()
    {
        $variables = [
            'site' => $this,
            'title' => 'Главная',
            'description' => $this->description,
            'keywords' => $this->keywords,
            'image' => $this->image,
        ];
        $variables['headBlocks'] = $this->getSiteHeadBlocks($variables);
        $this->render(self::MAIN_PAGE_FILE, $variables);
    }

    public function getSiteHeadBlocks(array $variables)
    {
        $request = request();
        $pageTitle = replace_quotes($variables['title'] . ' | ' . $this->title);

        $result = [];

        $result[] = "<title>$pageTitle</title>";
        $result[] = "<meta name='title' content='$pageTitle'>";
        $result[] = "<meta name='owner' content='" . $request::host() . "' />";
        $result[] = "<meta name='author' lang='ru' content='" . $request::host() . "' />";
        $result[] = "<meta name='og:locale' content='" . $this->locale . "'>";
        $result[] = "<meta name='og:type' content='website'>";
        $result[] = "<meta name='description' content='" . replace_quotes($variables['description']) . "'>";
        $result[] = "<meta name='keywords' content='" . replace_quotes($variables['keywords']) . "'>";
        $result[] = "<link rel='shortlinkUrl' href='" . replace_quotes($request->uri()) . "'>";
        $result[] = "<meta property='og:site_name' content='" . replace_quotes($this->title) . "'>";
        $result[] = "<meta property='og:title' content='$pageTitle'>";
        $result[] = "<meta property='og:description' content='" . replace_quotes($variables['description']) . "'>";

        if ($variables['image']) {
            $result[] = "<meta property='og:image' content='" . replace_quotes(href($variables['image'])) . "'>";
            $result[] = "<link rel='image_src' href='" . replace_quotes(href($variables['image'])) . "'>";
        }

        $result[] = "\r\n\r\n" . $this->metrics;

        return implode($result);
    }

    public function __get(string $name)
    {
        return isset($this->$name) ? $this->$name : $this->repository->$name;
    }

    function save()
    {
        foreach (get_object_vars($this) as $key => $value) {
            $this->repository->$key = $value;
        }

        $this->repository->save();
    }

    public function __set(string $name, $value): void
    {
        $this->$name = $value;
        $this->repository->$name = $value;
        $this->repository->save();
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