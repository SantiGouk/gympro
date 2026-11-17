<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturamos el id_socio que viene del selector en asistencias.php
    $id_socio = trim($_POST['id_socio'] ?? '');

    if (empty($id_socio)) {
        header("Location: asistencias.php?error=Por favor, seleccione un socio.");
        exit();
    }

    try {
        // Insertamos la asistencia vinculando el id_socio y la fecha/hora actual
        $sql = "INSERT INTO asistencias (id_socio, fecha_hora) VALUES (?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_socio]);

        header("Location: asistencias.php?exito=Ingreso registrado correctamente.");
        exit();

    } catch (PDOException $e) {
        header("Location: asistencias.php?error=Error al registrar el ingreso.");
        exit();
    }
} else {
    header("Location: asistencias.php");
    exit();
}
?>