<?php

namespace macklus\payments\models;

use Yii;

/**
 * This is the model class for table "payment_response".
 *
 * @property int $id
 * @property int $payment_id
 * @property string $status
 * @property double $amount
 * @property string $provider
 * @property string $data
 * @property string $error_code
 * @property string $date_received
 * @property string $date_processed
 *
 * @property Payment $payment
 */
class PaymentResponse extends \yii\db\ActiveRecord {

    const STATUS_OK = 'ok';
    const STATUS_ERROR = 'error';
    const STATUS_UNKNOW = 'unknow';
    const PROVIDER_PAYPAL = 'paypal';
    const PROVIDER_REDSYS = 'redsys';
    const PROVIDER_TRANSFER = 'transfer';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return Yii::$app->getModule('payments')->tables['response'];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['payment_id'], 'integer'],
            [['status', 'provider', 'data'], 'string'],
            [['amount', 'provider', 'data'], 'required'],
            [['amount'], 'number'],
            [['date_received', 'date_processed'], 'safe'],
            [['error_code'], 'string', 'max' => 255],
            [['payment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payment::className(), 'targetAttribute' => ['payment_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('payment', 'ID'),
            'payment_id' => Yii::t('payment', 'Payment ID'),
            'status' => Yii::t('payment', 'Status'),
            'amount' => Yii::t('payment', 'Amount'),
            'provider' => Yii::t('payment', 'Provider'),
            'data' => Yii::t('payment', 'Data'),
            'error_code' => Yii::t('payment', 'Error Code'),
            'date_received' => Yii::t('payment', 'Date Received'),
            'date_processed' => Yii::t('payment', 'Date Processed'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment() {
        return $this->hasOne(Payment::className(), ['id' => 'payment_id']);
    }

    /**
     * @inheritdoc
     * @return PaymentResponseQuery the active query used by this AR class.
     */
    public static function find() {
        return new PaymentResponseQuery(get_called_class());
    }

}
