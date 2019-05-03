<?php

namespace frontend\controllers;

use app\models\Activity;
use app\models\Participant;
use common\models\User;
use Yii;
use app\models\Countries;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\models\ActivityPhotos;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;
use yii\web\View;
use yii\helpers\Json;
use yii\data\Pagination;
use yii\filters\AccessControl;
use app\components\Csc;

class MyactivityController extends \yii\web\Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
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
        $user = User::find()->where(['id' => Yii::$app->user->id])->one();
        if (empty($user->firstname))
        {
            Yii::$app->session->setFlash('error', Yii::t('common', 'Сначала заполните свои данные'));
            return Yii::$app->getResponse()->redirect(['user/update']);
        }

        $condition = [];
        if (!empty(Yii::$app->request->get()['Activity']['city_id'])) $city_id = Yii::$app->request->get()['Activity']['city_id'];
        //elseif (!empty(Yii::$app->request->get()['city_id'])) $city_id = Yii::$app->request->get()['city_id'];
        if (!empty($city_id)) $condition['city_id'] = $city_id;
        //$condition['status'] = "A";
        if (!empty(Yii::$app->request->get()['Activity'])) $_cond = Yii::$app->request->get()['Activity'];
        $query = Activity::find()->where($condition);
        if (!empty($_cond['name'])) $query = $query->andWhere(['like', 'name', $_cond['name']]);
        if (!empty($_cond['category_id'])) $query = $query->andWhere(['=', 'category_id', $_cond['category_id']]);
        if (!empty($_cond['city_id'])) $query = $query->andWhere(['=', 'city_id', $_cond['city_id']]);
        if (!empty($_cond['location'])) $query = $query->andWhere(['like', 'location', $_cond['location']]);
        if (!empty($_cond['date_from'])) $query = $query->andWhere(['>=', 'date_from', strtotime($_cond['date_from'])]);
        if (!empty($_cond['date_to'])) $query = $query->andWhere(['<=', 'date_to', strtotime($_cond['date_to'])]);
        $query = $query->andWhere(['user_id' => Yii::$app->user->id]);
        $query = $query->orderBy('created desc');
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->defaultPageSize = 9;
        $activities = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        foreach($activities as &$activity)
        {
            Activity::prepareActivity($activity);
        }

        return $this->render('index', [
            'activities' => $activities,
            'pages' => $pages,
        ]);
    }

    public function actionDeletephotos($id)
    {
        $model = new ActivityPhotos();
        $image = ActivityPhotos::findOne($id);
        $directory = Yii::getAlias('@frontend/web/img/activities/detailed') . DIRECTORY_SEPARATOR . $image->activity_id . DIRECTORY_SEPARATOR;
        $thumbs_directory = Yii::getAlias('@frontend/web/img/activities/thumbs') . DIRECTORY_SEPARATOR . $image->activity_id . DIRECTORY_SEPARATOR;

        $det_fileName = basename($image->detailed_path);
        $th_fileName = basename($image->thumbnail_path);
        unlink($directory . $det_fileName);
        unlink($thumbs_directory . $th_fileName);
        $image->delete();

        $images = $model->findByActivity($image->activity_id);
        $output = [];
        foreach ($images as $image) {
            $fileName = basename($image['thumbnail_path']);
            $path = $thumbs_directory . $fileName;
            $output['files'][] = [
                'name' => $fileName,
                'url' => $path,
                'thumbnailUrl' => $path,
                'deleteUrl' => Url::to(['myactivity/deletephotos', 'id' => $image['id']]),
                'deleteType' => 'POST',
                'setMainUrl' => Url::to(['myactivity/setmainactivityphoto', 'id' => $image['id']]),
                'type' => $image['type'],
            ];
        }

        return Json::encode($output);
    }

    public function actionDelete($id)
    {
        $model = Activity::find()->where(['id' => $id])->one();
        $model->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSetmainactivityphoto($photo_id)
    {
        $model = ActivityPhotos::findById($photo_id);
        Yii::$app->db->createCommand()->update('activity_photos', ['type' => "A"], 'activity_id = :activity_id', [':activity_id' => $model->activity_id])->execute();
        $model->type = "M";
        $model->save();
    }

    public function actionSetparticipantstatus()
    {
        $participant = Participant::find()->where(['id' => Yii::$app->request->get()['participant_id']])->one();
        $participant->status = Yii::$app->request->get()['status'];
        $participant->save();
        $user = User::find()->where(['id' => $participant->user_id])->one();
        $body = Yii::t('common', 'Статус вашей заявки на движуху изменен') . " " . Yii::$app->urlManager->createAbsoluteUrl(['/activities/view', 'id' => $participant->activity_id]);
        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($user->email)
            ->setSubject(Yii::t('common', 'Статус вашей заявки на движуху изменен'))
            ->setHtmlBody($body)
            ->send();
        Yii::$app->session->setFlash('success', Yii::t('common', 'Успешно'));
    }

    public function actionUpdate($id = 0)
    {
        $participants = [];
        $view = $this->getView();
        $model = Activity::findById($id, Yii::$app->user->id);
        if (empty($model))
        {
            if ($id == 0) $model = new Activity();
            else throw new \yii\web\NotFoundHttpException(Yii::t('common', 'Движуха не найдена =('));
        }
        $photos_model = new ActivityPhotos();
        if ($model->load(Yii::$app->request->post()))
        {
            $model->user_id = Yii::$app->user->id;
            $model->date_from = strtotime($model->date_from);
            $model->date_to = strtotime($model->date_to);
            $model->created = time();
            if ($model->validate())
            {
                $model->save();
                Yii::$app->session->setFlash('success', Yii::t('common', 'К успеху шел!'));
                return $this->redirect(['myactivity/update/' . $model->getPrimaryKey()]);
            }
        }
        else
        {
            if ($id > 0)
            {
                $model->prepareActivity($model);
                $participants = Participant::find()->where(['activity_id' => $id]);
                $countQuery = clone $participants;
                $pages = new Pagination(['totalCount' => $countQuery->count()]);
                $pages->defaultPageSize = 9;
                $participants = $participants->offset($pages->offset)
                    ->limit($pages->limit)
                    ->all();
                Participant::prepareUsers($participants);
            }
            else
            {
                $user = User::findIdentity(Yii::$app->user->id);
                $model->country = $user->country;
                $model->city_id = $user->city_id;
            }
        }
        if (empty($pages)) $pages = 0;

        //images
        $directory = Yii::getAlias('@frontend/web/img/activities/detailed') . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;
        $thumbs_directory = Yii::getAlias('@frontend/web/img/activities/thumbs') . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;

        $imageFile = UploadedFile::getInstance($photos_model, 'image');
        if ($imageFile && $imageFile->tempName)
        {
            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }
            if (!is_dir($thumbs_directory)) {
                FileHelper::createDirectory($thumbs_directory);
            }
            $photos_model->image = $imageFile;
            if ($photos_model->validate(['image']))
            {
                $uid = uniqid(time(), true);
                $fileName = $uid . '.' . $imageFile->extension;
                $filePath = $directory . $fileName;
                $photos_model->image->saveAs($filePath);
                unset($photos_model->image);
                $photo = Image::getImagine()->open($filePath);
                $photo->thumbnail(new Box(800, 800))->save($filePath, ['quality' => 90]);
                $thumb_path = $thumbs_directory . $fileName;
                $photo->thumbnail(new Box(200, 200))->save($thumb_path, ['quality' => 90]);

                $directory = Yii::getAlias('@web/img/activities/detailed') . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;
                $thumbs_directory = Yii::getAlias('@web/img/activities/thumbs') . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;
                $filePath = $directory . $fileName;
                $thumb_path = $thumbs_directory . $fileName;
                $photos_model->activity_id = $id;
                $photos_model->detailed_path = $filePath;
                $photos_model->thumbnail_path = $thumb_path;
                $photos_model->save(false);

                return Json::encode([
                    'files' => [
                        [
                            'id' => $photos_model->getPrimaryKey(),
                            'name' => $fileName,
                            'url' => $thumb_path,
                            'thumbnailUrl' => $thumb_path,
                            'deleteUrl' => Url::to(['myactivity/deletephotos', 'id' => $photos_model->getPrimaryKey()]),
                            'deleteType' => 'POST',
                            'setMainUrl' => Url::to(['myactivity/setmainactivityphoto', 'id' => $photos_model->getPrimaryKey()]),
                        ],
                    ],
                ]);
            }
        }
        if (!empty(Yii::$app->request->get()['act']) && Yii::$app->request->get()['act'] == 'getphotos')
        {
            $directory = Yii::getAlias('@web/img/activities/detailed') . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;
            $thumbs_directory = Yii::getAlias('@web/img/activities/thumbs') . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;
            $images = $photos_model->findByActivity($id);
            $output = [];
            foreach ($images as $image) {
                $fileName = basename($image['thumbnail_path']);
                $path = $thumbs_directory . $fileName;
                $output['files'][] = [
                    'id' => $image['id'],
                    'name' => $fileName,
                    'url' => $path,
                    'thumbnailUrl' => $path,
                    'deleteUrl' => Url::to(['myactivity/deletephotos', 'id' => $image['id']]),
                    'deleteType' => 'POST',
                    'setMainUrl' => Url::to(['myactivity/setmainactivityphoto', 'id' => $image['id']]),
                    'type' => $image['type'],
                ];
            }

            return Json::encode($output);
        }
        //

        $view->registerJs("
                $(document).on('click','.set_main', function(){
                     $.ajax({
                        url: '" . Url::to(['myactivity/setmainactivityphoto']) . "',
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

        return $this->render('update', [
            'model' => $model,
            'photos_model' => $photos_model,
            'participants' => $participants,
            'pages' => $pages,
            'activity_id' => $id
        ]);
    }
}