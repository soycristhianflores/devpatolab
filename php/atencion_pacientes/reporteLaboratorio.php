<html>
<head>
  <style>
    @page { 
		margin: 180px 60px; 
	}
	
	p, label, span, table{
		font-family: 'Helvetica';
		font-size: 10pt;
	}	
	
    #header { 
		position: fixed; 
		left: 0px; 
		top: -185px; right: 3px; 
		height: 185px; 
		text-align: center; 		
	}
	
    #footer { 
		position: fixed; 
		left: 0px; 
		bottom: -180px; 
		right: 0px; 
		height: 140px; 
	}
	
    #footer .page:after { 
		content: counter(page, upper-roman);
	}
	
	.logo_factura{	
		width: 20%;
		display:block;
		margin:auto;			
	}
	
	.logo_factura img{
		width:250px;
		height:75px;			
	}	
	
	.title {			
		font-size: 13px;
		font-family: 'Helvetica';
		font-weight: bold;
		padding-left: 10px;
		padding-top: 10px;   
		padding-bottom: 10px;
		text-align: center;
		width: 97%;
		word-wrap: break-word;
	}

	.title1 {
		border: 1px solid #99cccc;			
		font-size: 13px;
		font-family: 'Helvetica';
		font-weight: bold;
		padding-left: 10px;
		padding-top: 10px;   
		padding-bottom: 10px;
		text-align: center;
		width: 25%;
		word-wrap: break-word;
		background-color: #DCDCDC;
	}	

	hr {
	  height: 1px;
	  width: 100%;
	  border: 1px solid #99cccc;
	  background-color: black;
	}	

	.left {
		padding-left: 2px;
		padding-top: 2px;
		margin-left: 2px;
		float: left;
		position: relative;
		width:370px;
		height:auto;
		word-wrap: break-word;
		border: steelblue solid 1px;
	}

	.right {
		padding-top: 2px;
		padding-left: 2px;
		margin-left: 2px;
		position: relative;
		float: left;
		width:330px;
		height:auto;
		word-wrap: break-word;
		border: steelblue solid 1px;
	}
	
	.contenido {			
		font-size: 13px;
		font-family: 'Helvetica';
		padding-left: 10px;
		padding-top: 10px;   
		padding-bottom: 10px;
		width: 97%;
		line-height:20px;
		min-height: 100vh;
	}	
	
	.div_left {
		padding-left: 10px;
		padding-top: 10px;
		margin-left: 10px;
		float: left;
		position: relative;
		width: 45%;
		height: auto;
		text-align:left;
		word-wrap: break-word;
		left: 0px;
		font-size: 10pt;		
	}

	.div_right {
		padding-top: 10px;
		padding-left: 10px;
		margin-left: 10px;
		position: relative;
		text-align: center;
		float: left;
		width: 45%;
		height: auto;
		word-wrap: break-word;
		left: 0px;  		
	}	
	

	body {
		background: url(<?php echo $image_server; ?>);
		background-repeat: no-repeat;
		background-position: center;
		width: 100%;
		height: auto;
		margin: auto;
		padding: 0;
		height: auto;
		background-color: #ffffff;
	}
	
	.item{
	  width:170px;
	  text-align:center;
	  display:block;
	  background-color: transparent;
	  border: 1px solid transparent;
	  float:left;
	}
	
	.item img {
		width: 40px;
		height: 40px;
		background-color: grey;
	}

	#index-gallery{
	  width:200px;
	}	
  </style>
  </head>
<body>
  <div id="header">
	<div class="logo_factura">
		<img src="<?php echo SERVERURL; ?>img/logo_factura.jpg">
	</div>  
	<div class="title"><i><?php echo $consulta_registro['eslogan']; ?></i></div>
	<div class="title1">Biopsia N° <?php echo $consulta_registro['numero']; ?></div>
	<div class="title">INFORME DE ANATOMÍA PATOLÓGICA</div>
	<hr/>
  </div>
  <div id="footer">
	<table style="width: 100px; margin: 0 auto;">
		<tr>
			<td>
				<div class="item">
					<img src="<?php echo SERVERURL; ?>img/email.jpg" alt=""/>
					<p><?php echo $consulta_registro['empresa_correo']; ?></p>
				</div>				
			</td>
			<td>
				<div class="item">
					<img src="<?php echo SERVERURL; ?>img/telephone.jpg" alt=""/>
					<p><?php echo $consulta_registro['empresa_telefono']; ?></p>
				</div>				
			</td>
			<td>
				<div class="item">
					<img src="<?php echo SERVERURL; ?>img/whatsapp.jpg" alt=""/>
					<p><?php echo $consulta_registro['celular']; ?></p>
				</div>		
			</td>
			<td>
				<div class="item">
					<img src="<?php echo SERVERURL; ?>img/address.jpg" alt=""/>
					<p><?php echo $consulta_registro['direccion_empresa']; ?></p>
				</div>		
			</td>
		</tr>
	</table>
  </div>
  <div id="content">
    <p style="page-break-before: auto;">
        <div class="left">
			<p><b>Registro Número:</b> <?php echo $consulta_registro['numero']; ?></p>
			<p><b>Nombre:</b> 
			<?php 
				$paciente = $consulta_registro['paciente'];
				$empresa = "";	
				if($paciente != ""){
					$empresa = $paciente;
				}else{
					$empresa = $consulta_registro['empresa'];
				}			
			
				echo $empresa; 
			
			?></p>
			<p><b>Edad:</b> <?php 
				$paciente = $consulta_registro['paciente'];
				$edad = "";	
				if($paciente != ""){
					$edad = $consulta_registro['edad_paciente'];
				}else{
					$edad = $consulta_registro['edad'];
				}
				
				echo $edad; 
			?> <b>Sexo:</b> 
			<?php 
				$paciente = $consulta_registro['paciente'];
				$genero = "";	
				if($paciente != ""){
					$genero = $consulta_registro['genero_paciente'];
				}else{
					$genero = $consulta_registro['genero'];
				}
			echo $genero; 
			
			?></p>
			<p><b>Medico Remitente:</b> <?php 
				if($consulta_registro['medico_remitente'] == "Sin Registro"){
					echo $consulta_registro['hospital']; 
				}else{
					echo $consulta_registro['medico_remitente'].'/'.$consulta_registro['hospital']; 
				}				
			?></p>
			<p><b>Diagnostico Clínico:</b> <?php echo $consulta_registro['diagnostico_clinico']; ?></p>
        </div>
        <div class="right">
			<p><b>Sitio Preciso de la Muestra: </b><?php echo $consulta_registro['sitio_muestra']; ?></p>
			<p><b>Fecha de Recibido:</b>  <?php echo $consulta_registro['fecha_recibido']; ?></p>
			<p><b>Fecha de la Toma:</b> <?php echo $consulta_registro['fecha_recibido']; ?></p>
			<p><b>Fecha de Emisión de Reporte:</b> <?php echo $consulta_registro['fecha_emision_reporte']; ?></p>
			<p><br/></p>
        </div>	
	
	<div class="imagen_fondo">
		<div class="contenido"><?php 
				if($consulta_registro['diagnostico'] != ""){
					echo "<b>DIAGNÓSTICO:</b><br/>";
					echo nl2br($consulta_registro['diagnostico']);
				}
			?>
		</div>
		<div class="contenido"><?php 
				if($consulta_registro['factores_pronostico'] != ""){
					echo "<b>FACTORES PRONÓSTICOS / PROTOCOLO SEGÚN EL COLEGIO AMERICANO DE PATOLÓGOS:</b><br/>"; 
					echo nl2br($consulta_registro['factores_pronostico']);
				}
			?>
		</div>
		<div class="contenido"><?php 			
				if($consulta_registro['descripcion_macroscopica'] != ""){
					echo "<b>DESCRIPCIÓN MACROSCÓPICA:</b><br/>"; 
					echo nl2br($consulta_registro['descripcion_macroscopica']);
				}				
			?>
		</div>
		<div class="contenido"><?php 											
				if($consulta_registro['descripcion_microscopica'] != ""){
					echo "<b>DESCRIPCIÓN MICROSCÓPICA:</b><br/>"; 
					echo nl2br($consulta_registro['descripcion_microscopica']);
				}					
			?>
		</div>
		<div class="contenido"><?php 
				if($consulta_registro['comentario'] != ""){
					echo "<b>COMENTARIO:</b><br/>"; 
					echo nl2br($consulta_registro['comentario']);
				}					
		 ?>
		</div>
		<div class="contenido"><b>IMÁGENES:</b> </div>
		<div class="contenido"><?php
			if($consulta_registro['adendum'] != ""){
				echo "<b>Adendum:</b> ".$consulta_registro['adendum']; 
			}
		?></div>	
		<br/>		
		<div class="div_left"><b>Fecha:</b> <?php echo $consulta_registro['fecha_emision_reporte']; ?></div>
		<div class="div_right">
			<img src="<?php echo SERVERURL; ?>img/firma_sello_nombre.png" width="300px" height="120px">
		</div>
	</div>
	</p>
  </div>
</body>
</html>