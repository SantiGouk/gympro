<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymPro - Registrar Socio</title>
    <!-- Importamos la fuente Poppins de Google Fonts para un look moderno -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* CSS REINICIADO Y GENERALES */
        :root {
            --bg-body: #111317; /* Fondo oscuro moderno */
            --bg-card: #1a1d24; /* Fondo de la tarjeta un poco más claro */
            --primary-color: #00ff88; /* Verde neón para acentos y botones */
            --text-primary: #ffffff; /* Texto principal en blanco */
            --text-secondary: #a0a6b1; /* Texto secundario gris */
            --border-color: #313741; /* Color de bordes */
            --shadow-card: 0 10px 30px rgba(0,0,0,0.5); /* Sombra suave para profundidad */
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
            min-height: 100vh; /* Centrado vertical completo */
            padding: 20px;
        }

        /* CONTENEDOR PRINCIPAL (TARJETA) */
        .container {
            max-width: 600px;
            width: 100%;
            background: var(--bg-card);
            padding: 40px;
            border-radius: 12px;
            box-shadow: var(--shadow-card);
            border: 1px solid var(--border-color);
        }

        /* TÍTULOS */
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

        /* GRID PARA FORMULARIOS (MODERNO) */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Dos columnas por defecto */
            gap: 20px; /* Espacio entre campos */
        }

        /* Hacemos que los campos de dirección y mail ocupen todo el ancho si queremos */
        .full-width {
            grid-column: span 2;
        }

        /* GRUPO DE FORMULARIO INDIVIDUAL */
        .form-group {
            position: relative;
        }

        /* ETIQUETAS */
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 400;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* CAMPOS DE ENTRADA (INPUTS) */
        input[type="text"], 
        input[type="email"], 
        input[type="tel"] {
            width: 100%;
            padding: 12px 15px;
            background-color: rgba(255, 255, 255, 0.05); /* Fondo input semitransparente */
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-primary);
            font-family: var(--font-family);
            font-size: 16px;
            box-sizing: border-box;
            transition: all 0.3s ease; /* Transición suave para foco */
        }

        /* EFECTO FOCO EN INPUTS */
        input[type="text"]:focus, 
        input[type="email"]:focus, 
        input[type="tel"]:focus {
            outline: none;
            border-color: var(--primary-color); /* Borde neón al hacer foco */
            background-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 10px rgba(0, 255, 136, 0.2);
        }

        /* BOTÓN MODERNO */
        button {
            margin-top: 30px;
            background-color: var(--primary-color);
            color: #000; /* Texto oscuro sobre el verde neón */
            border: none;
            padding: 15px 20px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-family: var(--font-family);
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease; /* Transición para hover */
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.3);
        }

        /* EFECTO HOVER EN BOTÓN */
        button:hover {
            background-color: #00e077; /* Verde un poco más oscuro */
            box-shadow: 0 8px 20px rgba(0, 255, 136, 0.5);
            transform: translateY(-2px); /* Pequeño levante al pasar el mouse */
        }

        /* RESPONSIVE (CELULARES) */
        @media (max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr; /* Una sola columna en pantallas chicas */
            }
            .full-width {
                grid-column: span 1;
            }
            .container {
                padding: 30px 20px;
            }
            h2 {
                font-size: 24px;
            }
        }
        .buttons-group {
    display: flex;
    gap: 15px;
    margin-top: 20px;
    align-items: center;
}

/* Botón Volver (secundario) */
.btn-cancelar {
    background-color: transparent;
    color: var(--text-secondary);
    border: 1px solid var(--border-color);
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: center;
    transition: all 0.3s ease;
    box-sizing: border-box;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    justify-content: center;

    position: relative;
    top: -2px;
}

.btn-cancelar:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
}

/* Botón Guardar Socio (principal) */
.btn-guardar {
    background-color: var(--primary-color);
    color: #000000;
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    box-sizing: border-box;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-guardar:hover {
    background-color: #00e077;
}
    </style>
</head>
<body>

<div class="container">
    
    <h2>Registrar Nuevo Socio</h2>
    
    <!-- CARTEL DE ALERTA ROJO PARA ERRORES (Con ancho completo 100%) -->
    <?php if (isset($_GET['error'])): ?>
        <div style="width: 100%; margin-bottom: 20px;">
            <div class="alert alert-danger bg-dark border-danger text-center" role="alert" style="border-color: #ff4d4d !important; color: #ff4d4d !important; margin: 0;">
                <strong>¡Atención!</strong> <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        </div>
    <?php endif; ?>
    
    <form action="guardar_socio.php" method="POST">
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">NOMBRE: <span style="color: #ff4d4d;">*</span></label>
                <input type="text" name="nombre" class="form-control" placeholder="Ej: Juan">
            </div>

            <div class="form-group">
                <label class="form-label">APELLIDO: <span style="color: #ff4d4d;">*</span></label>
                <input type="text" name="apellido" class="form-control" placeholder="Ej: Pérez">
            </div>

            <div class="form-group">
                <label class="form-label">DNI: <span style="color: #ff4d4d;">*</span></label>
                <input type="text" name="dni" class="form-control" placeholder="Sin puntos">
            </div>

            <div class="form-group">
                <label class="form-label">TELÉFONO: <span style="color: #ff4d4d;">*</span></label>
                <input type="text" name="telefono" class="form-control" placeholder="Ej: 351xxxxxxx">
            </div>
        </div>
            <div class="form-group full-width">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="juan.perez@email.com">
            </div>

            <div class="form-group full-width">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" placeholder="Calle Falsa 123">
            </div>

            <div class="form-group full-width">
                <label for="localidad">Localidad:</label>
                <input type="text" id="localidad" name="localidad" placeholder="Bell Ville">
            </div>
        </div>

        <div class="buttons-group">
    <a href="index.php" class="btn-cancelar">Volver</a>
    <button type="submit" class="btn-guardar">Guardar Socio</button>
</div>
    </form>
</div>

</body>
</html>