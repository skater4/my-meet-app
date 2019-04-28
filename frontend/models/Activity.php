<?php

namespace app\models;

use Yii;
use yii\db\Exception;
use app\models\ActivityPhotos;

/**
 * This is the model class for table "activities".
 *
 * @property int $id
 * @property int $category_id
 * @property int $user_id
 * @property string $name
 * @property string $description
 * @property string $city_id
 * @property string $location
 * @property string $contacts
 * @property int $date_from
 * @property int $date_to
 * @property int $max_users
 * @property int $status
 * @property int $created
 */
class Activity extends \yii\db\ActiveRecord
{
    public $images;
    public $participants;

    public function __construct($id = 0, $config = [])
    {
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        if (Yii::$app->controller->id == 'activities' || Yii::$app->controller->id == 'myactivity' && Yii::$app->controller->action->id == 'index') return [
            [['name', 'description', 'location', 'contacts', 'status', 'country'], 'string'],
            [['user_id', 'max_users', 'city_id', 'category_id', 'created'], 'integer'],
        ];
        return [
            [['user_id', 'name', 'description', 'location', 'contacts', 'date_from', 'date_to', 'status', 'city_id', 'country', 'category_id', 'created'], 'required'],
            [['name', 'description', 'location', 'contacts', 'status', 'country'], 'string'],
            [['user_id', 'max_users', 'city_id', 'category_id', 'created'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'category_id' => Yii::t('common', 'Категория'),
            'name' => Yii::t('common', 'Название'),
            'description' => Yii::t('common', 'Описание'),
            'country' => Yii::t('common', 'Страна'),
            'city_id' => Yii::t('common', 'Город'),
            'location' => Yii::t('common', 'Место'),
            'contacts' => Yii::t('common', 'Контакты'),
            'date_from' => Yii::t('common', 'Начало'),
            'date_to' => Yii::t('common', 'Окончание'),
            'max_users' => Yii::t('common', 'Макс. кол-во участников'),
            'status' => Yii::t('common', 'Статус'),
        ];
    }

    public function findById($id, $user_id = 0)
    {
        $params = [
            'id' => $id
        ];
        if ($user_id > 0) $params['user_id'] = $user_id;
        return static::find()->where($params)->one();
    }

    public function findByUser($user_id)
    {
        return static::find()->where(['user_id' => $user_id])->all();
    }

    public function prepareActivity(&$activity)
    {
        $activity->date_from = date('d.m.Y H:i:s', $activity->date_from);
        $activity->date_to = date('d.m.Y H:i:s', $activity->date_to);

        $activity->images = ActivityPhotos::findByActivity($activity['id']);
    }

    public function getActivityIds($user_id)
    {

    }
}