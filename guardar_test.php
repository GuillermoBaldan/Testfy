<?php
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
$titulo = $_POST["titulo"];
$descripcion = $_POST["descripcion"];
$enunciadoPregunta = $_POST["enunciado_pregunta"];
$enunciadoRespuesta = $_POST["enunciado_respuesta"];

// Consulta SQL para insertar los datos en la base de datos
$sql = "INSERT INTO Tests (Titulo, Descripcion) VALUES ('$titulo', '$descripcion')";
if ($conn->query($sql) === TRUE) {
    // Obtenemos el ID del test recién creado
    $testID = $conn->insert_id;

    // Insertamos la pregunta y la respuesta asociadas al test
    $sql2 = "INSERT INTO PreguntasRespuestas (EnunciadoPregunta, EnunciadoRespuesta, TestID) VALUES ('$enunciadoPregunta', '$enunciadoRespuesta', $testID)";
    
    if ($conn->query($sql2) === TRUE) {
        echo "Test creado exitosamente. <a href='inicio.html'>Volver al Inicio</a>";
    } else {
        echo "Error al insertar la pregunta y respuesta: " . $conn->error;
    }
} else {
    echo "Error al crear el test: " . $conn->error;
}

$conn->close();
?>
