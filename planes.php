<?php
session_start();
require_once 'conexion.php';

// Consultamos los planes existentes en la base de datos
try {
    $sql = "SELECT * FROM planes ORDER BY id_plan DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $planes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $planes = [];
    $error = "Error al consultar planes: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymPro - Gestión de Planes</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Tu archivo CSS unificado -->
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-color: #12161f;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
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
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        h2 {
            margin: 0;
            color: var(--primary-color);
            font-size: 26px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-volver {
            background-color: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-volver:hover {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* LAYOUT EN DOS COLUMNAS */
        .content-grid {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 25px;
        }

        .card {
            background: var(--bg-card);
            padding: 25px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            height: fit-content;
        }

        h3 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 18px;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
        }

        /* FORMULARIO */
        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 12px;
            font-weight: 400;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px 12px;
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-primary);
            font-family: var(--font-family);
            font-size: 14px;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: rgba(255, 255, 255, 0.1);
        }

        button {
            margin-top: 10px;
            background-color: var(--primary-color);
            color: #000;
            border: none;
            padding: 12px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-family: var(--font-family);
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #00e077;
            transform: translateY(-2px);
        }

        /* TABLA */
        table {
            width: 100%;
            border-collapse: collapse;
            color: #ffffff; /* <- Agregale esto */
        }

        th, td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background-color: rgba(255, 255, 255, 0.03);
            color: var(--text-secondary);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .precio {
            color: var(--primary-color);
            font-weight: 600;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 11px;
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

        @media (max-width: 900px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Planes y Membresías</h2>
        <a href="socios.php" class="btn" style="background-color: #00ff66; color: #000; font-weight: bold; text-decoration: none;">← Ver Socios</a>
    </div>

    <div class="content-grid">
        <!-- Formulario para agregar un plan -->
        <div class="card">
            <h3>Nuevo Plan</h3>
            <form action="guardar_plan.php" method="POST">
                <div class="form-group">
                    <label for="nombre_plan">Nombre del Plan:</label>
                    <input type="text" id="nombre_plan" name="nombre_plan" placeholder="Ej: Pase Libre" required>
                </div>

                <div class="form-group">
                    <label for="precio">Precio ($):</label>
                    <input type="number" step="0.01" id="precio" name="precio" placeholder="15000.00" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Detalles de lo que incluye..."></textarea>
                </div>

                <button type="submit">Guardar Plan</button>
            </form>
        </div>

        <!-- Tabla con lista de planes -->
        <div class="card">
            <h3>Planes Disponibles</h3>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Plan</th>
                            <th>Precio</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($planes) > 0): ?>
                            <?php foreach ($planes as $plan): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($plan['id_plan']); ?></td>
                                    <td><strong><?php echo htmlspecialchars($plan['nombre_plan']); ?></strong></td>
                                    <td class="precio">$<?php echo number_format($plan['precio'], 2, ',', '.'); ?></td>
                                    <td><?php echo htmlspecialchars($plan['descripcion']); ?></td>
                                    <td>
                                        <?php if ($plan['estado'] == 1): ?>
                                            <span class="badge badge-activo">Activo</span>
                                        <?php else: ?>
                                            <span class="badge badge-inactivo">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-secondary); padding: 20px;">
                                    No hay planes registrados aún.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>