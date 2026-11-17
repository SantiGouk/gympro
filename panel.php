<?php
session_start();
include 'conexion.php';

// Contadores para el panel principal
try {
    $totalSocios = $pdo->query("SELECT COUNT(*) FROM socios WHERE estado = 1")->fetchColumn();
    $totalPlanes = $pdo->query("SELECT COUNT(*) FROM planes WHERE estado = 1")->fetchColumn();
    $totalPagos  = $pdo->query("SELECT COUNT(*) FROM pagos")->fetchColumn();
    $total_asistencias = $pdo->query("SELECT COUNT(*) FROM asistencias")->fetchColumn();
} catch (PDOException $e) {
    $totalSocios = $totalPlanes = $totalPagos = $total_asistencias = 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYMPRO - Panel Principal</title>
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

    <!-- Aquí ponemos el menú modular arriba de todo -->
    <?php include 'navbar.php'; ?>

</body>
</html>
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
            padding: 0;
        }

        /* BARRA DE NAVEGACIÓN SUPERIOR */
        .navbar {
            background-color: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .brand {
            font-size: 22px;
            font-weight: 600;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
        }

        .navbar .nav-links {
            display: flex;
            gap: 20px;
        }

        .navbar .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .navbar .nav-links a:hover,
        .navbar .nav-links a.active {
            color: var(--primary-color);
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome-header {
            margin-bottom: 30px;
        }

        .welcome-header h1 {
            font-size: 28px;
            margin: 0 0 5px 0;
        }

        .welcome-header p {
            color: var(--text-secondary);
            margin: 0;
        }

        /* TARJETAS RESUMEN (STATS) */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }

        .stat-card h3 {
            margin: 0;
            font-size: 14px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-card .number {
            font-size: 36px;
            font-weight: 600;
            color: var(--primary-color);
            margin: 10px 0 0 0;
        }

        /* ACCESOS RÁPIDOS */
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .action-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 25px;
            text-decoration: none;
            color: var(--text-primary);
            transition: all 0.3s ease;
            display: block;
        }

        .action-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 255, 136, 0.15);
        }

        .action-card h4 {
            margin: 0 0 8px 0;
            font-size: 18px;
            color: var(--primary-color);
        }

        .action-card p {
            margin: 0;
            font-size: 13px;
            color: var(--text-secondary);
        }
        .stats-grid a.stat-card {
        padding: 24px;
        display: block;
        text-decoration: none;
        color: inherit;
        transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
    }

    /* Efecto hover al apoyar el cursor */
    .stats-grid a.stat-card:hover {
        transform: translateY(-4px);
        border-color: #00ff88;
        box-shadow: 0 6px 15px rgba(0, 255, 136, 0.15);
    }
    </style>
</head>
<body>



<div class="container">
    <div class="welcome-header">
        <h1>Panel de Control</h1>
        <p>Bienvenido al sistema de gestión de gimnasio.</p>
    </div>

    <!-- TARJETAS DE ESTADÍSTICAS -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Socios Activos</h3>
            <p class="number"><?php echo $totalSocios; ?></p>
        </div>
        <div class="stat-card">
            <h3>Planes Disponibles</h3>
            <p class="number"><?php echo $totalPlanes; ?></p>
        </div>
        <div class="stat-card">
            <h3>Cobros Registrados</h3>
            <p class="number"><?php echo $totalPagos; ?></p>
        </div>
        <!-- Tarjeta de Asistencias unificada con el diseño exacto -->
        <div class="stat-card">
            <h3>Asistencias Hoy</h3>
            <p class="number"><?php echo isset($total_asistencias) ? $total_asistencias : 0; ?></p>
            <a href="registrar_asistencia.php" style="color: var(--primary-color, #00ff88); font-size: 13px; text-decoration: none; display: inline-block; margin-top: 8px;">+ Registrar Asistencia</a>
        </div>
    </div>
<!-- MÓDULOS / ACCESOS DIRECTOS -->
<div class="stats-grid">
    <a href="socios.php" class="stat-card" style="text-decoration: none; color: inherit; display: block; transition: all 0.3s ease;">
        <h3>Gestión de Socios</h3>
        <p class="number" style="font-size: 16px; color: var(--primary-color, #00ff88);">Administrar socios</p>
    </a>
    <a href="planes.php" class="stat-card" style="text-decoration: none; color: inherit; display: block; transition: all 0.3s ease;">
        <h3>Gestión de Planes</h3>
        <p class="number" style="font-size: 16px; color: var(--primary-color, #00ff88);">Administrar planes</p>
    </a>
    <a href="pagos.php" class="stat-card" style="text-decoration: none; color: inherit; display: block; transition: all 0.3s ease;">
        <h3>Gestión de Pagos</h3>
        <p class="number" style="font-size: 16px; color: var(--primary-color, #00ff88);">Administrar pagos</p>
    </a>
    <a href="asistencias.php" class="stat-card" style="text-decoration: none; color: inherit; display: block; transition: all 0.3s ease;">
        <h3>Registrar Asistencia</h3>
        <p class="number" style="font-size: 16px; color: var(--primary-color, #00ff88);">Control de ingreso</p>
    </a>
</div>
    
</div>

</body>
</html>