<?php
// DIC configuration


$container = $app->getContainer();

// view twig
$container[\Contract\Container::NAME_VIEW] = function (\Psr\Container\ContainerInterface $c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container[\Contract\Container::NAME_LOGGER] = function (\Psr\Container\ContainerInterface $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container[\Contract\Container::NAME_SETTING] = function (\Psr\Container\ContainerInterface $c){
  return $c->get('settings');
};

$container[\Contract\Container::NAME_HTTP_CLIENT] = function (){
    $client = new \GuzzleHttp\Client();
    return $client;
};

$container[\Contract\Container::NAME_HANDLER_WX_JS] = function ($c){
    return new \Handler\WxJsHandler($c);
};

$container[\Contract\Container::NAME_HANDLER_BACKED] = function ($c){
    return new \Handler\BackedHandler($c);
};


$container[\Contract\Container::NAME_WX_APP] = function (\Psr\Container\ContainerInterface $c){

    $config = $c->get('setting')['wxConfig'];

    $app = \EasyWeChat\Factory::officialAccount($config);

    return $app;

};

$container[\Contract\Container::NAME_WX_PAYMENT] = function (\Psr\Container\ContainerInterface $c){

    $config = $c->get('setting')['wxPaymentConfig'];

    $app = \EasyWeChat\Factory::payment($config);

    return $app;

};

$container[\Doctrine\ORM\EntityManager::class] = function (\Slim\Container $container): \Doctrine\ORM\EntityManager {
    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
        $container['settings']['doctrine']['metadata_dirs'],
        $container['settings']['doctrine']['dev_mode']
    );

    $config->setMetadataDriverImpl(
        new \Doctrine\ORM\Mapping\Driver\AnnotationDriver(
            new \Doctrine\Common\Annotations\AnnotationReader(),
            $container['settings']['doctrine']['metadata_dirs']
        )
    );

    $config->setMetadataCacheImpl(
        new \Doctrine\Common\Cache\FilesystemCache(
            $container['settings']['doctrine']['cache_dir']
        )
    );

    return \Doctrine\ORM\EntityManager::create(
        $container['settings']['doctrine']['connection'],
        $config
    );
};

$container[\Contract\Container::NAME_REDIS] = function (\Slim\Container $container){

    $redis = new Redis();
    $redis->connect('redis');
//    $redis->auth('zhise');
    $redis->select(1);
    return $redis;
};

$container[\Contract\Container::NAME_SESSION] = function (\Psr\Container\ContainerInterface $container){
    return new \SlimSession\Helper();
};


require __DIR__.'/services.php';












//errorHandler
//$container['errorHandler'] = function ($c) {
//    return function ($request, $response, $exception) use ($c) {
//        /** @var \Monolog\Logger $logger */
//        $logger = $c[\Contract\Container::NAME_LOGGER];
//        $logger->error(strval($exception), (array)$request);
//        return $c['response']->withStatus(500)
//            ->withHeader('Content-Type', 'text/html')
//            ->write('Something went wrong!');
//    };
//};
//
//$container['phpErrorHandler'] = function ($c) {
//    return function ($request, $response, $error) use ($c) {
//        /** @var \Monolog\Logger $logger */
//        $logger = $c[\Contract\Container::NAME_LOGGER];
//        $logger->error(strval($error), (array)$request);
//        return $c['response']
//            ->withStatus(500)
//            ->withHeader('Content-Type', 'text/html')
//            ->write('Something went wrong!');
//    };
//};