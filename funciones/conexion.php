<?php
// Establece la conexión
function connect() {
    static $conn;
    if ($conn === NULL){ 
        $conn = mysqli_connect('localhost','root','','proyecto');
    }
    return $conn;
}

?>