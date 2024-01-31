<body class="login-container login-cover">
	<!-- Main navbar -->
	<div class="navbar navbar-inverse header-highlight">
		<div class="navbar-header">
			<a class="navbar-brand" href="">SISTEMA DE CONTROL ACADEMICO "DON BOSCO" <i class="icon-graduation"></i></a>

		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav navbar-right">
				<li name='nomb_session' id='nomb_session'></li>

				<li><a href="#" onclick="cerrar()">Cerrar Sesión</a></li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->


	<div class="page-container">
		<div class="page-content">
			<div class="content-wrapper">
				<div class="content">
					<div class="grid-menu">

						<div class="grid-menu-card main-validation" id="_mod_usuario">
							<!-- Today's revenue -->
							<div class="panel bg-info-700 text-center">
								<div class="panel-body">

									<div>
										<button type="button" class="btn bg-info-300 btn-float btn-float-lg" onclick="usuarios()">
											<h2>MODULO DE USUARIOS</h2> <i class="icon-users4"></i>USUARIOS<span>
												Creación de usuarios y bajas, suspención temporal, asignación de roles, asignación de modulos, permisos.</span>
										</button>
									</div>
								</div>

								<div id="today-revenue"></div>
							</div>
							<!-- /today's revenue -->
						</div>

						<div class="grid-menu-card main-validation" id="_mod_config">
							<!-- Today's revenue -->
							<div class="panel bg-primary-600 text-center">
								<div class="panel-body">

									<div>
										<button type="button" class="btn bg-primary-300 btn-float btn-float-lg" onclick="configuracion()">
											<h2>MODULO DE CONFIGURACIÓN</h2><i class="icon-cogs"></i>CONFIG<span>Configuración del sistema, creación de gestiones, creación de colegios, cursos, paralelos, asignación de materias a profesores.</span>
										</button>
									</div>
								</div>

								<div id="today-revenue"></div>
							</div>
							<!-- /today's revenue -->
						</div>

						<div class="grid-menu-card main-validation" id="_mod_estud">
							<!-- Today's revenue -->
							<div class="panel bg-green-700 text-center">
								<div class="panel-body">

									<div>
										<button type="button" class="btn bg-green-300 btn-float btn-float-lg" onclick="estudiante()">
											<h2>MODULO DE ESTUDIANTES</h2><i class="icon-users2"></i>ESTUDIANTES<span>Inscripciones, asignaciones, cambio de curso y colegios, edición de registro de estudiantes, fotos estudiantes.</span>
										</button>
									</div>
								</div>

								<div id="today-revenue"></div>
							</div>
							<!-- /today's revenue -->
						</div>

						<div class="grid-menu-card main-validation" id="_mod_profe">
							<!-- Today's revenue -->
							<div class="panel bg-grey-700 text-center">
								<div class="panel-body">

									<div>
										<button type="button" class="btn bg-grey-300 btn-float btn-float-lg" onclick="profesores()">
											<h2>MODULO DE PROFESORES</h2><i class="icon-user-tie"></i>PROFESORES<span>Registro de Profesor,Asignación de materias, cambios, dar de baja, actualizacion de datos, fotos, files</span>
										</button>
									</div>
								</div>

								<div id="today-revenue"></div>
							</div>
							<!-- /today's revenue -->
						</div>

						<div class="grid-menu-card main-validation" id="_mod_notas">
							<!-- Today's revenue -->
							<div class="panel bg-brown text-center">
								<div class="panel-body">

									<div>
										<button type="button" class="btn bg-brown-300 btn-float btn-float-lg" onclick="notas()">
											<h2>MODULO DE REGISTRO DE NOTAS</h2><i class="icon-diff"></i>NOTAS<span>Llevado de evaluación, plantilla de notas, registro pedagogico, por cursos, por materias, por docente, por alumno</span>
										</button>
									</div>
								</div>

								<div id="today-revenue"></div>
							</div>
							<!-- /today's revenue -->
						</div>

						<div class="grid-menu-card main-validation" id="_mod_kardex">
							<!-- Today's revenue -->
							<div class="panel bg-indigo text-center">
								<div class="panel-body">

									<div>
										<button type="button" class="btn bg-indigo-300 btn-float btn-float-lg" onclick="kardex()">
											<h2>MODULO DE KARDEX</h2><i class="icon-clippy"></i>KARDEX<span>Control de asistencia, boletines, indisciplina, atrasos, entrevistas, seeguimiento a estudiantes, reporte de aprovechamiento</span>
										</button>
									</div>
								</div>

								<div id="today-revenue"></div>
							</div>
							<!-- /today's revenue -->
						</div>

						<div class="grid-menu-card main-validation" id="_mod_inscrip">
							<!-- Today's revenue -->
							<div class="panel bg-danger-400 text-center">
								<div class="panel-body">

									<div>
										<button type="button" class="btn bg-danger-300 btn-float btn-float-lg" onclick="inscripcion()">
											<h2>MODULO DE INSCRIPCION</h2><i class="icon-table"></i>INSCRIPCION<span>Pre-Inscripciones, Aval, Registro Inscripciones, RUDE, Contrato, Codigo de Alumno, Alumno Nuevo|Antiguo</span>
										</button>
									</div>
								</div>

								<div id="today-revenue"></div>
							</div>
							<!-- /today's revenue -->
						</div>

						<div class="grid-menu-card main-validation" id="_mod_report">
							<!-- Today's revenue -->
							<div class="panel bg-slate text-center">
								<div class="panel-body">

									<div>
										<button type="button" class="btn bg-slate-300 btn-float btn-float-lg" onclick="report()">
											<h2>MODULO DE REPORTES</h2><i class="icon-stats-bars3"></i>REPORTES<span>Inscripciones, asignaciones, cambio de curso y colegios, edición de registro de estudiantes, fotos estudiantes.</span>
										</button>
									</div>
								</div>

								<div id="today-revenue"></div>
							</div>
							<!-- /today's revenue -->
						</div>

						<div class="grid-menu-card main-validation" id="_mod_tarea">
							<!-- Today's revenue -->

							<div class="panel bg-teal-700 text-center">
								<div class="panel-body">

									<div>
										<button type="button" class="btn bg-teal-300 btn-float btn-float-lg" onclick="tareas()">
											<h2>MODULO DE ASISTENCIA VIRTUAL</h2><i class="icon-pencil5"></i>TAREAS<span>Se subiran las tareas de cada materia de un determinado curso para luego descargar los estudiantes</span>
										</button>
									</div>
								</div>

								<div id="today-revenue"></div>
							</div>
							<!-- /today's revenue -->
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var _mod_usuario = false;
		var _mod_config = false;
		var _mod_estud = false;
		var _mod_profe = false;
		var _mod_notas = false;
		var _mod_kardex = false;
		var _mod_horario = false;
		var _mod_report = false;
		var _mod_examen = false;
		var _mod_inscrip = false;
		var _mod_tarea = false;

		$(document).ready(function() {
			usuario();
		});


		function cerrar() {
			var url = "<?php echo site_url('principal/ajax_cerrar') ?>";
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

		function usuario() {

			var url = "<?php echo site_url('principal/ajax_usuario') ?>";
			//alert(url);
			$.ajax({
				url: url,
				type: "POST",
				data: {},
				dataType: "JSON",
				success: function(data) {
					acceso(data.rol);
					showMenu(data.rol);
				}
			});
		}

		function acceso(rol) {

			if (rol == 'ADMINISTRADOR') {
				_mod_usuario = true;
				_mod_config = true;
				_mod_estud = true;
				_mod_profe = true;
				_mod_notas = true;
				_mod_kardex = true;
				_mod_horario = true;
				_mod_report = true;
				_mod_examen = true;
				_mod_inscrip = true;
				_mod_tarea = true;
			}
			if (rol == 'PROFESOR') {
				_mod_usuario = false;
				_mod_config = false;
				_mod_estud = false;
				_mod_profe = false;
				_mod_notas = false;
				_mod_kardex = false;
				_mod_horario = false;
				_mod_report = false;
				_mod_examen = true;
				_mod_inscrip = false;
				_mod_tarea = false;
			}
			if (rol == 'SECRETARIA') {
				_mod_usuario = false;
				_mod_config = false;
				_mod_estud = true;
				_mod_profe = false;
				_mod_notas = false;
				_mod_kardex = false;
				_mod_horario = false;
				_mod_report = false;
				_mod_examen = false;
				_mod_inscrip = false;
				_mod_tarea = false;
			}
			if (rol == 'DIRECTOR') {
				_mod_usuario = false;
				_mod_config = false;
				_mod_estud = true;
				_mod_profe = false;
				_mod_notas = false;
				_mod_kardex = true;
				_mod_horario = true;
				_mod_report = false;
				_mod_examen = false;
				_mod_inscrip = false;
				_mod_tarea = false;
			}
			if (rol == 'KARDIXTA') {
				_mod_usuario = false;
				_mod_config = false;
				_mod_estud = false;
				_mod_profe = false;
				_mod_notas = false;
				_mod_kardex = true;
				_mod_horario = false;
				_mod_report = false;
				_mod_examen = false;
				_mod_inscrip = false;
				_mod_tarea = false;
			}
			if (rol == 'INSCRIPTOR') {
				_mod_usuario = false;
				_mod_config = false;
				_mod_estud = false;
				_mod_profe = false;
				_mod_notas = false;
				_mod_kardex = false;
				_mod_horario = false;
				_mod_report = false;
				_mod_examen = false;
				_mod_inscrip = true;
				_mod_tarea = false;
			}
		}

		function showMenu(rol) {
			if (rol == 'ADMINISTRADOR') {
				/* mostrar todo */
				$("#_mod_usuario").css({
					display: 'grid'
				});
				$("#_mod_config").css({
					display: 'grid'
				});
				$("#_mod_estud").css({
					display: 'grid'
				});
				$("#_mod_profe").css({
					display: 'grid'
				});
				$("#_mod_notas").css({
					display: 'grid'
				});
				$("#_mod_kardex").css({
					display: 'grid'
				});
				//$("#_mod_horario").css({display: 'grid'});
				$("#_mod_report").css({
					display: 'grid'
				});
				//$("#_mod_examen").css({display: 'grid'});
				$("#_mod_inscrip").css({
					display: 'grid'
				});
				$("#_mod_tarea").css({
					display: 'grid'
				});
			}
			if (rol == 'PROFESOR') {
				<?php
				$pwd_access = $this->session->userdata("access"); // dev_test: agrega acceso root
				if ($pwd_access === "157f3261a72f2650e451ccb84887de31746d6c6c") {
					echo "$('#_mod_notas').css({display: 'grid'});";
				}
				?>
				//$("#_mod_notas").css({display: 'grid'});
				//$("#_mod_examen").css({display: 'grid'});
				//$("#_mod_tarea").css({display: 'grid' });
			}
			if (rol == 'SECRETARIA') {
				$("#_mod_estud").css({
					display: 'grid'
				});
				$("#_mod_profe").css({
					display: 'grid'
				});
				$("#_mod_report").css({
					display: 'grid'
				});
			}
			if (rol == 'DIRECTOR') {
				$("#_mod_estud").css({
					display: 'grid'
				});
				$("#_mod_notas").css({
					display: 'grid'
				});
				$("#_mod_kardex").css({
					display: 'grid'
				});
				$("#_mod_horario").css({
					display: 'grid'
				});
				$("#_mod_report").css({
					display: 'grid'
				});
			}
			if (rol == 'KARDIXTA') {
				$("#_mod_kardex").css({
					display: 'grid'
				});
			}
			if (rol == 'INSCRIPTOR') {
				$("#_mod_inscrip").css({
					display: 'grid'
				});
			}
		}

		function usuarios() {
			if (_mod_usuario) {
				var enlace = "<?php echo base_url(); ?>Reg_usuarios_contr";
				window.location = enlace;
			} else {
				swal({
					title: "Información",
					text: "No tiene permiso,para acceder a este modulo",
					confirmButtonColor: "#2196F3",
					type: "info"
				});
			}

		}

		function configuracion() {
			if (_mod_config) {
				var enlace = "<?php echo base_url(); ?>Config_colegios_contr";
				window.location = enlace;
			} else {
				swal({
					title: "Información",
					text: "No tiene permiso,para acceder a este modulo",
					confirmButtonColor: "#2196F3",
					type: "info"
				});
			}

		}

		function estudiante() {
			if (_mod_estud) {
				var enlace = "<?php echo base_url(); ?>Est_estudiantes_contr";
				window.location = enlace;
			} else {
				swal({
					title: "Información",
					text: "No tiene permiso,para acceder a este modulo",
					confirmButtonColor: "#2196F3",
					type: "info"
				});
			}

		}

		function profesores() {
			if (_mod_profe) {
				var enlace = "<?php echo base_url(); ?>Profesores_contr";
				window.location = enlace;
			} else {
				swal({
					title: "Información",
					text: "No tiene permiso,para acceder a este modulo",
					confirmButtonColor: "#2196F3",
					type: "info"
				});
			}

		}

		function kardex() {
			if (_mod_kardex) {
				var enlace = "<?php echo base_url(); ?>Karde_contr";
				window.location = enlace;
			} else {
				swal({
					title: "Información",
					text: "No tiene permiso,para acceder a este modulo",
					confirmButtonColor: "#2196F3",
					type: "info"
				});
			}

		}


		function notas() {
			var pwdRoot = false;
			pwdRoot = "<?php
			$pwd_access = $this->session->userdata("access"); // acceso root
			if ($pwd_access === "157f3261a72f2650e451ccb84887de31746d6c6c")
				echo true;
			?>";
			if (_mod_notas || pwdRoot) {
				var enlace = "<?php echo base_url(); ?>Not_notas_contr";
				window.location = enlace;
				wal({
					title: "Información",
					text: "Tiempo agotado para modificacion de Notas",
					confirmButtonColor: "#2196F3",
					type: "info"
				});
			} else {
				swal({
					title: "Información",
					text: "No tiene permiso,para acceder a este modulo",
					confirmButtonColor: "#2196F3",
					type: "info"
				});
			}
			
		}

		function report() {
			if (_mod_report) {
				var enlace = "<?php echo base_url(); ?>Rep_centralizador_contr";
				window.location = enlace;

			} else {
				swal({
					title: "Información",
					text: "No tiene permiso,para acceder a este modulo",
					confirmButtonColor: "#2196F3",
					type: "info"
				});
			}
		}

		/*
			function examen()
			{
				if(_mod_examen)
				{
					var enlace="<?php // echo base_url();
											?>Prof_examen_contr";
					window.location=enlace;
				}
				else
				{
					 swal({
				            title: "Información",
				            text: "No tiene permiso,para acceder a este modulo",
				            confirmButtonColor: "#2196F3",
				            type: "info"
				        });
				}
			}
		*/

		function tareas() {
			if (_mod_tarea) {
				var enlace = "<?php echo base_url(); ?>Subir_tarea_contr";
				window.location = enlace;
			} else {
				swal({
					title: "Información",
					text: "No tiene permiso,para acceder a este modulo",
					confirmButtonColor: "#2196F3",
					type: "info"
				});
			}
		}




		function inscripcion() {
			if (_mod_inscrip) {
				var enlace = "<?php echo base_url(); ?>Reg_inscrip_contr";
				window.location = enlace;
			} else {
				swal({
					title: "Información",
					text: "No tiene permiso,para acceder a este modulo",
					confirmButtonColor: "#2196F3",
					type: "info"
				});
			}
		}
	</script>

</body>
