<?php
namespace app\components;
use yii\base\Component;

/**
 * @property string $key
 * @property string $url
 * @property string[] $scalePercent
 * @property string[] $rates
 * @property string[] $currencies
 *
 * Class FixerIoComponent
 * @package app\components
 */
class FixerIoComponent extends Component
{
    public function getCurrencies()   { return ['RUB', 'USD', 'EUR']; }
    public function getScalePercent() { return \Yii::$app->params['scalepercentLocal']; }
    public function getKey()          { return \Yii::$app->params['fixer_key'];   }
    public function getUrl()          { return 'http://data.fixer.io/api/latest?access_key=' . $this->key; }
    public function getRates()
    {
        $url = $this->url . '&base=EUR&symbols=' . implode(',', $this->currencies);
        $response = json_decode(file_get_contents($url), true);
        $sourceRates = $response['rates'];
        $RUB = $sourceRates['RUB'];
        $ratesByRUB = [];
        foreach ($sourceRates as $amount => $rate) {
            $scale = isset($this->scalePercent[$amount]) ? $this->scalePercent[$amount] : 0;
            $ratesByRUB[$amount] = $RUB / ($rate - ($rate / 100 * $scale));
        }
        return $ratesByRUB;
    }
}
