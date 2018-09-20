<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\Pagination;
use app\models\V_doctypes;

class ProductController extends \yii\web\Controller {
    public function actionIndex()
    {
        return $this->render('index');
    }
}
?>