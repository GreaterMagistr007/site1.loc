<?php

/**
 * Работа со статьями происходит в файлах  /data/articles[$id_start-$id_end].json
 * где id_start и id_end - это разброс id статей, которые хранятся конкретно в этом файле
 *
 * Чтобы получить доступ по ЧПУ к идентификатору статьи, нужно обратиться в файл articles_chpu_to_id.json
 * В нем хранится объект соответствий ЧПУ (Человеко Понятный УРЛ) к идентификатору статьи
 *
 */


namespace core\modules\repositories;

use core\modules\Article;

class ArticleRepository extends AbstractRepository
{
    /**
     * Список соответствий ЧПУ к ID для абсолютно всех статей
     * @var array
     */
    private array $chpuToId = [];


    private int $maxId = 0;

    /** Количество статей в одном файле */
    const ARTICLES_PER_FILE = 10;

    public function __construct(string $driver)
    {
        parent::__construct($driver);
        $this->chpuToId = $this->readDataFile('articles_chpu_to_id', true);
        $this->getMaxId();
    }

    /**
     * @param $count
     * @param $offset
     * @return array of Article
     */
    public function getNewArticles($count = 5, $offset = 0)
    {
        $result = [];

        $currentId = $this->getMaxId();
        while (count($result) < $count && $currentId > 0) {
            if ($offset > 0) {
                $offset -= 1;
                $currentId -= 1;
                continue;
            }
            $article = $this->getById($currentId);
            if ($article) {
                $result[] = $article;
            }
            $currentId -= 1;
        }

        return $result;
    }

    /**
     * Id последней статьи
     * @return int
     */
    private function getMaxId()
    {
        if (!$this->maxId) {
            $this->maxId = max($this->chpuToId);
        }
        return $this->maxId;
    }

    /**
     * @param string $chpu
     * @return Article|null
     */
    public function getArticleByChpu(string $chpu)
    {
        $id = $this->getIdByChpu($chpu);
        if (!$id) {
            return null;
        }

        return $this->getById($id);
    }

    /**
     * @param int $id
     * @return Article|null
     */
    public function getById(int $id)
    {
        if ($id < 1) {
            return null;
        }

        $articlesInFile = $this->readDataFile($this->getArticlesFileNameById($id), true);

        return isset($articlesInFile[$id]) ? new Article($articlesInFile[$id]) : null;
    }

    /**
     * @param string $chpu
     * @return int|false
     */
    private function getIdByChpu(string $chpu)
    {
        if (!($chpu) || strlen($chpu) < 1 || strlen($chpu) > 255) {
            return false;
        }
        return isset($this->chpuToId[$chpu]) ? $this->chpuToId[$chpu] : false;
    }

    /**
     * @param int $id
     * @return int
     */
    private function getFileIndexById(int $id)
    {
        return intdiv($id, self::ARTICLES_PER_FILE) + 1;
    }

    private function getArticlesFileNameById(int $id)
    {
        return 'articles' . $this->getFileIndexById($id);
    }

    /**
     * @param Article $article
     * @return void
     */
    public function save(Article $article)
    {
        $newArticle = new \stdClass;
        foreach (get_object_vars($article) as $key => $value) {
            $newArticle->$key = $value;
        }

        // Назначим CHPU
        if (!isset($newArticle->chpu) || !is_string($newArticle->chpu) || strlen($newArticle->chpu) < 2) {
            $newArticle->chpu = translit($newArticle->title);
        }
        // Назначим Id
        if (!isset($newArticle->id)) {
            $newArticle->id = $this->getMaxId() + 1;
        }

        $oldArticle = $this->getById($newArticle->id);

        $needSave = false;
        if (!$oldArticle) {
            // Статьи такой еще не было
            while ($this->getArticleByChpu($newArticle->chpu)) {
                // такое ЧПУ уже есть, нужно придумать уникальное:
                $newArticle->chpu .=  $newArticle->id;
            }

            $this->chpuToId[$newArticle->chpu] = $newArticle->id;
            $needSave = true;
        } else {
            // Статья была, нужно исправить chpu
            if ($oldArticle->chpu !== $newArticle->chpu) {
                while ($this->getArticleByChpu($newArticle->chpu)) {
                    // такое ЧПУ уже есть, нужно придумать уникальное:
                    $newArticle->chpu .=  $newArticle->id;
                }
                unset($this->chpuToId[$oldArticle->chpu]);
                $this->chpuToId[$newArticle->chpu] = $newArticle->id;
                $needSave = true;
            }
        }
        // Сохраним список ChpuToId :
        if ($needSave) {
            $this->saveToFile('articles_chpu_to_id', $this->chpuToId);
        }

        // Теперь сохраним саму статью в нужный файл:
        $fileName = $this->getArticlesFileNameById($newArticle->id);

        $data = $this->readDataFile($fileName, true);
        $data[$newArticle->id] = $newArticle;

        $this->saveToFile($fileName, $data);
    }

    /**
     * @param Article $article
     * @return void
     */
    public function delete(Article $article)
    {
        // Удалим из файла
        $fileName = $this->getArticlesFileNameById($article->id);
        $data = $this->readDataFile($fileName, true);
        unset($data[$article->id]);
        $this->saveToFile($fileName, $data);

        // Удалим из реестра
        unset($this->chpuToId[$article->chpu]);
        $this->saveToFile('articles_chpu_to_id', $this->chpuToId);
    }
}