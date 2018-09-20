<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<h1>Книги</h1>
<ul>
<?php foreach ($books as $book): ?>
    <li>
        <?= Html::encode("{$book->id} {$book->title} {$book->author_id}") ?>
    </li>
<?php endforeach; ?>
</ul>

<?= LinkPager::widget(['pagination' => $pagination]) ?>
