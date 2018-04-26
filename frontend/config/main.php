<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => common\models\User::className(),
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => yii\log\FileTarget::className(),
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => yii\log\EmailTarget::className(),
                    'levels' => ['error', 'warning'],
                    'except' => [
                        'yii\debug\Module::checkAccess',
                    ],
                    'message' => [
                        'to' => ['admin@feehi.com', 'liufee@126.com'],//当触发levels配置的错误级别时，发送到此些邮箱（请改成自己的邮箱）
                        'subject' => '来自 Feehi CMS 前台的新日志消息',
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'cache' => [
            'class' => yii\caching\FileCache::className(),//使用文件缓存，可根据需要改成apc redis memcache等其他缓存方式
            'keyPrefix' => 'frontend',       // 唯一键前缀
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,//隐藏index.php
            'enableStrictParsing' => false,
            //'suffix' => '.html',//后缀，如果设置了此项，那么浏览器地址栏就必须带上.html后缀，否则会报404错误
            'rules' => [
                //'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                //'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>?id=<id>'
                //'detail/<id:\d+>' => 'site/detail?id=$id',
                //'post/22'=>'site/detail',
                //'<controller:detail>/<id:\d+>' => '<controller>/index',
                '' => 'article/index',
                '<page:\d+>' => 'article/index',
                'login' => 'site/login',
                'signup' => 'site/signup',
                'view/<id:\d+>' => 'article/view',
                'page/<name:\w+>' => 'page/view',
                'comment' => 'article/comment',
                'search' => 'search/index',
                'tag/<tag:\w+>' => 'search/tag',
                'rss' => 'article/rss',
                'list/<page:\d+>' => 'site/index',
            ],
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => yii\i18n\PhpMessageSource::className(),
                    'basePath' => '@frontend/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
                'cms*' => [
                    'class' => yii\i18n\PhpMessageSource::className(),
                    'basePath' => '@cms/frontend/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'cms' => 'app.php',
                    ],
                ],
            ],
        ],
        'assetManager' => [
            'bundles' => [
                yii\web\JqueryAsset::className() => [
                    'js' => [],
                ],
                frontend\assets\AppAsset::className() => [
                    'css' => [
                        'a' => 'static/css/style.css',
                        'b' => 'static/plugins/toastr/toastr.min.css',
                    ],
                    'js' => [
                        'a' => 'static/js/jquery.min.js',
                        'b' => 'static/js/index.js',
                        'c' => 'static/plugins/toastr/toastr.min.js',
                    ],
                ],
                frontend\assets\IndexAsset::className() => [
                    'js' => [
                        'a' => 'static/js/responsiveslides.min.js',
                    ]
                ],
                frontend\assets\ViewAsset::className() => [
                    'css' => [
                        'a' => 'static/syntaxhighlighter/styles/shCoreDefault.css'
                    ],
                    'js' => [
                        'a' => 'static/syntaxhighlighter/scripts/shCore.js',
                        'b' => 'static/syntaxhighlighter/scripts/shBrushJScript.js',
                        'c' => 'static/syntaxhighlighter/scripts/shBrushPython.js',
                        'd' => 'static/syntaxhighlighter/scripts/shBrushPhp.js',
                        'e' => 'static/syntaxhighlighter/scripts/shBrushJava.js',
                        'f' =>'static/syntaxhighlighter/scripts/shBrushCss.js',
                    ]
                ],
            ]
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@frontend/views' => ['@frontend/views', '@cms/frontend/views'],
                ],
            ],
        ]
    ],
    'params' => $params,
    'on beforeRequest' => function($event){
        feehi\components\Feehi::frontendInit();
        if(isset(\yii::$app->session['view'])) \yii::$app->viewPath = dirname(__DIR__).'/'.\yii::$app->session['view'];
        if(isset(\yii::$app->session['language'])) \yii::$app->language = yii::$app->session['language'];
    }
];
