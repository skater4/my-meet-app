<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_photos".
 *
 * @property int $id
 * @property int $user_id
 * @property string $detailed_path
 * @property string $thumbnail_path
 * @property string $type
 */
class UserPhotos extends \yii\db\ActiveRecord
{
    public $image;
    public $images;

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_photos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'detailed_path', 'thumbnail_path'], 'required'],
            [['user_id'], 'integer'],
            [['detailed_path', 'thumbnail_path'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'detailed_path' => Yii::t('common', 'detailed Path'),
            'thumbnail_path' => Yii::t('common', 'Thumbnail Path'),
            [['images'], 'file', 'extensions' => 'png, jpg'],
        ];
    }

    public static function findUserPhotos($user_id)
    {
        return static::find()->where(['user_id' => $user_id])->orderBy('type desc, id')->all();
    }

    public static function findUserPhoto($photo_id)
    {
        return static::find()->where(['id' => $photo_id])->one();
    }

    public static function getUserMainPhoto($user_id)
    {
        return static::find()->where(['user_id' => $user_id, 'type' => 'M'])->one();
    }

    public static function getAvatar($user_id)
    {
        return static::find()->where(['user_id' => $user_id, 'type' => "M"])->one();
    }

    public static function getAvatarImagePath($user_id)
    {
        $avatar = static::find()->where(['user_id' => $user_id, 'type' => "M"])->one();
        if (!empty($avatar)) $avatar = $avatar->thumbnail_path;
        else $avatar = Yii::getAlias('@web/img/no_image.png');

        return $avatar;
    }
}