<?php


namespace app\steps;


use app\buy\BuyCurrencies;
use app\models\UserSession;
use app\telegram\messages\EnterBuyAmountTelegramMessages;
use app\telegram\messages\EnterPhoneTelegramMessages;
use app\telegram\messages\ResetTelegramMessages;
use TelegramBot\Api\Client;

class StepEnteragreeamount
{
    /**
     * @param UserSession $userSession
     * @param string $yesNo
     * @param Client $bot
     * @return array
     */
    public function process($userSession, $yesNo, $bot)
    {
        $userSession->step = UserSession::STEP_ENTER_PHONE;
        $userSession->save();

        return [(new EnterPhoneTelegramMessages())->message,
                (new ResetTelegramMessages())->buttons];
    }
}