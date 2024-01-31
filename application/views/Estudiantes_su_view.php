<?php
$id_est = $this->session->get_userdata()['id'];
$course = $this->session->get_userdata()['course'];
$gestion = $this->session->get_userdata()['gestion'];
$nivel = $this->session->get_userdata()['nivel'];
if (isset($this->session->get_userdata()['ci'])) {
	$ci = trim($this->session->get_userdata()['ci']);
} else {
	$ci = "";
}
$value = $course . '-' . $nivel . '-' . $gestion . '-' . $id_est;
$kardex_url = base_url() . 'Karde_contr/printKar_studien/' . $value;
$notas_url = 'http://181.115.156.38/apidb/boletines/tercer/' . $value;
$preinscripcion = 'http://181.115.156.38/donboscoapp/preinscripcion';
// $notas_url = 'http://181.115.156.38/donboscoapp/boletines/'.$value;
$formulario_permanencia = 'http://181.115.156.38/donboscoapp/confirmar?ci=' . $ci;
$permanencia_form = 'http://181.115.156.38/donboscoapp/permanencia?ci=' . $ci;
if ($nivel === 'SM' || $nivel === 'PM') {
	$convocatoria_preinscripcion = base_url() . 'files/COMUNICADO_PREINSCRIPCION.pdf';
} else {
	$convocatoria_preinscripcion = base_url() . 'files/PREINSCRIPCION_TT.pdf';
}
?>

<body>
	<!-- Main navbar -->
	<div class="navbar bg-primary-300 ">
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

								<!--
								<li class="navigation-header"><span>TAREAS</span> <i class="icon-menu" title="Usuarios"></i></li>
								<li>
									<a href='<?php // echo site_url('Estudiantes_su_contr'); 
														?>'><i class="icon-user-lock"></i> <span>Subir Tareas</span></a>
								</li>
								<li>
									<a href='<?php // echo site_url('Estudiantes_de_contr'); 
														?>'><i class="icon-user-lock"></i> <span>Descargar Tareas y Temas</span></a>
								</li>
								<li>
									<a href='<?php // echo site_url('Estudiantes_pe_contr'); 
														?>'><i class="icon-user-lock"></i> <span>Foro</span></a>
								</li>
								<li class="navigation-header"><span>CLASE VIRTUALES</span> <i class="icon-menu" title="Usuarios"></i></li>
								<li>
									<a href='<?php // echo site_url('Estudiantes_ca_contr'); 
														?>'><i class="icon-user-lock"></i> <span>Calendario Virtual</span></a>									
								</li>
								<li>
									<a href='<?php // echo site_url('Estudiantes_sac_contr'); 
														?>'><i class="icon-user-lock"></i> <span>Olimpiadas 2da Fase</span></a>
								</li>
								<li>
									<a href='<?php // echo site_url('Estudiantes_link_contr'); 
														?>'><i class="icon-user-lock"></i> <span>Clases</span></a>
								</li>
								<li>
									<a href='<?php // echo site_url('Estudiantes_nota_contr'); 
														?>'><i class="icon-user-lock"></i> <span>Notas</span></a>
								</li>
							-->
								<li class="navigation-header"><span>Kardex</span> <i class="icon-menu" title="Usuarios"></i></li>
								<li>
									<a href='<?php echo $kardex_url; ?>' target='_blank'><i class="icon-user-lock"></i> <span>Kardex</span></a>
								</li>

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

							<!--<div class="col-lg-12">
								<div class="panel panel-warning">
									<div class="panel-heading bg-warning">
										<CENTER>
											<h4 class="panel-title">NOTIFICACIONES <?php echo ' - ' . $this->mat->get_trimestre_current()->nombre; ?></h4>
										</CENTER>
									</div>
									<div class="panel-body">
										<?php
										/*if ($nivel === 'SM' || $nivel === 'PM') {
											echo "<div class='col-sm-12 col-md-6'>
											<div class='panel panel-animacion'>
												<div class='panel-heading panel-olimpiada--header' style='background: black!important;'>
													<h6 class='panel-title'>
														<svg class='animated wobble' xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='#fadb6a' style='position: relative; display: inline-block; top: 5px;'>
															<path d='M0 0h24v24H0V0z' fill='none'/><path d='M18 16v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-5 0h-2v-2h2v2zm0-4h-2V8h2v4zm-1 10c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2z'/>
														</svg>
														FORMULARIO
														<small>PERMANENCIA</small>
													</h6>
												</div>
												<div class='panel-body panel-olimpiada' style='background-image: none!important;'>
													<small class='display-block'>Por favor llena el formulario de permanencia, si continuaras estudiando en colegio la gestión 2022.</small><br>
													<a class='panel-olimpiada--button' style='background: #fadb6a !important' href='$formulario_permanencia' target='_blank'>Llenar Formulario</a>
												</div>
											</div>
										</div>";
										}*/
										?>
										<div class='col-sm-12 col-md-6'>
											<div class='panel panel-animacion'>
												<div class='panel-heading panel-olimpiada--header'>
													<h6 class='panel-title'>
														BOLETÍN
														<small>TERCER TRIMESTRE</small>
													</h6>
												</div>
												<div class='panel-body panel-olimpiada'>
													<small class='display-block'>Ya puedes revisar tus notas del tercer trimestre.</small><br>
													<a class="panel-olimpiada--button" href="<?php //echo $notas_url 
																																		?>" target="_blank">Ver mi boletín</a>
												</div>
											</div>
										</div>

										<div name="falta" id="falta" class='col-sm-12'></div>
									</div>
								</div>
							</div>-->

							<div>
								<br>
								<h5 class="text-right">
									<img src="<?php echo base_url(); ?>/assets/images/logo1.png" alt="" width="65" height="75">
									<small class="display-block text-white">Gestión 2022</small>

								</h5>
								<br>
							</div>
						</div>

						<div class="breadcrumb-line">
							<ul class="breadcrumb">
								<li><a href=""><i class="icon-home2 position-left"></i> Principal</a></li>

								<li class="active">Materia</li>
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

								<div class="panel-heading bg-primary-300">
									<h6 class="panel-title">Subir Tareas</h6>
									<div class="heading-elements">
										<ul class="icons-list">
											<li><a data-action="reload"></a></li>
										</ul>
									</div>
								</div>

								<div class="panel-body">
									<div class="col-lg-12">
										<div class="col-sm-5">
											<div class="form-group">
												<label class="control-label">ESTUDIANTE:</label>
												<input name="Festud" placeholder="" class="form-control" id="Festud" readonly="true">
												<span class="help-block"></span>
											</div>
										</div>

										<input name="id_est" placeholder="" class="form-control" id="id_est" readonly="true">

										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label">GESTION:</label>
												<input class="form-control" type="text" name="Fgestion" id="Fgestion" readonly="true">
												<span class="help-block"></span>
											</div>
										</div>

										<div class="col-sm-3">
											<div class="form-group">
												<label class="control-label">NIVEL:</label>
												<input class="form-control" type="text" name="Fnivel" id="Fnivel" readonly="true">
												<span class="help-block"></span>
											</div>
										</div>

										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label">CURSO:</label>
												<input name="Fcurso" class="form-control" id="Fcurso" readonly="true">
												<span class="help-block"></span>
											</div>
										</div>
										<!--
										<div class="col-sm-3">
											<div class="form-group">
												<label class="control-label">MATERIA:</label>
												<select name="materia" class="form-control" id="materia" onchange="gesprof(this.value)">
												</select>
												<span class="help-block"></span>
											</div>
										</div>

										<div class="col-sm-3">
											<div class="form-group">
												<label class="control-label">PROFESOR:</label>
												<select name="prof" class="form-control" id="prof" onchange="gestemas(this.value);get_temas()">
												</select>
												<span class="help-block"></span>
											</div>
										</div>

										<div class="col-sm-2" name="correo" id="correo"></div>
									-->

										<!--<div class="col-md-2">
											<div class="form-group">
												<a class="btn bg-info-400" id="btnpreinscrip" href="<php echo $preinscripcion ?>" target="_blank"><i class="icon icon-file-pdf"></i>&nbsp; Form Preinscripcion</a>
											</div>
										</div>-->

									</div>

									<!-- <div class="row">
										<div class="col-lg-12">
											<div class="col-md-3">
												<div class="form-group">
													<button class="btn  bg-danger-400" onclick="export_pdf()" id="btnrude"><i class="icon icon-file-pdf"></i>&nbsp;Imprimir RUDE</button>
												</div>
											</div>

											<div class="col-md-3">
												<div class="form-group">
													<button class="btn  bg-primary-400" onclick="print_ctto()" id="btnctto"><i class="icon icon-file-pdf"></i>&nbsp;Imprimir CTTO</button>
												</div>
											</div>

											<div class="col-md-3">
												<div class="form-group">
													<a class="btn bg-warning-400" id="btnreglamento" href="<?php echo base_url() ?>assets/pdf/reglamento-interno.pdf" target="_blank"><i class="icon icon-file-pdf"></i>&nbsp; Reglamento Interno</a>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<a class="btn bg-success-400" id="btnpermanencia" href="<?php echo $permanencia_form; ?>" target="_blank"><i class="icon icon-file-pdf"></i>&nbsp; Form Permanencia</a>
												</div>
											</div>
										</div>
									</div> -->
									<div class="row">
										<div class="col-lg-12">
											<h6 style="text-align: center;">Dudas o preguntas, mandar un mensaje a través de Whatsapp al número: <a href="https://api.whatsapp.com/send?phone=59179303018" target="_blank">79303018</a></h6>
										</div>
									</div>
									<!--
									<div class="col-sm-12 alert alert-warning">
										<p>Los archivos que subas deben ser menores a 30 Mb, si es un pdf - <a href="https://smallpdf.com/es/comprimir-pdf" target="_blank">Reducir PDF</a></p>
									</div>

									<div class="">
										<table id="table_mat" class="table datatable-select-basic table-bordered " cellspacing="0" width="100%">
											<thead class="bg-primary-300">
												<tr>
													<th>#</th>
													<th>ESTADO</th>
													<th>MATERIA</th>
													<th>PROFESOR</th>
													<th>TEMA</th>
													<th>FECHA ENTREGA</th>
													<th>FECHA SUBIDA</th>
													<th>HORA SUBIDA</th>
													<th>ACCION</th>
												</tr>
											</thead>
											<tbody>
											</tbody>

											<tfoot class="bg-primary-300">
												<tr>
													<th>#</th>
													<th>ESTADO</th>
													<th>MATERIA</th>
													<th>PROFESOR</th>
													<th>TEMA</th>
													<th>FECHA ENTREGA</th>
													<th>FECHA SUBIDA</th>
													<th>HORA SUBIDA</th>
													<th>ACCION</th>
												</tr>
											</tfoot>
										</table>
									</div>
								-->

								</div>
							</div>
						</div>

						<!-- Footer -->

						<!-- /footer -->

					</div>
					<!-- /content area -->


				</div>
				<!-- /main content -->

			</div>
			<!-- /page content -->

		</div>

		<div class="footer text-muted">
			&copy; 2022 .<a href="#">Sistema de Control Académico "DON BOSCO"</a> by <a href="donboscosucre.edu.bo" target="_blank">Departamento de Informatica </a>
		</div>
		<!-- /page container -->
</body>

<script type="text/javascript">
	var tmateria;
	var save_method;
	var _global_idcur = "";

	$(document).ready(function() {
		Get_estu();
		materias();
		document.getElementById('id_est').style.display = 'none';
		tarea_faltante();
	});

	function generar() {
		var id = document.getElementById('Fgestion').value + "-" + document.getElementById('Fnivel').value + "-" + document.getElementById('Fcurso').value;

		var url = "<?php echo site_url('Estudiantes_su_contr/generar'); ?>/" + id;

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

	function tarea_faltante() {
		var contenido = "";

		$.ajax({
			url: "<?php echo site_url('Estudiantes_su_contr/tarea_faltante'); ?>",
			type: "POST",
			data: "",
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					i = 0;
					j = -1;
					data.materia[-1] = "";
					// alert(data.docente);  
					nu = 0;
					data.materia.forEach(function(item) {
						//alert(data.materia[i]+"=="+data.materia[j]);
						if (data.materia[i] != data.materia[j]) {
							contenido = contenido + "<div class='col-sm-3'>";
							contenido = contenido + "<div class='panel '>";
							contenido = contenido + "<div class='panel-heading bg-warning-300'>";
							contenido = contenido + "<h6 class='panel-title'>" + data.materia[i] + "</h6>";
							contenido = contenido + "</div>";
							contenido = contenido + "<div class='panel-body'>";

							nu = 0;
						}
						nu++;
						contenido = contenido + "<small class='display-block'>" + nu + ".- TEMA: " + data.tema[i] + "</small>";
						contenido = contenido + "<small class='display-block'>PROF: " + data.porf[i] + "</small>";
						contenido = contenido + "<small class='display-block'>FECHA DE ENTREGA: " + data.fecha[i] + "</small>  <hr>";

						if (data.materia[i] != data.materia[i + 1]) {

							//alert(data.materia[i]+"=="+data.materia[j]);
							contenido = contenido + "</div>";
							contenido = contenido + "</div>";
							contenido = contenido + "</div>";
						}
						i++;
						j++;
					});
					//alert(contenido);
					document.getElementById('falta').innerHTML = contenido;
					// document.getElementById('nivel').innerHTML=options;	
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	function adenda() {
		var url = "<?php echo site_url('Estudiantes_su_contr/print_cpse'); ?>/";

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

	function compromiso() {
		var url = "<?php echo site_url('Estudiantes_su_contr/compromiso'); ?>/";

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

	function temas(cat) {
		if (cat == 'T') {
			document.getElementById('tem').style.display = 'none';
			document.getElementById('temas').style.display = 'block';
			var data1 = {
				"table": "tema",
				"nivel": document.getElementById('Fnivel').value,
				"curso": document.getElementById('Fcurso').value,
				"materia": document.getElementById('materia').value,
				"id_prof": document.getElementById('Fidprofe').value,
				"gestion": document.getElementById('Fgestion').value,
			};

			$.ajax({

				url: "<?php echo site_url('Estudiantes_su_contr/temas'); ?>",
				type: "POST",
				data: data1,
				dataType: "JSON",
				success: function(data) //cargado de datos del registro 
				{
					if (data.status) {
						i = 0;
						//alert("asas");
						options = "";
						data.data.forEach(function(item) {

							options = options + "<option value='" + data.data[i] + "'>" + data.data1[i] + "</option>";
							i++;
						});

						document.getElementById('ttema').innerHTML = options;
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					// alert('No puede obtener codigo nuevo, para el registro');
				}
			});

		} else {
			document.getElementById('tem').style.display = 'block';
			document.getElementById('temas').style.display = 'none';

		}

	}

	function cerrar() {
		var url = "<?php echo site_url('Estudiantes_su_contr/ajax_cerrar') ?>";
		//alert(url);

		$.ajax({
			url: url,
			type: "POST",
			data: {},
			dataType: "JSON",
			success: function(data) {

			}
		});
		var enlace = "<?php echo  base_url(); ?>LoginE";
		window.location.replace(enlace);

	}

	function add_mat() //CARGA EL MODAL
	{
		save_method = 'add';
		$('#form')[0].reset(); // reset form on modals
		$('.form-group').removeClass('has-error'); // clear error class
		$('.help-block').empty(); // clear error string
		// document.getElementById('loadimg').src=temp;

		var gestion = document.getElementById('Fgestion').value;
		document.getElementById('gestion').value = gestion;

		$('#modal_form').modal('show'); // show bootstrap modal
		$('.modal-title').text('ADICIONAR PROFESOR'); // Set Title to Bootstrap modal title
		$('#btnSave').text('Guardar');
		//alert(document.getElementById('Fgestion').value);

	}

	//obtener codigo nuevo para el registro
	function get_idcod() {
		//envio de valores
		var data1 = {
			"table": "materia",
			"cod": "MAT-",
		};

		$.ajax({

			url: "<?php echo site_url('Estudiantes_su_contr/ajax_get_id'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					//datos recuperados
					document.getElementById('newcod').value = data.codgen;
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	function Get_estu() {
		//alert();
		var data1 = {
			"table": "curso",
			"lvl": "",
		};
		$.ajax({

			url: "<?php echo site_url('Estudiantes_su_contr/Get_estu'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{

				if (data.status) {
					//alert(data.data[2]);

					document.getElementById('Festud').value = data.data[0];
					document.getElementById('id_est').value = data.data[1];
					document.getElementById('Fgestion').value = data.data[2];
					document.getElementById('Fnivel').value = data.data[3];
					document.getElementById('Fcurso').value = data.data[4];
					//alert(data.data[5]);
					if (data.data[5] === "PM") {

						options = '<a class="btn btn-sm btn-primary"  title="Descargar " href="http://181.115.156.38/donbosco/Estudiantes_su_contr/descarga_correo/' + data.data[6] + '.pdf"><i class="glyphicon glyphicon-download"></i>CORREOS</a>';
						document.getElementById('correo').innerHTML = options;
					}
					/*if(data.data[5]==="ST" || data.data[5]==="PT") 
					{

						document.getElementById("adendas").style.visibility = "none";
						document.getElementById('compromiso').style.display = 'block';
					}   
					if(data.data[5]==="SM" || data.data[5]==="PM") 
					{
						document.getElementById('compromiso').style.display = 'none';
						document.getElementById("adendas").style.visibility = "block";

					}  */
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	function getcole(id) {


		var data1 = {
			"table": "curso",
			"lvl": id,
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
					document.getElementById('colegio').value = data.data[0];
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	function getcurso(id) {
		var options = "<option value=''></option>";
		var data1 = {
			"table": "curso",
			"nivel": id,
		};
		// alert(id);
		$.ajax({

			url: "<?php echo site_url('Estudiantes_su_contr/ajax_get_curso'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				var i = -1;
				if (data.status) {

					//alert(data.data[0]);
					data.data.forEach(function(item) {
						i++;
						options = options + "<option value='" + data.data1[i] + "'>" + item + "</option>";
					});
					document.getElementById('curso').innerHTML = options;
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	function gestemas(id_prof) {
		var options = "<option value=''></option>";

		var data1 = {
			"id_mat": document.getElementById('materia').value,
			"id_prof": id_prof,
		};

		//alert(document.getElementById('materia').value);
		$.ajax({

			url: "<?php echo site_url('Estudiantes_su_contr/ajax_temas'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				var i = -1;
				if (data.status) {
					data.data.forEach(function(item) {
						i++;
						options = options + "<option value='" + data.data1[i] + "'>" + item + "</option>";
					});
					if (document.getElementById('tema')) {
						document.getElementById('tema').innerHTML = options;
					}
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	function gesprof(id_mat) {
		var options = "<option value=''></option>";

		var data1 = {
			"id_mat": id_mat,
		};

		// alert(id);
		$.ajax({

			url: "<?php echo site_url('Estudiantes_su_contr/ajax_profesor'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				var i = -1;
				if (data.status) {

					//alert(data.data[0]);
					data.data.forEach(function(item) {
						i++;
						options = options + "<option value='" + data.data1[i] + "'>" + item + "</option>";
					});
					document.getElementById('prof').innerHTML = options;
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	function materias() {
		var options = "<option value=''></option>";

		var data1 = {
			"table": "curso",
			"nivel": document.getElementById('Fnivel').value,
			"curso": document.getElementById('Fcurso').value,
		};

		// alert(id);
		$.ajax({

			url: "<?php echo site_url('Estudiantes_su_contr/ajax_materias'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				var i = -1;
				if (data.status) {

					//alert(data.data[0]);
					data.data.forEach(function(item) {
						i++;
						options = options + "<option value='" + data.data1[i] + "'>" + item + "</option>";
					});
					document.getElementById('materia').innerHTML = options;
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	function getmateria(id) {
		var options = "<option value=''></option>";
		var nivel = document.getElementById('Fnivel').value;
		var data1 = {
			"table": "curso",
			"curso": id,
			"nivel": nivel,
		};
		// alert(id);
		$.ajax({

			url: "<?php echo site_url('Estudiantes_su_contr/ajax_get_materia'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				var i = -1;
				if (data.status) {

					//alert(data.data[0]);
					data.data.forEach(function(item) {
						i++;
						options = options + "<option value='" + data.data1[i] + "'>" + item + "</option>";
					});
					document.getElementById('materia').innerHTML = options;
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	function getcurso2(id) {
		var options = "<option value=''></option>";
		var data1 = {
			"table": "curso",
			"idcur": id,
		};

		$.ajax({

			url: "<?php echo site_url('Estudiantes_su_contr/ajax_get_curso2'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					document.getElementById('curso').value = data.data[0];
					document.getElementById('corto').value = data.data[1];
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function boletin() {



		var url = "<?php echo site_url('Estudiantes_su_contr/boletines'); ?>/";

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

	function getcorto(cur) {
		var data1 = {
			"table": "curso",
			"nivel": document.getElementById('nivel').value,
			"curso": cur,
		};

		$.ajax({

			url: "<?php echo site_url('Estudiantes_su_contr/ajax_get_corto'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					document.getElementById('corto').value = data.data[0];
					document.getElementById('idcur').value = data.data[1];
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	function getcampo() {
		var a = 'COMUNIDAD Y SOCIEDAD';
		var b = 'CIENCIA TECNOLOGIA Y PRODUCCION';
		var c = 'VIDA TIERRA TERRITORIO';
		var d = 'COSMOS Y PENSAMIENTO';
		var options = "<option value=''></option>";
		options = options + "<option value='" + a + "'>" + a + "</option>";
		options = options + "<option value='" + b + "'>" + b + "</option>";
		options = options + "<option value='" + c + "'>" + c + "</option>";
		options = options + "<option value='" + d + "'>" + d + "</option>";
		document.getElementById('campo').innerHTML = options;
	}

	function getarea(campo) {

		var a = 'COMUNICACION Y LENGUAJES';
		var b = 'CIENCIAS SOCIALES';
		var c = 'EDUCACION FÍSICA Y DEPORTES';
		var d = 'EDUCACION MUSICAL';
		var e = 'ARTES PLÁSTICAS Y VISUALES';
		var f = 'MATEMÁTICAS';
		var g = 'TÉCNICA Y TECNOLÓGICA GENERAL';
		var h = 'CIENCIAS NATURALES';
		var i = 'VALORES, ESPIRITUALIDAD Y RELIGIONES';
		//--------------------------------------------------
		var j = 'LENGUA CASTELLANA Y ORIGINARIA';
		var k = 'LENGUA EXTRANJERA';
		var l = 'CIENCIAS NATURALES:BIOLOGIA - GEOGRAFIA';
		var m = 'COSMOVISIONES, FILOSOFÍA Y SICOLOGÍA';
		//--------------------------------------------------
		var n = 'LENGUA EXTRANJERA';
		var o = 'TÉCNICA Y TECNOLÓGICA ESPECIALIDAD';
		var p = 'CIENCIAS NATURALES:FISICA - QUIMICA';


		var x = campo;
		var y = document.getElementById('nivel').value;
		var z = document.getElementById('curso').value;
		var options = "<option value=''></option>";

		if ((y == "PRIMARIA MAÑANA") || (y == "PRIMARIA TARDE")) {
			if (x == 'COMUNIDAD Y SOCIEDAD') {
				options = options + "<option value='" + a + "'>" + a + "</option>";
				options = options + "<option value='" + b + "'>" + b + "</option>";
				options = options + "<option value='" + c + "'>" + c + "</option>";
				options = options + "<option value='" + d + "'>" + d + "</option>";
				options = options + "<option value='" + e + "'>" + e + "</option>";
			}
			if (x == 'CIENCIA TECNOLOGIA Y PRODUCCION') {
				options = options + "<option value='" + f + "'>" + f + "</option>";
				options = options + "<option value='" + g + "'>" + g + "</option>";
			}
			if (x == 'VIDA TIERRA TERRITORIO') {
				options = options + "<option value='" + h + "'>" + h + "</option>";
			}
			if (x == 'COSMOS Y PENSAMIENTO') {
				options = options + "<option value='" + i + "'>" + i + "</option>";
			}
		}

		if ((y == "SECUNDARIA MAÑANA") || (y == "SECUNDARIA TARDE")) {
			if ((z == 'PRIMERO A') || (z == 'PRIMERO B') || (z == 'SEGUNDO A') || (z == 'SEGUNDO B')) {
				if (x == 'COMUNIDAD Y SOCIEDAD') {
					options = options + "<option value='" + j + "'>" + j + "</option>";
					options = options + "<option value='" + k + "'>" + k + "</option>";
					options = options + "<option value='" + b + "'>" + b + "</option>";
					options = options + "<option value='" + c + "'>" + c + "</option>";
					options = options + "<option value='" + d + "'>" + d + "</option>";
					options = options + "<option value='" + e + "'>" + e + "</option>";
				}
				if (x == 'CIENCIA TECNOLOGIA Y PRODUCCION') {
					options = options + "<option value='" + f + "'>" + f + "</option>";
					options = options + "<option value='" + g + "'>" + g + "</option>";
				}
				if (x == 'VIDA TIERRA TERRITORIO') {
					options = options + "<option value='" + l + "'>" + l + "</option>";

				}
				if (x == 'COSMOS Y PENSAMIENTO') {
					options = options + "<option value='" + m + "'>" + m + "</option>";
					options = options + "<option value='" + i + "'>" + i + "</option>";
				}
			} else {
				if (x == 'COMUNIDAD Y SOCIEDAD') {
					options = options + "<option value='" + j + "'>" + j + "</option>";
					options = options + "<option value='" + k + "'>" + k + "</option>";
					options = options + "<option value='" + b + "'>" + b + "</option>";
					options = options + "<option value='" + c + "'>" + c + "</option>";
					options = options + "<option value='" + d + "'>" + d + "</option>";
					options = options + "<option value='" + e + "'>" + e + "</option>";
				}
				if (x == 'CIENCIA TECNOLOGIA Y PRODUCCION') {
					options = options + "<option value='" + f + "'>" + f + "</option>";
					options = options + "<option value='" + o + "'>" + o + "</option>";
				}
				if (x == 'VIDA TIERRA TERRITORIO') {
					options = options + "<option value='" + l + "'>" + l + "</option>";
					options = options + "<option value='" + p + "'>" + p + "</option>";
				}
				if (x == 'COSMOS Y PENSAMIENTO') {
					options = options + "<option value='" + m + "'>" + m + "</option>";
					options = options + "<option value='" + i + "'>" + i + "</option>";
				}
			}
		}

		document.getElementById('area').innerHTML = options;
	}

	function export_pdf() {
		var id_est = document.getElementById('id_est').value;
		var gestion = 2022;
		var idins = id_est + "-" + gestion + "-";
		var url = "<?php echo site_url('Est_Inscrip_contr/print_rude'); ?>/" + idins;


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


	function print_ctto() {
		var id_est = document.getElementById('id_est').value;
		var gestion = 2022;

		var url = "<?php echo site_url('inscrip_est_contr/print_ctto'); ?>/" + gestion + "-" + id_est + "-";

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

	function getprofe() {
		var options = "<option value=''></option>";
		var data1 = {
			"table": " ",
		};

		$.ajax({

			url: "<?php echo site_url('Estudiantes_su_contr/ajax_get_profe'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					var i = 0;
					//alert(data.data[0]);
					data.data.forEach(function(item) {
						options = options + "<option value='" + data.data1[i] + "'>" + item + "</option>";
						i++;
					});
					document.getElementById('profe').innerHTML = options;
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	function getprofe2(id) {
		var data1 = {
			"table": "profesor",
			"idprof": id,
		};

		$.ajax({

			url: "<?php echo site_url('Estudiantes_su_contr/ajax_get_profe2'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					document.getElementById('profe').value = data.data[0];
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	function getidprof(nomb) {
		var data1 = {
			"table": "profesor",
			"profe": document.getElementById('profe').value,
		};

		$.ajax({

			url: "<?php echo site_url('Estudiantes_su_contr/ajax_get_idprofe'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					document.getElementById('idprof').value = data.data[0];
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

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

	function msg_guardar() {
		swal({
			title: "Guardado!",
			text: "Registro Guardado, Satisfactoriamente!",
			confirmButtonColor: "#66BB6A",
			type: "success"
		});

	}

	function openFile(element) {

		var x = new FileReader();
		var file = element.files[0];
		var reader = new FileReader();
		reader.onloadend = function() {
			document.getElementById('txtar').innerHTML = reader.result;
			document.getElementById('loadimg').src = reader.result;
		}
		reader.readAsDataURL(file);

	}


	//guardado de usuario
	function save_profesor() {
		var id, materia, area, nivel, idprof, gestion, curso;
		var error = false;
		var url;

		//alert(save_method);


		if (save_method == 'add') {
			url = "<?php echo site_url('Estudiantes_su_contr/ajax_set_profesor'); ?>";
		} else {
			url = "<?php echo site_url('Estudiantes_su_contr/ajax_update_profeso'); ?>";
		}

		id = document.getElementById('id').value;
		materia = document.getElementById('materia').value;
		curso = document.getElementById('curso').value;
		nivel = document.getElementById('nivel').value;
		idprof = document.getElementById('profe').value;
		gestion = document.getElementById('gestion').value;

		if (!error) {
			var data1 = {
				"id": id,
				"materia": materia,
				"curso": curso,
				"nivel": nivel,
				"idprof": idprof,
				"gestion": gestion,
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

				}
			});

		}

	}

	function delete_temas(id) {
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
						url: "<?php echo site_url('Estudiantes_su_contr/ajax_delete_temas') ?>/" + id,
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

	function delete_tareas(id) {
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
						url: "<?php echo site_url('Estudiantes_su_contr/ajax_delete_tareas') ?>/" + id,
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

	function edit_profesor(id) {
		//alert(id);
		save_method = 'update';
		$('#btnSave').text('Modificar');

		$.ajax({
			url: "<?php echo site_url('Estudiantes_su_contr/ajax_edit_profesor'); ?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				var codigo = data.codigo;
				codigos = codigo.split('-');
				$('[name="id"]').val(data.id_asg_prof);
				$('[name="materia"]').val(data.id_asg_mate);
				$('[name="profe"]').val(data.id_prof);
				$('[name="nivel"]').val(codigos[1]);
				$('[name="curso"]').val(codigos[4]);
				// alert(data.id_asg_mate);
				$('[name="gestion"]').val(data.gestion);
				getarea(data.id_asg_mate);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				swal({
					title: "Información",
					text: "Error al obtener los dartos",
					confirmButtonColor: "#2196F3",
					type: "info"
				});
			}
		});

		$('#modal_form').modal('show');

	}

	function reload_table() {
		tmateria.ajax.reload(null, false);

	}

	//ventana para el pdf
	/*function export_pdf()
	{
	
		
	    var url = "<?php echo site_url('Prof_profesores_contr/print'); ?>";

	    $.ajax({
	            url :url,
	            type: "POST",
	            ContentType:"application/pdf",
	            success: function(data)
	            {
	                window.open(url,"menubar=no,scrollbars=yes,statubar=no,titlebar=yes,width=500,height=500"); 

	                swal({
		                    title: "Archivo",
		                    text: "Archivo Generado Satisfactoriamente!!!",
		                    confirmButtonColor: "#66BB6A",
		                    type: "success"
		                });

	                
	            },
	            error: function (jqXHR, textStatus, errorThrown)
	            {
	                swal({
	                    title: "Archivo NO Generado",
	                    text: "Hubo un error, comuniquese con el administrador.",
	                    confirmButtonColor: "#2196F3",
	                    type: "error"
	                });
	            }
	        });	    
	}*/
	function getarea(id_mat) {
		// alert(id_mat);
		var data1 = {
			"table": "curso",
			"id_mat": id_mat,
		};

		$.ajax({

			url: "<?php echo site_url('Estudiantes_su_contr/ajax_get_area'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					//datos recuperados
					//	document.getElementById('Fgestion').value=data.data[0];
					document.getElementById('area').value = data.data[0];
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function get_temas() {

		var materia = document.getElementById('materia').value;
		var id_prof = document.getElementById('prof').value;
		var id = materia + "-" + id_prof;
		url1 = "<?php echo site_url('Estudiantes_su_contr/ajax_list_temas'); ?>/" + id;
		tmateria = $('#table_mat').DataTable({
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


	}

	function getestud() {

		var gestion = document.getElementById('Fgestion').value;
		var nivel = document.getElementById('Fnivel').value;
		var curso = document.getElementById('Fcurso').value;
		var id = gestion + "-" + nivel + "-" + curso;
		getcurso(nivel);
		getcole(nivel);

		// alert(codigos[4]);
		// alert("");
		url1 = "<?php echo site_url('Estudiantes_su_contr/ajax_list_profesor'); ?>/" + id;
		tmateria = $('#table_mat').DataTable({
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


	}
</script>


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary-300">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title">Formulario de Registro de Profesores</h3>
			</div>
			<div class="modal-body form">
				<form action="#" id="form" class="form-horizontal">
					<input type="hidden" value="" name="id" />
					<div class="form-body">
						<div class="alert alert-primary no-border">
							Seleccionar Nivel y Curso.
						</div>
						<div class="row">
							<input type="hidden" value="" id="id" name="id">
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label col-md-3">NIVEL</label>
									<div class="col-md-9">
										<Select class="form-control" name="nivel" id="nivel" onchange="getcole(this.value);getcurso(this.value);">
										</Select>
										<span class="help-block"></span>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label class="control-label col-md-3">COLEGIO</label>
									<div class="col-md-9">
										<input name="colegio" placeholder="colegio" class="form-control" type="text" readonly="true" id="colegio">
										<span class="help-block"></span>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label col-md-3">CURSO</label>
									<div class="col-md-9">
										<Select class="form-control" name="curso" id="curso" onchange="getmateria(this.value)">
										</Select>
										<span class="help-block"></span>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label col-md-3">GESTION</label>
									<div class="col-md-9">
										<input name="gestion" class="form-control" type="text" readonly="true" id="gestion">
										<span class="help-block"></span>
									</div>
								</div>
							</div>



						</div>

						<div class="alert alert-primary no-border">
							Materia:
						</div>
						<div class="row">
							<div class="col-md-6">

							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-2">AREA</label>
									<div class="col-md-10">
										<input type="text" id='area' class="form-control" readonly="true">
										<span class="help-block"></span>
									</div>
								</div>
							</div>

						</div>


						<div class="alert alert-primary no-border">
							Asignar a Profesor
						</div>
						<div class="row">
							<div class="col-md-9">
								<div class="form-group">
									<label class="control-label col-md-2">PROFESOR:</label>
									<div class="col-md-10">
										<select class="form-control" name="profe" id="profe">
										</select>
										<span class="help-block"></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer bg-primary-300">
				<br>
				<button type="button" id="btnSave" onclick="save_profesor()" class="btn bg-grey-600">Save</button>
				<button type="button" class="btn bg-danger-300" data-dismiss="modal">Cancel</button>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
