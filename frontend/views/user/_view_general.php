<?php
use app\models\Countries;
?>

<p><b><?=Yii::t('common', 'Имя');?></b></p>
<p><?=$model->firstname?> <?=$model->lastname?></p>
<hr>
<p><b><?=Yii::t('common', 'Дата рождения');?></b></p>
<p><?=date("d.m.Y", $model->birthday)?>
<hr>
<p><b><?=Yii::t('common', 'Пол');?></b></p>
<p><?=$model->genders[$model->sex]?>
<hr>
<p><b><?=Yii::t('common', 'Телефон');?></b></p>
<p><?=$model->phone?>
<hr>
<p><b><?=Yii::t('common', 'Семейное положение');?></b></p>
<p><?=$model->relationships[$model->relationship]?>
<hr>
<p><b><?=Yii::t('common', 'Страна');?></b></p>
<p><?=$model->country?>
<hr>
<p><b><?=Yii::t('common', 'Город');?></b></p>
<?php
$city = Countries::getByCityId($model->city_id);
$suffix = '';
if (Yii::$app->language != 'ru') $suffix .= '_' . Yii::$app->language;
$var = 'city' . $suffix;
?>
<p><?=$city->$var?>
<hr>
<p><b><?=Yii::t('common', 'Деятельность');?></b></p>
<p><?=$model->activity?>
<hr>
<p><b><?=Yii::t('common', 'Интересы');?></b></p>
<p><?=$model->interests?>
<hr>
<p><b><?=Yii::t('common', 'Учеба');?></b></p>
<p><?=$model->study?>
<hr>
<p><b><?=Yii::t('common', 'Работа');?></b></p>
<p><?=$model->job?>