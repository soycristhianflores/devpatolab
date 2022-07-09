<?php
session_start();   
include "../funtions.php";
	
//CONEXION A DB
$mysqli = connect_mysqli(); 

$facturas_id = $_POST['factura_id_transferencia'];
$fecha = date("Y-m-d");
$fecha_registro = date("Y-m-d H:i:s");
$importe = $_POST['monto_efectivo'];
$cambio = 0;
$empresa_id = $_SESSION['empresa_id'];	
$usuario = $_SESSION['colaborador_id'];			
$tipo_pago_id = 4;//TRANSFERENCIA		
$banco_id = 0;//SIN BANCO
$tipo_pago = 1;//1. CONTADO 2. CRÉDITO
$estado = 2;//FACTURA PAGADA
$estado_atencion = 1;
$estado_pago = 1;//ACTIVO
$fecha_registro = date("Y-m-d H:i:s");
$referencia_pago1 = cleanStringConverterCase($_POST['ben_nm']);//TARJETA DE CREDITO
$referencia_pago2 = "";
$referencia_pago3 = "";
$activo = 1;//SECUENCIA DE FACTURACION
$efectivo = 0;
$tarjeta = 0;	

//CONSULTAR DATOS DE LA SECUENCIA DE FACTURACION
$query_secuencia = "SELECT secuencia_facturacion_id, prefijo, siguiente AS 'numero', rango_final, fecha_limite, incremento, relleno
   FROM secuencia_facturacion
   WHERE activo = '$activo' AND empresa_id = '$empresa_id'";
$result = $mysqli->query($query_secuencia) or die($mysqli->error);
$consulta2 = $result->fetch_assoc();

$secuencia_facturacion_id = "";
$prefijo = "";
$numero = "";
$rango_final = "";
$fecha_limite = "";
$incremento = "";
$no_factura = "";

if($result->num_rows>0){
	$secuencia_facturacion_id = $consulta2['secuencia_facturacion_id'];	
	$prefijo = $consulta2['prefijo'];
	$numero = $consulta2['numero'];
	$rango_final = $consulta2['rango_final'];
	$fecha_limite = $consulta2['fecha_limite'];	
	$incremento = $consulta2['incremento'];
	$no_factura = $consulta2['prefijo']."".str_pad($consulta2['numero'], $consulta2['relleno'], "0", STR_PAD_LEFT);		
}

//CONSULTAR DATOS DE LA FACTURA
$query_factura = "SELECT servicio_id, colaborador_id, fecha, pacientes_id
	FROM facturas_grupal
	WHERE facturas_grupal_id = '$facturas_id'";
$result_factura = $mysqli->query($query_factura) or die($mysqli->error);	
$consultaFactura = $result_factura->fetch_assoc();

$servicio_id = "";
$colaborador_id = "";
$fecha_factura = "";
$pacientes_id = "";

if($result_factura->num_rows>0){
	$servicio_id = $consultaFactura['servicio_id'];	
	$colaborador_id = $consultaFactura['colaborador_id'];	
	$fecha_factura = $consultaFactura['fecha'];	
	$pacientes_id = $consultaFactura['pacientes_id'];		
}

//INSERTAMOS LOS DATOS EN LA ENTIDAD PAGO
//VALIDAMOS QUE EL PAGO PARA LA FACTURA NO EXISTA, DE EXISTIR NO SE ALMACENA
$queryPagos = "SELECT pagos_grupal_id
	FROM pagos_grupal
	WHERE facturas_grupal_id = '$facturas_id'";
$result_ConsultaPagos = $mysqli->query($queryPagos) or die($mysqli->error);

if($result_ConsultaPagos->num_rows==0){
	$pagos_grupal_id = correlativo("pagos_grupal_id","pagos_grupal");
	$insert = "INSERT INTO pagos_grupal 
	VALUES ('$pagos_grupal_id','$facturas_id','$tipo_pago','$fecha_factura','$importe','$efectivo','$cambio','$tarjeta','$usuario','$estado_pago','$empresa_id','$fecha_registro')";
	$query = $mysqli->query($insert);

	if($query){	
		//ACTUALIZAMOS EL DETALLE DEL PAGO
		$pagos_grupal_detalles_id  = correlativo('pagos_grupal_detalles_id', 'pagos_grupal_detalles');
		$insert = "INSERT INTO pagos_grupal_detalles 
			VALUES ('$pagos_grupal_detalles_id','$pagos_grupal_id','$tipo_pago_id','$banco_id','$importe','$referencia_pago1','$referencia_pago2','$referencia_pago3')";
		$query = $mysqli->query($insert);
		/*******************************************************************************************************************************************************************/
		//CONSULTAMOS EL NUMERO DE ATENCION
		$query_atencion = "SELECT atencion_id  
			FROM atenciones_medicas
			WHERE pacientes_id = '$pacientes_id' AND servicio_id = '$servicio_id' AND colaborador_id = '$colaborador_id' AND fecha = '$fecha_factura'";
		$result_atencion = $mysqli->query($query_atencion) or die($mysqli->error);
		$consultaDatosAtencion = $result_atencion->fetch_assoc();

		$atencion_id = "";

		if($result_atencion->num_rows>0){
			$atencion_id = $consultaDatosAtencion['atencion_id'];	
		}
		/*******************************************************************************************************************************************************************/
		//ACTUALIZAMOS EL ESTADO DE LA FACTURA GRUPAL
		$update_factura = "UPDATE facturas_grupal
			SET
				estado = '$estado'
			WHERE facturas_grupal_id = '$facturas_id'";
		$mysqli->query($update_factura) or die($mysqli->error);	
		
		//CONSULTAMOS LOS NUMEROS DE FACTURAS QUE SE ATENDIERON
		$query_facturas = "SELECT facturas_id
			FROM facturas_grupal_detalle
			WHERE facturas_grupal_id = '$facturas_id'";
		$result_facturas = $mysqli->query($query_facturas) or die($mysqli->error);
		
		while($registroFacturas = $result_facturas->fetch_assoc()){//INICIO CICLO WHILE
			$total_valor = 0;
			$descuentos = 0;
			$isv_neto = 0;
			$total_despues_isv = 0;
	
			$facturaConsulta = $registroFacturas['facturas_id'];
			
			//CONSULTAMOS EL TOTAL EN EL DETALLE DE LAS FACTURAS
			$query_facturas_detalles = "SELECT cantidad, precio, isv_valor, descuento
				FROM facturas_detalle
				WHERE facturas_id = '$facturaConsulta'";
			$result_facturas_detalles = $mysqli->query($query_facturas_detalles) or die($mysqli->error);
			
			while($registroFacturasDetalles = $result_facturas_detalles->fetch_assoc()){
				$total_valor += ($registroFacturasDetalles['precio'] * $registroFacturasDetalles['cantidad']);
				$descuentos += $registroFacturasDetalles['descuento'];
				$isv_neto += $registroFacturasDetalles['isv_valor'];				
			}
			$total_despues_isv = ($total_valor + $isv_neto) - $descuentos;
			$cambio_ = 0;
			
			//CONSULTAR DATOS DE LA FACTURA
			$query_factura_grupal_consulta = "SELECT servicio_id, colaborador_id, fecha, pacientes_id
				FROM facturas
				WHERE facturas_id = '$facturaConsulta'";
			$result_factura_grupal_consulta = $mysqli->query($query_factura_grupal_consulta) or die($mysqli->error);	
			$consultaFactura_result_factura_grupal_consulta = $result_factura_grupal_consulta->fetch_assoc();

			$fecha_factura = "";

			if($result_factura_grupal_consulta->num_rows>0){	
				$fecha_factura = $consultaFactura_result_factura_grupal_consulta['fecha'];	
			}
			
			//ACTUALIZAMOS EL PAGO DE LA FACTURA CONSULTADA
			//INSERTAMOS LOS DATOS EN LA ENTIDAD PAGO
			$pagos_id = correlativo("pagos_id","pagos");
			$insert = "INSERT INTO pagos 
			VALUES ('$pagos_id','$facturaConsulta','$tipo_pago','$fecha','$total_despues_isv','$cambio','$usuario','$estado_pago','$empresa_id','$fecha_registro')";
			$query_pagos = $mysqli->query($insert);
			
			if($query_pagos){
				//ACTUALIZAMOS EL DETALLE DEL PAGO
					$pagos_detalles_id = correlativo("pagos_detalles_id","pagos_detalles");
					$insert = "INSERT INTO pagos_detalles 
					VALUES ('$pagos_detalles_id','$pagos_id','$tipo_pago_id','$banco_id','$total_despues_isv','$referencia_pago1','$referencia_pago2','$referencia_pago3')";
					$query = $mysqli->query($insert);				
			}
			
			//ACTUALIZAMOS EL ESTADO DE LA FACTURA
			$update_factura = "UPDATE facturas
				SET
					estado = '$estado',
					number = '$numero'
				WHERE facturas_id = '$facturaConsulta'";
			$mysqli->query($update_factura) or die($mysqli->error);				
		}//FIN CICLO WHILE
		
		//ACTUALIZAMOS EL ESTADO DE LA ATENCION PARA SABER SI SE PAGO O NO LA FACTURA
		$update_atencion = "UPDATE atenciones_medicas 
			SET 
				estado = '$estado_atencion' 
			WHERE atencion_id  = '$atencion_id'";
		$mysqli->query($update_atencion) or die($mysqli->error);
		
		//CONSULTAMOS EL NUMERO QUE SIGUE DE EN LA SECUENCIA DE FACTURACION
		$numero_secuencia_facturacion = correlativo("siguiente", "secuencia_facturacion");
		
		//ACTUALIZAMOS LA SECUENCIA DE FACTURACION AL NUMERO SIGUIENTE		
		$update = "UPDATE secuencia_facturacion 
		SET 
			siguiente = '$numero_secuencia_facturacion' 
		WHERE secuencia_facturacion_id = '$secuencia_facturacion_id'";
		$mysqli->query($update);

		$datos = array(
			0 => "Almacenado", 
			1 => "Pago Realizado Correctamente, ¿Desea enviar esta factura por correo electrónico?", 
			2 => "info",
			3 => "btn-primary",
			4 => "formTransferenciaBillGrupal",
			5 => "Registro",
			6 => "PagosGrupal",//FUNCION DE LA TABLA QUE LLAMAREMOS PARA QUE ACTUALICE (DATATABLE BOOSTRAP)
			7 => "modal_grupo_pagos", //Modals Para Cierre Automatico
			8 => $facturas_id, //Modals Para Cierre Automatico
			9 => "Guardar" //confirmButtonText
		);
	}else{//NO SE PUEDO ALMACENAR ESTE REGISTRO
		$datos = array(
			0 => "Error", 
			1 => "No se puedo almacenar este registro, los datos son incorrectos por favor corregir", 
			2 => "error",
			3 => "btn-danger",
			4 => "",
			5 => "",			
		);
	}		
}else{//NO SE PUEDO ALMACENAR ESTE REGISTRO
	$datos = array(
		0 => "Error", 
		1 => "No se puedo almacenar este registro, los datos son incorrectos por favor corregir", 
		2 => "error",
		3 => "btn-danger",
		4 => "",
		5 => "",			
	);
}

echo json_encode($datos);
?>