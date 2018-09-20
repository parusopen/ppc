<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\Pagination;
use app\models\Books;

class BooksController extends Controller
{
    public function actionIndex()
    {
        $query = Books::find();

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);

        $books = $query->orderBy('title')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'books' => $books,
            'pagination' => $pagination,
        ]);
    }
}
