<?php
session_start();
if (isset($_SESSION['username'])) {
    $usernamel = $_SESSION['username'];
} else {
    header('Location: login.php');
    exit;
}
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "biblioteca";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


$sql = "SELECT telefono, profile_picture FROM usuarios WHERE username='$usernamel'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $telefono = $row['telefono'];
    $profile_picture = $row['profile_picture'];
} else {
    // Si no se encuentran los datos del usuario, destruir la sesión y redirigir a login
    session_destroy();
    header('Location: login.php');
    exit;
}


// Procesar formulario de libros
if (isset($_POST['submit_libro'])) {
    $titulo = $_POST['titulo_libro'];
    $autor = $_POST['autor_libro'];
    $editorial = $_POST['editorial_libro'];
    $fecha = $_POST['fecha_libro'];

    $sql = "INSERT INTO libros (titulo, autor, editorial, fecha) VALUES ('$titulo', '$autor', '$editorial', '$fecha')";

    if ($conn->query($sql) === TRUE) {
        echo "Libro agregado exitosamente";
    } else {
        echo "Error al agregar libro: " . $conn->error;
    }
}

// Procesar formulario de revistas
if (isset($_POST['submit_revista'])) {
    $titulo = $_POST['titulo_revista'];
    $volumen = $_POST['volumen_revista'];
    $numero = $_POST['numero_revista'];
    $editorial = $_POST['editorial_revista'];
    $fecha = $_POST['fecha_revista'];

    $sql = "INSERT INTO revistas (titulo, volumen, numero, editorial, fecha) VALUES ('$titulo', '$volumen', '$numero', '$editorial', '$fecha')";

    if ($conn->query($sql) === TRUE) {
        echo "Revista agregada exitosamente";
    } else {
        echo "Error al agregar revista: " . $conn->error;
    }
}

// Procesar formulario de periódicos
if (isset($_POST['submit_periodico'])) {
    $titulo = $_POST['titulo_periodico'];
    $seccion = $_POST['seccion_periodico'];
    $editor = $_POST['editor_periodico'];
    $fecha = $_POST['fecha_periodico'];

    $sql = "INSERT INTO periodicos (titulo, seccion, editor, fecha) VALUES ('$titulo', '$seccion', '$editor', '$fecha')";

    if ($conn->query($sql) === TRUE) {
        echo "Periódico agregado exitosamente";
    } else {
        echo "Error al agregar periódico: " . $conn->error;
    }
}

// Manejo del cierre de sesión
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Agregar Lecturas</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="perfil.css">
</head>
<body>
    <header>
        <button id="menu-btn">☰</button>
    </header>

    <div id="menu-panel">
        <img id="ImagePerfil" src="data:image/jpeg;base64,<?php echo base64_encode($profile_picture); ?>" alt="Imagen de perfil">
        <form action="index.php" method="post" class="accountbuttons">
            <button type="submit" name="logout" class="btnaccount" id="btnlogout">Cerrar Sesión</button>
        </form>
    </div>

    <h1>BIBLIOTECA</h1>

    <!-- Botones para mostrar el formulario correspondiente -->
    <div class="button-container">
        <button onclick="mostrarFormulario('form_libro')" class="btn-bi">Libros</button>
        <button onclick="mostrarFormulario('form_revista')" class="btn-bi">Revistas</button>
        <button onclick="mostrarFormulario('form_periodico')" class="btn-bi">Periódicos</button>
    </div>

    <!-- Formulario para Libros -->
    <div id="form_libro" class="form-container">
        <h2>Agregar Libro</h2>
        <form action="index.php" method="post">
            <label for="titulo_libro">Título del Libro:</label><br>
            <input type="text" id="titulo_libro" name="titulo_libro" required><br><br>

            <label for="autor_libro">Autor:</label><br>
            <input type="text" id="autor_libro" name="autor_libro" required><br><br>

            <label for="editorial_libro">Editorial:</label><br>
            <input type="text" id="editorial_libro" name="editorial_libro" required><br><br>

            <label for="fecha_libro">Fecha de Publicación:</label><br>
            <input type="date" id="fecha_libro" name="fecha_libro" required><br><br>

            <input type="submit" name="submit_libro" value="Agregar Libro">
        </form>
    </div>

    <!-- Formulario para Revistas -->
    <div id="form_revista" class="form-container">
        <h2>Agregar Revista</h2>
        <form action="index.php" method="post">
            <label for="titulo_revista">Título de la Revista:</label><br>
            <input type="text" id="titulo_revista" name="titulo_revista" required><br><br>

            <label for="volumen_revista">Volumen:</label><br>
            <input type="text" id="volumen_revista" name="volumen_revista" required><br><br>

            <label for="numero_revista">Número:</label><br>
            <input type="text" id="numero_revista" name="numero_revista" required><br><br>

            <label for="editorial_revista">Editorial:</label><br>
            <input type="text" id="editorial_revista" name="editorial_revista" required><br><br>

            <label for="fecha_revista">Fecha de Publicación:</label><br>
            <input type="date" id="fecha_revista" name="fecha_revista" required><br><br>

            <input type="submit" name="submit_revista" value="Agregar Revista">
        </form>
    </div>

    <!-- Formulario para Periódicos -->
    <div id="form_periodico" class="form-container">
        <h2>Agregar Periódico</h2>
        <form action="index.php" method="post">
            <label for="titulo_periodico">Título del Periódico:</label><br>
            <input type="text" id="titulo_periodico" name="titulo_periodico" required><br><br>

            <label for="seccion_periodico">Sección:</label><br>
            <input type="text" id="seccion_periodico" name="seccion_periodico" required><br><br>

            <label for="editor_periodico">Nombre del Editor:</label><br>
            <input type="text" id="editor_periodico" name="editor_periodico" required><br><br>

            <label for="fecha_periodico">Fecha de Publicación:</label><br>
            <input type="date" id="fecha_periodico" name="fecha_periodico" required><br><br>

            <input type="submit" name="submit_periodico" value="Agregar Periódico">
        </form>
    </div>

    <!-- Botones para ver los elementos de la base de datos -->
    <div class="button-container">
        <button onclick="mostrarLectura('lectura_libros')" class="btn-bi">Ver Libros</button>
        <button onclick="mostrarLectura('lectura_revistas')" class="btn-bi">Ver Revistas</button>
        <button onclick="mostrarLectura('lectura_periodicos')" class="btn-bi">Ver Periódicos</button>
        <button onclick="mostrarLectura('lectura_todo')" class="btn-bi">Ver Todo</button>
    </div>

    <!-- Contenedores para mostrar los datos de la base de datos -->
    <div id="lectura_libros" class="lectura-container">
        <h2>Libros</h2>
        <?php
        // Mostrar los libros
        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "SELECT titulo, autor, editorial, fecha FROM libros";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='lectura-item'>";
                echo "<strong>Título:</strong> " . $row['titulo'] . "<br>";
                echo "<strong>Autor:</strong> " . $row['autor'] . "<br>";
                echo "<strong>Editorial:</strong> " . $row['editorial'] . "<br>";
                echo "<strong>Fecha:</strong> " . $row['fecha'] . "<br>";
                echo "</div>";
            }
        } else {
            echo "No hay libros disponibles.";
        }
        ?>
    </div>

    <div id="lectura_revistas" class="lectura-container">
        <h2>Revistas</h2>
        <?php
        // Mostrar las revistas
        $sql = "SELECT titulo, volumen, numero, editorial, fecha FROM revistas";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='lectura-item'>";
                echo "<strong>Título:</strong> " . $row['titulo'] . "<br>";
                echo "<strong>Volumen:</strong> " . $row['volumen'] . "<br>";
                echo "<strong>Número:</strong> " . $row['numero'] . "<br>";
                echo "<strong>Editorial:</strong> " . $row['editorial'] . "<br>";
                echo "<strong>Fecha:</strong> " . $row['fecha'] . "<br>";
                echo "</div>";
            }
        } else {
            echo "No hay revistas disponibles.";
        }
        ?>
    </div>

    <div id="lectura_periodicos" class="lectura-container">
        <h2>Periódicos</h2>
        <?php
        // Mostrar los periódicos
        $sql = "SELECT titulo, seccion, editor, fecha FROM periodicos";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='lectura-item'>";
                echo "<strong>Título:</strong> " . $row['titulo'] . "<br>";
                echo "<strong>Sección:</strong> " . $row['seccion'] . "<br>";
                echo "<strong>Editor:</strong> " . $row['editor'] . "<br>";
                echo "<strong>Fecha:</strong> " . $row['fecha'] . "<br>";
                echo "</div>";
            }
        } else {
            echo "No hay periódicos disponibles.";
        }
        ?>
    </div>

    <div id="lectura_todo" class="lectura-container">
        <h2>Todos los elementos</h2>
        <?php
        // Mostrar todos los elementos (libros, revistas, periódicos)
        echo "<h3>Libros:</h3>";
        $sql = "SELECT titulo, autor, editorial, fecha FROM libros";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='lectura-item'>";
                echo "<strong>Título:</strong> " . $row['titulo'] . "<br>";
                echo "<strong>Autor:</strong> " . $row['autor'] . "<br>";
                echo "<strong>Editorial:</strong> " . $row['editorial'] . "<br>";
                echo "<strong>Fecha:</strong> " . $row['fecha'] . "<br>";
                echo "</div>";
            }
        } else {
            echo "No hay libros disponibles.";
        }

        echo "<h3>Revistas:</h3>";
        $sql = "SELECT titulo, volumen, numero, editorial, fecha FROM revistas";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='lectura-item'>";
                echo "<strong>Título:</strong> " . $row['titulo'] . "<br>";
                echo "<strong>Volumen:</strong> " . $row['volumen'] . "<br>";
                echo "<strong>Número:</strong> " . $row['numero'] . "<br>";
                echo "<strong>Editorial:</strong> " . $row['editorial'] . "<br>";
                echo "<strong>Fecha:</strong> " . $row['fecha'] . "<br>";
                echo "</div>";
            }
        } else {
            echo "No hay revistas disponibles.";
        }

        echo "<h3>Periódicos:</h3>";
        $sql = "SELECT titulo, seccion, editor, fecha FROM periodicos";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='lectura-item'>";
                echo "<strong>Título:</strong> " . $row['titulo'] . "<br>";
                echo "<strong>Sección:</strong> " . $row['seccion'] . "<br>";
                echo "<strong>Editor:</strong> " . $row['editor'] . "<br>";
                echo "<strong>Fecha:</strong> " . $row['fecha'] . "<br>";
                echo "</div>";
            }
        } else {
            echo "No hay periódicos disponibles.";
        }
        ?>
    </div>
    <script src="scripts.js"></script>
</body>
</html>