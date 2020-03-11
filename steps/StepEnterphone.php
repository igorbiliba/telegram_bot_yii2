<?php


namespace app\steps;


use app\buy\BuyCurrencies;
use app\helpers\PhoneHelper;
use app\models\UserSession;
use app\telegram\messages\EnterBuyBtcTelegramMessages;
use app\telegram\messages\EnterLLCAccountTelegramMessages;
use app\telegram\messages\EnterPhoneTelegramMessages;
use app\telegram\messages\ResetTelegramMessages;
use TelegramBot\Api\Client;

class StepEnterphone
{
    /**
     * @param UserSession $userSession
     * @param string $phone
     * @param Client $bot
     * @return array
     */
    public function process($userSession, $phone, $bot)
    {
        if(!PhoneHelper::CheckPhone($phone))
        {
            return [(new EnterPhoneTelegramMessages())->error,
                    (new ResetTelegramMessages())->buttons];
        }
        $userSession->phone = $phone . '';

        $buyCurrencies = new BuyCurrencies();
        if($buyCurrencies->ExistsCurrency($userSession->currency, $buyCurrencies->localCurrencies))
        {
            $userSession->step = UserSession::STEP_ENTER_LOCALCOIN_ACCOUNT;
            $userSession->save();
            return [(new EnterLLCAccountTelegramMessages())->message,
                    (new ResetTelegramMessages())->buttons];
        }

        if(trim(strtolower($userSession->currency)) === 'btc') {
            $userSession->step = UserSession::STEP_ENTER_BTC_ADDRESS;
            $userSession->save();
            return [(new EnterBuyBtcTelegramMessages())->message,
                    (new ResetTelegramMessages())->buttons];
        }

        return [(new ResetTelegramMessages())->message,
                (new ResetTelegramMessages())->buttons];
    }
}