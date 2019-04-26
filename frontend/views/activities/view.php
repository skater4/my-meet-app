<?php
use dosamigos\gallery\Gallery;
use app\models\Countries;
use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\models\Participant;
use yii\helpers\Url;
$this->title = Yii::t('common', $model->name);
?>

<h1><?=Yii::t('common', $model->name)?></h1>
<?php
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Поиск движа'), 'url' => Url::to(['activities/index'])];
$this->params['breadcrumbs'][] = ['label' => $model->name];
?>
<?php $form = ActiveForm::begin(['action' => Url::to(['activities/applyparticipant'])]); ?>
<a class="btn btn-primary" onclick="javascript:history.back();"><?=Yii::t('common', 'Назад')?></a>
<?php
if (Yii::$app->user->id != $model->user_id && !Participant::userApplied($model->id, Yii::$app->user->id)):
Modal::begin([
    'header' => Yii::t('common', 'Подать заявку'),
    'toggleButton' => ['label' => Yii::t('common', 'Подать заявку'), 'class' => 'btn btn-primary']
]);
?>
<?= $form->field($new_participant, 'activity_id')->hiddenInput(['readonly' => true, 'value' => $model->id])->label(false) ?>
<?= $form->field($new_participant, 'user_id')->hiddenInput(['readonly' => true, 'value' => Yii::$app->user->id])->label(false) ?>
<?= $form->field($new_participant, 'status')->hiddenInput(['readonly' => true, 'value' => "P"])->label(false) ?>
<?= $form->field($new_participant, 'message')->textarea(['rows' => 3])?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('common', 'Отправить'), ['class' => 'btn btn-primary']) ?>
</div>
<?php
Modal::end();
endif;
?>
<?php ActiveForm::end(); ?>

<br><br>

<?php
$items = [
    [
        'label' => Yii::t('common', 'Общее'),
        'content' => $this->render('_view_general', ['model' => $model, 'activity_id' => $model->id, 'images' => $images, 'user' => $user]),
        'active' => true
    ],
    [
        'label' => Yii::t('common', 'Участники'),
        'content' => $this->render('_view_participants', ['model' => $model, 'activity_id' => $model->id, 'pages' => $pages, 'participants' => $participants]),
    ],
];

if (Participant::userAccepted($model->id, Yii::$app->user->id)) $items []= [
    'label' => Yii::t('common', 'Сообщения'),
    'content' => $this->render('_chat', ['model' => $model, 'object_id' => $model->id, 'participants' => $participants]),
];
?>
<?=Tabs::widget([
    'items' => $items
]);?>
