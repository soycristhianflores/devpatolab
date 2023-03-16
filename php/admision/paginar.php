<?php
session_start();   
include "../funtions.php";

//CONEXION A DB
$mysqli = connect_mysqli();

$paginaActual = $_POST['partida'];

$query = "SELECT m.muestras_id, m.fecha AS 'fecha', CONCAT(p.nombre, ' ', p.apellido) AS 'nombre', p.edad, m.number AS 'numero', tp.nombre AS 'tipo', f.number AS 'factura', sf.prefijo AS 'prefijo', sf.relleno AS 'relleno'
	FROM pacientes AS p
	INNER JOIN muestras AS m
	ON p.pacientes_id = m.pacientes_id
	INNER JOIN tipo_muestra AS tp
	ON m.tipo_muestra_id = tp.tipo_muestra_id
	LEFT JOIN facturas AS f
	ON m.muestras_id = f.muestras_id
	LEFT JOIN secuencia_facturacion AS sf
	ON f.secuencia_facturacion_id = sf.secuencia_facturacion_id
	WHERE p.tipo_paciente_id = 1";  
$result = $mysqli->query($query);
   
$nroLotes = 15;
$nroProductos = $result->num_rows;
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

$registro = "SELECT m.muestras_id, m.fecha AS 'fecha', CONCAT(p.nombre, ' ', p.apellido) AS 'nombre', p.edad, m.number AS 'numero', tp.nombre AS 'tipo', f.number AS 'factura', sf.prefijo AS 'prefijo', sf.relleno AS 'relleno'
	FROM pacientes AS p
	INNER JOIN muestras AS m
	ON p.pacientes_id = m.pacientes_id
	INNER JOIN tipo_muestra AS tp
	ON m.tipo_muestra_id = tp.tipo_muestra_id
	LEFT JOIN facturas AS f
	ON m.muestras_id = f.muestras_id
	LEFT JOIN secuencia_facturacion AS sf
	ON f.secuencia_facturacion_id = sf.secuencia_facturacion_id
	WHERE p.tipo_paciente_id = 1
    ORDER BY m.muestras_id LIMIT $limit, $nroLotes";
  
$result = $mysqli->query($registro);

$tabla = $tabla.'<table class="table table-striped table-condensed table-hover">
		  <tr>
			<th width="2%">N°</th>
			<th width="8%">Fecha</th>
			<th width="19%">Cliente</th>
			<th width="4%">Edad</th>
			<th width="15%">Numero Muestra</th>
			<th width="10%">Tipo</th>	
			<th width="18%">Factura</th>				
			<th width="8%">Ver Mas</th>
			<th width="8%">Editar</th>
			<th width="8%">Eliminar</th>
		   </tr>';
			
$i=1;			
while($registro2 = $result->fetch_assoc()){
	$no_factura = "";
	if($registro2['numero'] != "" || $registro2['numero'] != 0){
		$no_factura = $registro2['prefijo'].''.str_pad($registro2['factura'], $registro2['relleno'], "0", STR_PAD_LEFT);
	}
        
	$tabla = $tabla.'<tr>
	   <td>'.$i.'</td>		
	   <td>'.$registro2['fecha'].'</td>
	   <td>'.$registro2['nombre'].'</td>   
	   <td>'.$registro2['edad'].'</td>	   
	   <td>'.$registro2['numero'].'</td>
	   <td>'.$registro2['tipo'].'</td>	   
	   <td>'.$no_factura.'</td>
	   <td>
	   		<a class="btn btn btn-secondary ml-2" href="javascript:ModalVerMas('.$registro2['muestras_id'].');void(0);"><div class="sb-nav-link-icon"></div><i class="fas fa-eye fa-lg"></i> Ver Más</a>
	   </td>
	   <td>
	   		<a class="btn btn btn-secondary ml-2" href="javascript:ModalEditar('.$registro2['muestras_id'].');void(0);"><div class="sb-nav-link-icon"></div><i class="fas fa-user-edit fa-lg"></i> Editar</a>
	   </td>
	   <td>
	   		<a class="btn btn btn-secondary ml-2" href="javascript:modal_eliminar('.$registro2['muestras_id'].');void(0);"><div class="sb-nav-link-icon"></div><i class="fas fa-trash fa-lg"></i> Eliminar</a>
	   </td>	   	   		   	   
  </tr>';		
  $i++;
}       	   

if($nroProductos == 0){
	$tabla = $tabla.'<tr>
	   <td colspan="13" style="color:#C7030D">No se encontraron resultados</td>
	</tr>';		
}else{
   $tabla = $tabla.'<tr>
	  <td colspan="13"><b><p ALIGN="center">Total de Registros Encontrados '.number_format($nroProductos).'</p></b>
   </tr>';		
}

$tabla = $tabla.'</table>';	

$array = array(0 => $tabla,
			   1 => $lista);

echo json_encode($array);

$result->free();//LIMPIAR RESULTADO
$mysqli->close();//CERRAR CONEXIÓN	
?>