<?php
/**
  @author Nerea Nuevo Pascual
  @since 30/11/2020
 */
require '../core/201020validacionFormularios.php'; //Importamos la libreria de validacion
include '../config/confDB.php'; //Importo los datos de conexión

$entradaOK = true; //Inicializamos una variable que nos ayudara a controlar si todo esta correcto

$aErrores = [
    'nombre' => null,
    'pass' => null
];

if (isset($_POST["registrar"])) {
    header('Location: registro.php');
    exit;
}


if (isset($_POST['enviar'])) { //Si se ha pulsado enviar
    //La posición del array de errores recibe el mensaje de error si hubiera
    $aErrores['nombre'] = validacionFormularios::comprobarAlfabetico($_POST['nombre'], 50, 1, 1);  //maximo, mínimo y opcionalidad
    $aErrores['pass'] = validacionFormularios::comprobarAlfaNumerico($_POST['pass'], 20, 1, 1); //maximo, mínimo y opcionalidad
    foreach ($aErrores as $campo => $error) { //Recorre el array en busca de mensajes de error
        if ($error != null) { //Si lo encuentra vacia el campo y cambia la condiccion
            $entradaOK = false; //Cambia la condiccion de la variable
        }
    }
} else {
    $entradaOK = false; //Cambiamos el valor de la variable porque no se ha pulsado el botón
}

if ($entradaOK) {
    try {
        $miDB = new PDO(HOST, USUARIO, PASS);
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $codUsuario = $_POST['nombre'];
        $password = $_POST['pass'];

        $consultaSQL = "SELECT * FROM T01_Usuario WHERE T01_CodUsuario = :usuario AND T01_Password = :passHash";
        $resultadoSQL = $miDB->prepare($consultaSQL);
        $resultadoSQL->bindValue(':usuario', $codUsuario);
        $resultadoSQL->bindValue(':passHash', hash('sha256', $codUsuario . $password));
        $resultadoSQL->execute();

        if ($resultadoSQL->rowCount() == 1) {
            $aObjetos = $resultadoSQL->fetchObject(); //transforma los valores en objetos y me permite seleccionarlos   
            session_start();
            $_SESSION['usuarioDAW214LogInLogOutTema5'] = $aObjetos->T01_CodUsuario;
            $_SESSION['ultimaConexionAnterior'] = $aObjetos->T01_FechaHoraUltimaConexion;
            
            $fechaSQL = "UPDATE T01_Usuario SET T01_FechaHoraUltimaConexion = " . time() . " WHERE T01_CodUsuario = :codigo;";
            $actualizarFechaSQL = $miDB->prepare($fechaSQL);
            $actualizarFechaSQL->bindParam(":codigo", $_SESSION['usuarioDAW214LogInLogOutTema5']);
            $actualizarFechaSQL->execute();
            
            $conexionesSQL = "UPDATE T01_Usuario SET T01_NumConexiones = T01_NumConexiones + 1 WHERE T01_CodUsuario = :codigo;";
            $actualizarConexionesSQL = $miDB->prepare($conexionesSQL);
            $actualizarConexionesSQL->bindParam(":codigo", $_SESSION['usuarioDAW214LogInLogOutTema5']);
            $actualizarConexionesSQL->execute();
            
            header("Location: programa.php");
        } else {
            header('Location: login.php');
        }
    } catch (PDOException $mensajeError) { //Cuando se produce una excepcion se corta el programa y salta la excepción con el mensaje de error
        echo "<h3>Mensaje de ERROR</h3>";
        echo "Error: " . $mensajeError->getMessage() . "<br>";
        echo "Código de error: " . $mensajeError->getCode();
    } finally {
        unset($miDB);
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="es">
        <head>
            <title>Nerea Nuevo Pascual</title>
            <meta charset="UTF-8">
            <link href="../webroot/css/estilos.css" rel="stylesheet" type="text/css" title="Default style">
            <style>
                
                #enviar{
                    font-weight: bold;
                    background-color: #008acc;
                    cursor: pointer;
                }
                
                #registrar{
                    background-color: #9AE898;
                    font-weight: bold;
                    cursor: pointer;
                }
            </style>
        </head>
        <body>
            <header>
                <h1>Log In / Log Out - Tema 5<a hreF="../../../../proyectos.html"><img src="../webroot/images/volver.png" width="70" height="40" align = "right"></a></h1>
            </header>
            <h3>INICIAR SESIÓN</h3>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <fieldset>
                    <div class="obligatorio">
                        <input type="text" id="nombre" name="nombre" placeholder="Nombre de Usuario" value="<?php if ($aErrores['nombre'] == NULL && isset($_POST['nombre'])) {echo $_POST['nombre'];} ?>"><br>
                        <?php if ($aErrores['nombre'] != NULL) { ?>
                              
                        <?php } ?>                
                    </div>
                    <br>
                    <div class="obligatorio">
                        <input type="password" id="pass" name="pass" placeholder="Contraseña" value="<?php if ($aErrores['pass'] == NULL && isset($_POST['pass'])) {echo $_POST['pass'];} ?>"><br>
                        <?php if ($aErrores['pass'] != NULL) { ?>
                             
                        <?php } ?>                
                    </div>
                    <br>
                    <div class="obligatorio">
                        <input type="submit" name="enviar" id="enviar" value="Iniciar sesión">
                        <input type="submit" name="registrar" id="registrar" value="Registrarse">
                    </div>
                </fieldset>
            </form>
            <footer>&COPY; Nerea Nuevo Pascual
                <a href="https://github.com/NereaNuevo13/ProyectoLogInLogOut/tree/developer" target="_blank">
                    <img src="../webroot/images/github.png" width="40" height="40">
                </a>
            </footer>
        <?php } ?>
    </body>
</html>