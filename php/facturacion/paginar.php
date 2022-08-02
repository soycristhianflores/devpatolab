<?php
session_start();
include "../funtions.php";

//CONEXION A DB
$mysqli = connect_mysqli();

$colaborador_id = $_SESSION['colaborador_id'];
$paginaActual = $_POST['partida'];
$fechai = $_POST['fechai'];
$fechaf = $_POST['fechaf'];
$dato = $_POST['dato'];
$tipo_paciente_grupo = $_POST['tipo_paciente_grupo'];
$pacientesIDGrupo = $_POST['pacientesIDGrupo'];
$estado = $_POST['estado'];
$usuario = $_SESSION['colaborador_id'];

if($estado == 2 || $estado == 4){
	if($tipo_paciente_grupo == "" && $pacientesIDGrupo == ""){
		$where = "WHERE f.fecha BETWEEN '$fechai' AND '$fechaf' AND f.estado = '$estado' AND f.usuario = '$colaborador_id' AND (p.expediente LIKE '$dato%' OR p.nombre LIKE '$dato%' OR p.apellido LIKE '$dato%' OR CONCAT(p.apellido,' ',p.nombre) LIKE '%$dato%' OR f.number LIKE '$dato%')";
	}else if($tipo_paciente_grupo != "" && $pacientesIDGrupo == ""){
		$where = "WHERE p.tipo_paciente_id = '$tipo_paciente_grupo' AND f.estado = '$estado' AND f.usuario = '$colaborador_id' AND (p.expediente LIKE '$dato%' OR p.nombre LIKE '$dato%' OR p.apellido LIKE '$dato%' OR CONCAT(p.apellido,' ',p.nombre) LIKE '%$dato%' OR f.number LIKE '$dato%')";
	}else if($tipo_paciente_grupo != "" && $pacientesIDGrupo != ""){
		$where = "WHERE p.tipo_paciente_id = '$tipo_paciente_grupo' AND p.pacientes_id = '$pacientesIDGrupo' AND f.usuario = '$colaborador_id' AND f.estado = '$estado' AND (p.expediente LIKE '$dato%' OR p.nombre LIKE '$dato%' OR p.apellido LIKE '$dato%' OR CONCAT(p.apellido,' ',p.nombre) LIKE '%$dato%' OR f.number LIKE '$dato%')";
	}else{
		$where = "WHERE f.fecha BETWEEN '$fechai' AND '$fechaf' AND f.estado = '$estado' AND f.usuario = '$colaborador_id' AND (p.expediente LIKE '$dato%' OR p.nombre LIKE '$dato%' OR p.apellido LIKE '$dato%' OR CONCAT(p.apellido,' ',p.nombre) LIKE '%$dato%' OR f.number LIKE '$dato%')";
	}
}else{
	if($tipo_paciente_grupo == "" && $pacientesIDGrupo == ""){
		$where = "WHERE f.fecha BETWEEN '$fechai' AND '$fechaf' AND f.estado = '$estado' AND (p.expediente LIKE '$dato%' OR p.nombre LIKE '$dato%' OR p.apellido LIKE '$dato%' OR CONCAT(p.apellido,' ',p.nombre) LIKE '%$dato%' OR f.number LIKE '$dato%')";
	}else if($tipo_paciente_grupo != "" && $pacientesIDGrupo == ""){
		$where = "WHERE p.tipo_paciente_id = '$tipo_paciente_grupo' AND f.estado = '$estado' AND (p.expediente LIKE '$dato%' OR p.nombre LIKE '$dato%' OR p.apellido LIKE '$dato%' OR CONCAT(p.apellido,' ',p.nombre) LIKE '%$dato%' OR f.number LIKE '$dato%')";
	}else if($tipo_paciente_grupo != "" && $pacientesIDGrupo != ""){
		$where = "WHERE p.tipo_paciente_id = '$tipo_paciente_grupo' AND p.pacientes_id = '$pacientesIDGrupo' AND f.estado = '$estado' AND (p.expediente LIKE '$dato%' OR p.nombre LIKE '$dato%' OR p.apellido LIKE '$dato%' OR CONCAT(p.apellido,' ',p.nombre) LIKE '%$dato%' OR f.number LIKE '$dato%')";
	}else{
		$where = "WHERE f.fecha BETWEEN '$fechai' AND '$fechaf' AND f.estado = '$estado' AND (p.expediente LIKE '$dato%' OR p.nombre LIKE '$dato%' OR p.apellido LIKE '$dato%' OR CONCAT(p.apellido,' ',p.nombre) LIKE '%$dato%' OR f.number LIKE '$dato%')";
	}
}

$query = "SELECT f.facturas_id AS facturas_id, DATE_FORMAT(f.fecha, '%d/%m/%Y') AS 'fecha', CONCAT(p.nombre,' ',p.apellido) AS 'empresa', p.identidad AS 'identidad', CONCAT(c.nombre,' ',c.apellido) AS 'profesional', f.estado AS 'estado', s.nombre AS 'consultorio', sc.prefijo AS 'prefijo', f.number AS 'numero', sc.relleno AS 'relleno', CONCAT(p1.nombre,' ',p1.apellido) AS 'paciente', p1.pacientes_id AS 'codigoPacienteEmpresa', f.muestras_id AS 'muestras_id', c.colaborador_id AS 'colaborador_id', m.number AS 'muestra'
	FROM facturas AS f
	INNER JOIN pacientes AS p
	ON f.pacientes_id = p.pacientes_id
	INNER JOIN secuencia_facturacion AS sc
	ON f.secuencia_facturacion_id = sc.secuencia_facturacion_id
	INNER JOIN servicios AS s
	ON f.servicio_id = s.servicio_id
	INNER JOIN colaboradores AS c
	ON f.colaborador_id = c.colaborador_id
	LEFT JOIN muestras_hospitales AS mh
	ON f.muestras_id = mh.muestras_id
	LEFT JOIN pacientes As p1
	ON mh.pacientes_id = p1.pacientes_id
	INNER JOIN muestras AS m
    ON f.muestras_id = m.muestras_id
	".$where."
	GROUP BY m.muestras_id
	ORDER BY f.pacientes_id ASC";
$result = $mysqli->query($query) or die($mysqli->error);

$nroLotes = 200;
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

$registro = "SELECT f.facturas_id AS facturas_id, DATE_FORMAT(f.fecha, '%d/%m/%Y') AS 'fecha', CONCAT(p.nombre,' ',p.apellido) AS 'empresa', p.identidad AS 'identidad', CONCAT(c.nombre,' ',c.apellido) AS 'profesional', f.estado AS 'estado', s.nombre AS 'consultorio', sc.prefijo AS 'prefijo', f.number AS 'numero', sc.relleno AS 'relleno', CONCAT(p1.nombre,' ',p1.apellido) AS 'paciente', p1.pacientes_id AS 'codigoPacienteEmpresa', f.muestras_id AS 'muestras_id', c.colaborador_id AS 'colaborador_id', m.number AS 'muestra'
	FROM facturas AS f
	INNER JOIN pacientes AS p
	ON f.pacientes_id = p.pacientes_id
	INNER JOIN secuencia_facturacion AS sc
	ON f.secuencia_facturacion_id = sc.secuencia_facturacion_id
	INNER JOIN servicios AS s
	ON f.servicio_id = s.servicio_id
	INNER JOIN colaboradores AS c
	ON f.colaborador_id = c.colaborador_id
	LEFT JOIN muestras_hospitales AS mh
	ON f.muestras_id = mh.muestras_id
	LEFT JOIN pacientes As p1
	ON mh.pacientes_id = p1.pacientes_id
	INNER JOIN muestras AS m
    ON f.muestras_id = m.muestras_id	
	".$where."
	GROUP BY m.muestras_id
	ORDER BY f.pacientes_id ASC
	LIMIT $limit, $nroLotes";
$result = $mysqli->query($registro) or die($mysqli->error);

$tabla = $tabla.'<table class="table table-striped table-condensed table-hover">
	<thead>
		<tr>
			<th width="2.14%"><input id="checkAllFactura" class="formcontrol" type="checkbox"></th>
			<th width="1.14%">No.</th>
			<th width="5.14%">Fecha</th>
			<th width="10.14%">Muestra</th>
			<th width="9.14%">Factura</th>
			<th width="12.14%">Empresa</th>
			<th width="9.14%">Identidad</th>
			<th width="4.14%">Profesional</th>
			<th width="8.14%">Importe</th>
			<th width="4.14%">ISV</th>
			<th width="7.14%">Descuento</th>
			<th width="7.14%">Neto</th>
			<th width="5.14%">Estado</th>
			<th width="7.14%">Opciones</th>
		</tr>
	</thead>';
$i = 1;
$fila = 0;
while($registro2 = $result->fetch_assoc()){
	$facturas_id = $registro2['facturas_id'];
	//CONSULTAR DATOS DEL DE TALLE DE LA FACTURACION
	$query_detalle = "SELECT cantidad, precio, descuento, isv_valor
		FROM facturas_detalle
		WHERE facturas_id = '$facturas_id'";
	$result_detalles = $mysqli->query($query_detalle) or die($mysqli->error);

	$cantidad = 0;
	$descuento = 0;
	$precio = 0;
	$total_precio = 0;
	$total = 0;
	$isv_neto = 0;
	$neto_antes_isv = 0;
	$total_neto_general = 0;
	$cantidad_ = 0;

	while($registrodetalles = $result_detalles->fetch_assoc()){
			$precio += $registrodetalles["precio"];
			$cantidad += $registrodetalles["cantidad"];
			$descuento += $registrodetalles["descuento"];
			$total_precio = $registrodetalles["precio"] * $registrodetalles["cantidad"];
			$neto_antes_isv += $total_precio;
			$isv_neto += $registrodetalles["isv_valor"];
			$cantidad_ = $registrodetalles["cantidad"];
	}

	$total = ($neto_antes_isv + $isv_neto) - $descuento;

	if($registro2['numero'] == 0){
		$numero = "Aún no se ha generado";		
	}else{
		$numero = $registro2['prefijo'].''.rellenarDigitos($registro2['numero'], $registro2['relleno']);
	}

	$estado = $registro2['estado'];
	$factura = "";
	$eliminar = "";
	$pay = "";
	$send_mail = "";
	$pay_credit = "";

	if($estado==1){
		$eliminar = '<a style="text-decoration:none;" data-toggle="tooltip" data-placement="right" href="javascript:deleteBill('.$registro2['facturas_id'].');void(0);" class="fas fa-trash fa-lg" title="Imprimir Factura"></a>';
	}

	if($estado==2 || $estado==3 || $estado==4){
		$factura = '<a style="text-decoration:none;" data-toggle="tooltip" data-placement="right" href="javascript:printBill('.$registro2['facturas_id'].');void(0);" class="fas fa-print fa-lg" title="Imprimir Factura"></a>';
	}

	if($estado == 2){
		$send_mail = '<a style="text-decoration:none;" data-toggle="tooltip" data-placement="right" href="javascript:mailBill('.$registro2['facturas_id'].');void(0);" class="far fa-paper-plane fa-lg" title="Enviar Factura"></a>';
	}
	
	if($estado == 4){
		$pay_credit = '<a style="text-decoration:none;" data-toggle="tooltip" data-placement="right" href="javascript:pago('.$registro2['facturas_id'].');void(0);" class="fab fa-amazon-pay fa-lg" title="Pagar Factura"></a>';		
	}		

	$estado_ = "";
	if($estado == 1){
		$estado_ = "Borrador";
	}else if($estado == 2){
		$estado_ = "Pagada";
	}else if($estado == 4){
		$estado_ = "Crédito";
	}else{
		$estado_ = "Cancelada";
	}

	if($estado==1){
		$pay = '<a style="text-decoration:none;" data-toggle="tooltip" data-placement="right" title = "Realizar Cobro" href="javascript:pay('.$registro2['facturas_id'].');void(0);" class="fas fa-file-invoice fa-lg"></a>';
	}

	$paciente = $registro2['paciente'];
	$empresa = "";
	if($paciente != ""){
		$empresa = $registro2['empresa']." (<b>Paciente</b>: ".$paciente.")";
	}else{
		$empresa = $registro2['empresa'];
	}

	$paciente_empresa = $registro2['codigoPacienteEmpresa'];
	$muestras_id = $registro2['muestras_id'];
	$profesional = $registro2['profesional'];
	$colaborador_id = $registro2['colaborador_id'];

	$tabla = $tabla.'<tr>
			<td><input class="itemRowFactura" type="checkbox" name="itemFactura" id="itemFactura_'.$fila.'" value="'.$facturas_id.'"></td>
			<td>'.$i.'</td>
			<td>'.$registro2['fecha'].'</td>
			<td>'.$registro2['muestra'].'</td>
			<td>'.$numero.'</td>
			<td>'.$empresa.'</td>
			<td>'.$registro2['identidad'].'</td>
			<td>'.$registro2['profesional'].'</td>
            <td>'.number_format($precio,2).'</td>
            <td>'.number_format($isv_neto,2).'</td>
			<td>'.number_format($descuento,2).'</td>
			<td>
				<div name="quantyGrupoQuantityValor" id="quantyGrupoQuantityValor_'.$facturas_id.'" data-value='.$cantidad_.'></div>
				<div name="profesionalIDGrupo" id="profesionalIDGrupo_'.$facturas_id.'" data-value='.$colaborador_id.'></div>
				<div name="muestraGrupo" id="muestraGrupo_'.$facturas_id.'" data-value='.$muestras_id.'></div>
				<div name="codigoFacturaGrupo" id="codigoFacturaGrupo_'.$facturas_id.'" data-value='.$facturas_id.'></div>
				<div name="pacientesIDFacturaGrupo" id="pacientesIDFacturaGrupo_'.$facturas_id.'" data-value='.$paciente_empresa.'></div>
				<div name="importeFacturaGrupo" id="importeFacturaGrupo_'.$facturas_id.'" data-value='.$total.'></div>'.number_format($total,2).'
				<div name="ISVFacturaGrupo" id="precioFacturaGrupo_'.$facturas_id.'" data-value='.$precio.'></div>
				<div name="ISVFacturaGrupo" id="ISVFacturaGrupo_'.$facturas_id.'" data-value='.$isv_neto.'></div>
				<div name="DescuentoFacturaGrupo" id="DescuentoFacturaGrupo_'.$facturas_id.'" data-value='.$descuento.'></div>
				<div name="DescuentoFacturaGrupo" id="netoAntesISVFacturaGrupo_'.$facturas_id.'" data-value='.$neto_antes_isv.'></div>
				</td>
			<td>'.$estado_.'</td>
			<td>
			  '.$send_mail.'
			  '.$pay_credit.'
			  '.$pay.''.$factura.'
			  '.$eliminar.'
			</td>
			</tr>';
			$i++;
			$fila++;
}

if($nroProductos == 0){
	$tabla = $tabla.'<tr>
	   <td colspan="14" style="color:#C7030D">No se encontraron resultados, seleccione un profesional para verificar si hay registros almacenados</td>
	</tr>';
}else{
   $tabla = $tabla.'<tr>
	  <td colspan="14"><b><p ALIGN="center">Total de Registros Encontrados '.$nroProductos.'</p></b>
   </tr>';
}
$tabla = $tabla.'</table>';

$array = array(0 => $tabla,
			   1 => $lista);

echo json_encode($array);

$result->free();//LIMPIAR RESULTADO
$mysqli->close();//CERRAR CONEXIÓN
?>
