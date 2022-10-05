<?php
require 'funciones/conexion.php';
session_start();

// Revisa si el usuario está logueado o no
if (!isset($_SESSION['USUARIO_ID'])) {
    header("location:index.php");
}
$temp = $_SESSION['USUARIO_ID'];
session_destroy();
session_start();
$_SESSION['USUARIO_ID'] = $temp;
ob_start();

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
        <br>
        <div class="crearPost">
            <form method="post" action="" onsubmit="return validarPost()" enctype="multipart/form-data">
                <h2>Publicar</h2>
                <hr>
                <span style="float:right; color:black">
                    <input type="checkbox" id="publico" name="publico">
                    <label for="publico">Visible para todos</label>
                </span>
                <br>
                <span class="requerido" style="display:none;">Tiene que escribir algo antes de publicarlo</span><br>
                <textarea rows="6" name="publicaciones"></textarea>
                <center><img src="" id="vistaPrevia" style="max-width:580px; display:none;"></center>
                <br>
                <div class="crearPostBotones">
                    <label>
                        <img src="imagenes/fotoIcono.jpg">
                        <input type="file" name="subirFoto" id="foto">
                    </label>
                    <input type="submit" value="Publicar" name="publicar">
                </div>
            </form>
        </div>
        <br>
        <?php
        // Muestra las publicaciones de los amigos agregados
        $sql = "SELECT POSTS.POST_CONTENIDO, POSTS.POST_FECHA, POSTS.POST_PRIVACIDAD, USUARIOS.USUARIO_NOMBRE,
                        USUARIOS.USUARIO_APELLIDO, USUARIOS.USUARIO_ID, USUARIOS.USUARIO_GENERO, POSTS.POST_ID
                FROM POSTS
                JOIN USUARIOS
                ON POSTS.POST_AUTOR = USUARIOS.USUARIO_ID
                WHERE POSTS.POST_PRIVACIDAD = 'Y' OR USUARIOS.USUARIO_ID = {$_SESSION['USUARIO_ID']}
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
                WHERE POSTS.POST_PRIVACIDAD = 'N'
                ORDER BY POST_FECHA DESC";
        $query = mysqli_query($conn, $sql);
        if (!$query) {
            echo mysqli_error($conn);
        }
        if (mysqli_num_rows($query) == 0) {
            echo '<div class="post">';
            echo 'Nadie ha publicado nada aún.';
            echo '</div>';
        } else {
            $width = '40px'; // Dimensiones de la foto de perfil
            $height = '40px';
            while ($row = mysqli_fetch_assoc($query)) {
                include 'vistas/publicaciones.php';
                echo '<br>';
            }
        }
        ?>
        <br><br><br>
    </div>
    <script src="js/jquery.js"></script>
    <script>
        // Muestra la previa cuando se escoge una imagen
        $(document).ready(function() {
            $('#foto').change(function() {
                vistaPrevia(this);
            });
        });
    </script>
    <script src="js/funciones.js"></script>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Publicar post
    // Asignación de variables
    $publicaciones = $_POST['publicaciones'];
    if (isset($_POST['publico'])) {
        $publico = "Y";
    } else {
        $publico = "N";
    }
    $publicador = $_SESSION['USUARIO_ID'];
    // Insertar posts en la base de datos
    $sql = "INSERT INTO POSTS (POST_CONTENIDO, POST_PRIVACIDAD, POST_FECHA, POST_AUTOR)
            VALUES ('$publicaciones', '$publico', NOW(), $publicador)";
    $query = mysqli_query($conn, $sql);
    // Si todo sale bien, pasa hasta acá
    if ($query) {
        // Sube la imagen elegida
        if (!empty($_FILES['subirImagen']['nombre'])) {
            echo 'FUUUQ';
            // Devuelve post ID
            $ultimo_id = mysqli_insert_id($conn);
            include 'funciones/cargarImagenes.php';
        }
        header("location: inicio.php");
    }
}
?>