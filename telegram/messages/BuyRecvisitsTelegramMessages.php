<?php

namespace app\telegram\messages;

use app\buy\BuyCurrencies;
use app\components\CreateResponse;
use app\models\Ticket;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use yii\base\Component;

/**
 * @property string $message
 * @property string $localMessage
 * @property string $btcMessage
 * @property string $messageUpdateRate
 * @property string $messageCreate
 * @property ReplyKeyboardMarkup $buttons
 *
 * Class FirstTelegramMessages
 * @package app\telegram\messages
 */
class BuyRecvisitsTelegramMessages extends Component
{
    /**
     * @var CreateResponse
     */
    public $createResponse;

    function getLocalMessage()
    {
        $answer = '<i>' . \Yii::t('app', 'Заявка: {ticket_comment}') . PHP_EOL;
        $answer .=        \Yii::t('app', 'Текущий курс {rate} руб.') . PHP_EOL;
        $answer .=        \Yii::t('app', 'Вы покупаете {currency_amount} {currency} на {amount} руб') . PHP_EOL;
        $answer .=        \Yii::t('app', 'Номер {phone}') . PHP_EOL;
        $answer .=        \Yii::t('app', 'У вас есть '. Ticket::TICKET_EXPIRE_MINUTES .' минут, чтобы сделать оплату, заявка будет отменена автоматически если оплата не поступит в течение 10 минут.') . '</i>';

        $answer = str_replace('{ticket_comment}',  $this->createResponse->ticket_comment,  $answer);
        $answer = str_replace('{rate}',            $this->createResponse->rate,            $answer);
        $answer = str_replace('{currency_amount}', $this->createResponse->currency_amount, $answer);
        $answer = str_replace('{currency}',        $this->createResponse->currency,        $answer);
        $answer = str_replace('{amount}',          $this->createResponse->amount,          $answer);
        $answer = str_replace('{phone}',           $this->createResponse->phone,           $answer);

        return $answer;
    }

    function getBtcMessage()
    {
        $answer = '<i>' . \Yii::t('app', 'Заявка: {ticket_comment}') . PHP_EOL;
        $answer .=        \Yii::t('app', 'Текущий курс {rate} руб.') . PHP_EOL;
        $answer .=        \Yii::t('app', 'Вы покупаете {currency_amount} {currency} на {amount} руб') . PHP_EOL;
        $answer .=        \Yii::t('app', 'Номер {phone}, BTC адрес {btc_address}') . PHP_EOL;
        $answer .=        \Yii::t('app', 'У вас есть '. Ticket::TICKET_EXPIRE_MINUTES .' минут, чтобы сделать оплату, заявка будет отменена автоматически если оплата не поступит в течение 10 минут.') . '</i>';

        $answer = str_replace('{ticket_comment}',  $this->createResponse->ticket_comment,  $answer);
        $answer = str_replace('{rate}',            $this->createResponse->rate,            $answer);
        $answer = str_replace('{currency_amount}', $this->createResponse->currency_amount, $answer);
        $answer = str_replace('{currency}',        $this->createResponse->currency,        $answer);
        $answer = str_replace('{amount}',          $this->createResponse->amount,          $answer);
        $answer = str_replace('{phone}',           $this->createResponse->phone,           $answer);
        $answer = str_replace('{btc_address}',     $this->createResponse->btc_address,     $answer);

        return $answer;
    }

    public function getMessage()
    {
        $buyCurrencies = new BuyCurrencies();

        if($buyCurrencies->ExistsCurrency($this->createResponse->currency, $buyCurrencies->localCurrencies))
            return $this->localMessage;

        if(trim(strtolower($this->createResponse->currency)) === 'btc')
            return $this->btcMessage;
    }

    public function getMessageCreate()
    {
        return \Yii::t('app', 'Создание заявки, пожалуйста подождите...');
    }

    public function getMessageUpdateRate()
    {
        return \Yii::t('app', 'Обновление курсов...');
    }

    public function getButtons()
    {
        $comment = str_replace('#', '%23', $this->createResponse->ticket_comment);
        $url = "https://qiwi.com/payment/form/99?extra['account']=". $this->createResponse->phone
            ."&amountInteger=". $this->createResponse->amount
            ."&amountFraction=00&extra['comment']=".$comment
            ."&currency=643&blocked[0]=account&blocked[1]=comment&blocked[2]=sum";

        return new InlineKeyboardMarkup(
            [
                [
                    ['text' => \Yii::t('app', 'Оплатить'), 'url' => $url]
                ]
            ]
        );
    }
}