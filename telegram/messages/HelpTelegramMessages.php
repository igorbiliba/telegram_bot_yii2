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
class HelpTelegramMessages extends Component
{
    public function getMessage()
    {
        return \Yii::t('app', 'Чтобы получить помощь, жми.') . PHP_EOL."@BuyCoinChat";
    }
}