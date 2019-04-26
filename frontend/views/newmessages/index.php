<?php
use yii\helpers\Url;
use app\models\UserPhotos;
use yii\widgets\LinkPager;
use \vision\messages\models\Messages;
$this->title = Yii::t('common', 'Новые сообщения');
?>
    <h1><?=Yii::t('common', 'Новые сообщения')?></h1>
<?php
$this->params['breadcrumbs'][] = Yii::t('common', 'Новые сообщения');
$i = 1;
?>
    <div class='row'>
        <?php
        foreach($users as $key => $user):?>
            <?php
            $main_image = UserPhotos::getAvatarImagePath($user->id);
            if ($i == 1) echo '<div class="row">';
            ?>
            <div class='col-lg-4 activity-item'>
                <div class="card" style="width: 207px;">
                    <a href="<?=Url::to(['user/view', 'id' => $user->id])?>">
                        <img class="card-img-top" src="<?=$main_image?>">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title"><?=$user->firstname?> <?=$user->lastname?></h5>
                        <p class="card-text"><b><?=Yii::t('common', 'Новые сообщения')?>: <?=Messages::getRelatedNewMessagesCount($user->id, Yii::$app->user->id)?></b></p>
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