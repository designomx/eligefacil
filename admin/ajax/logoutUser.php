<?php
	
	session_start();

	$_SESSION['email'] = NULL;
	unset( $_SESSION['email']);
	
	$_SESSION['nombre'] = NULL;
	unset( $_SESSION['nombre']);
	
	/*$_SESSION['rol'] = NULL;
	unset( $_SESSION['rol']);*/
	
	session_destroy();
	
?>