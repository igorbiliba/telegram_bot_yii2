<?php
namespace app\helpers;

use app\buy\BuyCurrencies;
use app\components\FixerIoComponent;
use app\components\Homer;
use yii\base\Component;

class PhoneHelper extends Component
{
    public static function CheckPhone($phone)
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (empty($phone) || strlen($phone) < 7 || preg_match('/^(79|380|44|375|994|91|77|9955|370|992|66|998|507|374|371|90|373|972|84|372|82|996)/', $phone) !== 1 || (substr($phone, 0, 2) == '79' && strlen($phone) != 11)) {
            return false;
        }

        return true;
    }
}