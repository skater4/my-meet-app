<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $verification_token
 * @property string $firstname
 * @property string $lastname
 * @property int $birthday
 * @property string $sex
 * @property string $country
 * @property int $city_id
 * @property string $phone
 * @property string $relationship
 * @property string $job
 * @property string $activity
 * @property string $interests
 * @property string $study
 *
 * @property Message[] $messages
 * @property Message[] $messages0
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at', 'firstname', 'lastname', 'birthday', 'sex', 'country', 'city_id', 'phone', 'relationship', 'job', 'activity', 'interests', 'study'], 'required'],
            [['status', 'created_at', 'updated_at', 'birthday', 'city_id'], 'integer'],
            [['phone', 'relationship', 'job', 'activity', 'interests', 'study'], 'string'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['firstname', 'lastname'], 'string', 'max' => 50],
            [['sex'], 'string', 'max' => 10],
            [['country'], 'string', 'max' => 100],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'verification_token' => Yii::t('app', 'Verification Token'),
            'firstname' => Yii::t('app', 'Firstname'),
            'lastname' => Yii::t('app', 'Lastname'),
            'birthday' => Yii::t('app', 'Birthday'),
            'sex' => Yii::t('app', 'Sex'),
            'country' => Yii::t('app', 'Country'),
            'city_id' => Yii::t('app', 'City ID'),
            'phone' => Yii::t('app', 'Phone'),
            'relationship' => Yii::t('app', 'Relationship'),
            'job' => Yii::t('app', 'Job'),
            'activity' => Yii::t('app', 'Activity'),
            'interests' => Yii::t('app', 'Interests'),
            'study' => Yii::t('app', 'Study'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['from_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(Message::className(), ['whom_id' => 'id']);
    }
}
