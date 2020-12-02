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
require_once ('../config/confDB.php');

$entradaOK = true;

try { // Bloque de código que puede tener excepciones en el objeto PDO
    $miDB = new PDO(HOST, USUARIO, PASS);
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $consultaSQL = "SELECT T01_NumConexiones, T01_DescUsuario FROM T01_Usuario WHERE T01_CodUsuario=:codigo";
    $resultadoSQL = $miDB->prepare($consultaSQL); // prepara la consulta
    $resultadoSQL->bindParam(":codigo", $_SESSION['usuarioDAW214LogInLogOutTema5']);
    $resultadoSQL->execute();

    $aObjetos = $resultadoSQL->fetchObject();
    $numConexiones = $aObjetos->T01_NumConexiones;
    $descUsuario = $aObjetos->T01_DescUsuario;
} catch (PDOException $mensajeError) {
    echo "<h4>Se ha producido un error. Disculpe las molestias</h4>";
} finally { // codigo que se ejecuta haya o no errores
    unset($miDB); // destruyo la variable 
}

if (isset($_REQUEST['idioma'])) {
    if ($_REQUEST['idioma'] === "es") {
        setcookie('idioma', "es"); //La Cookie tiene un periodo de vida de 7 días
        header("Location: programa.php");
    }

    if ($_REQUEST['idioma'] === "en") {
        setcookie('idioma', "en"); //La Cookie tiene un periodo de vida de 7 días
        header("Location: programa.php");
    }

    if ($_REQUEST['idioma'] === "fr") {
        setcookie('idioma', "fr"); //La Cookie tiene un periodo de vida de 7 días
        header("Location: programa.php");
    }
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
            <nav class="idioma">
                <a href="<?php echo $_SERVER['PHP_SELF'] ?>?idioma=es"><button><img src="../webroot/images/spain.png" width="30" height="20"></button></a>
                <a href="<?php echo $_SERVER['PHP_SELF'] ?>?idioma=en"><button><img src="../webroot/images/usa.png" width="30" height="20"></button></a>
                <a href="<?php echo $_SERVER['PHP_SELF'] ?>?idioma=fr"><button><img src="../webroot/images/francia.png" width="30" height="20"></button></a>
            </nav>
            <h2>USUARIO CORRECTO</h2>
            <?php
            if (isset($_COOKIE['idioma'])) {//Comprobamos que existe $_COOKIE['idioma'] y ($_COOKIE['saludo']
                if ($_COOKIE['idioma'] == 'es') {//Si el idioma almacenado en la cookie idioma es español
                    ?>  
                    <h3>¡Bienvenid@ <?php echo $descUsuario; ?>!</h3>
                    <?php
                }
                if ($_COOKIE['idioma'] == 'en') {//Si el idioma almacenado en la cookie idioma es ingles
                    ?>   
                    <h3>¡Hello <?php echo $descUsuario; ?>!</h3>
                    <?php
                }
                if ($_COOKIE['idioma'] == 'fr') {//Si el idioma almacenado en la cookie idioma es francés
                    ?>   
                    <h3>¡Salut <?php echo $descUsuario; ?>!</h3>
                    <?php
                }
            } else {
                ?> 
                <h3>¡Bienvenid@ <?php echo $descUsuario; ?>!</h3>
                <?php
            }
            ?>

            <?php
            if ($_SESSION['ultimaConexionAnterior'] === null) {
                echo "<h3>Esta es la primera vez que te conectas. Pásalo bien ^^</h3>";
            } else {
                ?>
                <h3>Usted se ha conectado <?php echo $numConexiones . " veces"; ?></h3>
                <h3>Su última conexión fue el día <?php echo date('d/m/Y', $_SESSION['ultimaConexionAnterior']); ?> a las <?php echo date('H:i:s', $_SESSION['ultimaConexionAnterior']); ?></h3>
            <?php } ?>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                <div class="obligatorio">
                    <br>
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