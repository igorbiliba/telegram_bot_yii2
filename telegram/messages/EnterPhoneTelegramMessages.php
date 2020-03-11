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
class EnterPhoneTelegramMessages extends Component
{
    public function getError()
    {
        return \Yii::t('app', 'Указанный вами номер не соответствует формату.' . PHP_EOL . 
            'Номер не должен содержать пробелов, скобок или тире.' . PHP_EOL . 
            'Номер должен начинаться с кода страны:' . PHP_EOL . 
            '+7... для России🇷🇺' . PHP_EOL . 
            '+77... для Казахстана🇰🇿' . PHP_EOL . 
            '+373...для Молдовы🇲🇩' . PHP_EOL . 
            '+380... для Украины🇺🇦' . PHP_EOL . 
            '+44... для Великобритании🇬🇧' . PHP_EOL . 
            'и тд.');
    }

    public function getMessage()
    {
        return \Yii::t('app', 'Укажите номер, c которого будет сделана оплата?' . PHP_EOL . 
            'Номер должен начинаться с кода страны:' . PHP_EOL . 
            '+7... для России🇷🇺' . PHP_EOL . 
            '+77... для Казахстана🇰🇿' . PHP_EOL . 
            '+373...для Молдовы🇲🇩' . PHP_EOL . 
            '+380... для Украины🇺🇦' . PHP_EOL . 
            '+44... для Великобритании🇬🇧' . PHP_EOL . 
            'и тд.' . PHP_EOL . 
            'Номер не должен содержать пробелов, скобок или тире.');
    }
}