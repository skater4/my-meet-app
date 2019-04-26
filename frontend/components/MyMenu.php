<?php

namespace app\components;
use yii\widgets\Menu;
use yii\helpers\Url;
use Yii;
use yii\base\Component;
use yii\filters\ContentNegotiator;

class MyMenu extends Component {

    public function userSidebarMenu() {
        return '<div class="col-lg-3">' .
Menu::widget([
            'items' => [
                ['label' => Yii::t('common', 'Инфо'), 'url' => ['user/index'], 'options' => ['href' => Url::to(['user/index'])]],
                ['label' => Yii::t('common', 'Редактирование'), 'url' => ['user/update'], 'options' => ['href' => Url::to(['user/update'])]],
                ['label' => Yii::t('common', 'Фото'), 'url' => ['user/updatephotos'], 'options' => ['href' => Url::to(['user/updatephotos'])]],
            ],
            'options' => ['tag' => 'div', 'class' => 'list-group'], // обертка вместо <ul>
            'itemOptions' => ['tag'=> 'a', 'class'=> 'list-group-item'],
            'linkTemplate' => '{label}'
        ])
        . '</div>';
    }

}