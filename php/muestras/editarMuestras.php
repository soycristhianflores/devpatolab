<?php	
session_start();   
include "../funtions.php";
	
//CONEXION A DB
$mysqli = connect_mysqli();

$muestras_id  = $_POST['muestras_id'];

$query = "SELECT p.pacientes_id AS 'pacientes_id', CONCAT(p.nombre, ' ', p.apellido) As 'paciente', m.fecha AS 'fecha', m.diagnostico_clinico AS 'diagnostico_clinico', m.material_eviando As 'material_eviando', m.datos_clinico As 'datos_clinico',
(CASE WHEN m.estado = '1' THEN 'Atendido' ELSE 'Pendiente' END) AS 'estatus', m.muestras_id  As 'muestras_id', m.mostrar_datos_clinicos As 'mostrar_datos_clinicos', m.colaborador_id AS 'remitente', m.hospitales_id AS 'hospitales_id', m.servicio_id AS 'servicio_id', m.colaborador_id AS 'colaborador_id', m.sitio_muestra AS 'sitio_muestra', m.tipo_muestra_id AS 'tipo_muestra_id', m.categoria_id AS 'categoria_id'
	FROM muestras AS m
	INNER JOIN pacientes AS p
	ON m.pacientes_id = p.pacientes_id
	WHERE muestras_id = '$muestras_id'";
$result = $mysqli->query($query) or die($mysqli->error);

$paciente_consulta = "";
$fecha = "";
$diagonostico_muestra = "";
$material_muestra = "";
$datos_relevantes_muestras = "";
$mostrar_datos_clinicos = "";
$remitente = "";
$hospitales_id = "";
$servicio_id = "";
$sitio_muestra = "";
$tipo_muestra_id = "";
$categoria_id = "";

if($result->num_rows>=0){	
	$valores2 = $result->fetch_assoc();

	$paciente_consulta = $valores2['pacientes_id'];
	$fecha = $valores2['fecha'];
	$diagonostico_muestra = $valores2['diagnostico_clinico'];
	$material_muestra = $valores2['material_eviando'];
	$datos_relevantes_muestras = $valores2['datos_clinico'];
	$mostrar_datos_clinicos = $valores2['mostrar_datos_clinicos'];
	$remitente = $valores2['remitente'];
	$hospitales_id = $valores2['hospitales_id'];
	$servicio_id = $valores2['servicio_id'];	
	$sitio_muestra = $valores2['sitio_muestra'];
	$tipo_muestra_id = $valores2['tipo_muestra_id'];	
	$categoria_id  = $valores2['categoria_id'];		
}

//CONSULTAR EL PACIENTE SI ES ENVIADO POR UNA EMPRESA O CLINICA
$query_paciente = "SELECT p.pacientes_id, CONCAT(p.nombre, ' ', p.apellido) As 'paciente'
	FROM muestras_hospitales AS mh
	INNER JOIN pacientes AS p
	ON mh.pacientes_id = p.pacientes_id
	WHERE mh.muestras_id = '$muestras_id'";
$result_paciente = $mysqli->query($query_paciente) or die($mysqli->error);

$pacientes_id_cliente_codigo = "";
$pacientes_id_cliente = "";

if($result_paciente->num_rows>0){	
	$valores_paciente = $result_paciente->fetch_assoc();
	$pacientes_id_cliente_codigo = $valores_paciente['pacientes_id'];
	$pacientes_id_cliente = $valores_paciente['paciente'];	
}

$datos = array(
	0 => $paciente_consulta,
	1 => $fecha,
	2 => $diagonostico_muestra,
	3 => $material_muestra,
	4 => $datos_relevantes_muestras,
	5 => $mostrar_datos_clinicos,
	6 => $remitente,
	7 => $hospitales_id,
	8 => $servicio_id,
	9 => $sitio_muestra,
	10 => $tipo_muestra_id,
	11 => $pacientes_id_cliente_codigo,	
	12 => $categoria_id,		
);	

echo json_encode($datos);