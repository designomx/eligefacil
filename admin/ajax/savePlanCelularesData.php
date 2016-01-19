<?php

require_once('../../connection/dbConn.php');
require_once('../phpTools/uploadFile.php');

mysql_select_db($database, $dbConn);


// Primero eliminamos los celulares dados de alta en la tabla.

$deleteSQL = sprintf("DELETE FROM planes_celulares WHERE id_plan=%s", GetSQLValueString($_POST['id_plan'], "int"));
$result = mysql_query($deleteSQL, $dbConn) or die(mysql_error());
	

// A continuación insertamos todos los celulares.	
	
for($i=1 ; $i < 5 ; $i++){

	if($_POST['id_celular_' . $i] != NULL){
				
			$sql = sprintf("INSERT INTO planes_celulares(id_plan, id_celular, precio_12m, precio_18m, precio_24m, precio_prepago, orden) VALUES(%s, %s, %s, %s, %s, %s, %s)",																																																																																
							 GetSQLValueString($_POST['id_plan'], "int"),
							 GetSQLValueString($_POST['id_celular_' . $i], "int"),
							 GetSQLValueString($_POST['id_celular_' . $i . '_precio_12m'], "double"),
							 GetSQLValueString($_POST['id_celular_' . $i . '_precio_18m'], "double"),
							 GetSQLValueString($_POST['id_celular_' . $i . '_precio_24m'], "double"),
							 GetSQLValueString($_POST['id_celular_' . $i . '_precio_prepago'], "double"),
							 GetSQLValueString($i, "int"));
			

		$result = mysql_query($sql, $dbConn) or die(mysql_error());
		mysql_free_result($result);
	
	}
}

?>