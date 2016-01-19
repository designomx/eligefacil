<?php 
require 'Templates/mainTemplate.php'; ?>

<script type="text/javascript">

	$(document).ready(function() {
		
			// Limpiamos los campos de la forma de login.
			$("div#form-login #email, div#form-login #password").val("");
			$("div#form-login #msgs p").html("");
		
			$('div#form-login input[name=email], div#form-login input[name=password]').keypress(function(event){
																																												 
					var keycode = (event.keyCode ? event.keyCode : event.which);
					if(keycode == '13'){ //dieron Enter
							$('div#form-login input#signin').click();
					}
					
			});
			
			$('div#form-login input#signin').click(function(){
				
					changeCursorToWait();
				
					var $email = $('div#form-login #email');
					var	$password = $('div#form-login #password');
					var	allFields = $([]).add(email).add(password);
					var	$msgs = $('div#form-login .msgs p');
					var bValid = true;
			
					//alert("email=" + email.val() + " - password=" + password.val());
		
					allFields.removeClass("error");
					$msgs.html(""); //limpiamos los mensajes.			
		
					////////////////////////////////////////////////////////////////////
					// Checamos si los campos no están vacíos.
					// El órden en que están puestos debe ser el contrario al que aparecen en el formulario,
					// ésto para que el focus se posicione en el primer input vacío.
					
					if($password.val() <= 0){
						$password.addClass("error").focus();
						bValid = false;
					}
					
					if($email.val() <= 0){
						$email.addClass("error").focus();
						bValid = false;
					}
														
					if(bValid){
						
						////////////////////////////////////////////////////////////////////
						// Si todos los campos fueron válidos, enviamos solictud validacion.
										
						var data = "email=" + $email.val();
								data += "&password=" + $password.val();							
													
						$.ajax({
							 type: "POST",
							 dataType: "json",
							 url: "ajax/validateUser.php",
							 data: data,
							 success: function(response){
								 
									if(response['validUser'] == "true"){
			
										window.location = response['page'];
										//updateMsg(msgs, "sucess", "¡Usuario conectado!");
									
									} else {
									
										updateMsg($msgs, "error", "Email o password incorrecto. Por favor, intente de nuevo");
									
									}
																												
							 },
							 error: function(errormessage) {
								 
									//alert(errormessage);
									updateMsg($msgs, "error", "Error de conexi&oacute;n. Por favor, intente m&aacute;s tarde.");
							 }
							 
						}); // $.ajax
						
						////////////////////////////////////////////////////////////////////
													
					} // if(bValid)
					else {
						
						updateMsg($msgs, "error", "Por favor, ingrese su email y password.");
					}
					
					removeCursorToWait()
			
			}); // button#signin.click();				
		
			
	}); //$(document).ready();

</script>

<?php require 'Templates/headLoginTemplate.php'; ?>

<!-- START CONTENT -->

  <div id="form-login" >
      <h2 class="green-transp">Log in</h2>
      <div class="form-group">
        <label for="email" class="">Email:</label>
        <input type="text" name="email" id="email" class="form-input" />
        <div class="clearfix"></div>
        <label for="password" class="">Password:</label>
        <input type="password" name="password" id="password" class="form-input" />
        <div class="clearfix"></div>
      </div>
      <input type="submit" name="signin" id="signin" value="ENTRAR" class="form-button" />
      <div class="msgs"><p></p></div>
  </div>  
 
<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>