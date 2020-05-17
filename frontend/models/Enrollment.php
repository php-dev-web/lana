<?php

namespace app\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "enrollment".
 *
 * @property int $id
 * @property string|null $date
 * @property int $user_id
 * @property string $sum
 * @property string $action
 *
 * @property User $user
 */
class Enrollment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'enrollment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['user_id', 'sum', 'action'], 'required'],
            [['user_id'], 'integer'],
            [['sum', 'action'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'user_id' => 'User',
            'sum' => 'Sum',
            'action' => 'Action',
            'userName' => 'User id or phone',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getUserName()
    {
        return $this->user->username;
    }

    public static function getTotalSum($dataProvider)
    {
        $total = 0;
        
        foreach ($dataProvider as $key => $value) {
            $total += $value['sum']; 
        }

        return $total;
    }

}
