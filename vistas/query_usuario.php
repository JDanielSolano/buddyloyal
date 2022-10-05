<?php

$query = mysqli_query($conn, $sql);
if(!$query){
    echo mysqli_error($conn);
}
$width = '168px';
$height = '168px';
if(mysqli_num_rows($query) == 0){
    echo '<div class="queryUsuario">';
    echo 'No se encontraron resultados a la siguiente búsqueda: ' . $buscar;
    echo '<br><br>';
    echo '</div>';
} else {
    while($row = mysqli_fetch_assoc($query)){
        echo '<div class="queryUsuario">';
        include 'vistas/foto_perfil.php';
        echo '<br>';
        echo '<a class="linkPerfil" href="perfil.php?id=' . $row['USUARIO_ID'] .'">' . $row['USUARIO_NOMBRE'] . ' ' . $row['USUARIO_APELLIDO'] . '<a>';
        echo '</div>';
        echo '<br>';
    }
}
