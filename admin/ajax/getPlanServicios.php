<?php

	require_once('../../connection/dbConn.php');
	require_once('../phpTools/utilities.php');
	
	$id = $_GET["id"];
		
	mysql_select_db($database, $dbConn);
	$query = sprintf("SELECT id_tipoServicio FROM planes_tipoServicios WHERE id_plan=%s", $id);
	$result = mysql_query($query, $dbConn) or die(mysql_error());
	
	echo recordSetToJson($result);	
	
	mysql_free_result($result);

?>