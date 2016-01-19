<?php

require_once('../../connection/dbConn.php');
require_once('../phpTools/utilities.php');

mysql_select_db($database, $dbConn);

$id_imagen = $_GET["id_imagen"];
$url = $_GET["url"];

$sql = sprintf("UPDATE imagenesCarrusel SET url=%s WHERE id_imagen=%s",
								GetSQLValueString($url, "text"),
								GetSQLValueString($id_imagen, "int"));

$result = mysql_query($sql, $dbConn) or die(mysql_error());

mysql_free_result($result);

?>