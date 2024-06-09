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
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Nb0h-rnlNSE9XnRttJJbGwKw6QN0AEwd',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser'
            ]
            
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mongodb' => require(__DIR__ . '/db.php'),
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
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
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'POST product/create' => 'product/create',
                'GET products/get-all-products' => 'product/get-all-products',
                'POST product/get-by-id' => 'product/get-product-by-id',
                'POST product/delete' => 'product/delete-product-by-id',
                'POST product/update' => 'product/update-product-by-id',
                'GET product/get-by-header-id' => 'product/get-product-by-id-from-header',
                'POST product/login' => 'product/login',
                'GET product/get-by-header-id-jwt' => 'product/get-product-by-id-from-header-jwt',
                'PUT product/update-put' => 'product/update-product-put',
                'DELETE product/delete/<id:\w+>' => 'product/delete',
            ],
        ],
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
