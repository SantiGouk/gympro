
<?php
session_start();
include 'conexion.php';

// Consulta para obtener todos los socios ordenados por el más reciente
try {
    $sql = "SELECT * FROM socios ORDER BY id_socio DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $socios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $socios = [];
    $error = "Error al consultar socios: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-array, initial-scale=1.0">
    <title>GYMPRO - Socios</title>
    <!-- Bootstrap 5 CSS para que el menú y los estilos funcionen bien -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tu archivo CSS unificado -->
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-color: #12161f;
            color: #ffffff;
        }
    </style>
</head>
<body>

    <!-- Aquí llamamos a nuestro menú modular -->
    <?php include 'navbar.php'; ?>

    
    <style>
        :root {
            --bg-body: #111317;
            --bg-card: #1a1d24;
            --primary-color: #00ff88;
            --text-primary: #ffffff;
            --text-secondary: #a0a6b1;
            --border-color: #313741;
            --font-family: 'Poppins', sans-serif;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-body);
            color: var(--text-primary);
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: var(--bg-card);
            padding: 30px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        h2 {
            margin: 0;
            color: var(--primary-color);
            font-size: 26px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-nuevo {
            background-color: var(--primary-color);
            color: #000;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-nuevo:hover {
            background-color: #00e077;
            transform: translateY(-2px);
        }

        /* TABLA MODERNA */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background-color: rgba(255, 255, 255, 0.03);
            color: var(--text-secondary);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }

        /* BADGES DE ESTADO */
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-activo {
            background-color: rgba(0, 255, 136, 0.15);
            color: #00ff88;
            border: 1px solid #00ff88;
        }

        .badge-inactivo {
            background-color: rgba(255, 77, 77, 0.15);
            color: #ff4d4d;
            border: 1px solid #ff4d4d;
        }

        /* BOTONES DE ACCIÓN */
        .btn-action {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-right: 5px;
        }

        .btn-editar {
            background-color: rgba(255, 193, 7, 0.15);
            color: #ffc107;
            border: 1px solid #ffc107;
        }

        .btn-editar:hover {
            background-color: #ffc107;
            color: #000;
        }

        .btn-estado {
            background-color: rgba(108, 117, 125, 0.15);
            color: #a0a6b1;
            border: 1px solid #a0a6b1;
        }

        .btn-estado:hover {
            background-color: #a0a6b1;
            color: #000;
        }

        .sin-datos {
            text-align: center;
            padding: 30px;
            color: var(--text-secondary);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Listado de Socios</h2>
        <div>
        <a href="panel.php" class="btn-nuevo">← Volver</a>
        <a href="agregar_socio.php" class="btn-nuevo">+ Nuevo Socio</a>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre y Apellido</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>Localidad</th>
                    <th>Fecha Alta</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($socios) > 0): ?>
                    <?php foreach ($socios as $socio): ?>
                        <tr>
                            <td>#<?php echo htmlspecialchars($socio['id_socio']); ?></td>
                            <td><strong><?php echo htmlspecialchars($socio['nombre'] . ' ' . $socio['apellido']); ?></strong></td>
                            <td><?php echo htmlspecialchars($socio['dni']); ?></td>
                            <td><?php echo htmlspecialchars($socio['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($socio['localidad']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($socio['fecha_alta'])); ?></td>
                            <td>
                                <?php if ($socio['estado'] == 1): ?>
                                    <span class="badge badge-activo">Activo</span>
                                <?php else: ?>
                                    <span class="badge badge-inactivo">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- Botón para Editar -->
                                <a href="editar_socio.php?id=<?php echo $socio['id_socio']; ?>" class="btn-action btn-editar">Editar</a>
                                
                                <!-- Botón para Cambiar Estado (Baja/Activar) -->
                                <a href="cambiar_estado_socio.php?id=<?php echo $socio['id_socio']; ?>" class="btn-action btn-estado">
                                    <?php echo ($socio['estado'] == 1) ? 'Dar de Baja' : 'Activar'; ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="sin-datos">No hay socios registrados todavía.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>