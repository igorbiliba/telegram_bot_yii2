<?php

namespace app\telegram\messages;

use app\telegram\TelegramTypeButtons;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use yii\base\Component;
use yii\helpers\Html;

/**
 * @property string $errorNotFound
 * @property string $message
 *
 * Class FirstTelegramMessages
 * @package app\telegram\messages
 */
class EnterLLCAccountTelegramMessages extends Component
{
    public $account;

    public function getErrorNotFound()
    {
        return \Yii::t(
            'app',
            'Аккаунт "{account}" не был найден, введите правильный аккаунт',
            [ 'account' => $this->account ]
        );
    }

    public function getMessage()
    {
        return \Yii::t('app', 'Укажите свой LocalCoin аккаунт');
    }
}