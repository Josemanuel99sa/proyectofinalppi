<?php
session_start();
$db = new mysqli('localhost', 'root', '', 'figuras');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit();
}

// Procesar el pago
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Obtener items del carrito
    $usuario_id = $_SESSION['usuario_id'];
    $carrito = $db->query("
        SELECT c.idproducto, c.cantidad, p.precio 
        FROM carrito c
        JOIN productos p ON c.idproducto = p.idproducto
        WHERE c.idusuario = $usuario_id
    ");
    
    // 2. Registrar en historial y actualizar stock
    while ($item = $carrito->fetch_assoc()) {
        // Registrar compra
        $db->query("
            INSERT INTO historial (usuario, producto, cantidad, fecha) 
            VALUES ($usuario_id, {$item['idproducto']}, {$item['cantidad']}, NOW())
        ");
        
        // Actualizar stock
        $db->query("
            UPDATE productos 
            SET cantidad_almacen = cantidad_almacen - {$item['cantidad']}
            WHERE idproducto = {$item['idproducto']}
        ");
    }
    
    // 3. Vaciar carrito
    $db->query("DELETE FROM carrito WHERE idusuario = $usuario_id");
    
    $_SESSION['mensaje'] = "Compra realizada con éxito!";
    header("Location: historial.php");
    exit();
}

// Mostrar resumen de compra
$total = 0;
$items = $db->query("
    SELECT p.nombre, p.precio, c.cantidad, (p.precio * c.cantidad) as subtotal
    FROM carrito c
    JOIN productos p ON c.idproducto = p.idproducto
    WHERE c.idusuario = {$_SESSION['usuario_id']}
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Confirmar Compra</h2>
        
        <table class="table">
            <tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th></tr>
            <?php while($item = $items->fetch_assoc()): ?>
                <tr>
                    <td><?= $item['nombre'] ?></td>
                    <td>$<?= number_format($item['precio'], 2) ?></td>
                    <td><?= $item['cantidad'] ?></td>
                    <td>$<?= number_format($item['subtotal'], 2) ?></td>
                </tr>
                <?php $total += $item['subtotal']; ?>
            <?php endwhile; ?>
            <tr><td colspan="3">Total</td><td>$<?= number_format($total, 2) ?></td></tr>
        </table>
        
        <form method="post">
            <h4>Datos de Pago</h4>
            <div class="mb-3">
                <label class="form-label">Número de Tarjeta</label>
                <input type="text" class="form-control" value="**** **** **** <?= substr($_SESSION['tarjeta'] ?? '', -4) ?>" readonly>
            </div>
            <button type="submit" class="btn btn-success">Confirmar Pago</button>
            <a href="carrito.php" class="btn btn-secondary">Volver al carrito</a>
        </form>
    </div>
</body>
</html>