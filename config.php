<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'juegos_db';
$user = 'root';
$password = '';

// Establece la conexión a la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
