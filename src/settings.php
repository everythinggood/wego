<?php

$env = new Dotenv\Dotenv(__DIR__.'/../');
$env->load();

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        //twig view
        'twigConfig'=>[
            'template'=>__DIR__.'/../templates',
            'options'=>[
                'cache'=>__DIR__.'/../cache',
                'debug'=>true
            ]
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        'wx' => [
            'appId' => $_ENV['appId'],
            'appSecret' => $_ENV['appSecret'],
            'redirect-url' => $_ENV['redirect-url'],
            'machine-scan-url' => $_ENV['machine-scan-url']
        ],

        'wxConfig' => [
            'app_id' => $_ENV['appId'],
            'secret' => $_ENV['appSecret'],
            'token' => $_ENV['token'],

            'oauth'=>[
                'scopes'=>['snsapi_userinfo'],
                'callback' => '/wx/oauthCallback'
            ],

            'response_type' => 'array',

            'log' => [
                'level' => 'debug',
                'file' => __DIR__.'/../logs/wechat.log',
            ],
        ],

        'wxPaymentConfig' => [
            'sandbox'           => $_ENV['sandbox'],// 设置为 false 或注释则关闭沙箱模式
            // 必要配置
            'app_id'             => $_ENV['app_id'],
            'mch_id'             => $_ENV['mch_id'],
            'key'                => $_ENV['key'],   // API 密钥

            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path'          => __DIR__.'/../security/cert.pem', // XXX: 绝对路径！！！！
            'key_path'           => __DIR__.'/../security/key',      // XXX: 绝对路径！！！！

            'notify_url'         => $_ENV['notify_url'],     // 你也可以在下单时单独设置来想覆盖它
        ],

        'doctrine' => [
            // if true, metadata caching is forcefully disabled
            'dev_mode' => true,

            // path where the compiled metadata info will be cached
            // make sure the path exists and it is writable
            'cache_dir' => __DIR__ . '/../cache',

            // you should add any other path containing annotated entity classes
            'metadata_dirs' => [__DIR__ . '/Domain'],

            'connection' => [
                'driver' => 'pdo_mysql',
                'host' => $_ENV['host'],
                'port' => $_ENV['port'],
                'dbname' => $_ENV['dbname'],
                'user' => $_ENV['user'],
                'password' => $_ENV['password'],
                'charset' => $_ENV['charset']
            ]
        ]
    ],
];
