<?php

require_once('../../connection/dbConn.php');
require_once('../phpTools/utilities.php');

mysql_select_db($database, $dbConn);
	
$id = $_GET["id"];
	
$query = sprintf("SELECT * FROM anuncios WHERE id_anuncio = %s", GetSQLValueString($id, "int"));
$result = mysql_query($query, $dbConn) or die(mysql_error());

echo json_encode(mysql_fetch_assoc($result));

/*$row = mysql_fetch_assoc($result);

$titulo = utf8_encode($row['titulo']);
$descripcion = utf8_encode($row['descripcion']);

echo json_encode(array('titulo' => $titulo, 'descripcion' => $descripcion));*/

mysql_free_result($result);

?>