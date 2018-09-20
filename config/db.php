<?php

return [
	'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=192.168.1.70;port=5433;dbname=booktown',
    'username' => 'postgres',
    'password' => 'postgres',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];  
