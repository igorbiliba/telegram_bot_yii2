<?php

namespace app\telegram\messages;

use app\telegram\TelegramTypeButtons;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use yii\base\Component;

/**
 * @property string $message
 * @property string $error
 * @property ReplyKeyboardMarkup $buttons
 *
 * Class FirstTelegramMessages
 * @package app\telegram\messages
 */
class ResetTelegramMessages extends Component
{
    public function getMessage()
    {
        return \Yii::t('app', '');
    }

    public function getError()
    {
        return \Yii::t('app', 'Что-то пошло не так, повторите операцию');
    }

    public function getButtons()
    {
        return new ReplyKeyboardMarkup([
            [
                \Yii::t('app', TelegramTypeButtons::RESET)
            ]
        ], true, true, false);
    }
}