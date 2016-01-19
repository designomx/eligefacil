<?php 
session_start();

if(!isset($_SESSION["email"])){
	
	header("Location: ../index.php");
	exit;
		
}

require 'Templates/phpHeadingTemplate.php'; 

/////////////////////////////////////////////////////////////////////////////////////////////////////

mysql_select_db($database, $dbConn);

/* Obtiene todas las páginas con anuncios */
$query_paginas = sprintf("SELECT pagina FROM anuncios GROUP BY pagina ORDER BY id_anuncio");
$paginas = mysql_query($query_paginas, $dbConn) or die(mysql_error());

/////////////////////////////////////////////////////////////////////////////////////////////////////

?>

<?php require 'Templates/mainTemplate.php'; ?>

<script type="text/javascript">

	///////////////////////////////////////////////////////////////////////////////////////////////////
	
	function showPopupWindow(caller, id_anuncio){

		//hiddens
		$id_anuncio = $('div#formAnuncioWindow input[name=id_anuncio]');
		$imagen_actual = $('div#formAnuncioWindow input[name=imagen_actual]');
		
		$url = $('div#formAnuncioWindow input[name=url]');
		$imagen = $('div#formAnuncioWindow input[name=imagen]');
		$msg = $('td.msg');
		
		allFields = $([]).add($id_anuncio).add($url).add($imagen);
		
		$currentImg = $('div#formAnuncioWindow td.currentImg');
		$medidas = $('div#formAnuncioWindow td.medidas');
		
		// Limpiamos el posible contenido de todos los inputs.
		allFields.val("").removeClass('inputDataMissing');
		$currentImg.empty();
		$msg.html("Los campos con [*] son requeridos.").removeClass("errorMsg");
						
		// Obtenemos de la base los datos correspondientes del anuncio y los asignamos a los campos.
		$.getJSON("ajax/getAdData.php", {'id': id_anuncio}, function(data) {
				
				//hiddens
				$id_anuncio.val(id_anuncio);
				$imagen_actual.val(data['imagen']);
				
				$url.val(data['url']);
				$currentImg.append($('<img>').attr("src", "../uploads/anuncios/" + id_anuncio + "/" + data['imagen']));
				$medidas.html("[ " + data['ancho'] + "px de ancho - " + data['alto'] + "px de alto ]");
																																																	 
		}).done(function(){
								
				// Ubicamos la ventana a la altura del elemento que ejecutó esta función.
				$('div#formAnuncioWindow').css('top', $(caller).offset().top);				
				// Una vez cargados los datos y ubicada la ventana a la altura adecuada, la mostramos.
				$('div#formAnuncioWindow').fadeIn();
				removeCursorToWait();	

		}); //.done(function(){... 
				
	
	} // function showPopupWindow


	///////////////////////////////////////////////////////////////////////////////////////////////////


	$(document).ready(function() {
			
		$('#updateAd').on('submit',(function(e) {
		
				e.preventDefault();
	
				$msg = $('td.msg');
				
				changeCursorToWait();
				
				$.ajax({
					url: "ajax/saveAdData.php", 		// Url to which the request is send
					type: "POST",             			// Type of request to be send, called as method
					data: new FormData(this), 			// Data sent to server, a set of key/value pairs (i.e. form fields and values)
					contentType: false,       			// The content type used when sending data to the server.
					cache: false,             			// To unable request pages to be cached
					processData: false,       			// To send DOMDocument or non processed data file it is set to false
					success: function(data)   			// A function to be called if request succeeds
					{
						removeCursorToWait();
						$('div#formAnuncioWindow').fadeOut();
						location.reload();						
						//$msg.html(data);
					}
				});
			
		}));							
		
			
	}); //$(document).ready();

	///////////////////////////////////////////////////////////////////////////////////////////////////

</script>

<?php require 'Templates/headTemplate.php'; ?>

<!-- START CONTENT -->

<table class="identification" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><h2>Administrador de Anuncios</h2></td>
  </tr>
</table>

<?php

while($row_paginas = mysql_fetch_assoc($paginas)){
	
	/* Obtiene todos los anuncios de cada página */
	$query_anuncios = sprintf("SELECT * FROM anuncios WHERE pagina=%s ORDER BY id_anuncio", GetSQLValueString($row_paginas['pagina'], "text"));
	$anuncios = mysql_query($query_anuncios, $dbConn) or die(mysql_error());
	$totalRows_anuncios = mysql_num_rows($anuncios);

?>
        
  <table class="itemsList" border="0" cellspacing="0" cellpadding="0"> 
    <tr class="headers nodrop nodrag">
      <td  colspan="5"><?php echo $row_paginas['pagina']; ?></td>
    </tr>
    <tr class="headers nodrop nodrag">
      <td width="20%"></td>
      <td width="30%">Ubicaci&oacute;n</td>
      <td width="30%">URL</td>
      <td width="20%"></td>
    </tr>
    <?php while($row_anuncios = mysql_fetch_assoc($anuncios)){ ?>
      
    <tr>
      <td class="picture">
        <img src="../uploads/anuncios/<?php echo $row_anuncios['id_anuncio'] . "/" . $row_anuncios['imagen'] ; ?>" />
      </td>
      <td><?php echo $row_anuncios['ubicacion']; ?></td>
      <td><?php echo $row_anuncios['url']; ?></td>
      <td class="button">
        <!-- /////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <!-- BOTON: Editar -->
        
        <input type="button" id="edit" value="Editar" onclick="changeCursorToWait(); showPopupWindow(this, <?php echo $row_anuncios['id_anuncio']; ?>);" />
        
        <!-- /////////////////////////////////////////////////////////////////////////////////////////////////// -->
      </td>
    </tr>
    
    <?php }//while(anuncios... ?>
  </table>

<?php }//while(paginas... ?>



<div id="formAnuncioWindow" class="popUpWindow">

  <form id="updateAd" action="" method="post" enctype="multipart/form-data">
  
  		<input type="hidden" id="id_anuncio" name="id_anuncio" value="" />
      <input type="hidden" id="imagen_actual" name="imagen_actual" value="" />
                                       
      <table class="form" border="0" cellspacing="0" cellpadding="5">
        <tr>
          <td id="title" colspan="2" class="center"><h2>Editar Anuncio</h2></td>
        </tr>
        <tr>
          <td class="msg center" colspan="2">Los campos con [*] son requeridos.</td>
        </tr>
        <tr>
          <td class="label">URL:</td>
          <td><input type="text" id="url" name="url" value=""/></td>
        </tr>
        <tr>
          <td class="label">Imagen Actual:</td>
          <td class="currentImg"></td>
        </tr>
        <tr>
          <td class="label">Nueva Imagen:</td>
          <td><input type="file" id="imagen" name="imagen" value=""/></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td class="medidas"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Nombre del archivo sin acentos ni caracteres especiales.</td>
        </tr>
      </table>
      
      <table class="buttons" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="50%" class="button">
            <input type="button" value="Cancelar" onClick="$('div#formAnuncioWindow').fadeOut();" />
          </td>
          <td width="50%" class="button"><input id="saveData" name="saveData" type="submit" value="Guardar Cambios"></td>
        </tr>
      </table>
  
  </form>

</div><!-- #formAnuncioWindow -->        



 
<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>