<?php
return \yii\helpers\ArrayHelper::merge(
    [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=****',
        'username' => '****',
        'password' => '****',
        'charset' => 'utf8',
    ],
    file_exists(__DIR__ . '/db-local.php') ? require(__DIR__ . '/db-local.php') : []
);
