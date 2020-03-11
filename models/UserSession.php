<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_session".
 *
 * @property string $id
 * @property string $step
 * @property string|null $amount
 * @property string|null $btc_addr
 * @property string|null $llc_account
 * @property string|null $currency
 * @property string|null $phone
 */
class UserSession extends \yii\db\ActiveRecord
{
    const STEP_FIRST                   = 'first';
    const STEP_ENTER_AMOUNT            = 'enteramount';
    const STEP_ENTER_BTC_ADDRESS       = 'enterbtcaddress';
    const STEP_ENTER_PHONE             = 'enterphone';
    const STEP_ENTER_LOCALCOIN_ACCOUNT = 'enterlocalcoinaccount';
    const STEP_WAIT_PAID               = 'waitpaid';
    const STEP_ENTER_AGREE_AMOUNT      = 'enteragreeamount';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_session';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'step', 'amount', 'btc_addr', 'llc_account', 'currency', 'phone'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'step' => 'Step',
            'amount' => 'Amount',
            'btc_addr' => 'Btc Addr',
            'llc_account' => 'Llc Account',
            'currency' => 'Currency',
            'phone' => 'Phone',
        ];
    }

    public static function FindOrCreate($id)
    {
        $id = trim($id . '');

        $model = UserSession::findOne(['id' => $id]);
        if($model) return $model;

        (new UserSession(['id' => $id]))->save();

        return UserSession::findOne(['id' => $id]);
    }

    public function SeparateForwardAmount($rate)
    {
        $arr = explode('.', $this->CalcCurrencyAmount($rate));

        return [
            'int'      => empty($arr[0]) ? 0 : $arr[0],
            'fraction' => empty($arr[1]) ? 0 : $arr[1],
        ];
    }

    public function CalcCurrencyAmount($rate)
    {
        return $this->amount / $rate;
    }

    public function Reset()
    {
        $this->step        = static::STEP_FIRST;
        $this->amount      = null;
        $this->btc_addr    = null;
        $this->llc_account = null;
        $this->currency    = null;
        $this->phone       = null;

        return $this->save();
    }

    public function ExistsWaitTickets()
    {
        return Ticket::find()
            ->where([
                'user_session_id' => $this->id,
                'status'          => Ticket::STATUS_WAIT
            ])
            ->exists();
    }
}
