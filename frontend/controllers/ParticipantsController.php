<?php

namespace frontend\controllers;

use Yii;
use app\models\Activity;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Url;

class ParticipantsController extends \yii\web\Controller
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
        return $this->render('index');
    }

    public function actionIncoming()
    {
        $query = Activity::find()->where(['activities.user_id' => Yii::$app->user->id])->leftJoin('participants', 'activities.id = participants.activity_id')->andWhere(['participants.status' => 'P'])->orderBy('created desc');
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

        return $this->render('incoming', [
            'activities' => $activities,
            'pages' => $pages,
        ]);
    }
}