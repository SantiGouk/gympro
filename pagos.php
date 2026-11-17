<?php
session_start();
require_once 'conexion.php';
// Definimos la fecha actual para comparar vencimientos
$hoy = date('Y-m-d');

// Consultamos los pagos uniendo las tablas pagos, socios y planes
try {
    $sql = "SELECT p.*, s.nombre, s.apellido, s.dni, pl.nombre_plan 
            FROM pagos p
            LEFT JOIN socios s ON p.id_socio = s.id_socio
            LEFT JOIN planes pl ON p.id_plan = pl.id_plan
            ORDER BY p.id_pago DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $pagos = [];
    $error = "Error al consultar pagos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymPro - Gestión de Pagos</title>
    <!-- Bootstrap 5 CSS -->
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

    <!-- Incluimos la barra de navegación modular -->
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

        .nav-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-accion {
            background-color: var(--primary-color);
            color: #000;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-accion:hover {
            background-color: #00e077;
            transform: translateY(-2px);
        }

        .btn-secundario {
            background-color: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secundario:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        /* TABLA */
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

        .monto {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* BADGES DE ESTADO DE CUOTA */
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-aldia {
            background-color: rgba(0, 255, 136, 0.15);
            color: #00ff88;
            border: 1px solid #00ff88;
        }

        .badge-vencido {
            background-color: rgba(255, 77, 77, 0.15);
            color: #ff4d4d;
            border: 1px solid #ff4d4d;
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
        <h2>Historial de Pagos y Cuotas</h2>
        <div class="nav-buttons">
            <a href="panel.php" class="btn-accion">← Volver</a>
            <a href="registrar_pago.php" class="btn-accion">+ Nuevo Cobro</a>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th># Pago</th>
                    <th>Socio</th>
                    <th>DNI</th>
                    <th>Plan</th>
                    <th>Monto</th>
                    <th>Fecha Pago</th>
                    <th>Vencimiento</th>
                    <th>Estado Cuota</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($pagos) > 0): ?>
                    <?php foreach ($pagos as $pago): ?>
                        <tr>
                            <td>#<?php echo htmlspecialchars($pago['id_pago']); ?></td>
                            <td><strong><?php echo htmlspecialchars($pago['apellido'] . ', ' . $pago['nombre']); ?></strong></td>
                            <td><?php echo htmlspecialchars($pago['dni']); ?></td>
                            <td><?php echo htmlspecialchars($pago['nombre_plan']); ?></td>
                            <td class="monto">$<?php echo number_format($pago['monto'], 2, ',', '.'); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($pago['fecha_pago'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($pago['fecha_vencimiento'])); ?></td>
                            <td>
                                <?php if ($pago['fecha_vencimiento'] >= $hoy): ?>
                                    <span class="badge badge-aldia">Al día</span>
                                <?php else: ?>
                                    <span class="badge badge-vencido">Vencido</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="sin-datos">No hay cobros registrados todavía.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>