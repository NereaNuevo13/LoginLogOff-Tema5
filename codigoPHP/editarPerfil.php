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
    header('Location: programa.php');
    exit;
}

if (isset($_POST["editarPass"])) {
    header('Location: editarPassword.php');
    exit;
}

$aErrores = [
    'descripcion' => null
];

try {
    $miDB = new PDO($mysqlDB, $usuarioDB, $passDB);
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $resultado = $miDB->query("SELECT * FROM Usuario WHERE CodUsuario = '" . $_SESSION['usuarioDAW213AppLoginLogoff'] . "';");
    $aObjeto = $resultado->fetchObject();
    $datos = [
        'codigo' => $aObjeto->CodUsuario,
        'descripcion' => $aObjeto->DescUsuario,
        'tipo' => $aObjeto->Perfil,
        'ultConexion' => $aObjeto->FechaHoraUltimaConexion,
        'conexiones' => $aObjeto->NumConexiones
    ];
} catch (PDOException $mensajeError) { //Cuando se produce una excepcion se corta el programa y salta la excepción con el mensaje de error
    echo "<h3>Mensaje de ERROR</h3>";
    echo "Error: " . $mensajeError->getMessage() . "<br>";
    echo "Código de error: " . $mensajeError->getCode();
}

if (isset($_POST['enviar'])) { //Si se ha pulsado enviar
    //La posición del array de errores recibe el mensaje de error si hubiera
    $aErrores['descripcion'] = validacionFormularios::comprobarAlfabetico($_POST['descripcion'], 250, 1, 1);  //maximo, mínimo y opcionalidad
    foreach ($aErrores as $campo => $error) { //Recorre el array en busca de mensajes de error
        if ($error != null) { //Si lo encuentra vacia el campo y cambia la condiccion
            $entradaOK = false; //Cambia la condiccion de la variable
        }
    }
} else {
    $entradaOK = false; //Cambiamos el valor de la variable porque no se ha pulsado el botón 
}

if ($entradaOK) {

    $sentenciaSQL = "UPDATE Usuario SET DescUsuario = :descripcion WHERE CodUsuario = :codigo;";
    $resultadoSQL = $miDB->prepare($sentenciaSQL);
    $resultadoSQL->execute(array(':codigo' => $_SESSION['usuarioDAW213AppLoginLogoff'], ':descripcion' => $_POST['descripcion']));
    $_SESSION['descUsuario213'] = $_POST['descripcion'];

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

                #descripcion{
                    background-color: #fff;
                }

                .editarPass{
                    display: flex;
                    align-items: center;
                    justify-content: flex-end;
                }

                #editar{
                    display: inline-block;
                    margin-left: 65px;
                    font-weight: bold;
                    cursor: pointer;
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
            <h3>EDITAR PERFIL</h3>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="box">
                    <div class="obligatorio">
                        <label for="nombre">Nombre del Usuario: </label>
                        <input type="text" id="nombre" name="nombre"value="<?php echo $datos['codigo'] ?>" disabled><br>   
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="descripcion">Descripción del Usuario: </label>
                        <input type="text" id="descripcion" name="descripcion" value="<?php echo $datos['descripcion']; ?>"><br>
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="tipo">Tipo de Usuario: </label> 
                        <input type="text" id="tipo" name="tipo" value="<?php echo $datos['tipo'] ?>" disabled><br>      
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="conexiones">Número de Conexiones: </label> 
                        <input type="text" id="conexiones" name="conexiones" value="<?php echo $datos['conexiones'] ?>" disabled><br>        
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="ultConexion">Fecha de la Última Conexión: </label> 
                        <input type="text" id="ultConexion" name="ultConexion" value="<?php echo date('d/m/Y - H:i:s', $datos['ultConexion']) ?>" disabled><br>        
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <input type="submit" name="enviar" id="aceptar" value="ACEPTAR">
                        <input type="submit" name="cancelar" id="cancelar" value="CANCELAR">
                        <div class="editarPass"><input type="submit" name="editarPass" id="editar" value="Editar Contraseña"></div>
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