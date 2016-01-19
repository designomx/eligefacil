<?php 
require 'Templates/phpHeadingTemplate.php';

mysql_select_db($database, $dbConn);

/* Obtiene todas las imágenes para el carrusel */
$query_imagenesCarrusel = "SELECT * FROM imagenesCarrusel ORDER BY orden ASC";
$imagenesCarrusel = mysql_query($query_imagenesCarrusel, $dbConn) or die(mysql_error());
$totalRows_imagenesCarrusel = mysql_num_rows($imagenesCarrusel);

require 'Templates/mainTemplate.php'; ?>

<script src="JQuery/flexslider-2.2/jquery.flexslider.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="JQuery/flexslider-2.2/flexslider.css" />
<script type="text/javascript" charset="utf-8" src="JQuery/utilities.js"></script>


<script type="text/javascript">

	$(document).ready(function() {
		
		$('div#contactanos').backstretch("images/contactanos.jpg");
		
		$('.flexslider').flexslider({
				animation: "slide",
				controlNav: true,
				directionNav: true,
				animationLoop: true,
				slideshow: true,
				prevText: "",
				nextText: "",
				itemWidth: "100%",
				itemMargin: 1,
				minItems: 1,
				maxItems: 1,
				move: 0
		});
				
				
		$("div#formulario input[name=enviar]").click(function(){
		
			changeCursorToWait();
			
			var isAllValid = true;
		
			$nombre = $('div#formulario input[name=nombre]');
			$email = $('div#formulario input[name=email]');
			$estado = $('div#formulario select[name=estado]');
			$comentario = $('div#formulario textarea[name=comentario]');
			
			$msgs = $('div#formulario td.msg');
							
			requiredFields = $([]).add($nombre).add($email).add($comentario);
							
			if(areRequiredFieldsFilledOut(requiredFields)){
	
					fieldsToValidate = [{field: $email, type: "email", label: "EMAIL"}];
																
					if(areFieldsDataValid($msgs, fieldsToValidate)){
						
							msg = "Por favor espera, enviando datos...";
							$msgs.html(msg).removeClass("error").addClass('success');													
						
							//////////////////////////////////////////////////////////////////////////////////
							
								var data = "&nombre=" + $nombre.val();
										data += "&email=" + $email.val();
										data += "&estado=" + $estado.val();
										data += "&comentario=" + $comentario.val();
								
								//Enviamos el correo y mostramos mensaje de éxito en el registro.
								$.ajax({
									 type: "POST",
									 url: "ajax/sendContactMail.php",
									 data: data,
									 success: function(phpReturnResult){
			
											//alert(phpReturnResult);
												
											$msgs.html("Muchas gracias por ponerte en contacto con nosotros, tan pronto nos sea posible, atenderemos tu solicitud.");	
											
											removeCursorToWait();
				
									 },
			
									 error: function(errormessage) {
												//alert('Sendmail failed possibly php script: ' + errormessage);
												//alert("Su registro no pudo ser completado en este momento enviado en este momento, por favor, intente más tarde.");
									 }
			
								}); //$.ajax
							
							//////////////////////////////////////////////////////////////////////////////////
											
					} // if(areFieldsDataValid(...
					else { 
											
						removeCursorToWait();
						
					}
								
			} //if(areRequiredFieldsOk(...
			else {
				
				errorMsg = "Los campos con [*] son requeridos.";
				$msgs.html(errorMsg).addClass("error");
				
				removeCursorToWait();
				
			}
								
		}); //$("div#formulario input[name=continuar]").click(...
	
		// Desplazamos la página hasta la barra rápida de filtrado.
		$('html, body').animate({scrollTop: $("div#header").height() - $("div#quick-filter-bar").height()}, 2000);					
							
	}); //$(document).ready(); 

</script>

<?php require 'Templates/headTemplate.php'; ?>

<?php

/* Obtiene todos los estados */
$query_estados_todos = "SELECT * FROM estados ORDER BY nombre ASC";
$estados_todos = mysql_query($query_estados_todos, $dbConn) or die(mysql_error());

?>

<!-- START CONTENT -->
<div id="contacto">
  
  <div id="contactanos">
    <div id="title-container">
      <div id="title">
        <span class="diamond">&diams;</span>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        CONT&Aacute;CTANOS
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span class="diamond">&diams;</span>
      </div>
    </div>
  	<div id="datos">
    	<div>PHONE: <br / class="mobile-version">+44 (0) 208 0000 000</div>
    	<div>FAX: <br / class="mobile-version">+44 (0) 208 0000 001</div>
      <div>Email: <br / class="mobile-version">contacto@eligefacil.com</div>
      <div id="company">Eligef&aacute;cil.com, M&eacute;xico.</div>
      <div id="social-media">
      	<div>S&iacute;guenos en:</div>
        <div id="tw"></div>
        <div id="fb"></div>
        <div class="clearfix"></div>
      </div>
    </div>
    <div class="clearfix"></div>
  </div> 
  
  <div id="formulario">
  
    <div id="indicacion">
      <div id="label">LLENA EL SIGUIENTE FORMULARIO PARA PONERTE EN CONTACTO CON NOSOTROS</div>
      <div class="clearfix"></div>
    </div>
     
    <div id="form-container">
            	
        <div class="left">
          <table border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td class="label">Nombre*:</td>
              <td><input type="text" id="nombre" name="nombre" value=""/></td>
            </tr>
            <tr>
              <td class="label">Email*:</td>
              <td><input type="text" id="email" name="email" value=""/></td>
            </tr>
            <tr>
              <td class="label">Estado:</td>
              <td>
                <select name="estado" id="estado" >
                  <option value="0">Selecciona tu estado</option>
                  <?php while($row_estados_todos = mysql_fetch_assoc($estados_todos)){ ?>
                  <option value="<?php echo $row_estados_todos['id_estado']; ?>"><?php echo $row_estados_todos['nombre']; ?></option>
                  <?php } ?> 
                </select>
              </td>
            </tr>
          </table>
        </div>
        
        <div class="right">
          <table border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td class="label">Comentario*:</td>
              <td><textarea id="comentario" name="comentario"></textarea></td>
            </tr>
          </table>  
        </div>
        
        <div class="clearfix"></div>

        <div>
          <table border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td colspan="2" class="button"><div><span class="diamond">&diams;</span>&nbsp;&nbsp;<input id="enviar" name="enviar" type="button" value="Enviar">&nbsp;&nbsp;<span class="diamond">&diams;</span></div></td>
            </tr>
            <tr>
              <td colspan="2" class="msg"></td>
            </tr>
          </table>
       </div>     
          
    </div> 
        
  </div><!-- #formulario -->
    
  <?php if($totalRows_imagenesCarrusel > 0){ ?>  
  <div class="flexslider">
    <ul class="slides">
    	<?php
			
				while($row_imagenesCarrusel = mysql_fetch_assoc($imagenesCarrusel)){
				
					echo "<li>";
					
					if($row_imagenesCarrusel['url'] != NULL){
						echo "<a href='" . $row_imagenesCarrusel['url'] . "' target='_blank'>";
					}
					
					echo "<img src='uploads/carrusel/" . $row_imagenesCarrusel['filename'] . "'>";	
					
					if($row_imagenesCarrusel['url'] != NULL){
						echo "</a>";
					}
					
					echo "</li>";
					
				}//while
			
			?>
    </ul>
  </div>
  <?php }//if ?>

</div>

<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>