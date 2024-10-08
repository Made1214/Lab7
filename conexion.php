<?php
// Datos de conexión
$host = "127.0.0.1";
$dbname = "cookiesbd"; // Nombre de tu base de datos
$username = "root";    // Nombre de usuario
$password = "";        // Contraseña (puedes dejarla vacía si no tienes una contraseña para localhost)

try {
    // Crear la conexión con PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Establecer el modo de error de PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Manejar el error de conexión
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>
