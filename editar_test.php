<?php
// Establecer estilo en línea
echo '<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .options-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .option-button {
            display: block;
            margin: 10px auto;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .option-button:hover {
            background-color: #0056b3;
        }
    </style>';

// Conexión a la base de datos (reemplaza con tus propias credenciales)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "TestfyDB"; // Asegúrate de que sea el nombre correcto de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta SQL para verificar si hay tests en la base de datos
$sql = "SELECT COUNT(*) as testCount FROM Tests";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $testCount = $row["testCount"];

    if ($testCount > 0) {
        // Hay tests disponibles, redirige a la página de edición
        header("Location: editar_test.html");
        exit();
    } else {
        // No hay tests disponibles, muestra una notificación
        echo '<body>
                <div class="container">
                    <h1>No hay ningún test creado</h1>
                    <div class="options-container">
                        <a href="inicio.html" class="option-button">Volver a Inicio</a>
                        <a href="crear_test.php" class="option-button">Crear Test</a>
                    </div>
                </div>
              </body>';
    }
} else {
    // Error al verificar los tests, muestra un mensaje de error
    echo "Error al verificar los tests. <a href='crear_test.html'>Crear un nuevo test</a>";
}

$conn->close();
?>
