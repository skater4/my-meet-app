<?php

namespace app\models;

use Yii;
use common\models\User;
use app\models\UserPhotos;

/**
 * This is the model class for table "participants".
 *
 * @property int $id
 * @property int $activity_id
 * @property int $user_id
 * @property string $status
 */
class Participant extends \yii\db\ActiveRecord
{
    public $user;
    public $avatar;
    public $statuses = [];

    public function __construct(array $config = [])
    {
        $this->statuses = [
            'A' => Yii::t('common', 'Одобрено'),
            'D' => Yii::t('common', 'Отклонено'),
            'P' => Yii::t('common', 'В ожидании'),
        ];
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'participants';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activity_id', 'user_id', 'status', 'message'], 'required'],
            [['activity_id', 'user_id'], 'integer'],
            [['message'], 'string'],
            [['status'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_id' => 'Activity ID',
            'user_id' => 'User ID',
            'status' => 'Status',
            'message' => Yii::t('common', 'Сообщение'),
        ];
    }

    public static function userApplied($activity_id, $user_id)
    {
        $row = static::find()->where(['activity_id' => $activity_id, 'user_id' => $user_id])->one();
        return !empty($row) ? true : false;
    }

    public static function userAccepted($activity_id, $user_id)
    {
        $row = static::find()->where(['activity_id' => $activity_id, 'user_id' => $user_id, 'status' => 'A'])->one();
        return !empty($row) ? true : false;
    }

    public static function prepareUsers(&$participants)
    {
        foreach ($participants as &$participant)
        {
            $participant->user = User::findIdentity($participant->user_id);
            $participant->avatar = UserPhotos::getAvatar($participant->user_id);
        }
    }

    public static function getActivityParticipantsCount($activity_id)
    {
        return static::find()->where(['activity_id' => $activity_id, 'status' => 'P'])->count();
    }

    public static function getActivitiesParticipantsCount()
    {
        $activity_ids = Yii::$app->db->createCommand("select id from activities where user_id = " . Yii::$app->user->id . " and status = 'A' and date_to > " . time())->queryColumn();
        if (!empty($activity_ids)) return static::find()->where(['status' => 'P'])->andWhere(['in', 'activity_id', $activity_ids])->count();
    }
}