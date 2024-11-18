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
            <div id="header-msg" class="ml-5 align-self-center">Parqueadero Inteligente</div>            
        </div>
    </header>
    <div class="table-container mt-5 mb-5 w-75 mx-auto">
        <h5 class="text-center mb-3">Búsqueda de Carros</h5>
        <input type="text" class="w-100 mb-3" id="searchInput" placeholder="Búsqueda...">
        <table class="table table-striped shadow" id="parkingTable">
            <thead class="text-white" id="tableHead">
                <tr>
                    <th scope="col">Nombre y Apellido</th>
                    <th scope="col">Placa</th>
                    <th scope="col">Fecha de Entrada</th>
                    <th scope="col">Fecha de Salida</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "parking_db";

                // Crear conexión
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Verificar conexión
                if ($conn->connect_error) {
                    die("Conexión fallida: " . $conn->connect_error);
                }

                // Borrar registro
                if (isset($_GET['delete_id'])) {
                    $id = intval($_GET['delete_id']);
                    $sql = "DELETE FROM vehicles WHERE id=$id";
                    if ($conn->query($sql) === TRUE) {
                        echo "<script>alert('Registro borrado exitosamente');</script>";
                    } else {
                        echo "<script>alert('Error al borrar el registro: " . $conn->error . "');</script>";
                    }
                }

                // Limpiar fechas
                if (isset($_GET['clear_id'])) {
                    $id = intval($_GET['clear_id']);
                    $sql = "UPDATE vehicles SET entry_time=NULL, exit_time=NULL WHERE id=$id";
                    if ($conn->query($sql) === TRUE) {
                        echo "<script>alert('Fechas borradas exitosamente');</script>";
                    } else {
                        echo "<script>alert('Error al borrar las fechas: " . $conn->error . "');</script>";
                    }
                }

                // Obtener registros
                $sql = "SELECT * FROM vehicles";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Mostrar datos de cada fila
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row["owner"]) . "</td>
                                <td>" . htmlspecialchars($row["licensePlate"]) . "</td>
                                <td>" . htmlspecialchars($row["entry_time"]) . "</td>
                                <td>" . htmlspecialchars($row["exit_time"]) . "</td>
                                <td>
                                    <a href='?delete_id=" . $row["id"] . "' class='btn btn-danger'>Borrar</a>
                                    <a href='?clear_id=" . $row["id"] . "' class='btn btn-warning'>Borrar Fechas</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay datos disponibles</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script src="./JS/bootstrap.min.js"></script>
    <script src="./JS/core.js"></script>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var input = document.getElementById('searchInput');
            var filter = input.value.toLowerCase();
            var table = document.getElementById('parkingTable');
            var tr = table.getElementsByTagName('tr');

            for (var i = 1; i < tr.length; i++) {
                var td = tr[i].getElementsByTagName('td');
                var match = false;
                for (var j = 0; j < td.length; j++) {
                    if (td[j]) {
                        if (td[j].innerHTML.toLowerCase().indexOf(filter) > -1) {
                            match = true;
                            break;
                        }
                    }
                }
                if (match) {
                    tr[i].style.display = '';
                } else {
                    tr[i].style.display = 'none';
                }
            }
        });
    </script>
</body>

</html>
