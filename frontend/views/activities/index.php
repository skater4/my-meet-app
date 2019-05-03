<?php
use yii\helpers\Url;
use app\models\ActivityPhotos;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\Activity;
use app\models\ActivityCategories;
use app\models\Countries;
use kartik\datetime\DateTimePicker;
use common\models\User;
$this->title = Yii::t('common', 'Поиск движа');
$this->params['breadcrumbs'][] = ['label' => "Поиск движа"];
?>
    <h1><?=Yii::t('common', 'Поиск движа')?></h1>
<?php
$i = 1;
$activity = new Activity();
if (!empty(Yii::$app->request->get()['Activity']['city_id'])) $city_id = Yii::$app->request->get()['Activity']['city_id'];
else $city_id = '';
$activity->load(Yii::$app->request->get());
//die($city_id);
echo "<div class='row'>";?>
    <div class="col-lg-3">
        <?php $form = ActiveForm::begin(['method' => 'get']); ?>
        <?= $form->field($activity, 'name')?>
        <?php
        $params = ['get_empty' => true];
        ?>
        <?= $form->field($activity, 'category_id')->dropDownList(
            ActivityCategories::findCategories($params)
        );
        ?>
        <?php echo $form->field($activity, 'country')->dropDownList(
            Countries::getCountries(),
            array(
                'prompt' => Yii::t('common', 'Выберите страну'),
                'onchange'=>'
				    $.get( "'.Yii::$app->urlManager->createUrl('cities/getactivityformcities?country=').'" + $(this).val() + "&city_id=' . $city_id . '", function( data ) {
				    $( "#cities select" ).html( data );
				});'
            )
        ); ?>
        <?php
        echo $form->field($activity, 'city_id', ['options' => ['id' => 'cities']])->dropDownList(
            Countries::getCities($activity->country)
        );
        ?>
        <?= $form->field($activity, 'location')?>
        <?= $form->field($activity, 'date_from')->widget(\kartik\datetime\DateTimePicker::class, [
            'value' => date('dd.mm.yyyy', time()),
            'options' => ['placeholder' => 'Select issue date ...'],
            'pluginOptions' => [
                'format' => 'dd.mm.yyyy hh:ii',
                'todayHighlight' => true
            ],
            'attribute' => 'date_from',

        ]) ?>

        <?= $form->field($activity, 'date_to')->widget(\kartik\datetime\DateTimePicker::class, [
            'value' => date('dd.mm.yyyy', time()),
            'options' => ['placeholder' => 'Select issue date ...'],
            'pluginOptions' => [
                'format' => 'dd.mm.yyyy hh:ii',
                'todayHighlight' => true
            ],
            'attribute' => 'date_to',

        ]) ?>
        <div class="form-check">
            <?=Html::hiddenInput('just_active', 'N')?>
            <label class="control-label" for="just_active"><?=Yii::t('common', 'Только активные')?></label>
            <?=Html::checkbox('just_active', !empty($_GET['just_active']) && $_GET['just_active'] == "Y", ['value' => "Y", 'class' => 'form-check-input', 'id' => 'just_active'])?>
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('common', 'Поиск'), ['class' => 'btn btn-primary']) ?>
            <a class="btn btn-primary" href="<?=Url::to(['/activities/index', 'page' => 1])?>"><?=Yii::t('common', 'Сбросить')?></a>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-lg-9">
        <?php
        foreach($activities as $key => $activity):?>
            <?php
            $main_image = ActivityPhotos::getMainImage($activity->id);
            if (!empty($main_image)) $image = $main_image->thumbnail_path;
            else $image = Yii::getAlias('@web/img/no_image.png');
            if ($i == 1) echo '<div class="row">';
            ?>
            <div class='col-lg-4 activity-item'>
                <div class="card" style="width: 207px;">
                    <a href="<?=Url::to(['activities/view', 'id' => $activity->id])?>">
                        <img class="card-img-top" src="<?=$image?>">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title"><?if ($activity->status == "D") echo "<b>" . Yii::t('common', 'Отменено') . "</b>"?></h5>
                        <h5 class="card-title"><?=$activity->name?></h5>
                        <div class="card-text"><?=$activity->description?></div>
                        <p class="card-text"><?=Yii::t('common', 'Начало')?> <?=$activity->date_from?></p>
                        <p class="card-text"><?=Yii::t('common', 'Окончание')?> <?=$activity->date_to?></p>
                    </div>
                </div>
            </div>

            <?php
            if ($i == 3) echo "</div>";
            $i++;
            if ($i == 4) $i = 1;
            ?>
        <?endforeach;?>
        <?php
        if ($i > 1) echo '</div>';
        echo "<div class='row'>";
        echo LinkPager::widget([
            'pagination' => $pages,
        ]);
        echo '</div>';
        ?>
    </div></div>
<?php
//if ($i < 3) echo "</div>";
?>