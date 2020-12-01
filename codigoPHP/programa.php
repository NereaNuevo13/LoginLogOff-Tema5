<?php
session_start(); //recupero la sesion creada en login.php

if (!isset($_SESSION['usuarioDAW214LogInLogOutTema5'])) { //si la sesion no se ha recuperado, te manda a login.php para logearte
    header('Location: login.php');
}

if (isset($_POST["detalle"])) {
    header('Location: detalle.php');
    exit;
}

if (isset($_POST["editar"])) {
    header('Location: editarPerfil.php');
    exit;
}

if (isset($_POST["cerrar"])) {
    session_destroy();
    header('location: login.php');
}

require_once '../core/201020validacionFormularios.php';
$errorIdioma = null; //Creamos e inicializamos $errorIdioma a null, en ella almacenaremos (si hay) los errores al validar el campo idioma del formulario
$entradaOK = true; //Creamos e inicializamos $entradaOK a true

if (isset($_REQUEST['aceptar'])) { //Comprobamos que el usuario haya enviado el formulario
    $errorIdioma = validacionFormularios::validarElementoEnLista($_REQUEST['idioma'], ['es', 'en', 'fr']); //Validamos el elemento lista del formulario, de tener error almacenamos el mensaje en la variable $errorIdioma
    if ($errorIdioma != null) {
        $entradaOK = false; // En caso de que haya algún error le asignamos a entradaOK el valor false para que vuelva a rellenar el formulario                             
    }
} else {
    $entradaOK = false; // Si el usuario no ha enviado el formulario asignamos a entradaOK el valor false para que rellene el formulario
}
if ($entradaOK) { // Si el usuario ha rellenado el formulario correctamente rellenamos el array aFormulario con las respuestas introducidas por el usuario
    if ($_REQUEST['idioma'] == 'es') {//Si el idioma seleccionado por el usuario es español
        setcookie("idioma", 'es'); //Creamos o cambiamos la cookie idioma al valor 'es'
    }
    if ($_REQUEST['idioma'] == 'en') {//Si el idioma seleccionado por el usuario es ingles
        setcookie("idioma", 'en'); //Creamos o cambiamos la cookie idioma al valor 'en'
    }
    if ($_REQUEST['idioma'] == 'fr') {//Si el idioma seleccionado por el usuario es francés
        setcookie("idioma", 'fr'); //Creamos o cambiamos la cookie idioma al valor 'fr'
    }
    header('location: programa.php'); //Volvemos a cargar el ejercicio01.php para que se recargue el valor de las cookies
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Nerea Nuevo Pascual</title>
        <meta charset="UTF-8">
        <link href="../webroot/css/estilos.css" rel="stylesheet" type="text/css" title="Default style">
        <style>
            .box{
                width: 30%;
            }

            #editar{
                background-color: #9AE898;
                font-weight: bold;
                cursor: pointer;
            }

            .cerrarS{
                display: flex;
                align-items: center;
                justify-content: flex-end;
            }

            #cerrar{
                background-color: #F84C4C;
                font-weight: bold;
                cursor: pointer;
            }

            #detalle{
                font-weight: bold;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>Proyecto Log In / Log Out</h1>
        </header>
        <div class="box">
            <h2>USUARIO CORRECTO</h2>
            <?php
            if (isset($_COOKIE['idioma'])) {//Comprobamos que existe $_COOKIE['idioma'] y ($_COOKIE['saludo']
                if ($_COOKIE['idioma'] == 'es') {//Si el idioma almacenado en la cookie idioma es español
                    ?>  
                    <h3>¡Bienvenid@ <?php echo $_SESSION['descUsuario214']; ?>!</h3>
                    <?php
                }
                if ($_COOKIE['idioma'] == 'en') {//Si el idioma almacenado en la cookie idioma es ingles
                    ?>   
                    <h3>¡Hello <?php echo $_SESSION['descUsuario214']; ?>!</h3>
                    <?php
                }
                if ($_COOKIE['idioma'] == 'fr') {//Si el idioma almacenado en la cookie idioma es francés
                    ?>   
                    <h3>¡Salut <?php echo $_SESSION['descUsuario214']; ?>!</h3>
                    <?php
                }
            }
            ?>
            <?php
            if ($_SESSION['ultimaConexion214'] === null) {
                echo "<h3>Esta es la primera vez que te conectas. Pásalo bien ^^</h3>";
            } else {
                ?>
                <h3>Usted se ha conectado <?php echo $_SESSION['numConexiones214'] . " veces"; ?></h3>
                <h3>Su última conexión fue el día <?php echo date('d/m/Y', $_SESSION['ultimaConexion214']); ?> a las <?php echo date('H:i:s', $_SESSION['ultimaConexion214']); ?></h3>
            <?php } ?>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                <div class="obligatorio">
                    <select id="idioma" class="select-css" name="idioma">
                        <option value="es" <?php
                        if (isset($_COOKIE['idioma'])) {//si existe la cookie idioma
                            if ($_COOKIE['idioma'] == 'es') {//Si el idioma almacenado es español
                                echo 'selected'; //Será el valor seleccionado en nuestra lista
                            }
                        }
                        ?>>Español</option>
                        <option value="en" <?php
                        if (isset($_COOKIE['idioma'])) {//si existe la cookie idioma
                            if ($_COOKIE['idioma'] == 'en') {//Si el idioma almacenado es ingles
                                echo 'selected'; //Será el valor seleccionado en nuestra lista
                            }
                        }
                        ?>>English</option>
                        <option value="fr" <?php
                        if (isset($_COOKIE['idioma'])) {//si existe la cookie idioma
                            if ($_COOKIE['idioma'] == 'fr') {//Si el idioma almacenado es frances
                                echo 'selected'; //Será el valor seleccionado en nuestra lista
                            }
                        }
                        ?>>Français</option>
                    </select><br><br>
                    <input type="submit" name="aceptar" value="Aceptar Cookies">
                    <input type="submit" name="detalle" id="detalle" value="Detalles">
                    <!--<input type="submit" name="editar" id="editar" value="Editar Perfil"><br><br>-->
                    <div class="cerrarS"><input type="submit" name="cerrar" id="cerrar" value="Cerrar Sesión"></div>
                </div>
            </form>
        </div>
        <footer>&COPY; Nerea Nuevo Pascual
            <a href="https://github.com/NereaNuevo13/ProyectoLogInLogOut/tree/developer" target="_blank">
                <img src="../webroot/images/github.png" width="40" height="40">
            </a>
        </footer>
    </body>
</html>