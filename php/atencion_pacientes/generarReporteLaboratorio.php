<?php
session_start();   
include "../funtions.php";

header("Content-Type: text/html;charset=utf-8");

include_once "../../dompdf/autoload.inc.php";
require_once '../../pdf/vendor/autoload.php';

use Dompdf\Dompdf;
		
//CONEXION A DB
$mysqli = connect_mysqli();

date_default_timezone_set('America/Tegucigalpa');

$noAtencion = $_GET['atencion_id'];
$anulada = '';

$query = "SELECT e.nombre AS 'empresa', e.ubicacion AS 'direccion_empresa', e.telefono As 'empresa_telefono', e.correo AS 'empresa_correo', CONCAT(p.nombre,' ',p.apellido) AS 'empresa', p.edad AS 'edad', 
(CASE WHEN p.genero = 'H' THEN 'Hombre' ELSE 'Mujer' END) AS 'genero', CONCAT(c.nombre,' ',c.apellido) AS 'medico_remitente', m.diagnostico_clinico AS 'diagnostico_clinico', DATE_FORMAT(m.fecha, '%d/%m/%Y')  AS 'fecha_recibido',  DATE_FORMAT(CAST(at.fecha_registro AS date), '%d/%m/%Y') AS 'fecha_emision_reporte', at.antecedentes AS 'diagnostico', at.historia_clinica AS 'factores_pronostico', at.examen_fisico AS 'descripcion_macroscopica', at.diagnostico AS 'descripcion_microscopica', at.seguimiento AS 'comentario', at.estado As 'estado', m.number AS 'numero', e.otra_informacion As 'otra_informacion', e.eslogan AS 'eslogan', e.celular As 'celular', a.descripcion AS 'adendum', m.sitio_muestra AS 'sitio_muestra', CONCAT(p1.nombre, ' ', p1.apellido) As 'paciente', p1.edad AS 'edad_paciente', (CASE WHEN p1.genero = 'H' THEN 'Hombre' ELSE 'Mujer' END) AS 'genero_paciente', h.nombre AS 'hospital'
FROM atenciones_medicas AS at
	INNER JOIN pacientes AS p
	ON at.pacientes_id = p.pacientes_id
	INNER JOIN muestras AS m
	ON at.muestras_id = m.muestras_id
    INNER JOIN hospitales AS h
    ON m.hospitales_id = h.hospitales_id
	INNER JOIN colaboradores AS c
	ON m.colaborador_id = c.colaborador_id
	INNER JOIN empresa AS e
    ON c.empresa_id = e.empresa_id
    LEFT JOIN adendum AS a
    ON at.atencion_id = a.atencion_id
	LEFT JOIN muestras_hospitales mh
	ON m.muestras_id = mh.muestras_id
	LEFT JOIN pacientes AS p1
	ON mh.pacientes_id = p1.pacientes_id 	
	WHERE at.atencion_id = '$noAtencion'";	
$result = $mysqli->query($query) or die($mysqli->error);

if($result->num_rows>0){
	$consulta_registro = $result->fetch_assoc();
	
	$noAtencion = $consulta_registro['numero'];

	if($consulta_registro['estado'] == 3){
		$anulada = '<img class="anulada" src="../../img/anulado.png" alt="Anulada">';
	}
	
	$image_server = SERVERURL."img/fondo_pagina.jpg";
	ob_start();
	include(dirname('__FILE__').'/reporteLaboratorio.php');
	$html = ob_get_clean();

	// instantiate and use the dompdf class
	$dompdf = new Dompdf();
	
	$dompdf->set_option('isRemoteEnabled', true);

	$dompdf->loadHtml(utf8_decode(utf8_encode($html)));
	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('letter', 'portrait');
	// Render the HTML as PDF
	$dompdf->render();
	
	file_put_contents(dirname('__FILE__').'/Reportes/muestra_'.$noAtencion.'.pdf', $dompdf->output());
	
	// Output the generated PDF to Browser
	$dompdf->stream('reporte_'.$noAtencion.'.pdf',array('Attachment'=>0));
	
	exit;	
}
?>