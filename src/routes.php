<?php

//use Slim\Http\Request;
//use Slim\Http\Response;

// Routes


$app->group('/wx', function () {

    $this->any('/bootstrap', \Action\OfficialAccount\BootstrapAction::class);

    $this->any('/index', \Action\OfficialAccount\IndexAction::class)->setName('wx_index');

    $this->get('/qrCode', \Action\OfficialAccount\QrCodeGeneratorAction::class);

    $this->any('/oauthCallback', \Action\OfficialAccount\OauthCallbackAction::class);

});

$app->group('/test', function () {

    $this->get('/adCode', \Action\Test\TestViewAction::class . ":adCode");

    $this->get('/offLine', \Action\Test\TestViewAction::class . ":offLine");

    $this->get('/fail', \Action\Test\TestViewAction::class . ":fail");

    $this->get('/success', \Action\Test\TestViewAction::class . ":success");

});
