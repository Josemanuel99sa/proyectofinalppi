<?php
session_start();
$db = new mysqli('localhost', 'root', '', 'figuras');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mi Historial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Mis Compras</h2>
            <a href="index.php" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Volver a la Tienda
            </a>
        </div>
        
        <?php
        $compras = $db->query("
            SELECT h.fecha, p.nombre, p.precio, h.cantidad, (p.precio * h.cantidad) as total
            FROM historial h
            JOIN productos p ON h.producto = p.idproducto
            WHERE h.usuario = {$_SESSION['usuario_id']}
            ORDER BY h.fecha DESC
        ");
        
        if ($compras->num_rows > 0): ?>
            <table class="table">
                <tr><th>Fecha</th><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Total</th></tr>
                <?php while($compra = $compras->fetch_assoc()): ?>
                    <tr>
                        <td><?= $compra['fecha'] ?></td>
                        <td><?= $compra['nombre'] ?></td>
                        <td>$<?= number_format($compra['precio'], 2) ?></td>
                        <td><?= $compra['cantidad'] ?></td>
                        <td>$<?= number_format($compra['total'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <div class="alert alert-info">
                No tienes compras registradas
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap Icons (necesario para el ícono de flecha) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>