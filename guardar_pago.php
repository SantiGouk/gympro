<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_socio          = $_POST['id_socio'];
    $id_plan           = $_POST['id_plan'];
    $monto             = $_POST['monto'];
    $fecha_pago        = $_POST['fecha_pago'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];

    try {
        $sql = "INSERT INTO pagos (id_socio, id_plan, monto, fecha_pago, fecha_vencimiento, estado_pago) 
                VALUES (?, ?, ?, ?, ?, 'Pagado')";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_socio, $id_plan, $monto, $fecha_pago, $fecha_vencimiento]);

        // Redirigimos al historial de pagos (que crearemos en el proximo paso)
        header('Location: pagos.php');
        exit;

    } catch (PDOException $e) {
        die("Error al registrar el pago: " . $e->getMessage());
    }
} else {
    header('Location: registrar_pago.php');
    exit;
}
?>