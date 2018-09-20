<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\V_tree;

class V_treeController extends Controller
{
    public function actionIndex()
    {
		if(isset($_POST['relation_item'])){
			echo V_tree::getTreeRelationItems($_POST['relation_item']);
			exit;
		}
		
		if(isset($_POST['relation_item_params'])){
			echo V_tree::getTreeRelationItemsParams($_POST['relation_item_params']);
			exit;
		}
		
		if(isset($_POST['modify'])){
			echo V_tree::modifyObjects($_POST);
			exit;
		}
				
		$v_tree = V_tree::getTree(0);
		
		return $this->render('index', [
            'v_tree'=>$v_tree
        ]);
    }
}
