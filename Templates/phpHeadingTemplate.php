<?php
require_once('connection/dbConn.php');

if (!function_exists("GetSQLValueString")) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
	{
		if (PHP_VERSION < 6) {
			$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
		}
	
		$theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
	
		switch ($theType) {
			case "text":
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;    
			case "long":
			case "int":
				$theValue = ($theValue != "") ? intval($theValue) : "NULL";
				break;
			case "double":
				$theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
				break;
			case "date":
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
			case "defined":
				$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
				break;
		}
		return $theValue;
	}
}

$currentPage = $_SERVER["PHP_SELF"];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

////////////////////////////////////////////////////////////////// 
/* DEFINICION DE CONSTANTES */

/* ANUNCIOS */

define(HOME_LEFT, 1);
define(HOME_CENTER, 2);
define(HOME_RIGHT, 3);

define(COMPARADOR_LEFT, 4);
define(COMPARADOR_CENTER, 5);
define(COMPARADOR_RIGHT, 6);

define(QUIENES_SOMOS_LEFT, 7);
define(QUIENES_SOMOS_RIGHT_TOP, 8);
define(QUIENES_SOMOS_RIGHT_BOTTOM, 9);

define(SERVICIOS_OTT_LEFT, 10);
define(SERVICIOS_OTT_CENTER, 11);
define(SERVICIOS_OTT_RIGHT, 12);

//////////////////////////////////////////////////////////////////

function loadAd($id_anuncio, $dbConn){
	
	$query_ad = sprintf("SELECT * FROM anuncios where id_anuncio=%s", GetSQLValueString($id_anuncio, "int"));
	$ad = mysql_query($query_ad, $dbConn) or die(mysql_error());
	$row_ad = mysql_fetch_assoc($ad);
	$totalRows_ad = mysql_num_rows($ad);
	
	if($totalRows_ad > 0){
		if($row_ad['url'] != NULL){
			echo "<a href='" . $row_ad['url'] . "' target='_blank'>";
		}
		
		echo "<img src='uploads/anuncios/" . $row_ad['id_anuncio'] . "/" . $row_ad['imagen'] . "' />";
	
		if($row_ad['url'] != NULL){
			echo "</a>";
		}
	}
}

//////////////////////////////////////////////////////////////////

?>