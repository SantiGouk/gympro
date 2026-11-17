<?php
require_once 'conexion.php';

// Verificamos que venga un ID valido por la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_socio = $_GET['id'];

    try {
        // 1. Consultamos el estado actual del socio
        $sqlSelect = "SELECT estado FROM socios WHERE id_socio = ?";
        $stmtSelect = $pdo->prepare($sqlSelect);
        $stmtSelect->execute([$id_socio]);
        $socio = $stmtSelect->fetch();

        if ($socio) {
            // 2. Invertimos el estado (si es 1 pasa a 0, si es 0 pasa a 1)
            $nuevo_estado = ($socio['estado'] == 1) ? 0 : 1;

            // 3. Actualizamos en la base de datos
            $sqlUpdate = "UPDATE socios SET estado = ? WHERE id_socio = ?";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute([$nuevo_estado, $id_socio]);
        }

    } catch (PDOException $e) {
        die("Error al cambiar el estado del socio: " . $e->getMessage());
    }
}

// Redirigimos de nuevo al listado de socios
header('Location: socios.php');
exit;
?>