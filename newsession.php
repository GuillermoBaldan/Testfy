<?php
// Iniciar la sesión (esto debe estar al principio de cada script que use sesiones)
session_start();

// Comprobar si el formulario se envió
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Conexión a la base de datos (reemplaza con tus propias credenciales)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "TestfyDB"; // Asegúrate de que sea el nombre correcto de tu base de datos

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener datos del formulario
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Consulta SQL para obtener la contraseña hasheada del usuario
    $sql = "SELECT id, nombre, contrasena FROM usuarios WHERE email = '$email'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row["contrasena"];

        // Verificar la contraseña hasheada
        if (password_verify($password, $hashedPassword)) {
            // Las credenciales son válidas, inicia una sesión
            $_SESSION["usuario_id"] = $row["id"];
            $_SESSION["usuario_nombre"] = $row["nombre"];

            // Redirige al usuario a una página de inicio de sesión exitosa o a donde desees
            header("Location: inicio.html");
            exit();
        } else {
            // Credenciales incorrectas, muestra un mensaje de error
            echo "Credenciales incorrectas. <a href='index.html'>Regresar</a>";
        }
    } else {
        // Usuario no encontrado, muestra un mensaje de error
        echo "Usuario no encontrado. <a href='index.html'>Regresar</a>";
    }

    $conn->close();
} else {
    // Si alguien intenta acceder a este script directamente sin enviar el formulario, redirige al formulario de inicio de sesión
    header("Location: index.html");
    exit();
}
?>
