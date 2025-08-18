<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();

$client = new Google_Client();
$config = require __DIR__ . '/config_local.php';
$client->addScope('email');
$client->addScope('profile');

header('Location: ' . $client->createAuthUrl());
exit;
