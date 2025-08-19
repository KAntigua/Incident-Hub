<?php
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

$config = require __DIR__ . '/../config_local.php';

$client = new Google_Client();
$client->setClientId($config['google_client_id']);
$client->setClientSecret($config['google_client_secret']);
$client->setRedirectUri($config['redirect_uri']);
$client->addScope(['email', 'profile']);

header('Location: ' . $client->createAuthUrl());
exit;
