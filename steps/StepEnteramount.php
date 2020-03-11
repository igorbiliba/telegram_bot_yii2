<?php


namespace app\steps;


use app\buy\BuyCurrencies;
use app\helpers\LocalcoinBridgeHelper;
use app\models\UserSession;
use app\telegram\messages\EnterBuyAmountTelegramMessages;
use app\telegram\messages\EnterPhoneTelegramMessages;
use app\telegram\messages\ResetTelegramMessages;
use app\telegram\messages\YesNoTelegramMessages;
use TelegramBot\Api\Client;

class StepEnteramount
{
    /**
     * @param UserSession $userSession
     * @param string $request
     * @param Client $bot
     * @return array
     */
    public function process($userSession, $amount, $bot)
    {
        $amount = floatval($amount);
        $max = \Yii::$app->params['sumTo'];
        $min = \Yii::$app->params['sumFrom'];

        if($amount > $max)
            return [(new EnterBuyAmountTelegramMessages())->errorToBigger,
                    (new ResetTelegramMessages())->buttons];

        if($amount < $min)
            return [(new EnterBuyAmountTelegramMessages())->errorToLower,
                    (new ResetTelegramMessages())->buttons];

        $userSession->amount = $amount . '';
        $userSession->step   = UserSession::STEP_ENTER_AGREE_AMOUNT;
        $userSession->save();

        $rate = LocalcoinBridgeHelper::GetRateByCurrency($userSession->currency);

        return [(new EnterBuyAmountTelegramMessages([   'runAmount'      => $userSession->amount,
                                                        'currencyAmount' => $userSession->CalcCurrencyAmount($rate),
                                                        'bulletCurrency' => str_replace('LOCAL', 'Local', strtoupper($userSession->currency)),
                                                    ]))->messageTotalAmount,
                (new YesNoTelegramMessages())->buttons,

                (new EnterBuyAmountTelegramMessages())->youAgreeMessage,
                (new YesNoTelegramMessages())->buttonsInline,
            ];
    }
}