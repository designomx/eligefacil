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

	// Eliminamos el usuario de la BD.
  $deleteSQL = sprintf("DELETE FROM usuarios WHERE id_usuario=%s", GetSQLValueString($_POST['id_usuario'], "int"));
	$result = mysql_query($deleteSQL, $dbConn) or die(mysql_error());
	
}

/////////////////////////////////////////////////////////////////////////////////////////////////////

mysql_select_db($database, $dbConn);

/* Obtiene de la Base todos los Usuarios */
$query_usuarios = "SELECT id_usuario, nombre, email, superusuario FROM usuarios ORDER BY id_usuario DESC";
$usuarios = mysql_query($query_usuarios, $dbConn) or die(mysql_error());
$totalRows_usuarios = mysql_num_rows($usuarios);

/////////////////////////////////////////////////////////////////////////////////////////////////////

?>

<?php require 'Templates/mainTemplate.php'; ?>

<script type="text/javascript">

	///////////////////////////////////////////////////////////////////////////////////////////////////
	
	function showPopupWindow(caller, transaccion, id_usuario){

		$email = $('div#formUsuarioWindow input[name=email]');
		$nombre = $('div#formUsuarioWindow input[name=nombre]');
		$password = $('div#formUsuarioWindow input[name=password]');
		//$id_permiso = $('div#formUsuarioWindow select[name=id_permiso]');
	
		$msg = $('td.msg');
	
		// Limpiamos el posible contenido de todos los inputs.
		allFields = $([]).add($email).add($nombre).add($password);
		//$id_permiso.prop('disabled', false);	
		
		$btn_guardarDatos = $('div#formUsuarioWindow input[name=saveData]');
		$popUpWindowTitle = $('div#formUsuarioWindow td#title');
				
		// Limpiamos el posible contenido de todos los inputs.
		allFields.val("").removeClass('error');
		$msg.html("Los campos con [*] son requeridos.").removeClass("errorMsg");
		
		
		switch(transaccion){
		
			case "INSERT":
				
				// Asignamos los valores conocidos.
				$popUpWindowTitle.html("<h2>Nuevo Usuario</h2>");
				//$id_permiso.val("2"); //como es un select, seleccionamos la primera opción
				$btn_guardarDatos.val("Guardar");
								
				// Ubicamos la ventana a la altura del elemento que ejecutó esta función.
				$('div#formUsuarioWindow').css('top', $(caller).offset().top);				
				// Mostramos la ventana.
				$('div#formUsuarioWindow').fadeIn();
				removeCursorToWait();	
						
				break;
			
			case "UPDATE":
			
				// Asignamos los valores conocidos.
				$popUpWindowTitle.html("<h2>Editar Usuario</h2>");
				$btn_guardarDatos.val("Guardar Cambios");
				
				// Obtenemos de la base los datos correspondientes del concepto.
				$.getJSON("ajax/getUsuarioData.php", {'id': id_usuario}, function(data) {
						
						$email.val(data['email']);
						$nombre.val(data['nombre']);
						$password.val(data['password']);
						
						/*$id_permiso.val(data['id_permiso']);

						if(($id_permiso.val() == 1) && (data['superusuario'] == 1)){ // Si el usuario tiene permisos de Administrador
								$id_permiso.prop('disabled', 'disabled'); 
						}
						
						$id_permiso.val(data['id_permiso']);*/
																																													 
				}).done(function(){
	
						$btn_guardarDatos.val("Guardar cambios");
						
						// Ubicamos la ventana a la altura del elemento que ejecutó esta función.
						$('div#formUsuarioWindow').css('top', $(caller).offset().top);				
						// Una vez cargados los datos, mostramos la ventana.
						$('div#formUsuarioWindow').fadeIn();
						removeCursorToWait();	
		
				}); //.done(function(){... 
			
				break;
				
		} //switch
		
		$btn_guardarDatos.unbind("click");
		$btn_guardarDatos.click(function(){
			
				changeCursorToWait();
								
				requiredFields = $([]).add($password).add($nombre).add($email);
								
				if(areRequiredFieldsFilledOut(requiredFields)){

						fieldsToValidate = [{field: $email, type: "email", label: "Email"}];
												 					
						if(areFieldsDataValid($msg, fieldsToValidate)){
							
								if(transaccion == "INSERT"){
					
										//Checamos si el email ya esta dado de alta.
										$.getJSON("ajax/checkIfEmailExists.php", {'email': $email.val()}, function(resp) {
												
												if(resp['emailExists'] == "true"){
							
													$msg.html("El email ya está dado de alta.").addClass("errorMsg");
													
													$email.addClass('error').focus();
													removeCursorToWait();
													
												} else {
																										
													$msg.html("Por favor espera, guardando datos...").removeClass("errorMsg").addClass('waitMsg');
															
													// Salvamos los datos en la base.
													$.get("ajax/saveUsuarioData.php", {'transaccion': transaccion, // Este valor fue pasado como argumento.
																													 'email': $email.val(),
																													 'nombre': $nombre.val(),
																													 'password': $password.val(),
																													 /*'id_permiso': $id_permiso.val(),*/
																													 'id_usuario': id_usuario //Este valor sólo en caso de "UPDATE" y fue pasado como argumento.
													}).done(function(){					
													
														$('div#formUsuarioWindow').fadeOut();
														location.reload();
														
													});
															
												} //else
																																																			 
										}); //$.getJSON...
								
								}// if(transaccion != "UPDATE")
								else{
								
										$msg.html("Por favor espera, guardando datos...").removeClass("errorMsg").addClass('waitMsg');
												
										// Salvamos los datos en la base.
										$.get("ajax/saveUsuarioData.php", {'transaccion': transaccion, // Este valor fue pasado como argumento.
																										 'email': $email.val(),
																										 'nombre': $nombre.val(),
																										 'password': $password.val(),
																										 /*'id_permiso': $id_permiso.val(),*/
																										 'id_usuario': id_usuario //Este valor sólo en caso de "UPDATE" y fue pasado como argumento.
										}).done(function(){					
										
											$('div#formUsuarioWindow').fadeOut();
											location.reload();
											
										});
								
								}
						
						} // if(areFieldsDataValid(...
						else { 
							
							//errorMsg = "Campos con valores no permitidos.";
							//$msg.text(errorMsg).addClass("errorMsg");
							
							removeCursorToWait();
							
						}
									
				} //if(areRequiredFieldsOk(...
				else {
					
					$msg.html("Los campos con [*] son requeridos.").addClass("errorMsg");
					
					removeCursorToWait();
				}
				
				
				
		}); //$btn_guardarDatos.click(...		
	
	} // function showPopupWindow

	///////////////////////////////////////////////////////////////////////////////////////////////////




	$(document).ready(function() {
		
		
			
	}); //$(document).ready();

</script>

<?php require 'Templates/headTemplate.php'; ?>

<!-- START CONTENT -->

<table class="identification" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><h2>Administrador de Usuarios</h2></td>
  </tr>
</table>

<table class="newItemLink" border="0" cellspacing="0" cellpadding="0">
    <td><h3>Selecciona un Usuario o da de alta uno <a href="#" onclick="showPopupWindow(this, 'INSERT');">Nuevo</a></h3></td>
  </tr>
</table>
        
<table class="itemsList" border="0" cellspacing="0" cellpadding="0"> 
  <tr class="headers nodrop nodrag">
    <td width="10%"></td>
    <td width="25%">Nombre</td>
    <td width="25%">Email</td>
    <td width="15%"></td>
    <td width="15%"></td>
  </tr>
  <?php while($row_usuarios = mysql_fetch_assoc($usuarios)){ ?>
    
  <tr>
    <td class="picture user">
    	<img src="images/icons/user.png" />
    </td>
    <td><?php echo $row_usuarios['nombre']; ?></td>
    <td><?php echo $row_usuarios['email']; ?></td>
    <td class="button">
      <!-- /////////////////////////////////////////////////////////////////////////////////////////////////// -->
      <!-- BOTON: Editar -->
    	<input type="button" id="edit" value="Editar" onclick="changeCursorToWait(); showPopupWindow(this, 'UPDATE', <?php echo $row_usuarios['id_usuario']; ?>);" />
		</td>
    <td class="button">
      <?php if($row_usuarios['superusuario'] == NULL){ ?> 
        <!-- /////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <!-- BOTON: Eliminar -->
        <form name="deleteItemForm" method="post" onsubmit="return confirm('¿Está seguro que desea eliminar a este usuario?\n\nSe eliminarán todos los registros y archivos relacionados.\n\nEsta acción es definitiva e irreversible.'); changeCursorToWait();">
          <input type="hidden" name="id_usuario" value="<?php echo $row_usuarios['id_usuario']; ?>" />
          <input type="submit" value="Eliminar" />
          <input type="hidden" name="MM_delete" value="deleteItemForm">
        </form>      
        <!-- /////////////////////////////////////////////////////////////////////////////////////////////////// -->
     <?php }//if ?> 
    </td>
  </tr>
  
  <?php }//while ?>
</table>



<div id="formUsuarioWindow" class="popUpWindow">

  <form>
                                       
      <table class="form" border="0" cellspacing="0" cellpadding="5">
        <tr>
          <td id="title" colspan="2" class="center"></td>
        </tr>
        <tr>
          <td class="msg center" colspan="2">Los campos con [*] son requeridos.</td>
        </tr>
        <tr>
          <td width="50%" class="label">Email *:</td>
          <td width="50%"><input type="text" id="email" name="email" value=""/></td>
        </tr>
        <tr>
          <td class="label">Nombre *:</td>
          <td><input type="text" id="nombre" name="nombre" value=""/></td>
        </tr>
        <tr>
          <td class="label">Contrase&ntilde;a *:</td>
          <td><input type="text" id="password" name="password" value=""/></td>
        </tr>
        <!--tr>
          <td width="20%" class="label">Permiso *:</td>
          <td width="80%">
            <select name="id_permiso" id="id_permiso" >
            < ?php while($row_permisos = mysql_fetch_assoc($permisos)){ ?>
              <option value="< ?php echo $row_permisos['id_permiso']; ?>">< ?php echo $row_permisos['nombre']; ?></option>
            < ?php } ?> 
            </select>       
          </td>
        </tr-->
      </table>
      
      <table class="buttons" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="50%" class="button">
            <input type="button" value="Cancelar" onClick="$('div#formUsuarioWindow').fadeOut();" />
          </td>
          <td width="50%" class="button"><input id="saveData" name="saveData" type="button" value=""></td>
        </tr>
      </table>
  
  </form>

</div><!-- #formUsuarioWindow -->        



 
<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>