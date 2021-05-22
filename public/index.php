<?php

require_once __DIR__."/../vendor/autoload.php";

use App\Main;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

$client = new \GuzzleHttp\Client([
    'base_uri'=>$_ENV['URL']
]);

$main = new Main($client);
$main->processRequest();
