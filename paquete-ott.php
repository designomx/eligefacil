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

mysql_select_db($database, $dbConn);

/* Obtiene todos los datos del paquete */
$query_paquete = sprintf("SELECT id_paquete, nombre, (select id_empresa from empresas_ott where empresas_ott.id_empresa = paquetes_ott.id_empresa) as id_empresa, (select logo from empresas_ott where empresas_ott.id_empresa = paquetes_ott.id_empresa) as empresa_logo, precio, dato_principal_1, dato_principal_2, dato_principal_3, dato_principal_4, mas_datos FROM paquetes_ott WHERE id_paquete=%s", GetSQLValueString($_GET['id_paquete'], "int"));
$paquete = mysql_query($query_paquete, $dbConn) or die(mysql_error());
$row_paquete = mysql_fetch_assoc($paquete);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>PLAN</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable = yes" />
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
		<link rel="stylesheet" href="css/plan.css" />
		<script type="text/javascript" charset="utf-8" src="JQuery/jquery-1.11.3.min.js"></script>
		<script>
			$(document).ready(function(){

				var x = $('div#plan').width();
				var y = $('div#plan').height();
		
				parent.$.colorbox.resize({width:x, height:y});

			});
		</script>
	</head>
	<body>
    <!-- Go to www.addthis.com/dashboard to customize your tools -->
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-55f1d450194756b3" async="async"></script>
  
   	<div id="plan">
      <div id="datos-principales">
        <div id="left">
          <div class="empresa ott"><img src="uploads/empresas_ott/<?php echo $row_paquete['id_empresa'] . "/" . $row_paquete['empresa_logo'];?>" /></div>
          <div class="nombre"><?php echo $row_paquete['nombre'];?></div>
          <div class='precio-formateado'>$<span><?php echo number_format($row_paquete['precio'], 0, '.', ','); ?></span></div>
          <!--div class="precio">$< ?php echo $row_paquete['precio'];? ></div-->
        </div>
        <div id="right">
        	<?php if($row_paquete['dato_principal_1'] != NULL){ ?>
          <div class="dato-1 dato"><span><?php echo $row_paquete['dato_principal_1'];?></span></div>
					<?php } ?>
        	<?php if($row_paquete['dato_principal_2'] != NULL){ ?>
          <div class="dato-2 dato"><span><?php echo $row_paquete['dato_principal_2'];?></span></div>
					<?php } ?>
        	<?php if($row_paquete['dato_principal_3'] != NULL){ ?>
          <div class="dato-3 dato"><span><?php echo $row_paquete['dato_principal_3'];?></span></div>
					<?php } ?>
        	<?php if($row_paquete['dato_principal_4'] != NULL){ ?>
          <div class="dato-4 dato"><span><?php echo $row_paquete['dato_principal_4'];?></span></div>
					<?php } ?>
        </div>
        <div class="clearfix"></div>
      </div>  
      <div id="datos-adicionales" class="ott">
				<h2>OPCIONES Y CARACTER&Iacute;STICAS ADICIONALES</h2>
				<?php echo $row_paquete['mas_datos']; ?>
      </div>
      <?php if(!isset($_GET['header'])){ ?>
        <div id="sharing-bar">
          
          <!-- Go to www.addthis.com/dashboard to customize your tools -->
          <div class="addthis_sharing_toolbox" data-url="http://eligefacil.com/paquete-ott-sharing.php?id_paquete=<?php echo $_GET['id_paquete']; ?>&header=true" data-title="Paquete: <?php echo $row_paquete['nombre']; ?>"></div>
          
          <div class="clearfix"></div>
        </div>
      <?php } ?>
    </div>
	</body>
</html>