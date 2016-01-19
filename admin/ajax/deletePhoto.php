<?php

require_once('../../connection/dbConn.php');
require_once('../phpTools/utilities.php');

mysql_select_db($database, $dbConn);

$id_imagen = $_GET["id_imagen"];

/* Obtiene de la Base el filename de la foto a eliminar */
$query_foto = sprintf("SELECT filename FROM imagenesCarrusel WHERE id_imagen=%s", $id_imagen);
$foto = mysql_query($query_foto, $dbConn) or die(mysql_error());
$row_foto = mysql_fetch_assoc($foto);
$filename = $row_foto['filename']; 

// Eliminamos primero el archivo del Servidor.
unlink("../../uploads/carrusel/$filename");

// Luego lo eliminamos de la Base de Datos.
$sql = sprintf("DELETE FROM imagenesCarrusel WHERE id_imagen = %s", $id_imagen);

//mysql_select_db($database_futurautos, $futurautos);
$result = mysql_query($sql, $dbConn) or die(mysql_error());

mysql_free_result($result);

?>