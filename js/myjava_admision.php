<script>
$(document).ready(function(){
	getEstado(); 
	getGenero();
	getTipo();
    getTipoMuestra();
    getEmpresa();
    getRemitente();
    getHospitales();
    getCategorias();
	getServicio();

	$('#form_main #bs_regis').on('keyup',function(){
		pagination(1);
	});

	$('#formulario_admision #fecha_nac').on('change',function(){
		CalcularEdadClientes();
	});   
});

/*INICIO DE FUNCIONES PARA ESTABLECER EL FOCUS PARA LAS VENTANAS MODALES*/
$(document).ready(function(){
    $("#modal_admision_clientes").on('shown.bs.modal', function(){
        $(this).find('#formulario_admision #name').focus();
    });
});

$(document).ready(function(){
    $("#modal_admision_empesas").on('shown.bs.modal', function(){
        $(this).find('#formulario_admision_empresas #empresa').focus();
    });
});
/*FIN DE FUNCIONES PARA ESTABLECER EL FOCUS PARA LAS VENTANAS MODALES*/
 
function getEstado(){
  var url = '<?php echo SERVERURL; ?>php/admision/getStatus.php';
  $.ajax({
 	 type:'POST',
	 url:url,
		success: function(data){
			$('#form_main_admision #estado').html("");
			$('#form_main_admision #estado').html(data);
			$('#form_main_admision #estado').selectpicker('refresh');
		}
   });
   return false;
}

function getTipoMuestra(){
  var url = '<?php echo SERVERURL; ?>php/admision/getTipoMuestra.php';
  $.ajax({
 	 type:'POST',
	 url:url,
		success: function(data){
			$('#formulario_admision #tipo_muestra').html("");
			$('#formulario_admision #tipo_muestra').html(data);
			$('#formulario_admision #tipo_muestra').selectpicker('refresh');
		}
   });
   return false;
}



function getGenero(){
  var url = '<?php echo SERVERURL; ?>php/admision/getSexo.php';
  $.ajax({
 	 type:'POST',
	 url:url,
		success: function(data){
			$('#formulario_admision #genero').html("");
			$('#formulario_admision #genero').html(data);
			$('#formulario_admision #genero').selectpicker('refresh');
		}
   });
   return false;
}

function getEmpresa(){
  var url = '<?php echo SERVERURL; ?>php/admision/getEmpresa.php';
  $.ajax({
 	 type:'POST',
	 url:url,
		success: function(data){
			$('#formulario_admision #empresa').html("");
			$('#formulario_admision #empresa').html(data);
			$('#formulario_admision #empresa').selectpicker('refresh');
		}
   });
   return false;
}

function getTipo(){
  var url = '<?php echo SERVERURL; ?>php/admision/getTipoPaciente.php';
  $.ajax({
 	 type:'POST',
	 url:url,
		success: function(data){
			$('#formulario_admision #paciente_tipo').html("");
			$('#formulario_admision #paciente_tipo').html(data);
			$('#formulario_admision #paciente_tipo').selectpicker('refresh');
		}
   });
   return false;
}

function CalcularEdadClientes(){
  var url = '<?php echo SERVERURL; ?>php/admision/calcularEdad.php';
  $.ajax({
 	 type:'POST',
     data:'fecha_nac='+$('#formulario_admision #fecha_nac').val(),
	 url:url,
		success: function(data){
			$('#formulario_admision #edad').val(data);
		}
   });
   return false;
}

function getRemitente(){
  var url = '<?php echo SERVERURL; ?>php/admision/getRemitente.php';
  $.ajax({
 	 type:'POST',
	 url:url,
		success: function(data){
			$('#formulario_admision #remitente').html("");
			$('#formulario_admision #remitente').html(data);
			$('#formulario_admision #remitente').selectpicker('refresh');
		}
   });
   return false;
}

function getHospitales(){
  var url = '<?php echo SERVERURL; ?>php/admision/getHospitales.php';
  $.ajax({
 	 type:'POST',
	 url:url,
		success: function(data){
			$('#formulario_admision #hospital').html("");
			$('#formulario_admision #hospital').html(data);
			$('#formulario_admision #hospital').selectpicker('refresh');

			$('#formulario_admision_empresas #hospital_empresa').html("");
			$('#formulario_admision_empresas #hospital_empresa').html(data);
			$('#formulario_admision_empresas #hospital_empresa').selectpicker('refresh');			
		}
   });
   return false;
}

function getCategorias(){
  var url = '<?php echo SERVERURL; ?>php/admision/getCategoriaMuestra.php';
  $.ajax({
 	 type:'POST',
	 url:url,
		success: function(data){
			$('#formulario_admision #categoria').html("");
			$('#formulario_admision #categoria').html(data);
			$('#formulario_admision #categoria').selectpicker('refresh');
		}
   });
   return false;
}

function getFechaActual(){
	var url = '<?php echo SERVERURL; ?>php/admision/getFechaActual.php';
	var fecha_actual;

	$.ajax({
	    type:'POST',
		url:url,
		async: false,
		success:function(data){
          fecha_actual = data;
		}
	});
	return fecha_actual;	
}

function getTipoPaciente(pacientes_id){
	var url = '<?php echo SERVERURL; ?>php/admision/getTipoPaciente.php';
	var tipo_paciente;

	$.ajax({
	    type:'POST',
		url:url,
		data:'pacientes_id='+pacientes_id,
		async: false,
		success:function(data){
          tipo_paciente = data;
		}
	});
	return tipo_paciente;
}

$('#formulario_admision #tipo_muestra').on('change', function(){
	var url = '<?php echo SERVERURL; ?>php/admision/getProductos.php';

	$.ajax({
	    type:'POST',
		url:url,
		data:'tipo_muestra_id='+$('#formulario_admision #tipo_muestra').val(),
		async: false,
		success:function(data){
			$('#formulario_admision #producto').html(data);
			$('#formulario_admision #producto').selectpicker('refresh');
		}
	});
});

function pagination(partida){
	var url = '<?php echo SERVERURL; ?>php/admision/paginar.php';
    var fechai = $('#form_main_admision #fecha_i').val();
	var fechaf = $('#form_main_admision #fecha_f').val();
	var estado = '';
	var pacientesIDGrupo = '';	
	var tipo_muestra = '';
	var dato = '';

	$.ajax({
		type:'POST',
		url:url,
		async: true,
		data:'partida='+partida+'&fechai='+fechai+'&fechaf='+fechaf,
		success:function(data){
			var array = eval(data);
			$('#agrega-registros').html(array[0]);
			$('#pagination').html(array[1]);
		}
	});
	return false;
}

$('#form_main_admision #registrar_cliente').on('click', function(e){
	e.preventDefault();
	modalClientes();
});

function modalClientes(){
	$('#formulario_admision').attr({ 'data-form': 'save' });
	$('#formulario_admision').attr({ 'action': '<?php echo SERVERURL; ?>php/admision/agregarRegistro.php' });
	$('#formulario_admision')[0].reset();
	$('#formulario_admision #pro_admision').val("Registro");
	$('#reg_admision').show();
	$('#edi_admision').hide();
	$('#delete_admision').hide();

	$('#formulario_admision #paciente_tipo').val(1);
	$('#formulario_admision #paciente_tipo').selectpicker('refresh');
	
	$('#formulario_admision #hospital').val(56);
	$('#formulario_admision #hospital').selectpicker('refresh');	

	//HABILITAR OBJETOS
	$('#formulario_admision #name').attr('readonly', false);
	$('#formulario_admision #lastname').attr('readonly', false);
	$('#formulario_admision #rtn').attr('readonly', false);
	$('#formulario_admision #fecha_nac').attr('disabled', false);
   $('#formulario_admision #edad').attr('readonly', false);
	$('#formulario_admision #telefono1').attr('readonly', false);
	$('#formulario_admision #genero').attr('disabled', false);
   $('#formulario_admision #direccion').attr('readonly', false);
	$('#formulario_admision #correo').attr('readonly', false);
   $('#formulario_admision #hospital').attr('disabled', false);
	$('#formulario_admision #empresa').attr('disabled', false);
   $('#formulario_admision #referencia').attr('readonly', false);
	$('#formulario_admision #tipo_muestra').attr('disabled', false);
   $('#formulario_admision #remitente').attr('readonly', false);
	$('#formulario_admision #categoria').attr('disabled', false);
   $('#formulario_admision #sitio_muestra').attr('readonly', false);
	$('#formulario_admision #diagnostico_clinico').attr('readonly', false);
	$('#formulario_admision #material_enviado').attr('readonly', false);
   $('#formulario_admision #datos_clinicos').attr('readonly', false);
   $('#formulario_admision #mostrar_datos_clinicos').attr('disabled', false);

	$('#modal_admision_clientes').modal({
		show:true,
		keyboard: false,
		backdrop:'static'
	});
}

$('#form_main_admision #registrar_empresa').on('click', function(e){
	e.preventDefault();
	modaEmpresa();
});

$('#formulario_admision #add_empresa').on('click', function(e){
	e.preventDefault();
	modaEmpresa();
});

function modaEmpresa(){
	$('#formulario_admision_empresas').attr({ 'data-form': 'save' });
	$('#formulario_admision_empresas').attr({ 'action': '<?php echo SERVERURL; ?>php/admision/agregarRegistroEmpresas.php' });
	$('#formulario_admision_empresas')[0].reset();
	$('#formulario_admision_empresas #pro_admision').val("Registro");
	$('#reg_admisionemp').show();
	$('#edi_admisionemp').hide();
	$('#delete_admisionemp').hide();

	$('#formulario_admision_empresas #paciente_tipo').val(1);
	$('#formulario_admision_empresas #paciente_tipo').selectpicker('refresh');	

	//HABILITAR OBJETOS
	$('#formulario_admision_empresas #name').attr('readonly', false);
	$('#formulario_admision_empresas #lastname').attr('readonly', false);
	$('#formulario_admision_empresas #rtn').attr('readonly', false);
	$('#formulario_admision_empresas #fecha_nac').attr('disabled', false);
   $('#formulario_admision_empresas #edad').attr('readonly', false);
	$('#formulario_admision_empresas #telefono1').attr('readonly', false);
	$('#formulario_admision_empresas #genero').attr('disabled', false);
   $('#formulario_admision_empresas #direccion').attr('readonly', false);
	$('#formulario_admision_empresas #correo').attr('readonly', false);

	$('#modal_admision_empesas').modal({
		show:true,
		keyboard: false,
		backdrop:'static'
	});
}

function convertDate(inputFormat) {
   function pad(s) { return (s < 10) ? '0' + s : s; }
   var d = new Date(inputFormat);
   return [d.getFullYear(), pad(d.getMonth()+1), pad(d.getDate())].join('-');
}

//FACTURA
function formFactura(){
	 $('#formulario_facturacion')[0].reset();
	 $('#main_facturacion').hide();
	 $('#main_facturacion').show();
	 $('#label_acciones_volver').html("Volver");
	 $('#acciones_atras').removeClass("active");
	 $('#acciones_factura').addClass("active");
	 $('#label_acciones_factura').html("Factura");
	 $('#formulario_facturacion #fecha').attr('disabled', false);
	 $('#formulario_facturacion').attr({ 'data-form': 'save' });
	 $('#formulario_facturacion').attr({ 'action': '<?php echo SERVERURL; ?>php/facturacion/addPreFactura.php' });
	 limpiarTabla();
	 $('.footer').show();
     $('.footer1').hide();	 
}

function volver(){
	$('#main_facturacion').show();
	$('#label_acciones_factura').html("");
	$('#main_facturacion').hide();
	$('#acciones_atras').addClass("breadcrumb-item active");
	$('#acciones_factura').removeClass("active");
	$('.footer').show();
    $('.footer1').hide();	
}

function getFacturaEmision(muestras_id){
	var url = '<?php echo SERVERURL; ?>php/muestras/getFacturaEmision.php';
	var disponible;

	$.ajax({
	    type:'POST',
		url:url,
		data:'muestras_id='+muestras_id,
		async: false,
		success:function(data){
          disponible = data;
		}
	});
	return disponible;
}

function createBill(muestras_id){
	if (getUsuarioSistema() == 1 || getUsuarioSistema() == 2 || getUsuarioSistema() == 3){
		if(getFacturaEmision(muestras_id) == ""){
			$('#formulario_facturacion')[0].reset();
			$("#formulario_facturacion #invoiceItem > tbody").empty();//limpia solo los registros del body
			var url = '<?php echo SERVERURL; ?>php/muestras/editarFacturasMuestras.php';

			var url = '<?php echo SERVERURL; ?>php/muestras/editarFacturasMuestras.php';
				$.ajax({
				type:'POST',
				url:url,
				data:'muestras_id='+muestras_id,
				success: function(valores){
					var datos = eval(valores);
					$('#formulario_facturacion #muestras_id').val(muestras_id);
					$('#formulario_facturacion #pacientes_id').val(datos[0]);
					$('#formulario_facturacion #cliente_nombre').val(datos[1]);
					$('#formulario_facturacion #fecha').val(getFechaActual());
					$('#formulario_facturacion #colaborador_id').val(datos[3]);
					$('#formulario_facturacion #colaborador_nombre').val(datos[4]);
					$('#formulario_facturacion #servicio_id').val(datos[5]);
					$('#formulario_facturacion #material_enviado_muestra').val(datos[6]);
					$('#formulario_facturacion #paciente_muestra_codigo').val(datos[7]);
					$('#formulario_facturacion #paciente_muestra').val(datos[8]);
					$('#formulario_facturacion #muestras_numero').val(datos[9]);					

					$('#formulario_facturacion #fecha').attr("readonly", true);
					$('#formulario_facturacion #validar').attr("disabled", false);
					$('#formulario_facturacion #addRows').attr("disabled", false);
					$('#formulario_facturacion #removeRows').attr("disabled", false);
					$('#formulario_facturacion #validar').show();
					$('#formulario_facturacion #editar').hide();
					$('#formulario_facturacion #eliminar').hide();

					if(getTipoPaciente(datos[0]) == 2){
						$('#formulario_facturacion #grupo_paciente_factura').show();
					}else{
						$('#formulario_facturacion #grupo_paciente_factura').hide();
					}

					$('#main_facturacion').hide();
					$('#facturacion').show();
					$('#label_acciones_volver').html("Volver");
					$('#acciones_atras').removeClass("active");
					$('#acciones_factura').addClass("active");
					$('#label_acciones_factura').html("Factura");
					$('#formulario_facturacion #fecha').attr('disabled', false);

					limpiarTabla();

					$('#main_facturacion').hide();
					$('#label_acciones_factura').html("Factura");
					$('#facturacion').show();
					
					$('.footer').hide();
    				$('.footer1').show();					

					return false;
				}
			});
		}else{
			swal({
				title: "Error",
				text: "Lo sentimos esta factura ya ha sido generada, por favor diríjase al módulo de facturación y realice le cobro de esta",
				type: "error",
				confirmButtonClass: 'btn-danger'
			});
		}
	}else{
		swal({
			title: "Acceso Denegado",
			text: "No tiene permisos para ejecutar esta acción",
			type: "error",
			confirmButtonClass: 'btn-danger'
		});
	}
}
</script>