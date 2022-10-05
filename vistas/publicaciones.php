<?php

echo '<div class="post">';
if($row['POST_PRIVACIDAD'] == 'Y') {
    echo '<p class="publico">';
    echo 'Visible para todos';
}else {
    echo '<p class="publico">';
    echo 'Visible para amigos';
}
echo '<br>';
echo '<span class="fechaPublicacion">' . $row['POST_FECHA'] . '</span>';
echo '</p>';
echo '<div>';
include 'foto_perfil.php';
echo '<a class="linkPerfil" href="perfil.php?id=' . $row['USUARIO_ID'] .'">' . $row['USUARIO_NOMBRE'] . ' ' . $row['USUARIO_APELLIDO'] . '<a>';
echo'</div>';
echo '<br>';
echo '<p class="publicaciones">' . $row['POST_CONTENIDO'] . '</p>';
echo '<center>'; 
$target = glob("utilidades/imagenes/publicaciones/" . $row['POST_ID'] . ".*");
if($target) {
    echo '<img src="' . $target[0] . '" style="max-width:580px">'; 
    echo '<br><br>';
}
echo '</center>';
echo '</div>';
