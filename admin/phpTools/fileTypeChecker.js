
function getfileextension(inputId) 
{ 
	var fileinput = document.getElementById(inputId); 
	if(!fileinput ) return ""; 

	var filename = fileinput.value; 
	if( filename.length == 0 ) return ""; 

	var dot = filename.lastIndexOf(".");
	if( dot == -1 ) return ""; 

	var extension = filename.substr(dot,filename.length); 
	return extension; 
} 

function checkImagefileType(inputId, fieldname) 
{ 
	var ext = getfileextension(inputId);
	if((ext == ".jpg") || (ext == ".jpeg") || (ext == ".gif") || (ext == ".png") || (ext == ".JPG") || (ext == ".JPEG") || (ext == ".GIF") || (ext == ".PNG")){ 
		return true;
	}else{
		/*if(fieldname == undefined)
			alert("Archivo inválido.\n\nPor favor, seleccione una imagen con alguna de las siguientes extensiones: .jpg .jpeg .gif .png");
		else
			alert("Archivo para \"" + fieldname + "\" inválido.\n\nPor favor, seleccione una imagen con alguna de las siguientes extensiones: .jpg .jpeg .gif .png");*/
		return false;
	}
}

function checkDocfileType(inputId, fieldname) 
{ 
	var ext = getfileextension(inputId);
	if((ext == ".pdf") || (ext == ".PDF") || (ext == ".DOC") || (ext == ".doc") || (ext == ".docx") || (ext == ".xls") || (ext == ".ppt") || (ext == ".txt") || (ext == ".rtf")){ 
		return true;
	}else{ 
		alert("Archivo para \"" + fieldname + "\" inválido.\n\nPor favor, seleccione un archivo con alguna de las siguientes extensiones: .pdf .doc .docx .txt .rtf");
		return false;
	}
}

function checkVideofileType(inputId, fieldname) 
{ 
	var ext = getfileextension(inputId);
	if(ext == ".f4v"){ // || (ext == ".PDF") || (ext == ".DOC") || (ext == ".doc") || (ext == ".docx") || (ext == ".xls") || (ext == ".ppt") || (ext == ".txt") || (ext == ".rtf")){ 
		return true;
	}else{
		alert("Archivo para \"" + fieldname + "\" inválido.\n\nPor favor, seleccione un archivo con alguna de las siguientes extensiones: .f4v");
		return false;
	}
}

function checkAudiofileType(inputId, fieldname) 
{ 
	var ext = getfileextension(inputId);
	if(ext == ".mp3"){ // || (ext == ".PDF") || (ext == ".DOC") || (ext == ".doc") || (ext == ".docx") || (ext == ".xls") || (ext == ".ppt") || (ext == ".txt") || (ext == ".rtf")){ 
		return true;
	}else{ 
		alert("Archivo para \"" + fieldname + "\" inválido.\n\nPor favor, seleccione un archivo con alguna de las siguientes extensiones: .mp3");
		return false;
	}
}
