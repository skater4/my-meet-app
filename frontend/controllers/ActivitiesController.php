<?php

namespace frontend\controllers;
use Yii;
use app\models\Activity;
use app\models\ActivityPhotos;
use yii\data\Pagination;
use yii\helpers\Url;
use app\models\Participant;
use common\models\User;
use app\models\UserPhotos;
use yii\filters\AccessControl;

class ActivitiesController extends \yii\web\Controller
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
        if (!empty($city_id)) $condition['city_id'] = $city_id;
        if (!empty($_GET['just_active']) && $_GET['just_active'] == "Y") $condition['status'] = "A";
        if (!empty(Yii::$app->request->get()['Activity'])) $_cond = Yii::$app->request->get()['Activity'];
        $query = Activity::find()->where($condition);
        if (!empty($_cond['name'])) $query = $query->andWhere(['like', 'name', $_cond['name']]);
        if (!empty($_cond['category_id'])) $query = $query->andWhere(['=', 'category_id', $_cond['category_id']]);
        if (!empty($_cond['city_id'])) $query = $query->andWhere(['=', 'city_id', $_cond['city_id']]);
        if (!empty($_cond['location'])) $query = $query->andWhere(['like', 'location', $_cond['location']]);
        if (!empty($_cond['date_from'])) $query = $query->andWhere(['>=', 'date_from', strtotime($_cond['date_from'])]);
        if (!empty($_cond['date_to'])) $query = $query->andWhere(['<=', 'date_to', strtotime($_cond['date_to'])]);
        else $query = $query->andWhere(['>', 'date_to', time()]);
        $query = $query->andWhere(['!=', 'user_id', Yii::$app->user->id]);
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

    public function actionView($id)
    {
        $directory = Yii::getAlias('@web/img/activities/detailed') . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;
        $thumbs_directory = Yii::getAlias('@web/img/activities/thumbs') . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;

        $participants = [];
        $model = Activity::find()->where(['id' => $id])->one();
        if (empty($model)) throw new \yii\web\NotFoundHttpException(Yii::t('common', 'Движуха не найдена =('));
        $user = User::findIdentity($model->user_id);
        $user->avatar = UserPhotos::getAvatar($user->id);
        $participants = new Participant();
        $photos_model = new ActivityPhotos();
        $images = $photos_model->findByActivity($id);
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

        $participants = Participant::find()->where(['activity_id' => $id, 'status' => 'A']);
        $countQuery = clone $participants;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->defaultPageSize = 9;
        $participants = $participants->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        Participant::prepareUsers($participants);
        $new_participant = new Participant();
        return $this->render('view', [
            'model' => $model,
            'user' => $user,
            'pages' => $pages,
            'participants' => $participants,
            'new_participant' => $new_participant,
            'images' => $output,
        ]);
    }

    public function actionApplyparticipant()
    {
        $model = new Participant();
        $activity = Activity::find()->where(['id' => Yii::$app->request->post()['Participant']['activity_id']])->one();
        $user = User::find()->where(['id' => $activity->user_id])->one();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save(false)) {
            $body = Yii::t('common', 'У вас новая заявка на движуху') . " " . Yii::$app->urlManager->createAbsoluteUrl(['/myactivity/update', 'id' => $model->activity_id]);
            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($user->email)
                ->setSubject(Yii::t('common', 'У вас новая заявка на движуху'))
                ->setHtmlBody($body)
                ->send();
            Yii::$app->session->setFlash('success', Yii::t('common', 'К успеху шел!'));
            $this->redirect(Url::to(['activities/view', 'id' => $model->activity_id]));
        }
        else $this->redirect(Url::to(['activities/view', 'id' => $model->activity_id]));
    }
}