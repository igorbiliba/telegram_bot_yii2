<?php
namespace app\buy;

use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * @property string $allCurrencies
 * @property string $fiatCurrencies
 * @property string $localCurrencies
 *
 * Class BuyCurrencies
 * @package app\buy
 */
class BuyCurrencies extends Component
{
    public function getAllCurrencies()
    {
        return ArrayHelper::merge($this->fiatCurrencies, $this->localCurrencies);
    }

    public function getFiatCurrencies()
    {
        return [
            'BTC' => 'BTC'
        ];
    }

    public function getLocalCurrencies()
    {
        return [
            'RUB' => 'LocalRUB',
            'USD' => 'LocalUSD',
            'EUR' => 'LocalEUR'
        ];
    }

    public function ExistsCurrency($currency, $haystack = null)
    {
        $currency = strtolower(trim($currency));

        foreach ($haystack === null ? $this->allCurrencies : $haystack as $key => $value)
            if(trim(strtolower($key)) === $currency || trim(strtolower($value)) === $currency)
                return true;

        return false;
    }

    public function GetInverseCurrency($bullet)
    {
        foreach ($this->allCurrencies as $key => $value) {
            if (trim(strtolower($key))   === $bullet) return trim(strtolower($value));
            if (trim(strtolower($value)) === $bullet) return trim(strtolower($key));
        }

        return $bullet;
    }
}