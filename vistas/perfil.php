<?php
echo '<div class="perfil">';
echo '<center>';
$row = mysqli_fetch_assoc($perfilquery);
// Nombre y nombre de usuario
if (!empty($row['USUARIO_NICKNAME']))
    echo $row['USUARIO_NOMBRE'] . ' ' . $row['USUARIO_APELLIDO'] . ' (' . $row['USUARIO_NICKNAME'] . ')';
else
    echo $row['USUARIO_NOMBRE'] . ' ' . $row['USUARIO_APELLIDO'];
echo '<br>';
echo '<br>';
// Vista e info de perfil
$width = '168px';
$height = '168px';
include 'vistas/foto_perfil.php';
echo '<br>';
// Sexo
if ($row['USUARIO_GENERO'] == "M")
    echo 'Masculino';
else if ($row['USUARIO_GENERO'] == "F")
    echo 'Femenino';
echo '<br>';
// Estado civil
if (!empty($row['USUARIO_ESTADO'])) {
    if ($row['USUARIO_ESTADO'] == "S")
        echo 'Solter@';
    else if ($row['USUARIO_ESTADO'] == "R")
        echo 'En relación';
    else if ($row['USUARIO_ESTADO'] == "M")
        echo 'Casad@';
    echo '<br>';
}
// Cumpleaños
echo $row['USUARIO_CUMPLE'];
// Información adicional
if (!empty($row['USUARIO_CIUDAD'])) {
    echo '<br>';
    echo $row['USUARIO_CIUDAD'];
}
if (!empty($row['USUARIO_INFO'])) {
    echo '<br>';
    echo $row['USUARIO_INFO'];
}
// Estado de amistad
if ($flag == 1) {
    echo '<br>';
    if (isset($row['ESTADO_AMISTAD'])) {
        if ($row['ESTADO_AMISTAD'] == 1) {
            echo '<form method="post">';
            echo '<input type="submit" value="Amigos" disabled="disabled" id="special">';
            echo '</form>';
        } else if ($row['ESTADO_AMISTAD'] == 0) {
            echo '<form method="post">';
            echo '<input type="submit" value="Solicitud pendiente" disabled="disabled" id="special">';
            echo '</form>';
        }
    } else {
        echo '<form method="post">';
        echo '<input type="submit" value="Enviar solicitud" name="solicitud">';
        echo '</form>';
    }
}

echo '<center>';
echo '</div>';

$query4 = mysqli_query($conn, "SELECT * FROM USUARIO_CELULAR WHERE USUARIO_ID = {$row['USUARIO_ID']}");
if (!$query4) {
    echo mysqli_error($conn);
}
if (mysqli_num_rows($query4) > 0) {
    echo '<br>';
    echo '<div class="perfil">';
    echo '<center class="cambiarPerfil">';
    echo 'Celulares:';
    echo '<br>';
    while ($row4 = mysqli_fetch_assoc($query4)) {
        echo $row4['USUARIO_CELULAR'];
        echo '<br>';
    }
    echo '</center>';
    echo '</div>';
}
