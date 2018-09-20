<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\Pagination;
use app\models\V_acatalog;

class V_acatalogController extends Controller
{
    public function actionIndex()
    {
        $query = V_acatalog::find();

        $pagination = new Pagination([
            'defaultPageSize' => 20,
            'totalCount' => $query->count(),
        ]);

        $v_acatalog = $query->orderBy('unitcode')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'v_doctypes'=>$v_acatalog,
            'pagination' => $pagination,
        ]);
    }
}
