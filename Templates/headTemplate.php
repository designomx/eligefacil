<?php

	mysql_select_db($database, $dbConn);
	
	/* Obtiene todos los estados */
	$query_estados = "SELECT * FROM estados WHERE id_estado IN (select id_estado from cobertura) ORDER BY nombre ASC";
	$estados = mysql_query($query_estados, $dbConn) or die(mysql_error());
	
	/* Obtiene todos los tipos de servicios */
	$query_servicios = "SELECT * FROM tipoServicios ORDER BY nombre ASC";
	$servicios = mysql_query($query_servicios, $dbConn) or die(mysql_error());

?>
  <script type="text/javascript" charset="utf-8" src="JQuery/jquery.backstretch.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="JQuery/jquery.redirect.js"></script>
	<script type="text/javascript" charset="utf-8" src="JQuery/quick-filter.js"></script>
  <script type="text/javascript" charset="utf-8" src="JQuery/utilities.js"></script>
  
	<script type="text/javascript">
  
    $(document).ready(function() {
    
      $('#header').backstretch("images/imagen_header.jpg");
			
			$("#mobileMenuIcon").click(function() {
				$('div#mobileMenu').toggle();
			});
    
    }); //$(document).ready();
  
  </script>
  
  </head>
  <body>
  
    <div id="header">
    
  		<div id="top-bar">
        <div class="header-wrapper">
          <a href="index.php"><div id="logo"></div></a>
          <ul id="main-menu" class="menu">
            <a href="comparador.php"><li>&iexcl;Descubre!</li></a>
            <a href="blog.php"><li>&iexcl;Ent&eacute;rate!</li></a>
            <a href="contacto.php"><li id="email"></li></a>
            <li class="clearfix"></li>
          </ul><!-- #main-menu -->
          <div id="mobileMenuIcon"></div>
          <div class="clearfix"></div>
        </div><!-- #header-wrapper -->
        <div id="mobileMenu">
          <ul>
            <a href="comparador.php"><li>&iexcl;Descubre!</li></a>
            <a href="blog.php"><li>&iexcl;Ent&eacute;rate!</li></a>
            <a href="contacto.php"><li id="email">Contacto</li></a>
          </ul>  
        </div>
      </div><!-- #top-bar -->
      
      <div id="quick-filter-bar">  
        <div class="header-wrapper">
          <div id="quick-filter">
          	
            <div id="label1" class="block">EMPIEZA A COMPARAR AQU&Iacute;:</div>
            <div id="estados" class="block">
              <select name="id_estado" id="id_estado" >
              	<option value="0">Selecciona tu estado</option>
								<?php while($row_estados = mysql_fetch_assoc($estados)){ ?>
                <option value="<?php echo $row_estados['id_estado']; ?>" <?php if($row_estados['id_estado'] == $_POST['id_estado']){ echo "SELECTED"; } ?>><?php echo $row_estados['nombre']; ?></option>
								<?php } ?> 
              </select>
            </div>
            <div class="mobile-version new-line"></div>
            <div id="label2" class="block">Selecciona los servicios que quieres comparar:</div>
            <div class="mobile-version new-line"></div>
            <div id="servicios" class="block">
            	<?php while($row_servicios = mysql_fetch_assoc($servicios)){ ?>
              	<div id="<?php echo $row_servicios['id_tipoServicio']; ?>" class="servicio <?php if(in_array($row_servicios['id_tipoServicio'], $_POST['servicios'])){ echo "selected"; } ?>"><img src="uploads/servicios/<?php echo $row_servicios['id_tipoServicio'] . "/" . $row_servicios['icono']; ?>" /><?php echo $row_servicios['nombre']; ?></div>
							<?php } ?>
            </div>
            <div class="mobile-version new-line"></div>
            <div id="btn_filtrar" class="block">&iexcl;DESCUBRE TU PLAN!</div>
            <div class="mobile-version clearfix"></div>
          
          </div><!-- #quick-filter -->
        </div><!-- #header-wrapper -->
      </div><!-- #quick-filter-bar -->  
    
    </div><!-- #header -->
    
    <div id="content">
    