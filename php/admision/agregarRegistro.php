<?php
session_start();   
include "../funtions.php";
	
//CONEXION A DB
$mysqli = connect_mysqli();

if(isset($_POST['cliente_admision'])){//COMPRUEBO SI LA VARIABLE ESTA DIFINIDA
	if($_POST['cliente_admision'] == ""){
		$cliente_admision = 0;
	}else{
		$cliente_admision = $_POST['cliente_admision'];
	}
}else{
	$cliente_admision = 0;
}

$nombre = cleanString($_POST['name']);
$apellido = cleanString($_POST['lastname']);
$identidad = $_POST['rtn'];

//CONSULTAR IDENTIDAD DEL USUARIO
if($identidad == 0){
	$flag_identidad = true;
	while($flag_identidad){
	   $d=rand(1,99999999);
	   $query_identidadRand = "SELECT pacientes_id 
	       FROM pacientes 
		   WHERE identidad = '$d'";
	   $result_identidad = $mysqli->query($query_identidadRand);
	   if($result_identidad->num_rows==0){
		  $identidad = $d;
		  $flag_identidad = false;
	   }else{
		  $flag_identidad = true;
	   }		
	}
}

$fecha_nacimiento = $_POST['fecha_nac'];
$edad = $_POST['edad'];
$telefono1 = $_POST['telefono1'];
$telefono2 = "";
$genero = $_POST['genero'];
$departamento_id = 0;
$municipio_id = 0;
$localidad = cleanString($_POST['direccion']);
$correo = strtolower(cleanString($_POST['correo']));
$fecha = date("Y-m-d");
$religion_id = 0;
$profesion_id = 0;
$paciente_tipo = 1;//1. CLIENTE 2. EMPRESA
$usuario = $_SESSION['colaborador_id'];
$estado = 1; //1. Activo 2. Inactivo
$fecha_registro = date("Y-m-d H:i:s");

if($cliente_admision == 0){//NO SE SELECCIONO NINGUN CLIENTE
	//CONSULTAMOS SI EXISTE EL PACIENTE ANTES DE ALMACENARLO
	$select = "SELECT pacientes_id
		FROM pacientes
		WHERE identidad = '$identidad' AND nombre = '$nombre' AND apellido = '$apellido' AND genero = '$genero'";
	$result = $mysqli->query($select) or die($mysqli->error);

	if($result->num_rows==0)//RREGISTRO NO EXISTE PROCEDEMOS A ALMACENARLO
	{
		$pacientes_id = correlativo('pacientes_id ', 'pacientes');
		$expediente = correlativo('expediente ', 'pacientes');
		$insert = "INSERT INTO pacientes VALUES ('$pacientes_id','$expediente','$identidad','$nombre','$apellido','$genero','$telefono1','$telefono2','$fecha_nacimiento','$edad','$correo','$fecha','$departamento_id','$municipio_id','$localidad','$religion_id','$profesion_id','$usuario','$estado','$paciente_tipo','$fecha_registro')";
		$query = $mysqli->query($insert);
	}else{
		$consulta2 = $result->fetch_assoc();
		$cliente_admision_id = $consulta2['pacientes_id'];
		$pacientes_id = $cliente_admision_id;
		$update = "UPDATE pacientes 
						SET 
							telefono1 = '$telefono1'
							,edad = '$edad'
							,email = '$correo' 
						WHERE pacientes_id = '$cliente_admision_id'";

		$query = $mysqli->query($update);
	}
}else{
	$update = "UPDATE pacientes 
					SET 
						telefono1 = '$telefono1'
						,edad = '$edad'
						,email = '$correo' 
				WHERE pacientes_id = '$cliente_admision'";
	$pacientes_id = $cliente_admision;
	$query = $mysqli->query($update);	
}

if($query){
	//AGREGAMOS LOS DATOS DE LA MUESTRA
	//DATOS DE LA MUESTRA
	$servicio_id = 1;

	if(isset($_POST['remitente'])){//COMPRUEBO SI LA VARIABLE ESTA DIFINIDA
		if($_POST['remitente'] == ""){
			$colaborador_id = 0;
		}else{
			$colaborador_id = $_POST['remitente'];
		}
	}else{
		$colaborador_id = 0;
	}

	if(isset($_POST['tipo_muestra'])){//COMPRUEBO SI LA VARIABLE ESTA DIFINIDA
		if($_POST['tipo_muestra'] == ""){
			$tipo_muestra_id = 0;
		}else{
			$tipo_muestra_id = $_POST['tipo_muestra'];
		}
	}else{
		$tipo_muestra_id = 0;
	}

	$referencia = cleanString($_POST['referencia']);
	$estado_muestra = 0;
	$identidad = $_POST['rtn'];
	$sitio_muestra = cleanString($_POST['sitio_muestra']);
	$diagnostico_clinico = cleanString($_POST['diagnostico_clinico']);
	$material_enviado = cleanString($_POST['material_enviado']);
	$datos_clinicos = cleanString($_POST['datos_clinicos']);

	if(isset($_POST['mostrar_datos_clinicos'])){//COMPRUEBO SI LA VARIABLE ESTA DIFINIDA
		if($_POST['mostrar_datos_clinicos'] == ""){
			$mostrar_datos_clinicos = 2;
		}else{
			$mostrar_datos_clinicos = $_POST['mostrar_datos_clinicos'];
		}
	}else{
		$mostrar_datos_clinicos = 2;
	}		

	if(isset($_POST['empresa'])){//COMPRUEBO SI LA VARIABLE ESTA DIFINIDA
		if($_POST['empresa'] == ""){
			$empresa = 0;
		}else{
			$empresa = $_POST['empresa'];
		}
	}else{
		$empresa = 0;
	}

	if(isset($_POST['hospital'])){//COMPRUEBO SI LA VARIABLE ESTA DIFINIDA
		if($_POST['hospital'] == ""){
			$hospital_clinica = 0;
		}else{
			$hospital_clinica = $_POST['hospital'];
		}
	}else{
		$hospital_clinica = 0;
	}

	if(isset($_POST['categoria'])){//COMPRUEBO SI LA VARIABLE ESTA DIFINIDA
		if($_POST['categoria'] == ""){
			$categoria_muestras = 0;
		}else{
			$categoria_muestras = $_POST['categoria'];
		}
	}else{
		$categoria_muestras = 0;
	}	
			
	//CONSULTAMOS LA SECUENCIA PARA LA ENTIDAD
	$estado_secuencia = 1;
	$query_secuencia = "SELECT *
		FROM secuencias_muestas
		WHERE tipo_muestra_id = '$tipo_muestra_id' AND estado = '$estado_secuencia'";
	$result_secuencia = $mysqli->query($query_secuencia) or die($mysqli->error);	

	$number = "";
	$flag = false;

	if($result_secuencia->num_rows>=0){	
		$flag = true;
		$consulta = $result_secuencia->fetch_assoc();
		$prefijo = $consulta["prefijo"];
		$sufijo = $consulta["sufijo"];
		$relleno = $consulta["relleno"];
		$incremento = $consulta["incremento"];	
		$siguiente = $consulta["siguiente"];
		$secuencias_id = $consulta["secuencias_id"];

		$numero = str_pad($consulta['siguiente'], $consulta['relleno'], "0", STR_PAD_LEFT);
		$año_actual = date("Y");
		$mes_actual = date("m");
		$dia_actual = date("d");
		$dia_semana = date("N");
			
		$prefijo = str_replace("@año_actual", $año_actual, $prefijo);
		$prefijo = str_replace("@mes_actual", $mes_actual, $prefijo);
		$prefijo = str_replace("@dia_actual", $dia_actual, $prefijo);
		$prefijo = str_replace("@dia_semana", $dia_semana, $prefijo);	
		
		$number .= $prefijo.$numero;
		
		$sufijo = str_replace("@año_actual", $año_actual, $sufijo);
		$sufijo = str_replace("@mes_actual", $mes_actual, $sufijo);
		$sufijo = str_replace("@dia_actual", $dia_actual, $sufijo);
		$sufijo = str_replace("@dia_semana", $dia_semana, $sufijo);	

		$number .= $sufijo;
	}	

	$muestras_id  = correlativo('muestras_id', 'muestras');
	$insert = "INSERT INTO muestras			VALUES('$muestras_id','$pacientes_id','$secuencias_id','$servicio_id','$colaborador_id','$tipo_muestra_id','$number','$referencia','$fecha','$estado_muestra','$sitio_muestra','$diagnostico_clinico','$material_enviado','$datos_clinicos','$mostrar_datos_clinicos','$hospital_clinica','$categoria_muestras','$usuario','$fecha_registro')";
	$mysqli->query($insert) or die($mysqli->error);
	
	if($flag){
		$siguiente += $incremento;
		
		//ACTUAIZAMOS EL NUMERO SIGUIENTE EN EL ADMINISTRADOR DE SECUENCIAS
		$update_secuencia = "UPDATE secuencias_muestas
			SET 
				siguiente = '$siguiente'
			WHERE secuencias_id = '$secuencias_id'";
		$mysqli->query($update_secuencia) or die($mysqli->error);
	}

	//OBTENER NOMBRE DEL CLIENTE EN EL REGISTRO DE LA MUESTRA
	$query_cliente_muestra = "SELECT CONCAT(p.nombre, ' ', p.apellido) AS 'cliente'
		FROM muestras AS m
		INNER JOIN pacientes AS p
		ON m.pacientes_id = p.pacientes_id
		WHERE m.muestras_id = 1";
	$result_cliente_muestra = $mysqli->query($query_cliente_muestra) or die($mysqli->error);

	$cliente_muestra = "";

	if($result_cliente_muestra->num_rows>0){
		$valores2 = $result_cliente_muestra->fetch_assoc();

		$cliente_muestra = $valores2['cliente'];					
	}

	/*if($categoria_muestras != 0){
		//CONSULTAMOS LA CATEGORIA DE LA MUESTRA
		$query_categoria = "SELECT nombre, tiempo
			FROM categoria
			WHERE categoria_id = '$categoria_muestras'";
		$result_categoria = $mysqli->query($query_categoria) or die($mysqli->error);

		$nombre_categoria = "";
		$tiempo_categoria = 0;

		if($result_categoria->num_rows>0){
			$valores2 = $result_categoria->fetch_assoc();

			$nombre_categoria = $valores2['nombre'];
			$tiempo_categoria = $valores2['tiempo'];	
			
			//SUMAMOS EL TIEMPO A LA FECHA ACTUAL
			$fecha_inicio = date("Y-m-d");
			$fecha_fin = date("Y-m-d",strtotime($fecha_inicio."+ ".$tiempo_categoria." days"));

			//OBTENER LA CANTIDAD DE DOMINGOS EN UN INTERVALO DADO
			$total_dias_sin_domingo = difDiasSinFinDeSemana($fecha_inicio, $fecha_fin);

			//EVALUAMOS SI LA FECHA INICIAL ES DOMINGO
			$domingo_fecha_actual = obtenerDomingo($fecha_inicio);
			$total_dias = $total_dias_sin_domingo;

			if($domingo_fecha_actual == "Domingo"){
				$total_dias = $total_dias - 1;
			}

			//RESTAMOS DEL TIEMPO_CATEGORIA LA CANTIDAD DE DIAS SIN DOMINGO PARA OBTENER CUANDOS VALORES RESTAN (LOS QUE SON DOMINGOS) PARA LUEGO SUMARLOS A LA FECHA
			$total_dias_domingo_general = $tiempo_categoria - $total_dias;
			
			if($total_dias_domingo_general < 0){
				$total_dias_domingo_general = $total_dias_domingo_general * -1;
			}
			
			//SUMAMOS LA CANTIDAD DE DOMINGOS A LA FECHA FINAL
			$fecha_fin_nueva = date("Y-m-d",strtotime($fecha_fin."+ ".$total_dias_domingo_general." days"));

			//EVALUAMOS SI LA ULTIMA FECHA ES DOMINGO, DE SERLO SUMAMOS UN DIA A LA NUEVA FEHCA		
			if(obtenerDomingo($fecha_fin_nueva)== "Domingo"){
				$nueva_fecha_fin = date("Y-m-d",strtotime($fecha_fin_nueva."+ 1 days"));
			}else{
				$nueva_fecha_fin = $fecha_fin_nueva;
			}

			//VALIDAMOS LOS COLORES A UTILIZAR SEGUN LA CATEGORIA DE LA MUESTRA
			if($categoria_muestras == 1){
				$color = '#008000';//COLOR VERDE
			}else if($categoria_muestras == 2){
				$color = '#0071c5';//COLOR AZUL
			}else if($categoria_muestras == 3){
				$color = '#DF0101';//COLOR ROJO
			}else if($categoria_muestras == 4){
				$color = '#824CC8';//COLOR MORADO
			}else{
				$color = "#FF5733";//COLOR NARANJA
			}					

			//OBTENEMOS LA CANTIDAD DE USUAIOS ACITVOS EN EL CALENDARIO
			$consulta_cita_usuarios = "SELECT calendario_id
				FROM calendario
				WHERE CAST(fecha_cita AS DATE) = '$nueva_fecha_fin'";
			$result_cita_usuarios = $mysqli->query($consulta_cita_usuarios) or die($mysqli->error);

			if($result_cita_usuarios->num_rows==0){
				$hora_inicio = "07:00:00";
				$hora_fin = "07:40:00";

				//APARTIR DE LA FECHA FINA ES QUE ESTABLECEMOS LA FECHA DE INICIO Y FECHA DE FIN PARA LA MUESTRA EN EL CALENDARIO
				$start = date("Y-m-d H:i:s", strtotime($nueva_fecha_fin." ".$hora_inicio));
				$end = date("Y-m-d H:i:s", strtotime($nueva_fecha_fin." ".$hora_fin));		
			}else{
				//OBTENEMOS LA ULTIMA HORA AGENDADA EN EL CALENDARIO Y LE SUMAMOS UNA CUARENTA MINUTOS
				$nueva_fecha_fin_ = date('Y-m-d', strtotime($nueva_fecha_fin));

				$query_ultima_fecha_calendario = "SELECT fecha_cita
					FROM calendario
					WHERE CAST(fecha_cita AS DATE) = '$nueva_fecha_fin_'
					ORDER BY fecha_cita DESC LIMIT 1";

				$result_ultima_fecha_calendario = $mysqli->query($query_ultima_fecha_calendario) or die($mysqli->error);
				$valores_ultima_fecha_calendario = $result_ultima_fecha_calendario->fetch_assoc();

				$ultima_fecha_calendario_inicio = date('Y-m-d H:i:s', strtotime('+ 40 minute', strtotime($valores_ultima_fecha_calendario['fecha_cita'])));
				$ultima_fecha_calendario_fin = date('Y-m-d H:i:s', strtotime('+ 40 minute', strtotime($ultima_fecha_calendario_inicio)));

				$hora_inicio = date('H:i',strtotime($ultima_fecha_calendario_inicio));
				$hora_fin = date('H:i',strtotime($ultima_fecha_calendario_fin));

				//APARTIR DE LA FECHA FINA ES QUE ESTABLECEMOS LA FECHA DE INICIO Y FECHA DE FIN PARA LA MUESTRA EN EL CALENDARIO
				$start = date("Y-m-d H:i:s", strtotime($nueva_fecha_fin." ".$hora_inicio));
				$end = date("Y-m-d H:i:s", strtotime($nueva_fecha_fin." ".$hora_fin));	
			}

			$observacion = "Muestra ".$nombre_categoria;
			$estado_calendario = 0;

			//VALIDAMOS QUE LA HORA EN EL CALENDARIO NO ESTE OCUPADA
			$consulta_hora = "SELECT calendario_id
				FROM calendario
				WHERE fecha_cita = '$start' AND fecha_cita_end = '$end'";
			$result_consulta_hora = $mysqli->query($consulta_hora) or die($mysqli->error);

			if($result_consulta_hora->num_rows==0){
				//AGREGAMOS LA MUESTRA EN EL CALENDARIO
				$calendario_id = correlativo('calendario_id', 'calendario');
				$insert = "INSERT INTO calendario VALUE('$calendario_id','$muestras_id','$pacientes_id','$start','$end','$fecha_registro','$color','$colaborador_id','$servicio_id','$observacion','$estado_calendario')";

				$query_muestras_calendario = $mysqli->query($insert) or die($mysqli->error);

				if($query_muestras_calendario){
					//echo '{"id":"'.$calendario_id.'","title":"'.$number."-".$cliente_muestra.'","start":"'.$start.'","end":"'.$end.'","color":"'.$color.'"}';
				}
			}
		}

		$fecha_tentativa = date("d/m/y", strtotime($nueva_fecha_fin));
		$mensaje = "Registro Almacenado Correctamente, la fecha prevista de entrega para esta muestra: ".$number." es: ".$fecha_tentativa;
	}else{
		$mensaje = "Registro Almacenado Correctamente";
	}*/
		
	//SI EL CLIENTE ES EMPRESA, muestras_hospitales
	if($empresa !=0 && $pacientes_id != 0){
		//ALMACENAMOS EL CLIENTE DEL ANALISIS QUE ENVIA LA EMPRESA O LABORATORIO
		$muestras_hospitales_id = correlativo('muestras_hospitales_id', 'muestras_hospitales');
		$insert = "INSERT INTO muestras_hospitales 
			VALUES('$muestras_hospitales_id','$empresa','$pacientes_id','$muestras_id','$fecha','$usuario','$fecha_registro')";
		$query = $mysqli->query($insert) or die($mysqli->error);
	}

	/*********************************************************************************************************************************************************************/
	//INGRESAR REGISTROS EN LA ENTIDAD HISTORIAL
	$historial_numero = historial();
	$estado_historial = "Agregar";
	$observacion_historial = "Se ha agregado un nuevo cliente: $nombre $apellido";
	$modulo = "Pacientes";
	$insert = "INSERT INTO historial 
		VALUES('$historial_numero','0','0','$modulo','$pacientes_id','$usuario','0','$fecha','$estado_historial','$observacion_historial','$usuario','$fecha_registro')";	 
	$mysqli->query($insert) or die($mysqli->error);
	/*********************************************************************************************************************************************************************/	

	/*********************************************************************************************************************************************************************/
	//INGRESAR REGISTROS EN LA ENTIDAD HISTORIAL
	$historial_numero = historial();
	$estado_historial = "Agregar";
	$observacion_historial = "Se ha agregado un nueva muestra con un diagnostico: $diagnostico_clinico";
	$modulo = "Muestras";
	$insert = "INSERT INTO historial 
		VALUES('$historial_numero','0','0','$modulo','$muestras_id','$usuario','0','$fecha','$estado_historial','$observacion_historial','$usuario','$fecha_registro')";	 
	$mysqli->query($insert) or die($mysqli->error);
	/*********************************************************************************************************************************************************************/			

	$datos = array(
		0 => "Almacenado", 
		1 => "Registro Almacenado Correctamente", 
		2 => "success",
		3 => "btn-primary",
		4 => "formulario_admision",
		5 => "Registro",
		6 => "formPacientesAdmision",
		7 => "modal_admision_clientes",
		8 => "",
		9 => "Guardar",
		10 => $muestras_id
	);
}else{
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