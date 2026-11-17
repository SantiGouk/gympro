<?php
session_start();

// Incluimos la conexión existente
include 'conexion.php'; 

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    if (!empty($usuario) && !empty($password)) {
        try {
            $sql = "SELECT id, usuario, password FROM usuarios WHERE usuario = ? OR email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$usuario, $usuario]);
            $user = $stmt->fetch();

            if ($user) {
                if ($password === $user['password']) {
                    $_SESSION['id_usuario'] = $user['id'];
                    $_SESSION['usuario'] = $user['usuario'];
                    
                    // Redirige al index o panel principal
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Contraseña incorrecta.";
                }
            } else {
                $error = "El usuario o email no existe.";
            }
        } catch (PDOException $e) {
            $error = "Error en el sistema: " . $e->getMessage();
        }
    } else {
        $error = "Por favor, completá todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYMPRO - Iniciar Sesión</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #12161f; /* Mismo fondo oscuro de tu sistema */
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            min-height: 100vh;
        }
        .bg-image {
            background-image: url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1470&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            filter: brightness(0.7);
        }
        .card-login {
            background-color: #1b212c; /* Color de tarjeta oscura similar a tus tablas */
            border: 1px solid #2a3447;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }
        .form-control {
            background-color: #12161f;
            border: 1px solid #2a3447;
            color: #ffffff;
        }
        .form-control:focus {
            background-color: #12161f;
            border-color: #0dcaf0;
            color: #ffffff;
            box-shadow: 0 0 0 0.25rem rgba(13, 202, 240, 0.25);
        }
        .form-label {
            color: #adb5bd;
        }
    </style>
</head>
<body>

    <div class="container-fluid login-container">
        <div class="row h-100">
            <!-- Columna Izquierda: Imagen del Gimnasio -->
            <div class="col-lg-7 d-none d-lg-flex bg-image align-items-center justify-content-center text-white p-5">
                <div class="bg-dark bg-opacity-75 p-5 rounded-4 text-center border border-secondary">
                    <h1 class="display-3 fw-bold text-info">GYMPRO</h1>
                    <p class="lead text-light">Sistema de Gestión Integral para Gimnasios</p>
                </div>
            </div>

            <!-- Columna Derecha: Formulario con Estilo Oscuro -->
            <div class="col-lg-5 d-flex align-items-center justify-content-center p-4">
                <div class="w-100 p-4 card-login" style="max-width: 420px;">
                    
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-info mb-1">💪 GYMPRO</h3>
                        <p class="text-muted small">Ingresá tus credenciales para continuar</p>
                    </div>

                    <!-- Mensaje de error -->
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                            <small><?php echo $error; ?></small>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Formulario -->
                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <label for="usuario" class="form-label small fw-bold">Usuario o Email</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" required placeholder="Ej: admin">
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label small fw-bold">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="********">
                        </div>

                        <button type="submit" class="btn btn-info w-100 py-2 fw-bold text-dark">Ingresar al Sistema</button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted" style="font-size: 0.8rem;">Panel de Administración Seguro</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>