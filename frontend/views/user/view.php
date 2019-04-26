<?php
use dosamigos\gallery\Gallery;
use app\models\Countries;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
$this->title = Yii::t('common', $model->firstname . ' ' . $model->lastname);
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Пользователи')];
$this->params['breadcrumbs'][] = ['label' => $model->firstname . ' ' . $model->lastname];
?>
<?php
$items = [
    [
        'label' => Yii::t('common', 'Общее'),
        'content' => $this->render('_view_general', ['model' => $model]),
        'active' => true
    ],
    [
        'label' => Yii::t('common', 'Фото'),
        'content' => $this->render('_view_photos', ['model' => $model, 'images' => $images]),
    ],
    [
        'label' => Yii::t('common', 'Сообщения'),
        'content' => $this->render('_chat', ['user_id' => $model->id]),
    ],
];
?>
<?=Tabs::widget([
    'items' => $items
]);?>