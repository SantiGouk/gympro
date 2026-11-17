<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_socio  = $_POST['id_socio'];
    $nombre    = trim($_POST['nombre']);
    $apellido  = trim($_POST['apellido']);
    $dni       = trim($_POST['dni']);
    $telefono  = trim($_POST['telefono']);
    $email     = trim($_POST['email']);
    $direccion = trim($_POST['direccion']);
    $localidad = trim($_POST['localidad']);

    try {
        $sql = "UPDATE socios 
                SET nombre = ?, apellido = ?, dni = ?, telefono = ?, email = ?, direccion = ?, localidad = ? 
                WHERE id_socio = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $apellido, $dni, $telefono, $email, $direccion, $localidad, $id_socio]);

        // Al guardar correctamente, volvemos a la lista de socios
        header('Location: socios.php');
        exit;

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<h3 style='color:red; text-align:center;'>Error: Ya existe otro socio con el DNI " . htmlspecialchars($dni) . ".</h3>";
            echo "<p style='text-align:center;'><a href='editar_socio.php?id=" . $id_socio . "' style='color:#00ff88;'>Volver a intentar</a></p>";
        } else {
            echo "Error al actualizar socio: " . $e->getMessage();
        }
    }

} else {
    header('Location: socios.php');
    exit;
}
?>