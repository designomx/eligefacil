<?php

	require_once('../../connection/dbConn.php');
	require_once('../phpTools/utilities.php');
	
	$id = $_GET["id"];
		
	mysql_select_db($database, $dbConn);
	$query = sprintf("SELECT * FROM usuarios WHERE id_usuario=%s", $id);
	$result = mysql_query($query, $dbConn) or die(mysql_error());

	echo json_encode(utf8json(mysql_fetch_assoc($result)));
	
	mysql_free_result($result);

?>