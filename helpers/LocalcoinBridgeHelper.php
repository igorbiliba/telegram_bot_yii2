<?php
namespace app\helpers;

use app\buy\BuyCurrencies;
use app\components\FixerIoComponent;
use app\components\Homer;
use yii\base\Component;

class LocalcoinBridgeHelper extends Component
{
    public static function GetRateByCurrency($currency)
    {
        //local
        $rates = (new FixerIoComponent())->rates;
        foreach ($rates as $itemCurrency => &$rate) {
            if(in_array(trim(strtoupper($itemCurrency)), \Yii::$app->params['scalePercentExclude']))
                continue;

            $rate = round(floatval($rate) * (100 + \Yii::$app->params['scalepercentLocal']) / 100, 2);
        }

        //btc
        $rates['BTC'] = (new Homer())->bridgeBtcRate;
        $rates['BTC'] = round(floatval($rates['BTC']) * (100 + \Yii::$app->params['scalepercentBtc']) / 100, 2);

        $originCurrency = null;
        foreach ((new BuyCurrencies())->allCurrencies as $key => $value)
            if(strtolower(trim($currency)) === strtolower(trim($value)))
                $originCurrency = $key;

        return $rates[$originCurrency];
    }
}