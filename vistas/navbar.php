<div class="navbar">
    <?php
    $sql1 = "SELECT COUNT(*) AS COUNT FROM AMISTAD 
                 WHERE AMISTAD.USUARIO2_ID = {$_SESSION['USUARIO_ID']} AND AMISTAD.ESTADO_AMISTAD = 0";
    $query1 = mysqli_query($conn, $sql1);
    $row = mysqli_fetch_assoc($query1);
    ?>

    <ul>
        <li><a href="inicio.php">Inicio</a></li>
        <li><a href="solicitudes.php">Solicitudes de amistad (<?php echo $row['COUNT'] ?>)</a></li>
        <li><a href="amigos.php">Amigos</a></li>
        <li><a href="perfil.php">Perfil</a></li>
        <li><a href="logout.php">Cerrar sesión</a></li>
    </ul>
    <div class="busquedaGlobal">
        <form method="get" action="buscar.php" onsubmit="return validarBusqueda()">
            <select name="tipoBusqueda">
                <option value="correos">Correo</option>
                <option value="nombres">Nombre</option>
                <option value="ciudades">Ciudad</option>
                <option value="posts">Posts</option>
            </select><input type="text" placeholder="Búsqueda" name="buscar" id="buscar"><input type="submit" value="Buscar" id="buscarBoton">
        </form>
    </div>
</div>

<script>
    function validarBusqueda() {
        var query = document.getElementById("query");
        var buscarBoton = document.getElementById("buscarBoton");
        if (query.value == " ") {
            query.placeholder = 'Ingrese una búsqueda';
            return false;
        }
        return true;
    }
</script>