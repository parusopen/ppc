<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\Pagination;
use app\models\V_doctypes;

class V_doctypesController extends Controller
{
    public function actionIndex()
    {
        $query = V_doctypes::find();

        $pagination = new Pagination([
            'defaultPageSize' => 20,
            'totalCount' => $query->count(),
        ]);

        $v_doctypes = $query->orderBy('sdoccode')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'v_doctypes'=>$v_doctypes,
            'pagination' => $pagination,
        ]);
    }
}
