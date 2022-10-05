<?php
require 'funciones/conexion.php';
session_start();

// Revisa si aún sigue logueado el usuario
if (!isset($_SESSION['USUARIO_ID'])) {
    header("location:index.php");
}

$conn = connect();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Buddy Loyal</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <style>
        .frame a {
            text-decoration: none;
            color: #4267b2;
        }

        .frame a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="contenedor">
        <?php include 'vistas/navbar.php'; ?>
        <h1>Amigos</h1>
        <?php
        echo '<center>';
        $sql = "SELECT USUARIOS.USUARIO_ID, USUARIOS.USUARIO_NOMBRE, USUARIOS.USUARIO_APELLIDO, USUARIOS.USUARIO_GENERO
                    FROM USUARIOS
                    JOIN (
                        SELECT AMISTAD.USUARIO1_ID AS USUARIO_ID
                        FROM AMISTAD
                        WHERE AMISTAD.USUARIO2_ID = {$_SESSION['USUARIO_ID']} AND AMISTAD.ESTADO_AMISTAD = 1
                        UNION
                        SELECT AMISTAD.USUARIO2_ID AS USUARIO_ID
                        FROM AMISTAD
                        WHERE AMISTAD.USUARIO1_ID = {$_SESSION['USUARIO_ID']} AND AMISTAD.ESTADO_AMISTAD = 1
                    ) USUARIOAMIGOS
                    ON USUARIOAMIGOS.USUARIO_ID = USUARIOS.USUARIO_ID";
        $query = mysqli_query($conn, $sql);
        $width = '168px';
        $height = '168px';
        if ($query) {
            if (mysqli_num_rows($query) == 0) {
                echo '<div class="post">';
                echo 'No hay amigos agregados';
                echo '</div>';
            } else {
                while ($row = mysqli_fetch_assoc($query)) {
                    echo '<div class="frame">';
                    echo '<center>';
                    include 'vistas/foto_perfil.php';
                    echo '<br>';
                    echo '<a href="perfil.php?id=' . $row['USUARIO_ID'] . '">' . $row['USUARIO_NOMBRE'] . ' ' . $row['USUARIO_APELLIDO'] . '</a>';
                    echo '</center>';
                    echo '</div>';
                }
            }
        }
        echo '</center>';
        ?>
    </div>
</body>

</html>