<?php

namespace core\modules;

/**
 * Класс проверяет работоспособность сайта
 * 1 - Генерируем статьи
 * 2 - запрашиваем этим самые статьи
 * 3 - сверяемся с тем, что нагенерировали
 */
class Test
{
    public function __construct()
    {
    }

    /**
     * @param int $count
     * @return array
     */
    private function generateArticles(int $count = 10): array
    {
        echo("Генерация статей: $count шт.");

        $articles = [];
        $datetime = date('Y-m-d H:i:s');

        for ($i = 0; $i < $count; $i++) {
            $params = [
                'title' => 'Статья ' . $datetime . ' - ' . $i,
            ];
            $articles[] = $params;
            $article = new Article($params);
            $article->save();
        }

        echo("Статьи созданы и сохранены");

        return $articles;
    }

    /**
     * @param array $articles
     * @return bool
     */
    private function isArticlesEqual(array $articles):bool
    {
        echo('Сверяем статьи');
        $createdArticles = Article::getNewArticles(count($articles));
        for ($i = 0; $i< count($articles); $i++) {
            foreach ($articles[$i] as $key => $value) {
                $index = count($articles) - 1 - $i;
                if ($createdArticles[$index]->$key !== $value) {
                    dd(
                        $createdArticles[$index],
                        $articles[$i]
                    );
                    throw new \DomainException("Значение $key не совпало!!! (" . $createdArticles[$index]->$key . " !== $value)");
                }
            }
        }
        echo "Сверка статей прошла успешно";
        return true;
    }

    /**
     * @param $articles
     * @return void
     */
    private function deleteArticles($articles):bool
    {
        echo('Удаляем статьи');
        foreach ($articles as $article) {
            $tmp = new Article($article);
            $chpu = $tmp->getChpu();
            unset($tmp);
            $createdArticle = Article::getByChpu($chpu);
            $createdArticle->delete();
        }
        echo('Статьи удалены');
        return true;
    }

    /**
     * @return bool
     */
    public function run():bool
    {
        try {
            $articlesCount = 3;
            $articles = $this->generateArticles($articlesCount);
            if (!$this->isArticlesEqual($articles)) {
                throw new \DomainException('Статьи не совпали', 500);
            }
            $this->deleteArticles($articles);
        } catch (\Exception $e) {
            dd([
                'Ошибка при выполнении тестов!!!',
                $e->getMessage(),
                $e->getCode()
            ]);
        }

        return true;
    }
}