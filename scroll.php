<?php
require 'Templates/phpHeadingTemplate.php';


	session_start();
	/*require_once("config.inc.php");
	if (!isset($_SESSION["cantidadcargadas"])) $_SESSION["cantidadcargadas"]=10;
	$q1="select titulo,texto from blog where activo='Si' order by fecha desc limit ".$_SESSION["cantidadcargadas"].",10";
	mysql_select_db($dbname);
	$r1=mysql_query($q1);
	while ($f1=mysql_fetch_array($r1))
	{
	?>
	<li><strong><?php echo utf8_encode($f1["titulo"]); ?></strong><br /><?php echo strip_tags($f1["texto"]); ?></li>
	<?php
	}
	*/
	mysql_select_db($database, $dbConn);
	define(TEL_MOVIL, 1);
	//$_POST['id_estado'] = 9; //Distrito Federal.
	//$_POST['servicios'] = array(TEL_MOVIL);
	$_SESSION['cantidadcargadas'] = (int)$_POST['cantidadcargadas'];
	
	$_POST['id_estado'] = $_SESSION['id_estado'];
	$_POST['servicios'] = $_SESSION['servicios'];


	$query_planes = sprintf("SELECT id_plan, nombre, (select nombre from empresas where empresas.id_empresa = planes.id_empresa) as empresa, (select codigo_color from empresas where empresas.id_empresa = planes.id_empresa) as empresa_color, precio, dato_principal_1, id_tipoDato_principal_1, dato_principal_2, id_tipoDato_principal_2, dato_principal_3, id_tipoDato_principal_3, dato_principal_4, id_tipoDato_principal_4, mas_datos, visible FROM planes WHERE id_plan in(select id_plan from cobertura where id_estado = %s) AND id_plan in( SELECT id_plan FROM planes_tipoServicios WHERE id_tipoServicio IN (%s) AND id_plan NOT IN(SELECT id_plan FROM planes_tipoServicios WHERE id_tipoServicio NOT IN (%s)) AND visible=1 GROUP BY id_plan HAVING count(*) >= %s )  ORDER BY precio ASC limit ".$_SESSION['cantidadcargadas'].",10", GetSQLValueString($_POST['id_estado'], "int"), implode(", ", $_POST['servicios']), implode(", ", $_POST['servicios']), count($_POST['servicios']));
	$planes = mysql_query($query_planes, $dbConn) or die(mysql_error());
	$totalRows_planes = mysql_num_rows($planes);
	$_SESSION['totalRows_planes']=$totalRows_planes;

	//$query_priceMax=sprintf("SELECT MAX(precio) FROM planes WHERE id_plan in(select id_plan from cobertura where id_estado = %s) AND id_plan in( SELECT id_plan FROM planes_tipoServicios WHERE id_tipoServicio IN (%s) AND id_plan NOT IN(SELECT id_plan FROM planes_tipoServicios WHERE id_tipoServicio NOT IN (%s)) AND visible=1 GROUP BY id_plan HAVING count(*) >= %s )  ORDER BY precio ASC limit ".$_SESSION['cantidadcargadas'].",10", GetSQLValueString($_POST['id_estado'], "int"), implode(", ", $_POST['servicios']), implode(", ", $_POST['servicios']), count($_POST['servicios']));
	//$resultadoQueryPriceMax=mysql_query($query_priceMax, $dbConn) or die(mysql_error());
	//$valor=mysql_fetch_object($resultadoQueryPriceMax);
	//$_SESSION['priceMax']= mysql_fetch_assoc($resultadoQueryPriceMax);
	$_SESSION['priceMax']= 1999;
	while($row_planes = mysql_fetch_assoc($planes)){

		createPlan($row_planes, $i, $dbConn);
		$i++;
	}
	
	//$_SESSION["cantidadcargadas"]+=10;



















	function createPlan($plan, $num, $dbConn, $sugerido){


	$query_redesSociales = sprintf("SELECT * FROM planes_redesSociales WHERE id_plan=%s", GetSQLValueString($plan['id_plan'], "int"));
	$redesSociales = mysql_query($query_redesSociales, $dbConn) or die(mysql_error());
	$totalRows_redesSociales = mysql_num_rows($redesSociales);

	$position = (($num % 2) == 0 ? "right" : "");

	$planLink = "plan.php?id_plan=" . $plan['id_plan'];
	
	if($sugerido){
		$planLink .= "&sugerido=true";
	}

	echo "<div id='plan-" . $plan['id_plan'] ."' class='plan box $sugerido " . $position . "'>";
	echo "	<div class='section-left'>";
	if($sugerido){ echo "<div class='sugerido label'>SUGERENCIA ELIGE F&Aacute;CIL</div>"; }
	echo "	<div class='empresa' style='background-color:" . $plan['empresa_color'] . "'>" . $plan['empresa'] . "</div>";
	echo "	<div class='nombre'>" . $plan['nombre'] . "</div>";
	echo "	<div class='precio-formateado'>$<span>" . number_format($plan['precio'], 0, '.', ',') . "</span></div>";
	echo "	<div class='precio'><span>" . $plan['precio'] . "</span></div>";
	echo "	<div class='ver-mas-container'><a onClick='$(this).colorbox({iframe:true, width:0, height:0, initialWidth:0, initialHeight:0, scrolling:false, opacity: 0.7});' href='" . $planLink . "'><div class='ver-mas'>Ver m&aacute;s</div></a></div>";
	echo "	</div>";
	echo "	<div class='section-right'>";
	
	createMainDataElement($plan, 1, $dbConn);
	createMainDataElement($plan, 2, $dbConn);
	createMainDataElement($plan, 3, $dbConn);
	createMainDataElement($plan, 4, $dbConn);
	
	if($totalRows_redesSociales > 0){
	
		echo "<div class='redes-sociales dato'>";
		echo "	<li class='label tipo_redesSoc' value='1'>Incluye:</li>";
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
		echo "	<div class='clearfix'></div>";
		echo "</div>";
	}//if($totalRows_redesSociales > 0)
	
	echo "	<div class='btn_selectComparing'>";
	echo "		<input type='checkbox' id='check-plan-" . $plan['id_plan'] . "' name='check-plan-" . $plan['id_plan'] . "' value='" . $plan['id_plan'] . "' class='" . $sugerido . "' onchange='updateAvailableSpaces(this);'>";
	echo "		<label for='check-plan-" . $plan['id_plan'] . "'> COMPARAR</label>";
	echo "	</div>";
	echo "	</div><!-- .section-right -->";
	echo "	<div class='clearfix'></div>";
	echo "</div><!-- .plan -->";

}	
	
	
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
			echo "	<li class='tipo_" . $plan['id_tipoDato_principal_'.$num] . "' value='" . $plan['dato_principal_'.$num] . "'>";
			echo 			$label;
			echo "	</li>";
			echo "</div>";
		}

	}//if 
	
}//function createMainDataElement(...


function createComparingBar(){
	
  echo "<div class='comparing-bar'>";
  echo " 	<div class='available-spaces'>";
  echo "   	<div class='space'></div>";
  echo "     <div class='space'></div>";
  echo "     <div class='space'></div>";
  echo "     <div class='space'></div>";
  echo "     <div class='space'></div>";
  echo "     <div class='space'></div>";
  echo "     <div class='clearfix'></div>";
  echo "     <div>SELECCIONA HASTA 6 PLANES A COMPARAR</div>";
  echo "   </div>";
  echo "   <div class='btn_verComparacion'><div class='label'>&iexcl;VER COMPARACI&Oacute;N!</div></div>";
  echo "   <div class='sorting'>";
  echo "   	<span>ORDENAR POR:</span>";
  echo "   	<select class='sort' name='sort'>";
  echo "     	<option value='menor-mayor'>PRECIO MENOR A MAYOR</option>";
  echo "       <option value='mayor-menor'>PRECIO MAYOR A MENOR</option>";
  echo "     </select>";
  echo "   </div>";  
  echo "   <div class='clearfix'></div>";
  echo "</div><!-- #comparing-bar -->";

}

?>