<?php
require 'funciones/conexion.php';
session_start();
if (isset($_SESSION['USUARIO_ID'])) {
    header("location:inicio.php");
}
session_destroy();
session_start();
ob_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Buddy Loyal</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <style>
        .contenedor {
            margin: 40px auto;
            width: 400px;
        }

        .contenido {
            padding: 30px;
            background-color: white;
            box-shadow: 0 0 5px #4267b2;
        }
    </style>
</head>

<body style="background-image: url('imagenes/fondo.png');  background-size: 2000px 1500px;">
    <h1>Bienvenido a Buddy Loyal</h1>
    <div class="contenedor">
        <div class="tab">
            <button class="tablink active" onclick="openTab(event,'inicioSesion')" id="pantalla1">Inicio de sesión</button>
            <button class="tablink" onclick="openTab(event,'registroUsuario')" id="pantalla2">Registro</button>
        </div>
        <div class="contenido">
            <div class="tabcontent" id="inicioSesion">
                <form method="post" onsubmit="return validarInicioSesion()">
                    <label>Correo<span>*</span></label><br>
                    <input type="text" name="loginCorreo" id="loginCorreo">
                    <div class="requerido"></div>
                    <br>
                    <label>Contraseña<span>*</span></label><br>
                    <input type="password" name="loginPass" id="loginPass">
                    <div class="requerido"></div>
                    <br><br>
                    <input type="submit" value="Iniciar sesión" name="login">
                </form>
            </div>
            <div class="tabcontent" id="registroUsuario">
                <form method="post" onsubmit="return validarRegistro()">
                    <!--Nombre-->
                    <label>Nombre<span>*</span></label><br>
                    <input type="text" name="registroNombre" id="registroNombre">
                    <div class="requerido"></div>
                    <br>
                    <!--Apellido-->
                    <label>Apellido<span>*</span></label><br>
                    <input type="text" name="registroApellido" id="registroApellido">
                    <div class="requerido"></div>
                    <br>
                    <!--Nombre de usuario-->
                    <label>Nombre de usuario</label><br>
                    <input type="text" name="registroUsuario" id="registroUsuario">
                    <div class="requerido"></div>
                    <br>
                    <!--Contraseña-->
                    <label>Contraseña<span>*</span></label><br>
                    <input type="password" name="registroPass" id="registroPass">
                    <div class="requerido"></div>
                    <br>
                    <!--Confirmación contraseña-->
                    <label>Confirmación de contraseña<span>*</span></label><br>
                    <input type="password" name="registroPassConf" id="registroPassConf">
                    <div class="requerido"></div>
                    <br>
                    <!--Correo-->
                    <label>Correo<span>*</span></label><br>
                    <input type="text" name="registroCorreo" id="registroCorreo">
                    <div class="requerido"></div>
                    <br>
                    <!--Fecha de nacimiento-->
                    Fecha de nacimiento<span>*</span><br>
                    <select name="seleccionDia">
                        <?php
                        for ($i = 1; $i <= 31; $i++) {
                            echo '<option value="' . $i . '">' . $i . '</option>';
                        }
                        ?>
                    </select>
                    <select name="seleccionMes">
                        <?php
                        echo '<option value="1">Enero</option>';
                        echo '<option value="2">Febrero</option>';
                        echo '<option value="3">Marzo</option>';
                        echo '<option value="4">Abril</option>';
                        echo '<option value="5">Mayo</option>';
                        echo '<option value="6">Junio</option>';
                        echo '<option value="7">Julio</option>';
                        echo '<option value="8">Agosto</option>';
                        echo '<option value="9">Septiembre</option>';
                        echo '<option value="10">Octubre</option>';
                        echo '<option value="11">Noviembre</option>';
                        echo '<option value="12">Diciembre</option>';
                        ?>
                    </select>
                    <select name="seleccionAnio">
                        <?php
                        for ($i = 2017; $i >= 1900; $i--) {
                            if ($i == 1996) {
                                echo '<option value="' . $i . '" seleccionado>' . $i . '</option>';
                            }
                            echo '<option value="' . $i . '">' . $i . '</option>';
                        }
                        ?>
                    </select>
                    <br><br>
                    <!--Sexo-->
                    <input type="radio" name="registroSexo" value="M" id="sexoMasculino" class="registroSexo">
                    <label>Masculino</label>
                    <input type="radio" name="registroSexo" value="F" id="sexoFemenino" class="registroSexo">
                    <label>Femenino</label>
                    <div class="requerido"></div>
                    <br>
                    <!--Ciudad-->
                    <label>Ciudad</label><br>
                    <input type="text" name="registroCiudad" id="registroCiudad">
                    <br>
                    <br>
                    <!--Estado civil-->
                    <input type="radio" name="estadoCivil" value="S" id="soltero">
                    <label>Solter@</label>
                    <input type="radio" name="estadoCivil" value="R" id="relacion">
                    <label>En relación</label>
                    <input type="radio" name="estadoCivil" value="M" id="matrimonio">
                    <label>Casad@</label>
                    <br><br>
                    <!--Biografía-->
                    <label>Biografía</label><br>
                    <textarea rows="12" name="registroBiografia" id="registroBiografia"></textarea>
                    <br><br>
                    <input type="submit" value="Registrarse" name="registro">
                </form>
            </div>
        </div>
    </div>
    <script src="js/funciones.js"></script>
</body>

</html>

<?php
$conn = connect();
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // POST del login
    if (isset($_POST['login'])) { // Lógica del login
        $loginCorreo = $_POST['loginCorreo'];
        $loginPass = md5($_POST['loginPass']);
        $query = mysqli_query($conn, "SELECT * FROM USUARIOS WHERE USUARIO_CORREO = '$loginCorreo' AND USUARIO_CONTRA = '$loginPass'");
        if ($query) {
            if (mysqli_num_rows($query) == 1) {
                $row = mysqli_fetch_assoc($query);
                $_SESSION['USUARIO_ID'] = $row['USUARIO_ID'];
                $_SESSION['USUARIO_NOMBRE'] = $row['USUARIO_NOMBRE'] . " " . $row['USUARIO_APELLIDO'];
                header("location:inicio.php");
            } else {
                ?> <script>
                    document.getElementsByClassName("requerido")[0].innerHTML = "Credenciales inválidas";
                    document.getElementsByClassName("requerido")[1].innerHTML = "Credenciales inválidas";
                </script> <?php
                                        }
                                    } else {
                                        echo mysqli_error($conn);
                                    }
                                }
                                if (isset($_POST['registro'])) { // Lógica del registro
                                    // Variables de los datos
                                    $registroNombre = $_POST['registroNombre'];
                                    $registroApellido = $_POST['registroApellido'];
                                    $registroUsuario = $_POST['registroUsuario'];
                                    $registroPass = md5($_POST['registroPass']);
                                    $registroCorreo = $_POST['registroCorreo'];
                                    $registroCumpleanios = $_POST['seleccionAnio'] . '-' . $_POST['seleccionMes'] . '-' . $_POST['seleccionDia'];
                                    $registroSexo = $_POST['registroSexo'];
                                    $registroCiudad = $_POST['registroCiudad'];
                                    $registroBiografia = $_POST['registroBiografia'];
                                    if (isset($_POST['estadoCivil'])) {
                                        $estadoCivil = $_POST['estadoCivil'];
                                    } else {
                                        $estadoCivil = NULL;
                                    }
                                    // Revisa valores existentes en la base de datos
                                    $query = mysqli_query($conn, "SELECT USUARIO_NICKNAME, USUARIO_CORREO FROM USUARIOS WHERE USUARIO_NICKNAME = '$registroUsuario' OR USUARIO_CORREO = '$registroCorreo'");
                                    if (mysqli_num_rows($query) > 0) {
                                        $row = mysqli_fetch_assoc($query);
                                        if ($registroUsuario == $row['USUARIO_NICKNAME'] && !empty($registroUsuario)) {
                                            ?> <script>
                    document.getElementsByClassName("requerido")[4].innerHTML = "El nickname ya está registrado";
                </script> <?php
                                        }
                                        if ($registroCorreo == $row['USUARIO_CORREO']) {
                                            ?> <script>
                    document.getElementsByClassName("requerido")[7].innerHTML = "El correo ya está registrado";
                </script> <?php
                                        }
                                    }
                                    // Creación de usuario
                                    $sql = "INSERT INTO USUARIOS(USUARIO_NOMBRE, USUARIO_APELLIDO, USUARIO_NICKNAME, USUARIO_CONTRA, USUARIO_CORREO, USUARIO_GENERO, USUARIO_CUMPLE, USUARIO_ESTADO, USUARIO_INFO, USUARIO_CIUDAD)
                VALUES ('$registroNombre', '$registroApellido', '$registroUsuario', '$registroPass', '$registroCorreo', '$registroSexo', '$registroCumpleanios', '$estadoCivil', '$registroBiografia', '$registroCiudad')";
                                    $query = mysqli_query($conn, $sql);
                                    if ($query) {
                                        $query = mysqli_query($conn, "SELECT USUARIO_ID FROM USUARIOS WHERE USUARIO_CORREO = '$registroCorreo'");
                                        $row = mysqli_fetch_assoc($query);
                                        $_SESSION['USUARIO_ID'] = $row['USUARIO_ID'];
                                        header("location:inicio.php");
                                    }
                                }
                            }
                            ?>