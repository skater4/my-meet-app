<?php
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'My Meet App';
?>
<style>
    body {
        background: linear-gradient( rgba(255, 255, 255, 0.8), rgba(0, 0, 0, 0.7) ), url('img/back.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
        background-position: top;
    }
</style>
<div class="site-index">

    <div class="jumbotron">
        <h1>My Meet App</h1>

        <p class="lead"><?=Yii::t('common', 'Найди себе пати в любом городе!')?></p>

        <p><a class="btn btn-lg btn-success" href="<?=Url::to(['/activities/index', 'page' => 1])?>"><?=Yii::t('common', 'Давай начнем!')?></a></p>
    </div>

    <div class="body-content">

        <div class="row" style="color: white;">
            <div class="col-lg-6">
                <h2><?=Yii::t('common', 'Зачем это?')?></h2>

                <p style="font-size: 17px;"><?=Yii::t('common', 'Иногда хочется развлечься с компанией и сходить в кино, или же поиграть в настольные игры, может даже посетить мастер-класс по буддийским традициям. Но не всегда есть кого позвать с собой. Наш сервис поможет тебе найти компанию для любого движа!')?></p>
            </div>
            <div class="col-lg-6">
                <h2><?=Yii::t('common', 'Для кого это?')?></h2>

                <p style="font-size: 17px;"><?=Yii::t('common', 'У всех людей бывает такое что не с кем пойти, будь футбол во дворе или опера. Наш сервис позволит найти компанию по душе всем людям без исключений!')?></p>
            </div>
        </div>

    </div>
</div>
