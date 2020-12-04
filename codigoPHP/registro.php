<?php
/**
  @author Nerea Nuevo Pascual
  @since 21/01/2020
 */
require_once '../core/201020validacionFormularios.php';
require_once ('../config/confDB.php');

$entradaOK = true; //Inicializamos una variable que nos ayudara a controlar si todo esta correcto
session_start();

try {
    
    $miDB = new PDO(HOST, USUARIO, PASS);
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        
} catch (PDOException $mensajeError) { //Cuando se produce una excepcion se corta el programa y salta la excepción con el mensaje de error
    echo "<h3>Mensaje de ERROR</h3>";
    echo "Error: " . $mensajeError->getMessage() . "<br>";
    echo "Código de error: " . $mensajeError->getCode();
}

$aErrores = [
    'nombre' => null,
    'descripcion' => null,
    'pass' => null,
    'pass2' => null
];

if (isset($_POST['enviar'])) { //Si se ha pulsado enviar
    //La posición del array de errores recibe el mensaje de error si hubiera
    $aErrores['nombre'] = validacionFormularios::comprobarAlfabetico($_POST['nombre'], 15, 1, 1);  //maximo, mínimo y opcionalidad
    $aErrores['descripcion'] = validacionFormularios::comprobarAlfabetico($_POST['descripcion'], 255, 1, 1);  //maximo, mínimo y opcionalidad
    $aErrores['pass'] = validacionFormularios::comprobarAlfaNumerico($_POST['pass'], 25, 4, 1); //maximo, mínimo y opcionalidad
    $aErrores['pass2'] = validacionFormularios::comprobarAlfaNumerico($_POST['pass2'], 25, 4, 1); //maximo, mínimo y opcionalidad
    
    if (isset($_POST['nombre']) && isset($_POST['pass']) && isset($_POST['pass2'])) {
        if ($_POST['pass'] === $_POST['pass2']) {
            $codUsuario = $_POST['nombre'];
            $password = $_POST['pass'];
            $consultaSQL1 = "SELECT * FROM T01_Usuario WHERE T01_CodUsuario LIKE '$codUsuario'";
            $resultadoSQL1 = $miDB->query($consultaSQL1);
            if ($resultadoSQL1->rowCount() === 1) {
                $aErrores['nombre'] = "Nombre de usuario ya existente";
            }
        }else{
            $aErrores['pass2'] = "Las contraseñas no coinciden";
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
    
        $consultaSQL2 = "INSERT INTO T01_Usuario(T01_CodUsuario, T01_DescUsuario, T01_Password) VALUES (:codigo, :descripcion, SHA2(:pass,256));";
        $resultadoSQL2 = $miDB->prepare($consultaSQL2);
        $resultadoSQL2->execute(array(':codigo' => $_POST['nombre'], ':descripcion' => $_POST['descripcion'], ':pass' => $_POST['nombre'] . $_POST['pass']));
        
        $fechaSQL = "UPDATE T01_Usuario SET T01_FechaHoraUltimaConexion = " . time() . " WHERE T01_CodUsuario = :codigo;";
        $actualizarFechaSQL = $miDB->prepare($fechaSQL);
        $actualizarFechaSQL->execute(array(':codigo' => $_POST['nombre']));
            
        $conexionesSQL = "UPDATE T01_Usuario SET T01_NumConexiones = T01_NumConexiones + 1 WHERE T01_CodUsuario = :codigo;";
        $actualizarConexionesSQL = $miDB->prepare($conexionesSQL);
        $actualizarConexionesSQL->execute(array(':codigo' => $_POST['nombre']));
        
        $_SESSION['usuarioDAW214LogInLogOutTema5'] = $_POST['nombre'];
        $_SESSION['ultimaConexionAnterior'] = null;
        header("Location: programa.php");
    
} else {
    ?>
    <!DOCTYPE html>
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
                
                #descripcion, #nombre, #pass, #pass2{
                    background-color: #fff;
                }
                
                #aceptar{
                    background-color: #008acc;
                    font-weight: bold;
                    cursor: pointer;
                }
                
                #cancelar{
                    background-color: #F84C4C;
                    font-weight: bold;cursor: pointer;
                    
                }
                
                b{
                    color: red;
                }
            </style>
        </head>
        <body>
            <header>
                <h1>Proyecto Log In / Log Out</h1>
            </header>
            <h3>REGISTRO DE USUARIO</h3>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <fieldset>
                    <div class="obligatorio">
                        <label>Nombre de Usuario: </label>
                        <input type="text" id="nombre" name="nombre" value="<?php if ($aErrores['nombre'] == NULL && isset($_POST['nombre'])) { echo $_POST['nombre'];} ?>"><br>
                        <?php if ($aErrores['nombre'] != NULL) { ?>
                            <div class="error">
                                <?php echo "<b>" . $aErrores['nombre'] . "</b>"; //Mensaje de error que tiene el array aErrores   ?>
                            </div>   
                        <?php } ?>                
                    </div>
                    <br>
                    <div class="obligatorio">
                        <label>Descripción de Usuario: </label>
                        <input type="text" id="descripcion" name="descripcion"value="<?php if ($aErrores['descripcion'] == NULL && isset($_POST['descripcion'])) { echo $_POST['descripcion'];} ?>"><br>
                        <?php if ($aErrores['descripcion'] != NULL) { ?>
                            <div class="error">
                                <?php echo "<b>" . $aErrores['descripcion'] . "</b>"; //Mensaje de error que tiene el array aErrores   ?>
                            </div>   
                        <?php } ?>                
                    </div>
                    <br>
                    <div class="obligatorio">
                        <label>Introduzca Contraseña: </label> 
                        <input type="password" id="pass" name="pass" value="<?php if ($aErrores['pass'] == NULL && isset($_POST['pass'])) { echo $_POST['pass'];} ?>"><br>
                            <?php if ($aErrores['pass'] != NULL) { ?>
                            <div class="error">
                            <?php echo "<b>" . $aErrores['pass'] . "</b>"; //Mensaje de error que tiene el array aErrores   ?>
                            </div>   
                    <?php } ?>                
                    </div>
                    <br>
                    <div class="obligatorio">
                        <label>Confirmar Contraseña: </label> 
                        <input type="password" id="pass2" name="pass2" value="<?php if ($aErrores['pass2'] == NULL && isset($_POST['pass2'])) { echo $_POST['pass2'];} ?>"><br> 
                        <?php if ($aErrores['pass2'] != NULL) { ?>
                            <div class="error">
                            <?php echo "<b>" . $aErrores['pass2'] . "</b>"; //Mensaje de error que tiene el array aErrores   ?>
                            </div>   
                    <?php } ?>    
                    </div>
                    <br>
                    <div class="obligatorio">
                        <input type="submit" name="enviar" id="aceptar" value="ACEPTAR">
                        <a href="login.php"><input type="button" name="cancelar" id="cancelar" value="CANCELAR"></a>
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