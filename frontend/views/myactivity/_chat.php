<?php
use yii\helpers\Url;
use common\models\User;
use sintret\chat\ChatRoom;

echo \sintret\chat\ChatRoom::widget([
        'url' => \yii\helpers\Url::to(['/messages/send']),
        'models' => User::className(),
        'object_id' => $object_id,
        'object' => 'activity',
    ]
);
?>