<?php
use yii\widgets\Menu;
$this->title = Yii::t('common', 'Мой профиль');
?>
<h1><?=Yii::t('common', 'Мой профиль')?></h1>
<?php
$this->params['breadcrumbs'][] = Yii::t('common', 'Мой профиль');
?>
<div class="row">
    <?=Yii::$app->mymenu->userSidebarMenu();?>
    <div class="col-lg-8">Тут будет какая нибудь инфа для себя, но я хз зачем вообще надо пока</div>
</div>