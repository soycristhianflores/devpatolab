<script>
$(document).ready(function() {
   getServicio();
   getReporte();
   $('#form_main #colaborador_usuario').html("");
   getColaborador_usuario();
   pagination_preclinica(1);
});

$(document).ready(function() {
  $('#form_main #servicio').on('change', function(){	
     $('#form_main #colaborador_usuario').html("");
	 getColaborador_usuario();
     pagination_preclinica(1);
  });
});

$(document).ready(function() {
  $('#form_main #reporte').on('change', function(){	
     $('#form_main #colaborador_usuario').html("");
	 getColaborador_usuario();
     pagination_preclinica(1);
  });
});

$(document).ready(function() {
  $('#form_main #fecha_i').on('change', function(){	
     pagination_preclinica(1);
  });
});

$(document).ready(function() {
  $('#form_main #fecha_f').on('change', function(){	
     pagination_preclinica(1);
  });
});

$(document).ready(function() {
  $('#form_main #bs-regis').on('keyup', function(){	
     pagination_preclinica(1);
  });
});

$(document).ready(function() {
  $('#form_main #colaborador_usuario').on('change', function(){	
     pagination_preclinica(1);
  });
});

function pagination_preclinica(partida){
	var servicio = $('#form_main #servicio').val();
	var desde = $('#form_main #fecha_i').val();
	var hasta = $('#form_main #fecha_f').val();
	var dato = $('#form_main #bs-regis').val();
	var colaborador_usuario = "";

	if($('#form_main #colaborador_usuario').val() == "" || $('#form_main #colaborador_usuario').val() == null){
		colaborador_usuario = "";
	}else{
		colaborador_usuario = $('#form_main #colaborador_usuario').val();
	}	

	if(servicio == "" || servicio == null){
		servicio = 1;
	}else{
		servicio = $('#form_main #servicio').val();
	}
	
	var url = '<?php echo SERVERURL; ?>php/reportes_enfermeria/paginar_preclinica.php';		
	var url = '<?php echo SERVERURL; ?>php/reportes_enfermeria/paginar_preclinica.php';		

	$.ajax({
		type:'POST',
		url:url,
		data:'partida='+partida+'&desde='+desde+'&hasta='+hasta+'&servicio='+servicio+'&dato='+dato+'&colaborador_usuario='+colaborador_usuario,
		success:function(data){
			var array = eval(data);
			$('#agrega-registros').html(array[0]);
			$('#pagination').html(array[1]);			
		}
	});
	return false;	
}

function getServicio(){
    var url = '<?php echo SERVERURL; ?>php/reportes_enfermeria/servicios.php';		
		
	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){	
		    $('#form_main #servicio').html("");
			$('#form_main #servicio').html(data);
		}			
     });	
}

function getReporte(){
    var url = '<?php echo SERVERURL; ?>php/reportes_enfermeria/getReporte.php';		
		
	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){
		    $('#form_main #reporte').html("");
			$('#form_main #reporte').html(data);				
        }
     });		
}

function reporteEXCEL(){
  if($('#form_main #servicio').val()!=""){		
	var servicio = $('#form_main #servicio').val();
	var desde = $('#form_main #fecha_i').val();
	var hasta = $('#form_main #fecha_f').val();
	var colaborador_usuario = $('#form_main #colaborador_usuario').val();

	if(colaborador_usuario == "" || colaborador_usuario == null){
		colaborador_usuario = "";
	}else{
		colaborador_usuario = $('#form_main #colaborador_usuario').val();
	}	
		
	url = '<?php echo SERVERURL; ?>php/reportes_enfermeria/reportePreclinica.php?desde='+desde+'&hasta='+hasta+'&servicio='+servicio+'&colaborador_usuario='+colaborador_usuario;
	    
	window.open(url);	
}else{
	swal({
		title: "Error", 
		text: "Debe seleccionar por lo menos una opción de búsqueda",
		type: "error", 
		confirmButtonClass: 'btn-danger'
	});		  
  }		
}

function reporteEXCELDiario(){
	if($('#form_main #servicio').val()!=""){		
		var servicio = $('#form_main #servicio').val();
		var desde = $('#form_main #fecha_i').val();
		var hasta = $('#form_main #fecha_f').val();
		var colaborador_usuario = $('#form_main #colaborador_usuario').val();

		if(colaborador_usuario == "" || colaborador_usuario == null){
			colaborador_usuario = "";
		}else{
			colaborador_usuario = $('#form_main #colaborador_usuario').val();
		}	

		var url = '<?php echo SERVERURL; ?>php/reportes_enfermeria/reporteDiarioUsuarios.php?desde='+desde+'&hasta='+hasta+'&servicio='+servicio+'&colaborador_usuario='+colaborador_usuario;
		window.open(url);	
	}else{
		swal({
			title: "Error", 
			text: "Debe seleccionar por lo menos una opción de búsqueda",
			type: "error", 
			confirmButtonClass: 'btn-danger'
		});	  
	}		
}


function limpiar(){
	$('#servicio').html("");
    $('#agrega-registros').html("");
	$('#pagination').html("");		
    getServicio();
	getReporte();
    $('#form_main #colaborador_usuario').html("");
	getColaborador_usuario();
	pagination_preclinica(1);
}

function modal_eliminarPreclinica(preclinica_id, pacientes_id){
   if (getUsuarioSistema() == 1 || getUsuarioSistema() == 3 || getUsuarioSistema() == 4 || getUsuarioSistema() == 5 || getUsuarioSistema() == 8 || getUsuarioSistema() == 9){	
	    var nombre_usuario = consultarNombre(pacientes_id);
        var expediente_usuario = consultarExpediente(pacientes_id);
        var dato;

        if(expediente_usuario == 0){
           dato = nombre_usuario;
        }else{
	        dato = nombre_usuario + " (Expediente: " + expediente_usuario + ")";
        }

		swal({
		  title: "¿Esta seguro?",
		  text: "¿Desea eliminar la preclínica de este usuario: " + dato + "?",
		  type: "input",
		  showCancelButton: true,
		  closeOnConfirm: false,
		  inputPlaceholder: "Comentario",
		  cancelButtonText: "Cancelar",	
		  confirmButtonText: "¡Sí, remover el usuario!",
		  confirmButtonClass: "btn-warning"
		}, function (inputValue) {
		  if (inputValue === false) return false;
		  if (inputValue === "") {
			swal.showInputError("¡Necesita escribir algo!");
			return false
		  }
			eliminarPreclinica(preclinica_id, inputValue);
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

function modal_eliminarPostClinica(postclinica_id, pacientes_id){
   if (getUsuarioSistema() == 1 || getUsuarioSistema() == 3 || getUsuarioSistema() == 4 || getUsuarioSistema() == 5 || getUsuarioSistema() == 8 || getUsuarioSistema() == 9){	
	    var nombre_usuario = consultarNombre(pacientes_id);
        var expediente_usuario = consultarExpediente(pacientes_id);
        var dato;

        if(expediente_usuario == 0){
           dato = nombre_usuario;
        }else{
	        dato = nombre_usuario + " (Expediente: " + expediente_usuario + ")";
        }
		;
		swal({
		  title: "¿Esta seguro?",
		  text: "¿Desea eliminar la postclinica de este usuario: " + dato + "?",
		  type: "input",
		  showCancelButton: true,
		  closeOnConfirm: false,
		  inputPlaceholder: "Comentario",
		  cancelButtonText: "Cancelar",	
		  confirmButtonText: "¡Sí, remover el usuario!",
		  confirmButtonClass: "btn-warning"
		}, function (inputValue) {
		  if (inputValue === false) return false;
		  if (inputValue === "") {
			swal.showInputError("¡Necesita escribir algo!");
			return false
		  }
			eliminarPreclinica(postclinica_id, inputValue);
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

function eliminarPreclinica(id, comentario){
  if(getUsuarioSistema() == 1 || getUsuarioSistema() == 3 || getUsuarioSistema() == 4 || getUsuarioSistema() == 5 || getUsuarioSistema() == 8 || getUsuarioSistema() == 9){
		var url = '<?php echo SERVERURL; ?>php/reportes_enfermeria/eliminarPreclinica.php';
		
		var fecha = getFechaPreclinica(id);

		var hoy = new Date();
		fecha_actual = convertDate(hoy);

		if(getMes(fecha)==2){	  
			swal({
				title: "Error", 
				text: "No se puede agregar/modificar registros fuera de este periodo",
				type: "error", 
				confirmButtonClass: 'btn-danger'
			});	 		 
			return false;	
		}else{	
		   if ( fecha <= fecha_actual){  
			$.ajax({
			  type:'POST',
			  url:url,
			  data:'id='+id+'&comentario='+comentario,
			  success: function(registro){
				 if(registro == 1){
					swal({
						title: "Success", 
						text: "Registro eliminado correctamente",
						type: "success", 
					});						 
					pagination_preclinica(1);			 
				 }else if(registro == 2){
					swal({
						title: "Error", 
						text: "Error al Eliminar el Registro",
						type: "error", 
						confirmButtonClass: 'btn-danger'
					});
					pagination_preclinica(1);			 
				 }else if(registro == 3){
					swal({
						title: "Error", 
						text: "No se puede eliminar este registro, existe información en la atención del usuario",
						type: "error", 
						confirmButtonClass: 'btn-danger'
					});
					pagination_preclinica(1);			 
				 }else{		
					swal({
						title: "Error", 
						text: "No se puede eliminar este registro, por favor intente de nuevo más tarde",
						type: "error", 
						confirmButtonClass: 'btn-danger'
					});
				 }
				 return false;
			  }
			});
			}else{	
				swal({
					title: "Error", 
					text: "No se puede agregar/modificar registros fuera de esta fecha",
					type: "error", 
					confirmButtonClass: 'btn-danger'
				});			   
			   return false;			
			}	
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

$('#eliminar_preclinica #Si').on('click', function(e){ // add event submit We don't want this to act as a link so cancel the link action
	 e.preventDefault();
	 eliminarPreclinica();		
});

function editarPreclinica(preclinica_id){
   if (getUsuarioSistema() == 1 || getUsuarioSistema() == 3 || getUsuarioSistema() == 4 || getUsuarioSistema() == 5 || getUsuarioSistema() == 8 || getUsuarioSistema() == 9){
	   var url = '<?php echo SERVERURL; ?>php/reportes_enfermeria/editarPreclinica.php';
	
	   $.ajax({
		  type:'POST',
		  url:url,
		  data:'id='+preclinica_id,
		  success: function(valores){
			  var datos = eval(valores);
			  $('#formulario_agregar_preclinica #pro').val('Edicion');
  			  $('#formulario_agregar_preclinica #id-registro').val(preclinica_id);
			  $('#formulario_agregar_preclinica #expediente').val(datos[0]);
			  $('#formulario_agregar_preclinica #fecha').val(datos[1]);
			  $('#formulario_agregar_preclinica #identidad').val(datos[2]);
			  $('#formulario_agregar_preclinica #nombre').val(datos[3]);
			  $('#formulario_agregar_preclinica #pa').val(datos[4]);
			  $('#formulario_agregar_preclinica #fr').val(datos[5]);
			  $('#formulario_agregar_preclinica #fc').val(datos[6]);
			  $('#formulario_agregar_preclinica #temperatura').val(datos[7]);
			  $('#formulario_agregar_preclinica #peso').val(datos[8]);
			  $('#formulario_agregar_preclinica #talla').val(datos[9]);
			  $('#formulario_agregar_preclinica #observaciones').val(datos[10]);
			  $('#formulario_agregar_preclinica #profesional_consulta').val(datos[11]);

			  $('#formulario_agregar_preclinica #expediente').attr("readonly", true);

			  $('#formulario_agregar_preclinica').attr({ 'data-form': 'update' }); 
			  $('#formulario_agregar_preclinica').attr({ 'action': '<?php echo SERVERURL; ?>php/reportes_enfermeria/modificarPreclinica.php' });

			  $('#formulario_agregar_preclinica #grupo').hide();
			  $('#edit_preclinica').show();
			  $('#reg_preclinica').hide();
			  $('#agregar_preclinica').modal({
				show:true,
				keyboard: false,
				backdrop:'static'
			  });
			return false;
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
}	
  
$('#form_main #exportar').on('click', function(e){ // add event submit We don't want this to act as a link so cancel the link action
 if (getUsuarioSistema() == 1 || getUsuarioSistema() == 3 || getUsuarioSistema() == 4 || getUsuarioSistema() == 5 || getUsuarioSistema() == 8 || getUsuarioSistema() == 9){
	 e.preventDefault();
	 reporteEXCEL();
 }else{
	swal({
		title: "Acceso Denegado", 
		text: "No tiene permisos para ejecutar esta acción",
		type: "error", 
		confirmButtonClass: 'btn-danger'
	});						 
 }		 
});

$('#form_main #reporte_diario').on('click', function(e){ // add event submit We don't want this to act as a link so cancel the link action
 if (getUsuarioSistema() == 1 || getUsuarioSistema() == 3 || getUsuarioSistema() == 4 || getUsuarioSistema() == 5 || getUsuarioSistema() == 8 || getUsuarioSistema() == 9){
	 e.preventDefault();
	 reporteEXCELDiario();
 }else{
	swal({
		title: "Acceso Denegado", 
		text: "No tiene permisos para ejecutar esta acción",
		type: "error", 
		confirmButtonClass: 'btn-danger'
	});					 
 }		 
});

function convertDate(inputFormat) {
  function pad(s) { return (s < 10) ? '0' + s : s; }
  var d = new Date(inputFormat);
  return [d.getFullYear(), pad(d.getMonth()+1), pad(d.getDate())].join('-');
}

function getMes(fecha){
    var url = '<?php echo SERVERURL; ?>php/atencion_pacientes/getMes.php';
	var resp;
	
	$.ajax({
	    type:'POST',
		data:'fecha='+fecha,
		url:url,
		async: false,
		success:function(data){	
          resp = data;			  		  		  			  
		}
	});
	return resp	;	
}

function getFechaPreclinica(preclinica_id){
    var url = '<?php echo SERVERURL; ?>php/reportes_enfermeria/getFechaPreclinica.php';
	var fecha;
	$.ajax({
	    type:'POST',
		url:url,
		data:'preclinica_id='+preclinica_id,
		async: false,
		success:function(data){	
          fecha = data;			  		  		  			  
		}
	});
	return fecha;	
}

function getColaborador_usuario(){
    var url = '<?php echo SERVERURL; ?>php/reportes_enfermeria/getColaborador_usuario.php';
		
	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){
		     $('#form_main #colaborador_usuario').html("");
			 $('#form_main #colaborador_usuario').html(data);	
		}			
     });		
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

$('#form_main #limpiar').on('click', function(e){
    e.preventDefault();
    limpiar();
});
</script>