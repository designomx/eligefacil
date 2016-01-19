<?php 
require 'Templates/phpHeadingTemplate.php';
require 'Templates/mainTemplate.php'; ?>

<script type="text/javascript" charset="utf-8" src="JQuery/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="JQuery/jquery-ui-1.11.4.custom/jquery-ui.min.css" />
<script type="text/javascript" charset="utf-8" src="JQuery/colorbox-1.6.3/jquery.colorbox-min.js"></script>
<link rel="stylesheet" type="text/css" href="JQuery/colorbox-1.6.3/colorbox.css" />

<script type="text/javascript">

	$(document).ready(function() {
	
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		var PAQUETES = [];
		
		// Llenamos en el arreglo general de paquetes, que utilizaremos para ordenar.
		$('div#paquetes-ott div#results div.plan').each(function(index){
		
			PAQUETES.push($(this));
		
		});
	
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		// Plan -> Ver más.
		$("div#paquetes-ott div#results div.plan div.ver-mas-container a").colorbox({iframe:true, width:0, height:0, initialWidth:0, initialHeight:0, scrolling:false, opacity: 0.7});

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		
		// Para ordenar los planes: de precio menor a mayor y de mayor a menor.
		$('div#paquetes-ott div.comparing-bar div.sorting select#sort').change(function(){
		
			switch($(this).val()){
			
				case "menor-mayor":
									
					//PAQUETES = PAQUETES.sort(function(a, b){return parseInt(a.find('.precio span').text(), 10) + parseInt(b.find('.precio span').text(), 10)});
					PAQUETES = PAQUETES.sort(function(a, b){return a.find('.precio span').text() + b.find('.precio span').text()});
					break;
					
				case "mayor-menor":
				
					//PAQUETES = PAQUETES.sort(function(a, b){return parseInt(a.find('.precio span').text(), 10) - parseInt(b.find('.precio span').text(), 10)});
					PAQUETES = PAQUETES.sort(function(a, b){return a.find('.precio span').text() - b.find('.precio span').text()});
					break;	
			}
			
			displayPaquetes(PAQUETES);
		
		}); //$('select#sort').change()
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
		// Desplazamos la página hasta la barra rápida de filtrado.
		$('html, body').animate({scrollTop: $("div#header").height() - $("div#quick-filter-bar").height()}, 2000);
					
	}); //$(document).ready();

	function displayPaquetes(paquetes){
		
			for(i=0 ; i < paquetes.length ; i++){
				paquetes[i].detach().prependTo('div#paquetes-ott div#results');
				
				paquetes[i].removeClass("right");
				if(((i + 1) % 2) == 0){
					paquetes[i].addClass("right");
				}
			}
		
	}

</script>

<?php require 'Templates/headTemplate.php'; ?>

<?php

/* Obtiene todos los paquetes ott */
$query_paquetes_ott = "SELECT id_paquete, nombre, (select id_empresa from empresas_ott where empresas_ott.id_empresa = paquetes_ott.id_empresa) as id_empresa, (select logo from empresas_ott where empresas_ott.id_empresa = paquetes_ott.id_empresa) as empresa_logo, precio, dato_principal_1, dato_principal_2, dato_principal_3, dato_principal_4, mas_datos FROM paquetes_ott ORDER BY precio ASC";
$paquetes_ott = mysql_query($query_paquetes_ott, $dbConn) or die(mysql_error());
 
?>

<!-- START CONTENT -->

	<div id="paquetes-ott" class="indent">
 
    <div id="ad-top" class="ad ott"><?php loadAd(SERVICIOS_OTT_CENTER, $dbConn); ?></div>
    
    <div class="comparing-bar">
      <div class="sorting">
      	<span>ORDENAR POR:</span>
      	<select id="sort" name="sort">
        	<option value="menor-mayor">PRECIO MENOR A MAYOR</option>
          <option value="mayor-menor">PRECIO MAYOR A MENOR</option>
        </select>
      </div>  
    </div>
  
    <div id="ad-left" class="ad"><?php loadAd(SERVICIOS_OTT_LEFT, $dbConn); ?></div>
         
    <div id="ad-right" class="ad"><?php loadAd(SERVICIOS_OTT_RIGHT, $dbConn); ?></div>

    <div id="results">
    
    	<?php
			
			 $i = 1;
			
			 while($row_paquetes_ott = mysql_fetch_assoc($paquetes_ott)){
							
			?>
    
    		<div id="paquete-<?php echo $i; ?>" class="plan box <?php if(($i % 2) == 0){ echo "right"; } ?>">
					<div class="section-left">
            <div class="empresa ott"><img src="uploads/empresas_ott/<?php echo $row_paquetes_ott['id_empresa'] . "/" . $row_paquetes_ott['empresa_logo'];?>" /></div>
            <div class="nombre"><?php echo $row_paquetes_ott['nombre'];?></div>
            <div class='precio-formateado'>$<span><?php echo number_format($row_paquetes_ott['precio'], 0, '.', ','); ?></span></div>
            <div class="precio"><span><?php echo $row_paquetes_ott['precio'];?></span></div>
          </div>
          <div class="section-right">
          	<?php if($row_paquetes_ott['dato_principal_1'] != NULL){ ?>
            <div class="dato-1 dato"><li><?php echo $row_paquetes_ott['dato_principal_1'];?></li></div>
            <?php } ?>
          	<?php if($row_paquetes_ott['dato_principal_2'] != NULL){ ?>
            <div class="dato-2 dato"><li><?php echo $row_paquetes_ott['dato_principal_2'];?></li></div>
            <?php } ?>
          	<?php if($row_paquetes_ott['dato_principal_3'] != NULL){ ?>
            <div class="dato-3 dato"><li><?php echo $row_paquetes_ott['dato_principal_3'];?></li></div>
            <?php } ?>
          	<?php if($row_paquetes_ott['dato_principal_4'] != NULL){ ?>
            <div class="dato-4 dato"><li><?php echo $row_paquetes_ott['dato_principal_4'];?></li></div>
            <?php } ?>
          </div>
          <div class="clearfix"></div>
          <div class="ver-mas-container"><a onClick="$(this).attr('ref')" href="paquete-ott.php?id_paquete=<?php echo $row_paquetes_ott['id_paquete']; ?>"><div class="ver-mas">Ver m&aacute;s</div></a></div>
    		</div>
    	
			<?php
				
				$i++;
			
			 } ?>
       
       <div class="clearfix"></div>
           
    </div>
    
    <div class="clearfix"></div>
  
  </div>
 
<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>