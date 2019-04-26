<?php

namespace frontend\controllers;

use common\models\User;
use Yii;
use vision\messages\models\Messages;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Url;

class NewmessagesController extends \yii\web\Controller
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
        $users = User::find()->where(['messages.whom_id' => Yii::$app->user->id, 'messages.status' => 1])->leftJoin('messages', 'user.id = messages.from_id');
        $countQuery = clone $users;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->defaultPageSize = 9;
        $users = $users->all();

        return $this->render('index', [
            'users' => $users,
            'pages' => $pages
        ]);
    }
}