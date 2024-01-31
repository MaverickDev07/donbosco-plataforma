<body>
	<!-- Main navbar -->
	<div class="navbar bg-danger-400 ">
		<div class="navbar-header">
			<a class="navbar-brand text-white" href="<?php echo base_url(); ?>Principal">SISTEMA DE CONTROL ACADEMICO "DON BOSCO" <i class="icon-graduation"></i></a>

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
									<a href='<?php echo site_url('Est_estudiante_contr'); ?>'><i class="icon-user-lock"></i> <span>Alumnos</span></a>
								</li>

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
									<small class="display-block">Gestión 2022</small>

								</h5>
								<br>
							</div>
						</div>

						<div class="breadcrumb-line">
							<ul class="breadcrumb">
								<li><a href="Principal/index"><i class="icon-home2 position-left"></i> Principal</a></li>

								<li class="active">Cursos</li>
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
								<div class="panel-heading bg-info-400">
									<h6 class="panel-title">Inscripción</h6>
									<div class="heading-elements">
										<ul class="icons-list">
											<li><a data-action="reload"></a></li>
										</ul>
									</div>
								</div>

								<div class="panel-body">

									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label class="display-block">Fecha:</label>
												<input type="text" id="fechains" class="form-control" readonly="true">
											</div>
										</div>
										<div class="col-md-3">
											<div>
												<label class="display-block">Usuario:</label>
												<input type="text" id="user" class="form-control" readonly="true">
											</div>
										</div>
										<div class="col-md-12">
										</div>


										<div class="col-md-3">
											<div class="form-group">

												<button class="btn  bg-slate-400 hidden" onclick="guardarIns()" id="btnguardar"><i class="icon icon-file-pdf"></i>&nbsp;Guardar Inscripción</button>

											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">

												<button class="btn  bg-danger-400 hidden" onclick="export_pdf()" id="btnrude"><i class="icon icon-file-pdf"></i>&nbsp;Imprimir RUDE</button>

											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">

												<button class="btn  bg-primary-400 hidden" onclick="print_ctto()" id="btnctto"><i class="icon icon-file-pdf"></i>&nbsp;Imprimir CTTO</button>

											</div>
										</div>

									</div>
									<legend class="primary-400"></legend>
									<!-- inscripcion -->
									<form class="steps-validation" action="#">

										<div class="row">
											<div class="row">
												<div class="col-lg-12">
													<div class="col-lg-12">
														<div class="panel ">
															<div class="panel-heading bg-success">
																<h6 class="panel-title">UNIDAD EDUCATIVA</h6>
																<div class="heading-elements">
																	<ul class="icons-list">
																		<li><a data-action="reload"></a></li>
																	</ul>
																</div>
															</div>

															<div class="panel-body">
																<div class="row">
																	<div class="col-lg-12">
																		<legend class="text-bold">DATOS DE ANTERIOR GESTION</legend>
																		<div class="row">
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>Codigo SIE de la UE.:</label>
																					<input type="text" id='antsie' class="form-control">
																				</div>
																			</div>
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>Nombre Unidad Educativa:</label>
																					<input type="text" id='antunidadedu' class="form-control">
																				</div>
																			</div>
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>Curso Anterior: </label>
																					<input type="text" name="idcur" id="idcur" class="form-control" readonly="true">
																				</div>

																			</div>
																		</div>
																	</div>
																	<legend class="text-bold">REGISTRO DE LA UNIDAD EDUCATIVA</legend>
																	<div class="col-lg-12">

																		<div class="row">

																			<div class="col-md-3">
																				<div class="form-group">
																					<label>Nombre de la Unidad Educativa: <span class="text-danger">*</span></label>
																					<select id='unidedu' class="form-control required" onchange="selectUnid(this.value);selcole(this.value)">
																					</select>

																				</div>
																			</div>
																			<div class="col-md-3">
																				<div class="form-group">
																					<label>Dependencia de la Unidad Educativa: </label>
																					<input type="text" id="depend" class="form-control" readonly="true">
																				</div>
																			</div>
																			<div class="col-md-3">
																				<div class="form-group">
																					<label>Distrito Educativo: </label>
																					<input type="text" id="distrito" class="form-control" readonly="true">
																				</div>
																			</div>
																			<div class="col-md-3">
																				<div class="form-group">
																					<label>Código SIE de la Unidad: </label>
																					<div class="form-group">
																						<input type="text" id="sie" class="form-control" readonly="true">
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="col-lg-12">
																		<legend class="text-bold">Nivel AÑO/GRADO ESCOLARIDAD DEL ESTUDIANTE</legend>

																		<div class="row">
																			<div class="col-md-3">
																				<div class="form-group">
																					<label>Nivel: <span class="text-danger">*</span></label>
																					<div class="form-group">
																						<!-- <select id="niveles" class="form-control required" onchange="selnivel(this.value)">
																
															</select> -->
																						<Select class="form-control" type="text" id="niveles" onchange="gescole(this.value);getcur(this.value);">
																						</Select>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>Colegio: <span class="text-danger">*</span></label>
																					<div class="form-group">
																						<!-- <select id="inscole" class="form-control required" >
																
															</select> -->
																						<input type="text" id='inscole' class="form-control" readonly="true">
																					</div>
																				</div>
																			</div>

																			<div class="col-md-3">
																				<div class="form-group">
																					<label>Curso: <span class="text-danger">*</span></label>
																					<div class="form-group">
																						<select id="curso" class="form-control required">
																						</select>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-2">
																				<label>Gestion: <span class="text-danger">*</span></label>
																				<div class="form-group">
																					<select id="gestion" class="form-control required">
																						<option>2022</option>
																					</select>
																				</div>
																			</div>
																		</div>


																		<legend class="text-bold">Facturación</legend>
																		<div class="row">
																			<div class="col-md-5">
																				<div class="form-group">
																					<label>NOMBRE de la persona que firmará en contrato:</label>
																					<input type="text" id='nitnombre' class="form-control">
																				</div>
																			</div>
																			<div class="col-md-3">
																				<div class="form-group">
																					<label>NIT de la persona que firmará en contrato: </label>
																					<input type="text" id='nit' class="form-control">
																				</div>
																			</div>
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>CORREO de la persona que firmará en contrato: </label>
																					<input type="email" id='nitcorreo' class="form-control">
																				</div>
																			</div>
																		</div>

																		<legend class="text-bold">Pandemia actual</legend>
																		<div class="row">
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Considerando el contexto actual sobre la pandemia, usted considera tomar las clases:</label>
																					<select id="pandemia_clases" class="form-control required">
																						<option>Semipresencial</option>
																						<option>Virtual</option>
																					</select>
																				</div>
																			</div>
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>El estudiante cuentas con las vacunas contra el COVID: </label>
																					<select id="pandemia_vacunas" class="form-control required">
																						<option>1era dosis</option>
																						<option>1era y 2da dosis</option>
																						<option>1era, 2da y 3era dosis</option>
																					</select>
																				</div>
																			</div>
																		</div>

																	</div>
																</div>
															</div>
														</div>
													</div>


													<div class="col-md-12">
														<div class="col-lg-12">
															<div class="panel ">
																<div class="panel-heading bg-danger">
																	<h6 class="panel-title">DATOS DE LA O EL ESTUDIANTE</h6>
																	<div class="heading-elements">
																		<ul class="icons-list">
																			<li><a data-action="reload"></a></li>
																		</ul>
																	</div>
																</div>

																<div class="panel-body">

																	<div class="row">
																		<div class="col-lg-12">
																			<legend class="text-bold">APELLIDO(S) Y NOMBRE(S)</legend>
																			<div class="col-md-12">
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Ap.Paterno: <span class="text-danger">*</span></label>
																						<input type="text" id="appat" class="form-control required">
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Ap.Materno: <span class="text-danger">*</span></label>
																						<input type="text" id="apmat" class="form-control required">
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Nombres: <span class="text-danger">*</span></label>
																						<input type="text" id="nombres" class="form-control required">
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Genero: <span class="text-danger">*</span></label>
																						<div class="form-group">
																							<select id="genero" class="form-control required">
																								<option></option>
																								<option value="M">Masculino</option>
																								<option value="F">Femenino</option>
																							</select>
																						</div>
																					</div>
																				</div>
																			</div>
																			<legend class="text-bold">DOCUMENTOS DE IDENTIFICACION</legend>
																			<div class="col-md-12">
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>RUDE: <span class="text-danger">*</span></label>
																						<input type="text" id="rude" class="form-control required" placeholder="Number">
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>CARNET DE IDENTIDAD (CI): <span class="text-danger">*</span></label>
																						<input type="text" id="ci" class="form-control required" placeholder="Carnet">
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Complemento (Caso duplicado): <span class="text-danger">*</span></label>
																						<input type="text" id="complemento" class="form-control" placeholder="Carnet">
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Extension: <span class="text-danger">*</span></label>
																						<select id='extension' class="form-control">
																							<option value="CH">CH</option>
																							<option value="LP">LP</option>
																							<option value="OR">OR</option>
																							<option value="PT">PT</option>
																							<option value="CB">CB</option>
																							<option value="SC">SC</option>
																							<option value="BN">BN</option>
																							<option value="PA">PA</option>
																							<option value="TJ">TJ</option>
																						</select>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-12">
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Código Banco: <span class="text-danger">*</span></label>
																						<input type="text" id="codigobanco" class="form-control required">
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>IdEst Anterior:</label>
																						<div class="form-group">
																							<input type="text" id="idest1" class="form-control" readonly="true">
																						</div>
																					</div>
																				</div>
																			</div>

																		</div>
																		<legend class="text-bold">LUGAR DE NACIMIENTO</legend>
																		<div class="col-lg-8">

																			<div class="col-md-12">
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Pais: <span class="text-danger">*</span></label>
																						<input type="text" id='pais' class="form-control required">
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Depto: </label>
																						<input type="text" id='dpto' class="form-control required">

																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Provincia: <span class="text-danger">*</span></label>
																						<input type="text" id='provincia' class="form-control required">
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Localidad: <span class="text-danger">*</span></label>
																						<input type="text" id='localidad' class="form-control required">
																					</div>
																				</div>
																			</div>
																			<legend class="text-bold">Certificado de Nacimiento</legend>
																			<div class="col-md-12">
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Oficialia: <span class="text-danger">*</span></label>
																						<input type="text" id='oficialia' class="form-control required">
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Libro: <span class="text-danger">*</span></label>
																						<input type="text" id='libro' class="form-control required">
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Partida: <span class="text-danger">*</span></label>
																						<input type="text" id='partida' class="form-control required">
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Folio: <span class="text-danger">*</span></label>
																						<input type="text" id='folio' class="form-control required">
																					</div>
																				</div>
																			</div>
																			<legend class="text-bold">Fecha de Nacimiento</legend>
																			<div class="col-md-12">
																				<div class="col-md-5">
																					<div class="form-group">
																						<label>fecha: <span class="text-danger">*</span></label>
																						<input type="date" id='fnaci' class="form-control required">
																					</div>
																					<!-- <div class="form-group">
														<label>Mes: <span class="text-danger">*</span></label>
														<select id='nacmes' data-placeholder="Mes" class="form-control">
															<option></option>
															<option value="Enero">Enero</option>
															<option value="Febrero">Febrero</option>
															<option value="Marzo">Marzo</option>
															<option value="Abril">Abril</option>
															<option value="Mayo">Mayo</option>
															<option value="Junio">Junio</option>
															<option value="Julio">Julio</option>
															<option value="Agosto">Agosto</option>
															<option value="Septiembre">Septiembre</option>
															<option value="Octubre">Octubre</option>
															<option value="Noviembre">Noviembre</option>
															<option value="Diciembre">Diciembre</option>
														</select>
					                                </div> -->
																				</div>
																				<!-- <div class="col-md-4">
					                                <div class="form-group">
					                                	<label>Dia: <span class="text-danger">*</span></label>
														<select id='nacdia' data-placeholder="Day" class="form-control">
														<option></option>
														<option value="1">1</option>
														<option value="2">2</option>
														<option value="3">3</option>
														<option value="4">4</option>
														<option value="5">5</option>
														<option value="6">6</option>
														<option value="7">7</option>
														<option value="8">8</option>
														<option value="9">9</option>
														<option value="10">10</option>
														<option value="11">11</option>
														<option value="12">12</option>
														<option value="13">13</option>
														<option value="14">14</option>
														<option value="15">15</option>
														<option value="16">16</option>
														<option value="17">17</option>
														<option value="18">18</option>
														<option value="19">19</option>
														<option value="20">20</option>
														<option value="21">21</option>
														<option value="22">22</option>
														<option value="23">23</option>
														<option value="24">24</option>
														<option value="25">25</option>
														<option value="26">26</option>
														<option value="27">27</option>
														<option value="28">28</option>
														<option value="29">29</option>
														<option value="30">30</option>
														<option value="31">31</option>
														</select>
													</div>
												</div>
												<div class="col-md-4">
					                                <div class="form-group">
					                                	<label>Año: <span class="text-danger">*</span></label>
														<select id='nacanio' data-placeholder="Año" class="form-control">
															<option></option>
															<option value="1998">1998</option>
															<option value="1999">1999</option>
															<option value="2000">2000</option>
															<option value="2001">2001</option>
															<option value="2002">2002</option>
															<option value="2003">2003</option>
															<option value="2004">2004</option>
															<option value="2005">2005</option>
															<option value="2006">2006</option>
															<option value="2007">2007</option>
															<option value="2008">2008</option>
															<option value="2009">2009</option>
															<option value="2010">2010</option>
															<option value="2011">2011</option>
															<option value="2012">2012</option>
															<option value="2013">2013</option>
															<option value="2014">2014</option>
															<option value="2015">2015</option>
															<option value="2016">2016</option>
															<option value="2017">2017</option>
															<option value="2018">2018</option>
														
														</select>
													</div>
												</div>
												<div class="col-md-3">
					                                
					                            </div> -->

																			</div>



																		</div>

																		<div class="col-lg-4">
																			<legend class="text-bold">DISCAPACIDAD</legend>
																			<div class="col-md-12">
																				<div class="col-md-12">
																					<div class="form-group">
																						<label class="display-block text-semibold">¿El/La estudiante presenta alguna discapacidad?: <span class="text-danger">*</span></label>
																						<label class="radio-inline">
																							<input type="radio" name="rdiscap" id="discap1" value="1" class="styled">
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Si
																						</label>

																						<label class="radio-inline">
																							<input type="radio" name="rdiscap" id="discap2" value="0" class="styled" checked="checked">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No
																						</label>
																					</div>
																				</div>
																				<div class="col-md-12">
																					<div class="form-group">
																						<label>Num de Registro de Discapacidad o IBC:</label>
																						<input type="text" id='regdiscap' class="form-control">
																					</div>
																				</div>

																				<div class="col-md-12">
																					<div class="form-group">
																						<label class="display-block">Tipo de Discapacidad</label>
																						<select name="tdiscap" id="tdiscap" class="form-control">
																							<option value="ninguna" selected>Ninguna</option>
																							<option value="psiquica">Psíquica</option>
																							<option value="autismo">Austismo</option>
																							<option value="down">Sindrome de Down</option>
																							<option value="intelectual">Intelectual</option>
																							<option value="auditiva">Auditiva</option>
																							<option value="fisica-motora">Física Motora</option>
																							<option value="sordoceguera">Sordoceguera</option>
																							<option value="multiple">Múltiple</option>
																							<option value="visual">Visual</option>
																						</select>
																					</div>
																				</div>
																				<div class="col-md-12">
																					<div class="form-group">
																						<label class="display-block">¿Grado de discapacidad?</label>
																						<select id="gradodiscap" class="form-control">
																							<optgroup label="Psíquica,Autismo,Down,Intelectual">
																								<option value="sin grado" selected>Sin grado</option>
																							</optgroup>
																							<optgroup label="Auditiva, fisica-motora, sordoceguera, multiple, visual">
																								<option value="Leve">Leve</option>
																								<option value="Moderado">Moderado</option>
																								<option value="Grave">Grave</option>
																								<option value="Muy Grave">Muy Grave</option>
																							</optgroup>
																							<optgroup label="Visual">
																								<option value="ceguera total">Ceguera Total</option>
																								<option value="baja vision">Baja Visión</option>
																							</optgroup>
																						</select>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>



														<div class="col-md-12">
															<div class="col-lg-12">
																<div class="panel ">
																	<div class="panel-heading bg-indigo">
																		<h6 class="panel-title">DIRECCION ACTUAL DE LA O EL ESTUDIANTE</h6>
																		<div class="heading-elements">
																			<ul class="icons-list">
																				<li><a data-action="reload"></a></li>
																			</ul>
																		</div>
																	</div>

																	<div class="panel-body">

																		<div class="row">
																			<div class="col-md-3">
																				<div class="form-group">
																					<label>Departamento:<span class="text-danger">*</span></label>
																					<input type="text" id='locdpto' class="form-control required" value="CHUQUISACA">
																				</div>
																			</div>
																			<div class="col-md-3">
																				<div class="form-group">
																					<label>Provincia: <span class="text-danger">*</span></label>
																					<input type="text" id='locprovin' class="form-control required" value="OROPEZA">
																				</div>
																			</div>
																			<div class="col-md-3">
																				<div class="form-group">
																					<label>Sección/Municipio:<span class="text-danger">*</span> </label>
																					<input type="text" id='locmuni' class="form-control required" value="SUCRE">
																				</div>
																			</div>
																			<div class="col-md-3">
																				<div class="form-group">
																					<label>Localidad/Comunidad:<span class="text-danger">*</span> </label>
																					<select id='loclocal' class="form-control required">
																						<option value="SUCRE">SUCRE</option>
																						<option value="YOTALA">YOTALA</option>
																					</select>
																				</div>
																			</div>

																		</div>
																		<div class="row">
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>Zona/Villa:<span class="text-danger">*</span></label>
																					<input type="text" id='loczona' class="form-control required" placeholder="senac, central, patacon, libertadores">
																				</div>
																			</div>
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>Avenida/Calle:</label>
																					<input type="text" id='loccalle' class="form-control">

																				</div>
																			</div>
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>N° Vivienda:</label>
																					<input type="text" id='locnum' class="form-control">
																				</div>
																			</div>

																		</div>
																		<div class="row">
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>Fono Fijo (Casa): </label>
																					<input type="text" id='locfono' class="form-control">
																				</div>
																			</div>
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>Celular Contacto: </label>
																					<input type="text" id='loccel' class="form-control ">
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>


														<div class="col-md-12">
															<div class="col-lg-12">
																<div class="panel ">
																	<div class="panel-heading bg-warning">
																		<h6 class="panel-title">IDIOMA, PERTENENCIA CULTURAL Y SALUD DE LA O EL ESTUDIANTE</h6>
																		<div class="heading-elements">
																			<ul class="icons-list">
																				<li><a data-action="reload"></a></li>
																			</ul>
																		</div>
																	</div>

																	<div class="panel-body">
																		<div class="col-lg-6">
																			<div class="col-md-12">
																				<legend class="text-bold">IDIOMA</legend>
																				<div class="col-md-12">
																					<div class="form-group">
																						<label class="display-block">¿Cual es el idioma natal del estudiante?<span class="text-danger">*</span></label>
																						<input type="text" id="idiomanatal" class="form-control required" value="CASTELLANO">
																					</div>
																				</div>
																				<div class="col-md-12">
																					<div class="form-group">
																						<label class="display-block">¿Idoma(s) que habla el estudiante?<span class="text-danger">*</span></label>
																						<input type="text" id="idioma1" class="form-control required" value="CASTELLANO">
																					</div>
																				</div>
																				<div class="col-md-12">
																					<label class="display-block">.</label>
																					<div class="form-group">
																						<input type="text" id="idioma2" class="form-control" placeholder="2°">
																					</div>
																				</div>
																				<div class="col-md-12">
																					<label class="display-block">.</label>
																					<div class="form-group">
																						<input type="text" id="idioma3" class="form-control" placeholder="3°">
																					</div>
																				</div>
																			</div>
																			<div class="col-md-12">
																				<legend class="text-bold">CULTURAL</legend>
																				<div class="col-md-12">
																					<div class="form-group">
																						<label class="display-block">¿Pertenece a alguna nación, pueblo indigena originario?</label>
																						<select id="nacion" class="form-control">
																							<option value="Ninguno">Ninguno</option>
																							<option value="Afroboliviano">Afroboliviano</option>
																							<option value="Araona">Araona</option>
																							<option value="Aymara">Aymara</option>
																							<option value="Baure">Baure</option>
																							<option value="Bésiro">Bésiro</option>
																							<option value="Canichana">Canichana</option>
																							<option value="Cavineño">Cavineño</option>
																							<option value="Cayubaba">Cayubaba</option>
																							<option value="Chacobo">Chacobo</option>
																							<option value="Chiman">Chiman</option>
																							<option value="Ese Ejja">Ese Ejja</option>
																							<option value="Guaraní">Guaraní</option>
																							<option value="Guarasuawe">Guarasuawe</option>
																							<option value="Guarayo">Guarayo</option>
																							<option value="Itonoma">Itonoma</option>
																							<option value="Leco">Leco</option>
																							<option value="Machajuyai-Kallawaya">Machajuyai-Kallawaya</option>
																							<option value="Machineru">Machineri</option>
																							<option value="Maropa">Maropa</option>
																							<option value="Mojeño-Ignaciano">Mojeño-Ignaciano</option>
																							<option value="Mojeño-Trinitario">Mojeño-Trinitario</option>
																							<option value="Moré">Moré</option>
																							<option value="Mosetén">Mosetén</option>
																							<option value="Movima">Movima</option>
																							<option value="Tacawara">Tacawara</option>
																							<option value="Puquina">Puquina</option>
																							<option value="Quechua">Quechua</option>
																							<option value="Sirionó">Sirionó</option>
																							<option value="Tacana">Tacana</option>
																							<option value="Tapiete">Tapiete</option>
																							<option value="Toromona">Toromona</option>
																							<option value="Uru-Chipaya">Uru-Chipaya</option>
																							<option value="Weenhayek">Weenhayek</option>
																							<option value="Yaminawa">Yaminawa</option>
																							<option value="Yuki">Yuki</option>
																							<option value="Yuracaré">Yuracaré</option>
																							<option value="Zamuco">Zamuco</option>
																						</select>
																					</div>
																				</div>
																			</div>

																		</div>
																		<div class="col-lg-6">

																			<div class="col-md-12">
																				<legend class="text-bold">SALUD</legend>
																				<div class="col-md-12">
																					<div class="form-group">
																						<label class="display-block text-semibold">¿Existe Centro de salud/Posta/Hospital en su comunidad?: <span class="text-danger">*</span></label>
																						<label class="radio-inline">
																							<input type="radio" name="posta" id="posta1" value="1" class="styled" checked="checked">
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Si
																						</label>

																						<label class="radio-inline">
																							<input type="radio" name="posta" id="posta2" value="0" class="styled">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No
																						</label>
																					</div>
																				</div>
																				<div class="col-md-12">
																					<div class="form-group">

																						<label class="display-block text-semibold">El año pasado, por problemas de salud,¿acudió o se atendió en...:</label>
																						<div class="checkbox">
																							<label><input type="checkbox" id="visitaposta1" value="Caja o Seguro de Salud">1. Caja o Seguro de Salud</label>
																						</div>
																						<div class="checkbox">
																							<label><input type="checkbox" id="visitaposta2" value="Establecimientos de Salud Públicos">2. Establecimientos de Salud Públicos</label>
																						</div>
																						<div class="checkbox">
																							<label><input type="checkbox" id="visitaposta3" value="Establecimientos de Salud Privados">3. Establecimientos de Salud Privados</label>
																						</div>
																						<div class="checkbox">
																							<label><input type="checkbox" id="visitaposta4" value="En su vivienda">4. En su vivienda</label>
																						</div>
																						<div class="checkbox">
																							<label><input type="checkbox" id="visitaposta5" value="Medicina Tradicional">5. Medicina Tradicional</label>
																						</div>
																						<div class="checkbox">
																							<label><input type="checkbox" id="visitaposta6" value="La farmacia sin receta médica">6. La farmacia sin receta médica (automedicación)</label>
																						</div>

																					</div>
																				</div>
																				<div class="col-md-12">
																					<div class="form-group">
																						<label class="display-block">¿Cuantas veces fue el estudiante a la posta el año pasado?</label>
																						<select id="veces" class="form-control">
																							<option value="1 a 2">1 A 2 veces</option>
																							<option value="3 a 5">3 a 5 veces</option>
																							<option value="6 o +">6 o más veces</option>
																							<option value="ninguna">ninguna</option>

																						</select>
																					</div>
																				</div>
																				<div class="col-md-12">
																					<div class="form-group">
																						<label class="display-block text-semibold">¿Tiene seguro de Salud?: <span class="text-danger">*</span></label>
																						<label class="radio-inline">
																							<input type="radio" name="seguro" id="seguro1" value="1" class="styled" checked="checked">
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Si
																						</label>

																						<label class="radio-inline">
																							<input type="radio" name="seguro" id="seguro2" value="0" class="styled">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>

														<div class="col-lg-12"></div>

														<div class="col-lg-12">
															<div class="col-lg-12">
																<div class="panel ">
																	<div class="panel-heading bg-primary-400">
																		<h6 class="panel-title">ACCESO DE LA O EL ESTUDIANTE A SERVICIOS BASICOS</h6>
																		<div class="heading-elements">
																			<ul class="icons-list">
																				<li><a data-action="reload"></a></li>
																			</ul>
																		</div>
																	</div>

																	<div class="panel-body">
																		<div class="col-lg-7">
																			<legend class="text-bold">SERVICIOS BASICOS</legend>
																			<div class="col-md-12">
																				<div class="col-md-6">
																					<div class="form-group">
																						<label class="display-block">¿Tiene acceso a agua por cañeria de red:<span class="text-danger">*</span></label>
																						<label class="radio-inline">
																							<input type="radio" name="agua" id="agua1" value="1" class="styled" checked="checked">
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Si
																						</label>

																						<label class="radio-inline">
																							<input type="radio" name="agua" id="agua2" value="0" class="styled">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No
																						</label>
																					</div>
																				</div>
																				<div class="col-md-6">
																					<div class="form-group">
																						<label class="display-block">¿Tiene baño en su vivienda?<span class="text-danger">*</span></label>
																						<label class="radio-inline">
																							<input type="radio" name="banio" id="banio1" value="1" class="styled" checked="checked">
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Si
																						</label>

																						<label class="radio-inline">
																							<input type="radio" name="banio" id="banio2" value="0" class="styled">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No
																						</label>
																					</div>

																				</div>
																				<div class="col-md-12">
																				</div>
																				<div class="col-md-6">
																					<div class="form-group">
																						<label class="display-block text-semibold">¿Tiene red de alcantarillado?: <span class="text-danger">*</span></label>
																						<label class="radio-inline">
																							<input type="radio" name="alcantarillado" id="alcan1" value="1" class="styled" checked="checked">
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Si
																						</label>

																						<label class="radio-inline">
																							<input type="radio" name="alcantarillado" id="alcan2" value="0" class="styled">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No
																						</label>
																					</div>
																				</div>
																				<div class="col-md-6">
																					<div class="form-group">
																						<label class="display-block">¿Usa energía eléctrica su vivienda?:<span class="text-danger">*</span></label>
																						<label class="radio-inline">
																							<input type="radio" name="luz" id="luz1" value="1" class="styled" checked="checked">
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Si
																						</label>

																						<label class="radio-inline">
																							<input type="radio" name="luz" id="luz2" value="0" class="styled">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No
																						</label>
																					</div>
																				</div>
																				<div class="col-md-6">
																					<div class="form-group">
																						<label class="display-block">¿Cuenta con servicio de recojo de basura?<span class="text-danger">*</span></label>
																						<label class="radio-inline">
																							<input type="radio" name="basura" id="basura1" value="1" class="styled" checked="checked">
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Si
																						</label>

																						<label class="radio-inline">
																							<input type="radio" name="basura" id="basura2" value="0" class="styled">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No
																						</label>
																					</div>

																				</div>
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>La vivienda que ocupa el hogar es:<span class="text-danger">*</span> </label>
																						<select id='hogar' class="form-control required">
																							<option value="Propia">Propia</option>
																							<option value="Alquilada">Alquilada</option>
																							<option value="Anticretico">Anticretico</option>
																							<option value="Cedida">Cedida por Servicios</option>
																							<option value="prestada">Prestada por parientes o amigos</option>
																							<option value="cttomixto">Contrato Mixto</option>
																						</select>
																					</div>
																				</div>

																			</div>

																		</div>
																		<div class="col-lg-5">
																			<div class="col-md-12">
																				<legend class="text-bold">ACCESO A INTERNET</legend>
																				<div class="row">
																					<div class="col-md-12">
																						<div class="form-group">
																							<label class="display-block text-semibold">El estudiante accede a internet en:</label>
																							<div class="checkbox">
																								<label><input type="checkbox" value="Su vivienda" id="netvivienda">Su vivienda</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" value="La Unidad Educativa" id="netunidadedu">La Unidad Educativa</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" value="Lugares Públicos" id="netpublic">Lugares Públicos</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" value="Teléfono Celula" id="netcelu">Teléfono Celular</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" value="No accede a Internet" id="nonet">No accede a Internet</label>
																							</div>
																						</div>
																					</div>
																					<div class="col-md-12">
																						<div class="form-group">
																							<label class="display-block text-semibold">¿Con que frecuencia usa internet?</label>
																							<select id='netfrecuencia' class="form-control required">
																								<option value="Diariamente">Diariamente</option>
																								<option value="Una vez a la semana">Una vez a la semana</option>
																								<option value="Mas de una vez a la semana">Mas de una vez a la semana</option>
																								<option value="Una vez al mes">Una vez al mes</option>
																							</select>
																						</div>
																					</div>
																				</div>
																			</div>

																		</div>
																	</div>
																</div>
															</div>
														</div>



														<div class="col-lg-12">
															<div class="col-lg-12">
																<div class="panel ">
																	<div class="panel-heading bg-info">
																		<h6 class="panel-title">ACTIVIDAD LABORAL DE LA O EL ESTUDUDIANTE</h6>
																		<div class="heading-elements">
																			<ul class="icons-list">
																				<li><a data-action="reload"></a></li>
																			</ul>
																		</div>
																	</div>

																	<div class="panel-body">

																		<div class="col-lg-6">
																			<div class="rocol-md-12">
																				<legend class="text-bold"></legend>
																				<div class="col-md-12">
																					<div class="form-group">
																						<label class="display-block text-semibold">En la pasada gestion ¿El estudiante trabajó?: <span class="text-danger">*</span></label>
																						<label class="radio-inline">
																							<input type="radio" name="trabajo" value="1" id="trabajo1" class="styled">
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Si
																						</label>

																						<label class="radio-inline">
																							<input type="radio" name="trabajo" value="0" id="trabajo2" class="styled" checked="checked">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No
																						</label>
																					</div>
																				</div>
																				<div class="col-md-12">
																					<div class="form-group">

																						<label class="display-block text-semibold">Marque los meses que trabajo:</label>
																						<div class="col-md-6">
																							<div class="checkbox">
																								<label><input type="checkbox" id="ene" value="enero">Enero</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" id="feb" value="febrero">Febrero</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" id="mar" value="marzo">Marzo</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" id="abr" value="abril">Abril</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" id="may" value="mayo">Mayo</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" id="jun" value="junio">Junio</label>
																							</div>
																						</div>
																						<div class="col-md-6">

																							<div class="checkbox">
																								<label><input type="checkbox" id="jul" value="julio">Julio</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" id="ago" value="agosto">Agosto</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" id="sep" value="septiembre">Septiembre</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" id="oct" value="octubre">Octubre</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" id="nov" value="noviembre">Noviembre</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" id="dic" value="diciembre">Diciembre</label>
																							</div>
																						</div>



																					</div>

																				</div>
																				<div class="col-md-12">
																					<legend class="text-bold"></legend>
																					<div class="col-md-12">
																						<div class="form-group">
																							<label class="display-block">En la pasada gestion ¿En qué actividad trabajó el estudiante?
																							</label>
																							<select name="actividad" id="actividad" class="select">
																								<option value="ninguno" selected>ninguno</option>
																								<option value="trabajo agricultura">Agricultura</option>
																								<option value="ayudo agricultura">Ganadería o pesca</option>
																								<option value="ayudo hogar">Minería</option>
																								<option value="trabajo de hogar">Construcción</option>
																								<option value="trabajo mineria">Zafra</option>
																								<option value="trabajo dependiente">Vendedor dependiente</option>
																								<option value="Vendedor por cuenta propia">Vendedor por cuenta propia</option>
																								<option value="Transporte o mecánica">Transporte o mecánica</option>
																								<option value="Lustrabotas">Lustrabotas</option>
																								<option value="Trabajador del hogar o niñero">Trabajador del hogar o niñero</option>
																								<option value="Ayudante familiar">Ayudante familiar</option>
																								<option value="Ayudante de venta o comercio">Ayudante de venta o comercio</option>
																								<option value="otro">Otro trabajo</option>
																							</select>
																						</div>
																					</div>
																					<div class="col-md-12">
																						<div class="form-group">
																							<label class="display-block">Otro trabajo:</label>
																							<input type="text" name="otrotrabajo" id="otrotrabajo" class="form-control" placeholder="especifique">
																						</div>
																					</div>
																				</div>

																			</div>

																		</div>
																		<div class="col-lg-5">


																		</div>
																		<div class="col-lg-6">
																			<div class="col-md-12">
																				<legend class="text-bold"></legend>
																				<div class="col-md-12">
																					<div class="form-group">
																						<label class="display-block">En que turno trabajó el estudiante:</label>
																						<div class="checkbox">
																							<label><input type="checkbox" value="Mañana" id="turnoman">Mañana</label>
																						</div>
																						<div class="checkbox">
																							<label><input type="checkbox" value="Tarde" id="turnotar">Tarde</label>
																						</div>
																						<div class="checkbox">
																							<label><input type="checkbox" value="Noche" id="turnonoc">Noche</label>
																						</div>
																					</div>
																				</div>

																				<div class="col-md-12">
																					<legend class="text-bold"></legend>
																					<div class="form-group">
																						<label class="display-block text-semibold">¿Con que frecuencia Trabajo?: </label>
																						<select name="trabfrecuencia" id="trabfrecuencia">
																							<option value=""></option>
																							<option value="Todos los dias">Todos los dias</option>
																							<option value="Fines de semana">Fines de semana</option>
																							<option value="Dias Festivos">Dias Festivos</option>
																							<option value="Dias habiles" selected>Dias habiles</option>
																							<option value="Eventual / esporádico">Eventual / esporádico</option>
																							<option value="En vacaciones">En vacaciones</option>

																						</select>
																					</div>
																				</div>
																				<div class="col-md-12">
																					<div class="form-group">
																						<label class="display-block text-semibold">¿Recibió algún pago por el trabajo realizado?: <span class="text-danger">*</span></label>
																						<label class="radio-inline">
																							<input type="radio" name="pagotrab" value="si" id="pagotrab1" class="styled">
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Si
																						</label>

																						<label class="radio-inline">
																							<input type="radio" name="pagotrab" value="no" id="pagotrab2" class="styled" checked="checked">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No
																						</label>
																					</div>
																				</div>
																				<div class="col-md-12">
																					<div class="form-group">
																						<label class="display-block text-semibold">Tipo de pago</label>
																						<select name="tipopago" id="tipopago" class="form-control">
																							<option value="nopago">No recibio</option>
																							<option value="En especie">En especie</option>
																							<option value="Dinero">Dinero</option>
																						</select>
																					</div>
																				</div>
																			</div>



																		</div>
																	</div>
																</div>
															</div>
														</div>


														<div class="col-lg-12">
															<div class="col-lg-12">
																<div class="panel ">
																	<div class="panel-heading bg-indigo">
																		<h6 class="panel-title">MEDIO DE TRANSPORTE PARA LLEGAR A LA UNIDAD EDUCATIVA</h6>
																		<div class="heading-elements">
																			<ul class="icons-list">
																				<li><a data-action="reload"></a></li>
																			</ul>
																		</div>
																	</div>

																	<div class="panel-body">
																		<div class="col-lg-12">
																			<div class="col-md-12">
																				<div class="col-md-4">
																					<div class="form-group">
																						<label class="display-block text-semibold">¿Cómo llega el estudiante a la Unidad Educativa?: <span class="text-danger">*</span></label>
																						<select name="transpllega" id="transpllega" class="form-control">

																							<option value="pie">A pie</option>
																							<option value="vehiculo">En vehiculo de transporte terrestre</option>
																							<option value="fluvial">Fluvial</option>
																							<option value="otro">Otro medio</option>
																						</select>
																					</div>
																				</div>
																				<div class="col-md-4">
																					<div class="form-group">
																						<label class="display-block text-semibold">Otro medio: </label>
																						<input type="text" name="otrollega" id="otrollega" class="form-control" placeholder="elicoptero">
																					</div>
																				</div>

																				<div class="col-md-4">
																					<div class="form-group">
																						<label class="display-block text-semibold">¿Cuanto tiempo le toma al estudiante llegar a la Unidad Educativa? <span class="text-danger">*</span></label>
																						<select name="tllegada" id="tllegada" class="form-control">
																							<option value="Menos de media hora">Menos de media hora</option>
																							<option value="Entre media hora y una hora">Entre media hora y una hora</option>
																							<option value="Entre una a dos horas">Entre una a dos horas</option>
																							<option value="Dos horas o mas">Dos horas o más</option>
																						</select>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="col-lg-12">
																			<legend class="text-bold"></legend>
																			<div class="col-md-12">
																				<div class="col-md-12">
																					<div class="form-group">
																						<label>¿El estudiante abandonó la Unidad Educativa el año pasado?: <span class="text-danger">*</span></label>
																						<label class="radio-inline">
																							<input type="radio" name="abandono" value="si" id="abandono1" class="styled">
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Si
																						</label>

																						<label class="radio-inline">
																							<input type="radio" name="abandono" value="no" id="abandono2" class="styled" checked="checked">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No
																						</label>
																					</div>
																				</div>
																				<div class="col-md-12">
																					<legend class="text-bold"></legend>
																					<label>¿Cual o cuales fueron las razones de abandono escolar?:</label>
																					<div class="form-group">

																						<div class="col-md-6">
																							<div class="checkbox">
																								<label><input type="checkbox" value="Tuvo que ayudar a sus padres en su trabajo" id="razon0">Tuvo que ayudar a sus padres en su trabajo</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" value="Tuvo trabajo renumerado" id="razon1">Tuvo trabajo renumerado</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" value="Falta de dinero" id="razon2">Falta de dinero</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" value="Edad Temprana / edad tardía" id="razon3">Edad Temprana / edad tardía</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" value="La unidad educativa era distante" id="razon4">La unidad educativa era distante</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" value="Labores de casa/cuidado de niños(as)" id="razon5">Labores de casa/cuidado de niños(as)</label>
																							</div>
																						</div>
																						<div class="col-md-6">
																							<div class="checkbox">
																								<label><input type="checkbox" value="Embarazo o paternidad" id="razon6">Embarazo o paternidad</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" value="Por enfermedad/accidente/discapacidad" id="razon7">Por enfermedad/accidente/discapacidad</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" value="Viaje o traslado" id="razon8">Viaje o traslado</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" value="Falta de interés" id="razon9">Falta de interés</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" value="Bullying o discriminación" id="razon10">Bullying o discriminación</label>
																							</div>
																							<div class="checkbox">
																								<label><input type="checkbox" value="Otra" id="razon11">Otra</label>
																							</div>
																							<div class="form-group">
																								<input type="text" id='otrarazon' class="form-control">
																							</div>
																						</div>

																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>

														<div class="col-lg-12">
															<div class="col-lg-12">
																<div class="panel ">
																	<div class="panel-heading bg-success">
																		<h6 class="panel-title">LA O EL ESTUDIANTE VIVE HABITUALMENTE</h6>
																		<div class="heading-elements">
																			<ul class="icons-list">
																				<li><a data-action="reload"></a></li>
																			</ul>
																		</div>
																	</div>

																	<div class="panel-body">
																		<div class="col-md-12">
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>¿El Estudiante vive habitualmente con:?</label>
																					<select id='vivecon' class="form-control">
																						<option value="Padre y Madre">Padre y Madre</option>
																						<option value="Solo Padre">Solo Padre</option>
																						<option value="Solo Madre">Solo Madre</option>
																						<option value="Tutor">Tutor(a)</option>
																						<option value="Solo">Solo(a)</option>
																					</select>
																				</div>
																			</div>

																		</div>
																	</div>
																</div>
															</div>
														</div>

														<div class="col-lg-6">
															<div class="col-lg-12">
																<div class="panel ">
																	<div class="panel-heading bg-danger">
																		<h6 class="panel-title">DATOS DEL PADRE</h6>
																		<div class="heading-elements">
																			<ul class="icons-list">
																				<li><a data-action="reload"></a></li>
																			</ul>
																		</div>
																	</div>

																	<div class="panel-body">
																		<input type="hidden" id='t1id'>
																		<div class="col-md-12">
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>CI: </label>
																					<input type="text" id='t1ci' class="form-control">
																				</div>
																			</div>
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>COM: </label>
																					<input type="text" id='t1comple' class="form-control">
																				</div>
																			</div>
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>EX: </label>
																					<select id='t1exten' class="form-control">
																						<option value="CH">CH</option>
																						<option value="LP">LP</option>
																						<option value="OR">OR</option>
																						<option value="PT">PT</option>
																						<option value="CB">CB</option>
																						<option value="SC">SC</option>
																						<option value="BN">BN</option>
																						<option value="PA">PA</option>
																						<option value="TJ">TJ</option>
																					</select>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12">
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Ap.Paterno: </label>
																					<input type="text" id='t1appat' class="form-control">
																				</div>
																			</div>
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Ap.Materno:</label>
																					<input type="text" id='t1apmat' class="form-control">
																				</div>
																			</div>
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Nombres: </label>
																					<input type="text" id='t1nombres' class="form-control">
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12">
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Idioma: </label>
																					<div class="form-group">

																						<select id='t1idioma' class="form-control">
																							<option value="Castellano">Castellano</option>
																							<option value="Quechua">Quechua</option>
																							<option value="Aymara">Aymara</option>
																							<option value="Guarani">Guarani</option>
																							<option value="Inglés">Inglés</option>
																							<option value="Postugues">Portugues</option>
																						</select>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Grado: </label>
																					<input type="text" id='t1grado' class="form-control" placeholder="licenciatura, postgrado, bachiller, magister">
																				</div>
																			</div>
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Ocupación: </label>
																					<input type="text" id='t1ocup' class="form-control" placeholder="construccion, ama de casa, oficinista, administrador">
																				</div>
																			</div>

																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Lugar Trabajo: </label>
																					<input type="text" id='t1lug' class="form-control" placeholder="Nombre de Empresa">
																				</div>
																			</div>


																		</div>
																		<div class="col-md-12">

																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Celular : <span class="text-danger">*</span></label>
																					<input type="text" id="t1celular" class="form-control">
																				</div>
																			</div>
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Oficina fono: <span class="text-danger">*</span></label>
																					<input type="text" id="t1ofifono" class="form-control">
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12">
																			<legend class="text-bold">Fecha de Nacimiento</legend>
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>fecha: <span class="text-danger">*</span></label>
																					<input type="date" id="t1fn" class="form-control">
																				</div>
																				<!-- <div class="form-group">
																	<label>Mes: <span class="text-danger">*</span></label>
																	<select id='t1fnacmes' data-placeholder="Mes" class="form-control">
																		<option></option>
																		<option value="Enero">Enero</option>
																		<option value="Febrero">Febrero</option>
																		<option value="Marzo">Marzo</option>
																		<option value="Abril">Abril</option>
																		<option value="Mayo">Mayo</option>
																		<option value="Junio">Junio</option>
																		<option value="Julio">Julio</option>
																		<option value="Agosto">Agosto</option>
																		<option value="Septiembre">Septiembre</option>
																		<option value="Octubre">Octubre</option>
																		<option value="Noviembre">Noviembre</option>
																		<option value="Diciembre">Diciembre</option>
																	</select>
								                                </div>
								                            </div>
								                            <div class="col-md-4">
								                                <div class="form-group">
								                                	<label>Dia:</label>
																	<select id='t1fnacdia' data-placeholder="Day" class="form-control">
																	<option></option>
																	<option value="1">1</option>
																	<option value="2">2</option>
																	<option value="3">3</option>
																	<option value="4">4</option>
																	<option value="5">5</option>
																	<option value="6">6</option>
																	<option value="7">7</option>
																	<option value="8">8</option>
																	<option value="9">9</option>
																	<option value="10">10</option>
																	<option value="11">11</option>
																	<option value="12">12</option>
																	<option value="13">13</option>
																	<option value="14">14</option>
																	<option value="15">15</option>
																	<option value="16">16</option>
																	<option value="17">17</option>
																	<option value="18">18</option>
																	<option value="19">19</option>
																	<option value="20">20</option>
																	<option value="21">21</option>
																	<option value="22">22</option>
																	<option value="23">23</option>
																	<option value="24">24</option>
																	<option value="25">25</option>
																	<option value="26">26</option>
																	<option value="27">27</option>
																	<option value="28">28</option>
																	<option value="29">29</option>
																	<option value="30">30</option>
																	<option value="31">31</option>
																	</select>
																</div>
															</div>
															<div class="col-md-4">
								                                <div class="form-group">
								                                	<label>Año: <span class="text-danger">*</span></label>
																	<select id='t1fnacanio' data-placeholder="Año" class="form-control">
																		<option></option>
																		<option value="1950">1950</option>
																		<option value="1951">1951</option>
																		<option value="1952">1952</option>
																		<option value="1953">1953</option>
																		<option value="1954">1954</option>
																		<option value="1955">1955</option>
																		<option value="1956">1956</option>
																		<option value="1957">1957</option>
																		<option value="1958">1958</option>
																		<option value="1959">1959</option>
																		<option value="1960">1960</option>
																		<option value="1961">1961</option>
																		<option value="1962">1962</option>
																		<option value="1963">1963</option>
																		<option value="1964">1964</option>
																		<option value="1965">1965</option>
																		<option value="1966">1966</option>
																		<option value="1967">1967</option>
																		<option value="1968">1968</option>
																		<option value="1969">1969</option>
																		<option value="1970">1970</option>
																		<option value="1971">1971</option>
																		<option value="1972">1972</option>
																		<option value="1973">1973</option>
																		<option value="1974">1974</option>
																		<option value="1975">1975</option>
																		<option value="1976">1976</option>
																		<option value="1977">1977</option>
																		<option value="1978">1978</option>
																		<option value="1979">1979</option>
																		<option value="1980">1980</option>
																		<option value="1981">1981</option>
																		<option value="1982">1982</option>
																		<option value="1983">1983</option>
																		<option value="1984">1984</option>
																		<option value="1985">1985</option>
																		<option value="1986">1986</option>
																		<option value="1987">1987</option>
																		<option value="1988">1988</option>
																		<option value="1989">1989</option>
																		<option value="1990">1990</option>
																		<option value="1991">1991</option>
																		<option value="1992">1992</option>
																		<option value="1993">1993</option>
																		<option value="1994">1994</option>
																		<option value="1995">1995</option>
																		<option value="1996">1996</option>
																		<option value="1997">1997</option>
																		<option value="1998">1998</option>
																		<option value="1999">1999</option>
																		<option value="2000">2000</option>
																		<option value="2001">2001</option>
																		<option value="2002">2002</option>
																		<option value="2003">2003</option>
																		<option value="2004">2004</option>
																		<option value="2005">2005</option>
																		<option value="2006">2006</option>
																		<option value="2007">2007</option>
																		<option value="2008">2008</option>
																		<option value="2009">2009</option>
																		<option value="2010">2010</option>
																		<option value="2011">2011</option>
																		<option value="2012">2012</option>
																		<option value="2013">2013</option>
																		<option value="2014">2014</option>
																		<option value="2015">2015</option>
																		<option value="2016">2016</option>
																		<option value="2017">2017</option>
																		<option value="2018">2018</option>
																	
																	</select>
																</div> -->
																			</div>
																			<div class="col-md-12">
																				<label class="radio-inline">
																					<input type="radio" name="contrato" value="P" class="styled" checked="checked">
																					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contrato
																				</label>
																			</div>

																		</div>
																	</div>
																</div>
															</div>
														</div>

														<div class="col-lg-6">
															<div class="col-lg-12">
																<div class="panel ">
																	<div class="panel-heading bg-primary-400">
																		<h6 class="panel-title">DATOS DE LA MADRE</h6>
																		<div class="heading-elements">
																			<ul class="icons-list">
																				<li><a data-action="reload"></a></li>
																			</ul>
																		</div>
																	</div>

																	<div class="panel-body">
																		<input type="hidden" id='t2id'>
																		<div class="col-md-12">
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>CI: </label>
																					<input type="text" id='t2ci' class="form-control">
																				</div>
																			</div>
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>COM: </label>
																					<input type="text" id='t2comple' class="form-control">
																				</div>
																			</div>
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>EX: </label>
																					<select id='t2exten' class="form-control">
																						<option value="CH">CH</option>
																						<option value="LP">LP</option>
																						<option value="OR">OR</option>
																						<option value="PT">PT</option>
																						<option value="CB">CB</option>
																						<option value="SC">SC</option>
																						<option value="BN">BN</option>
																						<option value="PA">PA</option>
																						<option value="TJ">TJ</option>
																					</select>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12">
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Ap.Paterno: </label>
																					<input type="text" id='t2appat' class="form-control">
																				</div>
																			</div>
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Ap.Materno:</label>
																					<input type="text" id='t2apmat' class="form-control">
																				</div>
																			</div>
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Nombres: </label>
																					<input type="text" id='t2nombres' class="form-control">
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12">

																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Idioma: </label>
																					<div class="form-group">

																						<select id='t2idioma' class="form-control">
																							<option value="Castellano">Castellano</option>
																							<option value="Quechua">Quechua</option>
																							<option value="Aymara">Aymara</option>
																							<option value="Guarani">Guarani</option>
																							<option value="Inglés">Inglés</option>
																							<option value="Postugues">Portugues</option>
																						</select>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Grado: </label>
																					<input type="text" id='t2grado' class="form-control" placeholder="licenciatura, postgrado, bachiller, magister">
																				</div>
																			</div>
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Ocupación: </label>
																					<input type="text" id='t2ocup' class="form-control" placeholder="construccion, ama de casa, oficinista, administrador">
																				</div>
																			</div>
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Lugar Trabajo: </label>
																					<input type="text" id='t2lug' class="form-control" placeholder="Nombre de Empresa">
																				</div>
																			</div>

																		</div>
																		<div class="col-md-12">
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Celular : <span class="text-danger">*</span></label>
																					<input type="text" id="t2celular" class="form-control">
																				</div>
																			</div>
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Oficina fono: <span class="text-danger">*</span></label>
																					<input type="text" id="t2ofifono" class="form-control">
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12">
																			<legend class="text-bold">Fecha de Nacimiento</legend>
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>fecha: <span class="text-danger">*</span></label>
																					<input type="date" id="t2fn" class="form-control">
																				</div>
																				<!-- <div class="form-group">
																	<label>Mes: <span class="text-danger">*</span></label>
																	<select id='t2fnacmes' data-placeholder="Mes" class="form-control">
																		<option></option>
																		<option value="Enero">Enero</option>
																		<option value="Febrero">Febrero</option>
																		<option value="Marzo">Marzo</option>
																		<option value="Abril">Abril</option>
																		<option value="Mayo">Mayo</option>
																		<option value="Junio">Junio</option>
																		<option value="Julio">Julio</option>
																		<option value="Agosto">Agosto</option>
																		<option value="Septiembre">Septiembre</option>
																		<option value="Octubre">Octubre</option>
																		<option value="Noviembre">Noviembre</option>
																		<option value="Diciembre">Diciembre</option>
																	</select>
								                                </div>
								                            </div>
								                            <div class="col-md-4">
								                                <div class="form-group">
								                                	<label>Dia: <span class="text-danger">*</span></label>
																	<select id='t2fnacdia' data-placeholder="Day" class="form-control">
																	<option></option>
																	<option value="1">1</option>
																	<option value="2">2</option>
																	<option value="3">3</option>
																	<option value="4">4</option>
																	<option value="5">5</option>
																	<option value="6">6</option>
																	<option value="7">7</option>
																	<option value="8">8</option>
																	<option value="9">9</option>
																	<option value="10">10</option>
																	<option value="11">11</option>
																	<option value="12">12</option>
																	<option value="13">13</option>
																	<option value="14">14</option>
																	<option value="15">15</option>
																	<option value="16">16</option>
																	<option value="17">17</option>
																	<option value="18">18</option>
																	<option value="19">19</option>
																	<option value="20">20</option>
																	<option value="21">21</option>
																	<option value="22">22</option>
																	<option value="23">23</option>
																	<option value="24">24</option>
																	<option value="25">25</option>
																	<option value="26">26</option>
																	<option value="27">27</option>
																	<option value="28">28</option>
																	<option value="29">29</option>
																	<option value="30">30</option>
																	<option value="31">31</option>
																	</select>
																</div>
															</div>
															<div class="col-md-4">
								                                <div class="form-group">
								                                	<label>Año: <span class="text-danger">*</span></label>
																	<select id='t2fnacanio' data-placeholder="Año" class="form-control">
																		<option></option>
																		<option value="1950">1950</option>
																		<option value="1951">1951</option>
																		<option value="1952">1952</option>
																		<option value="1953">1953</option>
																		<option value="1954">1954</option>
																		<option value="1955">1955</option>
																		<option value="1956">1956</option>
																		<option value="1957">1957</option>
																		<option value="1958">1958</option>
																		<option value="1959">1959</option>
																		<option value="1960">1960</option>
																		<option value="1961">1961</option>
																		<option value="1962">1962</option>
																		<option value="1963">1963</option>
																		<option value="1964">1964</option>
																		<option value="1965">1965</option>
																		<option value="1966">1966</option>
																		<option value="1967">1967</option>
																		<option value="1968">1968</option>
																		<option value="1969">1969</option>
																		<option value="1970">1970</option>
																		<option value="1971">1971</option>
																		<option value="1972">1972</option>
																		<option value="1973">1973</option>
																		<option value="1974">1974</option>
																		<option value="1975">1975</option>
																		<option value="1976">1976</option>
																		<option value="1977">1977</option>
																		<option value="1978">1978</option>
																		<option value="1979">1979</option>
																		<option value="1980">1980</option>
																		<option value="1981">1981</option>
																		<option value="1982">1982</option>
																		<option value="1983">1983</option>
																		<option value="1984">1984</option>
																		<option value="1985">1985</option>
																		<option value="1986">1986</option>
																		<option value="1987">1987</option>
																		<option value="1988">1988</option>
																		<option value="1989">1989</option>
																		<option value="1990">1990</option>
																		<option value="1991">1991</option>
																		<option value="1992">1992</option>
																		<option value="1993">1993</option>
																		<option value="1994">1994</option>
																		<option value="1995">1995</option>
																		<option value="1996">1996</option>
																		<option value="1997">1997</option>
																		<option value="1998">1998</option>
																		<option value="1999">1999</option>
																		<option value="2000">2000</option>
																		<option value="2001">2001</option>
																		<option value="2002">2002</option>
																		<option value="2003">2003</option>
																		<option value="2004">2004</option>
																		<option value="2005">2005</option>
																		<option value="2006">2006</option>
																		<option value="2007">2007</option>
																		<option value="2008">2008</option>
																		<option value="2009">2009</option>
																		<option value="2010">2010</option>
																		<option value="2011">2011</option>
																		<option value="2012">2012</option>
																		<option value="2013">2013</option>
																		<option value="2014">2014</option>
																		<option value="2015">2015</option>
																		<option value="2016">2016</option>
																		<option value="2017">2017</option>
																		<option value="2018">2018</option>
																	
																	</select>
																</div> -->
																			</div>
																			<div class="col-md-12">
																				<label class="radio-inline">
																					<input type="radio" name="contrato" value="M" class="styled">
																					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contrato
																				</label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>

														<div class="col-lg-6">
															<div class="col-lg-12">
																<div class="panel ">
																	<div class="panel-heading bg-warning">
																		<h6 class="panel-title">DATOS DEL TUTOR</h6>
																		<div class="heading-elements">
																			<ul class="icons-list">
																				<li><a data-action="reload"></a></li>
																			</ul>
																		</div>
																	</div>

																	<div class="panel-body">
																		<input type="hidden" id='t3id'>
																		<div class="col-md-12">
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>CI: </label>
																					<input type="text" id='t3ci' class="form-control">
																				</div>
																			</div>
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>COM: </label>
																					<input type="text" id='t3comple' class="form-control">
																				</div>
																			</div>
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>EX: </label>
																					<select id='t3exten' class="form-control">
																						<option value="CH">CH</option>
																						<option value="LP">LP</option>
																						<option value="OR">OR</option>
																						<option value="PT">PT</option>
																						<option value="CB">CB</option>
																						<option value="SC">SC</option>
																						<option value="BN">BN</option>
																						<option value="PA">PA</option>
																						<option value="TJ">TJ</option>
																					</select>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12">
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Ap.Paterno: </label>
																					<input type="text" id='t3appat' class="form-control">
																				</div>
																			</div>
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Ap.Materno:</label>
																					<input type="text" id='t3apmat' class="form-control">
																				</div>
																			</div>
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Nombres: </label>
																					<input type="text" id='t3nombres' class="form-control">
																				</div>
																			</div>

																		</div>
																		<div class="col-md-12">
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Idioma: </label>
																					<div class="form-group">

																						<select id='t3idioma' class="form-control">
																							<option value="Castellano">Castellano</option>
																							<option value="Quechua">Quechua</option>
																							<option value="Aymara">Aymara</option>
																							<option value="Guarani">Guarani</option>
																							<option value="Inglés">Inglés</option>
																							<option value="Postugues">Portugues</option>
																						</select>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Grado: </label>
																					<input type="text" id='t3grado' class="form-control" placeholder="licenciatura, postgrado, bachiller, magister">
																				</div>
																			</div>

																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Ocupación: </label>
																					<input type="text" id='t3ocup' class="form-control" placeholder="construccion, ama de casa, oficinista, administrador">
																				</div>
																			</div>
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Lugar Trabajo: </label>
																					<input type="text" id='t3lug' class="form-control" placeholder="Nombre de Empresa">
																				</div>
																			</div>

																		</div>

																		<div class="col-md-12">
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Oficina fono:
																						<input type="text" id="t3ofifono" class="form-control">
																				</div>
																			</div>

																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Celular :</label>
																					<input type="text" id="t3celular" class="form-control">
																				</div>
																			</div>
																		</div>
																		<legend class="text-bold">Fecha de Nacimiento</legend>
																		<div class="col-md-12">
																			<div class="col-md-4">
																				<div class="form-group">
																					<label>fecha :</label>
																					<input type="date" id="t3fn" class="form-control">
																				</div>
																				<!-- <div class="form-group">
																	<label>Mes: <span class="text-danger">*</span></label>
																	<select id='t3fnacmes' data-placeholder="Mes" class="form-control">
																		<option></option>
																		<option value="Enero">Enero</option>
																		<option value="Febrero">Febrero</option>
																		<option value="Marzo">Marzo</option>
																		<option value="Abril">Abril</option>
																		<option value="Mayo">Mayo</option>
																		<option value="Junio">Junio</option>
																		<option value="Julio">Julio</option>
																		<option value="Agosto">Agosto</option>
																		<option value="Septiembre">Septiembre</option>
																		<option value="Octubre">Octubre</option>
																		<option value="Noviembre">Noviembre</option>
																		<option value="Diciembre">Diciembre</option>
																	</select>
								                                </div>
								                            </div>
								                            <div class="col-md-4">
								                                <div class="form-group">
								                                	<label>Dia: <span class="text-danger">*</span></label>
																	<select id='t3fnacdia' data-placeholder="Day" class="form-control">
																	<option></option>
																	<option value="1">1</option>
																	<option value="2">2</option>
																	<option value="3">3</option>
																	<option value="4">4</option>
																	<option value="5">5</option>
																	<option value="6">6</option>
																	<option value="7">7</option>
																	<option value="8">8</option>
																	<option value="9">9</option>
																	<option value="10">10</option>
																	<option value="11">11</option>
																	<option value="12">12</option>
																	<option value="13">13</option>
																	<option value="14">14</option>
																	<option value="15">15</option>
																	<option value="16">16</option>
																	<option value="17">17</option>
																	<option value="18">18</option>
																	<option value="19">19</option>
																	<option value="20">20</option>
																	<option value="21">21</option>
																	<option value="22">22</option>
																	<option value="23">23</option>
																	<option value="24">24</option>
																	<option value="25">25</option>
																	<option value="26">26</option>
																	<option value="27">27</option>
																	<option value="28">28</option>
																	<option value="29">29</option>
																	<option value="30">30</option>
																	<option value="31">31</option>
																	</select>
																</div>
															</div>
															<div class="col-md-4">
								                                <div class="form-group">
								                                	<label>Año: <span class="text-danger">*</span></label>
																	<select id='t3fnacanio' data-placeholder="Año" class="form-control">
																		<option></option>
																		<option value="1930">1930</option>
																		<option value="1931">1931</option>
																		<option value="1932">1932</option>
																		<option value="1933">1933</option>
																		<option value="1934">1934</option>
																		<option value="1935">1935</option>
																		<option value="1936">1936</option>
																		<option value="1937">1937</option>
																		<option value="1938">1938</option>
																		<option value="1939">1939</option>																											
																		<option value="1940">1940</option>
																		<option value="1941">1941</option>
																		<option value="1942">1942</option>
																		<option value="1943">1943</option>
																		<option value="1944">1944</option>
																		<option value="1945">1945</option>
																		<option value="1946">1946</option>
																		<option value="1947">1947</option>
																		<option value="1948">1948</option>
																		<option value="1949">1949</option>															
																		<option value="1950">1950</option>
																		<option value="1951">1951</option>
																		<option value="1952">1952</option>
																		<option value="1953">1953</option>
																		<option value="1954">1954</option>
																		<option value="1955">1955</option>
																		<option value="1956">1956</option>
																		<option value="1957">1957</option>
																		<option value="1958">1958</option>
																		<option value="1959">1959</option>
																		<option value="1960">1960</option>
																		<option value="1961">1961</option>
																		<option value="1962">1962</option>
																		<option value="1963">1963</option>
																		<option value="1964">1964</option>
																		<option value="1965">1965</option>
																		<option value="1966">1966</option>
																		<option value="1967">1967</option>
																		<option value="1968">1968</option>
																		<option value="1969">1969</option>
																		<option value="1970">1970</option>
																		<option value="1971">1971</option>
																		<option value="1972">1972</option>
																		<option value="1973">1973</option>
																		<option value="1974">1974</option>
																		<option value="1975">1975</option>
																		<option value="1976">1976</option>
																		<option value="1977">1977</option>
																		<option value="1978">1978</option>
																		<option value="1979">1979</option>
																		<option value="1980">1980</option>
																		<option value="1981">1981</option>
																		<option value="1982">1982</option>
																		<option value="1983">1983</option>
																		<option value="1984">1984</option>
																		<option value="1985">1985</option>
																		<option value="1986">1986</option>
																		<option value="1987">1987</option>
																		<option value="1988">1988</option>
																		<option value="1989">1989</option>
																		<option value="1990">1990</option>
																		<option value="1991">1991</option>
																		<option value="1992">1992</option>
																		<option value="1993">1993</option>
																		<option value="1994">1994</option>
																		<option value="1995">1995</option>
																		<option value="1996">1996</option>
																		<option value="1997">1997</option>
																		<option value="1998">1998</option>
																		<option value="1999">1999</option>
																		<option value="2000">2000</option>
																		<option value="2001">2001</option>
																		<option value="2002">2002</option>
																		<option value="2003">2003</option>
																		<option value="2004">2004</option>
																		<option value="2005">2005</option>
																		<option value="2006">2006</option>
																		<option value="2007">2007</option>
																		<option value="2008">2008</option>
																		<option value="2009">2009</option>
																		<option value="2010">2010</option>
																		<option value="2011">2011</option>
																		<option value="2012">2012</option>
																		<option value="2013">2013</option>
																		<option value="2014">2014</option>
																		<option value="2015">2015</option>
																		<option value="2016">2016</option>
																		<option value="2017">2017</option>
																		<option value="2018">2018</option>
																	
																	</select>
																</div> -->
																			</div>
																		</div>
																		<div class="col-md-12">

																			<div class="col-md-12">
																				<div class="form-group">
																					<label>¿Cuál es su parentesco con el estudiante?</label>
																					<input type="text" id='t3parentesco' class="form-control" placeholder="tio, tia, abuelito,..">
																				</div>
																			</div>

																		</div>
																		<div class="col-md-12">
																			<label class="radio-inline">
																				<input type="radio" name="contrato" value="T" class="styled">
																				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contrato
																			</label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<button class="btn  bg-slate-400" onclick="guardarIns()" id="btnguardar1"><i class="icon icon-file-pdf"></i>&nbsp;Guardar Inscripción</button>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<button class="btn  bg-danger-400" onclick="export_pdf()" id="btnrude1"><i class="icon icon-file-pdf"></i>&nbsp;Imprimir RUDE</button>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<button class="btn  bg-primary-400" onclick="print_ctto()" id="btnctto1"><i class="icon icon-file-pdf"></i>&nbsp;Imprimir CTTO</button>
							</div>
						</div>
					</div>
					<!-- Footer -->

					<!-- /footer -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->
			<div class="footer text-muted">
				&copy; 2022. <a href="#">Sistema de Control Académico "DON BOSCO"</a> by <a href="donboscosucre.edu.bo" target="_blank">Departamento de Informatica</a>
			</div>
		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->
</body>

<script type="text/javascript">
	var testudiante;
	var save_method;
	var _global_idcur = "";
	var _turno = "";
	var _idinscrip = "";

	$(document).ready(function() {

		getstuden();
		// UnidadEdu();
		gestnacimiento();
		gestdic();
		getdirecion();
		getcultura();
		getsalud();
		getvive();
		getpadres();
		AntUnidadEdu();
		getfactura();
		getpandemia();
		// getnacimiento();
		// getdiscapacida();
		// getdireccion();
		// getpadre();
		// getmadre();
		// gettutor();
		// getidioma();
		getUnidadAll();
		//getUnidad();
		// get_nivel();
		//getUnidad();
		document.getElementById("btnguardar").disabled = false;
		document.getElementById("btnrude").disabled = true;
		document.getElementById("btnctto").disabled = true;
		document.getElementById("btnguardar1").disabled = false;
		document.getElementById("btnrude1").disabled = true;
		document.getElementById("btnctto1").disabled = true;
		//document.getElementById("btncodigo").disabled = true;
		var d = new Date();
		document.getElementById("fechains").value = d;
		document.getElementById("insdia").value = d.getDate();
		document.getElementById("insmes").value = d.getMonth() + 1;
		document.getElementById("insanio").value = d.getFullYear();
		getusuario();
		getnumctto();



	});

	function cerrar() {
		var url = "<?php echo site_url('Reg_inscrip_contr/ajax_cerrar') ?>";
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

	function getusuario() {
		var url = "<?php echo site_url('Inscrip_edit_contr/ajax_usuario') ?>";
		//alert(url);
		$.ajax({
			url: url,
			type: "POST",
			data: {},
			dataType: "JSON",
			success: function(data) {
				if (data.status) {
					document.getElementById('user').value = data.data[0];
				}

			}
		});
	}

	function getnumctto() {
		var url = "<?php echo site_url('Inscrip_edit_contr/ajax_get_numctto') ?>";
		//alert(url);
		$.ajax({
			url: url,
			type: "POST",
			data: {},
			dataType: "JSON",
			success: function(data) {
				if (data.status) {
					document.getElementById('numctto').value = data.data;
				}

			}
		});
	}

	//para estudiante
	function gestnacimiento() {

		var idest = document.getElementById('idest').value;


		var dataest = {
			"idest": idest,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/getnaci'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					// cargarBusqueda(data.data[0]);
					// _global_idcur=data.data[0];


					document.getElementById("pais").value = data.data[0];
					document.getElementById("dpto").value = data.data[1];
					document.getElementById("provincia").value = data.data[2];
					document.getElementById("localidad").value = data.data[3];
					document.getElementById("oficialia").value = data.data[4];
					document.getElementById("libro").value = data.data[5];
					document.getElementById("partida").value = data.data[6];
					document.getElementById("folio").value = data.data[7];
					document.getElementById("fnaci").value = data.data[8];
					//getUnidad();
					//alert(data.data[0]);

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getcultura() {

		var idest = document.getElementById('idest').value;
		var gestion = document.getElementById('gestion').value;

		var dataest = {
			"idest": idest,
			"gestion": gestion,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/getcultura'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					document.getElementById("idiomanatal").value = data.data[0];
					document.getElementById("idioma1").value = data.data[1];
					document.getElementById("idioma2").value = data.data[2];
					document.getElementById("idioma3").value = data.data[3];
					document.getElementById("nacion").value = data.data[4];
					//getUnidad();
					//alert(data.data[0]);

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getvive() {

		var idest = document.getElementById('idest').value;
		var gestion = document.getElementById('gestion').value;

		var dataest = {
			"idest": idest,
			"gestion": gestion,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/getvive'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				console.log('Obtener getvive: ', data);
				if (data.status) {
					// document.getElementById("posta").value=data.data[0];
					document.getElementById("vivecon").value = data.data[0];
					setTimeout(function () {
						document.getElementById("unidedu").value = data.data[1];
						document.getElementById("niveles").value=data.data[2];
						document.getElementById("curso").value=data.data[3];
					}, 500);
					selectUnid(data.data[1]);
					selcole(data.data[1]);
					gescole(data.data[2]);
					getcur(data.data[2]);
					// document.getElementById("seguro").value=data.data[2];

					//getUnidad();
					//alert(data.data[0]);


				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.error('ERROR getvive: ', jqXHR, textStatus, errorThrown);
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getfactura() {

		var idest = document.getElementById('idest').value;
		var gestion = document.getElementById('gestion').value;

		var dataest = {
			"idest": idest,
			"gestion": gestion,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/getfactura'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				// console.log("Obtener: getfactura ", data)
				if (data.status) {
					// document.getElementById("posta").value=data.data[0];
					document.getElementById("nitnombre").value = data.data[0];
					document.getElementById("nit").value = data.data[1];
					document.getElementById("nitcorreo").value = data.data[2];
					// document.getElementById("seguro").value=data.data[2];
					//getUnidad();
					//alert(data.data[0]);

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.error("ERROR: getfactura ", jqXHR, textStatus, errorThrown)
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getpandemia() {
		var idest = document.getElementById('idest').value;

		var dataest = {
			"idest": idest,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/getpandemia'); ?>";

		$.ajax({
			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				console.log("Obtener: getpandemia ", data)
				if (data.status) {
					document.getElementById("pandemia_clases").value = data.data[0];
					document.getElementById("pandemia_vacunas").value = data.data[1];
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.error("ERROR: getpandemia ", jqXHR, textStatus, errorThrown)
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	/*function getinscripcion() {
		var idest = document.getElementById('idest').value;
		var gestion = document.getElementById('gestion').value;

		var dataest = {
			"idest": idest,
			"gestion": gestion,
		}

		var url = "<?php //echo site_url('inscrip_edit_contr/getinscripcion'); ?>";

		$.ajax({
			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				console.log("Obtener: getinscripcion ", data)
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.error("ERROR: getinscripcion ", jqXHR, textStatus, errorThrown)
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}*/

	function getsalud() {

		var idest = document.getElementById('idest').value;
		var gestion = document.getElementById('gestion').value;

		var dataest = {
			"idest": idest,
			"gestion": gestion,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/getsalud'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					// document.getElementById("posta").value=data.data[0];
					document.getElementById("veces").value = data.data[1];
					// document.getElementById("seguro").value=data.data[2];
					//getUnidad();
					//alert(data.data[0]);

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getdirecion() {

		var idest = document.getElementById('idest').value;
		var gestion = document.getElementById('gestion').value;

		var dataest = {
			"idest": idest,
			"gestion": gestion,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/getdirecion'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					document.getElementById("locdpto").value = data.data[0];
					document.getElementById("locprovin").value = data.data[1];
					document.getElementById("locmuni").value = data.data[2];
					document.getElementById("loclocal").value = data.data[3];
					document.getElementById("loczona").value = data.data[4];
					document.getElementById("loccalle").value = data.data[5];
					document.getElementById("locnum").value = data.data[6];
					document.getElementById("locfono").value = data.data[7];
					document.getElementById("loccel").value = data.data[8];
					//getUnidad();
					//alert(data.data[0]);

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getpadres() {
		var options = "<option value=''></option>";
		var idcur = document.getElementById('idcur').value;
		var idest = document.getElementById('idest').value;
		var gestion = document.getElementById('gestion').value;

		var dataest = {
			"idest": idest,
			"gestion": gestion,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/getpadres'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					// //document.getElementById("unidedu").value=data.data[0];

					// data.data.forEach(function(item){

					// 	options=options+"<option value='"+item+"'>"+item+"</option>";

					// });
					// document.getElementById('unidedu').innerHTML=options;
					// //document.getElementById('inscole').innerHTML=options;
					var i = 0;
					data.data.forEach(function(item) {
						// alert(data.data[i]);
						if (data.data[i] == 'MADRE') {
							i++;
							getmadre1(data.data[i]);
							i++;
						}
						if (data.data[i] == 'PADRE') {
							i++;
							getpadre1(data.data[i]);
							i++;
						}
						if (data.data[i] == 'TUTOR') {
							i++;
							getptutor1(data.data[i]);
							i++;
						}
						// if(item=='TECNICO HUMANISTICO DON BOSCO'){id='3';}
						// if(item=='DON BOSCO B'){id='2';}
						// if(item=='DON BOSCO A'){id='1';}
						// options=options+"<option value='"+id+"'>"+item+"</option>";     			
					});
					// document.getElementById("unidedu").innerHTML=options;  
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	function getptutor1(id_padres) {

		var idest = document.getElementById('idest').value;


		var dataest = {
			"id_padre": id_padres,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/padres'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					document.getElementById("t3appat").value = data.data[0];
					document.getElementById("t3apmat").value = data.data[1];
					document.getElementById("t3nombres").value = data.data[2];
					document.getElementById("t3ci").value = data.data[7];
					document.getElementById("t3comple").value = data.data[8];
					document.getElementById("t3exten").value = data.data[9];
					document.getElementById("t3idioma").value = data.data[3];
					document.getElementById("t3ocup").value = data.data[4];
					document.getElementById("t3grado").value = data.data[5];
					document.getElementById("t3celular").value = data.data[11];
					document.getElementById("t3ofifono").value = data.data[10];
					document.getElementById("t3fn").value = data.data[6];
					document.getElementById("t3parentesco").value = data.data[12];
					document.getElementById("t3lug").value = data.data[12];
					document.getElementById("t3id").value = data.data[14];

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getmadre1(id_padres) {

		var idest = document.getElementById('idest').value;


		var dataest = {
			"id_padre": id_padres,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/padres'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					document.getElementById("t2appat").value = data.data[0];
					document.getElementById("t2apmat").value = data.data[1];
					document.getElementById("t2nombres").value = data.data[2];
					document.getElementById("t2ci").value = data.data[7];
					document.getElementById("t2comple").value = data.data[8];
					document.getElementById("t2exten").value = data.data[9];
					document.getElementById("t2idioma").value = data.data[3];
					document.getElementById("t2ocup").value = data.data[4];
					document.getElementById("t2grado").value = data.data[5];
					document.getElementById("t2celular").value = data.data[11];
					document.getElementById("t2ofifono").value = data.data[10];
					document.getElementById("t2fn").value = data.data[6];
					document.getElementById("t2lug").value = data.data[12];
					document.getElementById("t2id").value = data.data[14];

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getpadre1(id_padres) {

		var idest = document.getElementById('idest').value;


		var dataest = {
			"id_padre": id_padres,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/padres'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					document.getElementById("t1appat").value = data.data[0];
					document.getElementById("t1apmat").value = data.data[1];
					document.getElementById("t1nombres").value = data.data[2];
					document.getElementById("t1ci").value = data.data[7];
					document.getElementById("t1comple").value = data.data[8];
					document.getElementById("t1exten").value = data.data[9];
					document.getElementById("t1idioma").value = data.data[3];
					document.getElementById("t1ocup").value = data.data[4];
					document.getElementById("t1grado").value = data.data[5];
					document.getElementById("t1celular").value = data.data[11];
					document.getElementById("t1ofifono").value = data.data[10];
					document.getElementById("t1fn").value = data.data[6];
					document.getElementById("t1lug").value = data.data[12];
					document.getElementById("t1id").value = data.data[14];

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function gestdic() {

		var idest = document.getElementById('idest').value;


		var dataest = {
			"idest": idest,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/gestdic'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					// cargarBusqueda(data.data[0]);
					// _global_idcur=data.data[0];
					// discap=document.getElementsByName("rdiscap");
					if (data.data[0]) {
						document.getElementById("discap1").checked = "checked";
					} else {
						document.getElementById("discap2").checked = "checked";
					}
					//     		for(i=0; i<discap.length; i++){
					//     if(discap[i].checked){
					//         var discap1=discap[i].value;
					//     }
					// }

					// document.getElementsByName("rdiscap").value=data.data[0];
					// document.getElementById("discap1").value=data.data[0];
					document.getElementById("regdiscap").value = data.data[1];
					document.getElementById("tdiscap").value = data.data[2];
					document.getElementById("gradodiscap").value = data.data[3];
					//getUnidad();
					//alert(data.data[0]);

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getstuden() {

		var idest = document.getElementById('idest').value;


		var dataest = {
			"idest": idest,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/getstuden'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					// cargarBusqueda(data.data[0]);
					// _global_idcur=data.data[0];
					document.getElementById("rude").value = data.data[0];
					document.getElementById("ci").value = data.data[1];
					document.getElementById("complemento").value = data.data[2];
					document.getElementById("extension").value = data.data[3];
					document.getElementById("appat").value = data.data[4];
					document.getElementById("apmat").value = data.data[5];
					document.getElementById("nombres").value = data.data[6];
					document.getElementById("genero").value = data.data[7];
					document.getElementById("codigobanco").value = data.data[8];
					document.getElementById("idest1").value = data.data[9];
					//getUnidad();
					//alert(data.data[0]);

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function UnidadEdu() {

		var idest = document.getElementById('idest').value;


		var dataest = {
			"idest": idest,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/ajax_get_unidedu'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					// cargarBusqueda(data.data[0]);
					// _global_idcur=data.data[0];

					document.getElementById("idcur").value = data.data[0];
					document.getElementById("rude").value = data.data[1];
					document.getElementById("ci").value = data.data[2];
					document.getElementById("appat").value = data.data[3];
					document.getElementById("apmat").value = data.data[4];
					document.getElementById("nombres").value = data.data[5];
					document.getElementById("genero").value = data.data[6];
					document.getElementById("codigobanco").value = data.data[7];
					document.getElementById("idest1").value = data.data[8];
					document.getElementById("vivecon").value = data.data[9];
					//getUnidad();
					//alert(data.data[0]);

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function AntUnidadEdu() {

		var idest = document.getElementById('idest').value;
		var gestion = document.getElementById('gestion').value;


		var dataest = {
			"idest": idest,
			"gestion": gestion,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/ajax_get_antunidedu'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					// cargarBusqueda(data.data[0]);
					// _global_idcur=data.data[0];

					document.getElementById("antunidadedu").value = data.data[1];
					document.getElementById("antsie").value = data.data[0];
					//getUnidad();
					//alert(data.data[0]);

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getdiscapacida() {

		var idest = document.getElementById('idest').value;


		var dataest = {
			"idest": idest,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/ajax_get_discapacida'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					// cargarBusqueda(data.data[0]);
					// _global_idcur=data.data[0];

					//document.getElementById("antunidadedu").value=data.data[0];
					document.getElementById("regdiscap").value = data.data[1];
					document.getElementById("tdiscap").value = data.data[2];
					document.getElementById("gradodiscap").value = data.data[3];
					//getUnidad();
					//alert(data.data[0]);

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getnacimiento() {

		var idest = document.getElementById('idest').value;


		var dataest = {
			"idest": idest,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/ajax_get_nacimiento'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					// cargarBusqueda(data.data[0]);
					// _global_idcur=data.data[0];
					document.getElementById("oficialia").value = data.data[0];
					document.getElementById("libro").value = data.data[1];
					document.getElementById("partida").value = data.data[2];
					document.getElementById("folio").value = data.data[3];
					document.getElementById("pais").value = data.data[4];
					document.getElementById("dpto").value = data.data[5];
					document.getElementById("provincia").value = data.data[6];
					document.getElementById("localidad").value = data.data[7];
					document.getElementById("nacdia").value = data.data[8];
					document.getElementById("nacmes").value = data.data[9];
					document.getElementById("nacanio").value = data.data[10];
					//getUnidad();
					//alert(data.data[0]);

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getpadre() {

		var idest = document.getElementById('idest').value;


		var dataest = {
			"idest": idest,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/ajax_get_padre'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					// cargarBusqueda(data.data[0]);
					// _global_idcur=data.data[0];
					document.getElementById("t1appat").value = data.data[0];
					document.getElementById("t1apmat").value = data.data[1];
					document.getElementById("t1nombres").value = data.data[2];
					document.getElementById("t1ci").value = data.data[3];
					document.getElementById("t1comple").value = data.data[4];
					document.getElementById("t1exten").value = data.data[5];
					document.getElementById("t1idioma").value = data.data[6];
					document.getElementById("t1ocup").value = data.data[7];
					document.getElementById("t1grado").value = data.data[8];
					document.getElementById("t1celular").value = data.data[9];
					document.getElementById("t1ofifono").value = data.data[10];
					document.getElementById("t1fnacdia").value = data.data[11];
					document.getElementById("t1fnacmes").value = data.data[12];
					document.getElementById("t1fnacanio").value = data.data[13];

					//getUnidad();
					//alert(data.data[0]);

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getmadre() {

		var idest = document.getElementById('idest').value;


		var dataest = {
			"idest": idest,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/ajax_get_madre'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					// cargarBusqueda(data.data[0]);
					// _global_idcur=data.data[0];
					document.getElementById("t2appat").value = data.data[0];
					document.getElementById("t2apmat").value = data.data[1];
					document.getElementById("t2nombres").value = data.data[2];
					document.getElementById("t2ci").value = data.data[3];
					document.getElementById("t2comple").value = data.data[4];
					document.getElementById("t2exten").value = data.data[5];
					document.getElementById("t2idioma").value = data.data[6];
					document.getElementById("t2ocup").value = data.data[7];
					document.getElementById("t2grado").value = data.data[8];
					document.getElementById("t2celular").value = data.data[9];
					document.getElementById("t2ofifono").value = data.data[10];
					document.getElementById("t2fnacdia").value = data.data[11];
					document.getElementById("t2fnacmes").value = data.data[12];
					document.getElementById("t2fnacanio").value = data.data[13];

					//getUnidad();
					//alert(data.data[0]);

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function gettutor() {

		var idest = document.getElementById('idest').value;


		var dataest = {
			"idest": idest,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/ajax_get_tutor'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					// cargarBusqueda(data.data[0]);
					// _global_idcur=data.data[0];
					document.getElementById("t3appat").value = data.data[0];
					document.getElementById("t3apmat").value = data.data[1];
					document.getElementById("t3nombres").value = data.data[2];
					document.getElementById("t3ci").value = data.data[3];
					document.getElementById("t3comple").value = data.data[4];
					document.getElementById("t3exten").value = data.data[5];
					document.getElementById("t3idioma").value = data.data[6];
					document.getElementById("t3ocup").value = data.data[7];
					document.getElementById("t3grado").value = data.data[8];
					document.getElementById("t3celular").value = data.data[9];
					document.getElementById("t3ofifono").value = data.data[10];
					document.getElementById("t3fnacdia").value = data.data[11];
					document.getElementById("t3fnacmes").value = data.data[12];
					document.getElementById("t3fnacanio").value = data.data[13];
					document.getElementById("t3parentesco").value = data.data[14];

					//getUnidad();
					//alert(data.data[0]);

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getcur(nivel) {
		if (nivel == 'PT') {
			nivel = 'PRIMARIA TARDE';
		}
		if (nivel == 'ST') {
			nivel = 'SECUNDARIA TARDE';
		}
		if (nivel == 'PM') {
			nivel = 'PRIMARIA MAÑANA';
		}
		if (nivel == 'SM') {
			nivel = 'SECUNDARIA MAÑANA'
		}
		var options = "<option value=''></option>";
		//alert(nivel);
		var datacur = {
			"TablaCur": "curso",
			"nivel": nivel,
		}

		$.ajax({

			url: "<?php echo site_url('inscrip_edit_contr/ajax_get_curso1'); ?>",
			type: "POST",
			data: datacur,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					var id = '';
					//alert(data.data[0]);
					data.data.forEach(function(item) {
						if (item == 'PRIMERO A') {
							id = '1A';
						}
						if (item == 'PRIMERO B') {
							id = '1B';
						}
						if (item == 'SEGUNDO A') {
							id = '2A';
						}
						if (item == 'SEGUNDO B') {
							id = '2B';
						}
						if (item == 'TERCERO A') {
							id = '3A';
						}
						if (item == 'TERCERO B') {
							id = '3B';
						}
						if (item == 'CUARTO A') {
							id = '4A';
						}
						if (item == 'CUARTO B') {
							id = '4B';
						}
						if (item == 'QUINTO A') {
							id = '5A';
						}
						if (item == 'QUINTO B') {
							id = '5B';
						}
						if (item == 'QUINTO C') {
							id = '5C';
						}
						if (item == 'SEXTO A') {
							id = '6A';
						}
						if (item == 'SEXTO B') {
							id = '6B';
						}
						if (item == 'SEXTO C') {
							id = '6C';
						}
						if (item != 'PREINSCRIPTOS A' && item != 'PREINSCRIPTOS B') {
							options = options + "<option value='" + id + "'>" + item + "</option>";
						}
					});
					document.getElementById('curso').innerHTML = options;
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function gescole(level) {
		if (level == 'PT') {
			level = 'PRIMARIA TARDE';
		}
		if (level == 'ST') {
			level = 'SECUNDARIA TARDE';
		}
		if (level == 'PM') {
			level = 'PRIMARIA MAÑANA';
		}
		if (level == 'SM') {
			level = 'SECUNDARIA MAÑANA'
		}
		var data1 = {
			"table": "curso",
			"lvl": level,
		};

		$.ajax({

			url: "<?php echo site_url('inscrip_edit_contr/ajax_get_level'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					//datos recuperados
					//document.getElementById('Fgestion').value=data.data[0];
					document.getElementById('inscole').value = data.data[1];
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getidioma() {

		var idest = document.getElementById('idest').value;


		var dataest = {
			"idest": idest,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/ajax_get_idioma'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					// cargarBusqueda(data.data[0]);
					// _global_idcur=data.data[0];
					document.getElementById("idiomanatal").value = data.data[0];
					document.getElementById("idioma1").value = data.data[1];
					document.getElementById("idioma2").value = data.data[2];
					document.getElementById("idioma3").value = data.data[3];
					document.getElementById("nacion").value = data.data[4];
					//getUnidad();
					//alert(data.data[0]);

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getdireccion() {

		var idest = document.getElementById('idest').value;


		var dataest = {
			"idest": idest,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/ajax_get_direccion'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					// cargarBusqueda(data.data[0]);
					// _global_idcur=data.data[0];
					document.getElementById("locdpto").value = data.data[0];
					document.getElementById("locprovin").value = data.data[1];
					document.getElementById("locmuni").value = data.data[2];
					document.getElementById("loclocal").value = data.data[3];
					document.getElementById("loczona").value = data.data[4];
					document.getElementById("loccalle").value = data.data[5];
					document.getElementById("locnum").value = data.data[6];
					document.getElementById("locfono").value = data.data[7];
					document.getElementById("loccel").value = data.data[8];
					//getUnidad();
					//alert(data.data[0]);

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getUnidad() {
		var options = "<option value=''></option>";
		var idcur = document.getElementById('idcur').value;

		var dataest = {
			"idcur": idcur,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/ajax_get_unid'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				console.log('Obtener: getUnidad ', data)
				if (data.status) {

					// if(item=='TECNICO HUMANISTICO DON BOSCO'){id='3';}
					// if(item=='DON BOSCO B'){id='2';}
					// if(item=='DON BOSCO A'){id='1';}
					// options=options+"<option value='"+id+"'>"+item+"</option>";	           		 	           		    	
					// document.getElementById("unidedu").value=data.data[0];          		 	           		    	
					// document.getElementById("unidedu").value=data.data[0];
					// /*document.getElementById("unidedu").value=data.data[0];
					// var cole=data.data[0];
					// selectUnid(cole);*/

					data.data.forEach(function(item) {
						if (item == 'TECNICO HUMANISTICO DON BOSCO') {
							id = '3';
						}
						if (item == 'DON BOSCO B') {
							id = '2';
						}
						if (item == 'DON BOSCO A') {
							id = '1';
						}
						options = options + "<option value='" + id + "'>" + item + "</option>";
					});
					document.getElementById("unidedu").innerHTML = options;


				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.error('ERROR: getUnidad ', data)
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	function getUnidadAll() {
		var options = "<option value=''></option>";
		var idcur = document.getElementById('idcur').value;

		var dataest = {
			// "idcur":idcur,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/ajax_get_unidAll'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: dataest,
			success: function(data) //cargado de datos del registro 
			{
				console.log('Obtener: getUnidadAll ', data)
				if (data.status) {
					// //document.getElementById("unidedu").value=data.data[0];

					// data.data.forEach(function(item){

					// 	options=options+"<option value='"+item+"'>"+item+"</option>";

					// });
					// document.getElementById('unidedu').innerHTML=options;
					// //document.getElementById('inscole').innerHTML=options;

					data.data.forEach(function(item) {
						if (item == 'TECNICO HUMANISTICO DON BOSCO') {
							id = '3';
						}
						if (item == 'DON BOSCO B') {
							id = '2';
						}
						if (item == 'DON BOSCO A') {
							id = '1';
						}
						options = options + "<option value='" + id + "'>" + item + "</option>";
					});
					document.getElementById("unidedu").innerHTML = options;
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.error('ERROR: getUnidadAll ', data)
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	function selectUnid(cole) {
		if (cole == '3') {
			cole = 'TECNICO HUMANISTICO DON BOSCO';
		}
		if (cole == '2') {
			cole = 'DON BOSCO B';
		}
		if (cole == '1') {
			cole = 'DON BOSCO A';
		}
		var datacole = {
			"cole": cole,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/ajax_get_cole'); ?>";
		if (cole != '') {
			$.ajax({

				url: url,
				type: "POST",
				dataType: "JSON",
				data: datacole,
				success: function(data) //cargado de datos del registro 
				{
					if (data.status) {

						document.getElementById("sie").value = data.data[1];
						document.getElementById("distrito").value = "1001 SUCRE";
						document.getElementById("depend").value = data.data[4];
						// document.getElementById("unidedu").value=cole;
						//document.getElementById("antsie").value=data.data[1];
						//document.getElementById("antunidadedu").value=cole;

					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					// alert('No puede obtener codigo nuevo, para el registro');
				}
			});
		}
	}

	function selcole(col) {
		var options = "<option value=''></option>";
		var datacol = {
			"col": col,
		}

		var url = "<?php echo site_url('inscrip_edit_contr/ajax_get_niveles'); ?>";
		if (col != '') {
			$.ajax({

				url: url,
				type: "POST",
				dataType: "JSON",
				data: datacol,
				success: function(data) //cargado de datos del registro 
				{
					console.log('Obtener selcole: ', data);
					if (data.status) {
						data.data.forEach(function(item) {
							if (item == 'PRIMARIA TARDE') {
								id = 'PT';
							}
							if (item == 'SECUNDARIA TARDE') {
								id = 'ST';
							}
							if (item == 'PRIMARIA MAÑANA') {
								id = 'PM';
							}
							if (item == 'SECUNDARIA MAÑANA') {
								id = 'SM';
							}
							options = options + "<option value='" + id + "'>" + item + "</option>";
						});
						document.getElementById('niveles').innerHTML = options;
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.error('ERROR selcole: ', jqXHR, textStatus, errorThrown);
					// alert('No puede obtener codigo nuevo, para el registro');
				}
			});
		}
	}

	function selnivel(level) {
		var options = "<option value=''></option>";
		var col = document.getElementById("inscole").value;

		var datalevel = {
			"level": level,
			"col": col
		}

		var url = "<?php echo site_url('inscrip_edit_contr/ajax_get_curso'); ?>";
		if (level != '') {
			$.ajax({

				url: url,
				type: "POST",
				dataType: "JSON",
				data: datalevel,
				success: function(data) //cargado de datos del registro 
				{
					if (data.status) {
						//document.getElementById("niveles").value=data.data[0]; 
						data.data.forEach(function(item) {

							options = options + "<option value='" + item + "'>" + item + "</option>";

						});
						document.getElementById('curso').innerHTML = options;
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					// alert('No puede obtener codigo nuevo, para el registro');
				}
			});
		}
		var n = level.indexOf(" ");
		var turno = level.substr(n + 1, level.length); //para guaradar turno
		_turno = turno;

	}

	function get_nivel() {
		var options = "<option value=''></option>";
		//envio de valores
		var data1 = {
			"table": "nivel",
		};

		$.ajax({

			url: "<?php echo site_url('inscrip_edit_contr/ajax_get_nivel1'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				var id;
				if (data.status) {
					data.data.forEach(function(item) {
						if (item == 'PRIMARIA TARDE') {
							id = 'PT';
						}
						if (item == 'SECUNDARIA TARDE') {
							id = 'ST';
						}
						if (item == 'PRIMARIA MAÑANA') {
							id = 'PM';
						}
						if (item == 'SECUNDARIA MAÑANA') {
							id = 'SM';
						}
						options = options + "<option value='" + id + "'>" + item + "</option>";
					});
					document.getElementById('niveles').innerHTML = options;
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});
	}

	function getidcur() {
		var niveles = document.getElementById("niveles").value;
		var curso = document.getElementById("curso").value;
		var inscole = document.getElementById("inscole").value;
		//alert(niveles+" "+curso+" "+inscole);

		var datacole = {
			"nivel": niveles,
			"curso": curso,
			"inscole": inscole
		}

		var url = "<?php echo site_url('inscrip_edit_contr/ajax_get_idcur'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			dataType: "JSON",
			data: datacole,
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {

					document.getElementById("idcurnew").value = data.data[0];

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	function getidestnew() {
		var data1 = {
			"table": "estudiante",
			"cod": "EST-",
		};

		$.ajax({

			url: "<?php echo site_url('inscrip_edit_contr/ajax_get_idestnew'); ?>",
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					//datos recuperados
					document.getElementById('idestnew').value = data.codgen;
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}


	function guardarIns() {

		var user = document.getElementById("user").value;
		var fechains = document.getElementById("fechains").value;
		var unidedu = document.getElementById("unidedu").value;
		var depend = document.getElementById("depend").value;
		var distrito = document.getElementById("distrito").value;
		var sie = document.getElementById("sie").value;
		var idcur = document.getElementById("idcur").value;


		var appat = document.getElementById("appat").value;
		var apmat = document.getElementById("apmat").value;
		var nombres = document.getElementById("nombres").value;
		var genero = document.getElementById("genero").value;
		var rude = document.getElementById("rude").value;
		var ci = document.getElementById("ci").value;
		var complemento = document.getElementById("complemento").value;
		var extension = document.getElementById("extension").value;
		var codigobanco = document.getElementById("codigobanco").value;
		var idest1 = document.getElementById("idest1").value;
		var antsie = document.getElementById("antsie").value;
		var antunidadedu = document.getElementById("antunidadedu").value;

		// var idestnew=document.getElementById("idestnew").value;
		// var idcurnew=document.getElementById("idcurnew").value;
		var gestion = document.getElementById("gestion").value;
		var inscole = document.getElementById("inscole").value;
		var niveles = document.getElementById("niveles").value;
		var curso = document.getElementById("curso").value;
		var turno = _turno;
		var nitnombre = document.getElementById("nitnombre").value;
		var nit = document.getElementById("nit").value;
		var nitcorreo = document.getElementById("nitcorreo").value;
		var pandemia_clases = document.getElementById("pandemia_clases").value;
		var pandemia_vacunas = document.getElementById("pandemia_vacunas").value;
		// var insmes=document.getElementById("insmes").value;
		// var insdia=document.getElementById("insdia").value;
		// var insanio=document.getElementById("insanio").value;
		//var numctto=document.getElementById("numctto").value; 

		var pais = document.getElementById("pais").value;
		var dpto = document.getElementById("dpto").value;
		var provincia = document.getElementById("provincia").value;
		var localidad = document.getElementById("localidad").value;
		var oficialia = document.getElementById("oficialia").value;
		var libro = document.getElementById("libro").value;
		var partida = document.getElementById("partida").value;
		var folio = document.getElementById("folio").value;

		// var nacmes=document.getElementById("nacmes").value;
		// var nacdia=document.getElementById("nacdia").value;
		// var nacanio=document.getElementById("nacanio").value; 
		// alert("asasa");
		// exit();
		var discap = document.getElementsByName("rdiscap");
		for (i = 0; i < discap.length; i++) {
			if (discap[i].checked) {
				var discap1 = discap[i].value;
			}
		}
		// if(document.getElementById("discap1").checked)
		// 	var discap1=true;
		// else var discap1=false;

		var regdiscap = document.getElementById("regdiscap").value;
		var tdiscap = document.getElementById("tdiscap").value;
		var gradodiscap = document.getElementById("gradodiscap").value;

		var locdpto = document.getElementById("locdpto").value;
		var locprovin = document.getElementById("locprovin").value;
		var locmuni = document.getElementById("locmuni").value;
		var loclocal = document.getElementById("loclocal").value;
		var loczona = document.getElementById("loczona").value;
		var loccalle = document.getElementById("loccalle").value;
		var locnum = document.getElementById("locnum").value;
		var locfono = document.getElementById("locfono").value;
		var loccel = document.getElementById("loccel").value;

		var idiomanatal = document.getElementById("idiomanatal").value;
		var idioma1 = document.getElementById("idioma1").value;
		var idioma2 = document.getElementById("idioma2").value;
		var idioma3 = document.getElementById("idioma3").value;
		var nacion = document.getElementById("nacion").value;
		var postas = document.getElementsByName("posta");
		for (i = 0; i < postas.length; i++) {
			if (postas[i].checked) {
				var posta1 = postas[i].value;
			}
		}

		// if (document.getElementById("posta1").checked)
		// 	var posta1=true;
		// else
		// 	var posta1=false;

		if (document.getElementById("visitaposta1").checked)
			var visitaposta1 = "1";
		else
			var visitaposta1 = "0";

		if (document.getElementById("visitaposta2").checked)
			var visitaposta2 = "2";
		else
			var visitaposta2 = "0";

		if (document.getElementById("visitaposta3").checked)
			var visitaposta3 = "3";
		else
			var visitaposta3 = "0";

		if (document.getElementById("visitaposta4").checked)
			var visitaposta4 = "4";
		else
			var visitaposta4 = "0";

		if (document.getElementById("visitaposta5").checked)
			var visitaposta5 = "5";
		else
			var visitaposta5 = "0";

		if (document.getElementById("visitaposta6").checked)
			var visitaposta6 = "6";
		else
			var visitaposta6 = "0";

		var veces = document.getElementById("veces").value;

		var seguros = document.getElementsByName("seguro");
		for (i = 0; i < seguros.length; i++) {
			if (seguros[i].checked) {
				var seguro1 = seguros[i].value;
			}
		}
		// if(document.getElementById("seguro1").checked)
		// 	var seguro1=true;
		// else
		// 	var seguro1=false;
		var agua = document.getElementsByName("agua");
		for (i = 0; i < agua.length; i++) {
			if (agua[i].checked) {
				var agua1 = agua[i].value;
			}
		}

		// if(document.getElementById("agua1").checked)
		// 	var agua1=true;
		// else
		// 	var agua1=false;
		var banio = document.getElementsByName("banio");
		for (i = 0; i < banio.length; i++) {
			if (banio[i].checked) {
				var banio1 = banio[i].value;
			}
		}
		// if(document.getElementById("banio1").checked)
		// 	var banio1=true;
		// else
		// 	var banio1=false;
		var alcantarillado = document.getElementsByName("alcantarillado");
		for (i = 0; i < alcantarillado.length; i++) {
			if (alcantarillado[i].checked) {
				var alcan1 = alcantarillado[i].value;
			}
		}
		// if(document.getElementById("alcan1").checked)
		// 	var alcan1=true;
		// else
		// 	var alcan1=false;
		var luz = document.getElementsByName("luz");
		for (i = 0; i < luz.length; i++) {
			if (luz[i].checked) {
				var luz1 = luz[i].value;
			}
		}
		// if(document.getElementById("luz1").checked)
		// 	var luz1=true;
		// else
		// 	var luz1=false;
		var basura = document.getElementsByName("basura");
		for (i = 0; i < basura.length; i++) {
			if (basura[i].checked) {
				var basura1 = basura[i].value;
			}
		}
		// if(document.getElementById("basura1").checked)
		// 	var basura1=true;
		// else
		// 	var basura1=false;

		var hogar = document.getElementById("hogar").value;



		if (document.getElementById("netvivienda").checked)
			var netvivienda = 1;
		else
			var netvivienda = 0;

		if (document.getElementById("netunidadedu").checked)
			var netunidadedu = 2;
		else
			var netunidadedu = 0;

		if (document.getElementById("netpublic").checked)
			var netpublic = 3;
		else
			var netpublic = 0;

		if (document.getElementById("netcelu").checked)
			var netcelu = 4;
		else
			var netcelu = 0;

		if (document.getElementById("nonet").checked)
			var nonet = 5;
		else
			var nonet = 0;

		var netfrecuencia = document.getElementById("netfrecuencia").value;

		var trabajo = document.getElementsByName("trabajo");
		for (i = 0; i < trabajo.length; i++) {
			if (trabajo[i].checked) {
				var trabajo1 = trabajo[i].value;
			}
		}
		// if(document.getElementById("trabajo1").checked)
		// 	var trabajo1=true;
		// else
		// 	var trabajo1=false;

		if (document.getElementById("ene").checked)
			var ene = 1;
		else
			var ene = 0;

		if (document.getElementById("feb").checked)
			var feb = 2;
		else
			var feb = 0;

		if (document.getElementById("mar").checked)
			var mar = 3;
		else
			var mar = 0;

		if (document.getElementById("abr").checked)
			var abr = 4;
		else
			var abr = 0;

		if (document.getElementById("may").checked)
			var may = 5;
		else
			var may = 0;

		if (document.getElementById("jun").checked)
			var jun = 6;
		else
			var jun = 0;

		if (document.getElementById("jul").checked)
			var jul = 7;
		else
			var jul = 0;

		if (document.getElementById("ago").checked)
			var ago = 8;
		else
			var ago = 0;

		if (document.getElementById("sep").checked)
			var sep = 9;
		else
			var sep = 0;

		if (document.getElementById("oct").checked)
			var oct = 10;
		else
			var oct = 0;

		if (document.getElementById("nov").checked)
			var nov = 11;
		else
			var nov = 0;

		if (document.getElementById("dic").checked)
			var dic = 12;
		else
			var dic = 0;

		var actividad = document.getElementById("actividad").value;
		var otrotrabajo = document.getElementById("otrotrabajo").value;
		if (document.getElementById("turnoman").checked)
			var turnoman = 1;
		else
			var turnoman = 0;

		if (document.getElementById("turnotar").checked)
			var turnotar = 1;
		else
			var turnotar = 0;

		if (document.getElementById("turnonoc").checked)
			var turnonoc = 1;
		else
			var turnonoc = 0;

		var trabfrecuencia = document.getElementById("trabfrecuencia").value;

		if (document.getElementById("pagotrab1").checked)
			var pagotrab1 = 1;
		else
			var pagotrab1 = 0;


		var tipopago = document.getElementById("tipopago").value;
		var transpllega = document.getElementById("transpllega").value;
		var otrollega = document.getElementById("otrollega").value;
		var tllegada = document.getElementById("tllegada").value;
		if (document.getElementById("abandono1").checked)
			var abandono1 = 1;
		else
			var abandono1 = 0;

		if (document.getElementById("razon0").checked)
			var razon0 = 1;
		else
			var razon0 = 0;

		if (document.getElementById("razon1").checked)
			var razon1 = 2;
		else
			var razon1 = 0;

		if (document.getElementById("razon2").checked)
			var razon2 = 3;
		else
			var razon2 = 0;

		if (document.getElementById("razon3").checked)
			var razon3 = 4;
		else
			var razon3 = 0;

		if (document.getElementById("razon4").checked)
			var razon4 = 5;
		else
			var razon4 = 0;

		if (document.getElementById("razon5").checked)
			var razon5 = 6;
		else
			var razon5 = 0;

		if (document.getElementById("razon6").checked)
			var razon6 = 7;
		else
			var razon6 = 0;

		if (document.getElementById("razon7").checked)
			var razon7 = 8;
		else
			var razon7 = 0;

		if (document.getElementById("razon8").checked)
			var razon8 = 9;
		else
			var razon8 = 0;

		if (document.getElementById("razon9").checked)
			var razon9 = 10;
		else
			var razon9 = 0;

		if (document.getElementById("razon10").checked)
			var razon10 = 11;
		else
			var razon10 = 0;

		if (document.getElementById("razon11").checked)
			var razon11 = 12;
		else
			var razon11 = 0;


		var otrarazon = document.getElementById("otrarazon").value;

		var t1appat = document.getElementById("t1appat").value;
		var t1apmat = document.getElementById("t1apmat").value;
		var t1nombres = document.getElementById("t1nombres").value;
		var t1ci = document.getElementById("t1ci").value;
		var t1comple = document.getElementById("t1comple").value;
		var t1extension = document.getElementById("t1exten").value;
		var t1ocup = document.getElementById("t1ocup").value;
		var t1lugartra = document.getElementById("t3lug").value;
		var t1id = document.getElementById("t1id").value;
		var t1grado = document.getElementById("t1grado").value;
		var t1idioma = document.getElementById("t1idioma").value;
		var t1ofifono = document.getElementById("t1ofifono").value;
		var t1celular = document.getElementById("t1celular").value;
		var t1fn = document.getElementById("t1fn").value;

		var t2appat = document.getElementById("t2appat").value;
		var t2apmat = document.getElementById("t2apmat").value;
		var t2nombres = document.getElementById("t2nombres").value;
		var t2ci = document.getElementById("t2ci").value;
		var t2comple = document.getElementById("t2comple").value;
		var t2extension = document.getElementById("t2exten").value;
		var t2ocup = document.getElementById("t2ocup").value;
		var t2lugartra = document.getElementById("t3lug").value;
		var t2id = document.getElementById("t2id").value;
		var t2grado = document.getElementById("t2grado").value;
		var t2idioma = document.getElementById("t2idioma").value;
		var t2ofifono = document.getElementById("t2ofifono").value;
		var t2celular = document.getElementById("t2celular").value;
		var t2fn = document.getElementById("t2fn").value;

		var t3appat = document.getElementById("t3appat").value;
		var t3apmat = document.getElementById("t3apmat").value;
		var t3nombres = document.getElementById("t3nombres").value;
		var t3ci = document.getElementById("t3ci").value;
		var t3comple = document.getElementById("t3comple").value;
		var t3extension = document.getElementById("t3exten").value;
		var t3ocup = document.getElementById("t3ocup").value;
		var t3grado = document.getElementById("t3grado").value;
		var t3idioma = document.getElementById("t3idioma").value;
		var t3ofifono = document.getElementById("t3ofifono").value;
		var t3celular = document.getElementById("t3celular").value;
		var t3fn = document.getElementById("t3fn").value;
		var t3parentesco = document.getElementById("t3parentesco").value;
		var t3lugartra = document.getElementById("t3lug").value;
		var t3id = document.getElementById("t3id").value;
		var vivecon = document.getElementById("vivecon").value;
		var cont = document.getElementsByName("contrato");
		var fnaci = document.getElementById("fnaci").value;
		for (i = 0; i < cont.length; i++) {
			if (cont[i].checked) {
				var contrato = cont[i].value;
			}
		}
		//var contrato=document.getElementsByName("contrato");
		// alert("-"+niveles);
		// alert("100000");	
		url = "<?php echo site_url('inscrip_edit_contr/ajax_save_estud'); ?>";
		// alert(fnaci);
		// exit();

		var data1 = {
			"usuario": user,
			"fechains": fechains,
			"unidedu": unidedu,
			"depend": depend,
			"distrito": distrito,
			"sie": sie,
			"idcur": idcur,
			"appaterno": appat,
			"apmaterno": apmat,
			"nombres": nombres,
			"genero": genero,
			"rude": rude,
			"ci": ci,
			"complemento": complemento,
			"extension": extension,
			"codigobanco": codigobanco,
			"idest1": idest1,
			"antsie": antsie,
			"antunidadedu": antunidadedu,
			// "idestnew":idestnew,
			// "idcurnew":idcurnew,
			"gestion": gestion,
			"inscole": inscole,
			"colegio": inscole,
			"nivel": niveles,
			"curso": curso,
			"turno": turno,
			"nitnombre": nitnombre,
			"nit": nit,
			"nitcorreo": nitcorreo,
			"pandemia_clases": pandemia_clases,
			"pandemia_vacunas": pandemia_vacunas,
			// "insmes":insmes,
			// "insdia":insdia,
			// "insanio":insanio,
			// "numctto":numctto,
			"pais": pais,
			"dpto": dpto,
			"provincia": provincia,
			"localidad": localidad,
			"oficialia": oficialia,
			"libro": libro,
			"partida": partida,
			"folio": folio,
			// "nacmes":nacmes,
			// "nacdia":nacdia,
			// "nacanio":nacanio,
			"discap1": discap1,
			"regdiscap": regdiscap,
			"tdiscap": tdiscap,
			"gradodiscap": gradodiscap,
			"locdpto": locdpto,
			"locprovin": locprovin,
			"locmuni": locmuni,
			"loclocal": loclocal,
			"loczona": loczona,
			"loccalle": loccalle,
			"locnum": locnum,
			"locfono": locfono,
			"loccel": loccel,
			"idiomanatal": idiomanatal,
			"idioma1": idioma1,
			"idioma2": idioma2,
			"idioma3": idioma3,
			"nacion": nacion,
			"posta1": posta1,
			"visitaposta1": visitaposta1,
			"visitaposta2": visitaposta2,
			"visitaposta3": visitaposta3,
			"visitaposta4": visitaposta4,
			"visitaposta5": visitaposta5,
			"visitaposta6": visitaposta6,
			"veces": veces,
			"seguro1": seguro1,
			"agua1": agua1,
			"banio1": banio1,
			"alcanta1": alcan1,
			"luz1": luz1,
			"basura1": basura1,
			"hogar": hogar,
			"netvivienda": netvivienda,
			"netunidadedu": netunidadedu,
			"netpublic": netpublic,
			"netcelu": netcelu,
			"nonet": nonet,
			"netfrecuencia": netfrecuencia,
			"trabajo1": trabajo1,
			"ene": ene,
			"feb": feb,
			"mar": mar,
			"abr": abr,
			"may": may,
			"jun": jun,
			"jul": jul,
			"ago": ago,
			"sep": sep,
			"oct": oct,
			"nov": nov,
			"dic": dic,
			"actividad": actividad,
			"otrotrabajo": otrotrabajo,
			"turnoman": turnoman,
			"turnotar": turnotar,
			"turnonoc": turnonoc,
			"trabfrecuencia": trabfrecuencia,
			"pagotrab1": pagotrab1,
			"tipopago": tipopago,
			"transpllega": transpllega,
			"otrollega": otrollega,
			"tllegada": tllegada,
			"abandono1": abandono1,
			"razon0": razon0,
			"razon1": razon1,
			"razon2": razon2,
			"razon3": razon3,
			"razon4": razon4,
			"razon5": razon5,
			"razon6": razon6,
			"razon7": razon7,
			"razon8": razon8,
			"razon9": razon9,
			"razon10": razon10,
			"razon11": razon11,
			"otrarazon": otrarazon,
			"t1appat": t1appat,
			"t1apmat": t1apmat,
			"t1nombres": t1nombres,
			"t1ci": t1ci,
			"t1comple": t1comple,
			"t1extension": t1extension,
			"t1ocup": t1ocup,
			"t1id": t1id,
			"t1lugartra": t1lugartra,
			"t1grado": t1grado,
			"t1idioma": t1idioma,
			"t1ofifono": t1ofifono,
			"t1celular": t1celular,
			"t1fn": t1fn,
			"t2appat": t2appat,
			"t2apmat": t2apmat,
			"t2nombres": t2nombres,
			"t2ci": t2ci,
			"t2comple": t2comple,
			"t2extension": t2extension,
			"t2ocup": t2ocup,
			"t2id": t2id,
			"t2lugartra": t2lugartra,
			"t2grado": t2grado,
			"t2idioma": t2idioma,
			"t2ofifono": t2ofifono,
			"t2celular": t2celular,
			"t2fn": t2fn,
			"t3appat": t3appat,
			"t3apmat": t3apmat,
			"t3nombres": t3nombres,
			"t3ci": t3ci,
			"t3comple": t3comple,
			"t3extension": t3extension,
			"t3ocup": t3ocup,
			"t3id": t3id,
			"t3grado": t3grado,
			"t3lugartra": t3lugartra,
			"t3idioma": t3idioma,
			"t3ofifono": t3ofifono,
			"t3celular": t3celular,
			"t3fn": t3fn,
			"t3parentesco": t3parentesco,
			"vivecon": vivecon,
			"contrato": contrato,
			"fnaci": fnaci
		};


		/*alert(user+"-"+fechains+"-"+unidedu+"-"+depend+"-"+distrito+"-"+sie+"-"+idcur+"-"+appat+"-"+apmat+"-"+nombres+"-"+genero+"-"+rude+"-"+
				ci+"-"+complemento+"-"+extension+"-"+codigobanco+"-"+idest1+"-"+antsie+"-"+antunidadedu+"-"+idestnew+"-"+idcurnew+"-"+gestion+"-"+inscole+"-"+inscole+"-"+niveles+"-"+curso+"-"+turno+"-"+nitnombre+"-"+nit+"-"+insmes+"-"+insdia+"-"+insanio+"-"+numctto+"-"+pais+"-"+dpto+"-"+provincia+"-"+localidad+"-"+oficialia+"-"+libro+"-"+
				partida+"-"+folio+"-"+nacmes+"-"+nacdia+"-"+nacanio+"-"+discap1+"-"+regdiscap+"-"+tdiscap+"-"+gradodiscap+"-"+locdpto+"-"+locprovin+"-"+locmuni+"-"+loclocal+"-"+loczona+"-"+loccalle+"-"+locnum+"-"+
				locfono+"-"+loccel+"-"+idiomanatal+"-"+idioma1+"-"+idioma2+"-"+idioma3+"-"+nacion+"-"+posta1+"-"+visitaposta1+"-"+visitaposta2+"-"+
				visitaposta3+"-"+visitaposta4+"-"+visitaposta5+"-"+visitaposta6+"-"+
				veces+"-"+seguro1+"-"+agua1+"-"+banio1+"-"+alcan1+"-"+luz1+"-"+
				basura1+"-"+hogar+"-"+netvivienda+"-"+netunidadedu+"-"+netpublic+"-"+
				netcelu+"-"+nonet+"-"+netfrecuencia+"-"+trabajo1+"-"+ene+"-"+feb+"-"+mar+"-"+abr+"-"+may+"-"+jun+"-"+jul+"-"+ago+"-"+sep+"-"+oct+"-"+nov+"-"+dic+"-"+actividad+"-"+otrotrabajo+"-"+turnoman+"-"+turnotar+"-"+turnonoc+"-"+trabfrecuencia+"-"+pagotrab1+"-"+
				tipopago+"-"+transpllega+"-"+otrollega+"-"+tllegada+"-"+abandono1+"-"+razon0+"-"+razon1+"-"+razon2+"-"+razon3+"-"+razon4+"-"+razon5+"-"+razon6+"-"+razon7+"-"+razon8+"-"+razon9+"-"+razon10+"-"+razon11+"-"+otrarazon+"-"+t1appat+"-"+t1apmat+"-"+t1nombres+"-"+t1ci+"-"+t1comple+"-"+t1extension+"-"+t1ocup+"-"+t1grado+"-"+t1idioma+"-"+t1ofifono+"-"+t1celular+"-"+t1fnacmes+"-"+t1fnacdia+"-"+
				t1fnacanio+"-"+t2appat+"-"+t2apmat+"-"+t2nombres+"-"+t2ci+"-"+
				t2comple+"-"+t2extension+"-"+t2ocup+"-"+t2grado+"-"+t2idioma+"-"+
				t2ofifono+"-"+t2celular+"-"+t2fnacmes+"-"+t2fnacdia+"-"+t2fnacanio+"-"+
				t3appat+"-"+t3apmat+"-"+t3nombres+"-"+t3ci+"-"+t3comple+"-"+t3extension+"-"+t3ocup+"-"+t3grado+"-"+t3idioma+"-"+t3ofifono+"-"+t3celular+"-"+t3fnacmes+"-"+t3fnacdia+"-"+t3fnacanio+"-"+t3parentesco+"-"+vivecon);	*/

		/*
				alert(data1.usuario+"-"+data1.fechains+"-"+data1.unidedu+"-"+data1.depend+"-"+data1.distrito+"-"+data1.sie+"-"+data1.idcur+"-"+data1.appaterno+"-"+data1.apmaterno+"-"+data1.nombres+"-"+data1.genero+"-"+data1.rude+"-"+
					data1.ci+"-"+data1.complemento+"-"+data1.extension+"-"+data1.codigobanco+"-"+data1.idest1+"-"+data1.antsie+"-"+data1.antunidadedu+"-"+data1.idestnew+"-"+data1.idcurnew+"-"+data1.gestion+"-"+data1.inscole+"-"+data1.colegio+"-"+data1.nivel+"-"+data1.curso+"-"+data1.turno+"-"+data1.nitnombre+"-"+data1.nit+"-"+data1.insmes+"-"+data1.insdia+"-"+data1.insanio+"-"+data1.numctto+"-"+data1.pais+"-"+data1.dpto+"-"+data1.provincia+"-"+data1.localidad+"-"+data1.oficialia+"-"+data1.libro+"-"+
					data1.partida+"-"+data1.folio+"-"+data1.nacmes+"-"+data1.nacdia+"-"+data1.nacanio+"-"+data1.discap1+"-"+data1.regdiscap+"-"+data1.tdiscap+"-"+data1.gradodiscap+"-"+data1.locdpto+"-"+data1.locprovin+"-"+data1.locmuni+"-"+data1.loclocal+"-"+data1.loczona+"-"+data1.loccalle+"-"+data1.locnum+"-"+
					data1.locfono+"-"+data1.loccel+"-"+data1.idiomanatal+"-"+data1.idioma1+"-"+data1.idioma2+"-"+data1.idioma3+"-"+data1.nacion+"-"+data1.posta1+"-"+data1.visitaposta1+"-"+data1.visitaposta2+"-"+
					data1.visitaposta3+"-"+data1.visitaposta4+"-"+data1.visitaposta5+"-"+data1.visitaposta6+"-"+
					data1.veces+"-"+data1.seguro1+"-"+data1.agua1+"-"+data1.banio1+"-"+data1.alcanta1+"-"+data1.luz1+"-"+
					data1.basura1+"-"+data1.hogar+"-"+data1.netvivienda+"-"+data1.netunidadedu+"-"+data1.netpublic+"-"+
					data1.netcelu+"-"+data1.nonet+"-"+data1.netfrecuencia+"-"+data1.trabajo1+"-"+data1.ene+"-"+data1.feb+"-"+data1.mar+"-"+data1.abr+"-"+data1.may+"-"+data1.jun+"-"+data1.jul+"-"+data1.ago+"-"+data1.sep+"-"+data1.oct+"-"+data1.nov+"-"+data1.dic+"-"+data1.actividad+"-"+data1.otrotrabajo+"-"+data1.turnoman+"-"+data1.turnotar+"-"+data1.turnonoc+"-"+data1.trabfrecuencia+"-"+data1.pagotrab1+"-"+
					data1.tipopago+"-"+data1.transpllega+"-"+data1.otrollega+"-"+data1.tllegada+"-"+data1.abandono1+"-"+data1.razon0+"-"+data1.razon1+"-"+data1.razon2+"-"+data1.razon3+"-"+data1.razon4+"-"+data1.razon5+"-"+data1.razon6+"-"+data1.razon7+"-"+data1.razon8+"-"+data1.razon9+"-"+data1.razon10+"-"+data1.razon11+"-"+data1.otrarazon+"-"+data1.t1appat+"-"+data1.t1apmat+"-"+data1.t1nombres+"-"+data1.t1ci+"-"+data1.t1comple+"-"+data1.t1extension+"-"+data1.t1ocup+"-"+data1.t1grado+"-"+data1.t1idioma+"-"+data1.t1ofifono+"-"+data1.t1celular+"-"+data1.t1fnacmes+"-"+data1.t1fnacdia+"-"+
					data1.t1fnacanio+"-"+data1.t2appat+"-"+data1.t2apmat+"-"+data1.t2nombres+"-"+data1.t2ci+"-"+
					data1.t2comple+"-"+data1.t2extension+"-"+data1.t2ocup+"-"+data1.t2grado+"-"+data1.t2idioma+"-"+
					data1.t2ofifono+"-"+data1.t2celular+"-"+data1.t2fnacmes+"-"+data1.t2fnacdia+"-"+data1.t2fnacanio+"-"+
					data1.t3appat+"-"+data1.t3apmat+"-"+data1.t3nombres+"-"+data1.t3ci+"-"+data1.t3comple+"-"+data1.t3extension+"-"+data1.t3ocup+"-"+data1.t3grado+"-"+data1.t3idioma+"-"+data1.t3ofifono+"-"+data1.t3celular+"-"+data1.t3fnacmes+"-"+data1.t3fnacdia+"-"+data1.t3fnacanio+"-"+data1.t3parentesco+"-"+data1.vivecon);
		*/
		$.ajax({

			url: url,
			type: "POST",
			data: data1,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				console.log('Editar Inscripcion: ', data);
				if (data.status) {
					swal({
						title: "Inscripción Guardada!",
						text: "Registro Guardado, Satisfactoriamente!",
						confirmButtonColor: "#66BB6A",
						type: "success"
					});
					_idinscrip = data.idinscrip;
					document.getElementById("btnrude").disabled = false;
					document.getElementById("btnctto").disabled = false;
					document.getElementById("btnrude1").disabled = false;
					document.getElementById("btnctto1").disabled = false;
					//document.getElementById("btncodigo").disabled = false;         			   
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('error al enviar data');
			}
		});

		document.getElementById("btnguardar").disabled = true;
		document.getElementById("btnguardar1").disabled = true;

	}

	//ventana para el pdf
	function export_pdf() {
		var id_est = _idinscrip;
		var gestion = document.getElementById("gestion").value;
		var idins = id_est + "-" + gestion + "-";
		var url = "<?php echo site_url('inscrip_contr/print_rude'); ?>/" + idins;


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
		var id_est = _idinscrip;
		var gestion = document.getElementById("gestion").value;

		var url = "<?php echo site_url('inscrip_edit_contr/print_ctto'); ?>/" + gestion + "-" + id_est + "-";

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



	//************************************************************************************
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

		var url = "<?php echo site_url('Reg_inscrip_edit_contr/ajax_get_idcurso'); ?>";

		$.ajax({

			url: url,
			type: "POST",
			data: dataestu,
			dataType: "JSON",
			success: function(data) //cargado de datos del registro 
			{
				if (data.status) {
					cargarBusqueda(data.data[0]);
					_global_idcur = data.data[0];
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// alert('No puede obtener codigo nuevo, para el registro');
			}
		});

	}

	//abrir archivo
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



	//cargar tabla
	function reload_estud() {
		testudiante.ajax.reload(null, false);

	}
</script>
