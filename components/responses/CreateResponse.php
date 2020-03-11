<?php
namespace app\components\responses;

use yii\base\Component;

class CreateResponse extends Component
{
    public $ticket_comment;
    public $rate;
    public $currency_amount;    //сумма в валюте, которую покупаю
    public $currency;           //какую валюту покупаю
    public $amount;             //сумма в рублях
    public $phone;
    public $btc_address;
}