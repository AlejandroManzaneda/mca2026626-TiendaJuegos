<?php
// Iniciar sesión y conectar con la base de datos
session_start();
require 'config.php'; // Asegúrate de que config.php contiene la configuración de la base de datos

// Consulta para obtener todos los juegos
$stmt = $pdo->prepare('SELECT id, nombre, precio FROM juegos');
$stmt->execute();
$juegos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Juegos Disponibles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .juego {
            border: 1px solid #ddd;
            margin: 10px;
            padding: 10px;
            border-radius: 5px;
        }
        .juego h2 {
            margin: 0;
            font-size: 20px;
        }
        .juego p {
            margin: 5px 0;
        }
        .juego button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .juego button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Juegos Disponibles</h1>
    <?php foreach ($juegos as $juego): ?>
        <div class="juego">
            <h2><?php echo htmlspecialchars($juego['nombre']); ?></h2>
            <p>Precio: $<?php echo number_format($juego['precio'], 2); ?></p>
            <a href="detalles.php?id=<?php echo $juego['id']; ?>"><button>Comprar</button></a>
        </div>
    <?php endforeach; ?>
</body>
</html>
