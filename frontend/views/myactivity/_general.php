<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;
use app\models\Countries;
use app\models\ActivityCategories;
?>

<div class="row">
    <div class="col-lg-8">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
        <?= $form->field($model, 'category_id')->dropDownList(
            ActivityCategories::findCategories()
        );
        ?>
        <?php echo $form->field($model, 'country')->dropDownList(
            Countries::getCountries(),
            array(
                'prompt' => Yii::t('common', 'Выберите страну'),
                'onchange'=>'
				    $.get( "'.Yii::$app->urlManager->createUrl('cities/getactivitycities?country=').'" + $(this).val() + "&activity_id=' . $activity_id . '", function( data ) {
				    $( "#cities select" ).html( data );
				});'
            )
        ); ?>
        <?php
        echo $form->field($model, 'city_id', ['options' => ['id' => 'cities']])->dropDownList(
            Countries::getCities($model->country)
        );
        ?>
        <?= $form->field($model, 'location')->textarea(['rows' => 3]) ?>
        <?= $form->field($model, 'contacts')->textarea(['rows' => 3]) ?>

        <?= $form->field($model, 'date_from')->widget(\kartik\datetime\DateTimePicker::class, [
            //'value' => date('dd.mm.yyyy', time()),
            'options' => ['placeholder' => 'Select issue date ...'],
            'pluginOptions' => [
                'format' => 'dd.mm.yyyy hh:ii',
                'autoclose' => true,
                'todayHighlight' => true
            ],
            'attribute' => 'date_from',

        ]) ?>

        <?= $form->field($model, 'date_to')->widget(\kartik\datetime\DateTimePicker::class, [
            //'value' => date('dd.mm.yyyy', time()),
            'options' => ['placeholder' => 'Select issue date ...'],
            'pluginOptions' => [
                'format' => 'dd.mm.yyyy hh:ii',
                'autoclose' => true,
                'todayHighlight' => true
            ],
            'attribute' => 'date_to',

        ]) ?>
        <?= $form->field($model, 'max_users') ?>
        <?= $form->field($model, 'status')->radioList([
                'A' => Yii::t('common', 'Активно'),
                'D' => Yii::t('common', 'Отменено'),
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('common', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>