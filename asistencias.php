<?php
session_start();
// Incluimos la conexión a la base de datos
include("conexion.php"); 

// 1. Consultamos los socios reales ordenados por apellido (para el desplegable)
try {
    $stmt = $pdo->query("SELECT id_socio, nombre, apellido FROM socios ORDER BY apellido ASC");
    $socios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $socios = [];
    $error = "Error al consultar socios: " . $e->getMessage();
}

// 2. Consultamos los últimos ingresos registrados (para la tabla de la derecha)
try {
    $sql_ingresos = "SELECT a.fecha_hora, s.apellido, s.nombre 
                     FROM asistencias a 
                     INNER JOIN socios s ON a.id_socio = s.id_socio 
                     ORDER BY a.fecha_hora DESC LIMIT 10";
    $stmt_ingresos = $pdo->query($sql_ingresos);
    $ingresos = $stmt_ingresos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $ingresos = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Asistencias - GYMPRO</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            font-family: Arial, sans-serif;
        }
        .card-custom {
            background-color: #1e1e1e;
            border: 1px solid #2a2a2a;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }
        h2, h4 {
            color: #00ff88 !important;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #cccccc;
        }
        .form-control-custom {
            width: 100%;
            padding: 12px;
            background: #121212;
            color: #fff;
            border: 1px solid #333;
            border-radius: 6px;
            font-size: 16px;
            margin-bottom: 20px;
            outline: none;
        }
        .form-control-custom:focus {
            border-color: #00ff88;
            box-shadow: 0 0 0 0.25rem rgba(0, 255, 136, 0.25);
        }
        .btn-volver {
            background-color: #2a2a2a;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.2s;
        }
        .btn-volver:hover {
            background-color: #3a3a3a;
            color: #fff;
        }
        .btn-guardar {
            background-color: #00ff88;
            color: #121212;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-guardar:hover {
            background-color: #00cc6a;
        }
    </style>
</head>
<body class="bg-dark text-light">

    <!-- Incluimos la barra de navegación -->
    <?php include("navbar.php"); ?>

    <div class="container-fluid px-4 mt-4">
        
        <!-- CARTEL DE ALERTA DE ÉXITO -->
        <?php if (isset($_GET['exito'])): ?>
            <div class="alert alert-success bg-dark border-success mb-4 text-center" role="alert" style="border-color: #00ff88 !important; color: #00ff88 !important;">
                <strong>¡Éxito!</strong> <?php echo htmlspecialchars($_GET['exito']); ?>
            </div>
        <?php endif; ?>

        <!-- CARTEL DE ALERTA DE ERROR -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger bg-dark border-danger mb-4 text-center" role="alert" style="border-color: #ff4d4d !important; color: #ff4d4d !important;">
                <strong>¡Atención!</strong> <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Título principal -->
        <h2 class="mb-4 fw-bold">Control de Asistencias</h2>

        <!-- FILA PRINCIPAL: Dos columnas a lo ancho -->
        <div class="row">
            
            <!-- COLUMNA IZQUIERDA: Formulario de Registro -->
            <div class="col-lg-4 mb-4">
                <div class="card card-custom p-4 h-100">
                    <h4 class="mb-4 fs-4">Registrar Ingreso de Socio</h4>
                    
                    <form action="guardar_asistencias.php" method="POST">
                        <div class="mb-3">
                            <label for="id_socio">Seleccionar Socio:</label>
                            <select name="id_socio" id="id_socio" class="form-control-custom" required>
                                <option value="">-- Seleccione un socio --</option>
                                <?php foreach ($socios as $socio): ?>
                                    <option value="<?php echo $socio['id_socio']; ?>">
                                        <?php echo htmlspecialchars($socio['apellido'] . ', ' . $socio['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="index.php" class="btn-volver">Volver</a>
                            <button type="submit" class="btn-guardar">Registrar Ingreso</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- COLUMNA DERECHA: Tabla de Últimos Ingresos -->
            <div class="col-lg-8 mb-4">
                <div class="card card-custom p-4 h-100">
                    <h4 class="mb-4 fs-4">Últimos Ingresos Registrados</h4>
                    
                    <div class="table-responsive">
                        <table class="table table-dark table-striped table-hover align-middle mb-0">
                            <thead>
                                <tr style="color: #00ff88;">
                                    <th>Socio</th>
                                    <th>Fecha y Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($ingresos)): ?>
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">No hay ingresos registrados todavía.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($ingresos as $ingreso): ?>
                                        <tr>
                                            <td class="py-3"><?php echo htmlspecialchars($ingreso['apellido'] . ', ' . $ingreso['nombre']); ?></td>
                                            <td class="py-3"><?php echo date('d/m/Y H:i:s', strtotime($ingreso['fecha_hora'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>