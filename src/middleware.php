<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

$app->add(new \Slim\Middleware\Session([
    'name' => 'toiqgogo_session',
    'autorefresh' => true,
    'lifetime' => '1 minutes'
]));
