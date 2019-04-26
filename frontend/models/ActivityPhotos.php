<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "activity_photos".
 *
 * @property int $id
 * @property int $activity_id
 * @property string $detailed_path
 * @property string $thumbnail_path
 */
class ActivityPhotos extends \yii\db\ActiveRecord
{
    public $image;
    public $images;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_photos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activity_id', 'detailed_path', 'thumbnail_path'], 'required'],
            [['activity_id'], 'integer'],
            [['detailed_path', 'thumbnail_path'], 'string'],
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
            'detailed_path' => 'Detailed Path',
            'thumbnail_path' => 'Thumbnail Path',
        ];
    }

    public function findById($id)
    {
        return static::find()->where(['id' => $id])->one();
    }

    public function findByActivity($activity_id)
    {
        return static::find()->where(['activity_id' => $activity_id])->orderBy('type desc')->all();
    }

    public function getMainImage($activity_id)
    {
        return static::find()->where(['activity_id' => $activity_id, 'type' => 'M'])->one();
    }
}