<?php
use yii\widgets\Menu;
use dosamigos\fileupload\FileUploadUI;
use dosamigos\gallery\Gallery;
use yii\widgets\ActiveForm;
use yii\bootstrap\Html;
$this->title = Yii::t('common', 'Мой профиль');
?>

<h1><?=Yii::t('common', 'Мой профиль')?></h1>

<div class="row">
    <?=Yii::$app->mymenu->userSidebarMenu();?>
    <div class="col-lg-8">
        <?= FileUploadUI::widget([
            'model' => $model,
            'attribute' => 'image',
            'url' => ['user/updatephotos', 'act' => 'getphotos'],
            'gallery' => false,
            'load' => true,
            'fieldOptions' => [
                'accept' => 'image/*'
            ],
            'clientOptions' => [
                'maxFileSize' => 2000000
            ],
            // ...
            'clientEvents' => [
                'fileuploaddone' => 'function(e, data) {
                            }',
                'fileuploadfail' => 'function(e, data) {
                console.log(data);
                            }',
            ],
        ]); ?>
    </div>
</div>