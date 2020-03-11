<?php

namespace app\models;

use app\components\responses\CreateResponse;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "ticket".
 *
 * @property integer $id
 * @property string $user_session_id
 * @property string $status
 * @property string $ticket_comment
 * @property string $rate
 * @property string $currency_amount сумма в валюте, которую покупаю
 * @property string $currency какую валюту покупаю
 * @property string $amount сумма в рублях
 * @property string $phone
 * @property string $btc_address
 * @property int|null $created_at
 *
 * @property UserSession $userSession
 */
class Ticket extends \yii\db\ActiveRecord
{
    const TICKET_EXPIRE_MINUTES = 30;

    const STATUS_WAIT   = "wait";
    const STATUS_PAID   = "paid";
    const STATUS_CANCEL = "cancel";

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_session_id', 'ticket_comment', 'rate', 'currency_amount', 'currency', 'amount', 'phone', 'btc_address'], 'required'],
            [['created_at'], 'integer'],
            [['user_session_id', 'status', 'ticket_comment', 'rate', 'currency_amount', 'currency', 'amount', 'phone', 'btc_address'], 'string', 'max' => 255],
            [['user_session_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserSession::className(), 'targetAttribute' => ['user_session_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_session_id' => 'User Session ID',
            'status' => 'Status',
            'ticket_comment' => 'Ticket Comment',
            'rate' => 'Rate',
            'currency_amount' => 'Currency Amount',
            'currency' => 'Currency',
            'amount' => 'Amount',
            'phone' => 'Phone',
            'btc_address' => 'Btc Address',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSession()
    {
        return $this->hasOne(UserSession::className(), ['id' => 'user_session_id']);
    }

    public static function CreateNewTicket(CreateResponse $data, UserSession $userSession)
    {
        $model = new Ticket([
            'user_session_id' => $userSession->id,
            'ticket_comment'  => $data->ticket_comment . '',
            'rate'            => $data->rate . '',
            'currency_amount' => $data->currency_amount . '',
            'currency'        => $data->currency . '',
            'amount'          => $data->amount . '',
            'phone'           => $data->phone . '',
            'btc_address'     => $data->btc_address . '',
        ]);

        if($model->save())
            return $model;

        (new LogList([
            'data' => "error create: " . json_encode($model->errors)
        ]))->save();

        return null;
    }
}
