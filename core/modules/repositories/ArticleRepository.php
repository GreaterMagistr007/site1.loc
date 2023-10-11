<?php

namespace core\modules\repositories;

use core\modules\Article;

class ArticleRepository extends AbstractRepository
{
    public array $articles;

    public function __construct(string $driver)
    {
        parent::__construct($driver);
        $articles = $this->readDataFile('articles', true);

        foreach ($articles as $article) {
            $this->articles[$article['id']] = new Article($article);
        }
    }

    public function getNewArticles($count = 5, $offset = 0)
    {
        $result = [];

        $key = array_key_last($this->articles);

        while (count($result) < $count && $key > 0) {
            if ($offset > 0) {
                $offset -= 1;
                $key -= 1;
                continue;
            }
            if (isset($this->articles[$key])) {
                $result[] = &$this->articles[$key];
            }
            $key -= 1;
        }

        return $result;
    }
}