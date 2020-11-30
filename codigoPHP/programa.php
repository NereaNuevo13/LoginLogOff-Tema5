<?php
session_start(); //recupero la sesion creada en login.php

if (!isset($_SESSION['usuarioDAW213AppLoginLogoff'])) { //si la sesion no se ha recuperado, te manda a login.php para logearte
    header('location: login.php');
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
            <h3>¡Bienvenid@ <?php echo $_SESSION['descUsuario213']; ?>!</h3>
            <?php
            if ($_SESSION['ultimaConexion213'] === null) {
                echo "<h3>Esta es la primera vez que te conectas. Pásalo bien ^^</h3>";
            } else {
                ?>
                <h3>Usted se ha conectado <?php echo $_SESSION['numConexiones213'] . " veces"; ?></h3>
                <h3>Su última conecxión fue el día <?php echo date('d/m/Y', $_SESSION['ultimaConexion213']); ?> a las <?php echo date('H:i:s', $_SESSION['ultimaConexion213']); ?></h3>
            <?php } ?>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                <div class="obligatorio">
                    <input type="submit" name="detalle" id="detalle" value="Detalles">
                    <input type="submit" name="editar" id="editar" value="Editar Perfil"><br><br>
                    <div class="cerrarS"><input type="submit" name="cerrar" id="cerrar" value="Cerrar Sesión"></div>
                </div>
            </form>
        </div>
        <footer>&COPY; Nerea Nuevo Pascual<br>
                <a href="http://daw-usgit.sauces.local/NereaNuevo/LoginLogoffTema5/tree/master" target="_blank">
                    <img src="../webroot/images/gitLab.png" width="30" height="30">
                </a>
                <a href="https://github.com/NereaNuevo13/LoginLogoffTema5" target="_blank">
                    <img src="../webroot/images/gitHub.png" width="30" height="30">
                </a>
        </footer>
    </body>
</html>