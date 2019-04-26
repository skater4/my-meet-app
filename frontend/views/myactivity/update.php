<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\datetime\DateTimePicker;
use yii\bootstrap\Tabs;
if ($activity_id == 0) $title = Yii::t('common', 'Создание движухи');
else $title = Yii::t('common', 'Редактирование движухи ') . $model->name;
$this->title = Yii::t('common', $title);
?>

<h1><?=Yii::t('common', $title)?></h1>
<?php
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Мои движухи'), 'url' => Url::to(['myactivity/index'])];
$this->params['breadcrumbs'][] = ['label' => $model->name];
$items = [
    [
        'label' => Yii::t('common', 'Общее'),
        'content' => $this->render('_general', ['model' => $model, 'activity_id' => $activity_id]),
        'active' => true
    ]
];
if ($activity_id > 0)
{
    $items []= [
        'label' => Yii::t('common', 'Фото'),
        'content' => $this->render('_photo', ['model' => $photos_model, 'activity_id' => $activity_id]),
    ];
    $items []= [
        'label' => Yii::t('common', 'Участники'),
        'content' => $this->render('_participants', ['model' => $model, 'activity_id' => $activity_id, 'participants' => $participants, 'pages' => $pages]),
    ];
    $items []= [
        'label' => Yii::t('common', 'Сообщения'),
        'content' => $this->render('_chat', ['model' => $model, 'object_id' => $model->id]),
    ];
}
?>
<?=Tabs::widget([
        'items' => $items
]);?>


