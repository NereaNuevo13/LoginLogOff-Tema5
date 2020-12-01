<?php
session_start(); //recupero la sesion creada en login.php

if (!isset($_SESSION['usuarioDAW214LogInLogOutTema5'])) {
    header('location: login.php');
}

if (isset($_POST["volver"])) {
    header('Location: programa.php');
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
            table, td, th{
                border-collapse: collapse;
                border: 2px solid black;
                padding: 5px;
            }

            th{
                background-color: #9999CC;
            }

            td{
                background-color: #DDDDDD;
            }

            td:first-child{
                background-color: #CCCCFF;
            }

            input{
                cursor: pointer;
            }
        </style>
    </head>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <div class="obligatorio">
            <input type="submit" name="volver" value="Volver">
        </div>
    </form>

    <br>
    <h3>$_COOKIE con foreach</h3>
    <table border="1">
        <tr>
            <th>Variable</th>
            <th>Valor</th>
        </tr>
        <?php
        foreach ($_COOKIE as $codigoIndice => $valor) { //Con el foreach recorremos el array
            ?>
            <tr>
                <td><?php echo '<b>$_COOKIE[' . "'" . $codigoIndice . "'" . "]</b>"; ?></td>
                <td><?php echo "$valor"; ?></td>
            </tr>
            <?php
        }
        ?>
    </table>

    <h3>$_SESSION con foreach</h3>
    <table border="1">
        <tr>
            <th>Variable</th>
            <th>Valor</th>
        </tr>
        <?php
        foreach ($_SESSION as $codigoIndice => $valor) { //Con el foreach recorremos el array
            ?>
            <tr>
                <td><?php echo '<b>$_SESSION[' . "'" . $codigoIndice . "'" . "]</b>"; ?></td>
                <td><?php echo "$valor"; ?></td>
            </tr>
            <?php
        }
        ?>
    </table>

    <h3>$_SERVER con foreach</h3>
    <table border="1">
        <tr>
            <th>Variable</th>
            <th>Valor</th>
        </tr>
        <?php
        foreach ($_SERVER as $codigoIndice => $valor) { //Con el foreach recorremos el array
            ?>
            <tr>
                <td><?php echo '<b>$_SERVER[' . "'" . $codigoIndice . "'" . "]</b>"; ?></td>
                <td><?php echo "$valor"; ?></td>
            </tr>
            <?php
        }
        ?>
    </table>

    <h3>$_ENV con foreach</h3>
    <table border="1">
        <tr>
            <th>Variable</th>
            <th>Valor</th>
        </tr>
        <?php
        foreach ($_ENV as $codigoIndice => $valor) { //Con el foreach recorremos el array
            ?>
            <tr>
                <td><?php echo '<b>$_ENV[' . "'" . $codigoIndice . "'" . "]</b>"; ?></td>
                <td><?php echo "$valor"; ?></td>
            </tr>
            <?php
        }
        ?>
    </table>

    <h3>$_FILE con foreach</h3>
    <table border="1">
        <tr>
            <th>Variable</th>
            <th>Valor</th>
        </tr>
        <?php
        foreach ($_FILES as $codigoIndice => $valor) { //Con el foreach recorremos el array
            ?>
            <tr>
                <td><?php echo '<b>$_FILES[' . "'" . $codigoIndice . "'" . "]</b>"; ?></td>
                <td><?php echo "$valor"; ?></td>
            </tr>
            <?php
        }
        ?>
    </table>

</body>
</html>