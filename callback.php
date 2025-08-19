<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php'; 
session_start();

$config = require __DIR__ . '/config_local.php';

$client = new Google_Client();
$client->setClientId($config['google_client_id']);
$client->setClientSecret($config['google_client_secret']);
$client->setRedirectUri($config['redirect_uri']);
$client->addScope(['email', 'profile']);

if (!isset($_GET['code'])) {
    exit('Error: no se recibió el código de Google.');
}

$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
if (isset($token['error'])) {
    exit('Error al obtener token: ' . htmlspecialchars($token['error_description'] ?? $token['error']));
}

$client->setAccessToken($token);

$oauth2 = new Google_Service_Oauth2($client);
$googleUser = $oauth2->userinfo->get();

$email  = $googleUser->email ?? '';
$nombre = $googleUser->name ?? '';

if (!$email) exit('No se pudo obtener el email de Google.');

$stmt = $pdo->prepare("SELECT id, nombre, email FROM usuarios WHERE email = :email AND rol = 'reportero' LIMIT 1");
$stmt->execute(['email' => $email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol, fecha_registro)
                       VALUES (:nombre, :email, :password, 'reportero', NOW())");

    $stmt->execute([
        'nombre'   => $nombre ?: explode('@', $email)[0],
        'email'    => $email,
        'password' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT)
    ]);

    $usuarioId = (int)$pdo->lastInsertId();
    $usuario = ['id' => $usuarioId, 'nombre' => $nombre, 'email' => $email];
} else {
    $usuarioId = (int)$usuario['id'];
}

$_SESSION['reportero_id']     = $usuarioId;
$_SESSION['reportero_nombre'] = $usuario['nombre'] ?: $nombre;
$_SESSION['reportero_email']  = $email;

header('Location: ../Incident-Hub/reportero/panel.php');
exit;
