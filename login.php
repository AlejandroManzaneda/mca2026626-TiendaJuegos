<?php
session_start();
require 'config.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Accede a los datos del formulario usando los nombres de los campos
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepara y ejecuta la consulta
    $stmt = $pdo->prepare('SELECT id, password, role_id FROM usuarios WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Verifica las credenciales
    if ($user && password_verify($password, $user['password'])) {
        // Inicia la sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role_id'] = $user['role_id'];
        header('Location: index.php');
        exit;
    } else {
        echo 'Credenciales incorrectas';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <a href="register.php">Registrar</a>
</body>
</html>
