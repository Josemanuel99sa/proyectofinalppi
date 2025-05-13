<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Detalles del producto" />
    <meta name="author" content="" />
    <title>Detalles del Producto</title>
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
</head>
<body>
    <?php
    session_start();
    // Configuración de imágenes
    define('RUTA_FOTOS', '../fotos/');
    define('IMAGEN_DEFAULT', 'sin_imagen.jpg');
    
    // Conexión a la base de datos
    $db = new mysqli('localhost', 'root', '', 'figuras');
    if ($db->connect_error) {
        die("Error de conexión: " . $db->connect_error);
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
    ?>

    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="../index.php">Tienda de Figuras</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#!">About</a></li>
                </ul>
                <div class="d-flex">
                    <?php if(isset($_SESSION['usuario'])): ?>
                        <div class="dropdown me-2">
                            <button class="btn btn-outline-dark dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <i class="bi bi-person-fill me-1"></i>
                                <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                            </button>
                            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="../historial.php"><i class="bi bi-clock-history me-2"></i>Mi Historial</a></li>
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
                    <a class="btn btn-outline-dark ms-2" href="../carrito.php">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        <span class="badge bg-dark text-white ms-1 rounded-pill"><?= count_items_in_cart($db) ?></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Modal Login -->
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

    <!-- Modal Registro -->
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

    <!-- Product section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="row gx-4 gx-lg-5 align-items-center">
                <?php
                // Obtener ID del producto
                if (isset($_GET['id'])) {
                    $idproducto = intval($_GET['id']);
                    
                    // Consulta para el producto actual
                    $sql = "SELECT idproducto, nombre, descripcion, precio, fotos as nombre_imagen, cantidad_almacen, fabricante, origen FROM productos WHERE idproducto = ?";
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param("i", $idproducto);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        $producto = $result->fetch_assoc();
                        
                        // Procesamiento seguro de imagen
                        $nombre_imagen = !empty($producto['nombre_imagen']) ? basename($producto['nombre_imagen']) : '';
                        $ruta_imagen = RUTA_FOTOS . $nombre_imagen;
                        
                        if(!file_exists($ruta_imagen) || empty($nombre_imagen)) {
                            $ruta_imagen = RUTA_FOTOS . IMAGEN_DEFAULT;
                        }
                        ?>
                        <div class="col-md-6">
                            <img class="card-img-top mb-5 mb-md-0" 
                                 src="<?= $ruta_imagen ?>" 
                                 alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                 style="max-width: 100%; height: auto;">
                        </div>
                        <div class="col-md-6">
                            <h1 class="display-5 fw-bolder"><?= htmlspecialchars($producto['nombre']) ?></h1>
                            <div class="fs-5 mb-5">
                                <span>$<?= number_format($producto['precio'], 2) ?></span>
                            </div>
                            <p class="lead"><?= htmlspecialchars($producto['descripcion']) ?></p>
                            <p><strong>Fabricante:</strong> <?= htmlspecialchars($producto['fabricante']) ?></p>
                            <p><strong>Origen:</strong> <?= htmlspecialchars($producto['origen']) ?></p>
                            <p class="<?= ($producto['cantidad_almacen'] > 0) ? 'text-success' : 'text-danger' ?>">
                                <strong><?= ($producto['cantidad_almacen'] > 0) ? 'En stock' : 'Agotado' ?></strong>
                            </p>
                            <div class="d-flex">
                                <form action="../acciones_carrito.php" method="post" class="d-flex">
                                    <input type="hidden" name="idproducto" value="<?= $producto['idproducto'] ?>">
                                    <input class="form-control text-center me-3" name="cantidad" type="number" value="1" min="1" max="<?= $producto['cantidad_almacen'] ?>" style="max-width: 3rem" />
                                    <button class="btn btn-outline-dark flex-shrink-0" type="submit" name="accion" value="añadir" <?= ($producto['cantidad_almacen'] <= 0) ? 'disabled' : '' ?>>
                                        <i class="bi-cart-fill me-1"></i>
                                        Añadir al carrito
                                    </button>
                                </form>
                            </div>
                        </div>
                        <?php
                    } else {
                        echo "<div class='col-12'><p class='text-center'>Producto no encontrado.</p></div>";
                    }
                } else {
                    echo "<div class='col-12'><p class='text-center'>No se especificó un producto.</p></div>";
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Related products section-->
    <section class="py-5 bg-light">
        <div class="container px-4 px-lg-5 mt-5">
            <h2 class="fw-bolder mb-4">Productos relacionados</h2>
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <?php
                if (isset($idproducto)) {
                    // Consulta para 4 productos aleatorios (excluyendo el actual)
                    $sql_related = "SELECT idproducto, nombre, precio, fotos as nombre_imagen FROM productos WHERE idproducto != ? ORDER BY RAND() LIMIT 4";
                    $stmt_related = $db->prepare($sql_related);
                    $stmt_related->bind_param("i", $idproducto);
                    $stmt_related->execute();
                    $related_products = $stmt_related->get_result();

                    while ($row = $related_products->fetch_assoc()) {
                        // Procesamiento seguro de imagen para productos relacionados
                        $nombre_imagen_rel = !empty($row['nombre_imagen']) ? basename($row['nombre_imagen']) : '';
                        $ruta_imagen_rel = RUTA_FOTOS . $nombre_imagen_rel;
                        
                        if(!file_exists($ruta_imagen_rel) || empty($nombre_imagen_rel)) {
                            $ruta_imagen_rel = RUTA_FOTOS . IMAGEN_DEFAULT;
                        }
                        ?>
                        <div class="col mb-5">
                            <div class="card h-100">
                                <!-- Product image-->
                                <img class="card-img-top" 
                                     src="<?= $ruta_imagen_rel ?>" 
                                     alt="<?= htmlspecialchars($row['nombre']) ?>"
                                     style="height: 200px; object-fit: cover;">
                                <!-- Product details-->
                                <div class="card-body p-4">
                                    <div class="text-center">
                                        <!-- Product name-->
                                        <h5 class="fw-bolder"><?= htmlspecialchars($row['nombre']) ?></h5>
                                        <!-- Product price-->
                                        $<?= number_format($row['precio'], 2) ?>
                                    </div>
                                </div>
                                <!-- Product actions-->
                                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                    <div class="text-center">
                                        <a class="btn btn-outline-dark mt-auto" href="detalles.php?id=<?= $row['idproducto'] ?>">Ver detalles</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                $db->close();
                ?>
            </div>
        </div>
    </section>

    <!-- Footer-->
    <footer class="py-5 bg-dark">
        <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Tienda de Figuras 2025</p></div>
    </footer>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>
</html>