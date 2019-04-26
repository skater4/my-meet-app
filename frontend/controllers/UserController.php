<?php

namespace frontend\controllers;

use app\models\ProfileUpdateForm;
use Yii;
use common\models\User;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use app\models\UserPhotos;
use yii\imagine\Image;
use Imagine\Image\Box;
use yii\web\View;
use vision\messages\assets\PrivateMessPoolingAsset;
use yii\filters\AccessControl;
use yii\helpers\Url;

class UserController extends \yii\web\Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'private-messages' => [
                'class' => \vision\messages\actions\MessageApiAction::className()
            ]
        ];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    return $this->redirect(Url::to(['site/login']));
                }
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpdatephotos()
    {
        $view = $this->getView();
        $model = new UserPhotos();

        $directory = Yii::getAlias('@frontend/web/img/users/detailed') . DIRECTORY_SEPARATOR . Yii::$app->user->id . DIRECTORY_SEPARATOR;
        $thumbs_directory = Yii::getAlias('@frontend/web/img/users/thumbs') . DIRECTORY_SEPARATOR . Yii::$app->user->id . DIRECTORY_SEPARATOR;
        if (!is_dir($directory)) {
            FileHelper::createDirectory($directory);
        }
        if (!is_dir($thumbs_directory)) {
            FileHelper::createDirectory($thumbs_directory);
        }

        $imageFile = UploadedFile::getInstance($model, 'image');
        if ($imageFile && $imageFile->tempName)
        {
            $model->image = $imageFile;
            if ($model->validate(['image']))
            {
                $uid = uniqid(time(), true);
                $fileName = $uid . '.' . $imageFile->extension;
                $filePath = $directory . $fileName;
                $model->image->saveAs($filePath);
                unset($model->image);
                $photo = Image::getImagine()->open($filePath);
                $photo->thumbnail(new Box(800, 800))->save($filePath, ['quality' => 90]);
                $thumb_path = $thumbs_directory . $fileName;
                $photo->thumbnail(new Box(200, 200))->save($thumb_path, ['quality' => 90]);

                $directory = Yii::getAlias('@web/img/users/detailed') . DIRECTORY_SEPARATOR . Yii::$app->user->id . DIRECTORY_SEPARATOR;
                $thumbs_directory = Yii::getAlias('@web/img/users/thumbs') . DIRECTORY_SEPARATOR . Yii::$app->user->id . DIRECTORY_SEPARATOR;
                $filePath = $directory . $fileName;
                $thumb_path = $thumbs_directory . $fileName;
                $model->user_id = Yii::$app->user->id;
                $model->detailed_path = $filePath;
                $model->thumbnail_path = $thumb_path;
                $model->save(false);

                return Json::encode([
                    'files' => [
                        [
                            'id' => $model->id,
                            'name' => $fileName,
                            'url' => $thumb_path,
                            'thumbnailUrl' => $thumb_path,
                            'deleteUrl' => 'deletephotos?id=' . $model->getPrimaryKey(),
                            'setMainUrl' => 'setMainUserPhoto?id=' . $model->id,
                            'deleteType' => 'POST',
                        ],
                    ],
                ]);
            }
        }
        else
        {
            if (!empty(Yii::$app->request->get()['act']) && Yii::$app->request->get()['act'] == 'getphotos')
            {
                $directory = Yii::getAlias('@web/img/users/detailed') . DIRECTORY_SEPARATOR . Yii::$app->user->id . DIRECTORY_SEPARATOR;
                $thumbs_directory = Yii::getAlias('@web/img/users/thumbs') . DIRECTORY_SEPARATOR . Yii::$app->user->id . DIRECTORY_SEPARATOR;
                $main_photo_id = User::getMainPhotoId(Yii::$app->user->id);
                $images = $model->findUserPhotos(Yii::$app->user->id);
                $output = [];
                foreach ($images as $image) {
                    $fileName = basename($image['thumbnail_path']);
                    $path = $thumbs_directory . $fileName;
                    $output['files'][] = [
                        'id' => $image['id'],
                        'name' => $fileName,
                        'url' => $path,
                        'thumbnailUrl' => $path,
                        'deleteUrl' => 'deletephotos?id=' . $image['id'],
                        'setMainUrl' => 'setMainUserPhoto?id=' . $image['id'],
                        'deleteType' => 'POST',
                        'type' => $image['type'],
                    ];
                }

                return Json::encode($output);
            }
            else
            {
                $directory = Yii::getAlias('@web/img/activities/detailed') . DIRECTORY_SEPARATOR . Yii::$app->user->id . DIRECTORY_SEPARATOR;
                $thumbs_directory = Yii::getAlias('@web/img/activities/thumbs') . DIRECTORY_SEPARATOR . Yii::$app->user->id . DIRECTORY_SEPARATOR;
                $images = $model->findUserPhotos(Yii::$app->user->id);
                $output = [];
                foreach ($images as $image) {
                    $fileName = basename($image['thumbnail_path']);
                    $output[] = [
                        'id' => $image['id'],
                        'url' => $directory . $fileName,
                        'src' => $thumbs_directory . $fileName,
                        'type' => $image['type'],
                    ];
                }

                $view->registerJs("
                $(document).on('click','.set_main', function(){
                     $.ajax({
                        url: 'setmainuserhoto',
                        data: {
                            photo_id: $(this).attr('data-photo-id')
                        },
                        photo_id: $(this).attr('data-photo-id'),
                        success: function (data) {
                            $('.template-download').removeClass('main_photo');
                            $('#photo_' + $(this)[0].photo_id).addClass('main_photo');
                        }
                   })
                });",View::POS_READY);

                return $this->render('updatephotos', [
                    'model' => $model,
                    'images' => $output,
                ]);
            }
        }
    }

    public function actionSetmainuserhoto($photo_id)
    {
        Yii::$app->db->createCommand()->update('user_photos', ['type' => "A"], 'user_id = ' . Yii::$app->user->id)->execute();

        $model = UserPhotos::findUserPhoto($photo_id);
        $model->type = "M";
        $model->save();
    }

    public function actionDeletephotos($id)
    {
        $model = new UserPhotos();
        $directory = Yii::getAlias('@frontend/web/img/users/detailed') . DIRECTORY_SEPARATOR . Yii::$app->user->id . DIRECTORY_SEPARATOR;
        $thumbs_directory = Yii::getAlias('@frontend/web/img/users/thumbs') . DIRECTORY_SEPARATOR . Yii::$app->user->id . DIRECTORY_SEPARATOR;

        $image = UserPhotos::findOne($id);
        $det_fileName = basename($image->detailed_path);
        $th_fileName = basename($image->thumbnail_path);
        unlink($directory . $det_fileName);
        unlink($thumbs_directory . $th_fileName);
        $image->delete();

        $images = $model->findUserPhotos(Yii::$app->user->id);
        $output = [];
        $directory = Yii::getAlias('@web/img/users/detailed') . DIRECTORY_SEPARATOR . Yii::$app->user->id . DIRECTORY_SEPARATOR;
        $thumbs_directory = Yii::getAlias('@web/img/users/thumbs') . DIRECTORY_SEPARATOR . Yii::$app->user->id . DIRECTORY_SEPARATOR;
        foreach ($images as $image) {
            $fileName = basename($image['thumbnail_path']);
            $path = $thumbs_directory . $fileName;
            $output['files'][] = [
                'name' => $fileName,
                'url' => $path,
                'thumbnailUrl' => $path,
                'deleteUrl' => 'deletephotos?id=' . $image['id'],
                'deleteType' => 'POST',
            ];
        }

        return Json::encode($output);
    }

    public function actionUpdate()
    {
        try {
            $model = new ProfileUpdateForm(Yii::$app->user->id);
        }
        catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->updateUser()) {
            Yii::$app->session->setFlash('success', Yii::t('common', 'К успеху шел!'));
            return $this->refresh();
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        $view = $this->getView();
        //PrivateMessPoolingAsset::register($view);
        $view->registerJs('$(document).ready(function(){$(\'.contact\').click();})', View::POS_READY);
        $directory = Yii::getAlias('@web/img/users/detailed') . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;
        $thumbs_directory = Yii::getAlias('@web/img/users/thumbs') . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;
        $user = User::find()->where(['id' => $id])->one();
        if (empty($user)) throw new NotFoundHttpException(Yii::t('common', 'Пользователь не найден'));
        $photos_model = new UserPhotos();
        $images = $photos_model->findUserPhotos($id);
        $output = [];
        foreach ($images as $image) {
            $dfileName = basename($image['detailed_path']);
            $fileName = basename($image['thumbnail_path']);
            $dpath = $directory . $dfileName;
            $path = $thumbs_directory . $fileName;
            $output[] = [
                'url' => $dpath,
                'src' => $path
            ];
        }
        return $this->render('view', [
            'model' => $user,
            'images' => $output,
        ]);
    }
}