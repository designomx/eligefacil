<?php 
session_start();

if(!isset($_SESSION["email"])){
	
	header("Location: ../index.php");
	exit;
		
}

require 'Templates/phpHeadingTemplate.php'; 

/////////////////////////////////////////////////////////////////////////////////////////////////////

if ((isset($_POST["MM_delete"])) && ($_POST["MM_delete"] == "deleteItemForm")) {
	
	include('phpTools/utilities.php');

	//Primero eliminamos los archivos del celular, del servidor.
	rrmdir("../uploads/celulares_mas_populares/" . $_POST['id_celular']);
	
	mysql_select_db($database, $dbConn);


	// Eliminamos el celular de todos los planes que lo tienen asignado.
  $deleteSQL = sprintf("DELETE FROM planes_celulares WHERE id_celular=%s", GetSQLValueString($_POST['id_celular'], "int"));
	$result = mysql_query($deleteSQL, $dbConn) or die(mysql_error());

	// Eliminamos el celular de la bd.
  $deleteSQL = sprintf("DELETE FROM celularesMasPopulares WHERE id_celular=%s", GetSQLValueString($_POST['id_celular'], "int"));
	$result = mysql_query($deleteSQL, $dbConn) or die(mysql_error());
	
}

/////////////////////////////////////////////////////////////////////////////////////////////////////

mysql_select_db($database, $dbConn);

/* Obtiene de la Base todas las empresas */
$query_celulares = "SELECT * FROM celularesMasPopulares ORDER BY id_celular DESC";
$celulares = mysql_query($query_celulares, $dbConn) or die(mysql_error());
$totalRows_celulares = mysql_num_rows($celulares);

/////////////////////////////////////////////////////////////////////////////////////////////////////

?>

<?php require 'Templates/mainTemplate.php'; ?>

<script type="text/javascript" charset="utf-8" src="../JQuery/jquery.redirect.js"></script>

<script type="text/javascript">

	///////////////////////////////////////////////////////////////////////////////////////////////////
	
	function showPopupWindow(caller, transaccion, id_celular){

		// hiddens 
		$("div#formCelularWindow input[name=transaccion]").val(transaccion);
		$foto_actual = $('div#formCelularWindow input[name=foto_actual]');
		$id_celular = $('div#formCelularWindow input[name=id_celular]');

		$nombre = $('div#formCelularWindow input[name=nombre]');
		$foto = $('div#formCelularWindow input[name=foto]');
		$labelFoto = $('div#formCelularWindow .label.foto');
		$currentPic = $('div#formCelularWindow td.currentPic');
		$currentFotoContainer = $('div#formCelularWindow tr#currentFotoContainer');
	
	
		$msg = $('td.msg');
	
		// Limpiamos el posible contenido de todos los inputs.
		allFields = $([]).add($nombre).add($foto).add($foto_actual);
		
		$currentPic.empty();
		
		$btn_guardarDatos = $('div#formCelularWindow input[name=saveData]');
		$popUpWindowTitle = $('div#formCelularWindow td#title');
				
		// Limpiamos el posible contenido de todos los inputs.
		allFields.val("").removeClass('error');
		$msg.html("Los campos con [*] son requeridos.").removeClass("error");
		
		switch(transaccion){
		
			case "INSERT":
				
				// Asignamos los valores conocidos.
				$popUpWindowTitle.html("<h2>Nuevo Celular</h2>");
				//$id_permiso.val("2"); //como es un select, seleccionamos la primera opción
				$btn_guardarDatos.val("Guardar");
				$currentFotoContainer.hide();
				$labelFoto.html("Foto:");
					
				// Ubicamos la ventana a la altura del elemento que ejecutó esta función.
				$('div#formCelularWindow').css('top', $(caller).offset().top);				
				// Mostramos la ventana.
				$('div#formCelularWindow').fadeIn();
				removeCursorToWait();	
						
				break;
			
			case "UPDATE":
			
				// Asignamos los valores conocidos.
				$popUpWindowTitle.html("<h2>Editar Celular</h2>");
				$btn_guardarDatos.val("Guardar Cambios");
				
				// Obtenemos de la base los datos correspondientes del concepto.
				$.getJSON("ajax/getCelularData.php", {'id': id_celular}, function(data) {

						$("div#formCelularWindow input[name=id_celular]").val(id_celular);

						// hiddens
						$foto_actual.val(data['foto']);
						$id_celular.val(id_celular);
						
						$nombre.val(data['nombre']);
						$currentPic.append($('<img>').attr("src", "../uploads/celulares_mas_populares/" + id_celular + "/" + data['foto']));
						$currentFotoContainer.show();
						$labelFoto.html("Nueva Foto:");
																																																			 
				}).done(function(){
	
						$btn_guardarDatos.val("Guardar cambios");
						
						// Ubicamos la ventana a la altura del elemento que ejecutó esta función.
						$('div#formCelularWindow').css('top', $(caller).offset().top);				
						// Una vez cargados los datos, mostramos la ventana.
						$('div#formCelularWindow').fadeIn();
						removeCursorToWait();	
		
				}); //.done(function(){... 
			
				break;
				
		} //switch
	
	} // function showPopupWindow

	///////////////////////////////////////////////////////////////////////////////////////////////////

	function IsFormDataValid(){
		
			var allDataValid = false;
	
			requiredFields = $([]).add($nombre);
							
			if(areRequiredFieldsFilledOut(requiredFields)){

					fieldsToValidate = [{field: $foto, type: "image", label: "Foto"}];
																
					if(areFieldsDataValid($msg, fieldsToValidate)){
											
							$msg.html("Por favor espera, guardando datos...").removeClass("errorMsg").addClass('waitMsg');
																												
							allDataValid = true;
													
					} // if(areFieldsDataValid(...
					else { 

						allDataValid = false;
						
						//errorMsg = "Campos con valores no permitidos.";
						//$msg.text(errorMsg).addClass("errorMsg");
						
						removeCursorToWait();
						
					}
								
			} //if(areRequiredFieldsOk(...
			else {
				
				allDataValid = false;
				
				$msg.html("Los campos con [*] son requeridos.").addClass("error");
				
				removeCursorToWait();
			}
	
			return allDataValid;
			
	} //funcion(isFormDataValid(...

	///////////////////////////////////////////////////////////////////////////////////////////////////

	$(document).ready(function() {
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		
		$('#frm_celular').on('submit',(function(e) {
		
				e.preventDefault();
	
				$msg = $('td.msg');
				
				changeCursorToWait();
				
				if(IsFormDataValid()){
								
					$.ajax({
						url: "ajax/saveCelularData.php", 		// Url to which the request is send
						type: "POST",             			// Type of request to be send, called as method
						data: new FormData(this), 			// Data sent to server, a set of key/value pairs (i.e. form fields and values)
						contentType: false,       			// The content type used when sending data to the server.
						cache: false,             			// To unable request pages to be cached
						processData: false,       			// To send DOMDocument or non processed data file it is set to false
						success: function(data)   			// A function to be called if request succeeds
						{
							removeCursorToWait();
							$('div#formCelularWindow').fadeOut();
							location.reload();						
							//$msg.html(data);
						}
					});
				
				}
			
		}));
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		
		
			
	}); //$(document).ready();

</script>

<?php require 'Templates/headTemplate.php'; ?>

<!-- START CONTENT -->

<table class="identification" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><h2>Administrador de Celulares M&aacute;s Populares</h2></td>
  </tr>
</table>

<table class="newItemLink" border="0" cellspacing="0" cellpadding="0">
    <td><h3>Selecciona un Celular o da de alta uno <a href="#" onclick="showPopupWindow(this, 'INSERT');">Nuevo</a></h3></td>
  </tr>
</table>
        
<table class="itemsList" border="0" cellspacing="0" cellpadding="0"> 
  <tr class="headers nodrop nodrag">
    <td width="10%">Foto</td>
    <td width="50%">Nombre</td>
    <td width="20%"></td>
    <td width="20%"></td>
  </tr>
  <?php while($row_celulares = mysql_fetch_assoc($celulares)){ ?>
    
  <tr>
    <td class="picture"><img src="../uploads/celulares_mas_populares/<?php echo $row_celulares['id_celular'] . "/" . $row_celulares['foto'] ; ?>" /></td>
    <td><?php echo $row_celulares['nombre']; ?></td>
    <td class="button"><input type="button" id="edit" value="Editar" onclick="changeCursorToWait(); showPopupWindow(this, 'UPDATE', <?php echo $row_celulares['id_celular']; ?>);" /></td>
    <td class="button">
      <form name="deleteItemForm" method="post" onsubmit="return confirm('¿Está seguro que desea eliminar este Celular?\n\nSe eliminará, también, de todos los planes que lo tengan asignado.\n\nEsta acción es irreversible.'); changeCursorToWait();">
        <input type="hidden" name="id_celular" value="<?php echo $row_celulares['id_celular']; ?>" />
        <input type="submit" value="Eliminar" />
        <input type="hidden" name="MM_delete" value="deleteItemForm">
      </form>      
    </td>
  </tr>
  
  <?php }//while ?>
</table>



<div id="formCelularWindow" class="popUpWindow">

  <form id="frm_celular" action="" method="post" enctype="multipart/form-data">
             
  		<input type="hidden" name="transaccion" id="transaccion" value="">
      <input type="hidden" name="id_celular" id="id_celular" value=""><!-- Para UPDATE -->
      <input type="hidden" name="foto_actual" id="foto_actual" value=""><!-- Para UPDATE -->
                                       
      <table class="form" border="0" cellspacing="0" cellpadding="5">
        <tr>
          <td id="title" colspan="2" class="center"></td>
        </tr>
        <tr>
          <td class="msg center" colspan="2">Los campos con [*] son requeridos.</td>
        </tr>
        <tr>
          <td width="50%" class="label">Nombre *:</td>
          <td width="50%"><input type="text" id="nombre" name="nombre" value=""/></td>
        </tr>
        <tr id="currentFotoContainer">
          <td class="label">Foto Actual:</td>
          <td class="currentPic"></td>
        </tr>
        <tr>
          <td class="label foto"></td>
          <td><input type="file" id="foto" name="foto" value=""/></td>
        </tr>
        <tr>
          <td></td>
          <td>Ancho máximo: 120px. <br />Cualquier altura.</td>
        </tr>
      </table>
      
      <table class="buttons" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="50%" class="button">
            <input type="button" value="Cancelar" onClick="$('div#formCelularWindow').fadeOut();" />
          </td>
          <td width="50%" class="button"><input id="saveData" name="saveData" type="submit" value=""></td>
        </tr>
      </table>
  
  </form>

</div><!-- #formUsuarioWindow -->        



 
<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>