<?php
require_once 'conexion.php';

// Verificamos que se reciba un ID por URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: socios.php');
    exit;
}

$id_socio = $_GET['id'];

try {
    // Buscamos los datos actuales del socio
    $sql = "SELECT * FROM socios WHERE id_socio = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_socio]);
    $socio = $stmt->fetch();

    if (!$socio) {
        header('Location: socios.php');
        exit;
    }
} catch (PDOException $e) {
    die("Error al consultar el socio: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymPro - Editar Socio</title>
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
            max-width: 600px;
            width: 100%;
            background: var(--bg-card);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            border: 1px solid var(--border-color);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
            color: var(--primary-color);
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .full-width {
            grid-column: span 2;
        }

        .form-group {
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 400;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        input[type="text"], 
        input[type="email"], 
        input[type="tel"] {
            width: 100%;
            padding: 12px 15px;
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-primary);
            font-family: var(--font-family);
            font-size: 16px;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus, 
        input[type="email"]:focus, 
        input[type="tel"]:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 10px rgba(0, 255, 136, 0.2);
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
            padding: 15px 20px;
            border-radius: 6px;
            cursor: pointer;
            flex: 2;
            font-family: var(--font-family);
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.3);
        }

        button:hover {
            background-color: #00e077;
            box-shadow: 0 8px 20px rgba(0, 255, 136, 0.5);
            transform: translateY(-2px);
        }

        .btn-cancelar {
            background-color: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            padding: 15px 20px;
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
            border-color: #ff4d4d;
            color: #ff4d4d;
        }

        @media (max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .full-width {
                grid-column: span 1;
            }
            .buttons-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Editar Socio #<?php echo $socio['id_socio']; ?></h2>
    
    <form action="actualizar_socio.php" method="POST">
        <!-- Campo oculto para enviar el ID del socio -->
        <input type="hidden" name="id_socio" value="<?php echo $socio['id_socio']; ?>">

        <div class="form-grid">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($socio['nombre']); ?>" required>
            </div>

            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($socio['apellido']); ?>" required>
            </div>

            <div class="form-group">
                <label for="dni">DNI:</label>
                <input type="text" id="dni" name="dni" value="<?php echo htmlspecialchars($socio['dni']); ?>" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($socio['telefono']); ?>">
            </div>

            <div class="form-group full-width">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($socio['email']); ?>">
            </div>

            <div class="form-group full-width">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($socio['direccion']); ?>">
            </div>

            <div class="form-group full-width">
                <label for="localidad">Localidad:</label>
                <input type="text" id="localidad" name="localidad" value="<?php echo htmlspecialchars($socio['localidad']); ?>">
            </div>
        </div>

        <div class="buttons-group">
            <a href="socios.php" class="btn-cancelar">Cancelar</a>
            <button type="submit">Guardar Cambios</button>
        </div>
    </form>
</div>

</body>
</html>