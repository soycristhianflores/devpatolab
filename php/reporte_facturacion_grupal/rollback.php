<?php 
session_start();   
include "../funtions.php";
	
//CONEXION A DB
$mysqli = connect_mysqli(); 

$facturas_id = $_POST['facturas_id'];
$comentario = cleanStringStrtolower($_POST['comentario']);
$fecha_registro = date("Y-m-d H:i:s");
$fecha = date("Y-m-d");
$usuario = $_SESSION['colaborador_id'];
$estado = 3; //1. Borrador 2. Pagada 3. Cancelado
$estado_atencion = 1;//ESTADO DE LA ATENCION DEL PACIENTE PARA LA FACTURACION 1. PENDIENTE 2. PAGADA

//ACTUALIZAMOS EL METODO DE PAGO, CAMBIAMOS EL ESTADO A CANCELADO
$update_factura = "UPDATE facturas_grupal SET estado = '3' WHERE facturas_grupal_id  = '$facturas_id'";
$query = $mysqli->query($update_factura) or die($mysqli->error);

if($query){
	echo 1;//FACTURA CANCELADA CORRECTAMENTE
	//ACTUALIZAMOS LA FACTURA, CAMBIAMOS EL ESTADO A CANCELADO
	$update_metodoPago = "UPDATE pagos_grupal SET estado = '2' WHERE facturas_grupal_id = '$facturas_id'";
	$mysqli->query($update_factura) or die($mysqli->error);
	
	//CONSULTAMOS LAS FACTRURAS ID PARA ANULARLAS
	$query_facturas = "SELECT facturas_id
		FROM facturas_grupal_detalle
		WHERE facturas_grupal_id = '$facturas_id'";
	$result_faturas = $mysqli->query($query_facturas) or die($mysqli->error);

	while($registro2 = $result_faturas->fetch_assoc()){
		$factura_consulta_id = $registro2['facturas_id'];

		//ANULAMOS LA FACTURA
		$update_factura = "UPDATE facturas SET estado = '3' WHERE facturas_id = '$factura_consulta_id'";
		$mysqli->query($update_factura) or die($mysqli->error);		

		//ANULAMOS EL PAGO
		$update_factura = "UPDATE pagos SET estado = '2' WHERE facturas_id = '$factura_consulta_id'";
		$mysqli->query($update_factura) or die($mysqli->error);		
	}	
}else{
	echo 2;//ERROR AL CANCELAR LA FACTURA
}
?>