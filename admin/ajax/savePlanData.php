<?php

require_once('../../connection/dbConn.php');
require_once('../phpTools/uploadFile.php');

mysql_select_db($database, $dbConn);

$transaccion = $_POST['transaccion'];
$_POST['visible'] = ($_POST['visible'] == NULL) ? 0 : $_POST['visible'];

switch($transaccion){

	case 'INSERT':
	
				// Obtiene de la base de datos el último id de los planess
				$query_newId = "SELECT (MAX(id_plan) + 1) as newId FROM planes";
				$newId = mysql_query($query_newId, $dbConn) or die(mysql_error());
				$row_newId = mysql_fetch_assoc($newId);
				
				$_POST['id_plan'] = $row_newId["newId"]; // Creamos la variable POST['id_plan'] y le asignamos el valor del último plan + 1.

				$pdf_celulares = uploadFile("pdf_celulares",  "../../uploads/planes/" . $_POST['id_plan'] . "/pdf_celulares/");
				$pdf_canalesTV = uploadFile("pdf_canalesTV",  "../../uploads/planes/" . $_POST['id_plan'] . "/pdf_canalesTV/");

				$sql = sprintf("INSERT INTO planes(id_plan, id_empresa, nombre, precio, id_tipoDato_principal_1, dato_principal_1, id_tipoDato_principal_2, dato_principal_2, id_tipoDato_principal_3, dato_principal_3, id_tipoDato_principal_4, dato_principal_4, mas_datos, pdf_celulares, pdf_canalesTV, visible) VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",																																																																																
								 GetSQLValueString($_POST['id_plan'], "int"),
								 GetSQLValueString($_POST['id_empresa'], "int"),
								 GetSQLValueString(utf8_decode($_POST['nombre']), "text"),
								 GetSQLValueString($_POST['precio'], "double"),
								 GetSQLValueString($_POST['id_tipoDato_principal_1'], "int"),
								 GetSQLValueString(utf8_decode($_POST['dato_principal_1']), "text"),
								 GetSQLValueString($_POST['id_tipoDato_principal_2'], "int"),
								 GetSQLValueString(utf8_decode($_POST['dato_principal_2']), "text"),
								 GetSQLValueString($_POST['id_tipoDato_principal_3'], "int"),
								 GetSQLValueString(utf8_decode($_POST['dato_principal_3']), "text"),
								 GetSQLValueString($_POST['id_tipoDato_principal_4'], "int"),
								 GetSQLValueString(utf8_decode($_POST['dato_principal_4']), "text"),
								 GetSQLValueString(utf8_decode($_POST['mas_datos']), "text"),
								 GetSQLValueString($pdf_celulares, "text"),
								 GetSQLValueString($pdf_canalesTV, "text"),
								 GetSQLValueString($_POST['visible'], "int"));


				$result = mysql_query($sql, $dbConn) or die(mysql_error());
				mysql_free_result($result);
				
				break;
				
	case 'UPDATE':
				
				// Si el usuario seleccionó un nuevo archivo de celulares, primero elimina el archivo actual del servidor y la BD y luego sube el nuevo archivo.	
				if($_FILES["pdf_celulares"]['tmp_name'] != NULL){
					
					$dirCels = "../../uploads/planes/" . $_POST['id_plan'] . "/pdf_celulares/";
					
					//echo "|" . $_POST['pdf_celulares_current'] . "|";
					
					//Eliminamos el archivo actual del servidor.
					unlink($dirCels . $_POST['pdf_celulares_current']);
					
					$pdf_celulares = uploadFile("pdf_celulares",  $dirCels);
				
				} else {
					$pdf_celulares = $_POST['pdf_celulares_current'];
				}
				
				// Si el usuario seleccionó un nuevo archivo de canales de tv, primero elimina el archivo actual del servidor y la BD y luego sube el nuevo archivo.
				if($_FILES["pdf_canalesTV"]['tmp_name'] != NULL){
					
					$dirCanales = "../../uploads/planes/" . $_POST['id_plan'] . "/pdf_canalesTV/";

					//echo "|" . $_POST['pdf_canalesTV_current'] . "|";
					
					//Eliminamos el archivo actual del servidor.
					unlink($dirCanales . $_POST['pdf_canalesTV_current']);
					
					$pdf_canalesTV = uploadFile("pdf_canalesTV",  $dirCanales);
				
				} else {
					$pdf_canalesTV = $_POST['pdf_canalesTV_current'];
				}
				
				// Actualizamos los datos del plan.
				$sql = sprintf("UPDATE planes SET id_empresa=%s, nombre=%s, precio=%s, id_tipoDato_principal_1=%s, dato_principal_1=%s, id_tipoDato_principal_2=%s, dato_principal_2=%s, id_tipoDato_principal_3=%s, dato_principal_3=%s, id_tipoDato_principal_4=%s, dato_principal_4=%s, mas_datos=%s, pdf_celulares=%s, pdf_canalesTV=%s, visible=%s WHERE id_plan=%s",																																																																																	
								 GetSQLValueString($_POST['id_empresa'], "int"),
								 GetSQLValueString(utf8_decode($_POST['nombre']), "text"),
								 GetSQLValueString($_POST['precio'], "double"),
								 GetSQLValueString($_POST['id_tipoDato_principal_1'], "int"),
								 GetSQLValueString(utf8_decode($_POST['dato_principal_1']), "text"),
								 GetSQLValueString($_POST['id_tipoDato_principal_2'], "int"),
								 GetSQLValueString(utf8_decode($_POST['dato_principal_2']), "text"),
								 GetSQLValueString($_POST['id_tipoDato_principal_3'], "int"),
								 GetSQLValueString(utf8_decode($_POST['dato_principal_3']), "text"),
								 GetSQLValueString($_POST['id_tipoDato_principal_4'], "int"),
								 GetSQLValueString(utf8_decode($_POST['dato_principal_4']), "text"),
								 GetSQLValueString(utf8_decode($_POST['mas_datos']), "text"),
								 GetSQLValueString($pdf_celulares, "text"),
								 GetSQLValueString($pdf_canalesTV, "text"),
								 GetSQLValueString($_POST['visible'], "int"),
								 GetSQLValueString($_POST['id_plan'], "int"));

				$result = mysql_query($sql, $dbConn) or die(mysql_error());
				mysql_free_result($result);
																 				
				break;
				
}// switch

/* SERVICIOS */

if(isset($_POST['unassignedServices'])){

	// Luego eliminamos de la tabla los servicios que fueron desasignados del plan
	foreach($_POST['unassignedServices'] as $id_unassignedService){					
			//echo "proyecto desasignado = " . $id_unassignedService;
			$deleteSQL = sprintf("DELETE FROM planes_tipoServicios WHERE id_plan=%s AND id_tipoServicio=%s", GetSQLValueString($_POST['id_plan'], "int"), GetSQLValueString($id_unassignedService, "int"));
			$result = mysql_query($deleteSQL, $dbConn) or die(mysql_error());
			mysql_free_result($result);
					
	}//foreach

}

// Al final insertamos todos los servicios nuevos, asignados al plan.
foreach($_POST['services'] as $id_tipoServicio){
				
		$query_alreadyAssigned = sprintf("SELECT * FROM planes_tipoServicios WHERE id_plan=%s AND id_tipoServicio=%s", GetSQLValueString($_POST['id_plan'], "int"), GetSQLValueString($id_tipoServicio, "int"));
		$alreadyAssigned = mysql_query($query_alreadyAssigned, $dbConn) or die(mysql_error());
		$totalRows_alreadyAssigned = mysql_num_rows($alreadyAssigned);
		
		// Sólo inserta el servicio en la tabla si no está ya.
		if($totalRows_alreadyAssigned < 1){
							
			$insertSQL = sprintf("INSERT INTO planes_tipoServicios (id_plan, id_tipoServicio) VALUES(%s, %s)",																																																																																
							 GetSQLValueString($_POST['id_plan'], "int"),
							 GetSQLValueString($id_tipoServicio, "int"));
			
			
			//echo $insertSQL;
			
			$result = mysql_query($insertSQL, $dbConn) or die(mysql_error());
			mysql_free_result($result);
			
		}
						
}//foreach


/* COBERTURA */

if(isset($_POST['unassignedStates'])){

	// Luego eliminamos de la tabla los estados que fueron desasignados del plan
	foreach($_POST['unassignedStates'] as $id_unassignedState){					
			//echo "proyecto desasignado = " . $id_unassignedState;
			$deleteSQL = sprintf("DELETE FROM cobertura WHERE id_plan=%s AND id_estado=%s", GetSQLValueString($_POST['id_plan'], "int"), GetSQLValueString($id_unassignedState, "int"));
			$result = mysql_query($deleteSQL, $dbConn) or die(mysql_error());
			mysql_free_result($result1);
					
	}//foreach		

}

// Al final insertamos todos los estados nuevos, asignados al plan.
foreach($_POST['states'] as $id_estado){
				
		$query_alreadyAssigned = sprintf("SELECT * FROM cobertura WHERE id_plan=%s AND id_estado=%s", GetSQLValueString($_POST['id_plan'], "int"), GetSQLValueString($id_estado, "int"));
		$alreadyAssigned = mysql_query($query_alreadyAssigned, $dbConn) or die(mysql_error());
		$totalRows_alreadyAssigned = mysql_num_rows($alreadyAssigned);
		
		// Sólo inserta el estado en la tabla si no está ya.
		if($totalRows_alreadyAssigned < 1){
							
			$insertSQL = sprintf("INSERT INTO cobertura (id_plan, id_estado) VALUES(%s, %s)",																																																																																
							 GetSQLValueString($_POST['id_plan'], "int"),
							 GetSQLValueString($id_estado, "int"));
			
			//echo $insertSQL;
			
			$result = mysql_query($insertSQL, $dbConn) or die(mysql_error());
			mysql_free_result($result);
			
		}
						
}//foreach


/* REDES SOCIALES */

if(isset($_POST['unassignedRedesSociales'])){

	// Luego eliminamos de la tabla las redes sociales que fueron desasignados del plan
	foreach($_POST['unassignedRedesSociales'] as $id_unassignedRedSocial){					
			//echo "proyecto desasignado = " . $id_unassignedState;
			$deleteSQL = sprintf("DELETE FROM planes_redesSociales WHERE id_plan=%s AND id_redSocial=%s", GetSQLValueString($_POST['id_plan'], "int"), GetSQLValueString($id_unassignedRedSocial, "int"));
			$result = mysql_query($deleteSQL, $dbConn) or die(mysql_error());
			mysql_free_result($result1);
					
	}//foreach		

}

// Al final insertamos todas las redes sociales nuevas, asignados al plan.
foreach($_POST['redesSociales'] as $id_redSocial){
				
		$query_alreadyAssigned = sprintf("SELECT * FROM planes_redesSociales WHERE id_plan=%s AND id_redSocial=%s", GetSQLValueString($_POST['id_plan'], "int"), GetSQLValueString($id_redSocial, "int"));
		$alreadyAssigned = mysql_query($query_alreadyAssigned, $dbConn) or die(mysql_error());
		$totalRows_alreadyAssigned = mysql_num_rows($alreadyAssigned);
		
		// Sólo inserta la red social en la tabla si no está ya.
		if($totalRows_alreadyAssigned < 1){
							
			$insertSQL = sprintf("INSERT INTO planes_redesSociales (id_plan, id_redSocial) VALUES(%s, %s)",																																																																																
							 GetSQLValueString($_POST['id_plan'], "int"),
							 GetSQLValueString($id_redSocial, "int"));
			
			$result = mysql_query($insertSQL, $dbConn) or die(mysql_error());
			mysql_free_result($result);
			
		}
						
}//foreach


/* CELULARES MÁS POPULARES */

if(isset($_POST['unassignedCelulares'])){

	// Luego eliminamos de la tabla los celulares que fueron desasignados del plan
	foreach($_POST['unassignedCelulares'] as $id_unassignedCelular){					
			//echo "proyecto desasignado = " . $id_unassignedState;
			$deleteSQL = sprintf("DELETE FROM planes_celularesMasPopulares WHERE id_plan=%s AND id_celular=%s", GetSQLValueString($_POST['id_plan'], "int"), GetSQLValueString($id_unassignedCelular, "int"));
			$result = mysql_query($deleteSQL, $dbConn) or die(mysql_error());
			mysql_free_result($result1);
					
	}//foreach		

}

// Al final insertamos todos los celulares nuevos, asignados al plan.
foreach($_POST['celulares'] as $id_celular){
			
		$query_alreadyAssigned = sprintf("SELECT * FROM planes_celularesMasPopulares WHERE id_plan=%s AND id_celular=%s", GetSQLValueString($_POST['id_plan'], "int"), GetSQLValueString($id_celular, "int"));
		$alreadyAssigned = mysql_query($query_alreadyAssigned, $dbConn) or die(mysql_error());
		$totalRows_alreadyAssigned = mysql_num_rows($alreadyAssigned);
		
		// Sólo inserta el celular en la tabla si no está ya.
		if($totalRows_alreadyAssigned < 1){
							
			$insertSQL = sprintf("INSERT INTO planes_celularesMasPopulares (id_plan, id_celular) VALUES(%s, %s)",																																																																																
							 GetSQLValueString($_POST['id_plan'], "int"),
							 GetSQLValueString($id_celular, "int"));
			
			$result = mysql_query($insertSQL, $dbConn) or die(mysql_error());
			mysql_free_result($result);
			
		}
						
}//foreach



?>