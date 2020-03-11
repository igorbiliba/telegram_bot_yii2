<?php
namespace app\components;

use app\buy\BuyCurrencies;
use app\components\responses\CreateResponse;
use app\models\LogList;
use app\models\UserSession;
use yii\base\Component;

/**
 * Class ExchangeBotLLC
 * @param array $context
 * @package app\components
 */
class ExchangeBotLLC extends Component
{
    const HOST     = 'https://******/';
    const LOGIN    = '****';
    const PASSWORD = '****';

    function CreateUrl(UserSession $userSession, $currencyRate, $btcRate)
    {
        $params = [
            'sessionId'        => $userSession->id,
            'amount'           => $userSession->amount,
            'amountByCurrency' => round($userSession->CalcCurrencyAmount($currencyRate), 5),
            'amountByBtc'      => round($userSession->CalcCurrencyAmount($btcRate), 5),
            'phone'            => str_replace('+', '', $userSession->phone),
            'llcAccount'       => $userSession->llc_account,
            'currency'         => (new BuyCurrencies())->GetInverseCurrency($userSession->currency),
        ];

        return ExchangeBotLLC::HOST . 'create?' . http_build_query($params);
    }

    /**
     * @param UserSession $userSession
     * @param float $currencyRate
     * @param float $btcRate
     *
     * @return CreateResponse
     */
    public function CreateRecvisits($userSession, $currencyRate, $btcRate)
    {
        try {
            $url   = $this->CreateUrl($userSession, $currencyRate, $btcRate);
            $draft = file_get_contents($url, false, $this->context); //{"hash":"3KMHweuszrdzjodPZjaoC6CtQDefKTs743","account":"+79534475026","comment":"619801"}
            $json  = json_decode($draft, true);

            return new CreateResponse([
                'rate'            => $currencyRate,
                'currency_amount' => $userSession->CalcCurrencyAmount($currencyRate),
                'currency'        => $userSession->currency,
                'amount'          => $userSession->amount,
                'ticket_comment'  => $json['comment'],
                'phone'           => $json['account'],
                'btc_address'     => $json['hash'],
            ]);
        } catch (\Exception $e) { }

        return new CreateResponse();
    }

    function getContext()
    {
        $auth = base64_encode(ExchangeBotLLC::LOGIN . ":" . ExchangeBotLLC::PASSWORD);
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
}