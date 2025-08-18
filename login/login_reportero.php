<?php
require_once '../config.php';
require_once '../plantillas/plantilla1.php';
$plantilla = Plantilla1::aplicar();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND rol = 'reportero' LIMIT 1");
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && hash('sha256', $password) === $usuario['password']) {
        $_SESSION['reportero_id'] = $usuario['id'];
        $_SESSION['reportero_nombre'] = $usuario['nombre'];
        header('Location: ../Incident-Hub/reportero/index.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<div class="hero">
    <div class="overlay"></div>
    <div class="container-incident">
        <h1>Ingreso Reportero</h1>
        <?php if ($error): ?>
            <div class="alert-error"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="Correo electrónico" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Contraseña" required>
            </div>
            <button type="submit" class="btn btn-reportero">Ingresar</button>
        </form>

        <a href="login_google.php" class="btn-google">
            <img src="../img/google-logo.png" alt="Google" class="google-icon">
            Iniciar sesión con Google
        </a>
    </div>
</div>

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), 
                url('../img/anthony-maw-o1xcMHvkLVA-unsplash.jpg') no-repeat center center/cover;
    color: #fff;
}

.hero {
    position: relative;
    min-height: calc(100vh - 120px);
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.container-incident {
    position: relative;
    z-index: 1;
    background: rgba(255,255,255,0.1);
    padding: 40px;
    border-radius: 12px;
    backdrop-filter: blur(8px);
    max-width: 450px;
    width: 90%;
}

h1 {
    font-size: 2.2em;
    margin-bottom: 20px;
    font-weight: 600;
}

.form-group {
    margin-bottom: 15px;
}

input[type="email"], input[type="password"] {
    width: 100%;
    padding: 12px;
    border-radius: 6px;
    border: none;
    font-size: 1em;
}

.btn-reportero {
    display: inline-block;
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    border-radius: 6px;
    font-weight: 600;
    color: #fff;
    background: #ff9800;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-reportero:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

.btn-google {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 15px;
    padding: 12px;
    border-radius: 6px;
    font-weight: 600;
    color: #333;
    background: #fff;
    border: 1px solid #ccc;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-google:hover {
    background: #f1f1f1;
}

.google-icon {
    width: 20px;
    height: 20px;
}
.alert-error {
    background: #ff4d4f;
    color: #fff;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
}
</style>
