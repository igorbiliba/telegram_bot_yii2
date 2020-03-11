<?php
namespace app\components;

use yii\base\Component;
class Graphene extends Component
{
    public function IsIssetAccount($account)
    {
        $data = $this->Send("get_account_by_name", [$account]);
        return $data->result !== null;
    }
    public function Send($method, $params)
    {
        $data = [
            'jsonrpc' => "2.0",
            'id'      => 1,
            'method'  => $method,
            'params'  => $params
        ];
        $options = array(
            'http' => array(
                'method'  => 'POST',
                'content' => json_encode( $data ),
                'header'=>  "Content-Type: application/json\r\n" .
                    "Accept: application/json\r\n"
            )
        );
        $context  = stream_context_create( $options );
        $result = file_get_contents("https://de.localcoin.is/", false, $context );
        return json_decode( $result );
    }
}
