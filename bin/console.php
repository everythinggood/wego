<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 5/23/18
 * Time: 10:33 AM
 */
require __DIR__ . '/../vendor/autoload.php';

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);
$container = $app->getContainer();
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

\Doctrine\ORM\Tools\Console\ConsoleRunner::run(
    \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($container[\Doctrine\ORM\EntityManager::class])
);


