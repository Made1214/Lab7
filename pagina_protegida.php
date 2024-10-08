<?php
// Incluir el archivo de conexión
require_once 'conexion.php';

// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Ejemplo de consulta: obtener información adicional del usuario
$stmt = $conn->prepare("SELECT * FROM usuario WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$user_info = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Protegida</title>
</head>
<body>

<h1>Bienvenido a la Página Protegida, <?php echo $_SESSION['username']; ?>!</h1>

<p>Información adicional del usuario:</p>
<p>Nombre de usuario: <?php echo $user_info['user']; ?></p>

<?php
if ($_SESSION['is_admin'] === '1') {
    echo "<p>Este contenido es exclusivo para administradores.</p>";
}
?>

</body>
</html>
