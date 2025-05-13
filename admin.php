<?php
session_start();
$db = new mysqli('localhost', 'root', '', 'figuras');

// Verificar si es administrador (aquí debes implementar tu lógica de admin)
$es_admin = true; // Cambiar por tu verificación real
if (!$es_admin) {
    header("Location: ../index.php");
    exit();
}

// Procesar formularios de admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['agregar_producto'])) {
        // Procesar nuevo producto
        $nombre = $db->real_escape_string($_POST['nombre']);
        $precio = (float)$_POST['precio'];
        $stock = (int)$_POST['stock'];
        $fabricante = $db->real_escape_string($_POST['fabricante']);
        $origen = $db->real_escape_string($_POST['origen']);
        $descripcion = $db->real_escape_string($_POST['descripcion']);
        
        $db->query("
            INSERT INTO productos (nombre, descripcion, precio, cantidad_almacen, fabricante, origen, fotos)
            VALUES ('$nombre', '$descripcion', $precio, $stock, '$fabricante', '$origen', 'default.jpg')
        ");
        
        $_SESSION['mensaje'] = "Producto agregado correctamente";
    } 
    elseif (isset($_POST['actualizar_producto'])) {
        // Procesar actualización
        $id = (int)$_POST['idproducto'];
        $precio = (float)$_POST['precio'];
        $stock = (int)$_POST['stock'];
        
        $db->query("
            UPDATE productos 
            SET precio = $precio, cantidad_almacen = $stock
            WHERE idproducto = $id
        ");
        
        $_SESSION['mensaje'] = "Producto actualizado correctamente";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Panel de Administración</h2>
        
        <?php if(isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success"><?= $_SESSION['mensaje'] ?></div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-6">
                <h3>Historial de Compras</h3>
                <?php
                $historial = $db->query("
                  SELECT h.fecha, u.nombre as usuario, p.nombre as producto, h.cantidad, p.precio
                 FROM historial h
                 JOIN usuarios u ON h.usuario = u.id
                 JOIN productos p ON h.producto = p.idproducto
                 ORDER BY h.fecha DESC
                 LIMIT 50
                 ");
                ?>
                <table class="table">
                    <tr><th>Fecha</th><th>Usuario</th><th>Producto</th><th>Cantidad</th><th>Total</th></tr>
                    <?php while($item = $historial->fetch_assoc()): ?>
                        <tr>
                            <td><?= $item['fecha'] ?></td>
                            <td><?= $item['usuario'] ?></td>
                            <td><?= $item['producto'] ?></td>
                            <td><?= $item['cantidad'] ?></td>
                            <td>$<?= number_format($item['precio'] * $item['cantidad'], 2) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
            
            <div class="col-md-6">
                <h3>Gestión de Productos</h3>
                
                <h4>Agregar Nuevo Producto</h4>
                <form method="post" class="mb-4">
                    <div class="mb-3">
                        <input type="text" name="nombre" placeholder="Nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="descripcion" placeholder="Descripción" class="form-control"></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <input type="number" step="0.01" name="precio" placeholder="Precio" class="form-control" required>
                        </div>
                        <div class="col">
                            <input type="number" name="stock" placeholder="Stock" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <input type="text" name="fabricante" placeholder="Fabricante" class="form-control">
                        </div>
                        <div class="col">
                            <input type="text" name="origen" placeholder="Origen" class="form-control">
                        </div>
                    </div>
                    <button type="submit" name="agregar_producto" class="btn btn-primary">Agregar Producto</button>
                </form>
                
                <h4>Editar Productos Existentes</h4>
                <?php
                $productos = $db->query("SELECT * FROM productos ORDER BY nombre");
                while($producto = $productos->fetch_assoc()):
                ?>
                    <form method="post" class="mb-3">
                        <input type="hidden" name="idproducto" value="<?= $producto['idproducto'] ?>">
                        <div class="d-flex align-items-center mb-2">
                            <strong class="me-2"><?= $producto['nombre'] ?></strong>
                        </div>
                        <div class="row">
                            <div class="col">
                                <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" class="form-control form-control-sm">
                            </div>
                            <div class="col">
                                <input type="number" name="stock" value="<?= $producto['cantidad_almacen'] ?>" class="form-control form-control-sm">
                            </div>
                            <div class="col">
                                <button type="submit" name="actualizar_producto" class="btn btn-sm btn-warning">Actualizar</button>
                            </div>
                        </div>
                    </form>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</body>
</html>