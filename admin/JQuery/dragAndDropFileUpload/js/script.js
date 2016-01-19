$(function(){
				
	var dropbox = $('#dropbox'), message = $('.message', dropbox);
				
	dropbox.filedrop({
		// The name of the $_FILES entry:
		paramname:'pic',
		
		//maxfiles: 5,
		maxfiles: 30,
		maxfilesize: 10,
		url: 'post_file.php', // Modified by Juan Luis Almazo - May 7, 2014.
		
		uploadFinished:function(i, file, response){

			var id_imagen = 0;
			
			// response is the JSON object that post_file.php returns
			$.each(response, function(key, value){
			
				if(key == "id"){
					id_imagen = value;
				}
								
			});

			$.data(file).addClass('done');
			$.data(file).attr('id', id_imagen);
			
			var optionsTemplate = '<span class="options">'+
														 '<span class="buttons">'+
														   '<button onclick="editPictureInfo(' + id_imagen + ')">Editar URL</button>'+
														   '<button onclick="deletePicture('+ id_imagen + ');">Eliminar</button>'+
														 '</span>'+
														'</span>';
			
			
			$.data(file).find('.imageHolder').append($(optionsTemplate));
			
			$.data(file).mouseenter(function(){
				$.data(file).find('.uploaded').hide();															 
		  }); 
			
		},
		
		error: function(err, file) {
			switch(err) {
				case 'BrowserNotSupported':
					showMessage('Your browser does not support HTML5 file uploads!');
					break;
				case 'TooManyFiles':
					alert('Too many files! Please select 20 at most! (configurable)');
					break;
				case 'FileTooLarge':
					alert(file.name+' is too large! Please upload files up to 2mb (configurable).');
					break;
				default:
					break;
			}
		},
		
		// Called before each upload is started
		beforeEach: function(file){
			if(!file.type.match(/^image\//)){
				alert('Only images are allowed!');
				
				// Returning false will cause the
				// file to be rejected
				return false;
			}
		},
		
		uploadStarted:function(i, file, len){
			createImage(file);
		},
		
		progressUpdated: function(i, file, progress) {
			$.data(file).find('.progress').width(progress);
		}
    	 
	});
		
	var template = '<div class="preview">'+
										'<span class="imageHolder">'+
											'<img />'+
											'<span class="uploaded"></span>'+
										'</span>'+
										'<div class="progressHolder">'+
											'<div class="progress"></div>'+
										'</div>'+
									'</div>'; 
	
	
	function createImage(file){

		var preview = $(template), image = $('img', preview);
			
		var reader = new FileReader();
		
		image.width = 100;
		image.height = 100;
		
		reader.onload = function(e){
			
			// e.target.result holds the DataURL which
			// can be used as a source of the image:
			
			image.attr('src',e.target.result);
		};
		
		// Reading the file as a DataURL. When finished,
		// this will trigger the onload function above:
		reader.readAsDataURL(file);
		
		message.hide();
		preview.appendTo(dropbox);
		
		// Associating a preview container
		// with the file, using jQuery's $.data():
		
		$.data(file,preview);
	}

	function showMessage(msg){
		message.html(msg);
	}

});