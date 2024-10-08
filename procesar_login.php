<?php
// Incluir el archivo de conexión
require_once 'conexion.php';

// Iniciar la sesión
session_start();

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Preparar la consulta para verificar las credenciales del usuario
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE user = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // Si se encuentra un usuario con esas credenciales
    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Guardar los datos del usuario en la sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['user'];
        $_SESSION['is_admin'] = $user['admin'];

        // Redirigir a la página protegida
        header("Location: pagina_protegida.php");
        exit();
    } else {
        // Credenciales inválidas, redirigir al login
        echo "Usuario o contraseña incorrectos.";
    }
}
?>
