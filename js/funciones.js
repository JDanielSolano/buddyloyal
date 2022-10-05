// INDEX JS

if (typeof (Storage) !== "undefined") {
    var current = localStorage.recent;
    if (current) {
        // Esconde el contenido
        var tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        } // Remueve la clase activa en el momento
        var tablink = document.getElementsByClassName("tablink");
        for (i = 0; i < tablink.length; i++) {
            tablink[i].classList.remove("active");
        } // Muestra el contenido apropiado y añade la clase activa
        if (current == "pantalla1")
            document.getElementById("inicioSesion").style.display = "block";
        else
            document.getElementById("registroUsuario").style.display = "block";
        document.getElementById(current).classList.add("active");
    }
}

function openTab(evt, choice) {
    // Esconde el contenido
    var tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    } // Remueve la clase activa
    var tablink = document.getElementsByClassName("tablink");
    for (i = 0; i < tablink.length; i++) {
        tablink[i].classList.remove("active");
    } // Muestra el contenido apropiado y añade la clase activa
    document.getElementById(choice).style.display = "block";
    evt.currentTarget.classList.add("active");
    // Save
    if (typeof (Storage) !== "undefined") {
        localStorage.recent = evt.currentTarget.getAttribute('id');
    }
}

function validarInicioSesion() {
    limpiarCamposRequeridos();
    var requerido = document.getElementsByClassName("requerido");
    var loginCorreo = document.getElementById("loginCorreo").value;
    var loginPass = document.getElementById("loginPass").value;
    var resultado = true;
    if (loginCorreo == "") {
        requerido[0].innerHTML = "Debe de ingresar su correo";
        resultado = false;
    } else if (!validarCorreo(loginCorreo)) {
        requerido[0].innerHTML = "Correo inválido";
        resultado = false;
    }
    if (loginPass == "") {
        requerido[1].innerHTML = "Debe ingresar su contraseña";
        resultado = false;
    }
    return resultado;
}

function validarRegistro() {
    limpiarCamposRequeridos();
    var requerido = document.getElementsByClassName("requerido");
    var registroNombre = document.getElementById("registroNombre").value;
    var registroApellido = document.getElementById("registroApellido").value;
    var registroPass = document.getElementById("registroPass").value;
    var registroPassConf = document.getElementById("registroPassConf").value;
    var registroCorreo = document.getElementById("registroCorreo").value;
    var registroSexo = document.getElementsByClassName("registroSexo");
    var resultado = true;
    if (registroNombre == "") {
        requerido[2].innerHTML = "Ingrese su nombre";
        resultado = false;
    }
    if (registroApellido == "") {
        requerido[3].innerHTML = "Ingrese su apellido";
        resultado = false;
    }
    if (registroPass == "") {
        requerido[5].innerHTML = "Ingrese una contraseña";
        resultado = false;
    }
    if (registroPassConf == "") {
        requerido[6].innerHTML = "Ingrese una contraseña";
        resultado = false;
    }
    if (registroPass != "" && registroPassConf != "" && registroPass != registroPassConf) {
        requerido[5].innerHTML = "Las contraseñas no coinciden";
        requerido[6].innerHTML = "Las contraseñas no coinciden";
        resultado = false;
    }
    if (registroCorreo == "") {
        requerido[7].innerHTML = "Ingrese un correo";
        resultado = false;
    } else if (!validarCorreo(registroCorreo)) {
        requerido[7].innerHTML = "Correo inválido";
        resultado = false;
    }
    if (!registroSexo[0].checked && !registroSexo[1].checked) {
        requerido[8].innerHTML = "Seleccione su sexo";
        resultado = false;
    }
    return resultado;
}

function limpiarCamposRequeridos() {
    var requerido = document.getElementsByClassName("requerido");
    for (i = 0; i < requerido.length; i++) {
        requerido[i].innerHTML = "";
    }
}

function validarCorreo(correo) {
    var formatoCorreo = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\"[^\s@]+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!correo.match(formatoCorreo))
        return false;
    return true;
}

// INICIO JS

// Vista previa
function vistaPrevia(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (event) {
            $('#vistaPrevia').attr('src', event.target.result);
            $('#vistaPrevia').css('display', 'initial');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
// Validación del post
function validarPost() {
    var requerido = document.getElementsByClassName("requerido");
    var publicaciones = document.getElementsByTagName("textarea")[0].value;
    requerido[0].style.display = "none";
    if (publicaciones == "") {
        requerido[0].style.display = "initial";
        return false;
    }
    return true;
}

// PERFIL JS

function mostrarRuta() {
    var ruta = document.getElementById("archivoSeleccionado").value;
    ruta = ruta.replace(/^.*\\/, "");
    document.getElementById("path").innerHTML = ruta;
}

function validarNumero() {
    var numero = document.getElementById("numeroCelular").value;
    var requerido = document.getElementsByClassName("requerido");
    if (numero == "") {
        requerido[0].innerHTML = "Debe ingresar un número";
        return false;
    } else if (isNaN(numero)) {
        requerido[0].innerHTML = "Solo se permiten dígitos"
        return false;
    }
    return true;
}