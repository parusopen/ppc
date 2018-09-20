<?php

namespace app\models;

use yii\db\ActiveRecord;

class V_doctypes extends ActiveRecord
{   
    
    
    public function attributeLabels()
    {
        return [
            'rn' => 'Рег.номер',
            'sdoccode' => 'Мнемокод',
            'sdocname' => 'Наименование',
        ];
    }
}  


