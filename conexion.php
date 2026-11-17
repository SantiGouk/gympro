<?php
// Parámetros de configuración de la base de datos
$host    = "localhost";
$db      = "sistema_gimnasio";
$user    = "root";
$pass    = ""; // En XAMPP por defecto no lleva contraseña
$charset = "utf8mb4";

// Cadena de conexión (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Reporta errores de MySQL como excepciones
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve los datos como arreglos asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Consultas preparadas reales para evitar Inyección SQL
];

try {
    // Creamos la instancia de PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Descomentá la línea de abajo para probar que conecta:
    // echo "¡Conexión exitosa a la base de datos!";
} catch (\PDOException $e) {
    // Si falla, muestra el mensaje de error exacto
    die("Error de conexión: " . $e->getMessage());
}
?>