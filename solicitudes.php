<?php
require 'funciones/conexion.php';
session_start();
// Revisa si el usuario está logueado o no
if (!isset($_SESSION['USUARIO_ID'])) {
    header("location:index.php");
}
// Establece la conexion con la base de datos
$conn = connect();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Buddy Loyal</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body>
    <div class="contenedor">
        <?php include 'vistas/navbar.php'; ?>
        <h1>Solicitudes de amistad</h1>
        <?php
        // Abre el request
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (isset($_GET['aceptar'])) {
                $sql = "UPDATE AMISTAD
                        SET AMISTAD.ESTADO_AMISTAD = 1
                        WHERE AMISTAD.USUARIO1_ID = {$_GET['id']} AND AMISTAD.USUARIO2_ID = {$_SESSION['USUARIO_ID']}";
                $query = mysqli_query($conn, $sql);
                if ($query) {
                    echo '<div class="queryUsuarios">';
                    echo 'Ha agregado a ' . $_GET['nombre'];
                    echo '<br><br>';
                    echo 'Redirigiendo en 5 segundos';
                    echo '<br><br>';
                    echo '</div>';
                    echo '<br>';
                    header("refresh:5; url=solicitudes.php");
                } else {
                    echo mysqli_error($conn);
                }
            } else if (isset($_GET['ignorar'])) {
                $sql6 = "DELETE FROM AMISTAD
                        WHERE AMISTAD.USUARIO1_ID = {$_GET['id']} AND AMISTAD.USUARIO2_ID = {$_SESSION['USUARIO_ID']}";
                $query6 = mysqli_query($conn, $sql6);
                if ($query6) {
                    echo '<div class="queryUsuario">';
                    echo 'Ha ignorado a ' . $_GET['nombre'];
                    echo '<br><br>';
                    echo 'Redirigiendo en 5 segundos';
                    echo '<br><br>';
                    echo '</div>';
                    echo '<br>';
                    header("refresh:5; url=solicitudes.php");
                }
            }
        }
        //
        ?>
        <?php
        $sql = "SELECT USUARIOS.USUARIO_GENERO, USUARIOS.USUARIO_ID, USUARIOS.USUARIO_NOMBRE, USUARIOS.USUARIO_APELLIDO
                FROM USUARIOS
                JOIN AMISTAD
                ON AMISTAD.USUARIO2_ID = {$_SESSION['USUARIO_ID']} AND AMISTAD.ESTADO_AMISTAD = 0 AND AMISTAD.USUARIO1_ID = USUARIOS.USUARIO_ID";
        $query = mysqli_query($conn, $sql);
        $width = '168px';
        $height = '168px';
        if (!$query)
            echo mysqli_error($conn);
        if ($query) {
            if (mysqli_num_rows($query) == 0) {
                echo '<div class="queryUsuario">';
                echo 'No hay solicitudes pendientes';
                echo '<br><br>';
                echo '</div>';
            }
            while ($row = mysqli_fetch_assoc($query)) {
                echo '<div class="queryUsuario">';
                include 'vistas/foto_perfil.php';
                echo '<br>';
                echo '<a class="linkPerfil" href="perfil.php?id=' . $row['USUARIO_ID'] . '">' . $row['USUARIO_NOMBRE'] . ' ' . $row['USUARIO_APELLIDO'] . '<a>';
                echo '<form method="get" action="solicitudes.php">';
                echo '<input type="hidden" name="id" value="' . $row['USUARIO_ID'] . '">';
                echo '<input type="hidden" name="nombre" value="' . $row['USUARIO_NOMBRE'] . '">';
                echo '<input type="submit" value="Aceptar" name="aceptar">';
                echo '<br><br>';
                echo '<input type="submit" value="Ignorar" name="ignorar">';
                echo '<br><br>';
                echo '</form>';
                echo '</div>';
                echo '<br>';
            }
        }
        ?>
    </div>
</body>

</html>