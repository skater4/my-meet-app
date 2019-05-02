<?php

namespace frontend\controllers;

use Yii;
use yii\data\Pagination;
use app\models\Activity;
use yii\filters\AccessControl;
use yii\helpers\Url;
use common\models\User;

class MyparticipationController extends \yii\web\Controller
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
        $activities = Activity::find()->where(['participants.status' => 'A', 'participants.user_id' => Yii::$app->user->id])->leftJoin('participants', 'activities.id = participants.activity_id');

        $countQuery = clone $activities;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->defaultPageSize = 9;
        $activities = $activities->all();

        return $this->render('index', [
            'activities' => $activities,
            'pages' => $pages
        ]);
    }
}