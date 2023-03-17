<?php
session_start(); 
include "../php/funtions.php";

//CONEXION A DB
$mysqli = connect_mysqli();

if( isset($_SESSION['colaborador_id']) == false ){
   header('Location: login.php'); 
}    

$_SESSION['menu'] = "Clientes";

if(isset($_SESSION['colaborador_id'])){
 $colaborador_id = $_SESSION['colaborador_id'];  
}else{
   $colaborador_id = "";
}

$type = $_SESSION['type'];

$nombre_host = gethostbyaddr($_SERVER['REMOTE_ADDR']);//HOSTNAME	
$fecha = date("Y-m-d H:i:s"); 
$comentario = mb_convert_case("Ingreso al Modulo de Clientes", MB_CASE_TITLE, "UTF-8");   

if($colaborador_id != "" || $colaborador_id != null){
   historial_acceso($comentario, $nombre_host, $colaborador_id);  
}  

//OBTENER NOMBRE DE EMPRESA
$usuario = $_SESSION['colaborador_id'];

$query_empresa = "SELECT e.nombre AS 'nombre'
	FROM users AS u
	INNER JOIN empresa AS e
	ON u.empresa_id = e.empresa_id
	WHERE u.colaborador_id = '$usuario'";
$result = $mysqli->query($query_empresa) or die($mysqli->error);
$consulta_registro = $result->fetch_assoc();

$empresa = '';

if($result->num_rows>0){
  $empresa = $consulta_registro['nombre'];
}

$mysqli->close();//CERRAR CONEXIÓN     
 ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta name="author" content="Script Tutorials" />
    <meta name="description" content="Responsive Websites Orden Hospitalaria de San Juan de Dios">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Clientes :: <?php echo $empresa; ?></title>
	<?php include("script_css.php"); ?>			
</head>
<body>
   <!--Ventanas Modales-->
   <!-- Small modal -->  
  <?php include("templates/modals.php"); ?>    

<div class="modal fade" id="mensaje_show" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Información Clientes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
			<form id="mensaje_sistema">		
				<div class="form-row">
					<div class="col-md-12 mb-3">
					   <div class="modal-title" id="mensaje_mensaje_show"></div>
					</div>					
				</div>				
			</form>
      </div>
	  <div class="modal-footer">
		<button class="btn btn-success ml-2" type="submit" id="okay" data-dismiss="modal"><div class="sb-nav-link-icon"></div><i class="fas fa-thumbs-up fa-lg"></i> Okay</button>	
		<button class="btn btn-danger ml-2" type="submit" id="bad" data-dismiss="modal"><div class="sb-nav-link-icon"></div><i class="fas fa-thumbs-up fa-lg"></i> Okay</button>				
	  </div>	  
    </div>
  </div>
</div>

<!--FIN MODAL PARA EL INGRESO DE Clientes-->
   <?php include("modals/modals.php");?>	
<!--FIN VENTANAS MODALES-->	

<?php include("templates/menu.php"); ?> 

<br><br><br>
<div class="container-fluid">
	<ol class="breadcrumb mt-2 mb-4">
	<li class="breadcrumb-item" id="acciones_atras"><a id="ancla_volver" class="breadcrumb-link" href="#">Clientes</a></li>
		<li class="breadcrumb-item active" id="acciones_factura"><span id="label_acciones_factura"></span></li>
	</ol>

    <div id="main_facturacion">
		<form class="form-inline" id="form_main_admision">
			<div class="form-group mx-sm-3 mb-1">
				<div class="input-group">
					<div class="input-group-append">
						<span class="input-group-text"><div class="sb-nav-link-icon"></div>Estado</span>
						<select id="estado" name="estado" class="selectpicker" title="Estado" data-live-search="true">
						</select>
					</div>	
				</div>
			</div>
			<div class="form-group mx-sm-3 mb-1">
				<div class="input-group">
					<div class="input-group-append">
						<span class="input-group-text"><div class="sb-nav-link-icon"></div>Tipo</span>
						<select id="tipo" name="tipo" class="selectpicker" title="Tipo" data-live-search="true">
						</select>
					</div>	
				</div>
			</div>				  
			<div class="form-group">
				<input type="text" placeholder="Buscar por: Nombre, Apellido, Identidad o Teléfono Principal" data-toggle="tooltip" data-placement="top" title="Buscar por: Expediente, Nombre, Apellido, Identidad o Teléfono Principal" id="bs_regis" autofocus class="form-control" size="70" autofocus />
			</div>
			<div class="form-group mr-1">
			<div class="form-group">
				<button class="btn btn-primary ml-2" type="submit" id="registrar_cliente"><div class="sb-nav-link-icon"></div><i class="fas fa-user-plus fa-lg"></i> Registrar Clientes</button>	  	  
			</div> 
			<div class="form-group">
			   <button class="btn btn-primary ml-2" type="submit" id="registrar_empresa"><div class="sb-nav-link-icon"></div><i class="fas fa-user-plus fa-lg"></i> Registrar Empresa</button>	  	  
			</div> 	  	  
			<div class="form-group">
				<button class="btn btn-primary ml-2" type="submit" id="registrar_empresa"><div class="sb-nav-link-icon"></div><i class="fas fa-user-plus fa-lg"></i> Registrar Productos</button>	
			</div>				
			</div>
   
		</form>	
		<hr/>   
		<div class="form-group">
		<div class="col-sm-12">
			<div class="registros overflow-auto" id="agrega-registros"></div>
		</div>		   
		</div>
		<nav aria-label="Page navigation example">
			<ul class="pagination justify-content-center" id="pagination"></ul>
		</nav>

	</div>

	<?php include("templates/factura.php"); ?>
	<?php include("templates/footer.php"); ?>	
	<?php include("templates/footer_facturas.php"); ?>
</div>	  

    <!-- add javascripts -->	
	<?php 
		include "script.php"; 
		
		include "../js/main.php"; 
		include "../js/invoice.php"; 
		include "../js/myjava_admision.php"; 
		include "../js/select.php"; 	
		include "../js/functions.php"; 
		include "../js/myjava_cambiar_pass.php"; 		
	?>	
</body>
</html>