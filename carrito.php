<?php
session_start();
$db = new mysqli('localhost', 'root', '', 'figuras');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Carrito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Tienda</a>
            <a class="btn btn-outline-dark" href="carrito.php">
                Carrito <span class="badge bg-dark">
                    <?php 
                    $total_items = 0;
                    if(isset($_SESSION['usuario_id'])) {
                        $res = $db->query("SELECT SUM(cantidad) as total FROM carrito WHERE idusuario = ".$_SESSION['usuario_id']);
                        $total_items = $res->fetch_assoc()['total'] ?? 0;
                    }
                    echo $total_items;
                    ?>
                </span>
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Tu Carrito</h2>
        
        <?php
        if(isset($_SESSION['usuario_id'])) {
            $result = $db->query("
                SELECT p.*, c.cantidad, p.cantidad_almacen as stock
                FROM carrito c 
                JOIN productos p ON c.idproducto = p.idproducto 
                WHERE c.idusuario = ".$_SESSION['usuario_id']
            );
            
            if($result->num_rows > 0) {
                echo '<table class="table">
                        <tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Stock</th><th>Total</th><th>Acciones</th></tr>';
                
                $gran_total = 0;
                while($item = $result->fetch_assoc()) {
                    $subtotal = $item['precio'] * $item['cantidad'];
                    $gran_total += $subtotal;
                    $max_cantidad = min($item['stock'] + $item['cantidad'], 20); // Límite de 20 por producto
                    
                    echo '<tr>
                            <td>'.$item['nombre'].'</td>
                            <td>$'.number_format($item['precio'], 2).'</td>
                            <td>
                                <form action="acciones_carrito.php" method="post" class="d-flex">
                                    <input type="hidden" name="idproducto" value="'.$item['idproducto'].'">
                                    <input type="number" name="cantidad" value="'.$item['cantidad'].'" min="1" max="'.$max_cantidad.'" class="form-control form-control-sm" style="width: 70px;">
                                    <button type="submit" name="accion" value="actualizar" class="btn btn-primary btn-sm ms-2">✓</button>
                                </form>
                            </td>
                            <td>'.$item['stock'].'</td>
                            <td>$'.number_format($subtotal, 2).'</td>
                            <td>
                                <form action="acciones_carrito.php" method="post">
                                    <input type="hidden" name="idproducto" value="'.$item['idproducto'].'">
                                    <button type="submit" name="accion" value="eliminar" class="btn btn-danger btn-sm">X</button>
                                </form>
                            </td>
                          </tr>';
                }
                
                echo '<tr><td colspan="4">Total</td><td>$'.number_format($gran_total, 2).'</td><td></td></tr>
                      </table>
                      <a href="index.php" class="btn btn-secondary">Seguir comprando</a>
                      <a href="checkout.php" class="btn btn-success">Pagar</a>';
            } else {
                echo '<p>Tu carrito está vacío</p>';
            }
        } else {
            echo '<p>Debes iniciar sesión</p>';
        }
        ?>
    </div>
</body>
</html>