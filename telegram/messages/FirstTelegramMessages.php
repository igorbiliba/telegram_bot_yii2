<?php

namespace app\telegram\messages;

use app\telegram\TelegramTypeButtons;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use yii\base\Component;

/**
 * @property ReplyKeyboardMarkup $buttons
 * @property InlineKeyboardMarkup $buttonsInline
 * @property string $message
 * @property string $hello
 *
 * Class FirstTelegramMessages
 * @package app\telegram\messages
 */
class FirstTelegramMessages extends Component
{
    public function getMessage()
    {
        return \Yii::t('app', 'Выберите что хотите сделать');
    }

    public function getHello()
    {
        return \Yii::t('app', 'Добрый день!');
    }

    public function getButtons()
    {
        return new ReplyKeyboardMarkup([
            [
                \Yii::t('app', TelegramTypeButtons::BUY),
                \Yii::t('app', TelegramTypeButtons::SELL),
                \Yii::t('app', TelegramTypeButtons::HELP),
            ],
            [
                \Yii::t('app', TelegramTypeButtons::PARTNER),
            ]
        ], true, true, false);
    }

    public function getButtonsInline()
    {
        return new InlineKeyboardMarkup(
            [
                [
                    ['text' => \Yii::t('app', TelegramTypeButtons::BUY),  'callback_data' => \Yii::t('app', TelegramTypeButtons::BUY)],
                    ['text' => \Yii::t('app', TelegramTypeButtons::SELL), 'callback_data' => \Yii::t('app', TelegramTypeButtons::SELL)],
                ]
            ]
        );
    }
}