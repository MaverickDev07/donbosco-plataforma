<body>
	<!-- Main navbar -->
	<div class="navbar bg-indigo-700 ">
		<div class="navbar-header">
			<a class="navbar-brand text-white" href="<?php echo base_url(); ?>Principal">SISTEMA DE CONTROL ACADEMICO "DON BOSCO"<i class="icon-graduation"></i></a>

			<ul class="nav navbar-nav visible-xs-block ">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				<li><a class="sidebar-mobile-main-toggle "><i class="icon-paragraph-justify3 "></i></a></li>

			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav">
				<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3" style="color:white"></i></a></li>
			</ul>

			<div class="navbar-collapse collapse" id="navbar-mobile">
				<ul class="nav navbar-nav navbar-right">
					<li name='nomb_session' id='nomb_session'></li>
					<li><a href="#" onclick="cerrar()">Cerrar Sesión</a></li>
				</ul>
			</div>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main sidebar -->
			<div class="sidebar sidebar-main">
				<div class="sidebar-content">


					<!-- Main navigation -->
					<div class="sidebar-category sidebar-category-visible">
						<div class="category-content no-padding">
							<ul class="navigation navigation-main navigation-accordion">

								<!-- Forms -->

								<li class="navigation-header"><span>Configuración</span> <i class="icon-menu" title="Usuarios"></i></li>
								<li>
									<a href='<?php echo site_url('Karde_contr'); ?>'><i class="icon-user-lock"></i> <span>Alumnos</span></a>
								</li>
								<!--<li>
									<a href='<?php //echo site_url('Config_colegios_contr');
														?>'><i class="icon-users4"></i> <span>Colegios</span></a>								
								</li>
								<li>
									<a href='<?php // echo site_url('Config_nivel_contr');
														?>'><i class="icon-users4"></i> <span>Nivel</span></a>								
								</li>
								<li>
									<a href='<?php //echo site_url('Config_curso_contr');
														?>'><i class="icon-user-minus"></i> <span>Cursos</span></a>									
								</li>
								
								<li>
									<a href="#"><i class="icon-puzzle"></i> <span>Registro de Materias</span></a>									
								</li>
								<li>
									<a href="#"><i class="icon-vcard"></i> <span>Asignación Materias a Cursos</span></a>									
								</li>
								<li>
									<a href="#"><i class="icon-vcard"></i> <span>Asignación Materias a Profesores</span></a>									
								</li>-->



								<!-- /forms -->
							</ul>
						</div>
					</div>
					<!-- /main navigation -->

				</div>
			</div>
			<!-- /main sidebar -->

			<!-- Main content -->
			<div class="content-wrapper">
				<div class="content-group">
					<div class="page-header page-header-inverse has-cover" style="border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
						<div class="page-header-content">
							<div class="page-title ">
								<br>
								<h5 class="text-right">
									<img src="assets/images/logo1.png" alt="" width="65" height="75">
									<small class="display-block">Gestión 2023</small>

								</h5>
								<br>
							</div>
						</div>

						<div class="breadcrumb-line">
							<ul class="breadcrumb">
								<li><a href="Principal/index"><i class="icon-home2 position-left"></i> Principal</a></li>

								<li class="active">Kardex</li>
							</ul>
						</div>
					</div>
				</div>

				<!-- Content area -->
				<div class="content">

					<!-- Dashboard content -->
					<div class="row">
						<div class="col-lg-12">
							<div class="panel ">
								<div class="panel-heading bg-indigo">
									<h6 class="panel-title">Registro de Kardex</h6>
									<div class="heading-elements">
										<ul class="icons-list">
											<li><a data-action="reload"></a></li>
										</ul>
									</div>
								</div>

								<div class="panel-body">
									<div class="row">
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label col-md-3">GESTION:</label>
												<div class="col-md-9">
													<!--<input name="Fgestion" placeholder="" class="form-control" type="text" id="Fgestion" readonly="true">-->
													<select class="form-control" type="text" id="sgestion" onchange="">
													</select>
													<span class="help-block"></span>
												</div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label col-md-3">NIVEL:</label>
												<div class="col-md-9">
													<Select class="form-control" type="text" id="Fnivel" onchange="gescole(this.value);getcur(this.value)">
													</Select>
													<span class="help-block"></span>
												</div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label col-md-3">COLEGIO:</label>
												<div class="col-md-9">
													<input name="Fcolegio" placeholder="" class="form-control" id="Fcolegio" readonly="true">
													<span class="help-block"></span>
												</div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label col-md-3">CURSO:</label>
												<div class="col-md-9">
													<select name="Fcurso" class="form-control" id="Fcurso" onchange="cargarBusqueda()">
													</select>
													<span class="help-block"></span>
												</div>
											</div>
										</div>

										<div class="col-sm-4">


											<div class="btn-toolbar">
												<div class="btn-group">
													<button type="button" class="btn bg-info-300" onclick="print_kar_curso(this.id)" id='5'>1 Tri</button>
													<button type="button" class="btn bg-info-300" onclick="print_kar_curso(this.id)" id='6'>2 Tri</button>
													<button type="button" class="btn bg-info-300" onclick="print_kar_curso(this.id)" id='7'>3 Tri</button>
													&nbsp;&nbsp;&nbsp;
													<button class="btn  btn-danger" onclick="export_pdf()"><i class="icon icon-file-pdf"></i> PDF Lista</button>
													<!-- <button class="btn btn-default" onclick="reload_all()" ><i class="icon icon-user-block"></i>SIN FILTRO</button>
															<button class="btn btn-default" onclick="reload_estud()" ><i class="glyphicon glyphicon-refresh"></i> ACTUALIZAR</button> -->
												</div>
											</div>



										</div>
									</div>
									<hr class="bg-indigo-300">
									<table id="table_estudiante" class="table datatable-responsive" cellspacing="0" width="100%">
										<thead class="bg-indigo">
											<tr>
												<th>Nro</th>
												<th>RUDE</th>
												<th>CI</th>
												<th>AP PATERNO</th>
												<th>AP MATERNO</th>
												<th>NOMBRES</th>
												<th>GENERO</th>
												<th>ACTION</th>
												<th>kARDEX</th>
											</tr>
										</thead>
										<tbody>
										</tbody>

										<tfoot class="bg-indigo">
											<tr>
												<th>Nro</th>
												<th>RUDE</th>
												<th>CI</th>
												<th>AP PATERNO</th>
												<th>AP MATERNO</th>
												<th>NOMBRES</th>
												<th>GENERO</th>
												<th>ACTION</th>
												<th>kARDEX</th>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>

					<!-- Footer -->
					<div class="footer text-muted">
						&copy; 2023.<a href="#">Sistema de Control Académico "DON BOSCO"</a> by <a href="donboscosucre.edu.bo" target="_blank">Departamento de Informatica </a>
					</div>
					<!-- /footer -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->
</body>

<script type="text/javascript">
	var testudiante;
	var save_method;
	var _global_idcur = "";
	var _global_idest = "";

	$(document).ready(function() {

		// testudiante=$('#table_estudiante').DataTable({
		// 	"processing":true,
		// 	"serverSide":true,
		// 	"order":[],
		// 	"ajax":{
		// 		"url":"<?php echo site_url('Karde_contr/ajax_list'); ?>",
		// 		"type":"POST"
		// 	},

		// 	"columnDefs":[
		// 	{
		// 		"targets":[-1],
		// 		"orderable":false,
		// 	},
		// 	],
		// });
		get_nivel();
		get_gestion();
		//document.getElementById("sgestion").value='2019';

	});

	function get_gestion() {
		var options = "";
		//envio de valores
		var data1 = {
			"table": "gestion",
		};

		$.ajax({

			url: "<?php echo site_url('Karde_contr/ajax_get_gestion'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					data.data.forEach(function(item) {
						options = options + "<option value='" + item + "'>" + item + "</option>";
					});
					document.getElementById('sgestion').innerHTML = options;
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	//obtener codigo nuevo para el registro
	function get_idcod() {
		//envio de valores
		var data1 = {
			"table": "kardex",
			"cod": "KDX-",
		};

		$.ajax({

			url: "<?php echo site_url('Karde_contr/ajax_get_id'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					//datos recuperados
					document.getElementById('idkar').value = data.codgen;
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}


	function cerrar() {
		var url = "<?php echo site_url('Karde_contr/ajax_cerrar') ?>";
		//alert(url);

		$.ajax({
			url: url,
			type: "POST",
			data: {},
			dataType: "JSON",
			success: function(data) {

			}
		});
		var enlace = "<?php echo  base_url(); ?>";
		window.location.replace(enlace);

	}

	//SELECT nivel
	function get_nivel() {
		var options = "<option value=''></option>";
		//envio de valores
		var data1 = {
			"table": "nivel",
		};

		$.ajax({

			url: "<?php echo site_url('Karde_contr/ajax_get_niveles'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					i = 0;
					data.data.forEach(function(item) {

						options = options + "<option value='" + data.data1[i] + "'>" + item + "</option>";
						i++;
					});
					document.getElementById('Fnivel').innerHTML = options;
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	//gescole una vez selecionado el nivel carga el colegio y la gestion
	function gescole(level) {
		var data1 = {
			"table": "curso",
			"lvl": level,
		};

		$.ajax({

			url: "<?php echo site_url('Karde_contr/ajax_get_colegio'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					//datos recuperados
					//	document.getElementById('Fgestion').value=data.data[0];
					document.getElementById('Fcolegio').value = data.data[0];
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	//obtener id curso en la tabla curso
	function getcur(nivel) {
		var options = "<option value=''></option>";
		//alert(nivel);
		var datacur = {
			"TablaCur": "curso",
			"nivel": nivel,
		}

		$.ajax({

			url: "<?php echo site_url('Karde_contr/ajax_get_cursos'); ?>",
			type: "POST",
			data: datacur,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					i = 0;
					//alert(data.data[0]);
					data.data.forEach(function(item) {

						options = options + "<option value='" + data.data1[i] + "'>" + item + "</option>";
						i++;
					});
					document.getElementById('Fcurso').innerHTML = options;
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	//obtener estudiante en tabla, una vez escogido el curso
	function getestud() {
		var idcur = "";
		var curso = document.getElementById('Fcurso').value;
		var nivel = document.getElementById('Fnivel').value;


		//alert(curso);

		var dataestu = {
			"EstNivel": nivel,
			"EstCurso": curso,
		}

		var url = "<?php echo site_url('Karde_contr/ajax_get_idcurso'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			data: dataestu,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					var gestion = document.getElementById('sgestion').value;
					var id = gestion + data.data[0];
					// cargarBusqueda(id);
					_global_idcur = data.data[0];
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	//cargar Busqueda
	function cargarBusqueda() {
		var curso = document.getElementById('Fcurso').value;
		var nivel = document.getElementById('Fnivel').value;
		var gestion = document.getElementById('sgestion').value;
		var id = curso + "-" + nivel + "-" + gestion;
		url1 = "<?php echo site_url('Karde_contr/ajax_list_idcurso'); ?>/" + id;
		testudiante = $('#table_estudiante').DataTable({
			"destroy": true,
			"serverSide": true,
			"order": [],
			"processing": true,
			"ajax": {
				"url": url1,
				"type": "POST",
			},

			"columnDefs": [{
				"targets": [-1],
				"orderable": false,
			}, ],
		});

		//alert(nivel+"-"+gestion+"-"+colegio+"-"+cur);
	}

	//validacion de datos para el forumulario
	function validacion(campo, descrip) {
		var error = false;
		if (document.getElementById(campo).value == '') {
			error = true;
			swal({
				title: "Información",
				text: "Debe introducir en el campo  " + descrip,
				confirmButtonColor: "#2196F3",
				type: "info"
			});
			//alert("debe introducir en el campo  "+descrip);
		}
		return error;
	}

	//mensaje para guarfar
	function msg_guardar() {
		swal({
			title: "Guardado!",
			text: "Registro Guardado, Satisfactoriamente!",
			confirmButtonColor: "#66BB6A",
			type: "success"
		});

	}



	//guardado de usuario
	function save_kardex() {
		//alert(save_method);

		var idkar, idest, idcurso, colegio, gestion, bimestre, fecha, descrip, categoria = '';
		var error = false;
		var url;
		url = "<?php echo site_url('Karde_contr/ajax_save_kardex'); ?>";
		idest = document.getElementById('id_est').value;
		gestion = document.getElementById('sgestion').value;

		if (!validacion('bimestre', 'BIMESTRE'))
			bimestre = document.getElementById('bimestre').value;
		else
			error = true;
		fecha = document.getElementById('fecha').value;
		// alert(fecha);
		if (!validacion('descrip', 'DESCRIPCIÓN'))
			descrip = document.getElementById('descrip').value;
		else
			error = true;

		categoria = document.getElementById("categoria").value;
		materia = document.getElementById("materia").value;


		if (!error) {
			var data1 = {
				"idest": idest,
				"gestion": gestion,
				"bimestre": bimestre,
				"fecha": fecha,
				"descrip": descrip,
				"categoria": categoria,
				"materia": materia
			};


			$.ajax({

				url: url,
				type: "POST",
				data: data1,
				dataType: "JSON",
				success: function(data) //cargado de datos del registro 
				{
					if (data.status) {
						msg_guardar();
						$('#modal_form').modal('hide');
						reload_table();
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					// alert('No puede obtener codigo nuevo, para el registro');
				}
			});

		}

	}

	//borrar registro
	function delete_estud(id) {
		swal({
				title: "¿Esta Seguro?",
				text: "Esta seguro de eliminar el registro " + id + " !!!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#EF5350",
				confirmButtonText: "Si, Borrar!",
				cancelButtonText: "No, Cancelar!",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm) {
				if (isConfirm) {
					$.ajax({
						url: "<?php echo site_url('Karde_contr/ajax_delete') ?>/" + id,
						type: "POST",
						dataType: "JSON",
						success: function(data) {

							swal({
								title: "Eliminado!",
								text: "Registro Borrado!!!",
								confirmButtonColor: "#66BB6A",
								type: "success"
							});

							reload_table();

						},
						error: function(jqXHR, textStatus, errorThrown) {
							swal({
								title: "Información",
								text: "Hubo un error al intentar Borrar el Registro!",
								confirmButtonColor: "#2196F3",
								type: "info"
							});
						}
					});

				} else {
					swal({
						title: "NO Borrado",
						text: "Su registro esta a salvo :)",
						confirmButtonColor: "#2196F3",
						type: "error"
					});
				}
			});


	}

	//editar estudiante
	function edit_estud(id) {
		//alert(id);
		save_method = 'update';



		$.ajax({
			url: "<?php echo site_url('Karde_contr/ajax_edit_estud'); ?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				// $('[name="idest"]').val(data.idest);	
				$('[name="appaterno"]').val(data.appaterno);
				$('[name="apmaterno"]').val(data.apmaterno);
				$('[name="nombre"]').val(data.nombre);
				$('[name="descrip"]').val('');
				$('[name="id_est"]').val(data.id_est);


				// $('[name="idcurso"]').val(data.idcurso);
				// $('[name="colegio"]').val(data.colegio);
				// $('[name="gestion"]').val(data.gestion);



			},
			error: function(jqXHR, textStatus, errorThrown) {
				swal({
					title: "Información",
					text: "Error al obtener los datos",
					confirmButtonColor: "#2196F3",
					type: "info"
				});
			}

		});

		get_idcod();
		tkardex = $('#table_kardex').DataTable();
		tkardex.clear();
		$('#modal_form').modal('show');


	}

	//delete kardex
	function deletekar(idkar) {
		swal({
				title: "¿Esta Seguro?",
				text: "Esta seguro de eliminar el registro " + idkar + " !!!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#EF5350",
				confirmButtonText: "Si, Borrar!",
				cancelButtonText: "No, Cancelar!",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm) {
				if (isConfirm) {
					$.ajax({
						url: "<?php echo site_url('Karde_contr/deletekar') ?>/" + idkar,
						type: "POST",
						dataType: "JSON",
						success: function(data) {

							swal({
								title: "Eliminado!",
								text: "Registro Borrado!!!",
								confirmButtonColor: "#66BB6A",
								type: "success"
							});

							reload_estud();

						},
						error: function(jqXHR, textStatus, errorThrown) {
							swal({
								title: "Información",
								text: "Hubo un error al intentar Borrar el Registro!",
								confirmButtonColor: "#2196F3",
								type: "info"
							});
						}
					});


				} else {
					swal({
						title: "NO Borrado",
						text: "Su registro esta a salvo :)",
						confirmButtonColor: "#2196F3",
						type: "error"
					});
				}
			});
	}


	//cargar tabla
	function reload_estud() {
		table_kardex.ajax.reload(null, false);

	}

	function reload_all() {
		testudiante = $('#table_estudiante').DataTable({
			"destroy": true,
			"processing": true,
			"serverSide": true,
			"order": [],
			"ajax": {
				"url": "<?php echo site_url('Karde_contr/ajax_list'); ?>",
				"type": "POST"
			},
			"scrollX": true,
			"columnDefs": [{
				"targets": [-1],
				"orderable": false,
			}, ],
		});
		document.getElementById('Fnivel').value = "";
		document.getElementById('Fcolegio').value = "";
		document.getElementById('Fgestion').value = "";
		document.getElementById('Fcurso').options.length = 0;

	}

	function listarkar() {
		var data1 = {
			"idest": document.getElementById('id_est').value,
			"bimes": document.getElementById('mosbimes').value,
			"gestion": document.getElementById('sgestion').value
		}


		url = "<?php echo site_url('Karde_contr/ajax_load_sql'); ?>";

		tkardex = $('#table_kardex').DataTable({
			"destroy": true,
			"serverSide": true,
			"order": [],
			"processing": true,
			"ajax": {
				"url": url,
				"data": data1,
				"type": "POST",
			},

			"columnDefs": [{
				"targets": [-1],
				"orderable": true,
			}],
			"scrollX": true
		});
	}

	function printkardex() {
		var bimes = document.getElementById('mosbimes').value;
		// var gestion=document.getElementById('gestion').value;
		// var idcurso=document.getElementById('idcurso').value;
		var idest = document.getElementById('id_est').value;
		var gestion = document.getElementById('sgestion').value;
		var nivel = document.getElementById('Fnivel').value;
		var curso = document.getElementById('Fcurso').value;
		var valor = curso + "-" + nivel + "-" + gestion + "-" + idest + "-" + bimes;
		//alert(valor);

		var url = "<?php echo site_url('Karde_contr/printKar'); ?>/" + valor;



		$.ajax({
			url: url,
			type: "POST",
			ContentType: "application/pdf",
			success: function(data) {
				window.open(url, "menubar=no,scrollbars=yes,statubar=no,titlebar=yes,width=500,height=500");

				swal({
					title: "Archivo",
					text: "Archivo Generado Satisfactoriamente!!!",
					confirmButtonColor: "#66BB6A",
					type: "success"
				});


			},
			error: function(jqXHR, textStatus, errorThrown) {
				swal({
					title: "Archivo NO Generado",
					text: "Hubo un error, comuniquese con el administrador.",
					confirmButtonColor: "#2196F3",
					type: "error"
				});
			}
		});
	}

	//ventana para el pdf
	function export_pdf() {
		var gestion = document.getElementById('sgestion').value;
		var nivel = document.getElementById('Fnivel').value;
		var curso = document.getElementById('Fcurso').value;
		var valor = curso + "-" + nivel + "-" + gestion;

		var url = "<?php echo site_url('Karde_contr/export_pdf'); ?>/" + valor;

		$.ajax({
			url: url,
			type: "POST",
			ContentType: "application/pdf",
			success: function(data) {
				window.open(url, "menubar=no,scrollbars=yes,statubar=no,titlebar=yes,width=500,height=500");

				swal({
					title: "Archivo",
					text: "Archivo Generado Satisfactoriamente!!!",
					confirmButtonColor: "#66BB6A",
					type: "success"
				});


			},
			error: function(jqXHR, textStatus, errorThrown) {
				swal({
					title: "Archivo NO Generado",
					text: "Hubo un error, comuniquese con el administrador.",
					confirmButtonColor: "#2196F3",
					type: "error"
				});
			}
		});
	}

	function print_kar_curso(id) {
		var gestion = document.getElementById('sgestion').value;
		var nivel = document.getElementById('Fnivel').value;
		var curso = document.getElementById('Fcurso').value;
		var valor = curso + "-" + nivel + "-" + gestion + "-" + id;

		var url = "<?php echo site_url('Karde_contr/printKar_curso'); ?>/" + valor;

		$.ajax({
			url: url,
			type: "POST",
			ContentType: "application/pdf",
			success: function(data) {
				window.open(url, "menubar=no,scrollbars=yes,statubar=no,titlebar=yes,width=500,height=500");

				swal({
					title: "Archivo",
					text: "Archivo Generado Satisfactoriamente!!!",
					confirmButtonColor: "#66BB6A",
					type: "success"
				});
			},
			error: function(jqXHR, textStatus, errorThrown) {
				swal({
					title: "Archivo NO Generado",
					text: "Hubo un error, comuniquese con el administrador.",
					confirmButtonColor: "#2196F3",
					type: "error"
				});
			}
		});

	}

	function print_kar_studien(id_est) {
		var gestion = document.getElementById('sgestion').value;
		var nivel = document.getElementById('Fnivel').value;
		var curso = document.getElementById('Fcurso').value;
		var valor = curso + "-" + nivel + "-" + gestion + "-" + id_est;

		var url = "<?php echo site_url('Karde_contr/printKar_studien'); ?>/" + valor;

		$.ajax({
			url: url,
			type: "POST",
			ContentType: "application/pdf",
			success: function(data) {
				window.open(url, "menubar=no,scrollbars=yes,statubar=no,titlebar=yes,width=500,height=500");

				swal({
					title: "Archivo",
					text: "Archivo Generado Satisfactoriamente!!!",
					confirmButtonColor: "#66BB6A",
					type: "success"
				});
			},
			error: function(jqXHR, textStatus, errorThrown) {
				swal({
					title: "Archivo NO Generado",
					text: "Hubo un error, comuniquese con el administrador.",
					confirmButtonColor: "#2196F3",
					type: "error"
				});
			}
		});

	}

	function export_xls() {
		var valor = _global_idcur;

		var url = "<?php echo site_url('Karde_contr/printxls'); ?>/" + valor;

		$.ajax({
			url: url,
			type: "POST",
			//ContentType:"application/xls",
			success: function(data) {
				window.open(url, "menubar=no,scrollbars=yes,statubar=no,titlebar=yes,width=500,height=500");

				swal({
					title: "Archivo",
					text: "Archivo Generado Satisfactoriamente!!!",
					confirmButtonColor: "#66BB6A",
					type: "success"
				});


			},
			error: function(jqXHR, textStatus, errorThrown) {
				swal({
					title: "Archivo NO Generado",
					text: "Hubo un error, comuniquese con el administrador.",
					confirmButtonColor: "#2196F3",
					type: "error"
				});
			}
		});
	}
</script>


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-indigo-300">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title">Formulario de Registro de Kardex </h3>
			</div>
			<div class="modal-body form">
				<form action="#" id="form" class="form-horizontal">
					<input type="hidden" value="" name="id" />
					<div class="form-body">

						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label col-md-4">PATERNO:</label>
									<div class="col-md-8">
										<input type="text" name="appaterno" placeholder="apellido paterno" class="form-control bg-primary" style='color:black;' id="appaterno" readonly="true">
										<span class="help-block"></span>
									</div>
								</div>

							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label col-md-4">MATERNO</label>
									<div class="col-md-8">
										<input type="text" name="apmaterno" placeholder="apellido materno" class="form-control bg-primary" style='color:black;' id="apmaterno" readonly="true">
										<span class="help-block"></span>
									</div>
								</div>

								<input type="hidden" id="id_est" name="id_est">
								<!-- <div class="form-group">
		                            <label class="control-label col-md-4">GESTION:</label>
		                            <div class="col-md-8">
		                                <input type="text" name="gestion" placeholder="año" class="form-control" id="gestion" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div> -->
							</div>
							<div class="col-sm-4">

								<div class="form-group">
									<label class="control-label col-md-3">NOMBRE:</label>
									<div class="col-md-9">
										<input type="text" name="nombre" placeholder="nombres" class="form-control bg-primary" style='color:black;' id="nombre" readonly="true">
										<span class="help-block"></span>
									</div>
								</div>

							</div>

						</div>
						<hr class="bg-indigo-300">
						<div class="row">
							<div class="panel-body">
								<div class="tabbable">
									<ul class="nav nav-tabs bg-indigo-300 nav-tabs-component nav-justified">
										<li class="active"><a href="#colored-rounded-justified-tab1" data-toggle="tab">Registro</a></li>
										<li><a href="#colored-rounded-justified-tab2" data-toggle="tab">Listar</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="colored-rounded-justified-tab1">
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label col-md-4">TRIMESTRE:</label>
													<div class="col-md-8">
														<select name="bimestre" id="bimestre" class="form-control">
															<option value="5">1er Trimestre</option>
															<option value="6">2do Trimestre</option>
															<option value="7">3er Trimestre</option>
														</select>
														<span class="help-block"></span>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-4">FECHA:</label>
													<div class="col-md-8">
														<input type="date" name="fecha" placeholder="date" class="form-control" id="fecha" value="<?php echo date("Y-m-d"); ?>">
														<span class="help-block"></span>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-4">DESCRIPCIÓN:</label>
													<div class="col-md-8">
														<textarea rows="10" cols="50" name="descrip" placeholder="descripcion, información adicional" class="form-control" id="descrip">
														</textarea>
														<span class="help-block"></span>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-4"></label>
													<div class="col-md-8">
														<button type="button" id="btnSave" onclick="save_kardex()" class="btn bg-indigo-300 btn-rounded btn-xlg">Guardar</button>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-4"></label>
													<div class="col-md-8">
														<button type="button" class="btn bg-danger-300 btn-rounded btn-xlg" data-dismiss="modal"> Cancelar </button>
													</div>
												</div>
											</div>


											<div class="col-sm-6">
												<div class="alert alert-primary" role="alert">
													<h4 class="alert-heading">MATERIA!</h4>
													<p>Seleccione la materia</p>
													<hr>
													<p class="mb-0">
														<select name="materia" id="materia" class="select">
															<option value="REGENCIA">REGENCIA</option>
															<option value="DIRECCION">DIRECCION</option>
															<option value="LENGUAJE">LENGUAJE</option>
															<option value="QUECHUA">QUECHUA</option>
															<option value="GRAMATICA">GRAMATICA</option>
															<option value="INGLES">INGLES</option>
															<option value="CIENCIAS SOCIALES">CIENCIAS SOCIALES</option>
															<option value="HISTORIA">HISTORIA</option>
															<option value="CIVICA">CIVICA</option>
															<option value="GEOGRAFIA">GEOGRAFIA</option>
															<option value="EDUCACION FISICA Y DEPORTE">EDUCACION FISICA Y DEPORTE</option>
															<option value="EDUCACION MUSICA">EDUCACION MUSICA</option>
															<option value="ARTES PLASTICAS Y VISUALE">ARTES PLASTICAS Y VISUALES</option>
															<option value="MATEMATICA">MATEMATICA</option>
															<option value="INFORMATICA">INFORMATICA</option>
															<option value="SISTEMAS INFORMATICO">SISTEMAS INFORMATICO</option>
															<option value="ELECTRONICA">ELECTRONICA</option>
															<option value="CIENCIAS NATURALES">CIENCIAS NATURALES</option>
															<option value="BIOLOGIA">BIOLOGIA</option>
															<option value="FISICA">FISICA</option>
															<option value="QUIMICA">QUIMICA</option>
															<option value="FILOSOFICA">FILOSOFICA</option>
															<option value="PSICOLOGIA">PSICOLOGIA</option>
															<option value="RELIGION">RELIGION</option>
															<option value="ESTADISTICAS">ESTADISTICAS</option>
														</select>
												</div>
											</div>

											<div class="col-sm-6">
												<div class="alert alert-primary" role="alert">
													<h4 class="alert-heading">Categoria!</h4>
													<p>Seleccione la categoria correspondiente.</p>
													<hr>
													<p class="mb-0">
														<select name="categoria" id="categoria" class="select">
															<option value="1">NO INGRESO A CLASES</option>
															<option value="2">FALTA DE RESPETO A SUS COMPAÑEROS O DOCENTE</option>
															<option value="3">CONDUCTA AGRESIVA FÍSICA O VERBAL</option>
															<option value="4">NO TRABAJA EN CLASES</option>
															<option value="5">NO TRAE MATERIAL DE TRABAJO</option>
															<option value="19">NO TAREAS</option>
															<option value="6">USO INADECUADO DEL CELULAR</option>
															<option value="7">TRABAJO INCOMPLETO</option>
															<option value="8">CUADERNO INCOMPLETO</option>
															<option value="9">NO PRESENTO TRABAJO</option>
															<option value="10">NO PRESENTO CUADERNO</option>
															<option value="11">NO DIO EXAMÉN</option>
															<option value="12">INDISCIPLINA EN CLASE</option>
															<option value="13">EXPULSADO DE CLASE</option>
															<option value="14">SIN UNIFORME</option>
															<option value="15">ATRASO A CLASES</option>
															<option value="16">ATRASO AL COLEGIO</option>
															<option value="17">FALTA SIN LICENCIA</option>
															<option value="18">FALTA CON LICENCIA</option>
														</select>
												</div>
											</div>
										</div>

										<div class="tab-pane" id="colored-rounded-justified-tab2">
											<div class="col-sm-4">
												<div class="form-group">
													<label class="control-label col-md-4">BIMESTRE:</label>
													<div class="col-md-8">
														<select name="bimestre" id="mosbimes" class="form-control" onchange="listarkar()">
															<option value=""></option>
															<option value="5">1er Trimestre</option>
															<option value="6">2do Trimestre</option>
															<option value="7">3er Trimestre</option>
														</select>
														<span class="help-block"></span>
													</div>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="form-group">
													<label class="control-label col-md-4"></label>
													<div class="col-md-8">
														<button type="button" id="btnSave" onclick="printkardex()" class="btn bg-danger-300 btn-rounded btn-xlg btn-block"><i class="icon icon-file-pdf"></i> Imprimir Kardex</button>
													</div>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="form-group">
													<label class="control-label col-md-4"></label>
													<div class="col-md-8">
														<button type="button" class="btn bg-danger-300 btn-rounded btn-xlg" data-dismiss="modal"> Salir </button>
													</div>
												</div>
											</div>
											<br>
											<br>
											<br>
											<div class="alert alert-primary" role="alert">
												<table id="table_kardex" class="table datatable-responsive" cellspacing="0" width="100%">
													<thead class="bg-primary-300">
														<tr>
															<th>NRO</th>
															<th>FECHA</th>
															<th>CATEGORIA</th>
															<th>MATERIA</th>
															<th>DESCRIPCION</th>
															<th>ACTION</th>
														</tr>
													</thead>
													<tbody>
													</tbody>

													<tfoot class="bg-primary-300">
														<tr>
															<th>NRO</th>
															<th>FECHA</th>
															<th>CATEGORIA</th>
															<th>MATERIA</th>
															<th>DESCRIPCION</th>
															<th>ACTION</th>
														</tr>
													</tfoot>
												</table>
											</div>
										</div>


									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer bg-indigo-300">

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->