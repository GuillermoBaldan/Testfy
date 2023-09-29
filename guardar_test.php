<?php
// Iniciar la sesión (esto debe estar al principio de cada script que use sesiones)
session_start();

// Comprobar si el usuario ha iniciado sesión y si el ID del usuario no es nulo
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_id"] === null) {
    // Si el usuario no ha iniciado sesión o el ID del usuario es nulo, redirige a la página de inicio de sesión
    header("Location: index.html");
    exit();
}

// Resto del código para insertar el test en la base de datos (sin cambios)
// ...


// Conexión a la base de datos (reemplaza con tus propias credenciales)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "TestfyDB"; // Asegúrate de que sea el nombre correcto de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname); // Selecciona la base de datos

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener datos del formulario
$titulo = $_POST["titulo"];
$descripcion = $_POST["descripcion"];
$enunciado_pregunta = $_POST["enunciado_pregunta"];
$enunciado_respuesta = $_POST["enunciado_respuesta"];
$usuario_id = $_SESSION["usuario_id"];

// Preparar la consulta SQL para insertar el test en la base de datos
$sql = "INSERT INTO Tests (Titulo, Descripcion, UsuarioID) VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);

// Comprobar si la preparación de la consulta fue exitosa
if ($stmt) {
    // Vincular los parámetros
    $stmt->bind_param("ssi", $titulo, $descripcion, $usuario_id);

    // Ejecutar la consulta para insertar el test
   // Ejecutar la consulta para insertar el test
if ($stmt->execute()) {
    // Obtener el ID del test recién insertado
    $test_id = $conn->insert_id;

    if ($test_id !== null) {
        // Preparar la consulta para insertar la pregunta y respuesta en la tabla PreguntasRespuestas
        $sqlPregResp = "INSERT INTO PreguntasRespuestas (EnunciadoPregunta, EnunciadoRespuesta, TestID) VALUES (?, ?, ?)";
        $stmtPregResp = $conn->prepare($sqlPregResp);

        if ($stmtPregResp) {
            // Vincular los parámetros
            $stmtPregResp->bind_param("ssi", $enunciado_pregunta, $enunciado_respuesta, $test_id);

            // Ejecutar la consulta para insertar la pregunta y respuesta
            if ($stmtPregResp->execute()) {
                // Test y preguntas/respuestas creados exitosamente, redirige a alguna página de éxito o a donde desees
                header("Location: test_creado_exitosamente.html");
                exit();
            } else {
                // Error al ejecutar la consulta de preguntas/respuestas, muestra un mensaje de error
                echo "Error al crear el test. <a href='crear_test.html'>Regresar</a>";
            }

            // Cerrar la declaración de preguntas/respuestas
            $stmtPregResp->close();
        } else {
            // Error en la preparación de la consulta de preguntas/respuestas, muestra un mensaje de error
            echo "Error al preparar la consulta de preguntas/respuestas. <a href='crear_test.html'>Regresar</a>";
        }
    } else {
        // El ID del test es nulo, muestra una notificación
        echo "El ID del test es nulo. <a href='crear_test.html'>Regresar</a>";
    }
} else {
    // Error al ejecutar la consulta de test, muestra un mensaje de error
    echo "Error al crear el test. <a href='crear_test.html'>Regresar</a>";
}

// ...

    // Cerrar la declaración de test
    $stmt->close();
} else {
    // Error en la preparación de la consulta de test, muestra un mensaje de error
    echo "Error al preparar la consulta de test. <a href='crear_test.html'>Regresar</a>";
}

// Cerrar la conexión
$conn->close();
?>
