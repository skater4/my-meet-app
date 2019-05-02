<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('common', 'Авторизация');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?=Html::hiddenInput('return_url', @$_GET['return_url'])?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    <?=Yii::t('common', 'Не зарегистрированы?')?> <?= Html::a(Yii::t('common', 'Регистрация'), ['site/signup']) ?>.
                    <br>
                    <?=Yii::t('common', 'Забыли пароль?')?> <?= Html::a(Yii::t('common', 'Сбросить'), ['site/request-password-reset']) ?>.
                    <br>
                    <?=Yii::t('common', 'Не пришло письмо на почту?')?> <?= Html::a(Yii::t('common', 'Выслать заново'), ['site/resend-verification-email']) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
