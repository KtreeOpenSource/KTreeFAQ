<?php

$params = require(__DIR__ . '/params.php');
$basePath =  dirname(__DIR__);
$config = [

    'id' => 'KTreeFAQ',
    'name' => 'KTreeFAQ',
    'basePath' => dirname(__DIR__),
    'language'=>'EN',

    'components' => [
	'assetManager' => [
    	'basePath' => '@webroot/static',
	],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'KTreeFAQ_MAIN',
	    'csrfParam' => '_KTreeFAQ_MAINCSRF',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'generalCache' => [
            //'class' => 'yii\caching\DummyCache'
            'class' => 'yii\caching\FileCache'
        ],

        'user' => [
            'identityClass' => 'app\modules\users\models\Users',
            'enableAutoLogin' => true,
	    'identityCookie' => [
                'name' => 'KTreeFAQ_MAIN', // unique for frontend
            ]
        ],
        'session' => [
            'name' => 'PHPSESSIONKTreeFAQ_MAIN',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
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
        'db' => require(__DIR__ . '/db.php'),
    ],
    'modules' => [
        'filemanager' => [
            'class' => 'pendalf89\filemanager\Module',
            // Upload routes
            'routes' => [
                // Base absolute path to web directory
                'baseUrl' => '',
                // Base web directory url
                'basePath' => dirname(__DIR__).'/',
                // Path for uploaded files in web directory
                'uploadPath' => 'upload',
            ],
            // Thumbnails info
            'thumbs' => [
                'small' => [
                    'name' => 'Small',
                    'size' => [100, 100],
                ],
                'medium' => [
                    'name' => 'Medium',
                    'size' => [300, 200],
                ],
                'large' => [
                    'name' => 'Large',
                    'size' => [500, 400],
                ],
            ],
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
        'treemanager' =>  [
            'class' => '\kartik\tree\Module',
        ],
        'user' => [
            'class' => 'app\modules\users\UsersModule',
        ],
        'adminSettings' => [
            'class' => 'app\modules\adminSettingsConfig\AdminSettingsModule',
        ],
        'admin' => [
            'class' => 'app\modules\admin\AdminModule',
        ],


    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['bootstrap'][] = 'admin';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'] // adjust this to your needs

    ];
}

//return array_merge_recursive($config, require($basePath . '/vendor/noumo/easyii/config/easyii.php'));
return $config;
