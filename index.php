<?php
session_start();
$db = new mysqli('localhost', 'root', '', 'figuras');

// Configuración de imágenes
define('RUTA_FOTOS', 'fotos/');
define('IMAGEN_DEFAULT', 'sin_imagen.jpg');

// Función para contar items en el carrito
function count_items_in_cart($db) {
    if (!isset($_SESSION['usuario_id'])) return 0;
    
    $usuario_id = $_SESSION['usuario_id'];
    $sql = "SELECT SUM(cantidad) as total FROM carrito WHERE idusuario = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['total'] ?? 0;
}

// Procesar registro
if (isset($_POST['registrar'])) {
    $nombre = $db->real_escape_string($_POST['nombre']);
    $correo = $db->real_escape_string($_POST['correo']);
    $password = $db->real_escape_string($_POST['password']);
    $nacimiento = $db->real_escape_string($_POST['nacimiento']);
    $tarjeta = $db->real_escape_string($_POST['tarjeta']);
    $direccion = $db->real_escape_string($_POST['direccion']);
    
    $sql = "INSERT INTO usuarios (nombre, correo, password, nacimiento, tarjetabancaria, direccionpostal) 
            VALUES ('$nombre', '$correo', '$password', '$nacimiento', '$tarjeta', '$direccion')";
    
    if ($db->query($sql)) {
        $_SESSION['mensaje'] = "¡Registro exitoso!";
    } else {
        $_SESSION['error'] = "Error al registrar: " . $db->error;
    }
}

// Procesar login
if (isset($_POST['ingresar'])) {
    $nombre = $db->real_escape_string($_POST['nombre']);
    $password = $_POST['password']; 
    
    $resultado = $db->query("SELECT * FROM usuarios WHERE nombre = '$nombre'");
    
    if ($resultado && $resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        if ($password === $fila['password']) {
            $_SESSION['usuario'] = $fila['nombre'];
            $_SESSION['usuario_id'] = $fila['id'];
            $_SESSION['es_admin'] = $fila['es_admin']; // esto guarda los estado de admin
            
            // Redirigir según si es admin o no
            if ($fila['es_admin'] == 1) {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Contraseña incorrecta";
        }
    } else {
        $_SESSION['error'] = "Usuario no encontrado";
    }
}

// Cerrar sesión
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Para obtener productos de la base de datos
$productos = [];
$query_productos = "SELECT idproducto, nombre, descripcion, fotos as nombre_imagen, precio, cantidad_almacen, fabricante, origen FROM productos";
$resultado_productos = $db->query($query_productos);
if ($resultado_productos) {
    while($fila = $resultado_productos->fetch_assoc()) {
        $productos[] = $fila;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Shop Homepage - Start Bootstrap Template</title>
        <!-- el Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- los  Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- el Core theme CSS-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <!-- la Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="index.php">Koharu Store</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="#!">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.php">Sobre nosotros</a></li>
                    </ul>
                    <div class="d-flex">
                        <?php if(isset($_SESSION['usuario'])): ?>
                            <div class="dropdown">
                                <button class="btn btn-outline-dark dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-fill me-1"></i>
                                    <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                                </button>
                                <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="historial.php"><i class="bi bi-clock-history me-2"></i>Mi Historial</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="?logout=1"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</a></li>
            </ul>
                            </div>
                        <?php else: ?>
                            <button class="btn btn-outline-dark me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                Login
                            </button>
                            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#registerModal">
                                <i class="bi bi-person-plus me-1"></i>
                                Registro
                            </button>
                        <?php endif; ?>
                        <a class="btn btn-outline-dark ms-2" href="carrito.php">
                            <i class="bi-cart-fill me-1"></i>
                            Cart
                            <span class="badge bg-dark text-white ms-1 rounded-pill"><?= count_items_in_cart($db) ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- el Modal Login -->
        <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Iniciar Sesión</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <?php if(isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                            <?php endif; ?>
                            <div class="mb-3">
                                <label class="form-label">Nombre de usuario</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" name="ingresar" class="btn btn-primary">Ingresar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- el Modal Registro -->
        <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Registrarse</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <?php if(isset($_SESSION['mensaje'])): ?>
                                <div class="alert alert-success"><?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></div>
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label class="form-label">Nombre de usuario</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" name="correo" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Fecha de nacimiento</label>
                                <input type="date" class="form-control" name="nacimiento" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tarjeta bancaria</label>
                                <input type="text" class="form-control" name="tarjeta" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Dirección postal</label>
                                <input type="text" class="form-control" name="direccion" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" name="registrar" class="btn btn-primary">Registrarse</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Header-->
        <header class="bg-dark py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">Koharu Store</h1>
                    <p class="lead fw-normal text-white-50 mb-0">Tienda de figuras anime, videjuegos y demas</p>
                </div>
            </div>
        </header>
        <!-- Section-->
        <section class="py-5">
            <div class="container px-4 px-lg-5 mt-5">
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                    <?php foreach($productos as $producto): 
                        // Procesamiento seguro de imagen
                        $nombre_imagen = !empty($producto['nombre_imagen']) ? basename($producto['nombre_imagen']) : '';
                        $ruta_imagen = RUTA_FOTOS . $nombre_imagen;
                        
                        // Verificar si la imagen existe
                        if(!file_exists($ruta_imagen) || empty($nombre_imagen)) {
                            $ruta_imagen = RUTA_FOTOS . IMAGEN_DEFAULT;
                        }
                    ?>
                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- imagen del proeducto-->
                            <img class="card-img-top" 
                                 src="<?= $ruta_imagen ?>" 
                                 alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                 style="height: 200px; object-fit: cover;">
                            
                            <!-- Detalles del producto-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- nombre del priducto-->
                                    <h5 class="fw-bolder"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                    <!-- Fabricante -->
                                    <p class="text-muted"><?= htmlspecialchars($producto['fabricante']) ?></p>
                                    <!-- precio-->
                                    <span class="fw-bold">$<?= number_format($producto['precio'], 2) ?></span>
                                    <!-- Stock -->
                                    <p class="small <?= ($producto['cantidad_almacen'] > 0) ? 'text-success' : 'text-danger' ?>">
                                        <?= ($producto['cantidad_almacen'] > 0) ? 'En stock' : 'Agotado' ?>
                                    </p>
                                </div>
                            </div>
                            <!-- acciones-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center">
                                    <a class="btn btn-outline-dark mt-auto me-1" href="detalles/detalles.php?id=<?= $producto['idproducto'] ?>">Ver detalles</a>
                                    <?php if($producto['cantidad_almacen'] > 0): ?>
                                        <form action="acciones_carrito.php" method="post" style="display: inline;">
                                            <input type="hidden" name="idproducto" value="<?= $producto['idproducto'] ?>">
                                            <input type="hidden" name="cantidad" value="1">
                                            <button type="submit" name="accion" value="añadir" class="btn btn-dark mt-auto">
                                                Añadir al carrito
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-dark mt-auto" disabled>Agotado</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p></div>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>