<?php
namespace app\commands;

use app\components\Homer;
use app\models\Ticket;
use app\telegram\messages\PaidTicketTelegramMessages;
use app\telegram\messages\ResetTelegramMessages;
use TelegramBot\Api\Client;
use yii\console\Controller;

class TicketController extends Controller
{
    public function actionCheckpaid()
    {
        $bot   = new Client(\Yii::$app->params['bot_key']);
        $homer = new Homer();

        $list = Ticket::find()
            ->where('created_at > :expire', [':expire' => time() - (Ticket::TICKET_EXPIRE_MINUTES * 60)])
            ->andWhere(['status' => Ticket::STATUS_WAIT])
            ->all();

        /** @var Ticket $ticket */
        foreach ($list as $ticket)
        {
            $isPaid = $homer->CheckIsPaid(($ticket->btc_address));
            if($isPaid)
            {
                $ticket->status = Ticket::STATUS_PAID;
                $ticket->save();
                $bot->sendMessage($ticket->user_session_id, (new PaidTicketTelegramMessages())->success, 'html', false, null, (new ResetTelegramMessages())->buttons);
            }
        }
    }

    public function actionCheckcancel()
    {
        $bot = new Client(\Yii::$app->params['bot_key']);

        $list = Ticket::find()
            ->where('created_at < :expire', [':expire' => time() - (Ticket::TICKET_EXPIRE_MINUTES * 60)])
            ->andWhere(['status' => Ticket::STATUS_WAIT])
            ->all();

        /** @var Ticket $ticket */
        foreach ($list as $ticket)
        {
            $ticket->status = Ticket::STATUS_CANCEL;
            $ticket->save();
            $bot->sendMessage($ticket->user_session_id, (new PaidTicketTelegramMessages())->cancel, 'html', false, null, (new ResetTelegramMessages())->buttons);
        }
    }
}
