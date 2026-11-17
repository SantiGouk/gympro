<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_plan = trim($_POST['nombre_plan']);
    $precio      = trim($_POST['precio']);
    $descripcion = trim($_POST['descripcion']);

    try {
        $sql = "INSERT INTO planes (nombre_plan, precio, descripcion, estado) VALUES (?, ?, ?, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre_plan, $precio, $descripcion]);

        // Volvemos directamente a la pantalla de planes
        header('Location: planes.php');
        exit;

    } catch (PDOException $e) {
        die("Error al guardar el plan: " . $e->getMessage());
    }
} else {
    header('Location: planes.php');
    exit;
}
?>