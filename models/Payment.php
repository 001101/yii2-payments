<?php

namespace macklus\payments\models;

use Yii;
use Ramsey\Uuid\Uuid;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property string $code
 * @property double $amount
 * @property string $provider
 * @property string $date_received
 * @property string $date_procesed
 * @property string $date_add
 * @property string $date_edit
 *
 * @property PaymentResponse[] $paymentResponses
 */
class Payment extends \yii\db\ActiveRecord {

    const PROVIDER_PAYPAL = 'paypal';
    const PROVIDER_REDSYS = 'redsys';
    const PROVIDER_TRANSFER = 'transfer';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return Yii::$app->getModule('payments')->tables['payment'];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['amount', 'provider'], 'required'],
            [['amount'], 'number'],
            [['provider'], 'string'],
            [['code', 'date_received', 'date_procesed', 'date_add', 'date_edit'], 'safe'],
            [['code'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('payment', 'ID'),
            'code' => Yii::t('payment', 'Code'),
            'amount' => Yii::t('payment', 'Amount'),
            'provider' => Yii::t('payment', 'Provider'),
            'date_received' => Yii::t('payment', 'Date Received'),
            'date_procesed' => Yii::t('payment', 'Date Procesed'),
            'date_add' => Yii::t('payment', 'Date Add'),
            'date_edit' => Yii::t('payment', 'Date Edit'),
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_add',
                'updatedAtAttribute' => 'date_edit',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResponses() {
        return $this->hasMany(PaymentResponse::className(), ['payment_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return PaymentQuery the active query used by this AR class.
     */
    public static function find() {
        return new PaymentQuery(get_called_class());
    }

    public function beforeSave($insert) {
        if ($insert) {
            // Define code
            do {
                $this->code = strtoupper(Uuid::uuid4()->toString());
                $exists = Payment::find()->code($this->code)->one();
            } while ($exists);
        }
        return parent::beforeSave($insert);
    }

}
