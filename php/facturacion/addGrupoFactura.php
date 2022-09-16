<?php
session_start();   
include "../funtions.php";
	
//CONEXION A DB
$mysqli = connect_mysqli(); 

$pacientes_id = $_POST['clienteIDGrupo'];
$fecha = $_POST['fechaGrupo'];
$colaborador_id = $_POST['colaborador_idGrupo'];
$servicio_id = $_POST['servicio_idGrupo'];
$notes = cleanStringStrtolower($_POST['notesBillGrupo']);
$tamaño = intval($_POST['tamano']);
$usuario = $_SESSION['colaborador_id'];
$empresa_id = $_SESSION['empresa_id'];
$fecha_registro = date("Y-m-d H:i:s");
$activo = 1;
$estado = 4;//ESTADO FACTURA CREDITO
$cierre = 2;
$importe = 0;
$tipo = "";
$estado_factura = 1;//BORRADOR
$numero = 0; //NUMERO DE FACTURA AUN NO GENERADO

if(isset($_POST['facturas_grupal_activo'])){//COMPRUEBO SI LA VARIABLE ESTA DIFINIDA
	if($_POST['facturas_grupal_activo'] == ""){
		$tipo_factura = 2;
		$tipo = "facturacionGrupalCredito";
	}else{
		$tipo_factura = $_POST['facturas_grupal_activo'];
		$tipo = "facturacionGrupal";
	}
}else{
	$tipo_factura = 2;
	$tipo = "facturacionGrupalCredito";
}

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

//OBTENEMOS EL TAMAÑO DE LA TABLA
if(isset($_POST['pacienteIDBillGrupo'])){	
	if($_POST['pacienteIDBillGrupo'][0] != "" && $_POST['importeBillGrupo'][0] != "" && $_POST['totalBillGrupo'][0] != ""){
		$tamano_tabla = $tamaño;
	}else{
		$tamano_tabla = 0;
	}
}else{
	$tamano_tabla = 0;
}

if($tamano_tabla >0){
	//INSERTAMOS LOS DATOS EN LA ENTIDAD FACTURA
	$facturas_grupal_id = correlativo("facturas_grupal_id","facturas_grupal");
	$insert = "INSERT INTO facturas_grupal 
		VALUES('$facturas_grupal_id','$secuencia_facturacion_id','$numero','$tipo_factura','$pacientes_id','$colaborador_id','$servicio_id','$importe','$notes','$fecha','$estado_factura','$cierre','$usuario','$empresa_id','$fecha_registro')";
	$query = $mysqli->query($insert);

	if($query){			
		$total_valor = 0;
		$descuentos = 0;
		$isv_neto = 0;
		$total_despues_isv = 0;
		$lineaImporte = 0;
		$lineaISV = 0;
		$lineaDescuento = 0;
		$lineaCantidad = 0;

		//ALMACENAMOS EL DETALLE DE LA FACTURA EN LA ENTIDAD FACTURAS DETALLE
		for ($i = 0; $i < $tamaño; $i++){//INICIO CICLO FOR		
			$facturas_grupal_detalle_id = correlativo("facturas_grupal_detalle_id","facturas_grupal_detalle");
			$muestra_id = $_POST['billGrupoMuestraID'][$i];
			$materialEnviado = $_POST['billGrupoMaterial'][$i];
			$lineaFactura_id = $_POST['billGrupoID'][$i];
			$lineaPacientes_id = $_POST['pacienteIDBillGrupo'][$i];	
			$lineaImporte = $_POST['importeBillGrupo'][$i];	
			
			if($_POST['billGrupoISV'][$i] != "" || $_POST['billGrupoISV'][$i] != null){
				$lineaISV = $_POST['billGrupoISV'][$i];	
			}

			if($_POST['discountBillGrupo'][$i] != "" || $_POST['discountBillGrupo'][$i] != null){
				$lineaDescuento = $_POST['discountBillGrupo'][$i];
			}

			
			$lineaTotal = $_POST['totalBillGrupo'][$i];	
			$lineaCantidad = $_POST['quantyGrupoQuantity'][$i];				
			$isv_valor = 0;
						
			if($muestra_id != "" && $lineaFactura_id != "" && $lineaImporte != "" && $lineaTotal !="" && $lineaCantidad != ""){
				//CAMBIAMOS EL FORMATO DE PAGO A LAS FACTURAS DE CADA LINEA, AGREGANDO EL NUMERO DE FACTURA GENERADO
				$update = "UPDATE facturas
					SET
						fecha = '$fecha',
						estado = '$estado'
					WHERE facturas_id = '$lineaFactura_id'";
				$mysqli->query($update);
				
				//INSERTAMOS EL DETALLE DEL GRUPO DE FACTURAS				
				$insert_detalle = "INSERT INTO facturas_grupal_detalle 
					VALUES('$facturas_grupal_detalle_id','$facturas_grupal_id','$lineaFactura_id','$lineaPacientes_id','$muestra_id','$lineaCantidad','$lineaImporte','$lineaISV','$lineaDescuento')";
				$mysqli->query($insert_detalle);

				$total_valor += $lineaImporte;
				$descuentos += $lineaDescuento;
				$isv_neto += $lineaISV;
			}
		}//FIN CICLO FOR
		$total_despues_isv = ($total_valor + $isv_neto) - $descuentos;
		
		//ACTUALIZAMOS EL IMPORTE DE LA FACTURA
		$update = "UPDATE facturas_grupal
			SET
				importe = '$total_despues_isv',
				usuario = '$usuario'
			WHERE facturas_grupal_id = '$facturas_grupal_id'";
		$mysqli->query($update);		
		
		$datos = array(
			0 => "Almacenado", 
			1 => "Registro Almacenado Correctamente", 
			2 => "success",
			3 => "btn-primary",
			4 => "formGrupoFacturacion",
			5 => "Registro",
			6 => $tipo,//FUNCION DE LA TABLA QUE LLAMAREMOS PARA QUE ACTUALICE (DATATABLE BOOSTRAP)
			7 => "", //Modals Para Cierre Automatico
			8 => $facturas_grupal_id, //Modals Para Cierre Automatico		
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
}else{
	$datos = array(
		0 => "Error", 
		1 => "No se puedo almacenar este registro, los datos son incorrectos por favor corregir, verifique si hay registros en blanco antes de enviar los datos de la factura, se le recuerda que el detalle de la factura no puede quedar vacío", 
		2 => "error",
		3 => "btn-danger",
		4 => "",
		5 => "",			
	);	
}

echo json_encode($datos);
?>