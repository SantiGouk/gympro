<?php
require_once 'conexion.php';

try {
    // Obtenemos unicamente los socios ACTIVOS
    $sqlSocios = "SELECT id_socio, nombre, apellido, dni FROM socios WHERE estado = 1 ORDER BY apellido, nombre";
    $stmtSocios = $pdo->prepare($sqlSocios);
    $stmtSocios->execute();
    $socios = $stmtSocios->fetchAll();

    // Obtenemos unicamente los planes ACTIVOS
    $sqlPlanes = "SELECT id_plan, nombre_plan, precio FROM planes WHERE estado = 1 ORDER BY nombre_plan";
    $stmtPlanes = $pdo->prepare($sqlPlanes);
    $stmtPlanes->execute();
    $planes = $stmtPlanes->fetchAll();

} catch (PDOException $e) {
    die("Error al cargar datos: " . $e->getMessage());
}

// Fechas por defecto: Pago = Hoy, Vencimiento = Dentro de 30 dias
$fecha_pago_defecto = date('Y-m-d');
$fecha_venc_defecto = date('Y-m-d', strtotime('+30 days'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymPro - Registrar Pago</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 550px;
            width: 100%;
            background: var(--bg-card);
            padding: 40px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 26px;
            font-weight: 600;
            color: var(--primary-color);
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 400;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        select, input[type="number"], input[type="date"] {
            width: 100%;
            padding: 12px 15px;
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-primary);
            font-family: var(--font-family);
            font-size: 15px;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        select option {
            background-color: #1a1d24;
            color: #ffffff;
        }

        select:focus, input:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 10px rgba(0, 255, 136, 0.2);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .buttons-group {
            margin-top: 30px;
            display: flex;
            gap: 15px;
        }

        button {
            background-color: var(--primary-color);
            color: #000;
            border: none;
            padding: 14px;
            border-radius: 6px;
            cursor: pointer;
            flex: 2;
            font-family: var(--font-family);
            font-size: 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.3);
        }

        button:hover {
            background-color: #00e077;
            transform: translateY(-2px);
        }

        .btn-cancelar {
            background-color: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            padding: 14px;
            border-radius: 6px;
            text-decoration: none;
            text-align: center;
            flex: 1;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-cancelar:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Registrar Cobro</h2>

    <form action="guardar_pago.php" method="POST">
        
        <div class="form-group">
            <label for="id_socio">Socio:</label>
            <select name="id_socio" id="id_socio" required>
                <option value="">-- Seleccionar Socio --</option>
                <?php foreach ($socios as $socio): ?>
                    <option value="<?php echo $socio['id_socio']; ?>">
                        <?php echo htmlspecialchars($socio['apellido'] . ', ' . $socio['nombre'] . ' (DNI: ' . $socio['dni'] . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_plan">Plan a Contratar:</label>
            <select name="id_plan" id="id_plan" required onchange="actualizarMonto(this)">
                <option value="">-- Seleccionar Plan --</option>
                <?php foreach ($planes as $plan): ?>
                    <option value="<?php echo $plan['id_plan']; ?>" data-precio="<?php echo $plan['precio']; ?>">
                        <?php echo htmlspecialchars($plan['nombre_plan'] . ' - $' . number_format($plan['precio'], 2, ',', '.')); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="monto">Monto A Cobrar ($):</label>
            <input type="number" step="0.01" id="monto" name="monto" required placeholder="0.00">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="fecha_pago">Fecha de Pago:</label>
                <input type="date" id="fecha_pago" name="fecha_pago" value="<?php echo $fecha_pago_defecto; ?>" required>
            </div>

            <div class="form-group">
                <label for="fecha_vencimiento">Fecha Vencimiento:</label>
                <input type="date" id="fecha_vencimiento" name="fecha_vencimiento" value="<?php echo $fecha_venc_defecto; ?>" required>
            </div>
        </div>

        <div class="buttons-group">
            <a href="index.php" class="btn-cancelar">Volver</a>
            <button type="submit">Confirmar Pago</button>
        </div>
    </form>
</div>

<script>
// JavaScript para autorellenar el campo de monto al elegir un plan
function actualizarMonto(selectElement) {
    const optionSeleccionada = selectElement.options[selectElement.selectedIndex];
    const precio = optionSeleccionada.getAttribute('data-precio');
    if (precio) {
        document.getElementById('monto').value = precio;
    } else {
        document.getElementById('monto').value = '';
    }
}
</script>

</body>
</html>