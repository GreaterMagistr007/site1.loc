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
            self::$repository = new ArticleRepository(env('DB_DRIVER'));
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

    /**
     * @return string
     */
    public function getHref():string
    {
        return href('/' . $this->getChpu());
    }

    /**
     * @return string
     */
    public function getChpu():string
    {
        if (!isset($this->chpu) || !$this->chpu) {
            $this->chpu = translit($this->title);
        }

        return $this->chpu;
    }

    /**
     * @param int $id
     * @return Article|null
     */
    public static function getById(int $id):Article|null
    {
        return self::getRepository()->getById($id);
    }

    /**
     * @param string $chpu
     * @return Article|null
     */
    public static function getByChpu(string $chpu):Article|null
    {
        return self::getRepository()->getArticleByChpu($chpu);
    }

    /**
     * @return void
     */
    public function save():void
    {
        $id = self::getRepository()->save($this);
        $newArticle = self::getById($id);

        foreach (get_object_vars($newArticle) as $key => $value) {
            if (!isset($this->$key) || $this->$key !== $value) {
                $this->$key = $value;
            }
        }

        unset($newArticle);
    }

    /**
     * @return void
     */
    public function delete():void
    {
        self::getRepository()->delete($this);
    }
}