<?php
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?if (!empty($participants)):?>
    <br>
    <div class="participants-table">
        <?php $form = ActiveForm::begin(); ?>
        <?php foreach ($participants as $participant):?>
        <?if (empty($participant->user)) continue;?>
            <div class="row">
                <a href="<?=Url::to(['user/view', 'id' => $participant->user->id])?>">
                    <div class="col-lg-1">
                        <?php
                        if (empty($participant->avatar->thumbnail_path)) $image = Yii::getAlias('@web/img/no_image.png');
                        else $image = $participant->avatar->thumbnail_path;
                        ?>
                        <img src="<?=$image?>" class="part-avtr">
                    </div>
                    <div class="col-lg-1">
                        <div class="form-group field-profileupdateform-lastname required has-success">
                            <div class="controls">
                                <?=$participant->user->firstname?> <?=$participant->user->lastname?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <hr>
        <?php endforeach;?>
        <?php ActiveForm::end(); ?>
    </div>
<?php
echo LinkPager::widget([
    'pagination' => $pages,
]);
?>
<?endif;?>