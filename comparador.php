<?php 
session_start();
require 'Templates/phpHeadingTemplate.php';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
/* DEFINICION DE CONSTANTES */

/* TIPOS DE SERVICIO */
define(TEL_MOVIL, 1);
/*define(TEL_FIJO, 2);
define(INTERNET, 3); 
define(TV, 4);*/

/* REDES SOCIALES */
define(FACEBOOK, 1);
define(TWITTER, 2);
define(WHATSAPP, 3); 

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
/* VALORES DEFAULT*/

if(!isset($_POST['id_estado'])){

	$_POST['id_estado'] = 9; //Distrito Federal.
	$_POST['servicios'] = array(TEL_MOVIL);
	
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 


//unset($_SESSION["cantidadcargadas"]); /* SI ACTUALIZAMOS DEBEMOS PONER LA CUENTA A 0 */
//unset($_SESSION);
//enviar datos a scroll.php

$_SESSION["id_estado"]=$_POST['id_estado']; 
$_SESSION["servicios"]=$_POST['servicios'];
$_SESSION['totalRows_planes']=1;
  
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
 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
 
mysql_select_db($database, $dbConn);

//echo "id_estado = |" . $_POST['id_estado'] . "|"; 
//echo "id_servicios = |" . implode(", ", $_POST['servicios']) . "|";

/* Obtiene todos los planes que corresponden con los parámetros de filtrado de la barra rápida */
$query_planes = sprintf("SELECT id_plan, nombre, (select nombre from empresas where empresas.id_empresa = planes.id_empresa) as empresa, (select codigo_color from empresas where empresas.id_empresa = planes.id_empresa) as empresa_color, precio, dato_principal_1, id_tipoDato_principal_1, dato_principal_2, id_tipoDato_principal_2, dato_principal_3, id_tipoDato_principal_3, dato_principal_4, id_tipoDato_principal_4, mas_datos, visible FROM planes WHERE id_plan in(select id_plan from cobertura where id_estado = %s) AND id_plan in( SELECT id_plan FROM planes_tipoServicios WHERE id_tipoServicio IN (%s) AND id_plan NOT IN(SELECT id_plan FROM planes_tipoServicios WHERE id_tipoServicio NOT IN (%s)) AND visible=1 GROUP BY id_plan HAVING count(*) >= %s )  ORDER BY precio ASC limit 0,10", GetSQLValueString($_POST['id_estado'], "int"), implode(", ", $_POST['servicios']), implode(", ", $_POST['servicios']), count($_POST['servicios']));
$planes = mysql_query($query_planes, $dbConn) or die(mysql_error());
$totalRows_planes = mysql_num_rows($planes);


//echo $query_planes;

/* Obtiene las opciones de filtrado para checkboxes */
$query_filtros = sprintf("SELECT id_tipoDato, id_tipoServicio, (select icono from tipoServicios where tipoServicios.id_tipoServicio = tipoDatosServicios.id_tipoServicio) as icono_servicio, label, tipo, hijoDe, grupo FROM tipoDatosServicios WHERE id_tipoServicio IN( SELECT id_tipoServicio FROM planes_tipoServicios WHERE id_plan in(select id_plan from cobertura where id_estado = %s) AND id_plan in( SELECT id_plan FROM planes_tipoServicios WHERE id_tipoServicio IN (%s) AND id_plan NOT IN(SELECT id_plan FROM planes_tipoServicios WHERE id_tipoServicio NOT IN (%s)) GROUP BY id_plan HAVING count(*) >= %s ) ) AND tipo='boolean' ORDER BY orden ASC", GetSQLValueString($_POST['id_estado'], "int"), implode(", ", $_POST['servicios']), implode(", ", $_POST['servicios']), count($_POST['servicios']));
$filtros = mysql_query($query_filtros, $dbConn) or die(mysql_error());
$totalRows_filtros = mysql_num_rows($filtros);

//echo "///////////////////////////////////////////";
//echo $query_filtros; 

/* Obtiene las opciones de filtrado para sliders */
$query_filtros_slider = sprintf("SELECT id_tipoDato, id_tipoServicio, (select icono from tipoServicios where tipoServicios.id_tipoServicio = tipoDatosServicios.id_tipoServicio) as icono_servicio, label, tipo FROM tipoDatosServicios WHERE id_tipoServicio IN( SELECT id_tipoServicio FROM planes_tipoServicios WHERE id_plan in(select id_plan from cobertura where id_estado = %s) AND id_plan in( SELECT id_plan FROM planes_tipoServicios WHERE id_tipoServicio IN (%s) AND id_plan NOT IN(SELECT id_plan FROM planes_tipoServicios WHERE id_tipoServicio NOT IN (%s)) GROUP BY id_plan HAVING count(*) >= %s ) ) AND tipo='integer'", GetSQLValueString($_POST['id_estado'], "int"), implode(", ", $_POST['servicios']), implode(", ", $_POST['servicios']), count($_POST['servicios']));
$filtros_slider = mysql_query($query_filtros_slider, $dbConn) or die(mysql_error());
$totalRows_filtros_slider = mysql_num_rows($filtros_slider);

//echo "///////////////////////////////////////////";
//echo $query_filtros_slider;

$suggestedPlansIncluded = array();


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

require 'Templates/mainTemplate.php'; ?>

<script type="text/javascript" charset="utf-8" src="JQuery/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="JQuery/jquery-ui-1.11.4.custom/jquery-ui.min.css" />
<script type="text/javascript" charset="utf-8" src="JQuery/colorbox-1.6.3/jquery.colorbox-min.js"></script>
<link rel="stylesheet" type="text/css" href="JQuery/colorbox-1.6.3/colorbox.css" />












<script type="text/javascript" src="ajax/scroll.js"></script>
<script type="text/javascript">



var cantidadcargadas=0;
function cargardatos(cantidad){
    // Petición AJAX
    
    //$("#loader").html("<img src='loader2.gif'/>");
                
	$.ajax({
		type: "POST",
		url: "scroll.php",
		data: { "cantidadcargadas" :  cantidad },
		datatype: 'html',         
		contentType: "application/x-www-form-urlencoded; charset=UTF-8",
		beforeSend: function () {
            $("#loading").html("Procesando, espere por favor...<br><img src='images/loading.gif'/>");
        },
		success: function(data){
	      //alert("entro");
	      //alert(data),
	      $('#loading').empty();
	      if(data !=""){
	      	//alert(cantidad),
		      $('#results').append( data );
		    
          }  iniciar();
	  }
	});
			                           
}


$(window).scroll(function(){
        if ($(window).scrollTop() == $(document).height() - $(window).height()){
                //alert('Scroll JS'),
                cantidadcargadas+=10,
                cargardatos(cantidadcargadas)
        }                                       
});



















	$(document).ready(function() {
		cargardatos(0);	
});
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function iniciar(){
		//var priceMax = getMaxVal(getPlansBeingDisplayed());
		var priceMax = <? echo (int)$_SESSION['priceMax']; ?>;
		// Slider para filtrar por rango de precios.
	    $( "div#filters-bar div#precio.slider" ).slider({
	      range: true,
	      min: 0,
	      max: priceMax,
	      values: [0, priceMax],
	      slide: function( event, ui ) {
	        $( "div#filters-bar #amount_precio" ).val( "$" + addCommas(ui.values[ 0 ]) + " - $" + addCommas(ui.values[ 1 ]) );
	      }
	    });

		// Asignamos el rango inicial al slider.
		$( "div#filters-bar #amount_precio" ).val( "$" + addCommas($( "div#filters-bar div#precio.slider" ).slider( "values", 0 )) + " - $" + addCommas($( "div#filters-bar div#precio.slider" ).slider( "values", 1 )) );

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		// En este arreglo se almacenarán los valores máximos
		// para cada tipo de dato que se filtra con un slider.
		// Y lo utilizaremos luego, para asignarle el valor máximo
		// a los planes con valor "Ilimitados".
		var maxVals = [];
	
		// creamos los sliders para poder filtrar los datos númericos por rango.
		$( "div#filters-bar div.slider-adicional" ).each(function(){
								
				var $this = $(this);
				var valMax = getMaxVal(getPlansBeingDisplayed(), $this.attr("id"));
			
				// Si existe un valor para este tipo de dato, creamos el slider y le asignamos el valor máximo al rango.
				// En caso contrario, ocultamos el slider. 
				if(valMax > 0){
			
					maxVals["tipo_" + $this.attr("id")] = valMax;
				
					$this.find('.slider').slider({
						range: true,
						min: 0,
						max: valMax,
						values: [0, valMax],
						slide: function(event, ui){
							$this.find('input').val(addCommas(ui.values[ 0 ]) + " - " + addCommas(ui.values[ 1 ]));
						}
					});
			
					// Asignamos el rango inicial al slider.
					$this.find('input').val(addCommas($this.find('.slider').slider( "values", 0 )) + " - " + addCommas($this.find('.slider').slider( "values", 1 )) );
	
				} else {
				
					$(this).hide();
				
				}
		
		});	

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// A todos los datos de los planes con valor "Ilimitados", le asignamos el valor máximo correspondiente a su tipo de dato.
		// Ésto para que al filtrarse aparezcan únicamente cuando el usuario seleccione, en el rango del slider correspondiente, el valor máximo.
		$('.dato li[value="Ilimitados"]').each(function(){
		
			$(this).val(maxVals[$(this).attr('class')]);
			
		});

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// Esconde los checkboxes de las opciones de los datos no incluidos en los planes originalmente filtrados.
		$('input[type=checkbox].filter').each(function(){
		
			var tipoDato = $(this).val();
			var filteredPlans = $.grep(getPlansBeingDisplayed(), function(plan){
							
				return(plan.find('.dato li.' + tipoDato).length > 0);
			
			});
			
			if(filteredPlans.length == 0){
							
				$(this).parent().hide();
			}
					
		});
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// Si el checbox tiene checkboxes hijos, entonces los muestra o esconde.
		$('input[type=checkbox].filter').change(function(){
			
			$this = $(this);
	
			if($this.is(':checked')) {
					$('div.filter.' + $this.val()).fadeIn();
					// buscamos a los demás integrantes del grupo, si hay grupo, y los deschecamos.
					// También lo ejecutamos, para que si tiene hijos, los descheque también.
					$('div.filter.' + $this.attr('group')).find('input[type=checkbox].filter').not($this).attr('checked', false).change();
			} else {
					$('div.filter.' + $this.val()).fadeOut(function(){
						$('div.filter.' + $this.val()).find('input[type=checkbox].filter').attr('checked', false);
					});
			}			
			
		});

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
		// Botón "Actualizar Resultados" (según los filtros seleccionados).
		$('div#comparator div#filters-bar div#buttons div#btn_actualizarResults').click(function(){
				
			/* FILTRAMOS POR EL PRECIO  */

			var limInf = $( "div#filters-bar div#precio.slider" ).slider( "values", 0 );
			var limSup = $( "div#filters-bar div#precio.slider" ).slider( "values", 1 );
		
			//alert(limInf);
			//alert(limSup);
			
			var filteredPlans = $.grep(PLANES, function(plan){
			
				return ((parseInt(plan.find('.precio span').text(), 10) <= limSup) && (parseInt(plan.find('.precio span').text(), 10) >= limInf));
			
			});
						
			/* FILTRAMOS POR LOS DEMÁS SLIDERS */

			$( "div#filters-bar div.slider-adicional:visible" ).each(function(){
			
					$this = $(this);

					var limInf = $this.find('.slider').slider( "values", 0 );
					var limSup = $this.find('.slider').slider( "values", 1 );
			
					filteredPlans = $.grep(filteredPlans, function(plan){
					
						return (((parseInt(plan.find('.dato li.tipo_' + $this.attr("id")).val(), 10) <= limSup) && (parseInt(plan.find('.dato li.tipo_' + $this.attr('id')).val(), 10) >= limInf)) || (plan.find('.dato li.tipo_' + $this.attr("id")).length == 0));
					
					});
			
			});
			
			/* FILTRAMOS POR LOS CHECKBOXES */
			
			$('input[type=checkbox].filter:checked').each(function(){
			
				var tipoDato = 	$(this).val();
			
				filteredPlans = $.grep(filteredPlans, function(plan){
								
					return(parseInt(plan.find('.dato li.' + tipoDato).val(), 10) == 1);
			
				});
			
			});
			
			// Luego agregamos únicamente los planes que pasaron los filtros.
			displayPlanes(filteredPlans);

			// Deschecamos todos los planes que el usuario pudo haber seleccionado para comparar.
			//$('div#comparator div#results div.plan input[type=checkbox]').attr("checked", false);
	
			//Reseteamos los espacios disponibles.
			//$('div.available-spaces .space').removeClass('occupied');
			//AVAILABLE_SPACES = 6;
						
			$(this).find('span').html("Filtros aplicados").fadeIn().delay(500).fadeOut().fadeIn().delay(1000).fadeOut();		
		
		});

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// Botón "Borrar Selección", para limpiar todos los filtros.
		$('div#comparator div#filters-bar div#buttons div#btn_borrarSeleccion').click(function(){
					
			// Primero limpiamos todos los filtros.
			$( "div#filters-bar div#precio.slider" ).slider("values", 0, 0);
			$( "div#filters-bar div#precio.slider" ).slider("values", 1, priceMax);

			$( "div#filters-bar div.slider-adicional:visible" ).each(function(){
				$(this).find('.slider').slider("values", 0, 0);
				$(this).find('.slider').slider("values", 1, $(this).find('.slider').slider("option", "max"));				
			});

			$('input[type=checkbox].filter:checked').attr("checked", false).change();
			
			$('div#comparator div.comparing-bar div.sorting select.sort').val("menor-mayor");

			// Deschecamos todos los planes que el usuario pudo haber seleccionado para comparar.
			$('div#comparator div#results div.plan input[type=checkbox]').attr("checked", false);
		
			//Reseteamos los espacios disponibles.
			$('div.available-spaces .space').removeClass('occupied');
			AVAILABLE_SPACES = 6;
		
			// Finalmente desplegamos todos los planes nuevamente.
			displayPlanes(PLANES);
			
		});
					
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
		// Botón "VER COMPARACION".
		$('div#comparator div.comparing-bar div.btn_verComparacion').click(function(){
				
			var planes = "";
			var sugeridos = "";
			
			
			$('div#comparator div#results div.plan input[type=checkbox]:checked').each(function(){
			
				//alert($(this).attr('name') + " - " + $(this).val());			
				
				planes += "planes[]=" + $(this).val() + "&";
				
				if($(this).hasClass("sugerido")){
					sugeridos	+= "sugeridos[]=" + $(this).val() + "&";	
				}
										
				//$.colorbox({href:"comparacion.php?" + planes + sugeridos, iframe:true, width:0, height:0, initialWidth:0, initialHeight:0, scrolling:false, opacity: 0.7});	
						
			});
			
			$.colorbox({href:"comparacion.php?" + planes + sugeridos, iframe:true, width:0, height:0, initialWidth:0, initialHeight:0, scrolling:false, opacity: 0.7});
			
			//alert(planes);
		
		});

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		// Para ordenar los planes: de precio menor a mayor y de mayor a menor.
		$('div#comparator div.comparing-bar div.sorting select.sort').change(function(){
		
			var ordenarDe = $(this).val();
			
			// Esta instrucción sirve para sincronizar el select de las 2 barras de comparación.
			$('div#comparator div.comparing-bar div.sorting select.sort').val(ordenarDe);
			
			var displayedPlanes = getPlansBeingDisplayed();
			var sortedPlanes = [];
			
			switch(ordenarDe){
			
				case "menor-mayor":
									
					//PLANES = PLANES.sort(function(a, b){return parseInt(a.find('.precio span').text(), 10) + parseInt(b.find('.precio span').text(), 10)});
					sortedPlanes = displayedPlanes.sort(function(a, b){return parseInt(a.find('.precio span').text(), 10) - parseInt(b.find('.precio span').text(), 10)});
					break;
					
				case "mayor-menor":
				
					//PLANES = PLANES.sort(function(a, b){return parseInt(a.find('.precio span').text(), 10) - parseInt(b.find('.precio span').text(), 10)});
					sortedPlanes = displayedPlanes.sort(function(a, b){return parseInt(b.find('.precio span').text(), 10) - parseInt(a.find('.precio span').text(), 10)});
					break;	
			}
											
			displayPlanes(sortedPlanes);
		
		}); //$('select#sort').change()
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		// Al finalizar toda la inicialización, ordenamos los planes, pues los sugeridos no estarían en orden,
		// ya que los incluimos después.	
		$('div#comparator div.comparing-bar div.sorting select.sort').val('menor-mayor').change();		
		
		// Creamos y llenamos nuestro Arreglo Principal de Planes, que nos servirá para el filtrado.
		var PLANES = getPlansBeingDisplayed();
	
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
										
		// Desplazamos la página hasta la barra rápida de filtrado.
		$('html, body').animate({scrollTop: $("div#header").height() - $("div#quick-filter-bar").height()}, 2000);
					
	}//}); //$(document).ready(); function iniciar();

	$(window).load(function(){
		
		if($(window).width() > 800){
			
			// Si es necesario, hacemos más alta la barra de filtros para que abarque todos los sliders.
			
			//alert($("div#comparator div#filters-bar div#sliders").height());
			//alert($("div#comparator div#filters-bar div#add-and-filters").height());
					
			if($("div#comparator div#filters-bar div#sliders").height() > $("div#comparator div#filters-bar div#add-and-filters").height()){	
				$("div#comparator div#filters-bar").height($("div#comparator div#filters-bar div#sliders").height());
			}
		}
	
	});

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// FUNCIONES

	function getPlansBeingDisplayed(){
		
			var planes = [];
			// Llenamos el arreglo con los planes que se están visualizando.
			$('div#comparator div#results div.plan').each(function(index){
				planes.push($(this));
			});
	
		return planes;
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function getMaxVal(planes, id_tipoDato){
	
		var sortedPlans = [];
		var maxVal = 0;
	
		if(planes.length > 0){
			// Si id_tipoDato es NULL entonces estamos solicitando el Precio Máximo.
			if(typeof id_tipoDato === "undefined" || id_tipoDato === null){
				
				sortedPlans = planes.sort(function(a, b){return parseInt(b.find('.precio span').text(), 10) - parseInt(a.find('.precio span').text(), 10)});
				maxVal = sortedPlans[0].find('.precio span').text();
			
			} else {
				
				sortedPlans = planes.sort(function(a, b){return parseInt(b.find('.dato li.tipo_' + id_tipoDato).val(), 10) - parseInt(a.find('.dato li.tipo_' + id_tipoDato).val(), 10)});
				maxVal = sortedPlans[0].find('.dato li.tipo_' + id_tipoDato).val();
			}
		}
	
		return maxVal;
		
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function displayPlanes(planes){

			// Primero eliminamos los planes visualizándose
			$('div#comparator div#results').empty();
		
			if(planes.length > 0){
				for(i=0 ; i < planes.length ; i++){
	
					$('div#comparator div#results').append(planes[i]);
									
					planes[i].removeClass("right");
					if(((i + 1) % 2) == 0){
						planes[i].addClass("right");
					}
									
				}
			} else {
				$('div#comparator div#results').append($('<div>').attr("id", "no-results").html("No hay resultados que correspondan con la combinaci&oacute;n de filtros seleccionada."));
			}
			
			$('div#comparator div#results').append($('<div>').addClass("clearfix"));
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	var AVAILABLE_SPACES = 6;

	// Para actualizar el estatus del componente que muestra los espacios disponibles.
	// Además de servir para limitar la cantidad de planes que el usuario seleccione para compararlos.	
	function updateAvailableSpaces(element){

			if($(element).is(":checked")) {
				
				if(AVAILABLE_SPACES > 0){
				
					if(AVAILABLE_SPACES == 6){
						
						$('div.comparing-bar').each(function(){
							$(this).find('div.available-spaces .space:first-child').addClass('occupied');
						});
						
					} else {

						$('div.comparing-bar').each(function(){
							$(this).find('div.available-spaces .space.occupied').next().addClass('occupied');
						});
												
					}
											
					AVAILABLE_SPACES--;
					
				} else {
					
					$(element).attr('checked', false);
				
				}
	
			} else {
				
				$('div.comparing-bar').each(function(){
					$(this).find('div.available-spaces .space.occupied').last().removeClass('occupied');
				});
				
				AVAILABLE_SPACES++;
			}
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Sirve para darle formato a las cifras, agregando comas a los miles.

	function addCommas(nStr)
	{
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

</script>

<?php require 'Templates/headTemplate.php'; ?>

<!-- START CONTENT -->

	<div id="comparator" class="indent">
	
  	<!--div id="ad-top"></div-->
    
    <div id="filters-bar">
    
    	<div id="add-and-filters">
      
      
        <div id="ad-top" class="ad"><?php loadAd(COMPARADOR_CENTER, $dbConn); ?></div>
        
        <?php if($totalRows_filtros > 0){  ?>
        
          <div id="filters" class="box">
          
              <?php
              
              /*if($totalRows_filtros > 0){*/
              
                  $i = 0;
                  $tipoServicio = "";
                 
                  while($row_filtros = mysql_fetch_assoc($filtros)){ 
              
                    if($row_filtros['id_tipoServicio'] != $tipoServicio){
              
                      $tipoServicio = $row_filtros['id_tipoServicio'];
                      
                      if($i == 0){
                        echo "<div class='filters_tipoServicio'>";
                      } else {
                        
                        if(($i % 2) == 0){
                           $bgcolor = "darker";
                        }
                        echo "</div><div class='filters_tipoServicio " . $bgcolor . "'>";
                      }
                      
                      echo "<div class='icono_servicio'><img src='uploads/servicios/" . $row_filtros['id_tipoServicio'] . "/" . $row_filtros['icono_servicio'] . "' /></div>";
                      if($tipoServicio == TEL_MOVIL){
                        echo "<div class='filter'>";
                        echo "	<input type='checkbox' id='filtroTipoDato_redesSoc' name='filtroTipoDato_redesSoc' value='tipo_redesSoc' class='filter'>";
                        echo "	<label for='filtroTipoDato_redesSoc'>Redes sociales</label>";
                        echo "</div>";
                      }
                    }
              
                    $classes_hijoDe = "";
                    if($row_filtros['hijoDe'] != NULL){
                      $classes_hijoDe = "tipo_" . $row_filtros['hijoDe'] . " hidden";
                    }
                    
                    $class_grupo = "";
                    if($row_filtros['grupo'] != NULL){
                      $class_grupo = " grupo_" . $row_filtros['grupo'];
                    }
              
                  ?>
                  
                    <div class="filter <?php echo $classes_hijoDe . $class_grupo; ?>"> 
                      <input type="checkbox" id="filtroTipoDato_<?php echo $row_filtros['id_tipoDato']; ?>" name="filtroTipoDato_<?php echo $row_filtros['id_tipoDato']; ?>" value="tipo_<?php echo $row_filtros['id_tipoDato']; ?>" group="grupo_<?php echo $row_filtros['grupo']; ?>" class="filter">
                      <label for="filtroTipoDato_<?php echo $row_filtros['id_tipoDato']; ?>"><?php echo $row_filtros['label']; ?></label>
                    </div>
                 <?php
                 
                    $i++;
                 
                  }//while
                 
                 echo "  <div class='clearfix'></div>";
                 echo "</div>";
              
              //}//if($totalRows_filtros > 0)
              
              ?>
                   
            <div class="clearfix"></div>
          </div><!-- #filters -->
          
				<?php }//if($totalRows_filtros > 0) ?>

      </div><!-- #add-and-filters -->
    
    
    	<div id="sliders">
      
        <div class="box">
          <div class="label">RANGO DE PRECIO</div>
          <div id="precio" class="slider"></div>
          <p>
            <label for="amount_precio">Rango:</label>
            <input type="text" id="amount_precio" readonly>
          </p>
        </div>
        
        <?php
				
					if($totalRows_filtros_slider > 0){
						
						while($row_filtros_slider = mysql_fetch_assoc($filtros_slider)){
							
							echo "<div id='" . $row_filtros_slider['id_tipoDato'] . "' class='box slider-adicional'>";
							echo "	<div class='label'>" . $row_filtros_slider['label'] . "</div>";
							echo "	<div class='sliderContainer'><div class='slider adicional'></div><span class='ilimitado'>+</span><div class='clearfix'></div></div>";
							echo "	<p>";
							echo "		<label for='amount_" . $row_filtros_slider['id_tipoDato'] . "'>Rango:</label>";
							echo "		<input type='text' id='amount_" . $row_filtros_slider['id_tipoDato'] . "' readonly>";
							echo "	</p>";
							echo "</div>";
						}	
					}
				
				?>
                
      </div><!-- #sliders -->
                 
      <div id="buttons">
        <div id="btn_actualizarResults">APLICAR FILTROS<br /><span></span></div>
        <div id="btn_borrarSeleccion">BORRAR SELECCI&Oacute;N</div>
      </div>
      
      <div class="clearfix"></div>
      
    </div><!-- #filters-bar -->

		<?php createComparingBar(); ?>    
  
    <div id="ad-left" class="ad"><?php loadAd(COMPARADOR_LEFT, $dbConn); ?></div>
         
    <div id="ad-right" class="ad"><?php loadAd(COMPARADOR_RIGHT, $dbConn); ?></div>

    <div id="results">
    
    	<?php
			
				
				
				?>
       
             

           
    </div><!-- #results -->
    <div id="loading" style="text-align:center"></div>
	<?php createComparingBar(); ?>    
    
    <div class="clearfix"></div>
  
  </div><!-- #comparador -->
 
<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>