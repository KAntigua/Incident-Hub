<?php
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

$config = require __DIR__ . '/../config_local.php';

$client = new Google_Client();
$client->setClientId($CLIENT_ID);
$client->setClientSecret($CLIENT_SECRET);
$client->setRedirectUri($REDIRECT_URI);
$client->addScope(['email','profile']);

header('Location: ' . $client->createAuthUrl());
exit;
