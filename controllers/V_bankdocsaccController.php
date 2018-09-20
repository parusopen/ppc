<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\V_bankdocsacc;

class V_bankdocsaccController extends Controller
{
    public function actionIndex()
    {
		if(isset($_POST['relation_item'])){
			echo V_bankdocsacc::getTreeRelationItems($_POST['relation_item']);
			exit;
		}
		
		if(isset($_POST['relation_item_params'])){
			echo V_bankdocsacc::getTreeRelationItemsParams($_POST['relation_item_params']);
			exit;
		}
		
		if(isset($_POST['modify'])){
			echo V_bankdocsacc::modifyObjects($_POST);
			exit;
		}
				
		$v_bankdocsacc = V_bankdocsacc::getTree(0);
		
		return $this->render('index', [
            'v_bankdocsacc'=>$v_bankdocsacc
        ]);
    }
}
