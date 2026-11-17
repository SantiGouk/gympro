<?php
session_start();
require_once 'conexion.php';

// Verificamos que los datos hayan venido por el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Capturamos y limpiamos los campos enviados desde el formulario
    $nombre     = trim($_POST['nombre']);
    $apellido   = trim($_POST['apellido']);
    $dni        = trim($_POST['dni']);
    $telefono   = trim($_POST['telefono']);
    $email      = trim($_POST['email'] ?? '');
    $direccion  = trim($_POST['direccion'] ?? '');
    $localidad  = trim($_POST['localidad'] ?? '');
    $fecha_alta = date('Y-m-d'); // Fecha actual en formato YYYY-MM-DD

    // 1. VALIDACIÓN OBLIGATORIA: Si falta alguno de los 4 campos clave, frena y vuelve con error
    if (empty($nombre) || empty($apellido) || empty($dni) || empty($telefono)) {
        header("Location: agregar_socio.php?error=Por favor, complete todos los campos obligatorios marcados con *.");
        exit();
    }

    // 2. VALIDACIÓN DE DNI DUPLICADO: Que no se repita en la base de datos
    $sqlCheck = "SELECT id_socio FROM socios WHERE dni = ?";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([$dni]);
    if ($stmtCheck->rowCount() > 0) {
        header("Location: agregar_socio.php?error=El DNI ingresado ya se encuentra registrado en el sistema.");
        exit();
    }

    // 3. SI PASA TODAS LAS VALIDACIONES, GUARDA EN LA BASE DE DATOS
    try {
        $sql = "INSERT INTO socios (nombre, apellido, dni, telefono, email, direccion, localidad, fecha_alta, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)";
        
        $stmt = $pdo->prepare($sql);
        
        // Ejecutamos pasando los datos de forma segura
        $stmt->execute([$nombre, $apellido, $dni, $telefono, $email, $direccion, $localidad, $fecha_alta]);

        // Si se guardó correctamente, mostramos tu tarjeta de éxito personalizada
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>GymPro - Socio Registrado</title>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
            <style>
                :root {
                    --primary-color: #00ff88;
                    --bg-main: #111317;
                    --card-bg: #1a1d24;
                    --border-color: #313741;
                    --text-primary: #ffffff;
                    --text-muted: #a0a6b1;
                }
                body {
                    font-family: 'Poppins', sans-serif;
                    background-color: var(--bg-main);
                    color: var(--text-primary);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                }
                .card {
                    background-color: var(--card-bg);
                    padding: 40px;
                    border-radius: 12px;
                    border: 1px solid var(--border-color);
                    text-align: center;
                    max-width: 400px;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
                }
                h2 { color: var(--primary-color); margin-bottom: 10px; }
                p { color: var(--text-muted); margin-bottom: 25px; }
                .buttons-container {
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                    margin-top: 20px;
                }
                .btn {
                    display: block;
                    padding: 12px 20px;
                    border-radius: 8px;
                    text-decoration: none;
                    font-weight: 600;
                    font-size: 14px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    transition: all 0.3s ease;
                    box-sizing: border-box;
                }
                .btn-principal {
                    background-color: var(--primary-color);
                    color: #000000;
                }
                .btn-principal:hover {
                    background-color: #00e077;
                    transform: translateY(-2px);
                }
                .btn-secundario {
                    background-color: transparent;
                    color: var(--text-primary);
                    border: 1px solid var(--border-color);
                }
                .btn-secundario:hover {
                    border-color: var(--primary-color);
                    color: var(--primary-color);
                }
            </style>
        </head>
        <body>
            <div class="card">
                <h2>¡Socio Registrado!</h2>
                <p>El socio <strong><?php echo htmlspecialchars($nombre . " " . $apellido); ?></strong> fue guardado correctamente en la base de datos.</p>
                <div class="buttons-container">
                    <a href="socios.php" class="btn btn-principal">Ver Listado de Socios</a>
                    <a href="agregar_socio.php" class="btn btn-secundario">Cargar Otro Socio</a>
                    <a href="index.php" class="btn btn-secundario">Ir al Inicio</a>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit();

    } catch (PDOException $e) {
        header("Location: agregar_socio.php?error=Error al guardar en la base de datos.");
        exit();
    }

} else {
    // Si intentan entrar directo a guardar_socio.php sin enviar el formulario
    header('Location: agregar_socio.php');
    exit();
}
?>
            