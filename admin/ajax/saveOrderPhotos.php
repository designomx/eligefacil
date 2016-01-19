<?php

require_once('../../connection/dbConn.php');
require_once('../phpTools/utilities.php');

mysql_select_db($database, $dbConn);

$id = $_GET["id"];
$order = $_GET["order"];

$sql = sprintf("UPDATE imagenesCarrusel SET orden = %s WHERE id_imagen = %s", GetSQLValueString($order, "int"), GetSQLValueString($id, "int"));

$result = mysql_query($sql, $dbConn) or die(mysql_error());

mysql_free_result($result);

?>