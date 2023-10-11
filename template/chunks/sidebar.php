<?php
    $newArticles = \core\modules\Article::getNewArticles(4,0);
?>
<div class="contact-address span3">
    <h4>Новые статьи</h4>
    <?php foreach($newArticles as $article) { ?>
        <p>
            <a href="<?= $article->getHref() ?>">
                <?= $article->title ?>
            </a>
        </p>
    <?php } ?>
</div>