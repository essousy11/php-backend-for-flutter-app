<?php
require 'vendor/autoload.php';
//include '../connect.php';
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

$paypal = new ApiContext(
    new OAuthTokenCredential(
        'AS97aTfO38WuAejQGNkawFv93ydYpOPwsMJ1O0weEFSy3PDHJWQqF43o3aVZKcYteSVsNiPVeWmFaSKq',     // ClientID
        'EGS-mbo92F1Tq55feM64_q-7TTyI5Coohnxm4AtVBJgLD-9oCg-KNdIAZ_wf4HDmbevGqcKE-kvLaKVA'  // ClientSecret
    )
);

$paypal->setConfig([
    'mode' => 'sandbox', // or 'live'
    'http.ConnectionTimeOut' => 30,
    'log.LogEnabled' => false,
    'log.FileName' => '',
    'log.LogLevel' => 'FINE', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
    'validation.level' => 'log'
]);
