<?php
use app\models\UserPhotos;
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'assignmentTable'=>'authassignment',
        'mymessages' => [
            //Обязательно
            'class'    => 'vision\messages\components\MyMessages',
            //не обязательно
            //класс модели пользователей
            //по-умолчанию \Yii::$app->user->identityClass
            'modelUser' => 'common\models\User',
            //имя контроллера где разместили action
            'nameController' => 'ru/user',
            //не обязательно
            //имя поля в таблице пользователей которое будет использоваться в качестве имени
            //по-умолчанию username
            'attributeNameUser' => 'username',
            //не обязательно
            //можно указать роли и/или id пользователей которые будут видны в списке контактов всем кто не подпадает
            //в эту выборку, при этом указанные пользователи будут и смогут писать всем зарегестрированным пользователям
            //'admins' => ['admin', 17],
            //не обязательно
            //включение возможности дублировать сообщение на email
            //для работы данной функции в проектк должна быть реализована отправка почты штатными средствами фреймворка
            //'enableEmail' => true,
            //задаем функцию для возврата адреса почты
            //в качестве аргумента передается объект модели пользователя
            'getEmail' => function($user_model) {
                return $user_model->email;
            },
            //задаем функцию для возврата лого пользователей в списке контактов (для виджета cloud)
            //в качестве аргумента передается id пользователя
            'getLogo' => function($user_id) {
                $avatar = UserPhotos::getAvatar($user_id);
                if (empty($avatar)) $avatar = 'frontend/web/img/no_image.png';
                else $avatar = $avatar->thumbnail_path;
                return $avatar;
            },
            //указываем шаблоны сообщений, в них будет передаваться сообщение $message
            'templateEmail' => [
                'html' => 'private-message-text',
                'text' => 'private-message-html'
            ],
            //тема письма
            'subject' => 'Private message'
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'assetManager' => [
            'linkAssets' => true,
            'appendTimestamp' => true,
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'class' => 'codemix\localeurls\UrlManager',
            'languages' => ['ru', 'en'],
            'enableDefaultLanguageUrlCode' => true,
            'rules' => [
                '' => 'site/index',
                'user' => 'user/index',
                'user/view/<id:\d+>' => 'user/view',
                'activities/view/<id:\d+>' => 'activities/view',
                'activities/page/<page:\d+>' => 'activities/index',
                'myparticipation/<page:\d+>' => 'myparticipation/index',
                'newmessages/<page:\d+>' => 'newmessages/index',
                'activities' => 'activities/index',
                'myactivities/page/<page:\d+>' => 'myactivity/index',
                'myactivity/update/<id:\d+>' => 'myactivity/update',
                'myactivity/delete/<id:\d+>' => 'myactivity/delete',
                'participants/incoming/<page:\d+>' => 'participants/incoming',
                'participants/outgoing/<page:\d+>' => 'participants/outgoing',
                'myactivity/setmainactivityphoto/<id:\d+>' => 'myactivity/setmainactivityphoto',
                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
            ],
        ],
        'mymenu' => [
            'class' => 'app\components\MyMenu'
        ]
    ],
    'params' => $params,
];
