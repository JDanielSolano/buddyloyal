<?php
require 'funciones/conexion.php';
session_start();
ob_start();

if (!isset($_SESSION['USUARIO_ID'])) {
    header("location:index.php");
}
// Abre conexión
$conn = connect();
?>

<?php
if (isset($_GET['id']) && $_GET['id'] != $_SESSION['USUARIO_ID']) {
    $id_actual = $_GET['id'];
    $flag = 1;
} else {
    $id_actual = $_SESSION['USUARIO_ID'];
    $flag = 0;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Buddy Loyal</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <style>
        .post {
            margin-right: 50px;
            float: right;
            margin-bottom: 18px;
        }

        .perfil {
            margin-left: 50px;
            background-color: white;
            box-shadow: 0 0 5px #4267b2;
            width: 220px;
            padding: 20px;
        }

        input[type="file"] {
            display: none;
        }

        label.upload {
            cursor: pointer;
            color: white;
            background-color: #4267b2;
            padding: 8px 12px;
            display: inline-block;
            max-width: 80px;
            overflow: auto;
        }

        label.upload:hover {
            background-color: #23385f;
        }

        .cambiarPerfil {
            color: #23385f;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>

<body>
    <div class="contenedor">
        <?php include 'vistas/navbar.php'; ?>
        <br>
        <?php
        if ($flag == 0) { // Mi propio perfil      
            $postsql = "SELECT POSTS.POST_CONTENIDO, POSTS.POST_FECHA, USUARIOS.USUARIO_NOMBRE, USUARIOS.USUARIO_APELLIDO,
                                POSTS.POST_PRIVACIDAD, USUARIOS.USUARIO_ID, USUARIOS.USUARIO_GENERO, USUARIOS.USUARIO_NICKNAME,
                                USUARIOS.USUARIO_CUMPLE, USUARIOS.USUARIO_CIUDAD, USUARIOS.USUARIO_ESTADO, USUARIOS.USUARIO_INFO, 
                                POSTS.POST_ID
                        FROM POSTS
                        JOIN USUARIOS
                        ON USUARIOS.USUARIO_ID = POSTS.POST_AUTOR
                        WHERE POSTS.POST_AUTOR = $id_actual
                        ORDER BY POSTS.POST_FECHA DESC";
            $perfilsql = "SELECT USUARIOS.USUARIO_ID, USUARIOS.USUARIO_GENERO, USUARIOS.USUARIO_CIUDAD, USUARIOS.USUARIO_ESTADO, USUARIOS.USUARIO_CUMPLE,
                                 USUARIOS.USUARIO_NOMBRE, USUARIOS.USUARIO_APELLIDO, USUARIOS.USUARIO_INFO, USUARIOS.USUARIO_NICKNAME
                          FROM USUARIOS
                          WHERE USUARIOS.USUARIO_ID = $id_actual";
            $perfilquery = mysqli_query($conn, $perfilsql);
        } else { // Perfiles ajenos
            $perfilsql = "SELECT USUARIOS.USUARIO_ID, USUARIOS.USUARIO_GENERO, USUARIOS.USUARIO_CIUDAD, USUARIOS.USUARIO_ESTADO, USUARIOS.USUARIO_CUMPLE,
                                    USUARIOS.USUARIO_NOMBRE, USUARIOS.USUARIO_APELLIDO, USUARIOAMIGOS.ESTADO_AMISTAD, USUARIOS.USUARIO_INFO, USUARIOS.USUARIO_NICKNAME
                            FROM USUARIOS
                            LEFT JOIN (
                                SELECT AMISTAD.USUARIO1_ID AS USUARIO_ID, AMISTAD.ESTADO_AMISTAD
                                FROM AMISTAD
                                WHERE AMISTAD.USUARIO1_ID = $id_actual AND AMISTAD.USUARIO2_ID = {$_SESSION['USUARIO_ID']}
                                UNION
                                SELECT AMISTAD.USUARIO2_ID AS USUARIO_ID, AMISTAD.ESTADO_AMISTAD
                                FROM AMISTAD
                                WHERE AMISTAD.USUARIO1_ID = {$_SESSION['USUARIO_ID']} AND AMISTAD.USUARIO2_ID = $id_actual
                            ) USUARIOAMIGOS
                            ON USUARIOAMIGOS.USUARIO_ID = USUARIOS.USUARIO_ID
                            WHERE USUARIOS.USUARIO_ID = $id_actual";
            $perfilquery = mysqli_query($conn, $perfilsql);
            $row = mysqli_fetch_assoc($perfilquery);
            mysqli_data_seek($perfilquery, 0);

            if (isset($row['ESTADO_AMISTAD'])) { // Amigo o solicitud
                if ($row['ESTADO_AMISTAD'] == 1) { // Amigo nada mas
                    $postsql = "SELECT POSTS.POST_CONTENIDO, POSTS.POST_FECHA, USUARIOS.USUARIO_NOMBRE, USUARIOS.USUARIO_APELLIDO,
                                        POSTS.POST_PRIVACIDAD, USUARIOS.USUARIO_ID, USUARIOS.USUARIO_GENERO, USUARIOS.USUARIO_NICKNAME,
                                        USUARIOS.USUARIO_CUMPLE, USUARIOS.USUARIO_CIUDAD, USUARIOS.USUARIO_ESTADO, USUARIOS.USUARIO_INFO, 
                                        POSTS.POST_ID
                                FROM POSTS
                                JOIN USUARIOS
                                ON USUARIOS.USUARIO_ID = POSTS.POST_AUTOR
                                WHERE POSTS.POST_AUTOR = $id_actual
                                ORDER BY POSTS.POST_FECHA DESC";
                } else if ($row['ESTADO_AMISTAD'] == 0) { // Solicitud de amistad pendiente
                    $postsql = "SELECT POSTS.POST_CONTENIDO, POSTS.POST_FECHA, USUARIOS.USUARIO_NOMBRE, USUARIOS.USUARIO_APELLIDO,
                                        POSTS.POST_PRIVACIDAD, USUARIOS.USUARIO_ID, USUARIOS.USUARIO_GENERO, USUARIOS.USUARIO_NICKNAME,
                                        USUARIOS.USUARIO_CUMPLE, USUARIOS.USUARIO_CIUDAD, USUARIOS.USUARIO_ESTADO, USUARIOS.USUARIO_INFO, 
                                        POSTS.POST_ID
                                FROM POSTS
                                JOIN USUARIOS
                                ON USUARIOS.USUARIO_ID = POSTS.POST_AUTOR
                                WHERE POSTS.POST_AUTOR = $id_actual AND POSTS.POST_PRIVACIDAD = 'Y'
                                ORDER BY POSTS.POST_FECHA DESC";
                }
            } else { // Cuando no es amigo
                $postsql = "SELECT POSTS.POST_CONTENIDO, POSTS.POST_FECHA, USUARIOS.USUARIO_NOMBRE, USUARIOS.USUARIO_APELLIDO,
                                    POSTS.POST_PRIVACIDAD, USUARIOS.USUARIO_ID, USUARIOS.USUARIO_GENERO, USUARIOS.USUARIO_NICKNAME,
                                    USUARIOS.USUARIO_CUMPLE, USUARIOS.USUARIO_CIUDAD, USUARIOS.USUARIO_ESTADO, USUARIOS.USUARIO_INFO, 
                                    POSTS.POST_ID
                            FROM POSTS
                            JOIN USUARIOS
                            ON USUARIOS.USUARIO_ID = POSTS.POST_AUTOR
                            WHERE POSTS.POST_AUTOR = $id_actual AND POSTS.POST_PRIVACIDAD = 'Y'
                            ORDER BY POSTS.POST_FECHA DESC";
            }
        }
        $postquery = mysqli_query($conn, $postsql);
        if ($postquery) {
            // Posts
            $width = '40px';
            $height = '40px';
            if (mysqli_num_rows($postquery) == 0) { // Si no hay posts
                if ($flag == 0) { // Mensaje si es mi propio post
                    echo '<div class="post">';
                    echo 'Usted no ha realizado publicaciones aún';
                    echo '</div>';
                } else { // Si es un perfil ajeno
                    echo '<div class="post">';
                    echo 'No hay posts públicos por mostrar';
                    echo '</div>';
                }
                include 'vistas/perfil.php';
            } else {
                while ($row = mysqli_fetch_assoc($postquery)) {
                    include 'vistas/publicaciones.php';
                }
                // Info de perfil
                include 'vistas/perfil.php';
                ?>
                <br>
                <?php if ($flag == 0) { ?>
                    <div class="perfil">
                        <center class="cambiarPerfil">Cambiar foto de perfil</center>
                        <br>
                        <form action="" method="post" enctype="multipart/form-data">
                            <center>
                                <label class="upload" onchange="mostrarRuta()">
                                    <span id="path" style="color: white;">Buscar</span>
                                    <input type="file" name="subirArchivo" id="archivoSeleccionado">
                                </label>
                            </center>
                            <br>
                            <input type="submit" value="Subir selección" name="profile">
                        </form>
                    </div>
                    <br>
                    <div class="perfil">
                        <center class="cambiarPerfil">Números registrados</center>
                        <br>
                        <form method="post" onsubmit="return validarNumero()">
                            <center>
                                <input type="text" name="numero" id="numeroCelular">
                                <div class="requerido"></div>
                                <br>
                                <input type="submit" value="Agregar" name="celular">
                            </center>
                        </form>
                    </div>
                    <br>
                <?php } ?>
        <?php
            }
        }
        ?>
    </div>
    <script src="js/funciones.js"></script>
</body>

</html>
<?php include 'funciones/cargaImagenes.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['solicitud'])) { // Enviar solicitud de amistad
        $sql3 = "INSERT INTO AMISTAD(USUARIO1_ID, USUARIO2_ID, ESTADO_AMISTAD)
                 VALUES ({$_SESSION['USUARIO_ID']}, $id_actual, 0)";
        $query3 = mysqli_query($conn, $sql3);
        if (!$query3) {
            echo mysqli_error($conn);
        }
    } else if (isset($_POST['remover'])) { // Remueve la solicitud
        $sql3 = "DELETE FROM AMISTAD
                 WHERE ((AMISTAD.USUARIO1_ID = $id_actual AND AMISTAD.USUARIO2_ID = {$_SESSION['USUARIO_ID']})
                 OR (AMISTAD.USUARIO1_ID = {$_SESSION['user_id']} AND AMISTAD.USUARIO2_ID = $id_actual))
                 AND AMISTAD.ESTADO_AMISTAD = 1";
        $query3 = mysqli_query($conn, $sql3);
        if (!$query3) {
            echo mysqli_error($conn);
        }
    } else if (isset($_POST['celular'])) { // Agrega el numero de celular al perfil
        $sql3 = "INSERT INTO USUARIO_CELULAR(USUARIO_ID, USUARIO_CELULAR) VALUES ({$_SESSION['USUARIO_ID']},{$_POST['numero']})";
        $query3 = mysqli_query($conn, $sql3);
        if (!$query3) {
            echo mysqli_error($conn);
        }
    }
    sleep(4);
}
?>