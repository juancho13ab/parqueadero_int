<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./CSS/bootstrap.min.css">
    <link rel="stylesheet" href="./CSS/style.css">
    <link rel="icon" type="image/png" href="Images/logo2.png"/>
    <title>Parking CW</title>
</head>
<body style="background-color:lightblue;">
    <header class="shadow">
        <div class="header-content d-flex justify-content-center p-2">
            <img src="./Images/parking.svg" alt="" id="header-logo">
            <div id="header-msg" class="ml-5 align-self-center">Registro de Placa</div>            
        </div>
    </header>
    <div class="form-container mt-5">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $servername = "localhost"; // Cambia si tu servidor no es local
            $username = "root"; // Cambia si tu usuario de MySQL es diferente
            $password = ""; // Cambia la contraseña si es diferente
            $dbname = "parking_db"; // El nombre de tu base de datos

            // Crear conexión
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verificar conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            // Obtener los datos del formulario
            $owner = $conn->real_escape_string($_POST['owner']);
            $car = $conn->real_escape_string($_POST['car']);
            $licensePlate = $conn->real_escape_string($_POST['licensePlate']);

            // Consulta para insertar los datos
            $sql = "INSERT INTO vehicles (owner, code, licensePlate) VALUES ('$owner', '$car', '$licensePlate')";

            // Ejecutar la consulta
            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success'>Registro guardado exitosamente</div>";
            } else {
                echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
            }

            // Cerrar la conexión
            $conn->close();
        }
        ?>

        <!-- Formulario de registro -->
        <form class="w-50 mx-auto" id="entryForm" method="POST">            
            <div class="form-group">
                <center><img src="./Images/logo.png" width=210 alt=""></center></br></br>
                <label for="owner">Nombre y Apellido:</label>
                <input type="text" class="form-control rounded-0 shadow-sm" id="owner" name="owner" placeholder="Dueño del Carro" required>
            </div>
            <div class="form-group">
                <label for="car">Código:</label>
                <input type="text" class="form-control rounded-0 shadow-sm" id="car" name="car" placeholder="Número Único" required>
            </div>
            <div class="form-group">
                <label for="licensePlate">Placa:</label>
                <input type="text" class="form-control rounded-0 shadow-sm" id="licensePlate" name="licensePlate" placeholder="LLL-NNN" required>
            </div>
            <button type="submit" class="btn mx-auto d-block mt-5 rounded-0 shadow" id="btnOne">Guardar</button>
        </form>
    </div>
    <script src="./JS/jquery.min.js"></script>
    <script src="./JS/bootstrap.bundle.min.js"></script>
</body>
</html>
