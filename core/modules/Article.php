<?php

namespace core\modules;

use core\modules\repositories\ArticleRepository;

class Article
{
    protected static $repository = null;

    /**
     * @return ArticleRepository
     */
    protected static function getRepository()
    {
        if (!self::$repository) {
            self::$repository = new ArticleRepository(PARAMS['DB_DRIVER']);
        }

        return self::$repository;
    }

    public static function getNewArticles($count = 5, $offset = 0)
    {
        return self::getRepository()->getNewArticles($count, $offset);
    }

    public function __construct($params = [])
    {
        foreach ($params as $key => $param) {
            $this->$key = $param;
        }
    }

    public function getHref()
    {
        return href('/' . $this->id);
    }
}