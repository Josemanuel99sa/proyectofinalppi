<?php
session_start();
$db = new mysqli('localhost', 'root', '', 'figuras');

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para añadir productos al carrito";
    header("Location: index.php");
    echo '<script>alert("Inicia seccion ante sde agregar productos al carrito")</script>';
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$accion = $_POST['accion'];
$idproducto = (int)$_POST['idproducto'];
$cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;

// Verificar stock disponible
$producto = $db->query("SELECT cantidad_almacen FROM productos WHERE idproducto = $idproducto")->fetch_assoc();

if($accion == 'añadir') {
    // Verificar stock
    if($producto['cantidad_almacen'] > 0) {
        // Verificar si ya existe
        $existe = $db->query("SELECT * FROM carrito WHERE idusuario = $usuario_id AND idproducto = $idproducto");
        
        if($existe->num_rows > 0) {
            $db->query("UPDATE carrito SET cantidad = cantidad + $cantidad WHERE idusuario = $usuario_id AND idproducto = $idproducto");
        } else {
            $db->query("INSERT INTO carrito (idusuario, idproducto, cantidad) VALUES ($usuario_id, $idproducto, $cantidad)");
        }
    } else {
        $_SESSION['error'] = "No hay suficiente stock";
    }
} 
elseif($accion == 'actualizar') {
    // Verificar que la cantidad no supere el stock disponible + lo que ya tiene en carrito
    $en_carrito = $db->query("SELECT cantidad FROM carrito WHERE idusuario = $usuario_id AND idproducto = $idproducto")->fetch_assoc();
    $stock_disponible = $producto['cantidad_almacen'] + ($en_carrito['cantidad'] ?? 0);
    
    if($cantidad <= $stock_disponible && $cantidad > 0) {
        $db->query("UPDATE carrito SET cantidad = $cantidad WHERE idusuario = $usuario_id AND idproducto = $idproducto");
    } else {
        $_SESSION['error'] = "Cantidad no válida o excede el stock disponible";
    }
}
elseif($accion == 'eliminar') {
    $db->query("DELETE FROM carrito WHERE idusuario = $usuario_id AND idproducto = $idproducto");
}

header("Location: carrito.php");
?>