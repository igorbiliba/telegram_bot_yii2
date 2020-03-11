<?php
$force = isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] === '--force';

$botUrl = file_exists(__DIR__.'/../is_dev')
    ? "https://bot.n3.by/hwercg6gf0olgfkdrtdvkr354wdghdrw3127hgg"
    : "https://buycoinbot.com/hwercg6gf0olgfkdrtdvkr354wdghdrw3127hgg";

const MIN_PENGING_TRANSACTIONS = 5;

function Request($params) {
    $bot_token = file_exists(__DIR__ . '/../is_dev')
        ? require(__DIR__ . '/../config/bot_key_dev.php')
        : require(__DIR__ . '/../config/bot_key_prod.php');

    $url = "https://api.telegram.org/bot".$bot_token."/" . $params;
    $json = file_get_contents($url);
    return json_decode($json, true);
}

//1. Проверяем есть ли pending transactions
if(!$force) {
    if(intval(Request("getWebhookInfo")["result"]["pending_update_count"]) < MIN_PENGING_TRANSACTIONS) {
        echo "Pending transactions has been not found";
        return;
    }
}

//2. Удалим вебхук
Request("setWebhook?url=");

try {
    //3. Получим зависшие транзакции и обновим их
    $freezesIds = [];
    foreach(Request("getUpdates")["result"] as $item)
        Request("getUpdates?offset=" . $item["update_id"]);
} catch(\Exception $ex) {}

//4. Вернем вебхук
$setWH = Request("setWebhook?url=" . $botUrl);
print_r($setWH);