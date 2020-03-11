<?php
namespace app\commands;

use app\components\FixerIoComponent;
use app\components\Graphene;
use app\components\Homer;
use app\components\CreateResponse;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Html;

class CommonController extends Controller
{
    public function actionRates()
    {
        $rates = (new FixerIoComponent())->rates;
        echo json_encode($rates);
    }

    public function actionGetbridgebtcrate()
    {
        echo (new Homer())->bridgeBtcRate;
    }

    public function actionCheckname($name="localcoin-wallet")
    {
        echo $name . ': ' . (new Graphene())->IsIssetAccount($name);
    }

    public function actionRate($bulletCurrency = 'LocalUSD')
    {
        $rates = (new FixerIoComponent())->rates;
        $rates['BTC'] = (new Homer())->bridgeBtcRate;

        $directByCurrency = array_flip(\Yii::$app->params['local_currencies']);
        $originCurrency = $directByCurrency[$bulletCurrency];
        $rate = $rates[$originCurrency];

        $message = \Yii::t('app', 'Курс обмена Qiwi->{to} = {rate} руб', [
                'to'   => $bulletCurrency,
                'rate' => $rate
            ]). PHP_EOL;
        $message .= Html::tag(
                'i',
                \Yii::t('app', 'Вы можете купить на сумму от {from} тыс. до 150 тыс. руб.', [ 'from' => \Yii::$app->params['sumFrom'] / 1000])
            ). PHP_EOL;
        $message .= '============================' . PHP_EOL;
        $message .= Html::tag('b', \Yii::t('app', 'Сколько вы хотите оплатить?') . PHP_EOL . \Yii::t('app', 'Введите сумму оплаты в рублях:'));

        echo $message;
    }
}
