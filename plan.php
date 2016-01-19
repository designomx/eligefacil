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

/* TIPOS DE SERVICIO */
define(TV, 4);

/* TIPOS DE DATO */
define(POSPAGO, 1);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

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
			echo " <span>" .	$label . "</span>";
			echo "</div>";
		}

	}//if 
	
}//function createMainDataElement(...

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

mysql_select_db($database, $dbConn);

/* Obtiene todos los datos del plan */
$query_plan = sprintf("SELECT id_plan, nombre, (select nombre from empresas where empresas.id_empresa = planes.id_empresa) as empresa, (select codigo_color from empresas where empresas.id_empresa = planes.id_empresa) as empresa_color, precio, dato_principal_1, id_tipoDato_principal_1, dato_principal_2, id_tipoDato_principal_2, dato_principal_3, id_tipoDato_principal_3, dato_principal_4, id_tipoDato_principal_4, mas_datos, pdf_celulares, pdf_canalesTV FROM planes WHERE id_plan=%s", GetSQLValueString($_GET['id_plan'], "int"));
$plan = mysql_query($query_plan, $dbConn) or die(mysql_error());
$row_plan = mysql_fetch_assoc($plan);

/* Obtiene si el plan tiene asignado el tipo de servicio TV */
$query_isTV = sprintf("SELECT * FROM planes WHERE id_plan=%s AND id_tipoServicio=%s", GetSQLValueString($_GET['id_plan'], "int"), GetSQLValueString(TV, "int"));
$isTV = mysql_query($query_plan, $dbConn) or die(mysql_error());
$totalRows_isTV = mysql_num_rows($isTV);

$isTVPlan = ($totalRows_isTV > 0)? true : false;

/* Obtiene las redes sociales que incluye el plan */
$query_redesSociales = sprintf("SELECT * FROM planes_redesSociales WHERE id_plan=%s", GetSQLValueString($_GET['id_plan'], "int"));
$redesSociales = mysql_query($query_redesSociales, $dbConn) or die(mysql_error());
$totalRows_redesSociales = mysql_num_rows($redesSociales);

/* Obtiene los celulares más populares del plan */
$query_celsMasPopulares = sprintf("SELECT * FROM planes_celulares WHERE id_plan=%s ORDER BY orden ASC", GetSQLValueString($_GET['id_plan'], "int"));
$celsMasPopulares = mysql_query($query_celsMasPopulares, $dbConn) or die(mysql_error());
$totalRows_celsMasPopulares = mysql_num_rows($celsMasPopulares);


/* Obtiene si el plan es de pospago o prepago en caso de celular */
$esPlanPospago = false;

if(($row_plan['id_tipoDato_principal_1'] == POSPAGO) || ($row_plan['id_tipoDato_principal_2'] == POSPAGO) ||
	 ($row_plan['id_tipoDato_principal_3'] == POSPAGO) || ($row_plan['id_tipoDato_principal_4'] == POSPAGO)){

	$esPlanPospago = true;
}

if(isset($_GET['sugerido'])){
	$sugerido = $_GET['sugerido'];
}

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
		<link rel="stylesheet" href="css/plan.css" />
        
		<script type="text/javascript" charset="utf-8" src="JQuery/jquery-1.11.3.min.js"></script>
		<script>
			$(document).ready(function(){

				var x = $('div#plan').width();
				var y = $('div#plan').height();
	
				//alert("x: " + x);
				//alert("y: " + y);
		
				parent.$.colorbox.resize({width:x, height:y});
				
				/* asignamos el status del checkbox del plan en la página padre */
				$('input[type=checkbox]').prop('checked', window.parent.$("input[name=check-plan-<?php echo $row_plan['id_plan']; ?>]").prop('checked'));
				
				/* Reflejamos el valor del checkbox en el checkbox del plan en la página padre */
				$('input[type=checkbox]').click(function(){
						window.parent.$("input[name=check-plan-<?php echo $row_plan['id_plan']; ?>]").click();
				});
				
				
				//window.parent.$("#test").text($(this).val());

			});
		</script>
	</head>
	<body>
    <!-- Go to www.addthis.com/dashboard to customize your tools -->
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-55f1d450194756b3" async="async"></script>

   	<div id="plan">
      <div id="datos-principales">
        <div id="left">
        	<?php if($sugerido){ echo "<div class='sugerido label'>SUGERENCIA ELIGE F&Aacute;CIL</div>"; } ?>
          <div class="empresa" style="background-color: <?php echo $row_plan['empresa_color']; ?>"><?php echo $row_plan['empresa'];?></div>
          <div class="nombre"><?php echo $row_plan['nombre']; ?></div>
          <div class='precio-formateado'>$<span><?php echo number_format($row_plan['precio'], 0, '.', ','); ?></span></div>
          <!--div class="precio">$< ?php echo $row_plan['precio'];? ></div-->
        </div>
        <div id="right">
					<?php
            createMainDataElement($row_plan, 1, $dbConn);
            createMainDataElement($row_plan, 2, $dbConn);
            createMainDataElement($row_plan, 3, $dbConn);
            createMainDataElement($row_plan, 4, $dbConn);
          ?>
        </div>
        <div class="clearfix"></div>
        <?php if($totalRows_redesSociales > 0){?>
          <div class="redes-sociales">
            <div class="icons">
              <div class="label">Incluye:</div>
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
              <div class="clearfix"></div>
            </div><!-- #icons -->
            <div class="clearfix"></div>
          </div><!-- #redes-sociales -->
        <?php } ?>       
      </div>
			<?php if($isTVPlan){ ?>
        <div id="canalesTV">
        	<?php if($row_plan['pdf_canalesTV'] != NULL){ ?>
          <a href="uploads/planes/<?php echo $row_plan['id_plan'];?>/pdf_canalesTV/<?php echo $row_plan['pdf_canalesTV'];?>" target="_blank"><div class="ver-pdf">Ver lista de canales</div></a>
          <?php } ?>
        </div>
      <?php } ?>
      <?php if($totalRows_celsMasPopulares > 0){ ?>  
        <div id="equipos">
          <div id="sectionName">Equipos</div>
          <?php if($row_plan['pdf_celulares'] != NULL){ ?>
          <a href="uploads/planes/<?php echo $row_plan['id_plan'];?>/pdf_celulares/<?php echo $row_plan['pdf_celulares'];?>" target="_blank"><div class="ver-pdf">Ver todos los equipos</div></a>
          <?php } ?>
          <div class="clearfix"></div>
          <div class="celular titulos">
            <div class="foto"></div>
            <?php if($esPlanPospago){ ?>
            <div class="name">Plazo de renta</div>
            <div class="darkCell">12 meses</div>
            <div>18 meses</div>
            <div class="darkCell">24 meses</div>
            <?php } ?>
          </div>
          <?php while($row_celsMasPopulares = mysql_fetch_assoc($celsMasPopulares)){ 
           
							/* Obtiene los datos del celular */
							$query_celular = sprintf("SELECT * FROM celularesMasPopulares WHERE id_celular=%s", GetSQLValueString($row_celsMasPopulares['id_celular'], "int"));
							$celular = mysql_query($query_celular, $dbConn) or die(mysql_error());
							$row_celular = mysql_fetch_assoc($celular)
							
          ?>
            <div class="celular">
              <div class="foto"><img src="uploads/celulares_mas_populares/<?php echo $row_celular['id_celular'] . "/" . $row_celular['foto']; ?>" /></div>
              <div class="name"><?php echo $row_celular['nombre']; ?></div>
              <?php if($esPlanPospago){ ?>
              <div class="darkCell">$<?php echo number_format($row_celsMasPopulares['precio_12m'], 2, '.', ','); ?></div>
              <div>$<?php echo number_format($row_celsMasPopulares['precio_18m'], 2, '.', ','); ?></div>
              <div class="darkCell">$<?php echo number_format($row_celsMasPopulares['precio_24m'], 2, '.', ','); ?></div>
            	<?php } else { ?>
              <div class="darkCell">$<?php echo number_format($row_celsMasPopulares['precio_prepago'], 2, '.', ','); ?></div>
              <?php } ?>
            </div>
          
					<?php } ?>
          <div class="celular relleno">
            <div class="foto"></div>
            <div class="name">&nbsp;</div>
            <?php if($esPlanPospago){ ?>
            <div class="darkCell">&nbsp;</div>
            <div>&nbsp;</div>
            <div class="darkCell">&nbsp;</div>
            <?php } else { ?>
            <div class="darkCell">&nbsp;</div>
            <?php } ?>
          </div>
          <div class="clearfix"></div>
        </div><!-- #equipos -->
      <?php } //if($totalRows_celsMasPopulares > 0) ?>
      <div id="datos-adicionales">
				<h2>OPCIONES Y CARACTER&Iacute;STICAS ADICIONALES</h2>
				<?php echo $row_plan['mas_datos']; ?>
      </div>
			<?php if(!isset($_GET['header'])){ ?>
      <div id="sharing-bar">
      
        <!-- Go to www.addthis.com/dashboard to customize your tools -->
        <div class="addthis_sharing_toolbox" data-url="http://eligefacil.com/plan-sharing.php?id_plan=<?php echo $_GET['id_plan']; ?>&header=true" data-title="Plan: <?php echo $row_plan['nombre']; ?>"></div>
                
        <div class="btn_selectComparing">
        	<input type="checkbox" id="check-plan-<?php echo $row_plan['id_plan']; ?>" name="check-plan-<?php echo $row_plan['id_plan']; ?>" value="<?php echo $row_plan['id_plan']; ?>">
          <label for="check-plan-<?php echo $row_plan['id_plan']; ?>"> COMPARAR</label>
        </div>
         
        <div class="clearfix"></div>
      
      </div>
		 <?php } ?>
    </div>
	</body>
</html>