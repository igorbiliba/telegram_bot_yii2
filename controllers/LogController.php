<?php
namespace app\controllers;

use app\helpers\LocalcoinBridgeHelper;
use app\models\LogList;
use app\models\Ticket;
use app\models\UserSession;
use yii\web\Controller;

class LogController extends Controller
{
    public function actionIndex()
    {
        $data = [
            'btcRate' => LocalcoinBridgeHelper::GetRateByCurrency('BTC'),
            'User sessions cnt' => UserSession::find()->count(),
            'User sessions' => UserSession::find()->asArray()->all(),
            'Tickets' => Ticket::find()->asArray()->all(),
        ];

        echo '<pre>';
        print_r($data);

        print_r(LogList::find()->asArray()->all());
        die();
    }

    public function actionReset()
    {
        echo "delete: " . LogList::deleteAll([]);
        die();
    }
}