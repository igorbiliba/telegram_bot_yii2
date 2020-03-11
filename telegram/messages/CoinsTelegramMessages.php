<?php

namespace app\telegram\messages;

use app\buy\BuyCurrencies;
use app\telegram\TelegramTypeButtons;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * @property string $message
 * @property ReplyKeyboardMarkup $buttons
 *
 * Class FirstTelegramMessages
 * @package app\telegram\messages
 */
class CoinsTelegramMessages extends Component
{
    public function getMessage()
    {
        return \Yii::t('app', 'Выберите валюту для покупки');
    }

    public function getSecondMessage()
    {
        return \Yii::t('app', 'Валюту которую хотите получить');
    }

    public function getButtons()
    {
        return new ReplyKeyboardMarkup([
            array_values((new BuyCurrencies())->allCurrencies),
            [\Yii::t('app', TelegramTypeButtons::RESET)]
        ], true, true, false);
    }

    public function getButtonsInline()
    {
        $buttons = [];
        foreach (array_values((new BuyCurrencies())->allCurrencies) as $coin)
            $buttons[] = ['text' => $coin, 'callback_data' => $coin];

        return new InlineKeyboardMarkup(
            [
                $buttons
            ]
        );
    }
}