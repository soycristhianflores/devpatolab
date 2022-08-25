<script>
/****************************************************************************************************************************************************************/
//INICIO CONTROLES DE ACCION
$(document).ready(function() {
	$('.footer').show();
    $('.footer1').hide();
	getTotalFacturasDisponibles();

	//LLAMADA A LAS FUNCIONES
	funciones();

    //INICIO PAGINATION (PARA LAS BUSQUEDAS SEGUN SELECCIONES)
	$('#form_main_facturas #bs_regis').on('keyup',function(){
	  pagination(1);
	});

	$('#form_main_facturas #fecha_b').on('change',function(){
	  pagination(1);
	});

	$('#form_main_facturas #fecha_f').on('change',function(){
	  pagination(1);
	});

	$('#form_main_facturas #tipo_paciente_grupo').on('change',function(){
	  pagination(1);
	});

	$('#form_main_facturas #pacientesIDGrupo').on('change',function(){
	  pagination(1);
	});

	$('#form_main_facturas #estado').on('change',function(){
	  pagination(1);
	});
	//FIN PAGINATION (PARA LAS BUSQUEDAS SEGUN SELECCIONES)
});
//FIN CONTROLES DE ACCION
/****************************************************************************************************************************************************************/

/***************************************************************************************************************************************************************************/
//INICIO FUNCIONES

//INICIO OBTENER COLABORADOR CONSULTA
function getColaboradorConsulta(){
    var url = '<?php echo SERVERURL; ?>php/facturacion/getMedicoConsulta.php';
	var colaborador_id;
	$.ajax({
	    type:'POST',
		url:url,
		async: false,
		success:function(data){
		  var datos = eval(data);
          colaborador_id = datos[0];
		}
	});
	return colaborador_id;
}
//FIN OBTENER COLABORADOR CONSULTA

//INICIO MODAL PAGOS GRUPAL
function pagoGrupal(facturas_grupal_id){
	var url = '<?php echo SERVERURL; ?>php/facturacion/editarPagoGrupal.php';
	
	$.ajax({
		type:'POST',
		url:url,
		data:'facturas_grupal_id='+facturas_grupal_id,
		success: function(valores){
			var datos = eval(valores);
			$('#formEfectivoBillGrupal .border-right a:eq(0) a').tab('show');			
			$("#customer-name-bill-grupal").html("<b>Cliente:</b> " + datos[0]);
		    $("#customer_bill_pay_grupal-grupal").val(datos[2]);
			$('#bill-pay-grupal').html("L. " + parseFloat(datos[2]).toFixed(2));
			
			//EFECTIVO
			$('#formEfectivoBillGrupal')[0].reset();			
			$('#formEfectivoBillGrupal #monto_efectivo').val(datos[2]);
			$('#formEfectivoBillGrupal #factura_id_efectivo').val(facturas_grupal_id);
			$('#formEfectivoBillGrupal #pago_efectivo_grupal').attr('disabled', true);
			
			//TARJETA
			$('#formTarjetaBillGrupal')[0].reset();
			$('#formTarjetaBillGrupal #monto_efectivo').val(datos[2]);
			$('#formTarjetaBillGrupal #factura_id_tarjeta').val(facturas_grupal_id);

			//MIXTO
			$('#formMixtoBillGrupal')[0].reset();
			$('#formMixtoBillGrupal #monto_efectivo_mixto').val(datos[2]);
			$('#formMixtoBillGrupal #factura_id_mixto').val(facturas_grupal_id);
			$('#formMixtoBillGrupal #pago_efectivo_mixto_grupal').attr('disabled', true);			

			//TRANSFERENCIA
			$('#formTransferenciaBillGrupal')[0].reset();
			$('#formTransferenciaBillGrupal #monto_efectivo').val(datos[2]);
			$('#formTransferenciaBillGrupal #factura_id_transferencia').val(facturas_grupal_id);	
			
			//CHEQUES
			$('#formChequeBillGrupal')[0].reset();
			$('#formChequeBillGrupal #monto_efectivo').val(datos[2]);
			$('#formChequeBillGrupal #factura_id_cheque').val(facturas_grupal_id);			
			
			$('#modal_grupo_pagos').modal({
				show:true,
				keyboard: false,
				backdrop:'static'
			});

			return false;
		}
	});	
}

$(document).ready(function(){
	$("#tabGrupal1").on("click", function(){	
		$("#modal_grupo_pagos").on('shown.bs.modal', function(){
           $(this).find('#formEfectivoBillGrupal #efectivo_bill').focus();
		});			
	});
	
	$("#tabGrupal2").on("click", function(){	
		$("#modal_grupo_pagos").on('shown.bs.modal', function(){
           $(this).find('#formTarjetaBillGrupal #cr_bill').focus();
		});	
	});	
	
	$("#tabGrupal3").on("click", function(){	
		$("#modal_grupo_pagos").on('shown.bs.modal', function(){
           $(this).find('#formTarjetaBillGrupal #bk_nm').focus();
		});	
	});	
	
	$("#tabGrupal4").on("click", function(){	
		$("#modal_grupo_pagos").on('shown.bs.modal', function(){
           $(this).find('#formChequeBillGrupal #check_num').focus();
		});	
	});	
	
	$("#tabGrupal5").on("click", function(){	
		$("#modal_pagos").on('shown.bs.modal', function(){
           $(this).find('#formMixtoBillGrupal #efectivo_bill_mixto').focus();
		});	
	});		
});

$(document).ready(function(){
	$('#formTarjetaBillGrupal #cr_bill').inputmask("9999");
});

$(document).ready(function(){
	$('#formTarjetaBillGrupal #exp').inputmask("99/99");
});

$(document).ready(function(){
	$('#formTarjetaBillGrupal #cvcpwd').inputmask("999999");
});

// MIXTO
$(document).ready(function(){
	$('#formMixtoBill #cr_bill_mixto').inputmask("9999");
});

$(document).ready(function(){
	$('#formMixtoBill #exp_mixto').inputmask("99/99");
});

$(document).ready(function(){
	$('#formMixtoBill #cvcpwd_mixto').inputmask("999999");
});

$(document).ready(function(){
	$("#formEfectivoBillGrupal #efectivo_bill").on("keyup", function(){	
		var efectivo = parseFloat($("#formEfectivoBillGrupal #efectivo_bill").val()).toFixed(2);
		var monto = parseFloat($("#formEfectivoBillGrupal #monto_efectivo").val()).toFixed(2);
		
		var total = efectivo - monto;				
		
		if(Math.floor(efectivo*100) >= Math.floor(monto*100)){			
			$('#formEfectivoBillGrupal #cambio_efectivo').val(parseFloat(total).toFixed(2));
			$('#formEfectivoBillGrupal #pago_efectivo_grupal').attr('disabled', false);				
		}else{
			$('#formEfectivoBillGrupal #cambio_efectivo').val(parseFloat(0).toFixed(2));
			$('#formEfectivoBillGrupal #pago_efectivo_grupal').attr('disabled', true);
		}				
	});

	//MIXTO
	$("#formMixtoBillGrupal #efectivo_bill_mixto").on("keyup", function(){	
		var efectivo = parseFloat($("#formMixtoBillGrupal #efectivo_bill_mixto").val()).toFixed(2);
		var monto = parseFloat($("#formMixtoBillGrupal #monto_efectivo_mixto").val()).toFixed(2);
		
		var total = efectivo - monto;				
		
		if(Math.floor(efectivo*100) >= Math.floor(monto*100)){			
			$('#formMixtoBillGrupal #pago_efectivo_mixto_grupal').attr('disabled', true);	
			$('#formMixtoBillGrupal #monto_tarjeta').val(parseFloat(0).toFixed(2));
			$('#formMixtoBillGrupal #monto_tarjeta').attr('disabled', true);			
		}else{
			var tarjeta = monto - efectivo;
			$('#formMixtoBillGrupal #monto_tarjeta').val(parseFloat(tarjeta).toFixed(2))
			$('#formMixtoBillGrupal #cambio_efectivo_mixto').val(parseFloat(0).toFixed(2));
			$('#formMixtoBillGrupal #pago_efectivo_mixto_grupal').attr('disabled', false);
		}				
	});		
});
//FIN PAGO GRUPAL

//INICIO MODAL PAGOS
function pago(facturas_id){
	var url = '<?php echo SERVERURL; ?>php/facturacion/editarPago.php';
	
	$.ajax({
		type:'POST',
		url:url,
		data:'facturas_id='+facturas_id,
		success: function(valores){
			var datos = eval(valores);
			$('#formEfectivoBill .border-right a:eq(0) a').tab('show');			
			$("#customer-name-bill").html("<b>Cliente:</b> " + datos[0]);
		    $("#customer_bill_pay").val(datos[2]);
			$('#bill-pay').html("L. " + parseFloat(datos[2]).toFixed(2));
			
			//EFECTIVO
			$('#formEfectivoBill')[0].reset();			
			$('#formEfectivoBill #monto_efectivo').val(datos[2]);
			$('#formEfectivoBill #factura_id_efectivo').val(facturas_id);
			$('#formEfectivoBill #pago_efectivo').attr('disabled', true);
			
			//TARJETA
			$('#formTarjetaBill')[0].reset();
			$('#formTarjetaBill #monto_efectivo').val(datos[2]);
			$('#formTarjetaBill #factura_id_tarjeta').val(facturas_id);

			//TRANSFERENCIA
			$('#formTransferenciaBill')[0].reset();
			$('#formTransferenciaBill #monto_efectivo').val(datos[2]);
			$('#formTransferenciaBill #factura_id_transferencia').val(facturas_id);
			
			//MIXTO
			$('#formMixtoBill')[0].reset();
			$('#formMixtoBill #monto_efectivo_mixto').val(datos[2]);
			$('#formMixtoBill #factura_id_mixto').val(facturas_id);
			$('#formMixtoBill #pago_efectivo_mixto').attr('disabled', true);			
			
			//CHEQUES
			$('#formChequeBill')[0].reset();
			$('#formChequeBill #monto_efectivo').val(datos[2]);
			$('#formChequeBill #factura_id_cheque').val(facturas_id);	

			$('#modal_pagos').modal({
				show:true,
				keyboard: false,
				backdrop:'static'
			});

			return false;
		}
	});	
}

$(document).ready(function(){
	$("#tab1").on("click", function(){	
		$("#modal_pagos").on('shown.bs.modal', function(){
           $(this).find('#formTarjetaBill #efectivo_bill').focus();
		});			
	});
	
	$("#tab2").on("click", function(){	
		$("#modal_pagos").on('shown.bs.modal', function(){
           $(this).find('#formTarjetaBill #cr_bill').focus();
		});	
	});	
	
	$("#tab3").on("click", function(){	
		$("#modal_pagos").on('shown.bs.modal', function(){
           $(this).find('#formTarjetaBill #bk_nm').focus();
		});	
	});	
	
	$("#tab4").on("click", function(){	
		$("#modal_pagos").on('shown.bs.modal', function(){
           $(this).find('#formChequeBill #bk_nm_chk').focus();
		});	
	});	
	
	$("#tab5").on("click", function(){	
		$("#modal_pagos").on('shown.bs.modal', function(){
           $(this).find('#formMixtoBill #efectivo_bill_mixto').focus();
		});	
	});			
});

//mixto
$(document).ready(function(){
	$('#formMixtoPurchaseBill #cr_bill_mixtoPurchase').inputmask("9999");
});

$(document).ready(function(){
	$('#formMixtoPurchaseBill #exp_mixtoPurchase').inputmask("99/99");
});

$(document).ready(function(){
	$('#formMixtoPurchaseBill #cvcpwd_mixtoPurchase').inputmask("999999");
});

$(document).ready(function(){
	$('#formTarjetaBill #cr_bill').inputmask("9999");
});

$(document).ready(function(){
	$('#formTarjetaBill #exp').inputmask("99/99");
});

$(document).ready(function(){
	$('#formTarjetaBill #cvcpwd').inputmask("999999");
});

$(document).ready(function(){
	$("#formEfectivoBill #efectivo_bill").on("keyup", function(){	
		var efectivo = parseFloat($("#formEfectivoBill #efectivo_bill").val()).toFixed(2);
		var monto = parseFloat($("#formEfectivoBill #monto_efectivo").val()).toFixed(2);
		
		var total = efectivo - monto;				
		
		if(Math.floor(efectivo*100) >= Math.floor(monto*100)){			
			$('#formEfectivoBill #cambio_efectivo').val(parseFloat(total).toFixed(2));
			$('#formEfectivoBill #pago_efectivo').attr('disabled', false);				
		}else{
			$('#formEfectivoBill #cambio_efectivo').val(parseFloat(0).toFixed(2));
			$('#formEfectivoBill #pago_efectivo').attr('disabled', true);
		}				
	});

	//MIXTO
	$("#formMixtoBill #efectivo_bill_mixto").on("keyup", function(){	
		var efectivo = parseFloat($("#formMixtoBill #efectivo_bill_mixto").val()).toFixed(2);
		var monto = parseFloat($("#formMixtoBill #monto_efectivo_mixto").val()).toFixed(2);
		
		var total = efectivo - monto;				
		
		if(Math.floor(efectivo*100) >= Math.floor(monto*100)){			
			$('#formMixtoBill #pago_efectivo_mixto').attr('disabled', true);	
			$('#formMixtoBill #monto_tarjeta').val(parseFloat(0).toFixed(2));
			$('#formMixtoBill #monto_tarjeta').attr('disabled', true);			
		}else{
			var tarjeta = monto - efectivo;
			$('#formMixtoBill #monto_tarjeta').val(parseFloat(tarjeta).toFixed(2))
			$('#formMixtoBill #cambio_efectivo_mixto').val(parseFloat(0).toFixed(2));
			$('#formMixtoBill #pago_efectivo_mixto').attr('disabled', false);
		}				
	});		
});
//FIN MODAL PAGOS

//INICIO FUNCION PARA OBTENER LAS FUNCIONES
function funciones(){
    pagination(1);
	getColaborador();
	getTipoPacienteGrupo();
	getEstado();
	getPacientes();

	getServicio();
	getBanco();
	listar_pacientes_buscar();
	listar_colaboradores_buscar();
	listar_servicios_factura_buscar();
	listar_productos_facturas_buscar();
}
//FIN FUNCION PARA OBTENER LAS FUNCIONES

//INICIO PAGINACION DE REGISTROS
function pagination(partida){
	var url = '<?php echo SERVERURL; ?>php/facturacion/paginar.php';
    var fechai = $('#form_main_facturas #fecha_b').val();
	var fechaf = $('#form_main_facturas #fecha_f').val();
	var dato = '';
	var tipo_paciente_grupo = '';
	var pacientesIDGrupo = '';
	var estado = '';

    if($('#form_main_facturas #tipo_paciente_grupo').val() == "" || $('#form_main_facturas #tipo_paciente_grupo').val() == null){
		tipo_paciente_grupo = '';
	}else{
		tipo_paciente_grupo = $('#form_main_facturas #tipo_paciente_grupo').val();
	}

    if($('#form_main_facturas #pacientesIDGrupo').val() == "" || $('#form_main_facturas #pacientesIDGrupo').val() == null){
		pacientesIDGrupo = '';
	}else{
		pacientesIDGrupo = $('#form_main_facturas #pacientesIDGrupo').val();
	}

    if($('#form_main_facturas #estado').val() == "" || $('#form_main_facturas #estado').val() == null){
		estado = 1;
	}else{
		estado = $('#form_main_facturas #estado').val();
	}

	if($('#form_main_facturas #bs_regis').val() == "" || $('#form_main_facturas #bs_regis').val() == null){
		dato = '';
	}else{
		dato = $('#form_main_facturas #bs_regis').val();
	}

	$.ajax({
		type:'POST',
		url:url,
		async: true,
		data:'partida='+partida+'&fechai='+fechai+'&fechaf='+fechaf+'&dato='+dato+'&tipo_paciente_grupo='+tipo_paciente_grupo+'&pacientesIDGrupo='+pacientesIDGrupo+'&estado='+estado,
		success:function(data){
			var array = eval(data);
			$('#agrega-registros').html(array[0]);
			$('#pagination').html(array[1]);
		}
	});
	return false;
}
//FIN PAGINACION DE REGISTROS

//INICIO FUNCION PARA OBTENER LOS PACIENTES
function getPacientes(){
    var url = '<?php echo SERVERURL; ?>php/facturacion/getPacientes.php';

	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){
		    $('#formularioFactura #paciente').html("");
			$('#formularioFactura #paciente').html(data);
        }
     });
}
//FIN FUNCION PARA OBTENER LOS PACIENTES

function getColaboradorConsulta(){
	var url = '<?php echo SERVERURL; ?>php/facturacion/getMedicoConsulta.php';
	var colaborador_id = '';
	$.ajax({
		type:'POST',
		url:url,
		async: false,
		success: function(valores){
			var datos = eval(valores);
			colaborador_id = datos[0];
		}
	});
	return colaborador_id;
}
//FIN FUNCION PARA OBTENER LOS COLABORADORES

//INICIO FUNCION PARA OBTENER LOS BANCOS DISPONIBLES
function getEstado(){
    var url = '<?php echo SERVERURL; ?>php/facturacion/getEstado.php';

	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){
		    $('#form_main_facturas #estado').html("");
			$('#form_main_facturas #estado').html(data);
        }
     });
}
//FIN FUNCION PARA OBTENER LOS BANCOS DISPONIBLES

//INICIO FUNCION PARA OBTENER LOS BANCOS DISPONIBLES	
function getBanco(){
    var url = '<?php echo SERVERURL; ?>php/facturacion/getBanco.php';		
		
	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){
		    $('#formTransferenciaBill #bk_nm').html("");
			$('#formTransferenciaBill #bk_nm').html(data);	
			
		    $('#formChequeBill #bk_nm_chk').html("");
			$('#formChequeBill #bk_nm_chk').html(data);	
			
		    $('#formTransferenciaBillGrupal #bk_nm').html("");
			$('#formTransferenciaBillGrupal #bk_nm').html(data);	
			
		    $('#formChequeBillGrupal #bk_nm_chk').html("");
			$('#formChequeBillGrupal #bk_nm_chk').html(data);				
        }
     });		
}
//FIN FUNCION PARA OBTENER LOS BANCOS DISPONIBLES

//INICIO FUNCION PARA OBTENER LOS PROFESIONALES
function getColaborador(){
    var url = '<?php echo SERVERURL; ?>php/citas/getMedico.php';

	$.ajax({
        type: "POST",
        url: url,
        success: function(data){
		    $('#form_main_facturas #profesional').html("");
			$('#form_main_facturas #profesional').html(data);
		}
     });
}
//FIN FUNCION PARA OBTENER LOS PROFESIONALES

//INICIO ENVIAR FACTURA POR CORREO ELECTRONICO
function mailBill(facturas_id){
	swal({
	  title: "¿Estas seguro?",
	  text: "¿Desea enviar este numero de factura: # " + getNumeroFactura(facturas_id) + "?",
	  type: "info",
	  showCancelButton: true,
	  confirmButtonClass: "btn-primary",
	  confirmButtonText: "¡Sí, enviar la factura!",
	  cancelButtonText: "Cancelar",
	  closeOnConfirm: false
	},
	function(){
		sendMail(facturas_id);
	});
}

function mailBillGroup(facturas_id){
	swal({
	  title: "¿Estas seguro?",
	  text: "¿Desea enviar este numero de factura: # " + getNumeroFacturaGroup(facturas_id) + "?",
	  type: "info",
	  showCancelButton: true,
	  confirmButtonClass: "btn-primary",
	  confirmButtonText: "¡Sí, enviar la factura!",
	  cancelButtonText: "Cancelar",
	  closeOnConfirm: false
	},
	function(){
		sendMailGroup(facturas_id);
	});
}
//FIN ENVIAR FACTURA POR CORREO ELECTRONICO

//INICIO IMPRIMIR FACTURACION
function printBill(facturas_id){
	var url = '<?php echo SERVERURL; ?>php/facturacion/generaFactura.php?facturas_id='+facturas_id;
    window.open(url);
}

function printBillGroup(facturas_id){
	var url = '<?php echo SERVERURL; ?>php/facturacion/generaFacturaGrupal.php?facturas_id='+facturas_id;
    window.open(url);
}
//FIN IMPRIMIR FACTURACION

function sendMail(facturas_id){
	var url = '<?php echo SERVERURL; ?>php/facturacion/correo_facturas.php';
	var bill = '';

	$.ajax({
	   type:'POST',
	   url:url,
	   async: false,
	   data:'facturas_id='+facturas_id,
	   success:function(data){
	      bill = data;
	      if(bill == 1){
				swal({
					title: "Success",
					text: "La factura ha sido enviada por correo satisfactoriamente",
					type: "success",
				});
		  }
	  }
	});
	return bill;
}

function sendMailGroup(facturas_id){
	var url = '<?php echo SERVERURL; ?>php/facturacion/correo_facturasGrupal.php';
	var bill = '';

	$.ajax({
	   type:'POST',
	   url:url,
	   async: false,
	   data:'facturas_id='+facturas_id,
	   success:function(data){
	      bill = data;
	      if(bill == 1){
				swal({
					title: "Success",
					text: "La factura ha sido enviada por correo satisfactoriamente",
					type: "success",
				});
		  }
	  }
	});
	return bill;
}

function getNumeroFactura(facturas_id){
	var url = '<?php echo SERVERURL; ?>php/facturacion/getNoFactura.php';
	var noFactura = '';

	$.ajax({
	   type:'POST',
	   url:url,
	   async: false,
	   data:'facturas_id='+facturas_id,
	   success:function(data){
			var datos = eval(data);
			noFactura = datos[0];
	  }
	});
	return noFactura;
}

function getNumeroFacturaGroup(facturas_id){
	var url = '<?php echo SERVERURL; ?>php/facturacion/getNoFacturaGroup.php';
	var noFactura = '';

	$.ajax({
	   type:'POST',
	   url:url,
	   async: false,
	   data:'facturas_id='+facturas_id,
	   success:function(data){
			var datos = eval(data);
			noFactura = datos[0];
	  }
	});
	return noFactura;
}

function getNumeroNombrePaciente(facturas_id){
	var url = '<?php echo SERVERURL; ?>php/facturacion/getNombrePaciente.php';
	var noFactura = '';

	$.ajax({
	   type:'POST',
	   url:url,
	   async: false,
	   data:'facturas_id='+facturas_id,
	   success:function(data){
			var datos = eval(data);
			noFactura = datos[0];
	  }
	});
	return noFactura;
}
//FIN ENVIAR FACTURA POR CORREO ELECTRONICO
//FIN FUNCIONES

/*
###########################################################################################################################################################
###########################################################################################################################################################
###########################################################################################################################################################
*/
/*															INICIO FACTURACIÓN				   															 */
//INICIOS FORMULARIOS
$('#acciones_atras').on('click', function(e){
	 e.preventDefault();
	 if($('#formulario_facturacion #cliente_nombre').val() != "" || $('#formulario_facturacion #colaborador_nombre').val() != ""){
		swal({
		  title: "Tiene datos en la factura",
		  text: "¿Esta seguro que desea volver, recuerde que tiene información en la factura la perderá?",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonClass: "btn-warning",
		  confirmButtonText: "¡Si, deseo volver!",
		  closeOnConfirm: false
		},
		function(){
			$('#main_facturacion').show();
			$('#label_acciones_factura').html("");
			$('#facturacion').hide();
			$('#grupo_facturacion').hide();
			$('#acciones_atras').addClass("breadcrumb-item active");
			$('#acciones_factura').removeClass("active");
			$('#formulario_facturacion')[0].reset();
			swal.close();
			$('.footer').show();
    		$('.footer1').hide();			
		});
	 }else{
		 $('#main_facturacion').show();
		 $('#label_acciones_factura').html("");
		 $('#facturacion').hide();
		 $('#grupo_facturacion').hide();
		 $('#acciones_atras').addClass("breadcrumb-item active");
		 $('#acciones_factura').removeClass("active");
		 $('.footer').show();
    	 $('.footer1').hide();		 
	 }
});

$('#form_main_facturas #factura').on('click', function(e){
	e.preventDefault();
	formFactura();
});

function modal_pagos(){
	$('#modal_pagos').modal({
		show:true,
		keyboard: false,
		backdrop:'static'
	});
}

function formFactura(){
	 $('#formulario_facturacion')[0].reset();
	 $('#main_facturacion').hide();
	 $('#facturacion').show();
	 $('#label_acciones_volver').html("Facturación");
	 $('#acciones_atras').removeClass("active");
	 $('#acciones_factura').addClass("active");
	 $('#label_acciones_factura').html("Factura");
	 $('#formulario_facturacion #fecha').attr('disabled', false);
	 $('#formulario_facturacion').attr({ 'data-form': 'save' });
	 $('#formulario_facturacion').attr({ 'action': '<?php echo SERVERURL; ?>php/facturacion/addFactura.php' });
	 limpiarTabla();
	 $('#formulario_facturacion #addRows').show();
	 $('#formulario_facturacion #removeRows').show();
	 $('#formulario_facturacion #buscar_paciente').show();
	 $('#formulario_facturacion #buscar_colaboradores').show();
	 $('#formulario_facturacion #buscar_servicios').show();	 	 	 
}

$(document).ready(function() {
	$('#form_main_facturas #nuevo_registro').on('click',function(e){
		e.preventDefault();
		$('.footer').hide();
    	$('.footer1').show();		
		formFacturaGrupo();			
	});
});

function formFacturaGrupo(){
	$('#formGrupoFacturacion')[0].reset();
	$('#main_facturacion').hide();
	$('#facturacion').hide();
	$('#grupo_facturacion').show();
	$('#label_acciones_volver').html("Facturación");
	$('#acciones_atras').removeClass("active");
	$('#acciones_factura').addClass("active");
	$('#label_acciones_factura').html("Factura");
	$('#formGrupoFacturacion #fechaGrupo').attr('disabled', false);
	$('#formGrupoFacturacion').attr({ 'data-form': 'save' });
	$('#formGrupoFacturacion').attr({ 'action': '<?php echo SERVERURL; ?>php/facturacion/addGrupoFactura.php' });

	$('#formGrupoFacturacion #fechaGrupo').attr('readonly', 'true');

	$('#formGrupoFacturacion #invoiceItemGrupo > tbody').empty();
	$('#formGrupoFacturacion #clienteIDGrupo').val($('#form_main_facturas #pacientesIDGrupo').val());
	$('#formGrupoFacturacion #clienteNombreGrupo').val(getPacienteNombre($('#main_facturacion #pacientesIDGrupo').val()));

	$('#formGrupoFacturacion #buscar_pacienteGrupo').hide();
	$('#formGrupoFacturacion #buscar_colaboradoresGrupo').hide();
	$('#formGrupoFacturacion #buscar_serviciosGrupo').hide();	 	

	var tamaño = 0;
	var subTotal = 0;
	var ISVGrupo = 0;
	var descuentoGrupo  = 0;

	$('.registros .itemRowFactura').each(function(){
		if(this.checked){
			llenarTablaFacturaFacturaGrupo(tamaño);

			$('#formGrupoFacturacion #colaborador_idGrupo').val($('#profesionalIDGrupo_'+this.value).attr('data-value'));
			$('#formGrupoFacturacion #colaborador_nombreGrupo').val(getProfesionalNombre($('#profesionalIDGrupo_'+this.value).attr('data-value')));
			$('#formGrupoFacturacion #invoiceItemGrupo #quantyGrupoQuantity_'+tamaño).val($('#quantyGrupoQuantityValor_'+this.value).attr('data-value'));
			$('#formGrupoFacturacion #invoiceItemGrupo #billGrupoMuestraID_'+tamaño).val($('#muestraGrupo_'+this.value).attr('data-value'));
			$('#formGrupoFacturacion #invoiceItemGrupo #billGrupoMaterial_'+tamaño).val(getMaterialEnviado($('#muestraGrupo_'+this.value).attr('data-value')));
			$('#formGrupoFacturacion #invoiceItemGrupo #billGrupoID_'+tamaño).val($('#codigoFacturaGrupo_'+this.value).attr('data-value'));
			$('#formGrupoFacturacion #invoiceItemGrupo #pacienteIDBillGrupo_'+tamaño).val($('#pacientesIDFacturaGrupo_'+this.value).attr('data-value'));
			$('#formGrupoFacturacion #invoiceItemGrupo #pacienteBillGrupo_'+tamaño).val(getPacienteNombre($('#pacientesIDFacturaGrupo_'+this.value).attr('data-value')));
			$('#formGrupoFacturacion #invoiceItemGrupo #importeBillGrupo_'+tamaño).val(parseFloat($('#precioFacturaGrupo_'+this.value).attr('data-value')).toFixed(2));
			$('#formGrupoFacturacion #invoiceItemGrupo #discountBillGrupo_'+tamaño).val(parseFloat($('#DescuentoFacturaGrupo_'+this.value).attr('data-value')).toFixed(2));
			$('#formGrupoFacturacion #invoiceItemGrupo #totalBillGrupo_'+tamaño).val(parseFloat($('#precioFacturaGrupo_'+this.value).attr('data-value')).toFixed(2));
			subTotal += parseFloat($('#netoAntesISVFacturaGrupo_'+this.value).attr('data-value'));
			ISVGrupo += parseFloat($('#ISVFacturaGrupo_'+this.value).attr('data-value'));
			descuentoGrupo += parseFloat($('#DescuentoFacturaGrupo_'+this.value).attr('data-value'));
			tamaño++;
		}
	});

	//ENVIAMOS EL TAMAÑO AL FORMULARIO DE FACTURAS, QUE POSTERIOR MENTE SE USARA PARA SABER EL TAMAÑO DE LA TABLA Y PODER ITERAR LOS DETALLES DE ESTA
	$('#formGrupoFacturacion #tamano').val(tamaño);
	netoGrupo = (subTotal + ISVGrupo) - descuentoGrupo;

	$('#formGrupoFacturacion #subTotalBillGrupo').val(parseFloat(subTotal).toFixed(2));
	$('#formGrupoFacturacion #taxAmountBillGrupo').val(parseFloat(ISVGrupo).toFixed(2));
	$('#formGrupoFacturacion #taxDescuentoBillGrupo').val(parseFloat(descuentoGrupo).toFixed(2));
	$('#formGrupoFacturacion #totalAftertaxBillGrupo').val(parseFloat(netoGrupo).toFixed(2));

	$('#subTotalFooter').val(parseFloat(subTotal).toFixed(2));
	$('#taxAmountFooter').val(parseFloat(ISVGrupo).toFixed(2));
	$('#taxDescuentoFooter').val(parseFloat(descuentoGrupo).toFixed(2));
	$('#totalAftertaxFooter').val(parseFloat(netoGrupo).toFixed(2));	
}

function llenarTablaFacturaFacturaGrupo(count){
	var htmlRows = '';
	htmlRows += '<tr>';
	htmlRows += '<td><input type="hidden" name="quantyGrupoQuantity[]" id="quantyGrupoQuantity_'+count+'" class="form-control" placeholder="Cantidad" readonly autocomplete="off"><input type="hidden" name="billGrupoMuestraID[]" id="billGrupoMuestraID_'+count+'" class="form-control" placeholder="Muestra ID" readonly autocomplete="off"><input type="hidden" name="billGrupoMaterial[]" id="billGrupoMaterial_'+count+'" class="form-control" placeholder="Material Enviado" readonly autocomplete="off"><input type="hidden" name="billGrupoDescuento[]" id="billGrupoDescuento_'+count+'" class="form-control" placeholder="Descuento" readonly autocomplete="off"><input type="hidden" name="billGrupoISV[]" id="billGrupoISV_'+count+'" value="0" class="form-control" placeholder="ISV" readonly autocomplete="off"><input type="hidden" name="billGrupoID[]" id="billGrupoID_'+count+'" class="form-control" placeholder="Código Factura" readonly autocomplete="off"><input type="hidden" name="pacienteIDBillGrupo[]" id="pacienteIDBillGrupo_'+count+'" class="form-control" readonly placeholder="Paciente" autocomplete="off"><input type="text" name="pacienteBillGrupo[]" id="pacienteBillGrupo_'+count+'" class="form-control" readonly placeholder="Paciente" autocomplete="off"></td>';
	htmlRows += '<td><input type="number" name="importeBillGrupo[]" id="importeBillGrupo_'+count+'" class="form-control" readonly placeholder="Saldo" autocomplete="off"></td>';
	htmlRows += '<td><input type="number" name="discountBillGrupo[]" id="discountBillGrupo_'+count+'" readonly value="0" class="form-control" placeholder="Descuento" autocomplete="off"></td>';
	htmlRows += '<td><input type="number" name="totalBillGrupo[]" id="totalBillGrupo_'+count+'" class="form-control total" placeholder="Total" readonly autocomplete="off"></td>';
	htmlRows += '</tr>';
	$('#formGrupoFacturacion #invoiceItemGrupo').append(htmlRows);
}

function limpiarTablaFacturaGrupo(){
	$("#formGrupoFacturacion #invoiceItemGrupo > tbody").empty();//limpia solo los registros del body
	var count = 0;
	var htmlRows = '';
	htmlRows += '<tr>';
	htmlRows += '<td><input type="hidden" name="quantyGrupoQuantity[]" id="quantyGrupoQuantity_'+count+'" class="form-control" placeholder="Cantidad" readonly autocomplete="off"><input type="hidden" name="billGrupoMuestraID[]" id="billGrupoMuestraID_'+count+'" class="form-control" placeholder="Muestra ID" readonly autocomplete="off"><input type="hidden" name="billGrupoMaterial[]" id="billGrupoMaterial_'+count+'" class="form-control" placeholder="Material Enviado" readonly autocomplete="off"><input type="hidden" name="billGrupoDescuento[]" id="billGrupoDescuento_'+count+'" class="form-control" placeholder="Descuento" readonly autocomplete="off"><input type="hidden" name="billGrupoISV[]" id="billGrupoISV_'+count+'" value="0" class="form-control" placeholder="ISV" readonly autocomplete="off"><input type="hidden" name="billGrupoID[]" id="billGrupoID_'+count+'" class="form-control" placeholder="Código Factura" readonly autocomplete="off"><input type="hidden" name="pacienteIDBillGrupo[]" id="pacienteIDBillGrupo_'+count+'" class="form-control" readonly placeholder="Paciente" autocomplete="off"><input type="text" name="pacienteBillGrupo[]" id="pacienteBillGrupo_'+count+'" class="form-control" readonly placeholder="Paciente" autocomplete="off"></td>';
	htmlRows += '<td><input type="number" name="importeBillGrupo[]" id="importeBillGrupo_'+count+'" class="form-control" readonly placeholder="Saldo" autocomplete="off"></td>';
	htmlRows += '<td><input type="number" name="discountBillGrupo[]" id="discountBillGrupo_'+count+'" readonly value="0" class="form-control" placeholder="Descuento" autocomplete="off"></td>';
	htmlRows += '<td><input type="number" name="totalBillGrupo[]" id="totalBillGrupo_'+count+'" class="form-control total" placeholder="Total" readonly autocomplete="off"></td>';
	htmlRows += '</tr>';
	$('#formGrupoFacturacion #invoiceItemGrupo').append(htmlRows);
}

$(document).ready(function() {
	$('#label_acciones_volver').html("Facturación");
	$('#acciones_atras').addClass("active");
	$('#label_acciones_factura').html("");
});
//FIN BUSQUEDA PACIENTES

//INICIO BUSQUEDA COLABORADORES
$('#formulario_facturacion #buscar_colaboradores').on('click', function(e){
	e.preventDefault();
	listar_colaboradores_buscar();
	$('#modal_busqueda_colaboradores').modal({
		show:true,
		keyboard: false,
		backdrop:'static'
	});
});

var listar_colaboradores_buscar = function(){
	var table_colaboradores_buscar = $("#dataTableColaboradores").DataTable({
		"destroy":true,
		"ajax":{
			"method":"POST",
			"url":"<?php echo SERVERURL; ?>php/facturacion/getColaboradoresTabla.php"
		},
		"columns":[
			{"defaultContent":"<button class='view btn btn-primary'><span class='fas fa-copy'></span></button>"},
			{"data":"colaborador"},
			{"data":"identidad"},
			{"data":"puesto"}
		],
		"pageLength" : 5,
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,
	});
	table_colaboradores_buscar.search('').draw();
	$('#buscar').focus();

	view_colaboradores_busqueda_dataTable("#dataTableColaboradores tbody", table_colaboradores_buscar);
}

var view_colaboradores_busqueda_dataTable = function(tbody, table){
	$(tbody).off("click", "button.view");
	$(tbody).on("click", "button.view", function(e){
		e.preventDefault();
		var data = table.row( $(this).parents("tr") ).data();
		$('#formulario_facturacion #colaborador_id').val(data.colaborador_id);
		$('#formulario_facturacion #colaborador_nombre').val(data.colaborador);
		$('#modal_busqueda_colaboradores').modal('hide');
	});
}
//FIN BUSQUEDA COLABORADORES

//INCIO ELIMINAR FACTURA BORRADOR
function deleteBill(facturas_id){
	if (getUsuarioSistema() == 1 || getUsuarioSistema() == 2 || getUsuarioSistema() == 3){
		swal({
		  title: "¿Estas seguro?",
		  text: "¿Desea eliminar la factura para el paciente: " + getNumeroNombrePaciente(facturas_id) + "?",
		  type: "info",
		  showCancelButton: true,
		  confirmButtonClass: "btn-primary",
		  confirmButtonText: "¡Sí, Eliminar la!",
		  cancelButtonText: "Cancelar",
		  closeOnConfirm: false
		},
		function(){
			eliminarFacturaBorrador(facturas_id);
		});
	}else{
		swal({
			title: "Acceso Denegado",
			text: "No tiene permisos para ejecutar esta acción",
			type: "error",
			confirmButtonClass: 'btn-danger'
		});
	}
}

function eliminarFacturaBorrador(facturas_id){
	var url = '<?php echo SERVERURL; ?>php/facturacion/eliminar.php';
	$.ajax({
		type:'POST',
		url:url,
		data:'facturas_id='+facturas_id,
		success: function(registro){
			if(registro == 1){
				swal({
					title: "Success",
					text: "Registro eliminado correctamente",
					type: "success",
					timer: 3000,
				});
				pagination(1);
			   return false;
			}else if(registro == 2){
				swal({
					title: "Error al eliminar el registro, por favor intentelo de nuevo o verifique que no tenga información almacenada",
					text: "No tiene permisos para ejecutar esta acción",
					type: "error",
					confirmButtonClass: 'btn-danger'
				});
			    return false;
			}else{
				swal({
					title: "No se puede procesar su solicitud, por favor intentelo de nuevo mas tarde",
					text: "No tiene permisos para ejecutar esta acción",
					type: "error",
					confirmButtonClass: 'btn-danger'
				});
			    return false;
			}
  		}
	});
	return false;
}
//FIN ELIMINAR FACTURA BORRADOR

$(document).ready(function(){
	$(document).on('click', '#checkAllFactura', function(){
		if($('#form_main_facturas #tipo_paciente_grupo').val() == 2 && $('#form_main_facturas #pacientesIDGrupo').val() != ""){
			$(".itemRowFactura").prop("checked", this.checked);
			if ($('#checkAllFactura').is(':checked') ){
				$('#main_facturacion #factura_manual').show();
				calcularTotalFactura();
			}else{
				$('#main_facturacion #factura_manual').hide();
			}
		}
	});
	$(document).on('click', '.itemRowFactura', function(){
		if($('#form_main_facturas #tipo_paciente_grupo').val() == 2 && $('#form_main_facturas #pacientesIDGrupo').val() != ""){
			if ($('.itemRowFactura').is(':checked') ){
				$('#main_facturacion #factura_manual').show();
				calcularTotalFactura();
			}else{
				$('#main_facturacion #factura_manual').hide();
			}

			if ($('.itemRowFactura:checked').length == $('.itemRowFactura').length) {
				$('#checkAllFactura').prop('checked', true);
			} else {
				$('#checkAllFactura').prop('checked', false);
			}
		}
	});
	var count = $(".itemRowFactura").length;
});

function calcularTotalFactura(){
	var total_factura = 0;
	if($('#form_main_facturas #tipo_paciente_grupo').val() == 2 && $('#form_main_facturas #pacientesIDGrupo').val() != ""){
	   	$("[id^='importeFacturaGrupo_']").each(function() {
			var id = $(this).attr('id');
			id = id.replace("importeFacturaGrupo_",'');
			var importe_factura = parseFloat($('#importeFacturaGrupo_'+id).attr('data-value'));
			total_factura += importe_factura;
		});

	   	$("[id^='codigoFacturaGrupo_']").each(function() {
			var id = $(this).attr('id');
			id = id.replace("codigoFacturaGrupo_",'');
			var importe_factura = parseFloat($('#codigoFacturaGrupo_'+id).attr('data-value'));
			total_factura += importe_factura;
		});
	}
}

function getTipoPaciente(pacientes_id){
	var url = '<?php echo SERVERURL; ?>php/muestras/getTipoPaciente.php';
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

function getPacienteNombre(pacientes_id){
	var url = '<?php echo SERVERURL; ?>php/muestras/getPacienteNombre.php';
	var paciente_nombre;

	$.ajax({
	    type:'POST',
		url:url,
		data:'pacientes_id='+pacientes_id,
		async: false,
		success:function(data){
          paciente_nombre = data;
		}
	});
	return paciente_nombre;
}

function getProfesionalNombre(colaborador_id){
	var url = '<?php echo SERVERURL; ?>php/muestras/getProfesionalNombre.php';
	var colaborador_nombre;

	$.ajax({
	    type:'POST',
		url:url,
		data:'colaborador_id='+colaborador_id,
		async: false,
		success:function(data){
          colaborador_nombre = data;
		}
	});
	return colaborador_nombre;
}

function getMaterialEnviado(muestras_id){
	var url = '<?php echo SERVERURL; ?>php/muestras/getMaterialEnviado.php';
	var material_enviado;

	$.ajax({
	    type:'POST',
		url:url,
		data:'muestras_id='+muestras_id,
		async: false,
		success:function(data){
		  var datos = eval(data);
          material_enviado = datos[0];
		}
	});
	return material_enviado;
}

function volver(){
	$('#main_facturacion').show();
	$('#label_acciones_factura').html("");
	$('#facturacion').hide();
	$('#grupo_facturacion').hide();
	$('#acciones_atras').addClass("breadcrumb-item active");
	$('#acciones_factura').removeClass("active");
	$('.footer').show();
	$('.footer1').hide();	
}

function getTipoPacienteGrupo(){
    var url = '<?php echo SERVERURL; ?>php/facturacion/getTipoPaciente.php';

	$.ajax({
        type: "POST",
        url: url,
        success: function(data){
		    $('#form_main_facturas #tipo_paciente_grupo').html("");
			$('#form_main_facturas #tipo_paciente_grupo').html(data);
			getPacienteGrupo(1);
		}
     });
}

function getPacienteGrupo(tipo_paciente){
    var url = '<?php echo SERVERURL; ?>php/facturacion/getPacienteGrupo.php';

	$.ajax({
        type: "POST",
        url: url,
		data:'tipo_paciente='+tipo_paciente,
        success: function(data){
		    $('#form_main_facturas #pacientesIDGrupo').html("");
			$('#form_main_facturas #pacientesIDGrupo').html(data);
		}
     });
}

$('#form_main_facturas #tipo_paciente_grupo').on('change',function(){
	getPacienteGrupo($('#form_main_facturas #tipo_paciente_grupo').val());
});

function cierreCaja(){
	$('#formularioCierreCaja #pro').val("Cierre de Caja");

	$('#modalCierreCaja').modal({
		show:true,
		keyboard: false,
		backdrop:'static'
	});

	$('#formularioCierreCaja').attr({ 'data-form': 'save' });
	$('#formularioCierreCaja').attr({ 'action': '<?php echo SERVERURL; ?>php/facturacion/addPago.php' });
}

$('#form_main_facturas #cierre').on('click', function(e){
	e.preventDefault();
	cierreCaja();
});

$('#generarCierreCaja').on('click', function(e){
	e.preventDefault();
	var fecha = $('#formularioCierreCaja #fechaCierreCaja').val();
	var url = '<?php echo SERVERURL; ?>php/facturacion/generaCierreCaja.php?fecha='+fecha;
    window.open(url);
	$('#modalCierreCaja').modal('hide');
});

$('#form_main_facturas #buscar_cliente_muestras').on('click', function(e){
	listar_pacientesfacturas_tipo_buscar();
	 $('#modal_busqueda_pacientes').modal({
		show:true,
		keyboard: false,
		backdrop:'static'
	});
});

var listar_pacientesfacturas_tipo_buscar = function(){
	var tipo_paciente = 1;
	
	if($("#form_main_facturas #tipo_paciente_grupo").val() != ""){
		tipo_paciente = $("#form_main_facturas #tipo_paciente_grupo").val();
	}
	
	var table_pacientes_facturas_tipo_buscar = $("#dataTablePacientes").DataTable({
		"destroy":true,
		"ajax":{
			"method":"POST",
			"url":"<?php echo SERVERURL; ?>php/muestras/getPacienteGrupoTabla.php",
			"data":{
				"tipo_paciente":tipo_paciente
			}			
		},
		"columns":[
			{"defaultContent":"<button class='view btn btn-primary'><span class='fas fa-copy'></span></button>"},
			{"data":"paciente"},
			{"data":"identidad"},
			{"data":"expediente"},
			{"data":"email"}
		],
		"pageLength" : 5,
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,
	});
	table_pacientes_facturas_tipo_buscar.search('').draw();
	$('#buscar').focus();

	view_pacientes_facturas_tipo_busqueda_dataTable("#dataTablePacientes tbody", table_pacientes_facturas_tipo_buscar);
}

var view_pacientes_facturas_tipo_busqueda_dataTable = function(tbody, table){
	$(tbody).off("click", "button.view");
	$(tbody).on("click", "button.view", function(e){
		e.preventDefault();
		var data = table.row( $(this).parents("tr") ).data();
		$('#form_main_facturas #pacientesIDGrupo').val(data.pacientes_id);
		pagination(1);
		$('#modal_busqueda_pacientes').modal('hide');
	});
}
/*														 	FIN FACTURACIÓN				   															 	*/
/*
###########################################################################################################################################################
###########################################################################################################################################################
###########################################################################################################################################################
*/
$(document).ready(function() {		
	$('#formulario_facturacion #label_facturas_activo').html("Contado");
	
    $('#formulario_facturacion .switch').change(function(){    
        if($('input[name=facturas_activo]').is(':checked')){
            $('#formulario_facturacion #label_facturas_activo').html("Contado");
            return true;
        }
        else{
            $('#formulario_facturacion #label_facturas_activo').html("Crédito");
            return false;
        }
    });	

	$('#formGrupoFacturacion #label_facturas_grupal_activo').html("Contado");
	
    $('#formGrupoFacturacion .switch').change(function(){    
        if($('input[name=facturas_grupal_activo]').is(':checked')){
            $('#formGrupoFacturacion #label_facturas_grupal_activo').html("Contado");
            return true;
        }
        else{
            $('#formGrupoFacturacion #label_facturas_grupal_activo').html("Crédito");
            return false;
        }
    });		
});

//INICIO CONTROLES MODAL PAGO
$(".menu-toggle1").on("click", function(e){
	e.preventDefault();
	$(".menu-toggle1").hide();
	$(".menu-toggle2").show();
});

$(".menu-toggle2").on("click", function(e){
	e.preventDefault();
	$(".menu-toggle2").hide();
	$(".menu-toggle1").show();
});

//Menu Toggle Script
$("#menu-toggle1").click(function(e) {
	e.preventDefault();
	$("#wrapper").toggleClass("toggled");
});

$("#menu-toggle2").click(function(e) {
	e.preventDefault();
	$("#wrapper").toggleClass("toggled");
});	

$(document).ready(function(){
	$(".menu-toggle2").hide();
	$("#tab1").addClass("active1");
	$("#sidebar-wrapper").toggleClass("toggled");
	
	//Menu Toggle Script
	$("#menu-toggle").click(function(e) {
		e.preventDefault();
		$("#wrapper").toggleClass("toggled");
	});

	// For highlighting activated tabs
	$("#tab1").click(function () {
		$(".tabs").removeClass("active1");
		$(".tabs").addClass("bg-light");
		$("#tab1").addClass("active1");
		$("#tab1").removeClass("bg-light");
	});

	$("#tab2").click(function () {
		$(".tabs").removeClass("active1");
		$(".tabs").addClass("bg-light");
		$("#tab2").addClass("active1");
		$("#tab2").removeClass("bg-light");
	});

	$("#tab3").click(function () {
		$(".tabs").removeClass("active1");
		$(".tabs").addClass("bg-light");
		$("#tab3").addClass("active1");
		$("#tab3").removeClass("bg-light");
	});

	$("#tab4").click(function () {
		$(".tabs").removeClass("active1");
		$(".tabs").addClass("bg-light");
		$("#tab4").addClass("active1");
		$("#tab4").removeClass("bg-light");
	});	
	
	$("#tab5").click(function () {
		$(".tabs").removeClass("active1");
		$(".tabs").addClass("bg-light");
		$("#tab5").addClass("active1");
		$("#tab5").removeClass("bg-light");
	});		
})
//FIN CONTROLES MODAL PAGO

//INICIO CONTROLES MODAL PAGO GRUPAL
$(".menu-toggleGrupal1").on("click", function(e){
	e.preventDefault();
	$(".menu-toggleGrupal1").hide();
	$(".menu-toggleGrupal2").show();
});

$(".menu-toggleGrupal2").on("click", function(e){
	e.preventDefault();
	$(".menu-toggleGrupal2").hide();
	$(".menu-toggleGrupal1").show();
});

//Menu Toggle Script
$("#menu-toggleGrupal1").click(function(e) {
	e.preventDefault();
	$("#wrapper").toggleClass("toggled");
});

$("#menu-toggleGrupal2").click(function(e) {
	e.preventDefault();
	$("#wrapper").toggleClass("toggled");
});	

$(document).ready(function(){
	$(".menu-toggleGrupal2").hide();
	$("#tabGrupal1").addClass("active1");
	$("#sidebar-wrapper").toggleClass("toggled");
	
	//Menu Toggle Script
	$("#menu-toggle").click(function(e) {
		e.preventDefault();
		$("#wrapper").toggleClass("toggled");
	});

	// For highlighting activated tabs
	$("#tabGrupal1").click(function () {
		$(".tabs").removeClass("active1");
		$(".tabs").addClass("bg-light");
		$("#tabGrupal1").addClass("active1");
		$("#tabGrupal1").removeClass("bg-light");
	});

	$("#tabGrupal2").click(function () {
		$(".tabs").removeClass("active1");
		$(".tabs").addClass("bg-light");
		$("#tabGrupal2").addClass("active1");
		$("#tabGrupal2").removeClass("bg-light");
	});

	$("#tabGrupal3").click(function () {
		$(".tabs").removeClass("active1");
		$(".tabs").addClass("bg-light");
		$("#tabGrupal3").addClass("active1");
		$("#tabGrupal3").removeClass("bg-light");
	});

	$("#tabGrupal4").click(function () {
		$(".tabs").removeClass("active1");
		$(".tabs").addClass("bg-light");
		$("#tabGrupal4").addClass("active1");
		$("#tabGrupal4").removeClass("bg-light");
	});	
	
	$("#tabGrupal5").click(function () {
		$(".tabs").removeClass("active1");
		$(".tabs").addClass("bg-light");
		$("#tabGrupal5").addClass("active1");
		$("#tabGrupal5").removeClass("bg-light");
	});		
})
//FIN CONTROLES MODAL PAGO GRUPAL

//INICIO FORMULARIO PAGO FACTURAS
function pay(facturas_id){
	if (getUsuarioSistema() == 1 || getUsuarioSistema() == 2 || getUsuarioSistema() == 3){
		$('#formulario_facturacion')[0].reset();
		$("#formulario_facturacion #invoiceItem > tbody").empty();//limpia solo los registros del body

		var url = '<?php echo SERVERURL; ?>php/facturacion/editarFactura.php';
			$.ajax({
			type:'POST',
			url:url,
			data:'facturas_id='+facturas_id,
			success: function(valores){
				var datos = eval(valores);
				$('#formulario_facturacion #facturas_id').val(facturas_id);
				$('#formulario_facturacion #pacientes_id').val(datos[0]);
				$('#formulario_facturacion #cliente_nombre').val(datos[1]);
				$('#formulario_facturacion #colaborador_id').val(datos[3]);
				$('#formulario_facturacion #colaborador_nombre').val(datos[4]);
				$('#formulario_facturacion #servicio_id').val(datos[5]);
				$('#formulario_facturacion #notes').val(datos[6]);
				$('#formulario_facturacion #paciente_muestra').val(datos[7]);

				if(getTipoPaciente(datos[0]) == 2){
					$('#formulario_facturacion #grupo_paciente_factura').show();
				}else{
					$('#formulario_facturacion #grupo_paciente_factura').hide();
				}

				$('#formulario_facturacion #fecha').attr("readonly", true);
				$('#formulario_facturacion #validar').attr("disabled", false);
				$('#formulario_facturacion #addRows').attr("disabled", false);
				$('#formulario_facturacion #removeRows').attr("disabled", false);
				$('#formulario_facturacion #validar').hide();
				$('#formulario_facturacion #cobrar').show();
				$('#formulario_facturacion #editar').hide();
				$('#formulario_facturacion #eliminar').hide();
				$('#formulario_facturacion #buscar_paciente').hide();
	 			$('#formulario_facturacion #buscar_colaboradores').hide();
				$('#formulario_facturacion #buscar_servicios').hide();				

				$('#grupo_facturacion').hide();
				$('#main_facturacion').hide();
				$('#label_acciones_factura').html("Factura");
				$('#facturacion').show();

				$('#formulario_facturacion').attr({ 'data-form': 'save' });
				$('#formulario_facturacion').attr({ 'action': '<?php echo SERVERURL; ?>php/facturacion/addFacturaporUsuario.php' });

				$('.footer').hide();
     			$('.footer1').show();	
				return false;
			}
		});

		var url = '<?php echo SERVERURL; ?>php/facturacion/editarFacturaDetalles.php';
		var isv_valor = 0.0;

		$.ajax({
			type:'POST',
			url:url,
			data:'facturas_id='+facturas_id,
			success:function(data){
				var datos = eval(data);
				for(var fila=0; fila < datos.length; fila++){
					var facturas_detalle_id = datos[fila]["facturas_detalle_id"];
					var productoID = datos[fila]["productos_id"];
					var productName = datos[fila]["producto"];
					var quantity = datos[fila]["cantidad"];
					var price = datos[fila]["precio"];
					var discount = datos[fila]["descuento"];
					var isv = datos[fila]["isv_valor"];
					var producto_isv = datos[fila]["producto_isv"];
					isv_valor = parseFloat(isv_valor) + parseFloat(datos[fila]["isv_valor"]);
					llenarTablaFactura(fila);
					$('#formulario_facturacion #invoiceItem #facturas_detalle_id_'+ fila).val(facturas_detalle_id);
					$('#formulario_facturacion #invoiceItem #productoID_'+ fila).val(productoID);
					$('#formulario_facturacion #invoiceItem #productName_'+ fila).val(productName);
					$('#formulario_facturacion #invoiceItem #quantity_'+ fila).val(quantity);
					$('#formulario_facturacion #invoiceItem #price_'+ fila).val(price);
					$('#formulario_facturacion #invoiceItem #discount_'+ fila).val(discount);
					$('#formulario_facturacion #invoiceItem #valor_isv_'+ fila).val(isv);
					$('#formulario_facturacion #invoiceItem #isv_'+ fila).val(data.producto_isv);

					$('#formulario_facturacion #invoiceItem #productName_'+ fila).attr("readonly", true);
					$('#formulario_facturacion #invoiceItem #quantity_'+ fila).attr("readonly", true);
					$('#formulario_facturacion #invoiceItem #price_'+ fila).attr("readonly", true);
					$('#formulario_facturacion #invoiceItem #discount_'+ fila).attr("readonly", true);
					$('#formulario_facturacion #invoiceItem #grupo_buscar_productos').hide();
					$('#formulario_facturacion #addRows').hide();
					$('#formulario_facturacion #removeRows').hide();					
				}
				$('#formulario_facturacion #taxAmount').val(isv_valor);
				calculateTotal();
			}
		});
		return false;
	}else{
		swal({
			title: "Acceso Denegado",
			text: "No tiene permisos para ejecutar esta acción",
			type: "error",
			confirmButtonClass: 'btn-danger'
		});
	}

	//MOSTRAMOS EL FORMULARIO PARA EL METODO DE PAGO
}
//FIN FORMULARIO PAGO FACTURAS

//INICIO FORMULARIO DE BUSQUEDA CLIENTES
$('#formulario_facturacion #buscar_paciente').on('click', function(e){
	e.preventDefault();
	 $('#modal_busqueda_pacientes').modal({
		show:true,
		keyboard: false,
		backdrop:'static'
	});		 
});

var listar_pacientes_buscar = function(){
	var tipo_paciente = $("#form_main_facturas #tipo_paciente_grupo").val();

	if(tipo_paciente == "" || tipo_paciente == null){
		tipo_paciente = 1;
	}

	var table_pacientes_buscar = $("#dataTablePacientes_main_muestras").DataTable({
		"destroy":true,	
		"ajax":{
			"method":"POST",
			"url":"<?php echo SERVERURL; ?>php/muestras/getPacientesTabla.php",
			"data":{
				"tipo_paciente":tipo_paciente
			}				
		},
		"columns":[
			{"defaultContent":"<button class='view btn btn-primary'><span class='fas fa-copy'></span></button>"},
			{"data":"paciente"},
			{"data":"identidad"},
			{"data":"expediente"},
			{"data":"email"}
		],
		"pageLength" : 5,
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,
		"dom": dom,
		"buttons":[
			{
				text:      '<i class="fas fa-sync-alt fa-lg"></i> Actualizar',
			titleAttr: 'Actualizar Registro',
				className: 'table_actualizar btn btn-secondary',
				action: 	function(){
					listar_pacientes_buscar();
				}
			}		
		],
	});
	table_pacientes_buscar.search('').draw();
	$('#buscar').focus();

	view_pacientes_busqueda_dataTable("#dataTablePacientes_main_muestras tbody", table_pacientes_buscar);
}

var view_pacientes_busqueda_dataTable = function(tbody, table){
	$(tbody).off("click", "button.view");
	$(tbody).on("click", "button.view", function(e){
		e.preventDefault();
		var data = table.row( $(this).parents("tr") ).data();
		$('#form_main_facturas #pacientesIDGrupo').val(data.pacientes_id);
		pagination(1);
		$('#modal_busqueda_pacientes_main_muetras').modal('hide');
	});
}

$('#formularioMuestras #buscar_paciente_consulta_muestras').on('click', function(e){
	$('#modal_busqueda_pacientes_muestras').modal({
		show:true,
		keyboard: false,
		backdrop:'static'
	});
});

function getTotalFacturasDisponibles(){
	var url = '<?php echo SERVERURL; ?>php/facturacion/getTotalFacturasDisponibles.php';

	$.ajax({
	   type:'POST',
	   url:url,
	   async: false,
	   success:function(registro){
			var valores = eval(registro);
			var mensaje = "";
			if(valores[0] >=10 && valores[0] <= 30){
				mensaje = "Total Facturas disponibles: " + valores[0];

				$("#mensajeFacturas").html(mensaje).addClass("alert alert-warning");
				$("#mensajeFacturas").html(mensaje).removeClass("alert alert-danger");

				$("#mensajeFacturas").attr("disabled", true);
				$("#formulario_facturacion #validar").attr("disabled", false);
				$("#formulario_facturacion #cobrar").attr("disabled", false);	


			}else if(valores[0] >=1 && valores[0] <= 9){
				mensaje = "Total Facturas disponibles: " + valores[0];
				$("#mensajeFacturas").html(mensaje).addClass("alert alert-danger");
				$("#mensajeFacturas").html(mensaje).removeClass("alert alert-warning");
				$("#mensajeFacturas").attr("disabled", true);
				$("#formulario_facturacion #validar").attr("disabled", false);
				$("#formulario_facturacion #cobrar").attr("disabled", false);	
			}
			else{
				mensaje = "";

				$("#formulario_facturacion #validar").attr("disabled", false);	
				$("#formulario_facturacion #cobrar").attr("disabled", false);			
				$("#mensajeFacturas").html(mensaje).addClass("alert alert-danger");
				$("#mensajeFacturas").html(mensaje).removeClass("alert alert-warning");				
			}

			if(valores[0] == 0){
				mensaje = "No puede seguir facturando";

				$("#formulario_facturacion #cobrar").attr("disabled", true);	
				$("#formulario_facturacion #validar").attr("disabled", true);			
				$("#mensajeFacturas").html(mensaje).addClass("alert alert-danger");
				$("#mensajeFacturas").html(mensaje).removeClass("alert alert-warning");
			}
			
			if(valores[1] == 1){
				mensaje += "<br/>Su fecha límite es: " + valores[2];
				$("#formulario_facturacion #validar").attr("disabled", false);	
				$("#formulario_facturacion #cobrar").attr("disabled", false);				
				$("#mensajeFacturas").html(mensaje).addClass("alert alert-warning");
				$("#mensajeFacturas").html(mensaje).removeClass("alert alert-danger");			
			}

			if(valores[1] == 0){
				mensaje += "<br/>Ya alcanzo su fecha límite";
				$("#formulario_facturacion #validar").attr("disabled", true);	
				$("#formulario_facturacion #cobrar").attr("disabled", true);				
				$("#mensajeFacturas").html(mensaje).addClass("alert alert-danger");	
				$("#mensajeFacturas").html(mensaje).removeClass("alert alert-warning");		
			}			
	   }
	});
}

setInterval('getTotalFacturasDisponibles()',1000);
</script>
