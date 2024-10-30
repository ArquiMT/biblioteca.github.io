<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "biblioteca";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Función para obtener el contenido binario de una imagen
function getProfilePicture($filePath) {
    return file_get_contents($filePath);
}

// Registro de Usuario
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $country_code = $_POST['country_code'];
    $telefono = $_POST['telefono'];
    
    // Combinar el código del país con el número de teléfono
    $telefono_completo = $country_code . $telefono;

    // Validación de Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || (!str_ends_with($email, '@gmail.com') && !str_ends_with($email, '@outlook.com'))) {
        echo "El correo electrónico debe ser @gmail.com o @outlook.com";
    } else {
        // Seleccionar una imagen aleatoria
        $images = ["images/gato1.png", "images/gato2.png", "images/gato3.png", "images/gato4.png", "images/gato5.png"];
        $randomIndex = array_rand($images);
        $randomImage = $images[$randomIndex];
        $profilePicture = getProfilePicture($randomImage);
        $profilePicture = $conn->real_escape_string($profilePicture);

        // Insertar nuevo usuario en la base de datos
        $sql = "INSERT INTO usuarios (username, email, password, telefono, profile_picture) VALUES ('$username', '$email', '$password', '$telefono_completo', '$profilePicture')";

        if ($conn->query($sql) === TRUE) {
            // Iniciar sesión automáticamente después del registro
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}


// Login de Usuario
if (isset($_POST['login'])) {
    $login_identifier = $_POST['login_identifier'];
    $password = $_POST['password'];

    // Verificar si es un email o username
    if (filter_var($login_identifier, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT * FROM usuarios WHERE email='$login_identifier'";
    } else {
        $sql = "SELECT * FROM usuarios WHERE username='$login_identifier'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Iniciar sesión
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");
            exit();
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Usuario no encontrado";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Registro</title>
    <link rel="stylesheet" href="stylelogin.css">
</head>
<body>
    <div class="container">
        <div id="welcome-section">
            <h1>Bienvenido a la Biblioteca</h1>
            <p>¡Nos alegra tenerte aquí! Por favor, inicia sesión o regístrate para acceder a nuestras funcionalidades.</p>
            <button id="show-login">Iniciar Sesión</button>
            <button id="show-register">Registrarse</button>
        </div>
        
        <div id="form-section">
            <!-- Formulario de Iniciar Sesión -->
            <div id="login-form" class="form" style="display: none;">
                <h2>Iniciar Sesión</h2>
                <form action="login.php" method="post">
                    <input type="text" name="login_identifier" placeholder="Email o Nombre de Usuario" required><br>
                    <input type="password" name="password" placeholder="Contraseña" required><br>
                    <button type="submit" name="login">Iniciar Sesión</button>
                </form>
            </div>
            
            <!-- Formulario de Registro -->
            <div id="register-form" class="form" style="display: none;">
                <h2>Registrarse</h2>
                <form action="login.php" method="post">
                    <input type="text" name="username" placeholder="Nombre de Usuario" required><br>
                    <input type="email" name="email" placeholder="Correo Electrónico (@gmail.com o @outlook.com)" required><br>
                    <input type="password" name="password" placeholder="Contraseña" required><br>
                    
                    <div style="display: flex; align-items: center;">
                        <select id="country" required style="flex-grow: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="+52">México</option>
                            <option value="+1">Estados Unidos</option>
                            <option value="+34">España</option>
                            <option value="+58">Venezuela</option>
                            <option value="+51">Perú</option>
                        </select>
                        <input type="text" id="country_code" name="country_code" readonly style="width: 60px; padding: 10px; margin-left: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <input type="text" name="telefono" placeholder="Número de Teléfono" required style="flex-grow: 2; padding: 10px; margin-left: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <br>
                    <button type="submit" name="register">Registrarse</button>
                </form>
            </div>
        </div>
    </div>
    <script src="scriptlogin.js"></script>
    <script>
        document.getElementById('country').addEventListener('change', function() {
            var selectedCountryCode = this.value;
            document.getElementById('country_code').value = selectedCountryCode;
        });

        // Inicializar el código de país en función de la selección predeterminada
        document.getElementById('country_code').value = document.getElementById('country').value;
    </script>
</body>
</html>