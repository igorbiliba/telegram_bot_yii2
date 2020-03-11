<?php


namespace app\steps;


use app\components\ExchangeBotLLC;
use app\components\Graphene;
use app\components\Homer;
use app\helpers\LocalcoinBridgeHelper;
use app\models\LogList;
use app\models\Ticket;
use app\models\UserSession;
use app\telegram\messages\BuyRecvisitsTelegramMessages;
use app\telegram\messages\EnterLLCAccountTelegramMessages;
use app\telegram\messages\ResetTelegramMessages;
use TelegramBot\Api\Client;

class StepEnterlocalcoinaccount
{
    /**
     * @param UserSession $userSession
     * @param string $account
     * @param Client $bot
     * @return array
     */
    public function process($userSession, $account, $bot)
    {
        try {
            if(!(new Graphene())->IsIssetAccount(trim($account)))
                return [(new EnterLLCAccountTelegramMessages(['account' => trim($account)]))->errorNotFound,
                        (new ResetTelegramMessages())->buttons];

            $userSession->step        = UserSession::STEP_WAIT_PAID;
            $userSession->llc_account = trim($account.'');
            $userSession->save();

            $this->SayAboutUpdateRate($userSession, $bot);
            $currencyRate   = LocalcoinBridgeHelper::GetRateByCurrency($userSession->currency);
            $btcRate        = (new Homer())->bridgeBtcRate;

            $this->SayAboutCreate($userSession, $bot);
            $createResponse = (new ExchangeBotLLC())->CreateRecvisits($userSession, $currencyRate, $btcRate);

            if(Ticket::CreateNewTicket($createResponse, $userSession) === null)
                throw new \Exception("not created Ticket::CreateNewTicket");

            return [(new BuyRecvisitsTelegramMessages(['createResponse' => $createResponse]))->message,
                    (new BuyRecvisitsTelegramMessages(['createResponse' => $createResponse]))->buttons];
        } catch (\Exception $e) {
            (new LogList(['data' => $e->getMessage()]))->save();
        }

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