//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CURSOR

function changeCursorToWait(){
	
	$('html').addClass('cursorToWait');
	
}

function removeCursorToWait(){
	
	$('html').removeClass('cursorToWait');
	
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function isEmpty(text) {
	 for ( i = 0 ; i < text.length ; i++ ) {  
			 if ( text.charAt(i) != " " ) {  
					 return false;  
			 }  
	 }  
	 return true;
 }  

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function trim(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}

function ltrim(stringToTrim) {
	return stringToTrim.replace(/^\s+/,"");
}

function rtrim(stringToTrim) {
	return stringToTrim.replace(/\s+$/,"");
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// FORM FIELDS VALIDATION

/**
* Recibe como parámetro un arreglo con los campos requeridos (como objetos)
* por ejemplo: requiredFields = $([]).add($importe).add($enganche);
*/
function areRequiredFieldsFilledOut(requiredFields){
							
	/* Revisamos si todos los campos requeridos fueron llenados */
	
	var allFieldsAreOk = true;
	var isFocusOnFirstElement = false;

	$.each(requiredFields, function(index, field) {
		
		//alert("index: " + index + " - " + $.type(field));
		
		if(isEmpty($(field).val())) {
			
			allFieldsAreOk = false;
			$(field).addClass('error');
			if(!isFocusOnFirstElement){
				$(field).focus();
				isFocusOnFirstElement = true;
			}
				
			//if(index == 0){ $(field).focus(); } //Para que el cursor se ponga en el primer elemento.
		
		} else {
			
			$(field).removeClass('error');
		}			
		
	});
								
	return allFieldsAreOk;
	
}

/**
* Recibe como parámetro un arreglo tipo json. Cada dato indica el campo y el tipo,
* por ejemplo: [{field: $importe_pago, type: "numeric"}, {field: $importe, type: "numeric"}]; 
*/
function areFieldsDataValid(msgsElement, fields){

	/* Revisamos si todos los campos fueron llenados con datos válidos. */
	
	var allFieldsDataAreValid = true;
	var isFieldValid = true;
	var isFocusOnFirstElement = false;
	
	msgsElement.empty();

	$.each(fields, function(index, field_json) {
			
			type = field_json['type'];
			field = field_json['field'];
			label = field_json['label'];
			

			//alert("type: " + type + " - " + $.type(field) + " - value = " + $(field).val());
						
			switch(type){
			
				case "numeric":
				
					if($(field).val() != ""){
						isFieldValid = $.isNumeric($(field).val());
					} else {
						isFieldValid = true;
					}
					
					if(!isFieldValid){
						errorMsg = label + ": Valor num&eacute;rico inv&aacute;lido.<br />Por favor, ingresa un n&uacute;mero.<br /><br />";
					}
					
					break;
					
				case "email":
				
					var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    			isFieldValid = re.test($(field).val());
					
					if(!isFieldValid){
						errorMsg = label + ": Inv&aacute;lido.<br />Por favor, ingresa un email correcto. Ejemplo: alejandra@hotmail.com<br /><br />";
					}
									
					break;
					
				case "image":
				
					if(!isEmpty(field.val())) {
				
						var ext = getfileextension(field);
											
						if((ext == ".jpg") || (ext == ".jpeg") || (ext == ".gif") || (ext == ".png") || (ext == ".JPG") || (ext == ".JPEG") || (ext == ".GIF") || (ext == ".PNG")){ 
							isFieldValid = true;
						}else{
							
							errorMsg = label + ": Archivo inv&aacute;lido.<br />Por favor, selecciona una imagen con alguna de las siguientes extensiones: .jpg .jpeg .gif .png<br /><br />";
							isFieldValid = false;
						}
					
					} else {
						isFieldValid = true;
					}
				
					break;	
			
			}
			
			if(isFieldValid) {
				
				$(field).removeClass('error');
			
			} else {
				
				allFieldsDataAreValid = false;
				$(field).addClass('error');
	
				if(!isFocusOnFirstElement){
					$(field).focus();
					isFocusOnFirstElement = true;
				}
			
				//if(index == 0){ $(field).focus(); } //Para que el cursor se ponga en el primer elemento.
				
				msgsElement.append(errorMsg).addClass("error");
			} 		
					
	});
							
	return allFieldsDataAreValid;
	
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getfileextension($field) 
{ 

	var filename = $field.val();
	 
	if( filename.length == 0 ) return ""; 

	var dot = filename.lastIndexOf(".");
	if( dot == -1 ) return ""; 

	var extension = filename.substr(dot,filename.length); 
	return extension; 
} 

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// DELETE MESSAGES

function confirmIsolatedItemDeletion(itemName, itemTypeMsg){

      return confirm(itemName + "\n\n¿Está seguro que desea eliminar " + itemTypeMsg + "?\n\nEsta acción es definitiva e irreversible.");

}

function confirmDeletion(itemName, itemTypeMsg){

      return confirm(itemName + "\n\n¿Está seguro que desea eliminar " + itemTypeMsg + "?\n\nEsta acción es definitiva e irreversible. Se eliminarán todos los archivos relacionadas con " + itemTypeMsg + ".");

}

function confirmDeletionCustomizableMsg(message){

      return confirm(message);

}

function confirmImageDeletion(itemName){

      return confirm(itemName + "\n\n¿Está seguro que desea eliminar esta imagen?\n\nEsta acción es definitiva e irreversible. Si elimina esta imagen y aún se está utilizando en algún artículo ésta aparecerá como liga rota.");

}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

