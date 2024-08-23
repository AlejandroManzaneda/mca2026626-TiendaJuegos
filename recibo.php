<?php
if (!isset($_GET['id']) || !isset($_GET['cantidad']) || !isset($_GET['total']) || !isset($_GET['pagado']) || !isset($_GET['cambio'])) {
    die('Datos inválidos.');
}

// Obtener los datos de la URL
$juego_id = intval($_GET['id']);
$cantidad = intval($_GET['cantidad']);
$total = floatval($_GET['total']);
$pagado = floatval($_GET['pagado']);
$cambio = floatval($_GET['cambio']);

// Consulta para obtener detalles del juego
require 'config.php';
$stmt = $pdo->prepare('SELECT nombre, precio FROM juegos WHERE id = ?');
$stmt->execute([$juego_id]);
$juego = $stmt->fetch();

if (!$juego) {
    die('Juego no encontrado.');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recibo de Compra</title>
</head>
<body>
    <h1>Recibo de Compra</h1>
    <div>
        <h2><?php echo htmlspecialchars($juego['nombre']); ?></h2>
        <p>Precio por Unidad: $<?php echo number_format($juego['precio'], 2); ?></p>
        <p>Cantidad Comprada: <?php echo htmlspecialchars($cantidad); ?></p>
        <p>Subtotal: $<?php echo number_format($total, 2); ?></p>
        <p>Total Pagado: $<?php echo number_format($pagado, 2); ?></p>
        <p>Cambio a Devolver: $<?php echo number_format($cambio, 2); ?></p>
        <a href="index.php">Volver a la lista</a>
    </div>
</body>
</html>
