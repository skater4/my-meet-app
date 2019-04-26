<?php
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
    <br>
    <div class="participants-table">
        <?php $form = ActiveForm::begin(); ?>
        <?php foreach ($participants as $participant):?>
            <div class="row">
                <a href="<?=Url::to(['user/view', 'id' => $participant->user->id])?>">
                    <div class="col-lg-1">
                        <?php
                        if (empty($participant->avatar->thumbnail_path)) $image = '/frontend/web/img/no_image.png';
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
                <div class="col-lg-2">
                    <div class="form-group field-profileupdateform-lastname required has-success">
                        <div class="controls" id="status_<?=$participant->id?>">
                            <?=$participant->statuses[$participant->status]?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group field-activity-status required">
                        <div class="col-lg-3">
                        <a id="approve_<?=$participant->id?>" class="btn btn-primary <?if ($participant->status == "A"):?>hidden<?endif;?>" onclick="$.ajax({url: '<?=Url::to(['myactivity/setparticipantstatus'])?>',
                                data: {
                                participant_id: <?=$participant->id?>,
                                status: 'A',
                                }
                                });
                                $('#decline_<?=$participant->id?>').removeClass('hidden');
                                $(this).addClass('hidden');
                                $('#status_<?=$participant->id?>').html('<?=Yii::t('common', 'Одобрено')?>');
                                "><?=Yii::t('common', 'Одобрить')?></a>
                        </div>
                        <div class="col-lg-3">
                        <a id="decline_<?=$participant->id?>" class="btn btn-danger <?if ($participant->status == "D"):?>hidden<?endif;?>" onclick="
                                $.ajax({
                                url: '<?=Url::to(['myactivity/setparticipantstatus'])?>',
                                data: {
                                participant_id: <?=$participant->id?>,
                                status: 'D',
                                }
                                });
                                $('#approve_<?=$participant->id?>').removeClass('hidden');
                                $(this).addClass('hidden');
                                $('#status_<?=$participant->id?>').html('<?=Yii::t('common', 'Отклонено')?>');
                                "><?=Yii::t('common', 'Отклонить')?></a>
                        </div>
                    </div>
                </div>
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