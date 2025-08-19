<?php
require_once '../config.php';
require_once '../plantillas/plantilla1.php';
$plantilla = Plantilla1::aplicar();

session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND rol = 'validador' LIMIT 1");
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && hash('sha256', $password) === $usuario['password']) {
        $_SESSION['validador_id'] = $usuario['id'];
        $_SESSION['validador_nombre'] = $usuario['nombre'];
        header('Location: /Incident-Hub/super/index.php'); 
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<div class="hero">
    <div class="overlay"></div>
    <div class="container-incident">
        <h1>Ingreso Validador</h1>
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
            <button type="submit" class="btn btn-validador">Ingresar</button>
        </form>
    </div>
</div>

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), 
                url('../img/connor-betts-QK6Iwzd5MhE-unsplash.jpg') no-repeat center center/cover;
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

.btn-validador {
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

.btn-validador:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

.alert-error {
    background: #ff4d4f;
    color: #fff;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
}
</style>
