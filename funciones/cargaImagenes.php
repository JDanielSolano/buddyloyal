<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if (isset($_POST['perfil']) || isset($_POST['post'])){
        $nombreArchivo = basename($_FILES["subirImagenes"]["nombre"]);
        $tipoArchivo = pathinfo($nombreArchivo, PATHINFO_EXTENSION); // Detecta la extension de la imagen
        if($tipoArchivo != "png" && $tipoArchivo != "jpg" && $tipoArchivo!= "jpeg" && $tipoArchivo != "gif"){
            echo 'Solo los formatos JPG, JPEG, PNG & GIF están permitidos.';
        }
        if(exif_imagetype($_FILES["subirImagenes"]["tmp_name"])){ //Revisa si realmente el archivo es una imagen
            if(isset($_POST['perfil'])){
                $exito = 0;
                $carpetaImagen = "utilidades/imagenes/perfiles/" . $_SESSION['USUARIO_ID'] . '.' . $tipoArchivo;
                if(move_uploaded_file($_FILES["subirImagenes"]["tmp_name"], $carpetaImagen)){
                    $sql5 = "INSERT INTO POSTS (POST_CONTENIDO, POST_PRIVACIDAD, POST_FECHA, POST_AUTOR)
                            VALUES ('" . $row['USUARIO_NOMBRE'] . " " . $row['USUARIO_APELLIDO'] . " ha cambiado su foto de perfil.', 'N', NOW(), {$_SESSION['USUARIO_ID']})";
                    $query5 = mysqli_query($conn, $sql5);
                    $ultimo_id = mysqli_insert_id($conn);
                    $carpetaImagen2 = "utilidades/imagenes/posts/" . $ultimo_id . '.' . $tipoArchivo;
                    copy($carpetaImagen, $carpetaImagen2);
                    if(!$query5)
                        echo mysqli_error($conn);
                    else
                        $exito = 1;

                }
                if($exito = 1)
                    header("location:perfil.php?id=" . $_SESSION['USUARIO_ID']);
            }
            else if(isset($_POST['post'])){
                $carpetaImagen = "utilidades/imagenes/posts/" . $ultimo_id . '.' . $tipoArchivo;
                if(move_uploaded_file($_FILES["subirArchivo"]["tmp_name"], $carpetaImagen)){
                    header("refresh:5; url=home.php");
                }
            }
        }
    }
}
