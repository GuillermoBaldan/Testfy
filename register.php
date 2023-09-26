<?php
// Conexión a MySQL sin seleccionar una base de datos específica
$servername = "localhost";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Crear la base de datos TestfyDB si no existe
$sqlCreateDB = "CREATE DATABASE IF NOT EXISTS TestfyDB";
if ($conn->query($sqlCreateDB) === TRUE) {
    echo "Base de datos TestfyDB creada con éxito.<br>";
    
    // Cambiar a la base de datos TestfyDB
    $conn->select_db("TestfyDB");

    // Crear la tabla Usuarios
    $sqlCreateUsuariosTable = "CREATE TABLE IF NOT EXISTS Usuarios (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        Nombre VARCHAR(255) NOT NULL,
        Contrasena VARCHAR(255) NOT NULL,
        CorreoElectronico VARCHAR(255) NOT NULL
    )";

    if ($conn->query($sqlCreateUsuariosTable) === TRUE) {
        echo "Tabla Usuarios creada con éxito.<br>";
        
        // Crear la tabla Tests
        $sqlCreateTestsTable = "CREATE TABLE IF NOT EXISTS Tests (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Titulo VARCHAR(255) NOT NULL,
            Descripcion TEXT,
            FechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UsuarioID INT,
            FOREIGN KEY (UsuarioID) REFERENCES Usuarios(ID)
        )";

        if ($conn->query($sqlCreateTestsTable) === TRUE) {
            echo "Tabla Tests creada con éxito.<br>";
            
            // Crear la tabla PreguntasRespuestas
            $sqlCreatePreguntasRespuestasTable = "CREATE TABLE IF NOT EXISTS PreguntasRespuestas (
                ID INT AUTO_INCREMENT PRIMARY KEY,
                EnunciadoPregunta TEXT,
                EnunciadoRespuesta TEXT,
                FechaUltimoAcierto TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                TiempoDelta INT,
                TestID INT,
                FOREIGN KEY (TestID) REFERENCES Tests(ID)
            )";

            if ($conn->query($sqlCreatePreguntasRespuestasTable) === TRUE) {
                echo "Tabla PreguntasRespuestas creada con éxito.<br>";
                
                // Redirigir a inicio.html
                header("Location: inicio.html");
                exit();
            } else {
                echo "Error al crear la tabla PreguntasRespuestas: " . $conn->error;
            }
        } else {
            echo "Error al crear la tabla Tests: " . $conn->error;
        }
    } else {
        echo "Error al crear la tabla Usuarios: " . $conn->error;
    }
} else {
    echo "Error al crear la base de datos: " . $conn->error;
}

$conn->close();
?>
