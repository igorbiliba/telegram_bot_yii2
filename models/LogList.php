<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_list".
 *
 * @property int $id
 * @property string $data
 */
class LogList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data'], 'required'],
            [['data'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data' => 'Data',
        ];
    }
}
