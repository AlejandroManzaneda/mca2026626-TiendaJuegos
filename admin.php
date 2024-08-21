<?php
session_start();
require 'config.php'; 

if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header('Location: login.php');
    exit;
}

// Agregar, editar o eliminar juegos según la acción
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $id = $_POST['id'] ?? null;

    if ($action == 'add') {
        $stmt = $pdo->prepare('INSERT INTO juegos (nombre, precio, cantidad) VALUES (?, ?, ?)');
        $stmt->execute([$nombre, $precio, $cantidad]);
    } elseif ($action == 'edit' && $id) {
        $stmt = $pdo->prepare('UPDATE juegos SET nombre = ?, precio = ?, cantidad = ? WHERE id = ?');
        $stmt->execute([$nombre, $precio, $cantidad, $id]);
    } elseif ($action == 'delete' && $id) {
        $stmt = $pdo->prepare('DELETE FROM juegos WHERE id = ?');
        $stmt->execute([$id]);
    }
}

$juegos = $pdo->query('SELECT * FROM juegos')->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Juegos</title>
</head>
<body>
    <h1>Gestión de Juegos</h1>
    <form method="post" action="">
        <input type="hidden" name="action" value="add">
        <input type="text" name="nombre" placeholder="Nombre del juego" required>
        <input type="text" name="precio" placeholder="Precio" required>
        <input type="text" name="cantidad" placeholder="Cantidad" required>
        <button type="submit">Agregar Juego</button>
    </form>
    
    <h2>Juegos Existentes</h2>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($juegos as $juego): ?>
        <tr>
            <form method="post" action="">
                <input type="hidden" name="id" value="<?= $juego['id'] ?>">
                <input type="hidden" name="action" value="edit">
                <td><input type="text" name="nombre" value="<?= $juego['nombre'] ?>" required></td>
                <td><input type="text" name="precio" value="<?= $juego['precio'] ?>" required></td>
                <td><input type="text" name="cantidad" value="<?= $juego['cantidad'] ?>" required></td>
                <td>
                    <button type="submit">Actualizar</button>
                    <button type="submit" name="action" value="delete">Eliminar</button>
                </td>
            </form>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
