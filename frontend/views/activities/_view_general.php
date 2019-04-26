<?php
use app\models\Countries;
use dosamigos\gallery\Gallery;
use app\models\ActivityCategories;
use yii\helpers\Url;
?>

<div class="row">
    <div class="col-lg-3">
        <p><b><?=Yii::t('common', 'Страна')?></b></p>
        <?php
        $suffix = '';
        if (Yii::$app->language != 'ru') $suffix .= '_' . Yii::$app->language;
        $param = 'country' . $suffix;
        $country = Countries::getByCityId($model->city_id);
        ?>
        <p><?=$country->$param?></p>
        <p><b><?=Yii::t('common', 'Город')?></b></p>
        <?php
        $city = Countries::find()->where(['id' => $model->city_id])->one();
        ?>
        <p><?=$city['city' . $suffix]?></p>
        <p><b><?=Yii::t('common', 'Место')?></b></p>
        <p><?=$model->location?></p>
        <p><b><?=Yii::t('common', 'Категория')?></b></p>
        <p><?=ActivityCategories::getCategoryName($model->category_id)?></p>
        <p><b><?=Yii::t('common', 'Контакты')?></b></p>
        <p><?=$model->contacts?></p>
        <p><b><?=Yii::t('common', 'Начало')?></b></p>
        <p><?=date("d.m.Y H:i:s", $model->date_from)?></p>
        <p><b><?=Yii::t('common', 'Окончание')?></b></p>
        <p><?=date("d.m.Y H:i:s", $model->date_to)?></p>
        <p><b><?=Yii::t('common', 'Макс. кол-во участников')?></b></p>
        <p><?=$model->max_users?></p>
    </div>
    <div class="col-lg-9">
        <div class="row" style="padding-top: 10px;">
            <div class="col-lg-2"><b><?=Yii::t('common', 'Организатор')?></b></div>
            <a href="<?=Url::to(['user/view', 'id' => $user->id])?>">
                <div class="col-lg-2">
                    <?php
                    if (empty($user->avatar->thumbnail_path)) $image = '/frontend/web/img/no_image.png';
                    else $image = $user->avatar->thumbnail_path;
                    ?>
                    <img src="<?=$image?>" style="max-width: 100px;max-height: 100px;">
                </div>
                <div class="col-lg-2">
                    <div class="form-group field-profileupdateform-lastname required has-success">
                        <div class="controls">
                            <?=$user->firstname?> <?=$user->lastname?>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="row" style="padding-top: 10px;">
            <p style="padding-top: 10px;"><?=$model->description?></p>
        </div>
        <hr>
        <div class="col-lg-9">
            <?= dosamigos\gallery\Gallery::widget(['items' => $images]);?>
        </div>
    </div>
</div>