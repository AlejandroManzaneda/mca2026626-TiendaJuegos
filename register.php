<?php
require 'config.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role_id = $_POST['role_id'];

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Preparar y ejecutar la consulta para insertar el nuevo usuario
    $stmt = $pdo->prepare('INSERT INTO usuarios (username, password, role_id) VALUES (?, ?, ?)');
    $stmt->execute([$username, $hashed_password, $role_id]);

    // Redirigir a la página de login
    header('Location: login.php');
    exit; // Asegúrate de detener la ejecución del script después de redirigir
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar</title>
</head>
<body>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role_id">
            <option value="1">Admin</option>
            <option value="2">Cliente</option>
        </select>
        <button type="submit">Registrar</button>
    </form>
</body>
</html>
