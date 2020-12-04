<?php
/**
  @author Nerea Nuevo Pascual
  @since 03/12/2020
 */

session_start();

if (!isset($_SESSION['usuarioDAW214LogInLogOutTema5'])) { //Si no has pasado por el login, te redirige para allá
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

require_once '../core/201020validacionFormularios.php'; //Libreria de Validacion de los Formularios
require_once ('../config/confDB.php'); //Configuración de la base de datos
$entradaOK = true;

$aErrores = [
    'descripcion' => null
];

try {
    $miDB = new PDO(HOST, USUARIO, PASS);
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $resultado = $miDB->query("SELECT * FROM T01_Usuario WHERE T01_CodUsuario = '" . $_SESSION['usuarioDAW214LogInLogOutTema5'] . "';");
    $usuario = $resultado->fetchObject();
    $datosUsuario = [
        'codigo' => $usuario->T01_CodUsuario,
        'descripcion' => $usuario->T01_DescUsuario,
        'tipo' => $usuario->T01_Perfil,
        'ultConexion' => $usuario->T01_FechaHoraUltimaConexion,
        'conexiones' => $usuario->T01_NumConexiones
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

    $sentenciaSQL = "UPDATE T01_Usuario SET T01_DescUsuario = :descripcion WHERE T01_CodUsuario = :codigo;";
    $resultadoSQL = $miDB->prepare($sentenciaSQL);
    $resultadoSQL->execute(array(':codigo' => $_SESSION['usuarioDAW214LogInLogOutTema5'], ':descripcion' => $_POST['descripcion']));
    //$_SESSION['descUsuario213'] = $_POST['descripcion'];

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
                    margin: 0 auto;
                    margin-left: 10%;
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
                        <input type="text" id="nombre" name="nombre"value="<?php echo $datosUsuario['codigo'] ?>" disabled><br>   
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="descripcion">Descripción del Usuario: </label>
                        <input type="text" id="descripcion" name="descripcion" value="<?php echo $datosUsuario['descripcion']; ?>"><br>
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="tipo">Tipo de Usuario: </label> 
                        <input type="text" id="tipo" name="tipo" value="<?php echo $datosUsuario['tipo'] ?>" disabled><br>      
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="conexiones">Número de Conexiones: </label> 
                        <input type="text" id="conexiones" name="conexiones" value="<?php echo $datosUsuario['conexiones'] ?>" disabled><br>        
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="ultConexion">Fecha de la Última Conexión: </label> 
                        <input type="text" id="ultConexion" name="ultConexion" value="<?php echo date('d/m/Y - H:i:s', $datosUsuario['ultConexion']) ?>" disabled><br>        
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <input type="submit" name="enviar" id="aceptar" value="ACEPTAR">
                        <input type="submit" name="cancelar" id="cancelar" value="CANCELAR"><br><br>
                        <div class="editarPass"><input type="submit" name="editarPass" id="editar" value="Editar Contraseña"></div>
                    </div>
                </div>
            </form>
            <footer>&COPY; Nerea Nuevo Pascual
                <a href="https://github.com/NereaNuevo13/ProyectoLogInLogOut/tree/developer" target="_blank">
                    <img src="../webroot/images/github.png" width="40" height="40">
                </a>
            </footer>
        <?php } ?>
    </body>
</html>