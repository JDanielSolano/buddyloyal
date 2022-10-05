<?php

$target = glob("utilidades/imagenes/perfiles/" . $row['USUARIO_ID'] . ".*");
if($target) {
    echo '<img src="' . $target[0] . '" width="' . $width . '" height="' . $height .'">'; 
} else {
    if($row['USUARIO_GENERO'] == 'M') {
        echo '<img src="utilidades/imagenes/perfiles/M.jpg" width="' . $width . '" height="' . $height .'">';
    } else if ($row['USUARIO_GENERO'] == 'F') {
        echo '<img src="utilidades/imagenes/perfiles/F.jpg" width="' . $width . '" height="' . $height .'">';
    }
}
