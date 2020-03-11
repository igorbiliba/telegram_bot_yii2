<?php


namespace app\steps;


use app\models\UserSession;
use app\telegram\messages\ResetTelegramMessages;
use TelegramBot\Api\Client;

class StepWaitpaid
{
    /**
     * @param UserSession $userSession
     * @param string $request
     * @param Client $bot
     * @return array
     */
    public function process($userSession, $request, $bot)
    {
        return [(new ResetTelegramMessages())->message,
                (new ResetTelegramMessages())->buttons];
    }
}