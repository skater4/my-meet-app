<?php
use dosamigos\fileupload\FileUploadUI;
?>

<div class="row">
    <div class="col-lg-8">
        <?= FileUploadUI::widget([
            'model' => $model,
            'attribute' => 'image',
            'url' => ['myactivity/update/', 'id' => $activity_id, 'act' => 'getphotos'],
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
                console.log(data);
                            }',
                'fileuploadfail' => 'function(e, data) {
                console.log(data);
                            }',
            ],
        ]); ?>
    </div>
</div>