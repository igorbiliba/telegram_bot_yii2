<?php

return [
    'fixer_key' => '****',
    'bot_key' => YII_ENV === 'prod' ? require(__DIR__ . '/bot_key_prod.php') : require(__DIR__ . '/bot_key_dev.php'),
    'sumFrom' => 5000,
    'sumTo' => 150000,
    'scalepercentBtc' => 3,
    'scalepercentLocal' => 5,
    'scalePercentExclude' => ['RUB'],
    'qiwi_user' => 'ninetor',
    'exchange_host' => 'https://****:8088',
    'allow_ip_bot_prod' => [
        '****',
        '****',
        '****',
        '****'
    ]
];
