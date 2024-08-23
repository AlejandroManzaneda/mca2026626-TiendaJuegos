<?php
session_start();
require 'config.php'; // Asegúrate de que config.php contiene la configuración de la base de datos

// Verificar si se ha enviado un ID de juego
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Juego no válido.');
}

$juego_id = intval($_GET['id']);

// Consulta para obtener detalles del juego
$stmt = $pdo->prepare('SELECT id, nombre, precio, cantidad FROM juegos WHERE id = ?');
$stmt->execute([$juego_id]);
$juego = $stmt->fetch();

if (!$juego) {
    die('Juego no encontrado.');
}

// Inicializar variables para el cálculo
$subtotal = 0;
$cambio = 0;

// Procesar la orden si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cantidad = intval($_POST['cantidad']);
    $total_pagado = floatval($_POST['total_pagado']);

    if ($cantidad > 0 && $cantidad <= $juego['cantidad']) {
        // Calcular subtotal y total
        $subtotal = $juego['precio'] * $cantidad;
        $cambio = $total_pagado - $subtotal;

        if ($cambio >= 0) {
            // Insertar venta en la base de datos
            $stmt = $pdo->prepare('INSERT INTO ventas (juego_id, cantidad, total, pagado, cambio) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$juego_id, $cantidad, $subtotal, $total_pagado, $cambio]);

            // Actualizar cantidad de juegos disponibles
            $stmt = $pdo->prepare('UPDATE juegos SET cantidad = cantidad - ? WHERE id = ?');
            $stmt->execute([$cantidad, $juego_id]);

            // Redirigir a la página de recibo con los detalles
            header('Location: recibo.php?id=' . urlencode($juego_id) . '&cantidad=' . urlencode($cantidad) . '&total=' . urlencode($subtotal) . '&pagado=' . urlencode($total_pagado) . '&cambio=' . urlencode($cambio));
            exit;
        } else {
            echo 'El total pagado es insuficiente para cubrir el subtotal.';
        }
    } else {
        echo 'Cantidad no válida.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detalles del Juego</title>
    <script>
        function updateTotals() {
            var precio = parseFloat(document.getElementById('precio').value);
            var cantidad = parseInt(document.getElementById('cantidad').value, 10);
            var total = precio * cantidad;
            var totalPagado = parseFloat(document.getElementById('total_pagado').value);
            var cambio = totalPagado - total;

            document.getElementById('total').value = isNaN(total) ? '' : total.toFixed(2);
            document.getElementById('cambio').value = isNaN(cambio) ? '' : cambio.toFixed(2);
        }
    </script>
</head>
<body>
    <h1>Detalles del Juego</h1>
    <div>
        <h2><?php echo htmlspecialchars($juego['nombre']); ?></h2>
        <p>Precio: $<?php echo number_format($juego['precio'], 2); ?></p>
        <p>Disponible: <?php echo $juego['cantidad']; ?> unidades</p>

        <form method="post" action="">
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" min="1" max="<?php echo $juego['cantidad']; ?>" required oninput="updateTotals()">
            <br><br>

            <label for="total">Total:</label>
            <input type="text" id="total" value="<?php echo number_format($subtotal, 2); ?>" readonly>
            <br><br>

            <label for="total_pagado">Total Pagado:</label>
            <input type="text" name="total_pagado" id="total_pagado" placeholder="Ingrese el total pagado" required oninput="updateTotals()">
            <br><br>

            <label for="cambio">Cambio:</label>
            <input type="text" id="cambio" value="<?php echo number_format($cambio, 2); ?>" readonly>
            <br><br>

            <button type="submit">Comprar</button>
        </form>
        <a href="index.php">Volver a la lista</a>
    </div>
</body>
</html>
