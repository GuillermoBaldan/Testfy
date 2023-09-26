<?php
// Conexión a MySQL sin seleccionar una base de datos específica
$servername = "localhost";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si la base de datos TestfyDB existe
$sql = "CREATE DATABASE IF NOT EXISTS TestfyDB";
if ($conn->query($sql) === TRUE) {
    // Cambiar a la base de datos TestfyDB
    $conn->select_db("TestfyDB");
    
    // Crear la tabla usuarios si no existe
    $createTableSQL = "CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        contrasena VARCHAR(255) NOT NULL
    )";
    
    if ($conn->query($createTableSQL) === TRUE) {
        // Obtener datos del formulario
        $nombre = $_POST["username"];
        $email = $_POST["email"];
        $contrasena = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hashear la contraseña
        
        // Insertar datos en la tabla usuarios
        $insertSQL = "INSERT INTO usuarios (nombre, email, contrasena) VALUES ('$nombre', '$email', '$contrasena')";
        
        if ($conn->query($insertSQL) === TRUE) {
            echo "Registro exitoso. <a href='login.html'>Inicia sesión</a>";
        } else {
            echo "Error al registrar el usuario: " . $conn->error;
        }
    } else {
        echo "Error al crear la tabla usuarios: " . $conn->error;
    }
    
    $conn->close();
} else {
    echo "Error al crear la base de datos: " . $conn->error;
}
?>
