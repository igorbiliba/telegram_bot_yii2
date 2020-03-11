<?php
namespace app\controllers;
use app\models\LogList;
use app\models\UserSession;
use app\components\TelegramComponent;
use app\telegram\messages\BuyRecvisitsTelegramMessages;
use app\telegram\messages\PaidTicketTelegramMessages;
use app\telegram\messages\ResetTelegramMessages;
use app\telegram\TelegramTypeButtons;
use TelegramBot\Api\Client;
use yii\web\Controller;

class BotController extends Controller
{
    function IsBot() {
        foreach(\Yii::$app->params['allow_ip_bot_prod'] as $ip)
            if(strpos($_SERVER["REMOTE_ADDR"]."", $ip) === 0)
                return true;

        return false;
    }

    public function beforeAction($action)
    {
        if(YII_ENV == 'prod' && !$this->IsBot())
        {
            echo 'not allowed ip';
            die();
        }

        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * @param UserSession $userSession
     * @param string $request
     * @param Client $bot
     */
    function InvokeStep($userSession, $request, $bot)
    {
        $className = '\app\steps\Step' . ucfirst(strtolower($userSession->step));
        return (new $className())->process($userSession, $request, $bot);
    }

    /**
     * Точка входа бота
     */
    public function actionCommand()
    {
        $bot         = new Client(\Yii::$app->params['bot_key']);
        $body        = json_decode($bot->getRawBody(), true);
        $userSession = UserSession::FindOrCreate(!empty($body['callback_query']) ? $body['callback_query']['message']['chat']['id'] : $body['message']['chat']['id']);

        try {
            (new LogList(['data' => json_encode($body)]))->save();

            $request  = isset($body['callback_query']) && isset($body['callback_query']['data'])
                ? $body['callback_query']['data']
                : $body['message']['text'];

            if($userSession->ExistsWaitTickets() && $request === TelegramTypeButtons::BUY)
            {
                $this->SendWaitPaidMessage($bot, $userSession);
                return false;
            }

            if($request === TelegramTypeButtons::RESET || $request === TelegramTypeButtons::NO)
                $userSession->Reset();

            $response = $this->InvokeStep($userSession, $request, $bot);
            $bot->sendMessage($userSession->id, $response[0], 'html', false, null, $response[1]);
            if(!empty($response[2]) && !empty($response[3]))
                $bot->sendMessage($userSession->id, $response[2], 'html', false, null, $response[3]);
        } catch (\Exception $exception) {
            $this->SetError($exception);
            $this->SendErrorMessage($bot, $userSession);
        }

        return false;
    }

    function SendWaitPaidMessage($bot, $userSession)
    {
        $bot->sendMessage(
            $userSession->id,
            (new PaidTicketTelegramMessages())->existsNotPaid,
            'html',
            false,
            null,
            (new ResetTelegramMessages())->buttons
        );
    }

    function SetError(\Exception $exception) {
        (new LogList(['data' => $exception->getMessage()]))->save();
    }

    /**
     * @param UserSession $userSession
     * @param Client $bot
     */
    function SendErrorMessage($bot, $userSession)
    {
        $bot->sendMessage(
            $userSession->id,
            (new ResetTelegramMessages())->error,
            'html',
            false,
            null,
            (new ResetTelegramMessages())->buttons
        );
    }
}