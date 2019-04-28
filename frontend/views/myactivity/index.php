<?php
use yii\helpers\Url;
use app\models\ActivityPhotos;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use app\models\Countries;
use yii\helpers\Html;
use app\models\Activity;
use app\models\ActivityCategories;
$this->title = Yii::t('common', 'Мои движухи');
?>
    <h1><?=Yii::t('common', 'Мои движухи')?></h1>
<?php
$this->params['breadcrumbs'][] = Yii::t('common', 'Мои движухи');
?>
    <div><a class="btn btn-primary" href="<?=Url::to(['myactivity/update']);?>"><?=Yii::t('common', 'Создать')?></a></div><br>
<?php
$i = 1;
$activity = new Activity();
if (!empty(Yii::$app->request->get()['Activity']['city_id'])) $city_id = Yii::$app->request->get()['Activity']['city_id'];
else $city_id = '';
$activity->load(Yii::$app->request->get());
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
        <div class="form-group">
            <?= Html::submitButton(Yii::t('common', 'Поиск'), ['class' => 'btn btn-primary']) ?>
            <a class="btn btn-primary" href="<?=Url::to(['/myactivity/index', 'page' => 1])?>"><?=Yii::t('common', 'Сбросить')?></a>
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
                    <a href="<?=Url::to(['myactivity/update', 'id' => $activity->id])?>">
                        <img class="card-img-top" src="<?=$image?>">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title"><?=$activity->name?></h5>
                        <p class="card-text"><?=$activity->description?></p>
                        <p class="card-text"><?=Yii::t('common', 'Начало')?> <?=$activity->date_from?></p>
                        <p class="card-text"><?=Yii::t('common', 'Окончание')?> <?=$activity->date_to?></p>
                        <p class="card-text"><?php
                            switch ($activity->status)
                            {
                                case "A":
                                    $status = Yii::t('common', 'Активно');
                                    break;
                                case "D":
                                    $status = Yii::t('common', 'Отменено');
                                    break;
                            }
                            echo Yii::t('common', $status)
                            ?></p>
                        <a href="<?=Url::to(['myactivity/update', 'id' => $activity->id])?>" class="btn btn-primary"><?=Yii::t('common', 'Редактировать')?></a>
                        <a href="<?=Url::to(['myactivity/delete', 'id' => $activity->id])?>" class="btn btn-danger"><?=Yii::t('common', 'Удалить')?></a>
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