<?php

namespace app\models;

use yii\db\ActiveRecord;

class V_acatalog extends ActiveRecord
{   
//    use \kartik\tree\models\TreeTrait {
//        isDisabled as parentIsDisabled; // note the alias
//    }
    
    public static function tableName()
    {
        return 'v_acatalog';
    }
    
    public function attributeLabels()
    {
        return [
            'rn' => 'Рег.номер',
            'crn' => 'Род.номер',
            'unitcode' => 'Раздел',
            'NAME' => 'Наименование',
        ];
    }
}  


