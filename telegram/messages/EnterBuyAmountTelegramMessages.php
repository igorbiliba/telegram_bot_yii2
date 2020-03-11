<?php

namespace app\telegram\messages;

use app\telegram\TelegramTypeButtons;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use yii\base\Component;
use yii\helpers\Html;

/**
 * @property string $errorToBigger
 * @property string $errorToLower
 * @property string $message
 * @property string $messageTotalAmount
 *
 * Class FirstTelegramMessages
 * @package app\telegram\messages
 */
class EnterBuyAmountTelegramMessages extends Component
{
    public $bulletCurrency;
    public $rate;
    public $max;
    public $min;
    public $runAmount;
    public $currencyAmount;

    public function getErrorToBigger()
    {
        return \Yii::t('app', 'Сумма слишком большая, введите правильную сумму');
    }

    public function getErrorToLower()
    {
        return \Yii::t('app', 'Сумма слишком маленькая, введите правильную сумму');
    }

    public function getMessage()
    {
        $message = \Yii::t('app', 'Курс обмена Qiwi->{to} = {rate} руб', [
                'to'   => $this->bulletCurrency,
                'rate' => $this->rate
            ]). PHP_EOL;

        $message .= Html::tag(
                'i',
                \Yii::t('app', 'Вы можете купить на сумму от {from} тыс. до {to} тыс. руб.', [
                    'from' => $this->min / 1000,
                    'to' => $this->max / 1000,
                ])
            ). PHP_EOL;

        $message .= '============================' . PHP_EOL;

        $message .= Html::tag('b', \Yii::t('app', 'Сколько вы хотите оплатить?') . PHP_EOL
            . \Yii::t('app', 'Введите сумму оплаты в рублях:'));

        return $message;
    }

    public function getMessageTotalAmount()
    {
        $message = \Yii::t('app', '{from} Qiwi RUB -> {to} {currency}', [
            'from'     => $this->runAmount,
            'to'       => $this->currencyAmount,
            'currency' => $this->bulletCurrency
        ]) . PHP_EOL;
        $message .= '============================' . PHP_EOL;

        return $message;
    }

    public function getYouAgreeMessage()
    {
        return \Yii::t('app', 'Вы согласны?');
    }
}