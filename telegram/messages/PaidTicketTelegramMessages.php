<?php

namespace app\telegram\messages;

use app\buy\BuyCurrencies;
use app\components\CreateResponse;
use app\models\UserSession;
use app\telegram\TelegramTypeButtons;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use yii\base\Component;

/**
 * @property string $success
 * @property string $cancel
 * @property string $localMessage
 * @property string $btcMessage
 * @property ReplyKeyboardMarkup $buttons
 *
 * Class FirstTelegramMessages
 * @package app\telegram\messages
 */
class PaidTicketTelegramMessages extends Component
{
    public function getSuccess()
    {
        return \Yii::t('app', 'Заявка успешло оплачена');
    }

    public function getExistsNotPaid()
    {
        return \Yii::t('app', 'У вас висит неоплаченная заявка');
    }

    public function getCancel()
    {
        return \Yii::t('app', 'Заявка отменена');
    }
}