<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = $model->firstname . " " . $model->lastname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->firstname . " " . $model->lastname;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($model->firstname . " " . $model->lastname) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <a href="<?=Yii::$app->urlManagerFrontend->createUrl(['user/login-as', 'id' => $model->id, 'auth_key' => $model->auth_key])?>" class="btn btn-primary" target="_blank">Залогиниться</a>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'email:email',
            'status',
            'created_at',
            'updated_at',
            'verification_token',
            'firstname',
            'lastname',
            'birthday',
            'sex',
            'country',
            'city_id',
            'phone:ntext',
            'relationship:ntext',
            'job:ntext',
            'activity:ntext',
            'interests:ntext',
            'study:ntext',
        ],
    ]) ?>

</div>
