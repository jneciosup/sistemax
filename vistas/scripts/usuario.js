var tabla;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	})

	$("#imagenmuestra").hide();
	//Mostramos los permisos
	$.post("../ajax/usuario.php?op=permisos&id=",function(r){
		var data = JSON.parse(r);
		var html = '';
		data.forEach(function(p) {
			var checked = p.marcado ? 'checked' : '';
			html += '<li> <input type="checkbox" '+checked+' name="permiso[]" value="'+p.idpermiso+'">'+p.nombre+'</li>';
		});
		$("#permisos").html(html);
	});
}

//Función limpiar
function limpiar()
{
	$("#nombre").val("");
	$("#num_documento").val("");
	$("#direccion").val("");
	$("#telefono").val("");
	$("#email").val("");
	$("#cargo").val("");
	$("#login").val("");
	$("#clave").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
	$("#idusuario").val("");
}

//Función mostrar formulario
function mostrarform(flag)
{
	limpiar();
	if (flag)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		// Aplicar maxlength acorde al tipo de documento actual al mostrar el formulario
		applyDocumentoMaxlength();
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//Función cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
}

//Función Listar
function listar()
{
	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		            'copyHtml5',
		            'excelHtml5',
		            'csvHtml5',
		            'pdf'
		        ],
		"ajax":
				{
					url: '../ajax/usuario.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"columns": [
			{ 
				"data": null, 
				"render": function (data, type, row) {
					var id = row.idusuario;
					if (row.condicion == 1) {
						return '<button class="btn btn-warning" onclick="mostrar('+id+')"><i class="fa fa-pencil"></i></button> ' +
							   '<button class="btn btn-danger" onclick="desactivar('+id+')"><i class="fa fa-close"></i></button>';
					} else {
						return '<button class="btn btn-warning" onclick="mostrar('+id+')"><i class="fa fa-pencil"></i></button> ' +
							   '<button class="btn btn-primary" onclick="activar('+id+')"><i class="fa fa-check"></i></button>';
					}
				}
			},
			{ "data": "nombre" },
			{ "data": "tipo_documento" },
			{ "data": "num_documento" },
			{ "data": "telefono" },
			{ "data": "email" },
			{ "data": "login" },
			{ 
				"data": "imagen",
				"render": function(data) {
					return "<img src='../files/usuarios/"+data+"' height='50px' width='50px' >";
				}
			},
			{ 
				"data": "condicion",
				"render": function (data, type, row) {
					return (data == 1) ? '<span class="label bg-green">Activado</span>' : '<span class="label bg-red">Desactivado</span>';
				}
			}
		],
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	var clave = $("#clave").val();

	if (clave.length < 10) {
		bootbox.alert("La clave debe tener al menos 10 caracteres.");
		return false;
	}

	if ($("input[name='permiso[]']:checked").length === 0) {
		$("#btnGuardar").prop("disabled", false); // Volver a habilitar el botón
		bootbox.alert("Debe seleccionar al menos un permiso.");
		return false;
	}

	$.ajax({
		url: "../ajax/usuario.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {                    
	          bootbox.alert(datos);	          
	          mostrarform(false);
	          tabla.ajax.reload();
	    }

	});
	limpiar();
}

function mostrar(idusuario)
{
	$.post("../ajax/usuario.php?op=mostrar",{idusuario : idusuario}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);

		$("#nombre").val(data.nombre);
		$("#tipo_documento").val(data.tipo_documento);
		$("#tipo_documento").selectpicker('refresh');
		$("#num_documento").val(data.num_documento);
		$("#direccion").val(data.direccion);
		$("#telefono").val(data.telefono);
		$("#email").val(data.email);
		$("#cargo").val(data.cargo);
		$("#login").val(data.login);
		$("#clave").val(data.clave);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../files/usuarios/"+data.imagen);
		$("#imagenactual").val(data.imagen);
		$("#idusuario").val(data.idusuario);

		// Ajustar maxlength del número según el tipo de documento cargado
		applyDocumentoMaxlength();

 	});
 	$.post("../ajax/usuario.php?op=permisos&id="+idusuario,function(r){
		var data = JSON.parse(r);
		var html = '';
		data.forEach(function(p) {
			var checked = p.marcado ? 'checked' : '';
			html += '<li> <input type="checkbox" '+checked+' name="permiso[]" value="'+p.idpermiso+'">'+p.nombre+'</li>';
		});
		$("#permisos").html(html);
	});
}

//Función para desactivar registros
function desactivar(idusuario)
{
	bootbox.confirm("¿Está Seguro de desactivar el usuario?", function(result){
		if(result)
        {
        	$.post("../ajax/usuario.php?op=desactivar", {idusuario : idusuario}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Función para activar registros
function activar(idusuario)
{
	bootbox.confirm("¿Está Seguro de activar el Usuario?", function(result){
		if(result)
        {
        	$.post("../ajax/usuario.php?op=activar", {idusuario : idusuario}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//limpiar caracteres especiales en el campo de login
$("#login").on("input", function () {
    this.value = this.value.replace(/[^a-zA-Z0-9]/g, "");
});

// Ajusta el maxlength del campo num_documento según el tipo seleccionado
function applyDocumentoMaxlength() {
	var tipo = $("#tipo_documento").val();
	var max = 20; // valor por defecto
	if (tipo === 'DNI') {
		max = 8;
	} else if (tipo === 'RUC') {
		max = 11;
	} else if (tipo === 'CEDULA') {
		max = 13;
	}
	$("#num_documento").attr('maxlength', max);
	// Truncar valor si es más largo que el máximo
	var current = $("#num_documento").val();
	if (current && current.length > max) {
		$("#num_documento").val(current.substring(0, max));
	}
}

// Vincular el cambio en el select para aplicar inmediatamente el maxlength
$(document).on('change', '#tipo_documento', function() {
	applyDocumentoMaxlength();
});

init();