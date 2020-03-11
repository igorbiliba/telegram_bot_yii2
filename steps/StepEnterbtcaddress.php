<?php


namespace app\steps;


use app\components\Homer;
use app\components\CreateResponse;
use app\helpers\LocalcoinBridgeHelper;
use app\models\LogList;
use app\models\Ticket;
use app\models\UserSession;
use app\telegram\messages\BuyRecvisitsTelegramMessages;
use app\telegram\messages\EnterBuyBtcTelegramMessages;
use app\telegram\messages\ResetTelegramMessages;
use TelegramBot\Api\Client;

class StepEnterbtcaddress
{
    /**
     * @param UserSession $userSession
     * @param string $address
     * @param Client $bot
     * @return array
     */
    public function process($userSession, $address, $bot)
    {
        $address = trim($address);
        $Homer = new Homer();

        if(!$Homer->CheckAddressBTC($address))
            return [(new EnterBuyBtcTelegramMessages())->error,
                    (new ResetTelegramMessages())->buttons];

        try {
            $userSession->step     = UserSession::STEP_WAIT_PAID;
            $userSession->btc_addr = $address;
            $userSession->save();

            $this->SayAboutUpdateRate($userSession, $bot);
            $rate = LocalcoinBridgeHelper::GetRateByCurrency($userSession->currency);

            $this->SayAboutCreate($userSession, $bot);
            /** @var CreateResponse $createResponse */
            $createResponse = (new Homer())->CreateRecvisits($userSession, $rate);
            if(Ticket::CreateNewTicket($createResponse, $userSession) === null)
                throw new \Exception("not created Ticket::CreateNewTicket");

            return [(new BuyRecvisitsTelegramMessages(['createResponse' => $createResponse]))->message,
                    (new BuyRecvisitsTelegramMessages(['createResponse' => $createResponse]))->buttons];
        } catch (\Exception $e) { }

        return [(new ResetTelegramMessages())->error,
                (new ResetTelegramMessages())->buttons];
    }

    /*
     * @param UserSession $userSession
     * @param Client $bot
     */
    function SayAboutUpdateRate($userSession, $bot)
    {
        $bot->sendMessage(
            $userSession->id,
            (new BuyRecvisitsTelegramMessages())->messageUpdateRate,
            'html',
            false,
            null,
            (new ResetTelegramMessages())->buttons
        );
    }

    /*
     * @param UserSession $userSession
     * @param Client $bot
     */
    function SayAboutCreate($userSession, $bot)
    {
        $bot->sendMessage(
            $userSession->id,
            (new BuyRecvisitsTelegramMessages())->messageCreate,
            'html',
            false,
            null,
            (new ResetTelegramMessages())->buttons
        );
    }
}