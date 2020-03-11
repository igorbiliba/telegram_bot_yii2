<?php
namespace app\steps;


use app\buy\BuyCurrencies;
use app\helpers\LocalcoinBridgeHelper;
use app\models\UserSession;
use app\telegram\messages\CoinsTelegramMessages;
use app\telegram\messages\EnterBuyAmountTelegramMessages;
use app\telegram\messages\FirstTelegramMessages;
use app\telegram\messages\HelpTelegramMessages;
use app\telegram\messages\PartnerTelegramMessages;
use app\telegram\messages\ResetTelegramMessages;
use app\telegram\messages\SellTelegramMessages;
use app\telegram\TelegramTypeButtons;
use TelegramBot\Api\Client;

class StepFirst
{
    /**
     * @param UserSession $userSession
     * @param string $currency
     * @return array
     */
    function currency($userSession, $currency)
    {
        $userSession->step     = UserSession::STEP_ENTER_AMOUNT;
        $userSession->currency = strtolower(trim($currency));
        $userSession->save();

        return [(new EnterBuyAmountTelegramMessages([
                    'bulletCurrency' => $currency,
                    'rate'           => LocalcoinBridgeHelper::GetRateByCurrency($currency),
                    'min'            => \Yii::$app->params['sumFrom'],
                    'max'            => \Yii::$app->params['sumTo'],
                ]))->message,
                (new ResetTelegramMessages())->buttons];
    }

    /**
     * @param UserSession $userSession
     * @param string $request
     * @param Client $bot
     * @return array
     */
    public function Process($userSession, $request, $bot)
    {
        if((new BuyCurrencies())->ExistsCurrency($request))
            return $this->currency($userSession, $request);

        switch ($request)
        {
            case TelegramTypeButtons::BUY:
                return [(new CoinsTelegramMessages())->message,
                        (new CoinsTelegramMessages())->buttons,
                        (new CoinsTelegramMessages())->secondMessage,
                        (new CoinsTelegramMessages())->buttonsInline];
                break;
            case TelegramTypeButtons::SELL:
                return [(new SellTelegramMessages())->message,
                        (new ResetTelegramMessages())->buttons];
                break;
            case TelegramTypeButtons::HELP:
                return [(new HelpTelegramMessages())->message,
                        (new ResetTelegramMessages())->buttons];
                break;
            case TelegramTypeButtons::PARTNER:
                return [(new PartnerTelegramMessages())->message,
                        (new ResetTelegramMessages())->buttons];
                break;
        }

        return [(new FirstTelegramMessages())->hello,
                (new FirstTelegramMessages())->buttons,
                (new FirstTelegramMessages())->message,
                (new FirstTelegramMessages())->buttonsInline,];
    }
}