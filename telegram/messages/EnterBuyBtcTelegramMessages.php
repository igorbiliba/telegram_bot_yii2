<?php

namespace app\telegram\messages;

use app\telegram\TelegramTypeButtons;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use yii\base\Component;
use yii\helpers\Html;

/**
 * @property string $error
 * @property string $message
 *
 * Class FirstTelegramMessages
 * @package app\telegram\messages
 */
class EnterBuyBtcTelegramMessages extends Component
{
    public function getError()
    {
        return \Yii::t('app', 'Такого адреса не существует. укажите корректный адрес BTC');
    }

    public function getMessage()
    {
        return \Yii::t('app', 'Укажите ваш адрес BTC');
    }
}