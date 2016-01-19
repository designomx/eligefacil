<?php 
session_start();

if(!isset($_SESSION["email"])){
	
	header("Location: ../index.php");
	exit;
		
}

require 'Templates/phpHeadingTemplate.php'; 


/////////////////////////////////////////////////////////////////////////////////////////////////////

mysql_select_db($database, $dbConn);

/* Obtiene todas las imágenes del carrusel */
$query_fotos = "SELECT * FROM imagenesCarrusel ORDER BY orden ASC";
$fotos = mysql_query($query_fotos, $dbConn) or die(mysql_error());
$totalRows_fotos = mysql_num_rows($fotos);

/////////////////////////////////////////////////////////////////////////////////////////////////////

?>

<?php require 'Templates/mainTemplate.php'; ?>

<!-- Including the HTML5 Uploader plugin -->
<script src="JQuery/dragAndDropFileUpload/js/jquery.filedrop.js"></script>
<!-- The main script file -->
<script src="JQuery/dragAndDropFileUpload/js/script.js"></script>
<!-- The styles -->
<link rel="stylesheet" href="JQuery/dragAndDropFileUpload/css/styles.css" />

<!-- Sortable behaviour -->
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">


<script type="text/javascript">

		///////////////////////////////////////////////////////////////////////////////////////////////////
	
		$(document).ready(function() {
															 
				// Para poder ordenar las imágenes.											 										
				$( "#dropbox" ).sortable();
				$( "#dropbox" ).disableSelection();
				
		});
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		
		function deletePicture(id_imagen){
			
			if(confirm("¿Estás seguro de que deseas eliminar esta imagen?\n\nEsta acción es irreversible.")){

				$.get("ajax/deletePhoto.php", {'id_imagen': id_imagen}).done(function(){					
				
					$('div.preview#' + id_imagen).hide();
					
				});
			}
		}

		///////////////////////////////////////////////////////////////////////////////////////////////////

		function editPictureInfo(id_imagen){
			
			// Primero lipiamos el contenido de los inputs.
			$('div.popupWindow input[name=url]').val("");

			// Luego obtenemos de la base los datos correspondientes a la imagen.
			$.getJSON("ajax/getPhotoInfo.php", {'id': id_imagen}, function(data) {
					
					$('div.popupWindow h1').html(data['filename']);
					$('div.popupWindow input[name=url]').val(data['url']);
																																												 
			}).done(function(){

				// Y finalmente mostramos la popup window con los datos,
				// para que el usuario pueda modificalos y luego guardarlos.

				$('div.popupWindow button#saveInfo').unbind("click");
				$('div.popupWindow button#saveInfo').click(function(){ 
									
					var url = $('div.popupWindow input[name=url]').val();
										
					//alert("id_foto: " + id_foto + " | titulo: " + titulo + " | descripcion: " + descripcion);
					$.get("ajax/savePhotoInfo.php", {'id_imagen': id_imagen, 'url': url}).done(function(){					
					
						$('div.popupWindow').fadeOut();
						
					});
				});

				// Hacemos que aparezca el popup window.
				$('div.popupWindow').fadeIn();

			});				
							
		}

		///////////////////////////////////////////////////////////////////////////////////////////////////
				
		function savePhotosOrder(){
			
			order = 1;
				
			$('div#dropbox div.preview').each(function() {
																			 
					var id = $(this).attr('id');
					
					$.get("ajax/saveOrderPhotos.php", {'id': id, 'order': order});
					
					order++;
					
			});
				
			alert("El nuevo órden de las imágenes fue actualizado con éxito.");		
			window.location.reload();
		
		}			
		
		///////////////////////////////////////////////////////////////////////////////////////////////////

</script>

<?php require 'Templates/headTemplate.php'; ?>

<!-- START CONTENT -->

<table class="identification" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><h2>Im&aacute;genes del Carrusel</h2></td>
  </tr>
</table>

<table class="form" border="0" cellspacing="0" cellpadding="5">
	<tr>
  	<td style="text-align: center;">Tamaño de las imágenes: [ 1300px de ancho - 410px de alto ]</td>
  </tr>
	<tr>
  	<td style="text-align: center;">Nombre de los archivo sin acentos ni caracteres especiales.</td>
  </tr>
  <tr>
    <td>
      <div id="dropbox">
        <?php if($totalRows_fotos > 0){ ?>
          <?php while($row_fotos = mysql_fetch_assoc($fotos)){ ?>
            <div id="<?php echo $row_fotos['id_imagen']; ?>" class="preview ui-widget-content">
              <span class="imageHolder">
                <img src="../uploads/carrusel/<?php echo $row_fotos['filename']; ?>" />
                <span class="options">
                  <span class="buttons">
                    <button onClick="editPictureInfo(<?php echo $row_fotos['id_imagen']; ?>)">Editar URL</button>
                    <button onClick="deletePicture(<?php echo $row_fotos['id_imagen']; ?>);">Eliminar</button>
                  </span>
                </span>
              </span>
              <div class="progressHolder">
                <div class="progress"></div>
              </div>
            </div>
          <?php } ?>
        <?php } else { ?>
          <span class="message">Arrastra las im&aacute;genes aqu&iacute;.</span>
        <?php } ?>
      </div>
    </td>
  </tr>
</table>

<div class="popupWindow">
  <h1></h1>
  <p><label for="url">URL:</label><input type="text" size="30" name="url" id="url" /></p>
  <p><button id="cancel" onClick="$('div.popupWindow').fadeOut();">Cancelar</button><button id="saveInfo">Guardar</button></p>
</div>  

<?php /*if($totalRows_fotos > 0) { */ ?>
<table id="orderBtn" border="0" cellspacing="0" cellpadding="0"> 
	<tr>
		<td><button onClick="changeCursorToWait(); savePhotosOrder();">Guardar Orden</button></td>
	</tr>
</table>
<?php /* } */ ?>

 
<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>