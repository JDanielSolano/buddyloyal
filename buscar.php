<?php
//
require 'funciones/conexion.php';
session_start();
// Revisa si el usuario está logueado o no
if (!isset($_SESSION['USUARIO_ID'])) {
    header("location:index.php");
}
// Establece conexión con la base de datos
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
        <h1>Resultados de búsqueda</h1>
        <?php
        $tipoBusqueda = $_GET['tipoBusqueda'];
        $buscar = $_GET['buscar'];
        if ($tipoBusqueda == 'correos') {
            $sql = "SELECT * FROM USUARIOS WHERE USUARIOS.USUARIO_CORREO = '$buscar'";
            include 'vistas/query_usuario.php';
        } else if ($tipoBusqueda == 'nombres') {
            $nombre = explode(' ', $buscar, 2); // Convierte el string a array
            if (empty($nombre[1])) {
                $sql = "SELECT * FROM USUARIOS WHERE USUARIOS.USUARIO_NOMBRE = '$nombre[0]' OR USUARIOS.USUARIO_APELLIDO = '$nombre[0]'";
            } else {
                $sql = "SELECT * FROM USUARIOS WHERE USUARIOS.USUARIO_NOMBRE = '$nombre[0]' AND USUARIOS.USUARIO_APELLIDO = '$nombre[1]'";
            }
            include 'vistas/query_usuario.php';
        } else if ($tipoBusqueda == 'ciudades') {
            $sql = "SELECT * FROM USUARIOS WHERE USUARIOS.USUARIO_CIUDAD = '$buscar'";
            include 'vistas/query_usuario.php';
        } else if ($tipoBusqueda == 'posts') {
            $sql = "SELECT POSTS.POST_CONTENIDO, POSTS.POST_FECHA, POSTS.POST_PRIVACIDAD, USUARIOS.USUARIO_NOMBRE,
                                USUARIOS.USUARIO_APELLIDO, USUARIOS.USUARIO_ID, USUARIOS.USUARIO_GENERO, POSTS.POST_ID
                        FROM POSTS
                        JOIN USUARIOS
                        ON POSTS.POST_AUTOR = USUARIOS.USUARIO_ID
                        WHERE (POSTS.POST_PRIVACIDAD = 'Y' OR USUARIOS.USUARIO_ID = {$_SESSION['USUARIO_ID']}) AND POSTS.POST_CONTENIDO LIKE '%$buscar%'
                        UNION
                        SELECT POSTS.POST_CONTENIDO, POSTS.POST_FECHA, POSTS.POST_PRIVACIDAD, USUARIOS.USUARIO_NOMBRE,
                                USUARIOS.USUARIO_APELLIDO, USUARIOS.USUARIO_ID, USUARIOS.USUARIO_GENERO, POSTS.POST_ID
                        FROM POSTS
                        JOIN USUARIOS
                        ON POSTS.POST_AUTOR = USUARIOS.USUARIO_ID
                        JOIN (
                            SELECT AMISTAD.USUARIO1_ID AS USUARIO_ID
                            FROM AMISTAD
                            WHERE AMISTAD.USUARIO2_ID = {$_SESSION['USUARIO_ID']} AND AMISTAD.ESTADO_AMISTAD = 1
                            UNION
                            SELECT AMISTAD.USUARIO2_ID AS USUARIO_ID
                            FROM AMISTAD
                            WHERE AMISTAD.USUARIO1_ID = {$_SESSION['USUARIO_ID']} AND AMISTAD.ESTADO_AMISTAD = 1
                        ) USUARIOAMIGOS
                        ON USUARIOAMIGOS.USUARIO_ID = POSTS.POST_AUTOR
                        WHERE POSTS.POST_PRIVACIDAD = 'N' AND POSTS.POST_CONTENIDO LIKE '%$buscar%'
                        ORDER BY POST_FECHA DESC";
            $query = mysqli_query($conn, $sql);
            $width = '40px'; // Dimensiones de foto de perfil
            $height = '40px';
            if (!$query) {
                echo mysqli_error($conn);
            }
            if (mysqli_num_rows($query) == 0) {
                echo '<div class="post">';
                echo 'No se encontraron resultados para esa búsqueda.';
                echo '</div>';
                echo '<br>';
            }
            while ($row = mysqli_fetch_assoc($query)) {
                include 'vistas/publicaciones.php';
                echo '<br>';
            }
        }
        ?>
    </div>
</body>

</html>