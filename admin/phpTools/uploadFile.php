<?php
/*
*
* File: uploadFile.php
* Author: Juan Luis Almazo.
* Created: 29/10/2011.
* Last update: 27/11/2012.
* 
*/

require_once('utilities.php');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function uploadFile($fileID, $directory){
	
	$FILE = $_FILES[$fileID]['tmp_name'];
	$FILE_NAME = $_FILES[$fileID]['name'];
	
	$fileName_final = cleanForShortURL($FILE_NAME);
	
	if($FILE != NULL){
		
		// if directory doesn't exist, it is created.
		if(!file_exists($directory)){
			mkdir($directory, 0777, true);
		}
		
		$full_path = $directory . $fileName_final;
		
		// save the file into directory
		move_uploaded_file($FILE, $full_path);
		
		// delete temporary uploaded file.
		unlink($FILE);
		
		return $fileName_final;
	} else{
		
		return NULL;
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function uploadImage($fileID, $directory, $imageWidth = NULL, $imageHeight = NULL, $thumbWidth = NULL, $thumbHeight = NULL){
	
	/*
	echo "imageWidth = |" . $imageWidth . "|<br />";
	echo "imageHeight = |" . $imageHeight . "|<br />";
	echo "thumbWidth = |" . $thumbWidth . "|<br />";
	echo "thumbHeight = |" . $thumbHeight . "|<br />";	
	*/
	
	$FILE = $_FILES[$fileID]['tmp_name'];
	$FILE_NAME = $_FILES[$fileID]['name'];

	$fileName_final = cleanForShortURL($FILE_NAME);
	
	if($FILE != NULL){

		//////////// BIG IMAGE  /////////////////////////////////////////////////////////
		
		// If big image is required.
		if($imageWidth != NULL || $imageHeight != NULL){
	
			if(is_numeric($imageWidth) || is_numeric($imageHeight)){
						
				// resample image
				$image = new SimpleImage();
				$image->load($FILE);
									
				if(is_numeric($imageWidth) && is_numeric($imageHeight)){
					
					if (($image->getWidth() > $imageWidth) || ($image->getHeight() > $imageHeight)) 
					{
						if($image->getWidth() > $imageWidth){
							
							$image->resizeToWidth($imageWidth);
						}
						
						if($image->getHeight() > $imageHeight){
							
							$image->resizeToHeight($imageHeight);
						}
					}
					
				}else{
	
					if(is_numeric($imageWidth) && !is_numeric($imageHeight)){
						
						if ($image->getWidth() > $imageWidth){ 
							$image->resizeToWidth($imageWidth);
						}
						
					}else{
						
						if(!is_numeric($imageWidth) && is_numeric($imageHeight)){
							
							if($image->getHeight() > $imageHeight){
								$image->resizeToHeight($imageHeight);
							}
						}
						
					}
				}
											
				// if directory doesn't exist, it is created.
				if(!file_exists($directory)){
					mkdir($directory, 0777, true);
				}
				
				// save image into directory.
				$image->save($directory . $fileName_final);
				
			}// close: if(!is_numeric($imageWidth) && !is_numeric($imageHeight))
			
		} else {
		
			//Subimos la imagen con sus dimensiones originales (sin resize)
		
			// if directory doesn't exist, it is created.
			if(!file_exists($directory)){
				mkdir($directory, 0777, true);
			}
			
			move_uploaded_file($FILE, $directory . $fileName_final) ; // Moving Uploaded imagen
		
		
		}

		//////////// THUMBNAIL  /////////////////////////////////////////////////////////
		
		// If thumbnail is required.
		if($thumbWidth != NULL || $thumbHeight != NULL){

			if(is_numeric($thumbWidth) || is_numeric($thumbHeight)){
		
				// resample thumbnail
				$thumb = new SimpleImage();
				$thumb->load($FILE);
									
				if(is_numeric($thumbWidth) && is_numeric($thumbHeight)){
					
					if (($thumb->getWidth() > $thumbWidth) || ($thumb->getHeight() > $thumbHeight)) 
					{
						if($thumb->getWidth() > $thumbWidth){
							
							$thumb->resizeToWidth($thumbWidth);
						}
						
						if($thumb->getHeight() > $thumbHeight){
							
							$thumb->resizeToHeight($thumbHeight);
						}
					}
					
				}else{

					if(is_numeric($thumbWidth) && !is_numeric($thumbHeight)){
						
						if ($thumb->getWidth() > $thumbWidth){ 
							$thumb->resizeToWidth($thumbWidth);
						}
						
					}else{
						
						if(!is_numeric($thumbWidth) && is_numeric($thumbHeight)){
							
							if($thumb->getHeight() > $thumbHeight){
								$thumb->resizeToHeight($thumbHeight);
							}
						}
						
					}
				}
							
				$thumbnailsDirectory = $directory . "thumbs/";
				
				// if directory doesn't exist, it is created.
				if(!file_exists($thumbnailsDirectory)){
					mkdir($thumbnailsDirectory, 0777, true);
				}
				
				// save thumb into thumbnails directory.
				$thumb->save($thumbnailsDirectory . $fileName_final);
				
			}// close: if(!is_numeric($thumbWidth) && !is_numeric($thumbHeight))
			
		}// close: if($thumbWidth != NULL || $thumbHeight != NULL)
		
		//////////// THUMBNAIL ENDS HERE /////////////////////////////////////////////////

		// delete temporary uploaded file.
		unlink($FILE);
		
		return $fileName_final;
		
	} else{ // close: if($FILE != NULL)
		
		return NULL;
		
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function uploadImage2($fileID, $directory){

	$filename = NULL;

	if(isset($_FILES[$fileID]["type"]))
	{
		$validextensions = array("jpeg", "jpg", "png");
		$temporary = explode(".", $_FILES[$fileID]["name"]);
		$imagen_extension = end($temporary);
		if ((($_FILES[$fileID]["type"] == "image/png") || ($_FILES[$fileID]["type"] == "image/jpg") || ($_FILES[$fileID]["type"] == "image/jpeg")
		) && ($_FILES[$fileID]["size"] < 10000000)//Approx. 100kb imagens can be uploaded.
		&& in_array($imagen_extension, $validextensions)) {
			if ($_FILES[$fileID]["error"] > 0)
			{
				echo "Return Code: " . $_FILES[$fileID]["error"] . "<br/><br/>";
			}
			else
			{
				//if (file_exists($directory . "/" . $_FILES[$fileID]["name"])) {
				//	echo $_FILES[$fileID]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
				//}
				//else
				//{
					
					$filename = cleanForShortURL($_FILES[$fileID]['name']);
					$sourcePath = $_FILES[$fileID]['tmp_name']; // Storing source path of the imagen in a variable
					$targetPath = $directory . "/" . $filename; // Target path where imagen is to be stored
					
					// if directory doesn't exist, it is created.
					if(!file_exists($directory)){
						mkdir($directory, 0777, true);
					}
					
					move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded imagen
					
					//$filename = $_FILES[$fileID]['name'];
					
					/*echo "<span id='success'>Image Uploaded Successfully...!!</span><br/>";
					echo "<br/><b>File Name:</b> " . $_FILES["imagen"]["name"] . "<br>";
					echo "<b>Type:</b> " . $_FILES["imagen"]["type"] . "<br>";
					echo "<b>Size:</b> " . ($_FILES["imagen"]["size"] / 1024) . " kB<br>";
					echo "<b>Temp imagen:</b> " . $_FILES["imagen"]["tmp_name"] . "<br>";*/
				//}
			}
		}
		else
		{
			echo "<span id='invalid'>***Invalid imagen Size or Type***<span>";
		}
	}
	
	return $filename;

}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



?>
