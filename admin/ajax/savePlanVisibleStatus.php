<?php

require_once('../../connection/dbConn.php');
require_once('../phpTools/utilities.php');

mysql_select_db($database, $dbConn);

$id_plan = $_GET["id_plan"];
$visible = $_GET["visible"];

$sql = sprintf("UPDATE planes SET visible=%s WHERE id_plan=%s", GetSQLValueString($visible, "int"), GetSQLValueString($id_plan, "int"));
$result = mysql_query($sql, $dbConn) or die(mysql_error());

echo $sql;

mysql_free_result($result);

?>