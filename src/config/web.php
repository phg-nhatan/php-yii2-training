<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'KOKVqVKTFlLnH9omDOFfyHhTeneUF24G',
            'parsers' => [
                'application/json' => yii\web\JsonParser::class, // Cái này để bật truyền JSON cho request body
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => app\models\User::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['auth/login'], // Router tới khi k session
        ],
        'session' => [
            'class' => yii\web\Session::class,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => true,
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
        'db' => $db,
        'urlManager' => [ // Chú ý đoạn này cần cấu hình khi thêm API
            'class' => yii\web\UrlManager::class,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // render
                'admin/users'                   => 'user-web/index',
                'admin/users/create'            => 'user-web/create',
                'admin/users/<id:\d+>'          => 'user-web/view',
                'admin/users/<id:\d+>/update'   => 'user-web/update',
                'admin/users/<id:\d+>/delete'   => 'user-web/delete',
                // rest
                [
                    'class' => yii\rest\UrlRule::class,
                    'controller' => ['user'],
                    'pluralize' => true,
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
