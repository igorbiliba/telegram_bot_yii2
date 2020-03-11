<?php
namespace app\components;

use app\components\responses\CreateResponse;
use app\models\LogList;
use app\models\UserSession;
use yii\base\Component;

/**
 * @property string $bridgeBtcRate
 *
 * Class Homer
 * @param array $context
 * @package app\components
 */
class Homer extends Component
{
    /**
     * @param UserSession $userSession
     * @param float $rate
     *
     * @return CreateResponse
     */
    public function CreateRecvisits($userSession, $rate)
    {
        $separatedForvard = $userSession->SeparateForwardAmount($rate);

        $amount       = $userSession->amount;
        $phone        = $userSession->phone;
        $btc_address  = $userSession->btc_addr;
        $btc_int      = $separatedForvard['int'];
        $btc_fraction = $separatedForvard['fraction'];

        if (is_null($amount) || is_null($phone) || is_null($btc_address) || is_null($btc_int) || is_null($btc_fraction)) return null;

        try {
            $url   = \Yii::$app->params['exchange_host'] . "/create?amount=$amount&phone=$phone&forwardtobtc=$btc_address&forwardint=$btc_int&forwardfraction=$btc_fraction";
            $draft = file_get_contents($url, false, $this->context);
            $json  = json_decode($draft, true);

            return new CreateResponse([
                'rate'            => $rate,
                'currency_amount' => $userSession->CalcCurrencyAmount($rate),
                'currency'        => $userSession->currency,
                'amount'          => $userSession->amount,
                'ticket_comment'  => $json['comment'],
                'phone'           => $json['account'],
                'btc_address'     => $json['hash'],
            ]);
        } catch (\Exception $e) {}

        return new CreateResponse();
    }

    public function getBridgeBtcRate()
    {
        $url = \Yii::$app->params['exchange_host'] . '/cource';
        $draft = file_get_contents($url, false, $this->context);

        $contents = json_decode($draft, true);
        return isset($contents['cource']) && floatval($contents['cource'] > 0) ? $contents['cource'] : null;
    }

    function getContext()
    {
        $auth = base64_encode("host_exchange:toocomplicatedpassword");
        return stream_context_create([
            "http" => [
                'timeout' => 55,
                "header" => "Authorization: Basic $auth",
            ],
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ]);
    }

    public function CheckAddressBTC($address)
    {
        try {
            $contents = json_decode(file_get_contents(\Yii::$app->params['exchange_host'] . '/btc_validate?address=' . $address, false, $this->context), true);
            return isset($contents['isvalid']) && $contents['isvalid'] === true;
        } catch (\Exception $e) {
            return null;
        }

        return false;
    }

    public function CheckIsPaid($address)
    {
        try {
            $contents = json_decode(file_get_contents(\Yii::$app->params['exchange_host'] . '/check?hash=' . $address, false, $this->context), true);
            return isset($contents['status']) && $contents['status'] === "paid";
        }
        catch (\Exception $e) {}

        return false;
    }
}
