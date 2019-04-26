<?php
use yii\widgets\Menu;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\models\Countries;
use yii\helpers\Url;
$this->title = Yii::t('common', 'Редактирование');
?>
<h1><?=Yii::t('common', 'Редактирование')?></h1>

<div class="row">
    <?=Yii::$app->mymenu->userSidebarMenu();?>
    <div class="col-lg-5">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'firstname') ?>
        <?= $form->field($model, 'lastname') ?>
        <?= $form->field($model, 'birthday')->widget(\kartik\date\DatePicker::class, [
            'value' => date('dd.mm.yyyy', time()),
            'options' => ['placeholder' => 'Select issue date ...'],
            'pluginOptions' => [
                'format' => 'dd.mm.yyyy',
                'todayHighlight' => true
            ],
            'attribute' => 'birthday',

        ]) ?>
        <?php echo $form->field($model, 'sex')->dropDownList([
                'male' => Yii::t('common', 'Муж.'),
                'female' => Yii::t('common', 'Жен.')
            ]
        ); ?>
        <?= $form->field($model, 'phone') ?>
        <?php echo $form->field($model, 'relationship')->dropDownList([
                'single' => Yii::t('common', 'Свободен'),
                'in_a_relationship' => Yii::t('common', 'Есть пара'),
                'married' => Yii::t('common', 'В браке'),
                'in_love' => Yii::t('common', 'Влюблен'),
                'complicated' => Yii::t('common', 'Всё сложно'),
                'searching' => Yii::t('common', 'В активном поиске'),
            ]
        ); ?>
        <?php echo $form->field($model, 'country')->dropDownList(
            Countries::getCountries(),
            array(
                'prompt' => Yii::t('common', 'Выберите страну'),
                'onchange'=>'
				    $.get( "'.Yii::$app->urlManager->createUrl('cities/getcities?country=').'"+$(this).val(), function( data ) {
				    $( "#cities select" ).html( data );
				});'
            )
        ); ?>
        <?php
        echo $form->field($model, 'city_id', ['options' => ['id' => 'cities']])->dropDownList(
            Countries::getCities($model->country)
        );
        ?>
        <?= $form->field($model, 'activity')->textarea(['rows' => '3']) ?>
        <?= $form->field($model, 'interests')->textarea(['rows' => '3']) ?>
        <?= $form->field($model, 'study')->textarea(['rows' => '3']) ?>
        <?= $form->field($model, 'job')->textarea(['rows' => '3']) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('common', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>