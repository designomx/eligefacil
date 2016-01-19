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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

/* REDES SOCIALES */
define(FACEBOOK, 1);
define(TWITTER, 2);
define(WHATSAPP, 3); 

function createMainDataElement($plan, $num, $dbConn){

	if($plan['dato_principal_'.$num] != NULL){

		$query_dato = sprintf("SELECT * FROM tipoDatosServicios WHERE id_tipoDato=%s", GetSQLValueString($plan['id_tipoDato_principal_'.$num], "int"));
		$dato = mysql_query($query_dato, $dbConn) or die(mysql_error());
		$row_dato = mysql_fetch_assoc($dato);
		
		$display = true;
		$label = "";

		if($row_dato['tipo'] == "boolean"){
			if($plan['dato_principal_'.$num] == "1"){
				$label = $row_dato['label'];
			} else {
				$display = false;
			}
		} else {
			if($row_dato['display_label']){
				$label = $plan['dato_principal_'.$num] . " " . $row_dato['label'];
			} else {
				$label = $plan['dato_principal_'.$num];
			}
		}
		
		if($display){
			echo "<div class='dato'>";
			echo " <li>" .	$label . "</li>";
			echo "</div>";
		}

	}//if 
	
}//function createMainDataElement(...


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

mysql_select_db($database, $dbConn);

/* Obtiene todos los datos del plan */
$query_planes = sprintf("SELECT id_plan, nombre, (select nombre from empresas where empresas.id_empresa = planes.id_empresa) as empresa, (select codigo_color from empresas where empresas.id_empresa = planes.id_empresa) as empresa_color, precio, dato_principal_1, id_tipoDato_principal_1, dato_principal_2, id_tipoDato_principal_2, dato_principal_3, id_tipoDato_principal_3, dato_principal_4, id_tipoDato_principal_4, mas_datos FROM planes WHERE id_plan IN (%s) ORDER BY precio ASC", implode(", ", $_GET['planes']));
$planes = mysql_query($query_planes, $dbConn) or die(mysql_error());
//echo $query_planes;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>PLAN</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable = yes" />
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
		<link rel="stylesheet" href="css/comparacion.css" />
		<script type="text/javascript" charset="utf-8" src="JQuery/jquery-1.11.3.min.js"></script>
		<script>
		
			$(document).ready(function(){
				
				// Para que el colorbox tenga el ancho adecuado al número de planes a comparar.
				$('div#comparacion').width($('div.plan').width() * <?php echo count($_GET['planes']); ?>);
				
				// the height of the highest element (after the function runs)
				var heightOfHighestPlan = 0;
				$('div.plan').each(function () {
						if ($(this).outerHeight() > heightOfHighestPlan) {
								heightOfHighestPlan = $(this).outerHeight();
						}
				});
					
				// Para que el contenedor general tenga la altura del plan más alto.
				$('div#comparacion').height(heightOfHighestPlan);				

				// Asignamos al colorbox las dimensiones para que se vea del tamaño justo.
				var x = $('div#comparacion').width();
				var y = $('div#comparacion').height();
				parent.$.colorbox.resize({width:x, height:y});
								
			});
						
		</script>
	</head>
	<body>
    <!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-55f1d450194756b3" async="async"></script>

  	<div id="comparacion">
			<?php
						
			$i = 1;
			
			while($row_planes = mysql_fetch_assoc($planes)){
				
				$query_redesSociales = sprintf("SELECT * FROM planes_redesSociales WHERE id_plan=%s", GetSQLValueString($row_planes['id_plan'], "int"));
				$redesSociales = mysql_query($query_redesSociales, $dbConn) or die(mysql_error());
				$totalRows_redesSociales = mysql_num_rows($redesSociales);
				
		  ?>
        <div class="plan <?php if(($i % 2) == 0){ echo "bg-darker"; } ?>">
          <div class="datos-plan">
          	<?php if(in_array($row_planes['id_plan'], $_GET['sugeridos'])){
							echo "<div class='sugerido label'>SUGERENCIA ELIGE F&Aacute;CIL</div>";
						} ?>
            <div class="empresa" style="background-color: <?php echo $row_planes['empresa_color']; ?>"><?php echo $row_planes['empresa'];?></div>
            <div class="nombre"><?php echo $row_planes['nombre'];?></div>
            <div class='precio-formateado'>$<span><?php echo number_format($row_planes['precio'], 0, '.', ','); ?></span></div>
            <!--div class="precio">$< ?php echo $row_planes['precio'];? ></div-->
          </div>
          <div class="datos-principales">
						<?php
              createMainDataElement($row_planes, 1, $dbConn);
              createMainDataElement($row_planes, 2, $dbConn);
              createMainDataElement($row_planes, 3, $dbConn);
              createMainDataElement($row_planes, 4, $dbConn);
            ?>
						<?php if($totalRows_redesSociales > 0){ ?>
              <div class="redes-sociales dato">
                <li class="label">Incluye:</li>
                <?php
                  while($row_redesSociales = mysql_fetch_assoc($redesSociales)){
                    
                    switch($row_redesSociales['id_redSocial']){
                    
                      case FACEBOOK:
                            echo "<div class='fb'></div>";
                            break;
                      
                      case TWITTER:
                            echo "<div class='tw'></div>";
                            break;
                      
                      case WHATSAPP:
                            echo "<div class='wp'></div>";
                            break;
                    }
                  }
                ?>
              </div>
            <?php }//if($totalRows_redesSociales > 0) ?>
          </div><!-- .datos-principales -->
          <div class="datos-adicionales">
            <h2>OPCIONES Y CARACTER&Iacute;STICAS ADICIONALES</h2>
            <?php echo $row_planes['mas_datos']; ?>
          </div>
        </div><!-- #plan -->
      <?php
			
				$i++;
			
			}//while ?>
      <div class="clearfix"></div>
      
			<?php if(!isset($_GET['header'])){ ?>
      <div class="addthis_sharing_toolbox" data-url="http://eligefacil.com/comparacion-sharing.php?<?php echo htmlentities($_SERVER['QUERY_STRING']); ?>&header=true" data-title="Comparaci&oacute;n de planes">	<!-- Go to www.addthis.com/dashboard to customize your tools 
				<div class="addthis_sharing_toolbox"></div>-->
      </div>
      <?php } ?>
    </div><!-- #comparacion -->
	</body>
</html>