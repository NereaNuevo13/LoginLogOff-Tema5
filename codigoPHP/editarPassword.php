<?php
/**
  @author Nerea Nuevo Pascual
  @since 28/01/2020
 */
session_start();
$entradaOK = true;
require '../core/validacionFormularios2.php'; //Importamos la libreria de validacion
include '../config/DBconf.php'; //Importo los datos de conexión

if (!isset($_SESSION['usuarioDAW213AppLoginLogoff'])) { //Si no has pasado por el login, te redirige para allá
    header("Location: login.php");
}

if (isset($_POST["cancelar"])) {
    header('Location: editarPerfil.php');
    exit;
}

if (isset($_POST["editarPass"])) {
    header('Location: editarPassword.php');
    exit;
}

$aErrores = [
    'passVieja' => null,
    'passNueva' => null,
    'passNueva2' => null
];

try {
    $miDB = new PDO($mysqlDB, $usuarioDB, $passDB);
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $mensajeError) { //Cuando se produce una excepcion se corta el programa y salta la excepción con el mensaje de error
    echo "<h3>Mensaje de ERROR</h3>";
    echo "Error: " . $mensajeError->getMessage() . "<br>";
    echo "Código de error: " . $mensajeError->getCode();
}

if (isset($_POST['enviar'])) { //Si se ha pulsado enviar
    //La posición del array de errores recibe el mensaje de error si hubiera
    $aErrores['passVieja'] = validacionFormularios::comprobarAlfaNumerico($_POST['passVieja'], 25, 1, 1);  //maximo, mínimo y opcionalidad
    $aErrores['passNueva'] = validacionFormularios::comprobarAlfaNumerico($_POST['passNueva'], 25, 1, 1);  //maximo, mínimo y opcionalidad
    $aErrores['passNueva2'] = validacionFormularios::comprobarAlfaNumerico($_POST['passNueva2'], 25, 1, 1);  //maximo, mínimo y opcionalidad
    
    if (isset($_POST['passVieja']) && isset($_POST['passNueva']) && isset($_POST['passNueva2'])) {
        $passwordVieja = $_POST['passVieja'];
        $SQL = "SELECT Password FROM Usuario WHERE CodUsuario = '" . $_SESSION['usuarioDAW213AppLoginLogoff'] . "';";
        $resultado = $miDB->query($SQL);
        $passUser = $resultado->fetchObject();
        
        if(hash('sha256', $_SESSION['usuarioDAW213AppLoginLogoff'] . $passwordVieja) !== $passUser->Password){
            $aErrores['passVieja'] = "La contraseña antigua no coincide.";
        }
        
        if ($_POST['passNueva'] !== $_POST['passNueva2']) {
            $aErrores['passNueva2'] = "Las contraseñas no son iguales.";
        }
    }
    
    foreach ($aErrores as $campo => $error) { //Recorre el array en busca de mensajes de error
        if ($error != null) { //Si lo encuentra vacia el campo y cambia la condiccion
            $entradaOK = false; //Cambia la condiccion de la variable
        }
    }
    
    
} else {
    $entradaOK = false; //Cambiamos el valor de la variable porque no se ha pulsado el botón 
}

if ($entradaOK) {

    $sentenciaSQL = "UPDATE Usuario SET Password = SHA2(:pass,256) WHERE CodUsuario = :codigo;";             
    $resultadoSQL = $miDB->prepare($sentenciaSQL);
    $resultadoSQL->execute(array(':codigo' => $_SESSION['usuarioDAW213AppLoginLogoff'], ':pass' => $_SESSION['usuarioDAW213AppLoginLogoff'] . $_POST['passNueva']));
  
    header("Location: programa.php");
} else {
    ?>

    <html lang="es">
        <head>
            <title>Nerea Nuevo Pascual</title>
            <meta charset="UTF-8">
            <link href="../webroot/css/estilos.css" rel="stylesheet" type="text/css" title="Default style">
            <style>
                label{
                    display: inline-block;
                    width: 200px;
                }

                #passVieja, #passNueva, #passNueva2{
                    background-color: #fff;
                }
                
                b{
                    color: red;
                }
                
                #aceptar{
                    background-color: #008acc;
                    font-weight: bold;
                    cursor: pointer;
                }
                
                #cancelar{
                    background-color: #F84C4C;
                    font-weight: bold;
                    cursor: pointer;
                }
            </style>
        </head>
        <body>
            <header>
                <h1>Proyecto Log In / Log Out</h1>
            </header>
            <h3>EDITAR CONTRASEÑA</h3>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="box">
                    <div class="obligatorio">
                        <label for="passVieja">Contraseña actual: </label>
                        <input type="password" id="passVieja" name="passVieja" value="<?php if ($aErrores['passVieja'] == NULL && isset($_POST['passVieja'])) {echo $_POST['passVieja'];} ?>"><br>
                        <?php if ($aErrores['passVieja'] != NULL) { ?>
                            <?php echo "<b>" . $aErrores['passVieja'] . "</b>";?>
                        <?php } ?>                
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="passNueva">Nueva Contraseña: </label>
                        <input type="password" id="passNueva" name="passNueva" value="<?php if ($aErrores['passNueva'] == NULL && isset($_POST['passNueva'])) {echo $_POST['passNueva'];} ?>"><br>
                        <?php if ($aErrores['passNueva'] != NULL) { ?>
                            <?php echo "<b>" . $aErrores['passNueva'] . "</b>";?>
                        <?php } ?>                
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="passNueva2">Confirme Nueva Contraseña: </label> 
                        <input type="password" id="passNueva2" name="passNueva2" value="<?php if ($aErrores['passNueva2'] == NULL && isset($_POST['passNueva2'])) {echo $_POST['passNueva2'];} ?>"><br>
                        <?php if ($aErrores['passNueva2'] != NULL) { ?>
                             <?php echo "<b>" . $aErrores['passNueva2'] . "</b>";?>
                        <?php } ?>                
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <input type="submit" name="enviar" id="aceptar" value="ACEPTAR">
                        <input type="submit" name="cancelar" id="cancelar" value="CANCELAR">
                    </div>
                </div>
            </form>
            <footer>&COPY; Nerea Nuevo Pascual<br>
                <a href="http://daw-usgit.sauces.local/NereaNuevo/LoginLogoffTema5/tree/master" target="_blank">
                    <img src="../webroot/images/gitLab.png" width="30" height="30">
                </a>
                <a href="https://github.com/NereaNuevo13/LoginLogoffTema5" target="_blank">
                    <img src="../webroot/images/gitHub.png" width="30" height="30">
                </a>
            </footer>
        <?php } ?>
    </body>
</html>