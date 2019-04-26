<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;


class PhotosUpdateForm extends Model
{
    public $images;

    private $_user_photos;

    public function __construct($user_id, $config = [])
    {
        parent::__construct($config);
    }

    public function updatePhotos()
    {
        $_user_photos = $this->_user;
        $_user_photos->images = $this->images;

        return $_user_photos->save(false);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['image'], 'file', 'extensions' => 'png, jpg'],
        ];
    }
}
