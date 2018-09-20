<?php
use yii\helpers\Html;
//use yii\widgets\LinkPager;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\V_doctypes;

use kartik\tree\TreeView;
use app\models\Products;

$this->title = 'Типы документов';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php
//echo TreeView::widget([
//    // single query fetch to render the tree
//    // use the Product model you have in the previous step
//    'query' => Product::find()->addOrderBy('root, lft'), 
//    'headingOptions' => ['label' => 'Categories'],
//    'fontAwesome' => false,     // optional
//    'isAdmin' => false,         // optional (toggle to enable admin mode)
//    'displayValue' => 1,        // initial display value
//    'softDelete' => true,       // defaults to true
//    'cacheSettings' => [        
//        'enableCache' => true   // defaults to true
//    ]
//]);

$dataProvider = new ActiveDataProvider([
    'query' => V_doctypes::find(),
    'pagination' => [
        'pageSize' => 20,
    ],
]);
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            //['class' => 'yii\grid\SerialColumn'],
            //'nrn',
            'sdoccode:ntext',
            'sdocname:ntext',
            ['class' => 'yii\grid\ActionColumn'],

            
        ],
]);
?>


