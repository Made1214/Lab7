<?php
session_start(); // Iniciar la sesión
 
// Inicializar la variable de paso si no existe
if (!isset($_SESSION['step'])) {
    $_SESSION['step'] = 1;  // Iniciar en el primer paso
}
 
// Manejo de los pasos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['next'])) {
        $_SESSION['step']++; // Avanzar al siguiente paso
 
        // Guardar los datos ingresados en cada paso en la sesión
        if ($_SESSION['step'] == 2) {
            $_SESSION['personal_data'] = [
                'nombre' => $_POST['nombre'],
                'correo' => $_POST['correo'],
            ];
        } elseif ($_SESSION['step'] == 3) {
            $_SESSION['account_data'] = [
                'username' => $_POST['username'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT), // Cifrar la contraseña
            ];
        }
    } elseif (isset($_POST['previous'])) {
        $_SESSION['step']--; // Volver al paso anterior
    } elseif (isset($_POST['submit'])) {
        // Guardar los datos en la base de datos en el último paso
        require_once 'conexion.php'; // Incluir la conexión a la base de datos
 
        // Guardar en la base de datos
        $stmt = $conn->prepare("INSERT INTO usuario (user, password, email, nombre) VALUES (:username, :password, :correo, :nombre)");
        $stmt->bindParam(':username', $_SESSION['account_data']['username']);
        $stmt->bindParam(':password', $_SESSION['account_data']['password']);
        $stmt->bindParam(':correo', $_SESSION['personal_data']['correo']);
        $stmt->bindParam(':nombre', $_SESSION['personal_data']['nombre']);
        $stmt->execute();
 
        // Limpiar la sesión después de registrarse
        session_destroy();
 
        // Redirigir al login o página de bienvenida
        header("Location: index.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registro de Usuario - Formulario Multipaso</title>
</head>
<body>
 
<h1>Registro de Usuario - Paso <?php echo $_SESSION['step']; ?></h1>
 
<?php
// Mostrar el contenido según el paso actual
if ($_SESSION['step'] == 1) {
?>
<!-- Paso 1: Datos Personales -->
<form method="POST" action="registro.php">
<label for="nombre">Nombre Completo:</label><br>
<input type="text" name="nombre" required><br><br>
 
        <label for="correo">Correo Electrónico:</label><br>
<input type="email" name="correo" required><br><br>
 
        <input type="submit" name="next" value="Siguiente">
</form>
 
<?php
} elseif ($_SESSION['step'] == 2) {
?>
<!-- Paso 2: Información de la Cuenta -->
<form method="POST" action="registro.php">
<label for="username">Nombre de Usuario:</label><br>
<input type="text" name="username" required><br><br>
 
        <label for="password">Contraseña:</label><br>
<input type="password" name="password" required><br><br>
 
        <input type="submit" name="previous" value="Anterior">
<input type="submit" name="next" value="Siguiente">
</form>
 
<?php
} elseif ($_SESSION['step'] == 3) {
?>
<!-- Paso 3: Confirmación -->
<h2>Confirma tus datos</h2>
<p><strong>Nombre:</strong> <?php echo $_SESSION['personal_data']['nombre']; ?></p>
<p><strong>Correo:</strong> <?php echo $_SESSION['personal_data']['correo']; ?></p>
<p><strong>Nombre de Usuario:</strong> <?php echo $_SESSION['account_data']['username']; ?></p>
 
    <form method="POST" action="registro.php">
<input type="submit" name="previous" value="Anterior">
<input type="submit" name="submit" value="Registrar">
</form>
 
<?php
}
?>
 
</body>
</html>