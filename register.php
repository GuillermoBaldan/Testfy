<?php
// Verificar si se ha enviado el formulario de registro
if (isset($_POST["registro"])) {
    // Obtener los datos del formulario
    $nombre = $_POST["username"];
    $email = $_POST["email"];
    $contrasena = $_POST["password"];

    // Conexión a la base de datos (reemplaza con tus propias credenciales)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "TestfyDB"; // Asegúrate de que sea el nombre correcto de tu base de datos

    $conn = new mysqli($servername, $username, $password);

    // Verificar la conexión a la base de datos
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Crear la base de datos TestfyDB si no existe
    $sqlCreateDB = "CREATE DATABASE IF NOT EXISTS TestfyDB";
    if ($conn->query($sqlCreateDB) === TRUE) {
        echo "Base de datos TestfyDB creada con éxito.<br>";

        // Cambiar a la base de datos TestfyDB
        $conn->select_db("TestfyDB");

        // Verificar y crear la tabla Usuarios si no existe
        $sqlCreateUsuariosTable = "CREATE TABLE IF NOT EXISTS Usuarios (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Nombre VARCHAR(255) NOT NULL,
            Contrasena VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL
        )";
        $conn->query($sqlCreateUsuariosTable);

        // Verificar y crear la tabla Tests si no existe
        $sqlCreateTestsTable = "CREATE TABLE IF NOT EXISTS Tests (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Titulo VARCHAR(255) NOT NULL,
            Descripcion TEXT,
            FechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UsuarioID INT,
            FOREIGN KEY (UsuarioID) REFERENCES Usuarios(ID)
        )";
        $conn->query($sqlCreateTestsTable);

        // Verificar y crear la tabla PreguntasRespuestas si no existe
        $sqlCreatePreguntasRespuestasTable = "CREATE TABLE IF NOT EXISTS PreguntasRespuestas (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            EnunciadoPregunta TEXT,
            EnunciadoRespuesta TEXT,
            FechaUltimoAcierto TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            TiempoDelta INT,
            TestID INT,
            FOREIGN KEY (TestID) REFERENCES Tests(ID)
        )";
        $conn->query($sqlCreatePreguntasRespuestasTable);

        // Hash de la contraseña para mayor seguridad (puedes usar otras funciones de hash)
        $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);

        // Preparar la consulta SQL para insertar un nuevo usuario
        $sqlInsertUsuario = "INSERT INTO Usuarios (Nombre, Contrasena, email) VALUES (?, ?, ?)";
        $stmtInsertUsuario = $conn->prepare($sqlInsertUsuario);

        // Comprobar si la preparación de la consulta fue exitosa
        if ($stmtInsertUsuario) {
            // Vincular los parámetros
            $stmtInsertUsuario->bind_param("sss", $nombre, $hashedPassword, $email);

            // Ejecutar la consulta para insertar el nuevo usuario
            if ($stmtInsertUsuario->execute()) {
                // Registro exitoso, redirige a alguna página de éxito o a donde desees
                header("Location: inicio.html");
                exit();
            } else {
                // Error al ejecutar la consulta, muestra un mensaje de error
                echo "Error al registrar el usuario. <a href='register.html'>Regresar</a>";
            }

            // Cerrar la declaración
            $stmtInsertUsuario->close();
        } else {
            // Error en la preparación de la consulta, muestra un mensaje de error
            echo "Error al preparar la consulta. <a href='register.html'>Regresar</a>";
        }
    } else {
        echo "Error al crear la base de datos: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
}
?>
