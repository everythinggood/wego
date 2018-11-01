<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 5/23/18
 * Time: 11:44 AM
 * @param \Slim\Container $container
 * @return \Service\BillService
 */

$container[\Service\BillService::class] = function (\Psr\Container\ContainerInterface $container) {
    $em = $container[\Doctrine\ORM\EntityManager::class];
    return new \Service\BillService($em);
};

$container[\Service\AdBIllService::class] = function (\Psr\Container\ContainerInterface $container) {
    $em = $container[\Doctrine\ORM\EntityManager::class];
    return new \Service\AdBIllService($em);
};