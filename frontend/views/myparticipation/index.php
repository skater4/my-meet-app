<?php
use yii\helpers\Url;
use app\models\ActivityPhotos;
use yii\widgets\LinkPager;
$this->title = Yii::t('common', 'Мои участия');
?>
    <h1><?=Yii::t('common', 'Мои участия')?></h1>
<?php
$this->params['breadcrumbs'][] = Yii::t('common', 'Входящие заявки');
$i = 1;
?>
    <div class='row'>
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
                        <h5 class="card-title"><?=$activity->name?></h5>
                        <p class="card-text"><?=$activity->description?></p>
                        <p class="card-text"><?=Yii::t('common', 'Начало')?> <?=date('d.m.Y H:i:s', $activity->date_from)?></p>
                        <p class="card-text"><?=Yii::t('common', 'Окончание')?> <?=date('d.m.Y H:i:s', $activity->date_to)?></p>
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
    </div>
<?php
//if ($i < 3) echo "</div>";
?>