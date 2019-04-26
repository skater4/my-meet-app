<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;
use common\models\User;
use app\models\Participant;
use vision\messages\models\Messages;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => Yii::t('common', 'Главная'), 'url' => Url::to(['site/index'])],
        ['label' => Yii::t('common', 'Контакты'), 'url' => Url::to(['/site/contact'])],
    ];

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => $menuItems,
    ]);
    $menuItems = [];

    if (!Yii::$app->user->isGuest) {
        $menuItems []=['label' => Yii::t('common', 'Поиск движа'), 'url' => Url::to(['/activities/index', 'page' => 1])];
        $menuItems []=['label' => Yii::t('common', 'Мои участия'), 'url' => Url::to(['/myparticipation/index', 'page' => 1])];
        $new_messages = Messages::getNewMessagesCount(Yii::$app->user->id);
        if ($new_messages > 0) $menuItems []=['label' => Yii::t('common', 'Новые сообщения') . " (" . $new_messages . ")", 'url' => Url::to(['/newmessages/index', 'page' => 1])];
        $menuItems []=['label' => Yii::t('common', 'Мои движухи'), 'url' => Url::to(['/myactivity/index', 'page' => 1])];
        $incoming_requests = Participant::getActivitiesParticipantsCount();
        if ($incoming_requests > 0) $menuItems []=['label' => Yii::t('common', 'Входящие заявки') . ' (' . $incoming_requests . ')', 'url' => Url::to(['/participants/incoming', 'page' => 1])];
        $menuItems []=['label' => Yii::t('common', 'Мой профиль'), 'url' => Url::to(['/user/index'])];
    }

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => Yii::t('common', 'Регистрация'), 'url' => Url::to(['/site/signup'])];
        $menuItems[] = ['label' => Yii::t('common', 'Войти'), 'url' => Url::to(['/site/login'])];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                Yii::t('common', 'Выйти') . ' (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);

    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?> | <?= $this->render('language'); ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>