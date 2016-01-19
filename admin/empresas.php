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
  $deleteSQL = sprintf("DELETE FROM empresas WHERE id_empresa=%s", GetSQLValueString($_POST['id_empresa'], "int"));
	$result = mysql_query($deleteSQL, $dbConn) or die(mysql_error());
	
}

/////////////////////////////////////////////////////////////////////////////////////////////////////

mysql_select_db($database, $dbConn);

/* Obtiene de la Base todas las empresas */
$query_empresas = "SELECT id_empresa, nombre, codigo_color, (select count(*) from planes where planes.id_empresa = empresas.id_empresa) as num_planes FROM empresas ORDER BY id_empresa DESC";
$empresas = mysql_query($query_empresas, $dbConn) or die(mysql_error());
$totalRows_empresas = mysql_num_rows($empresas);

/////////////////////////////////////////////////////////////////////////////////////////////////////

?>

<?php require 'Templates/mainTemplate.php'; ?>

<script type="text/javascript" charset="utf-8" src="../JQuery/jquery.redirect.js"></script>

<script type="text/javascript" charset="utf-8" src="../JQuery/spectrum-1.7.1/spectrum.js"></script>
<link rel="stylesheet" type="text/css" href="../JQuery/spectrum-1.7.1/spectrum.css" />

<script type="text/javascript">

	///////////////////////////////////////////////////////////////////////////////////////////////////
	
	function showPopupWindow(caller, transaccion, id_empresa){

		$("div#formEmpresaWindow input[name=transaccion]").val(transaccion);

		$nombre = $('div#formEmpresaWindow input[name=nombre]');
		$codigo_color = $('div#formEmpresaWindow input[name=codigo_color]');
	
		$msg = $('td.msg');
	
		// Limpiamos el posible contenido de todos los inputs.
		allFields = $([]).add($nombre).add($codigo_color);
		
		$btn_guardarDatos = $('div#formEmpresaWindow input[name=saveData]');
		$popUpWindowTitle = $('div#formEmpresaWindow td#title');
				
		// Limpiamos el posible contenido de todos los inputs.
		allFields.val("").removeClass('inputDataMissing');
		$msg.html("Los campos con [*] son requeridos.").removeClass("errorMsg");
		
		switch(transaccion){
		
			case "INSERT":
				
				// Asignamos los valores conocidos.
				$popUpWindowTitle.html("<h2>Nueva Empresa</h2>");
				//$id_permiso.val("2"); //como es un select, seleccionamos la primera opción
				$btn_guardarDatos.val("Guardar");
								
				// Ubicamos la ventana a la altura del elemento que ejecutó esta función.
				$('div#formEmpresaWindow').css('top', $(caller).offset().top);				
				// Mostramos la ventana.
				$('div#formEmpresaWindow').fadeIn();
				removeCursorToWait();	
						
				break;
			
			case "UPDATE":
			
				// Asignamos los valores conocidos.
				$popUpWindowTitle.html("<h2>Editar Empresa</h2>");
				$btn_guardarDatos.val("Guardar Cambios");
				
				// Obtenemos de la base los datos correspondientes del concepto.
				$.getJSON("ajax/getEmpresaData.php", {'id': id_empresa}, function(data) {

						$("div#formEmpresaWindow input[name=id_empresa]").val(id_empresa);
						
						$nombre.val(data['nombre']);
						$codigo_color.val(data['codigo_color']);
																																																			 
				}).done(function(){
	
						$btn_guardarDatos.val("Guardar cambios");
						
						// Ubicamos la ventana a la altura del elemento que ejecutó esta función.
						$('div#formEmpresaWindow').css('top', $(caller).offset().top);				
						// Una vez cargados los datos, mostramos la ventana.
						$('div#formEmpresaWindow').fadeIn();
						removeCursorToWait();	
		
				}); //.done(function(){... 
			
				break;
				
		} //switch
	
	} // function showPopupWindow

	///////////////////////////////////////////////////////////////////////////////////////////////////

	function IsFormDataValid(){
		
			var allDataValid = false;
	
			requiredFields = $([]).add($nombre).add($codigo_color);
							
			if(areRequiredFieldsFilledOut(requiredFields)){

					//fieldsToValidate = [{field: $precio, type: "numeric", label: "Precio"}];
																
					//if(areFieldsDataValid($msg, fieldsToValidate)){
											
							$msg.html("Por favor espera, guardando datos...").removeClass("errorMsg").addClass('waitMsg');
																												
							allDataValid = true;
													
					/*} // if(areFieldsDataValid(...
					else { 

						allDataValid = false;
						
						//errorMsg = "Campos con valores no permitidos.";
						//$msg.text(errorMsg).addClass("errorMsg");
						
						removeCursorToWait();
						
					}*/
								
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
		
		$('#frm_empresa').on('submit',(function(e) {
		
				e.preventDefault();
	
				$msg = $('td.msg');
				
				changeCursorToWait();
				
				if(IsFormDataValid()){
								
					$.ajax({
						url: "ajax/saveEmpresaData.php", 		// Url to which the request is send
						type: "POST",             			// Type of request to be send, called as method
						data: new FormData(this), 			// Data sent to server, a set of key/value pairs (i.e. form fields and values)
						contentType: false,       			// The content type used when sending data to the server.
						cache: false,             			// To unable request pages to be cached
						processData: false,       			// To send DOMDocument or non processed data file it is set to false
						success: function(data)   			// A function to be called if request succeeds
						{
							removeCursorToWait();
							$('div#formEmpresaWindow').fadeOut();
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
    <td><h2>Administrador de Empresas</h2></td>
  </tr>
</table>

<table class="newItemLink" border="0" cellspacing="0" cellpadding="0">
    <td><h3>Selecciona una Empresa o da de alta una <a href="#" onclick="showPopupWindow(this, 'INSERT');">Nueva</a></h3></td>
  </tr>
</table>
        
<table class="itemsList" border="0" cellspacing="0" cellpadding="0"> 
  <tr class="headers nodrop nodrag">
    <td width="10%">Color</td>
    <td width="50%">Nombre</td>
    <td width="20%"></td>
    <td width="20%" class="nota">S&oacute;lo se pueden eliminar las empresas que no tienen planes asociados.</td>
  </tr>
  <?php while($row_empresas = mysql_fetch_assoc($empresas)){ ?>
    
  <tr>
    <td style="background-color: <?php echo $row_empresas['codigo_color']; ?>"></td>
		<td><?php echo $row_empresas['nombre']; ?></td>
    <!--td>< ?php echo $row_empresas['codigo_color']; ? ></td-->
    <td class="button"><input type="button" id="edit" value="Editar" onclick="changeCursorToWait(); showPopupWindow(this, 'UPDATE', <?php echo $row_empresas['id_empresa']; ?>);" /></td>
    <td class="button">
    <?php if($row_empresas['num_planes'] == 0){ ?>
      <form name="deleteItemForm" method="post" onsubmit="return confirm('¿Está seguro que desea eliminar esta Empresa?\n\nSe eliminarán todos los registros y archivos relacionados.\n\nEsta acción es irreversible.'); changeCursorToWait();">
        <input type="hidden" name="id_empresa" value="<?php echo $row_empresas['id_empresa']; ?>" />
        <input type="submit" value="Eliminar" />
        <input type="hidden" name="MM_delete" value="deleteItemForm">
      </form>
    <?php } ?>        
    </td>
  </tr>
  
  <?php }//while ?>
</table>



<div id="formEmpresaWindow" class="popUpWindow">

  <form id="frm_empresa" action="" method="post">
             
  		<input type="hidden" name="transaccion" id="transaccion" value="">
      <input type="hidden" name="id_empresa" id="id_empresa" value=""><!-- Para UPDATE -->
                                       
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
        <tr>
          <td class="label">Código color *:</td>
          <td><input type="color" id="codigo_color" name="codigo_color" value=""/></td>
        </tr>
      </table>
      
      <table class="buttons" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="50%" class="button">
            <input type="button" value="Cancelar" onClick="$('div#formEmpresaWindow').fadeOut();" />
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