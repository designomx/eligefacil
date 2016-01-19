<?php 
session_start();

if(!isset($_SESSION["email"])){
	
	header("Location: ../index.php");
	exit;
		
}

require 'Templates/phpHeadingTemplate.php'; 

/////////////////////////////////////////////////////////////////////////////////////////////////////

if ((isset($_POST["MM_delete"])) && ($_POST["MM_delete"] == "deleteItemForm")) {
		
	mysql_select_db($database, $dbConn);

	// Eliminamos la empresa de la bd.
  $deleteSQL = sprintf("DELETE FROM paquetes_ott WHERE id_paquete=%s", GetSQLValueString($_POST['id_paquete'], "int"));
	$result = mysql_query($deleteSQL, $dbConn) or die(mysql_error());
	
}

/////////////////////////////////////////////////////////////////////////////////////////////////////

mysql_select_db($database, $dbConn);

/* Obtiene de la Base todas las empresas */
$query_paquetes_ott = "SELECT id_paquete, nombre, id_empresa, (select nombre from empresas_ott where empresas_ott.id_empresa = paquetes_ott.id_empresa) as empresa, precio FROM paquetes_ott ORDER BY id_paquete DESC";
$paquetes_ott = mysql_query($query_paquetes_ott, $dbConn) or die(mysql_error());
$totalRows_paquetes_ott = mysql_num_rows($paquetes_ott);

/* Obtiene el catálogo de empresas ott */
$query_empresas_ott = "SELECT * FROM empresas_ott ORDER BY id_empresa ASC";
$empresas_ott = mysql_query($query_empresas_ott, $dbConn) or die(mysql_error());

/////////////////////////////////////////////////////////////////////////////////////////////////////

?> 

<?php require 'Templates/mainTemplate.php'; ?>

<script src="//cdn.ckeditor.com/4.5.4/standard/ckeditor.js"></script>
<script src="//cdn-source.ckeditor.com/4.5.4/standard/adapters/jquery.js"></script>

<script type="text/javascript" charset="utf-8" src="../JQuery/jquery.redirect.js"></script>

<script type="text/javascript">

	///////////////////////////////////////////////////////////////////////////////////////////////////
	
	function showPopupWindow(caller, transaccion, id_paquete){

		// hiddens 
		$("div#formPaqueteWindow input[name=transaccion]").val(transaccion);
		$id_paquete = $('div#formPaqueteWindow input[name=id_paquete]');

		$nombre = $('div#formPaqueteWindow input[name=nombre]');
		$id_empresa = $('div#formPaqueteWindow select[name=id_empresa]');
		$precio = $('div#formPaqueteWindow input[name=precio]');
		$dato_principal_1 = $('div#formPaqueteWindow input[name=dato_principal_1]');
		$dato_principal_2 = $('div#formPaqueteWindow input[name=dato_principal_2]');
		$dato_principal_3 = $('div#formPaqueteWindow input[name=dato_principal_3]');
		$dato_principal_4 = $('div#formPaqueteWindow input[name=dato_principal_4]');
		
		$mas_datos = $('div#formPaqueteWindow input[name=mas_datos]');
		$mas_datos_aux = $('div#formPaqueteWindow textarea[name=mas_datos_aux]');
	
		$msg = $('td.msg');
	
		// Limpiamos el posible contenido de todos los inputs.
		allFields = $([]).add($nombre).add($id_empresa).add($precio).add($dato_principal_1).add($dato_principal_2).add($dato_principal_3).add($dato_principal_4).add($mas_datos);
				
		$btn_guardarDatos = $('div#formPaqueteWindow input[name=saveData]');
		$popUpWindowTitle = $('div#formPaqueteWindow td#title');
				
		// Limpiamos el posible contenido de todos los inputs.
		allFields.val("").removeClass('error');
		$mas_datos_aux.val("");
		$msg.html("Los campos con [*] son requeridos.").removeClass("errorMsg");
		
		switch(transaccion){
		
			case "INSERT":
				
				// Asignamos los valores conocidos.
				$popUpWindowTitle.html("<h2>Nuevo Paquete OTT</h2>");
				$id_empresa.val("1"); //como es un select, seleccionamos la primera opción
				$btn_guardarDatos.val("Guardar");
								
				// Ubicamos la ventana a la altura del elemento que ejecutó esta función.
				$('div#formPaqueteWindow').css('top', $(caller).offset().top);				
				// Mostramos la ventana.
				$('div#formPaqueteWindow').fadeIn();
				removeCursorToWait();	
						
				break;
			
			case "UPDATE":
			
				// Asignamos los valores conocidos.
				$popUpWindowTitle.html("<h2>Editar Paquete OTT</h2>");
				$btn_guardarDatos.val("Guardar Cambios");
				
				// Obtenemos de la base los datos correspondientes del concepto.
				$.getJSON("ajax/getPaqueteOTTData.php", {'id': id_paquete}, function(data) {

						$("div#formPaqueteWindow input[name=id_paquete]").val(id_paquete);

						// hiddens
						$id_paquete.val(id_paquete);
						
						$nombre.val(data['nombre']);
						$id_empresa.val(data['id_empresa']);
						$precio.val(data['precio']);
						$dato_principal_1.val(data['dato_principal_1']);
						$dato_principal_2.val(data['dato_principal_2']);
						$dato_principal_3.val(data['dato_principal_3']);
						$dato_principal_4.val(data['dato_principal_4']);
						$mas_datos.val(data['mas_datos']);
						$mas_datos_aux.val(data['mas_datos']);
																																																									 
				}).done(function(){
	
						$btn_guardarDatos.val("Guardar cambios");
						
						// Ubicamos la ventana a la altura del elemento que ejecutó esta función.
						$('div#formPaqueteWindow').css('top', $(caller).offset().top);				
						// Una vez cargados los datos, mostramos la ventana.
						$('div#formPaqueteWindow').fadeIn();
						removeCursorToWait();	
		
				}); //.done(function(){... 
			
				break;
				
		} //switch
			
	} // function showPopupWindow

	///////////////////////////////////////////////////////////////////////////////////////////////////

	function IsFormDataValid(){
		
			var allDataValid = false;
	
			requiredFields = $([]).add($nombre).add($precio);
							
			if(areRequiredFieldsFilledOut(requiredFields)){

					fieldsToValidate = [{field: $precio, type: "numeric", label: "Precio"}];
																
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
				
				$msg.html("Los campos con [*] son requeridos.").addClass("errorMsg");
				
				removeCursorToWait();
			}
	
			return allDataValid;
			
	} //funcion(isFormDataValid(...

	///////////////////////////////////////////////////////////////////////////////////////////////////

	$(document).ready(function() {
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		
		$('#frm_paquete').on('submit',(function(e) {
		
				e.preventDefault();
	
				$msg = $('td.msg');
				
				changeCursorToWait();
				
				if(IsFormDataValid()){
					
					//Antes de enviar el post del formulario, hacemos el paso de datos del campo mas_datos_aux a mas_datos (qué es el que finalmente se insertará en la base de datos).
					$mas_datos.val($mas_datos_aux.val()); 
													
					$.ajax({
						url: "ajax/savePaqueteOTTData.php", 		// Url to which the request is send
						type: "POST",             			// Type of request to be send, called as method
						data: new FormData(this), 			// Data sent to server, a set of key/value pairs (i.e. form fields and values)
						contentType: false,       			// The content type used when sending data to the server.
						cache: false,             			// To unable request pages to be cached
						processData: false,       			// To send DOMDocument or non processed data file it is set to false
						success: function(data)   			// A function to be called if request succeeds
						{
							removeCursorToWait();
							$('div#formPaqueteWindow').fadeOut();
							location.reload();						
							//$msg.html(data);
						}
					});
				
				}
			
		}));
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		
		// Construimos el editor wysiwyg sobre el textarea auxiliar.		
		$('#mas_datos_aux').ckeditor();
			
	}); //$(document).ready();

</script>

<?php require 'Templates/headTemplate.php'; ?>

<!-- START CONTENT -->

<table class="identification" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><h2>Administrador de Paquetes OTT</h2></td>
  </tr>
</table>

<table class="newItemLink" border="0" cellspacing="0" cellpadding="0">
    <td><h3>Selecciona un Paquete o da de alta uno <a href="#" onclick="showPopupWindow(this, 'INSERT');">Nuevo</a></h3></td>
  </tr>
</table>
        
<table class="itemsList" border="0" cellspacing="0" cellpadding="0"> 
  <tr class="headers nodrop nodrag">
    <td width="20%">Nombre</td>
    <td width="20%">Precio</td>
    <td width="20%">Empresa</td>
    <td width="20%"></td>
    <td width="20%"></td>
  </tr>
  <?php while($row_paquetes_ott = mysql_fetch_assoc($paquetes_ott)){ ?>
    
  <tr>
    <td><?php echo $row_paquetes_ott['nombre']; ?></td>
    <td>$<?php echo $row_paquetes_ott['precio']; ?></td>
    <td><?php echo $row_paquetes_ott['empresa']; ?></td>
    <td class="button"><input type="button" id="edit" value="Editar" onclick="changeCursorToWait(); showPopupWindow(this, 'UPDATE', <?php echo $row_paquetes_ott['id_paquete']; ?>);" /></td>
    <td class="button">
      <form name="deleteItemForm" method="post" onsubmit="return confirm('¿Está seguro que desea eliminar este Celular?\n\nEsta acción es irreversible.'); changeCursorToWait();">
        <input type="hidden" name="id_paquete" value="<?php echo $row_paquetes_ott['id_paquete']; ?>" />
        <input type="submit" value="Eliminar" />
        <input type="hidden" name="MM_delete" value="deleteItemForm">
      </form>      
    </td>
  </tr>
  
  <?php }//while ?>
</table>



<div id="formPaqueteWindow" class="popUpWindow">

  <form id="frm_paquete" action="" method="post" enctype="multipart/form-data">
             
  		<input type="hidden" name="transaccion" id="transaccion" value="" />
      <input type="hidden" name="id_paquete" id="id_paquete" value="" /><!-- Para UPDATE -->
      <input type="hidden" name="mas_datos" id="mas_datos" value="" /><!-- El contenido de este input es el que se insertará a la base de datos "mas_datos_aux" sirve de auxiliar para el manejo de ckeditor -->
                                       
      <table class="form" border="0" cellspacing="0" cellpadding="5">
        <tr>
          <td id="title" colspan="2" class="center"></td>
        </tr>
        <tr>
          <td class="msg center" colspan="2">Los campos con [*] son requeridos.</td>
        </tr>
        <tr>
          <td width="25%" class="label">Nombre *:</td>
          <td width="75%"><input type="text" id="nombre" name="nombre" value=""/></td>
        </tr>
        <tr>
          <td class="label">Empresa *:</td>
          <td>
						<select name="id_empresa" id="id_empresa" >
            <?php while($row_empresas_ott = mysql_fetch_assoc($empresas_ott)){ ?>
              <option value="<?php echo $row_empresas_ott['id_empresa']; ?>"><?php echo $row_empresas_ott['nombre']; ?></option>
            <?php } ?> 
            </select>        
          </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td class="label">Precio *:</td>
          <td><input type="text" id="precio" name="precio" value=""/></td>
        </tr>
        <tr>
          <td class="label">Dato principal 1:</td>
          <td><input type="text" id="dato_principal_1" name="dato_principal_1" value=""/></td>
        </tr>
        <tr>
          <td class="label">Dato principal 2:</td>
          <td><input type="text" id="dato_principal_2" name="dato_principal_2" value=""/></td>
        </tr>
        <tr>
          <td class="label">Dato principal 3:</td>
          <td><input type="text" id="dato_principal_3" name="dato_principal_3" value=""/></td>
        </tr>
        <tr>
          <td class="label">Dato principal 4:</td>
          <td><input type="text" id="dato_principal_4" name="dato_principal_4" value=""/></td>
        </tr>
        <tr>
        	<td class="label">M&aacute;s datos:</td>
        	<td><textarea id="mas_datos_aux" name="mas_datos_aux"></textarea></td>
        </tr>
      </table>
      
      <table class="buttons" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="50%" class="button">
            <input type="button" value="Cancelar" onClick="$('div#formPaqueteWindow').fadeOut();" />
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