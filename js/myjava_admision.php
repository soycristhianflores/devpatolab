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
	getClientesAdmision();
	getTipoPacienteSelect();

	$('#form_main_admision #tipo').val(1);
	$('#form_main_admision #tipo').selectpicker('refresh');

	$('#form_main_admision #estado').val(1);
	$('#form_main_admision #estado').selectpicker('refresh');

	$('#form_main_admision #bs_regis').on('keyup',function(){
		pagination(1);
	});

	$('#form_main_admision #estado').on('change',function(){
		pagination(1);
	});

	$('#form_main_admision #tipo').on('change',function(){
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
    $("#modal_admision_clientes_editar").on('shown.bs.modal', function(){
        $(this).find('#formulario_admision_clientes_editar #name').focus();
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

			$('#formulario_admision_clientes_editar #genero').html("");
			$('#formulario_admision_clientes_editar #genero').html(data);
			$('#formulario_admision_clientes_editar #genero').selectpicker('refresh');			
		}
   });
   return false;
}

function getClientesAdmision(){
  var url = '<?php echo SERVERURL; ?>php/admision/getClientes.php';
  $.ajax({
 	 type:'POST',
	 url:url,
		success: function(data){
			$('#formulario_admision #cliente_admision').html("");
			$('#formulario_admision #cliente_admision').html(data);
			$('#formulario_admision #cliente_admision').selectpicker('refresh');
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

function getTipoPacienteSelect(){
  var url = '<?php echo SERVERURL; ?>php/admision/getTipoPaciente.php';
  $.ajax({
 	 type:'POST',
	 url:url,
		success: function(data){
			$('#form_main_admision #tipo').html("");
			$('#form_main_admision #tipo').html(data);
			$('#form_main_admision #tipo').selectpicker('refresh');
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


$('#formulario_admision #cliente_admision').on('change', function(){
	var url = '<?php echo SERVERURL; ?>php/admision/consultarClientes.php';

	$.ajax({
	    type:'POST',
		url:url,
		data:'pacientes_id='+$('#formulario_admision #cliente_admision').val(),
		async: false,
		success:function(data){
			var valores = eval(data);
			$('#formulario_admision #name').val(valores[0]);
			$('#formulario_admision #lastname').val(valores[1]);
			$('#formulario_admision #rtn').val(valores[2]);
			$('#formulario_admision #edad').val(valores[3]);
			$('#formulario_admision #telefono1').val(valores[4]);
			$('#formulario_admision #genero').val(valores[5]);
			$('#formulario_admision #genero').selectpicker('refresh');
			$('#formulario_admision #direccion').val(valores[6]);
			$('#formulario_admision #correo').val(valores[7]);								
		}
	});
});

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
    var tipo = $('#form_main_admision #tipo').val();
	var dato = $('#form_main_admision #bs_regis').val();
	var estado = $('#form_main_admision #estado').val();

	$.ajax({
		type:'POST',
		url:url,
		async: true,
		data:'partida='+partida+'&tipo='+tipo+'&dato='+dato+'&estado='+estado,
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

function consultarExpediente(pacientes_id){	
    var url = '<?php echo SERVERURL; ?>php/pacientes/getExpedienteInformacion.php';
	var resp;
		
	$.ajax({
	    type:'POST',
		url:url,
		data:'pacientes_id='+pacientes_id,
		async: false,
		success:function(data){	
          resp = data;			  		  		  			  
		}
	});
	return resp;		
}

function consultarNombre(pacientes_id){	
    var url = '<?php echo SERVERURL; ?>php/pacientes/getNombre.php';
	var resp;
		
	$.ajax({
	    type:'POST',
		url:url,
		data:'pacientes_id='+pacientes_id,
		async: false,
		success:function(data){	
          resp = data;			  		  		  			  
		}
	});
	return resp;	
}

function eliminarRegistro(pacientes_id){	
	var url = '<?php echo SERVERURL; ?>php/pacientes/eliminar.php';
	$.ajax({
		type:'POST',
		url:url,
		data:'id='+pacientes_id,
		success: function(registro){
			if(registro == 1){
				swal({
					title: "Success", 
					text: "Registro eliminado correctamente",
					type: "success",
					timer: 3000, //timeOut for auto-clos
				});	
				pagination(1);
			   return false;				
			}else if(registro == 2){	
				swal({
					title: "Error", 
					text: "No se puede eliminar este registro",
					type: "error", 
					confirmButtonClass: 'btn-danger'
				});		
	           return false;				
			}else if(registro == 3){	
				swal({
					title: "Error", 
					text: "No se puede eliminar este registro, cuenta con información almacenada",
					type: "error", 
					confirmButtonClass: 'btn-danger'
				});		
	           return false;				
			}else{
				swal({
					title: "Error", 
					text: "Error al completar el registro",
					type: "error", 
					confirmButtonClass: 'btn-danger'
				});				   
	           return false;				
			}
  		}
	}); 
	return false;
}

function modal_eliminar(pacientes_id){
  if (consultarExpediente(pacientes_id) != 0 && (getUsuarioSistema() == 1 || getUsuarioSistema() == 2 || getUsuarioSistema() == 3)){
    var nombre_usuario = consultarNombre(pacientes_id);
    var expediente_usuario = consultarExpediente(pacientes_id);
    var dato;

    if(expediente_usuario == 0){
		dato = nombre_usuario;
	}else{
		dato = nombre_usuario + " (Expediente: " + expediente_usuario + ")";
	}
	
	swal({
	  title: "¿Estas seguro?",
	  text: "¿Desea eliminar este registro: " + dato + "?",
	  type: "warning",
	  showCancelButton: true,
	  confirmButtonClass: "btn-warning",
	  confirmButtonText: "¡Sí, eliminar el registro!",
	  cancelButtonText: "Cancelar",
	  closeOnConfirm: false
	},
	function(){
		eliminarRegistro(pacientes_id);
	});
  }else if (consultarExpediente(pacientes_id) == 0 && (getUsuarioSistema() == 1 || getUsuarioSistema() == 2 || getUsuarioSistema() == 3)){
    var nombre_usuario = consultarNombre(pacientes_id);
    var expediente_usuario = consultarExpediente(pacientes_id);
    var dato;

    if(expediente_usuario == 0){
		dato = nombre_usuario;
	}else{
		dato = nombre_usuario + " (Expediente: " + expediente_usuario + ")";
	}
	
	swal({
	  title: "¿Estas seguro?",
	  text: "¿Desea eliminar este registro: " + dato + "?",
	  type: "warning",
	  showCancelButton: true,
	  confirmButtonClass: "btn-warning",
	  confirmButtonText: "¡Sí, eliminar el registro!",
	  cancelButtonText: "Cancelar",	  
	  closeOnConfirm: false
	},
	function(){
		eliminarRegistro(pacientes_id);
	});
  }else{
	  swal({
			title: 'Acceso Denegado', 
			text: 'No tiene permisos para ejecutar esta acción',
			type: 'error', 
			confirmButtonClass: 'btn-danger'
	  });				
	 return false;	  
  }
}

function editarRegistro(pacientes_id){
	if (getUsuarioSistema() == 1 || getUsuarioSistema() == 2 || getUsuarioSistema() == 3){
		if($('#form_main_admision #tipo').val() == 1 || $('#form_main_admision #tipo').val() == ""){
			var url = '<?php echo SERVERURL; ?>php/admision/consultarClientes.php';

			$.ajax({
				type:'POST',
				url:url,
				data:'pacientes_id='+pacientes_id,
				success: function(valores){
					var datos = eval(valores);
					$('#formulario_admision_clientes_editar #edi_admision').show();	
					$('#formulario_admision_clientes_editar #pacientes_id').val(pacientes_id);					
					$('#formulario_admision_clientes_editar #name').val(datos[0]);
					$('#formulario_admision_clientes_editar #lastname').val(datos[1]);
					$('#formulario_admision_clientes_editar #rtn').val(datos[2]);
					$('#formulario_admision_clientes_editar #edad').val(datos[3]);
					$('#formulario_admision_clientes_editar #telefono1').val(datos[4]);
					$('#formulario_admision_clientes_editar #genero').val(datos[5]);
					$('#formulario_admision_clientes_editar #genero').selectpicker('refresh');
					$('#formulario_admision_clientes_editar #direccion').val(datos[6]);
					$('#formulario_admision_clientes_editar #correo').val(datos[7]);						


					$('#formulario_admision_clientes_editar').attr({ 'data-form': 'update' }); 
					$('#formulario_admision_clientes_editar').attr({ 'action': '<?php echo SERVERURL; ?>php/admision/modificarRegistro.php' });						
					
					//HABILITAR OBJETOS
					$('#formulario_admision_clientes_editar #name').attr('readonly', false);
					$('#formulario_admision_clientes_editar #lastname').attr('readonly', false);
					$('#formulario_admision_clientes_editar #rtn').attr('readonly', false);
					$('#formulario_admision_clientes_editar #fecha_nac').attr('disabled', false);
					$('#formulario_admision_clientes_editar #edad').attr('readonly', false);
					$('#formulario_admision_clientes_editar #telefono1').attr('readonly', false);
					$('#formulario_admision_clientes_editar #genero').attr('disabled', false);
					$('#formulario_admision_clientes_editar #direccion').attr('readonly', false);
					$('#formulario_admision_clientes_editar #correo').attr('readonly', false);

					$('#modal_admision_clientes_editar').modal({
						show:true,
						keyboard: false,
						backdrop:'static'
					});
					return false;
				}
			});	
		}else{

		}
	}else{
		swal({
			title: 'Acceso Denegado', 
			text: 'No tiene permisos para ejecutar esta acción',
			type: 'error', 
			confirmButtonClass: 'btn-danger'
		});				
		return false;			
	}
}

function modalEditar(pacientes_id){
	$('#formulario_admision_clientes_editar').attr({ 'data-form': 'update' });
	$('#formulario_admision_clientes_editar').attr({ 'action': '<?php echo SERVERURL; ?>php/admision/agregarRegistro.php' });
	$('#formulario_admision_clientes_editar')[0].reset();
	$('#formulario_admision_clientes_editar #pro_admision').val("Registro");
	$('#reg_admision').show();
	$('#edi_admision').hide();
	$('#delete_admision').hide();

	$('#formulario_admision_clientes_editar #paciente_tipo').val(1);
	$('#formulario_admision_clientes_editar #paciente_tipo').selectpicker('refresh');
	
	$('#formulario_admision #hospital').val(56);
	$('#formulario_admision #hospital').selectpicker('refresh');	


}

function modalClientes(){
	$('#formulario_admision').attr({ 'data-form': 'save' });
	$('#formulario_admision').attr({ 'action': '<?php echo SERVERURL; ?>php/admision/agregarRegistro.php' });
	$('#formulario_admision')[0].reset();
	$('#formulario_admision #pro_admision').val("Registro");
	$('#reg_admision').show();

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

$('#formulario_facturacion #validar').on('click', function(e){
	$('#formulario_facturacion').attr({ 'data-form': 'save' });
	$('#formulario_facturacion').attr({ 'action': '<?php echo SERVERURL; ?>php/facturacion/addPreFactura.php' });
	$("#formulario_facturacion").submit();
});

$('#formulario_facturacion #cobrar').on('click', function(e){
	$('#formulario_facturacion').attr({ 'data-form': 'save' });
	$('#formulario_facturacion').attr({ 'action': '<?php echo SERVERURL; ?>php/facturacion/addFactura.php' });
	$("#formulario_facturacion").submit();
});

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