<?php

namespace app\telegram\messages;

use app\buy\BuyCurrencies;
use app\components\CreateResponse;
use app\telegram\TelegramTypeButtons;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use yii\base\Component;

/**
 * @property ReplyKeyboardMarkup $buttons
 *
 * Class FirstTelegramMessages
 * @package app\telegram\messages
 */
class YesNoTelegramMessages extends Component
{
    public function getButtons()
    {
        return new ReplyKeyboardMarkup([
            [
                \Yii::t('app', TelegramTypeButtons::YES),
                \Yii::t('app', TelegramTypeButtons::NO)
            ]
        ], true, true, false);
    }

    public function getButtonsInline()
    {
        return new InlineKeyboardMarkup(
            [
                [
                    ['text' => \Yii::t('app', TelegramTypeButtons::YES), 'callback_data' => \Yii::t('app', TelegramTypeButtons::YES)],
                    ['text' => \Yii::t('app', TelegramTypeButtons::NO),  'callback_data' => \Yii::t('app', TelegramTypeButtons::NO) ],
                ]
            ]
        );
    }
}