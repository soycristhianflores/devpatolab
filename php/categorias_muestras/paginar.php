<?php
session_start();   
include "../funtions.php";

//CONEXION A DB
$mysqli = connect_mysqli(); 

$paginaActual = $_POST['partida'];
$dato = $_POST['dato'];

if($dato == ""){
	$query = "SELECT * FROM categoria";
}else{
	$query = "SELECT * 
		FROM categoria 
		WHERE nombre LIKE '$dato%'";
}

$result = $mysqli->query($query);
$nroProductos = $result->num_rows;

$nroLotes = 10;
$nroPaginas = ceil($nroProductos/$nroLotes);
$lista = '';
$tabla = '';

if($paginaActual > 1){
	$lista = $lista.'<li class="page-item"><a class="page-link" href="javascript:pagination('.(1).');void(0);">Inicio</a></li>';
}

if($paginaActual > 1){
	$lista = $lista.'<li class="page-item"><a class="page-link" href="javascript:pagination('.($paginaActual-1).');void(0);">Anterior '.($paginaActual-1).'</a></li>';
}

if($paginaActual < $nroPaginas){
	$lista = $lista.'<li class="page-item"><a class="page-link" href="javascript:pagination('.($paginaActual+1).');void(0);">Siguiente '.($paginaActual+1).' de '.$nroPaginas.'</a></li>';
}

if($paginaActual > 1){
	$lista = $lista.'<li class="page-item"><a class="page-link" href="javascript:pagination('.($nroPaginas).');void(0);">Ultima</a></li>';
}

if($paginaActual <= 1){
	$limit = 0;
}else{
	$limit = $nroLotes*($paginaActual-1);
}

if($dato == ""){
	$registro = "SELECT * FROM categoria";
}else{
	$registro = "SELECT * 
		FROM categoria 
		WHERE nombre LIKE '$dato%'";
}

$result = $mysqli->query($registro);

$tabla = $tabla.'<table class="table table-striped table-condensed table-hover">
			<tr>
			<th width="5%">No.</th>
			<th width="45%">Categoría</th>
			<th width="45%">Tiempo de Entrega</th>			
			<th width="5%">Opciones</th>
			</tr>';
$i = 1;				
while($registro2 = $result->fetch_array()){	  
	if($registro2[2] == 1){
		$dia = "día";
	}else{
		$dia = "días";
	}
	$tabla = $tabla.'<tr>
			<td>'.$registro2[0].'</td> 
			<td>'.$registro2[1].'</td>
			<td>'.$registro2[2].' '.$dia.'</td>
			<td>
			   <a style="text-decoration:none;" title = "Editar Registros" href="javascript:editarRegistro('.$registro2[0].');" class="fas fa-edit fa-lg"></a>
			   <a style="text-decoration:none;" href="javascript:modal_eliminar('.$registro2[0].');" class="fas fa-trash fa-lg"></a>				   
			</td>				
			</tr>';	
			$i++;				
}

if($nroProductos == 0){
	$tabla = $tabla.'<tr>
	   <td colspan="4" style="color:#C7030D">No se encontraron resultados</td>
	</tr>';		
}else{
   $tabla = $tabla.'<tr>
	  <td colspan="4"><b><p ALIGN="center">Total de Registros Encontrados '.$nroProductos.'</p></b>
   </tr>';		
}        

$tabla = $tabla.'</table>';

$array = array(0 => $tabla,
			   1 => $lista);

echo json_encode($array);

$result->free();//LIMPIAR RESULTADO
$mysqli->close();//CERRAR CONEXIÓN	
?>