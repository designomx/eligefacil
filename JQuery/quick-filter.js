
$(document).ready(function() {

	$('div#quick-filter div#servicios .servicio').click(function(){

		if($(this).hasClass("selected")){
			$(this).removeClass("selected");
		} else {
			$(this).addClass("selected");			
		}
	
	});
	
	
	$('div#quick-filter div#btn_filtrar').click(function(){
		
		var id_estado = $('div#quick-filter div#estados select[name=id_estado]').val();	
		
		if(id_estado == 0){
			
			alert("Por favor, selecciona un estado.");
		
		} else {
			
			//alert(id_estado + " - " + $('div#quick-filter div#estados select[name=id_estado] option:selected').text());
		
			if($('div#quick-filter div#servicios .servicio.selected').length == 0){
				
				alert("Por favor, selecciona al menos un servicio.");
			
			} else {
				
				changeCursorToWait();
				
				var servicios = Array();
				
				$('div#quick-filter div#servicios .servicio.selected').each(function(index){
				
					//alert(index + " - " + $(this).attr('id') + " - " + $(this).text());
					servicios.push($(this).attr('id'));
				
				});
							
				$.redirect("comparador.php", { 'id_estado' : id_estado , 'servicios' : servicios });  
	
			}		
		
		}
				
	});
	
	


});//$(document).ready();

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/* FUNCTIONS */

/*function goto(selector){
	
	jQuery('html, body').animate({scrollTop: (jQuery(selector).offset().top - 125)}, 2000);
	
}*/




											