<?php

namespace app\telegram\messages;

use app\telegram\TelegramTypeButtons;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use yii\base\Component;

/**
 * @property string $message
 *
 * Class FirstTelegramMessages
 * @package app\telegram\messages
 */
class PartnerTelegramMessages extends Component
{
    public function getMessage()
    {
        return \Yii::t('app', 'Функционал в разработке. На текущий момент мы не покупаем крипту. Следите за новостями в нашей группе @BuyCoinChat.');
    }
}