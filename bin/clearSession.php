<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 6/13/18
 * Time: 3:52 PM
 */
require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

$sHelper = new \SlimSession\Helper();
$sHelper::destroy();



