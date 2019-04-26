<?php

namespace frontend\controllers;

use common\models\User;
use app\models\Activity;
use Yii;
use app\models\Countries;
use yii\filters\AccessControl;
use yii\helpers\Url;

class CitiesController extends \yii\web\Controller
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

    public function actionGetcities()
    {
        $user = User::findIdentity(Yii::$app->user->id);
        $country = Yii::$app->request->get()['country'];
        $cities = Countries::getCities($country);
        $str = '';
        foreach($cities as $key => $city)
        {
            $str .= '<option value="' . $key . '"';
            if ($user['city_id'] == $key) $str .= 'selected';
            $str .= '>' . $city . '</option>';
        }
        echo $str;
    }

    public function actionGetactivitycities()
    {
        $country = Yii::$app->request->get()['country'];
        $activity_id = Yii::$app->request->get()['activity_id'];
        if ($activity_id > 0) $activity = Activity::findOne(['id' => $activity_id]);
        $cities = Countries::getCities($country);
        $str = '';
        foreach($cities as $key => $city)
        {
            $str .= '<option value="' . $key . '"';
            if (!empty($activity) && $activity->city_id == $key) $str .= 'selected';
            $str .= '>' . $city . '</option>';
        }
        echo $str;
    }

    public function actionGetactivityformcities()
    {
        if (Yii::$app->request->get()['country']) $country = Yii::$app->request->get()['country'];
        else
        {
            $city_id = Yii::$app->request->get()['city_id'];
            $country = Countries::getByCityId($city_id);
        }

        $cities = Countries::getCities($country);
        $str = '';
        foreach($cities as $key => $city)
        {
            $str .= '<option value="' . $key . '"';
            //if (!empty($activity) && $activity->city_id == $key) $str .= 'selected';
            $str .= '>' . $city . '</option>';
        }
        echo $str;
    }
}