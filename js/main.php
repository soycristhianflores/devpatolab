<script>
function reportePDF(agenda_id){
	if (getUsuarioSistema() == 1 || getUsuarioSistema() == 3 || getUsuarioSistema() == 4 || getUsuarioSistema() == 5 || getUsuarioSistema() == 8 || getUsuarioSistema() == 9){
	    window.open('<?php echo SERVERURL; ?>php/citas/tickets.php?agenda_id='+agenda_id);
	}else{	
		swal({
			title: "Acceso Denegado", 
			text: "No tiene permisos para ejecutar esta acción",
			type: "error", 
			confirmButtonClass: 'btn-danger'
		});			
        return false;	  
    }
}

function sendEmailReprogramación(agenda_id){
    var url = '<?php echo SERVERURL; ?>php/mail/correo_reprogramaciones.php';
	$.ajax({
	    type:'POST',
		url:url,
		data:'agenda_id='+agenda_id,
		success: function(valores){	
           		  		  		  			  
		}
	});	
}

function getUsuarioSistema(){
    var url = '<?php echo SERVERURL; ?>php/sesion/sistema_tipo_usuario.php';
	var usuario;
	$.ajax({
	    type:'POST',
		url:url,
		async: false,
		success:function(data){	
          usuario = data;			  		  		  			  
		}
	});
	return usuario;
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip({
	  trigger: "hover"
  })
});

function getMonth(){
	const hoy = new Date()
	return hoy.toLocaleString('default', { month: 'long' });
}
/*
###########################################################################################################################################################
###########################################################################################################################################################
###########################################################################################################################################################
*/
/*															INICIO FACTURACIÓN				   															 */
//INICIO BUSQUEDA PACIENTES
//FIN BUSQUEDA PACIENTES

//INICIO BUSQUEDA SERVICIOS
$('#formulario_facturacion #buscar_servicios').on('click', function(e){
	e.preventDefault();
	listar_servicios_factura_buscar();
	$('#modal_busqueda_servicios').modal({
		show:true,
		keyboard: false,
		backdrop:'static'
	});		 
});
//FIN BUSQUEDA SERVICIOS

//INICIO BUSQUEDA PRODUCTOS FACTURA
$(document).ready(function(){
    $("#formulario_facturacion #invoiceItem").on('click', '.buscar_producto', function() {
		  listar_productos_facturas_buscar();
		  var row_index = $(this).closest("tr").index();
		  var col_index = $(this).closest("td").index();
		  
		  $('#formulario_busqueda_productos_facturas #row').val(row_index);
		  $('#formulario_busqueda_productos_facturas #col').val(col_index);		  
		  $('#modal_busqueda_productos_facturas').modal({
			show:true,
			keyboard: false,
			backdrop:'static'
		  });
	});
});
//FIN BUSQUEDA PRODUCTOS FACTURA

//EVALUAMOS LA CANTIDAD PARA REALIZAR EL CALCULO
$(document).ready(function(){
    $("#formulario_facturacion #invoiceItem").on('blur', '.buscar_cantidad', function() {
		var row_index = $(this).closest("tr").index();
		var col_index = $(this).closest("td").index();

		var impuesto_venta = parseFloat($('#formulario_facturacion #invoiceItem #isv_'+ row_index).val());
		var cantidad = parseFloat($('#formulario_facturacion #invoiceItem #quantity_'+ row_index).val());
		var precio = parseFloat($('#formulario_facturacion #invoiceItem #price_'+ row_index).val());
		var total = parseFloat($('#formulario_facturacion #invoiceItem #total_'+ row_index).val());

		var isv = 0;
		var isv_total = 0;
		var porcentaje_isv = 0;
		var porcentaje_calculo = 0;
		var isv_neto = 0;
		
		if(impuesto_venta == 1){
			porcentaje_isv = parseFloat(getPorcentajeISV() / 100);
			if(total == "" || total == 0){
				porcentaje_calculo = (parseFloat(precio) * parseFloat(cantidad) * porcentaje_isv).toFixed(2);			
				isv_neto = parseFloat(porcentaje_calculo).toFixed(2);
				$('#formulario_facturacion #invoiceItem #valor_isv_'+ row_index).val(porcentaje_calculo);
			}else{	
				isv_total = parseFloat($('#formulario_facturacion #taxAmount').val());
				porcentaje_calculo = (parseFloat(precio) * parseFloat(cantidad) * porcentaje_isv).toFixed(2);
				isv_neto = parseFloat(isv_total) + parseFloat(porcentaje_calculo);
				$('#formulario_facturacion #invoiceItem #valor_isv_'+ row_index).val(porcentaje_calculo);
			}
		}

		calculateTotal();
	});
});

$(document).ready(function(){
    $("#formulario_facturacion #invoiceItem").on('keyup', '.buscar_cantidad', function() {
		var row_index = $(this).closest("tr").index();
		var col_index = $(this).closest("td").index();

		var impuesto_venta = parseFloat($('#formulario_facturacion #invoiceItem #isv_'+ row_index).val());
		var cantidad = parseFloat($('#formulario_facturacion #invoiceItem #quantity_'+ row_index).val());
		var precio = parseFloat($('#formulario_facturacion #invoiceItem #price_'+ row_index).val());
		var total = parseFloat($('#formulario_facturacion #invoiceItem #total_'+ row_index).val());

		var isv = 0;
		var isv_total = 0;
		var porcentaje_isv = 0;
		var porcentaje_calculo = 0;
		var isv_neto = 0;
		
		if(impuesto_venta == 1){
			porcentaje_isv = parseFloat(getPorcentajeISV() / 100);
			if(total == "" || total == 0){
				porcentaje_calculo = (parseFloat(precio) * parseFloat(cantidad) * porcentaje_isv).toFixed(2);			
				isv_neto = parseFloat(porcentaje_calculo).toFixed(2);
				$('#formulario_facturacion #invoiceItem #valor_isv_'+ row_index).val(porcentaje_calculo);
			}else{	
				isv_total = parseFloat($('#formulario_facturacion #taxAmount').val());
				porcentaje_calculo = (parseFloat(precio) * parseFloat(cantidad) * porcentaje_isv).toFixed(2);
				isv_neto = parseFloat(isv_total) + parseFloat(porcentaje_calculo);
				$('#formulario_facturacion #invoiceItem #valor_isv_'+ row_index).val(porcentaje_calculo);
			}
		}

		calculateTotal();
	});
});
//FIN FORMULARIOS

//INICIO FUNCIONES PARA LLENAR DATOS EN LA TABLA
var listar_servicios_factura_buscar = function(){
	var table_servicios_factura_buscar = $("#dataTableServicios").DataTable({		
		"destroy":true,	
		"ajax":{
			"method":"POST",
			"url":"<?php echo SERVERURL; ?>php/facturacion/getServiciosTabla.php"
		},
		"columns":[
			{"defaultContent":"<button class='view btn btn-primary'><span class='fas fa-copy'></span></button>"},
			{"data":"nombre"},		
		],
		"pageLength" : 5,
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,	
	});	 
	table_servicios_factura_buscar.search('').draw();
	$('#buscar').focus();
	
	view_servicios_busqueda_dataTable("#dataTableServicios tbody", table_servicios_factura_buscar);
}

var view_servicios_busqueda_dataTable = function(tbody, table){
	$(tbody).off("click", "button.view");		
	$(tbody).on("click", "button.view", function(e){
		e.preventDefault();
		var data = table.row( $(this).parents("tr") ).data();		  
		$('#formulario_facturacion #servicio_id').val(data.servicio_id);
		$('#modal_busqueda_servicios').modal('hide');
	});
}

var listar_productos_facturas_buscar = function(){
	var table_productos_buscar = $("#dataTableProductosFacturas").DataTable({		
		"destroy":true,	
		"ajax":{
			"method":"POST",
			"url":"<?php echo SERVERURL; ?>php/facturacion/getProductosFacturaTabla.php"
		},
		"columns":[
			{"defaultContent":"<button class='editar btn btn-primary'><span class='fas fa-copy'></span></button>"},
			{"data":"producto"},
			{"data":"descripcion"},
			{"data":"concentracion"},	
			{"data":"medida"},			
			{"data":"cantidad"},
			{"data":"precio_venta"}	,
			{"data":"precio_venta2"},
			{"data":"precio_venta3"},
			{"data":"precio_venta4"}			
		],
		"pageLength" : 5,
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,	
	});	 
	table_productos_buscar.search('').draw();
	$('#buscar').focus();
	
	editar_productos_busqueda_dataTable("#dataTableProductosFacturas tbody", table_productos_buscar);
}

var editar_productos_busqueda_dataTable = function(tbody, table){
	$(tbody).off("click", "button.editar");		
	$(tbody).on("click", "button.editar", function(e){
		e.preventDefault();
		if($("#formulario_facturacion #cliente_nombre").val() != ""){	
			var isv = 0;
			var isv_total = 0;
			var porcentaje_isv = 0;
			var porcentaje_calculo = 0;
			var isv_neto = 0;		
			var data = table.row( $(this).parents("tr") ).data();
			var row = $('#formulario_busqueda_productos_facturas #row').val();
		    var hospitales_id = getHospitalClinicaConsulta($("#formulario_facturacion #muestras_id").val());
			var consultaPrecio = getPrecioHospitalConsulta(hospitales_id);			
			
			if (data.categoria == "Servicio"){
				$('#formulario_facturacion #invoiceItem #productName_'+ row).val(data.producto);
			}else{
				$('#formulario_facturacion #invoiceItem #productName_'+ row).val(data.producto + ' ' + data.concentracion + ' ' + data.medida);
			}

			$('#formulario_facturacion #invoiceItem #productoID_'+ row).val(data.productos_id);
			
			if(consultaPrecio == "Precio1"){
				$('#formulario_facturacion #invoiceItem #price_'+ row).val(data.precio_venta);	
			}else if(consultaPrecio == "Precio2"){
				$('#formulario_facturacion #invoiceItem #price_'+ row).val(data.precio_venta2);					
			}else if(consultaPrecio == "Precio3"){
				$('#formulario_facturacion #invoiceItem #price_'+ row).val(data.precio_venta3);					
			}else if(consultaPrecio == "Precio4"){
				$('#formulario_facturacion #invoiceItem #price_'+ row).val(data.precio_venta4);				
			}else{
				$('#formulario_facturacion #invoiceItem #price_'+ row).val(data.precio_venta);				
			}

			$('#formulario_facturacion #invoiceItem #isv_'+ row).val(data.impuesto_venta);
			$('#formulario_facturacion #invoiceItem #discount_'+ row).val(0);
			$('#formulario_facturacion #invoiceItem #quantity_'+ row).val(1);								
			$('#formulario_facturacion #invoiceItem #quantity_'+ row).focus();
		
			if(data.impuesto_venta == 1){
				porcentaje_isv = parseFloat(getPorcentajeISV() / 100);
				if($('#formulario_facturacion #taxAmount').val() == "" || $('#formulario_facturacion #taxAmount').val() == 0){
					porcentaje_calculo = (parseFloat(data.precio_venta) * porcentaje_isv).toFixed(2);			
					isv_neto = porcentaje_calculo;
					$('#formulario_facturacion #taxAmount').val(porcentaje_calculo);
					$('#formulario_facturacion #invoiceItem #valor_isv_'+ row).val(porcentaje_calculo);
				}else{				
					isv_total = parseFloat($('#formulario_facturacion #taxAmount').val());
					porcentaje_calculo = (parseFloat(data.precio_venta) * porcentaje_isv).toFixed(2);
					isv_neto = parseFloat(isv_total) + parseFloat(porcentaje_calculo);
					$('#formulario_facturacion #taxAmount').val(isv_neto);	
					$('#formulario_facturacion #invoiceItem #valor_isv_'+ row).val(porcentaje_calculo);
				}
			}
			
			calculateTotal();
			addRow();
			$('#modal_busqueda_productos_facturas').modal('hide');
		}else{
			swal({
				title: "Error", 
				text: "Lo sentimos no se puede seleccionar un producto, por favor seleccione un cliente antes de poder continuar",
				type: "error", 
				confirmButtonClass: "btn-danger"
			});				
		}
	});
}
//FIN FUNCIONES PARA LLENAR DATOS EN LA TABLA

function getServicio(){
    var url = '<?php echo SERVERURL; ?>php/agenda_pacientes/servicios.php';		
		
	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){	
		    $('#formulario_facturacion #servicio_id').html("");
			$('#formulario_facturacion #servicio_id').html(data);

		    $('#formGrupoFacturacion #servicio_idGrupo').html("");
			$('#formGrupoFacturacion #servicio_idGrupo').html(data);			
		}			
     });	
}

$(document).ready(function(){
    $("#modal_busqueda_pacientes").on('shown.bs.modal', function(){
        $(this).find('#formulario_busqueda_pacientes #buscar').focus();
    });
});

$(document).ready(function(){
    $("#modal_busqueda_colaboradores").on('shown.bs.modal', function(){
        $(this).find('#formulario_busqueda_coloboradores #buscar').focus();
    });
});

$(document).ready(function(){
    $("#modal_busqueda_productos_facturas").on('shown.bs.modal', function(){
        $(this).find('#formulario_busqueda_productos_facturas #buscar').focus();
    });
});

$(document).ready(function(){
    $("#modal_busqueda_servicios").on('shown.bs.modal', function(){
        $(this).find('#formulario_busqueda_servicios #buscar').focus();
    });
});

/*INICIO AUTO COMPLETAR*/
/*INICIO SUGGESTION PRODUCTO*/
$("#formulario_facturacion #invoiceItem").on('click', '.producto', function() {
	var row = $(this).closest("tr").index();
	var col = $(this).closest("td").index();
	
    $('#formulario_facturacion #productName_'+ row).on('keyup', function() {
	   if($("#formulario_facturacion #cliente_nombre").val() != ""){		
		   if($('#formulario_facturacion #invoiceItem #productName_'+ row).val() != ""){
				 var key = $(this).val();		
				 var dataString = 'key='+key;
				 var url = '<?php echo SERVERURL; ?>php/productos/autocompletarProductos.php';
		
				$.ajax({
				   type: "POST",
				   url: url,
				   data: dataString,
				   success: function(data) {
					  //Escribimos las sugerencias que nos manda la consulta
					  $('#formulario_facturacion #invoiceItem #suggestions_producto_'+ row).fadeIn(1000).html(data);
					  //Al hacer click en algua de las sugerencias
					  $('.suggest-element').on('click', function(){
							//Obtenemos la id unica de la sugerencia pulsada
							var producto_id = $(this).attr('id');					
							
							//Editamos el valor del input con data de la sugerencia pulsada							
							$('#formulario_facturacion #invoiceItem #productName_'+ row).val($('#'+producto_id).attr('data'));
							$('#formulario_facturacion #invoiceItem #quantity_'+ row).val(1);
							$('#formulario_facturacion #invoiceItem #quantity_'+ row).focus();
							//Hacemos desaparecer el resto de sugerencias
							$('#formulario_facturacion #invoiceItem #suggestions_producto_'+ row).fadeOut(1000);
							addRow();	

							//OBTENEMOS DATOS DEL PRODUCTO
							var url = '<?php echo SERVERURL; ?>php/productos/editarProductos.php';		
								
							$.ajax({
								type: "POST",
								url: url,
								data: "productos_id=" + producto_id,
								async: true,
								success: function(data){
									var datos = eval(data);
									$('#formulario_facturacion #invoiceItem #productoID_'+ row).val(producto_id);
									$('#formulario_facturacion #invoiceItem #price_'+ row).val(datos[7]);
									
									var isv = 0;
									var isv_total = 0;
									var porcentaje_isv = 0;
									var porcentaje_calculo = 0;
									var isv_neto = 0;
								
									if(getISVEstadoProductos(producto_id) == 1){
										porcentaje_isv = parseFloat(getPorcentajeISV() / 100);
										
										if($('#formulario_facturacion #taxAmount').val() == 0){
											porcentaje_calculo = (parseFloat(datos[7]) * porcentaje_isv).toFixed(2);
											$('#formulario_facturacion #taxAmount').val(porcentaje_calculo);							
										}else{				
											isv_total = parseFloat($('#formulario_facturacion #taxAmount').val());
											porcentaje_calculo = (parseFloat(datos[7]) * porcentaje_isv).toFixed(2);	
											isv_neto = parseFloat(isv_total) + parseFloat(porcentaje_calculo);
											$('#formulario_facturacion #taxAmount').val(isv_neto);					
										}
									}	
									
									calculateTotal();
								}			
							 });	
													
							return false;
					 });
				  }
			   });   
		   }else{
			   $('#formulario_facturacion #invoiceItem #suggestions_producto_'+ row).fadeIn(1000).html("");
			   $('#formulario_facturacion #invoiceItem #suggestions_producto_'+ row).fadeOut(1000);
		   }
	   }else{
			swal({
				title: "Error", 
				text: "Lo sentimos no se puede efectuar la búsqueda, por favor seleccione un cliente antes de poder continuar",
				type: "error", 
				confirmButtonClass: "btn-danger"
			});		   
	   }
	 });		

	//OCULTAR EL SUGGESTION
    $('#formulario_facturacion #invoiceItem #productName_'+ row).on('blur', function() {
	   $('#formulario_facturacion #invoiceItem #suggestions_producto_'+ row).fadeOut(1000);
    });		

    $('#formulario_facturacion #invoiceItem #productName_'+ row).on('click', function() {
	   if($("#formulario_facturacion #cliente_nombre").val() != ""){
		   if($('#formulario_facturacion #invoiceItem #productName_1').val() != ""){
				 var key = $(this).val();		
				 var dataString = 'key='+key;
				 var url = '<?php echo SERVERURL; ?>php/productos/autocompletarProductos.php';
		
				$.ajax({
				   type: "POST",
				   url: url,
				   data: dataString,
				   success: function(data) {
					  //Escribimos las sugerencias que nos manda la consulta
					  $('#formulario_facturacion #invoiceItem #suggestions_producto_'+ row).fadeIn(1000).html(data);
					  //Al hacer click en algua de las sugerencias
					  $('.suggest-element').on('click', function(){
							//Obtenemos la id unica de la sugerencia pulsada
							var producto_id = $(this).attr('id');
							 
							//Editamos el valor del input con data de la sugerencia pulsada
							$('#formulario_facturacion #invoiceItem #productName_'+ row).val($('#'+producto_id).attr('data'));
							$('#formulario_facturacion #invoiceItem #quantity_'+ row).val(1);
							$('#formulario_facturacion #invoiceItem #quantity_'+ row).focus();
							//Hacemos desaparecer el resto de sugerencias
							$('#formulario_facturacion #invoiceItem #suggestions_producto_'+ row).fadeOut(1000);
							addRow();

							//OBTENEMOS DATOS DEL PRODUCTO
							var url = '<?php echo SERVERURL; ?>php/productos/editarProductos.php';		
								
							$.ajax({
								type: "POST",
								url: url,
								data: "productos_id=" + producto_id,
								async: true,
								success: function(data){
									var datos = eval(data);
									$('#formulario_facturacion #invoiceItem #productoID_'+ row).val(producto_id);									
									$('#formulario_facturacion #invoiceItem #price_'+ row).val(datos[7]);
										
									var isv = 0;
									var isv_total = 0;
									var porcentaje_isv = 0;
									var porcentaje_calculo = 0;
									var isv_neto = 0;
								
									if(getISVEstadoProductos(producto_id) == 1){
										porcentaje_isv = parseFloat(getPorcentajeISV() / 100);
										
										if($('#formulario_facturacion #taxAmount').val() == 0){
											porcentaje_calculo = (parseFloat(datos[7]) * porcentaje_isv).toFixed(2);
											$('#formulario_facturacion #taxAmount').val(porcentaje_calculo);							
										}else{				
											isv_total = parseFloat($('#formulario_facturacion #taxAmount').val());
											porcentaje_calculo = (parseFloat(datos[7]) * porcentaje_isv).toFixed(2);	
											isv_neto = parseFloat(isv_total) + parseFloat(porcentaje_calculo);
											$('#formulario_facturacion #taxAmount').val(isv_neto);					
										}
									}	
									
									calculateTotal();
								}			
							 });
													
							return false;
					 });
				  }
			   });   
		   }else{
			   $('#formulario_facturacion #invoiceItem #suggestions_producto_'+ row).fadeIn(1000).html("");
			   $('#formulario_facturacion #invoiceItem #suggestions_producto_'+ row).fadeOut(1000);
		   }
	   }else{
			swal({
				title: "Error", 
				text: "Lo sentimos no se puede efectuar la búsqueda, por favor seleccione un cliente antes de poder continuar",
				type: "error", 
				confirmButtonClass: "btn-danger"
			});		   
	   }
	});		
});
/*FIN SUGGESTION PRODUCTO*/
/*FIN AUTO COMPLETAR*/

//INICIO BOTOES RECETA MEDICA
$('#formulario_facturacion #bt_add').on('click', function(e){
	e.preventDefault();
});

$('#formulario_facturacion #bt_del').on('click', function(e){
	e.preventDefault();
});
//FIN BOTONES RECETA MEDICA
/*														 	FIN FACTURACIÓN				   															 	*/
/*
###########################################################################################################################################################
###########################################################################################################################################################
###########################################################################################################################################################
*/

//REFRESCAR LA SESION CADA CIERTO TIEMPO PARA QUE NO EXPIRE
document.addEventListener("DOMContentLoaded", function(){
    // Invocamos cada 5 segundos ;)
    const milisegundos = 5 *1000;
    setInterval(function(){
        // No esperamos la respuesta de la petición porque no nos importa
        fetch("<?php echo SERVERURL; ?>php/signin_out/refrescar.php");
    },milisegundos);
});

function getPorcentajeISV(){
    var url = '<?php echo SERVERURL; ?>php/productos/getIsv.php';
	var isv;
	$.ajax({
	    type:'POST',
		url:url,
		async: false,
		success:function(data){	
		  var datos = eval(data);
          isv = datos[0];			  		  		  			  
		}
	});
	return isv;	
}

function getISVEstadoProductos(productos_id){
    var url = '<?php echo SERVERURL; ?>php/productos/getIsvEstado.php';
	var isv_estado;
	$.ajax({
	    type:'POST',
		url:url,
		data:'productos_id='+productos_id,
		async: false,
		success:function(data){	
		  var datos = eval(data);
          isv_estado = datos[0];			  		  		  			  
		}
	});
	return isv_estado;	
}

$('#formulario_facturacion #notes').keyup(function() {
	    var max_chars = 250;
        var chars = $(this).val().length;
        var diff = max_chars - chars;
		
		$('#formulario_facturacion #charNum_notas').html(diff + ' Caracteres'); 
		
		if(diff == 0){
			return false;
		}
});

function caracteresAntecedentes(){
	var max_chars = 250;
	var chars = $('#formulario_facturacion #notes').val().length;
	var diff = max_chars - chars;
	
	$('#formulario_facturacion #charNum_notas').html(diff + ' Caracteres'); 
	
	if(diff == 0){
		return false;
	}
}

function getPrecioHospital(hospitales_id){
    var url = '<?php echo SERVERURL; ?>php/administrador_precios/getAdministradorPrecios.php';
	var precio_administrador;
	$.ajax({
	    type:'POST',
		url:url,
		data:'hospitales_id='+hospitales_id,
		async: false,
		success:function(data){	
		  var datos = eval(data);
          precio_administrador = datos[0];			  		  		  			  
		}
	});
	return precio_administrador;		
}

function showFactura(muestras_id){
	var url = '<?php echo SERVERURL; ?>php/muestras/editarFactura.php';

	$('#main_facturacion').hide();	
	$('#facturacion').show();
	
	$('#formulario_facturacion')[0].reset();	
	
	$.ajax({
	    type:'POST',
		url:url,
		data:'muestras_id='+muestras_id,
		success:function(data){	
		    var datos = eval(data);
	        $('#formulario_facturacion #pro').val("Registro");
			$('#formulario_facturacion #muestras_id').val(muestras_id);
			$('#formulario_facturacion #pacientes_id').val(datos[0]);
            $('#formulario_facturacion #cliente_nombre').val(datos[1]);
            $('#formulario_facturacion #fecha').val(datos[2]);
            $('#formulario_facturacion #colaborador_id').val(datos[3]);
			$('#formulario_facturacion #colaborador_nombre').val(datos[4]);
			$('#formulario_facturacion #servicio_id').val(datos[5]);		
			$('#label_acciones_volver').html("ATA");
			$('#label_acciones_receta').html("Receta");
			
			$('#formulario_facturacion #fecha').attr("readonly", true);
			$('#formulario_facturacion #validar').attr("disabled", false);
			$('#formulario_facturacion #addRows').attr("disabled", false);
			$('#formulario_facturacion #removeRows').attr("disabled", false);
		    $('#formulario_facturacion #validar').show();
		    $('#formulario_facturacion #editar').hide();
		    $('#formulario_facturacion #eliminar').hide();
			limpiarTabla();				
			
			$('#formulario_facturacion').attr({ 'data-form': 'save' }); 
			$('#formulario_facturacion').attr({ 'action': '<?php echo SERVERURL; ?>php/atencion_pacientes/addFactura.php' }); 					
		}
	});
}

function getHospitalClinicaConsulta(muestras_id){
    var url = '<?php echo SERVERURL; ?>php/muestras/getHospitalClinicaCodigo.php';
	var hospitales_id;
	$.ajax({
	    type:'POST',
		url:url,
		data:'muestras_id='+muestras_id,
		async: false,
		success:function(data){	
			var valores = eval(data);
			hospitales_id = valores[0];			  		  		  			  
		}
	});
	return hospitales_id;	
}

function getPrecioHospitalConsulta(hospitales_id){
    var url = '<?php echo SERVERURL; ?>php/muestras/getPrecioHospital.php';
	var precio;
	$.ajax({
	    type:'POST',
		url:url,
		data:'hospitales_id='+hospitales_id,
		async: false,
		success:function(data){	
			var valores = eval(data);
			precio = valores[0];			  		  		  			  
		}
	});
	return precio;		
}

//INICIO FORMULARIO PACIENTES
function cleanPacientes(){
	$("#formulario_pacientes #correo").css("border-color", "none");	
}

function getDepartamento(){
    var url = '<?php echo SERVERURL; ?>php/pacientes/getDepartamento.php';		
		
	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){	
		    $('#formulario_pacientes #departamento').html("");
			$('#formulario_pacientes #departamento').html(data);
		}			
     });		
}

$(document).ready(function() {
	$('#formulario_pacientes #departamento').on('change', function(){
      getMunicipio();
	  return false;			 				
    });					
});

function getMunicipio(){
	var url = '<?php echo SERVERURL; ?>php/pacientes/getMunicipio.php';
		
	var departamento_id = $('#formulario_pacientes #departamento').val();
	
	$.ajax({
	   type:'POST',
	   url:url,
	   data:'departamento_id='+departamento_id,
	   success:function(data){
		  $('#formulario_pacientes #municipio').html("");
		  $('#formulario_pacientes #municipio').html(data);  
	  }
  });	
}

function getMunicipioEditar(departamento_id, municipio_id){
	var url = '<?php echo SERVERURL; ?>php/pacientes/getMunicipio.php';
		
	$.ajax({
	   type:'POST',
	   url:url,
	   data:'departamento_id='+departamento_id,
	   success:function(data){
	      $('#formulario_pacientes #municipio').html("");
		  $('#formulario_pacientes #municipio').html(data);
		  $('#formulario_pacientes #municipio').val(municipio_id);		  
	  }
	});
	return false;		
}

function getReligion(){
    var url = '<?php echo SERVERURL; ?>php/pacientes/getReligion.php';		
		
	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){	
		    $('#formulario_pacientes #religion').html("");
			$('#formulario_pacientes #religion').html(data);
		}			
     });		
}

function getProfesion(){
    var url = '<?php echo SERVERURL; ?>php/pacientes/getProfesion.php';		
		
	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){	
		    $('#formulario_pacientes #profesion').html("");
			$('#formulario_pacientes #profesion').html(data);
		}			
     });		
}

function getSexo(){
    var url = '<?php echo SERVERURL; ?>php/pacientes/getSexo.php';		
		
	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){	
		    $('#formulario_pacientes #sexo').html("");
			$('#formulario_pacientes #sexo').html(data);

		    $('#formulario_agregar_expediente_manual #sexo_manual').html("");
			$('#formulario_agregar_expediente_manual #sexo_manual').html(data);		
		}			
     });		
}

function getTipoPacienteEstado(){
    var url = '<?php echo SERVERURL; ?>php/pacientes/getTipoPaciente.php';		
		
	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){
		    $('#formulario_pacientes #paciente_tipo').html("");
			$('#formulario_pacientes #paciente_tipo').html(data);
			
		    $('#form_main #tipo_paciente_id').html("");
			$('#form_main #tipo_paciente_id').html(data);			
		}			
     });		
}

function getStatus(){
    var url = '<?php echo SERVERURL; ?>php/pacientes/getStatus.php';		
		
	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){	
		    $('#form_main #estado').html("");
			$('#form_main #estado').html(data);
		}			
     });		
}

$('#formulario_pacientes #buscar_religion_pacientes').on('click', function(e){
	listar_religion_buscar();
	 $('#modal_busqueda_religion').modal({
		show:true,
		keyboard: false,
		backdrop:'static'
	});	 
});

$('#formulario_pacientes #buscar_profesion_pacientes').on('click', function(e){
	listar_profesion_buscar();
	 $('#modal_busqueda_profesion').modal({
		show:true,
		keyboard: false,
		backdrop:'static'
	});	 
});

$('#formulario_pacientes #buscar_departamento_pacientes').on('click', function(e){
	listar_departamentos_buscar(); 
	$('#modal_busqueda_departamentos').modal({
		show:true,
		keyboard: false,
		backdrop:'static'
	});			
});

$('#formulario_pacientes #buscar_municipio_pacientes').on('click', function(e){
	if($('#formulario_pacientes #departamento').val() == "" || $('#formulario_pacientes #departamento').val() == null){
		swal({
			title: "Error", 
			text: "Lo sentimos el departamento no debe estar vacío, antes de seleccionar esta opción por favor seleccione un departamento, por favor corregir",
			type: "error", 
			confirmButtonClass: 'btn-danger'
		});			
	}else{
		listar_municipios_buscar();
		 $('#modal_busqueda_municipios').modal({
			show:true,
			keyboard: false,
			backdrop:'static'
		});		
	}	
});

var listar_religion_buscar = function(){
	var table_religion_buscar = $("#dataTableReligion").DataTable({		
		"destroy":true,	
		"ajax":{
			"method":"POST",
			"url":"<?php echo SERVERURL; ?>php/pacientes/getReligionTable.php"
		},
		"columns":[
			{"defaultContent":"<button class='view btn btn-primary'><span class='fas fa-copy'></span></button>"},
			{"data":"nombre"}		
		],
		"pageLength" : 5,
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,	
	});	 
	table_religion_buscar.search('').draw();
	$('#buscar').focus();
	
	view_religion_busqueda_dataTable("#dataTableReligion tbody", table_religion_buscar);
}

var view_religion_busqueda_dataTable = function(tbody, table){
	$(tbody).off("click", "button.view");		
	$(tbody).on("click", "button.view", function(e){
		e.preventDefault();
		var data = table.row( $(this).parents("tr") ).data();		  
		$('#formulario_pacientes #religion').val(data.religion_id);
		$('#modal_busqueda_religion').modal('hide');
	});
}

var listar_profesion_buscar = function(){
	var table_profeision_buscar = $("#dataTableProfesiones").DataTable({		
		"destroy":true,	
		"ajax":{
			"method":"POST",
			"url":"<?php echo SERVERURL; ?>php/pacientes/getProfesionTable.php"
		},
		"columns":[
			{"defaultContent":"<button class='view btn btn-primary'><span class='fas fa-copy'></span></button>"},
			{"data":"nombre"}		
		],
		"pageLength" : 5,
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,	
	});	 
	table_profeision_buscar.search('').draw();
	$('#buscar').focus();
	
	view_profesion_busqueda_dataTable("#dataTableProfesiones tbody", table_profeision_buscar);
}

var view_profesion_busqueda_dataTable = function(tbody, table){
	$(tbody).off("click", "button.view");		
	$(tbody).on("click", "button.view", function(e){
		e.preventDefault();
		var data = table.row( $(this).parents("tr") ).data();		  
		$('#formulario_pacientes #profesion').val(data.profesion_id);
		$('#modal_busqueda_profesion').modal('hide');
	});
}

var listar_departamentos_buscar = function(){
	var table_departamentos_buscar = $("#dataTableDepartamentos").DataTable({		
		"destroy":true,	
		"ajax":{
			"method":"POST",
			"url":"<?php echo SERVERURL; ?>php/pacientes/getDepartamentosTabla.php"
		},
		"columns":[
			{"defaultContent":"<button class='view btn btn-primary'><span class='fas fa-copy'></span></button>"},
			{"data":"nombre"}		
		],
		"pageLength" : 5,
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,	
	});	 
	table_departamentos_buscar.search('').draw();
	$('#buscar').focus();
	
	view_departamentos_busqueda_dataTable("#dataTableDepartamentos tbody", table_departamentos_buscar);
}

var view_departamentos_busqueda_dataTable = function(tbody, table){
	$(tbody).off("click", "button.view");		
	$(tbody).on("click", "button.view", function(e){
		e.preventDefault();
		var data = table.row( $(this).parents("tr") ).data();		  
		$('#formulario_pacientes #departamento').val(data.departamento_id);
		getMunicipio();
		$('#modal_busqueda_departamentos').modal('hide');
	});
}

var listar_municipios_buscar = function(){
	var departamento = $('#formulario_pacientes #departamento').val();
	var table_municipios_buscar = $("#dataTableMunicipios").DataTable({
		"destroy":true,	
		"ajax":{
			"method":"POST",
			"url":"<?php echo SERVERURL; ?>php/pacientes/getMunicipiosTabla.php",
			"data":{ 'departamento' : departamento },
		},
		"columns":[
			{"defaultContent":"<button class='view btn btn-primary'><span class='fas fa-copy'></span></button>"},
			{"data":"municipio"},
			{"data":"departamento"}			
		],
		"pageLength" : 5,
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,	
	});	 
	table_municipios_buscar.search('').draw();
	$('#buscar').focus();
	
	view_municipios_busqueda_dataTable("#dataTableMunicipios tbody", table_municipios_buscar);
}

var view_municipios_busqueda_dataTable = function(tbody, table){
	$(tbody).off("click", "button.view");		
	$(tbody).on("click", "button.view", function(e){
		e.preventDefault();
		var data = table.row( $(this).parents("tr") ).data();		  
		$('#formulario_pacientes #municipio').val(data.municipio_id);
		$('#modal_busqueda_municipios').modal('hide');
	});
}

$('#form_main #limpiar').on('click', function(e){
    e.preventDefault();
	$('#form_main #bs_regis').val("");
	$('#form_main #bs_regis').focus();
	getSexo();
	pagination(1);
	getStatus();
	getTipoPacienteEstado();
	getDepartamento();
	getReligion();
	getProfesion();	
	listar_departamentos_buscar();
	listar_profesion_buscar();
	listar_religion_buscar();
});

$(document).ready(function(){
	getSexo();
	pagination(1);
	getStatus();
	getTipoPacienteEstado();
	getDepartamento();
	getReligion();
	getProfesion();		
});
//FIN FORMULARIO PACIENTES

//INICIO FORMULARIO COLABORADORES
function puesto(){
	var url = '<?php echo SERVERURL; ?>php/selects/puestos.php';
	
	$.ajax({
		type:'POST',
		url:url,			
		success: function(data){
			$('#formulario_colaboradores #puesto').html(data);			
		}
	});
	return false;	
}

function empresa(){
	var url = '<?php echo SERVERURL; ?>php/selects/empresa.php';
	
	$.ajax({
		type:'POST',
		url:url,		
		success: function(data){
			$('#formulario_colaboradores #empresa').html("");
			$('#formulario_colaboradores #empresa').html(data);			
		}
	});
	return false;
}

function getEstatus(){
    var url = '<?php echo SERVERURL; ?>php/users/getStatus.php';		
		
	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){		
		    $('#main_form #status').html("");
			$('#main_form #status').html(data);

		    $('#formulario_colaboradores #estatus').html("");
			$('#formulario_colaboradores #estatus').html(data);			
		}			
     });		
}
//FIN FORMULARIO COLABORADORES

$(document).ready(function(){
	getClientes();
	getEmpresas();
	getTotalMuestras()
	getTotalAtenciones();
	getPendientesAtencion();
	getTotalPendienteMuestras();
	getPendientesFacturas();
	getTotalProductos();
	
	setInterval('getClientes()',2000);
	setInterval('getEmpresas()',2000);
	setInterval('getTotalMuestras()',2000);
	setInterval('getTotalAtenciones()',2000);
	setInterval('getPendientesAtencion()',2000);
	setInterval('getTotalPendienteMuestras()',2000);
	setInterval('getPendientesFacturas()',2000);
	setInterval('getTotalProductos()',2000);
});

//DATOS MAIN
function getClientes(){
    var url = '<?php echo SERVERURL; ?>php/main/getClientes.php';
	$.ajax({
	    type:'POST',
		url:url,
		success: function(data){
           	$('#main_clientes').html(data);  		  		  			  
		}
	});	
}

function getEmpresas(){
    var url = '<?php echo SERVERURL; ?>php/main/getEmpresas.php';
	$.ajax({
	    type:'POST',
		url:url,
		success: function(data){
           	$('#main_empresas').html(data);  		  		  			  
		}
	});	
}

function getTotalMuestras(){
    var url = '<?php echo SERVERURL; ?>php/main/getTotalMuestras.php';
	$.ajax({
	    type:'POST',
		url:url,
		success: function(data){
           	$('#main_muestras').html(data);  		  		  			  
		}
	});	
}

function getTotalAtenciones(){
    var url = '<?php echo SERVERURL; ?>php/main/getTotalAtenciones.php';
	$.ajax({
	    type:'POST',
		url:url,
		success: function(data){
           	$('#main_atenciones').html(data);  		  		  			  
		}
	});	
}

function getPendientesAtencion(){
    var url = '<?php echo SERVERURL; ?>php/main/pendienteAtenciones.php';
	$.ajax({
	    type:'POST',
		url:url,
		success: function(data){
           	$('#main_prendiente_atenciones').html(data);  		  		  			  
		}
	});	
}

function getTotalPendienteMuestras(){
    var url = '<?php echo SERVERURL; ?>php/main/getTotalPendienteMuestras.php';
	$.ajax({
	    type:'POST',
		url:url,
		success: function(data){
           	$('#main_pendiente_muestras').html(data);  		  		  			  
		}
	});	
}

function getPendientesFacturas(){
    var url = '<?php echo SERVERURL; ?>php/main/facturasPendientes.php';
	$.ajax({
	    type:'POST',
		url:url,
		success: function(data){
           	$('#main_facturas_pendientes').html(data);  		  		  			  
		}
	});	
}

function getTotalProductos(){
    var url = '<?php echo SERVERURL; ?>php/main/totalProductos.php';
	$.ajax({
	    type:'POST',
		url:url,
		success: function(data){
           	$('#main_productos').html(data);  		  		  			  
		}
	});	
}

function pagination(partida){
	
}
</script>