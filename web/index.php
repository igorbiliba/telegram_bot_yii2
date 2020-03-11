<?php
defined('YII_DEBUG') or define('YII_DEBUG', __DIR__ . '/../is_dev');
defined('YII_ENV')   or define('YII_ENV',   __DIR__ . '/../is_dev' ? 'dev' : 'prod');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();