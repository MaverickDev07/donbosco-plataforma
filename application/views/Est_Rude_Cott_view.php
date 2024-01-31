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
									<a href='<?php echo site_url('Est_estudiante_contr');?>'><i class="icon-user-lock"></i> <span>Alumnos</span></a>									
								</li>
								<!--<li>
									<a href='<?php //echo site_url('Config_colegios_contr');?>'><i class="icon-users4"></i> <span>Colegios</span></a>								
								</li>
								<li>
									<a href='<?php// echo site_url('Config_nivel_contr');?>'><i class="icon-users4"></i> <span>Nivel</span></a>								
								</li>
								<li>
									<a href='<?php //echo site_url('Config_curso_contr');?>'><i class="icon-user-minus"></i> <span>Cursos</span></a>									
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
									<img src="assets/images/logo1.png" alt="" width="65" height="75" >									
									<small class="display-block">Gestión 2021</small>

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
			                                   		<button class="btn  bg-danger-400" onclick="export_pdf()" id="btnrude" ><i class="icon icon-file-pdf"></i>&nbsp;Imprimir RUDE</button>
			                                    </div>
											</div>

											<div class="col-md-3">
												<div class="form-group">
													<button class="btn  bg-primary-400" onclick="print_ctto()" id="btnctto"><i class="icon icon-file-pdf"></i>&nbsp;Imprimir CTTO</button>
			                                    </div>
											</div>	
									</div>	
									<legend class="primary-400"></legend>
				                    <!-- inscripcion -->
				                    <form class="steps-validation" action="#">

				                   
										<div class="col-md-12">
													<div class="form-group">
														<h1>USTED YA ESTA INSCRITO </h1>
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
														<input type="text" id="appat" class="form-control required" readonly="true">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label>Ap.Materno: <span class="text-danger">*</span></label>
														<input type="text" id="apmat" class="form-control required" readonly="true">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label>Nombres: <span class="text-danger">*</span></label>
														<input type="text" id="nombres" class="form-control required" readonly="true">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label>Genero: <span class="text-danger">*</span></label>
														<div class="form-group">
															<select id="genero" class="form-control required" readonly="true">
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
														<input type="text" id="rude" class="form-control required" placeholder="Number" readonly="true">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label>CARNET DE IDENTIDAD (CI): <span class="text-danger">*</span></label>
														<input type="text" id="ci" class="form-control required" placeholder="Carnet" readonly="true">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label>Complemento (Caso duplicado): <span class="text-danger">*</span></label>
														<input type="text" id="complemento" class="form-control" placeholder="Carnet" readonly="true">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label>Extension: <span class="text-danger">*</span></label>	
														<select id='extension' class="form-control" readonly="true">
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
														<input type="text" id="codigobanco" class="form-control required" readonly="true">
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
										<div class="col-lg-12">
									               						
											<div class="col-md-12">
												<div class="col-md-3">
													<div class="form-group">
														<label>Pais: <span class="text-danger">*</span></label>
														<input type="text" id='pais' class="form-control required" readonly="true">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label>Depto: </label>
														<input type="text" id='dpto' class="form-control required" readonly="true">
																											
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label>Provincia: <span class="text-danger">*</span></label>
														<input type="text" id='provincia' class="form-control required" readonly="true">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label>Localidad: <span class="text-danger">*</span></label>
														<input type="text" id='localidad' class="form-control required" readonly="true">
													</div>
												</div>
											</div>
											<legend class="text-bold">Certificado de Nacimiento</legend>
											<div class="col-md-12">	
												<div class="col-md-3">
													<div class="form-group">
														<label>Oficialia: <span class="text-danger">*</span></label>
														<input type="text" id='oficialia' class="form-control required" readonly="true">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label>Libro: <span class="text-danger">*</span></label>
														<input type="text" id='libro' class="form-control required" readonly="true">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label>Partida: <span class="text-danger">*</span></label>
														<input type="text" id='partida' class="form-control required" readonly="true">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label>Folio: <span class="text-danger">*</span></label>
														<input type="text" id='folio' class="form-control required" readonly="true">
													</div>
												</div>
											</div>
											<legend class="text-bold">Fecha de Nacimiento</legend>
											<div class="col-md-12">
												<div class="col-md-5">
													<div class="form-group">
														<label>fecha: <span class="text-danger">*</span></label>
														<input type="date" id='fnaci' class="form-control required" readonly="true">
													</div>
					                            </div>
					                            <div class="col-md-5">
													<label>Gestion: <span class="text-danger">*</span></label>
														<div class="form-group">
															<select id="gestion" class="form-control required" readonly="true">
																<option>2021</option>
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
													</div>
												
										
										
									
									</form>

					<div class="row">
						<div class="col-md-12"> 
						</div>

						<div class="col-md-3"> 
							<div class="form-group">
                           		<button class="btn  bg-danger-400" onclick="export_pdf()" id="btnrude1" ><i class="icon icon-file-pdf"></i>&nbsp;Imprimir RUDE</button>
                            </div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<button class="btn  bg-primary-400" onclick="print_ctto()" id="btnctto1"><i class="icon icon-file-pdf"></i>&nbsp;Imprimir CTTO</button>
                            </div>
						</div>	
					</div>	
								</div>
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
					&copy; 2019. <a href="#">Sistema de Control Académico "DON BOSCO"</a> by <a href="donboscosucre.edu.bo" target="_blank">Departamento de Informatica</a>
				</div>
		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->
</body>

<script type="text/javascript">
	var testudiante;
	var save_method;
	var _global_idcur="";
	var _turno="";
	var _idinscrip="";

	$(document).ready(function(){
		
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
		document.getElementById("insdia").value=d.getDate();
		document.getElementById("insmes").value=d.getMonth()+1;
		document.getElementById("insanio").value=d.getFullYear();
		getusuario();			
		getnumctto();

		

	});

	function cerrar()
	{		
		var url="<?php echo site_url('Reg_inscrip_contr/ajax_cerrar')?>";
		//alert(url);
		
		$.ajax({
	        url : url,
	        type: "POST",
	        data: {},
	        dataType: "JSON",
	        success: function(data)
	        {
	        	
	        }
    	});
    	var enlace="<?php echo  base_url();?>";
	    window.location.replace(enlace);
    	
	}

	function getusuario()
	{
		var url="<?php echo site_url('Est_Inscrip_contr/ajax_usuario')?>";
		//alert(url);
		$.ajax({
	        url : url,
	        type: "POST",
	        data: {},
	        dataType: "JSON",
	        success: function(data)
	        {
	        	if(data.status)
	           {	
	           		document.getElementById('user').value=data.data[0];	           		
	           }
	           
	        }
    	});
	}

	function getnumctto()
	{
		var url="<?php echo site_url('Est_Inscrip_contr/ajax_get_numctto')?>";
		//alert(url);
		$.ajax({
	        url : url,
	        type: "POST",
	        data: {},
	        dataType: "JSON",
	        success: function(data)
	        {
	        	if(data.status)
	           {	
	           		document.getElementById('numctto').value=data.data;           		
	           }
	           
	        }
    	});
	}

	//para estudiante
	function gestnacimiento()
	{
		
		var idest=document.getElementById('idest').value;		
		

		var dataest={
			"idest":idest,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/getnaci');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		// cargarBusqueda(data.data[0]);
	           		// _global_idcur=data.data[0];
	           		

	           		document.getElementById("pais").value=data.data[0];
	           		document.getElementById("dpto").value=data.data[1];
	           		document.getElementById("provincia").value=data.data[2];
	           		document.getElementById("localidad").value=data.data[3];
	           		document.getElementById("oficialia").value=data.data[4];
	           		document.getElementById("libro").value=data.data[5];
	           		document.getElementById("partida").value=data.data[6];
	           		document.getElementById("folio").value=data.data[7];
	           		document.getElementById("fnaci").value=data.data[8];
	           		//getUnidad();
	           		//alert(data.data[0]);

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}
	function getcultura()
	{
		
		var idest=document.getElementById('idest').value;		
		var gestion=document.getElementById('gestion').value;

		var dataest={
			"idest":idest,
			"gestion":gestion,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/getcultura');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	
	           		document.getElementById("idiomanatal").value=data.data[0];
	           		document.getElementById("idioma1").value=data.data[1];
	           		document.getElementById("idioma2").value=data.data[2];
	           		document.getElementById("idioma3").value=data.data[3];
	           		document.getElementById("nacion").value=data.data[4];
	           		//getUnidad();
	           		//alert(data.data[0]);

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}
	function getvive()
	{
		
		var idest=document.getElementById('idest').value;		
		var gestion=document.getElementById('gestion').value;

		var dataest={
			"idest":idest,
			"gestion":gestion,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/getvive');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	
	           		// document.getElementById("posta").value=data.data[0];
	           		document.getElementById("vivecon").value=data.data[0];
	           		// document.getElementById("unidedu").value=data.data[1];
	           		// selectUnid(data.data[1]);
	           		// document.getElementById("niveles").value=data.data[2];
	           		// document.getElementById("seguro").value=data.data[2];
	           		
	           		//getUnidad();
	           		//alert(data.data[0]);


	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}
	function getfactura()
	{
		
		var idest=document.getElementById('idest').value;		
		var gestion=document.getElementById('gestion').value;

		var dataest={
			"idest":idest,
			"gestion":gestion,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/getfactura');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	
	           		// document.getElementById("posta").value=data.data[0];
	           		document.getElementById("nitnombre").value=data.data[0];
	           		document.getElementById("nit").value=data.data[1];
	           		// document.getElementById("seguro").value=data.data[2];
	           		//getUnidad();
	           		//alert(data.data[0]);

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}
	function getsalud()
	{
		
		var idest=document.getElementById('idest').value;		
		var gestion=document.getElementById('gestion').value;

		var dataest={
			"idest":idest,
			"gestion":gestion,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/getsalud');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	
	           		// document.getElementById("posta").value=data.data[0];
	           		document.getElementById("veces").value=data.data[1];
	           		// document.getElementById("seguro").value=data.data[2];
	           		//getUnidad();
	           		//alert(data.data[0]);

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}

	function getdirecion()
	{
		
		var idest=document.getElementById('idest').value;		
		var gestion=document.getElementById('gestion').value;

		var dataest={
			"idest":idest,
			"gestion":gestion,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/getdirecion');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	 
	           		document.getElementById("locdpto").value=data.data[0];
	           		document.getElementById("locprovin").value=data.data[1];
	           		document.getElementById("locmuni").value=data.data[2];
	           		document.getElementById("loclocal").value=data.data[3];
	           		document.getElementById("loczona").value=data.data[4];
	           		document.getElementById("loccalle").value=data.data[5];
	           		document.getElementById("locnum").value=data.data[6];
	           		document.getElementById("locfono").value=data.data[7];
	           		document.getElementById("loccel").value=data.data[8];
	           		//getUnidad();
	           		//alert(data.data[0]);

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}
	function getpadres()
	{
		var options="<option value=''></option>";
		//var idcur=document.getElementById('idcur').value;
		var idest=document.getElementById('idest').value;		
		var gestion=document.getElementById('gestion').value;

		var dataest={
			"idest":idest,
			"gestion":gestion,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/getpadres');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		// //document.getElementById("unidedu").value=data.data[0];

	           		// data.data.forEach(function(item){
	           			
	           		// 	options=options+"<option value='"+item+"'>"+item+"</option>";
	           			           			
	           		// });
	           		// document.getElementById('unidedu').innerHTML=options;
	           		// //document.getElementById('inscole').innerHTML=options;
	           		var i=0;
	           		data.data.forEach(function(item){
	           			// alert(data.data[i]);
	           			if(data.data[i]=='MADRE'){i++;   getmadre1(data.data[i]); i++;}
	           			if(data.data[i]=='PADRE'){i++;  getpadre1(data.data[i]); i++;}
	           			if(data.data[i]=='TUTOR'){i++;  getptutor1(data.data[i]); i++;}
	           			// if(item=='TECNICO HUMANISTICO DON BOSCO'){id='3';}
	           			// if(item=='DON BOSCO B'){id='2';}
	           			// if(item=='DON BOSCO A'){id='1';}
		           		// options=options+"<option value='"+id+"'>"+item+"</option>";     			
	           		});
	           		// document.getElementById("unidedu").innerHTML=options;  
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}
	function getptutor1(id_padres)
	{
		
		var idest=document.getElementById('idest').value;		
		

		var dataest={
			"id_padre":id_padres,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/padres');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	
	           		document.getElementById("t3appat").value=data.data[0];
	           		document.getElementById("t3apmat").value=data.data[1];
	           		document.getElementById("t3nombres").value=data.data[2];
	           		document.getElementById("t3ci").value=data.data[7];
	           		document.getElementById("t3comple").value=data.data[8];
	           		document.getElementById("t3exten").value=data.data[9];
	           		document.getElementById("t3idioma").value=data.data[3];
	           		document.getElementById("t3ocup").value=data.data[4];
	           		document.getElementById("t3grado").value=data.data[5];
	           		document.getElementById("t3celular").value=data.data[11];
	           		document.getElementById("t3ofifono").value=data.data[10];
	           		document.getElementById("t3fn").value=data.data[6];
	           		document.getElementById("t3parentesco").value=data.data[12];
	           		document.getElementById("t3lug").value=data.data[12];
	           		document.getElementById("t3id").value=data.data[14];

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}
	function getmadre1(id_padres)
	{
		
		var idest=document.getElementById('idest').value;		
		

		var dataest={
			"id_padre":id_padres,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/padres');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	
	           		document.getElementById("t2appat").value=data.data[0];
	           		document.getElementById("t2apmat").value=data.data[1];
	           		document.getElementById("t2nombres").value=data.data[2];
	           		document.getElementById("t2ci").value=data.data[7];
	           		document.getElementById("t2comple").value=data.data[8];
	           		document.getElementById("t2exten").value=data.data[9];
	           		document.getElementById("t2idioma").value=data.data[3];
	           		document.getElementById("t2ocup").value=data.data[4];
	           		document.getElementById("t2grado").value=data.data[5];
	           		document.getElementById("t2celular").value=data.data[11];
	           		document.getElementById("t2ofifono").value=data.data[10];
	           		document.getElementById("t2fn").value=data.data[6];
	           		document.getElementById("t2lug").value=data.data[12];
	           		document.getElementById("t2id").value=data.data[14];

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}
	function getpadre1(id_padres)
	{
		
		var idest=document.getElementById('idest').value;		
		

		var dataest={
			"id_padre":id_padres,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/padres');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	
	           		document.getElementById("t1appat").value=data.data[0];
	           		document.getElementById("t1apmat").value=data.data[1];
	           		document.getElementById("t1nombres").value=data.data[2];
	           		document.getElementById("t1ci").value=data.data[7];
	           		document.getElementById("t1comple").value=data.data[8];
	           		document.getElementById("t1exten").value=data.data[9];
	           		document.getElementById("t1idioma").value=data.data[3];
	           		document.getElementById("t1ocup").value=data.data[4];
	           		document.getElementById("t1grado").value=data.data[5];
	           		document.getElementById("t1celular").value=data.data[11];
	           		document.getElementById("t1ofifono").value=data.data[10];
	           		document.getElementById("t1fn").value=data.data[6];
	           		document.getElementById("t1lug").value=data.data[12];
	           		document.getElementById("t1id").value=data.data[14];

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}
	function gestdic()
	{
		
		var idest=document.getElementById('idest').value;		
		

		var dataest={
			"idest":idest,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/gestdic');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		// cargarBusqueda(data.data[0]);
	           		// _global_idcur=data.data[0];
	           		// discap=document.getElementsByName("rdiscap");
	           		if(data.data[0]){
	           			document.getElementById("discap1").checked="checked";
	           		}else{
	           			document.getElementById("discap2").checked="checked";
	           		}
	       //     		for(i=0; i<discap.length; i++){
				    //     if(discap[i].checked){
				    //         var discap1=discap[i].value;
				    //     }
			    	// }

	           		// document.getElementsByName("rdiscap").value=data.data[0];
	           		// document.getElementById("discap1").value=data.data[0];
	           		document.getElementById("regdiscap").value=data.data[1];
	           		document.getElementById("tdiscap").value=data.data[2];
	           		document.getElementById("gradodiscap").value=data.data[3];
	           		//getUnidad();
	           		//alert(data.data[0]);

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}

	function getstuden()
	{
		
		var idest=document.getElementById('idest').value;		
		
		var gestion=document.getElementById('gestion').value;	
		var dataest={
			"idest":idest,
			"gestion":gestion,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/getstuden');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		// cargarBusqueda(data.data[0]);
	           		// _global_idcur=data.data[0];
	           		document.getElementById("rude").value=data.data[0];
	           		document.getElementById("ci").value=data.data[1];
	           		document.getElementById("complemento").value=data.data[2];
	           		document.getElementById("extension").value=data.data[3];
	           		document.getElementById("appat").value=data.data[4];
	           		document.getElementById("apmat").value=data.data[5];
	           		document.getElementById("nombres").value=data.data[6];
	           		document.getElementById("genero").value=data.data[7];
	           		document.getElementById("codigobanco").value=data.data[8];
	           		document.getElementById("idest1").value=data.data[9];
	           		//getUnidad();
	           		//alert(data.data[0]);

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}
	function UnidadEdu()
	{
		
		var idest=document.getElementById('idest').value;		
		

		var dataest={
			"idest":idest,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/ajax_get_unidedu');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		// cargarBusqueda(data.data[0]);
	           		// _global_idcur=data.data[0];
	           		
	           		//document.getElementById("idcur").value=data.data[0];
	           		document.getElementById("rude").value=data.data[1];
	           		document.getElementById("ci").value=data.data[2];
	           		document.getElementById("appat").value=data.data[3];
	           		document.getElementById("apmat").value=data.data[4];
	           		document.getElementById("nombres").value=data.data[5];
	           		document.getElementById("genero").value=data.data[6];
	           		document.getElementById("codigobanco").value=data.data[7];
	           		document.getElementById("idest1").value=data.data[8];
	           		document.getElementById("vivecon").value=data.data[9];
	           		//getUnidad();
	           		//alert(data.data[0]);

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}

	function AntUnidadEdu()
	{
		
		var idest=document.getElementById('idest').value;
		var gestion=document.getElementById('gestion').value;		
		

		var dataest={
			"idest":idest,
			"gestion":gestion,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/ajax_get_antunidedu');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		// cargarBusqueda(data.data[0]);
	           		// _global_idcur=data.data[0];
	           		
	           		document.getElementById("antunidadedu").value=data.data[1];
	           		document.getElementById("antsie").value=data.data[0];
	           		//getUnidad();
	           		//alert(data.data[0]);

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}
	function getdiscapacida()
	{
		
		var idest=document.getElementById('idest').value;		
		

		var dataest={
			"idest":idest,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/ajax_get_discapacida');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		// cargarBusqueda(data.data[0]);
	           		// _global_idcur=data.data[0];
	           		
	           		//document.getElementById("antunidadedu").value=data.data[0];
	           		document.getElementById("regdiscap").value=data.data[1];
	           		document.getElementById("tdiscap").value=data.data[2];
	           		document.getElementById("gradodiscap").value=data.data[3];
	           		//getUnidad();
	           		//alert(data.data[0]);

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}
	function getnacimiento()
	{
		
		var idest=document.getElementById('idest').value;		
		

		var dataest={
			"idest":idest,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/ajax_get_nacimiento');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		// cargarBusqueda(data.data[0]);
	           		// _global_idcur=data.data[0];
	           		document.getElementById("oficialia").value=data.data[0];
	           		document.getElementById("libro").value=data.data[1];
	           		document.getElementById("partida").value=data.data[2];
	           		document.getElementById("folio").value=data.data[3];
	           		document.getElementById("pais").value=data.data[4];
	           		document.getElementById("dpto").value=data.data[5];
	           		document.getElementById("provincia").value=data.data[6];
	           		document.getElementById("localidad").value=data.data[7];
	           		document.getElementById("nacdia").value=data.data[8];
	           		document.getElementById("nacmes").value=data.data[9];
	           		document.getElementById("nacanio").value=data.data[10];
	           		//getUnidad();
	           		//alert(data.data[0]);

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}
	function getpadre()
	{
		
		var idest=document.getElementById('idest').value;		
		

		var dataest={
			"idest":idest,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/ajax_get_padre');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		// cargarBusqueda(data.data[0]);
	           		// _global_idcur=data.data[0];
	           		document.getElementById("t1appat").value=data.data[0];
	           		document.getElementById("t1apmat").value=data.data[1];
	           		document.getElementById("t1nombres").value=data.data[2];
	           		document.getElementById("t1ci").value=data.data[3];
	           		document.getElementById("t1comple").value=data.data[4];
	           		document.getElementById("t1exten").value=data.data[5];
	           		document.getElementById("t1idioma").value=data.data[6];
	           		document.getElementById("t1ocup").value=data.data[7];
	           		document.getElementById("t1grado").value=data.data[8];
	           		document.getElementById("t1celular").value=data.data[9];
	           		document.getElementById("t1ofifono").value=data.data[10];
	           		document.getElementById("t1fnacdia").value=data.data[11];
	           		document.getElementById("t1fnacmes").value=data.data[12];
	           		document.getElementById("t1fnacanio").value=data.data[13];

	           		//getUnidad();
	           		//alert(data.data[0]);

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}
	function getmadre()
	{
		
		var idest=document.getElementById('idest').value;		
		

		var dataest={
			"idest":idest,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/ajax_get_madre');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		// cargarBusqueda(data.data[0]);
	           		// _global_idcur=data.data[0];
	           		document.getElementById("t2appat").value=data.data[0];
	           		document.getElementById("t2apmat").value=data.data[1];
	           		document.getElementById("t2nombres").value=data.data[2];
	           		document.getElementById("t2ci").value=data.data[3];
	           		document.getElementById("t2comple").value=data.data[4];
	           		document.getElementById("t2exten").value=data.data[5];
	           		document.getElementById("t2idioma").value=data.data[6];
	           		document.getElementById("t2ocup").value=data.data[7];
	           		document.getElementById("t2grado").value=data.data[8];
	           		document.getElementById("t2celular").value=data.data[9];
	           		document.getElementById("t2ofifono").value=data.data[10];
	           		document.getElementById("t2fnacdia").value=data.data[11];
	           		document.getElementById("t2fnacmes").value=data.data[12];
	           		document.getElementById("t2fnacanio").value=data.data[13];

	           		//getUnidad();
	           		//alert(data.data[0]);

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}
	function gettutor()
	{
		
		var idest=document.getElementById('idest').value;		
		

		var dataest={
			"idest":idest,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/ajax_get_tutor');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		// cargarBusqueda(data.data[0]);
	           		// _global_idcur=data.data[0];
	           		document.getElementById("t3appat").value=data.data[0];
	           		document.getElementById("t3apmat").value=data.data[1];
	           		document.getElementById("t3nombres").value=data.data[2];
	           		document.getElementById("t3ci").value=data.data[3];
	           		document.getElementById("t3comple").value=data.data[4];
	           		document.getElementById("t3exten").value=data.data[5];
	           		document.getElementById("t3idioma").value=data.data[6];
	           		document.getElementById("t3ocup").value=data.data[7];
	           		document.getElementById("t3grado").value=data.data[8];
	           		document.getElementById("t3celular").value=data.data[9];
	           		document.getElementById("t3ofifono").value=data.data[10];
	           		document.getElementById("t3fnacdia").value=data.data[11];
	           		document.getElementById("t3fnacmes").value=data.data[12];
	           		document.getElementById("t3fnacanio").value=data.data[13];
	           		document.getElementById("t3parentesco").value=data.data[14];

	           		//getUnidad();
	           		//alert(data.data[0]);

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}
	function getcur(nivel)
	{
		if(nivel=='PT'){nivel='PRIMARIA TARDE';}
	    if(nivel=='ST'){nivel='SECUNDARIA TARDE';}
	    if(nivel=='PM'){nivel='PRIMARIA MAÑANA';}
	    if(nivel=='SM'){nivel='SECUNDARIA MAÑANA'}
		var options="<option value=''></option>";
		//alert(nivel);
		var datacur={
			"TablaCur":"curso",
			"nivel":nivel,
		}

		$.ajax({
			
	        url : "<?php echo site_url('Est_Inscrip_contr/ajax_get_curso1');?>",
	        type: "POST",
	        data:datacur,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           { var id='';
	           		//alert(data.data[0]);
	           		data.data.forEach(function(item){
	           			if(item=='PRIMERO A'){id='1A';}
	           			if(item=='PRIMERO B'){id='1B';}
	           			if(item=='SEGUNDO A'){id='2A';}
	           			if(item=='SEGUNDO B'){id='2B';}
	           			if(item=='TERCERO A'){id='3A';}
	           			if(item=='TERCERO B'){id='3B';}
	           			if(item=='CUARTO A'){id='4A';}
	           			if(item=='CUARTO B'){id='4B';}
	           			if(item=='QUINTO A'){id='5A';}
	           			if(item=='QUINTO B'){id='5B';}
	           			if(item=='QUINTO C'){id='5C';}
	           			if(item=='SEXTO A'){id='6A';}
	           			if(item=='SEXTO B'){id='6B';}
	           			if(item=='SEXTO C'){id='6C';}
	           			if(item!='PREINSCRIPTOS A' && item!='PREINSCRIPTOS B'){
	           			options=options+"<option value='"+id+"'>"+item+"</option>";	 
	           			}          			
	           		});
	           		document.getElementById('curso').innerHTML=options;           	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
		
	}
	function gescole(level)
	{
		if(level=='PT'){level='PRIMARIA TARDE';}
	    if(level=='ST'){level='SECUNDARIA TARDE';}
	    if(level=='PM'){level='PRIMARIA MAÑANA';}
	    if(level=='SM'){level='SECUNDARIA MAÑANA'}
		var data1={
				"table":"curso",
				"lvl":level,
		};
	
		$.ajax({
			
	        url : "<?php echo site_url('Est_Inscrip_contr/ajax_get_level');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		//datos recuperados
	           		//document.getElementById('Fgestion').value=data.data[0];
	           		document.getElementById('inscole').value=data.data[1];           	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
		
	}

	function getidioma()
	{
		
		var idest=document.getElementById('idest').value;		
		

		var dataest={
			"idest":idest,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/ajax_get_idioma');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		// cargarBusqueda(data.data[0]);
	           		// _global_idcur=data.data[0];
	           		document.getElementById("idiomanatal").value=data.data[0];
	           		document.getElementById("idioma1").value=data.data[1];
	           		document.getElementById("idioma2").value=data.data[2];
	           		document.getElementById("idioma3").value=data.data[3];
	           		document.getElementById("nacion").value=data.data[4];
	           		//getUnidad();
	           		//alert(data.data[0]);

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}
	function getdireccion()
	{
		
		var idest=document.getElementById('idest').value;		
		

		var dataest={
			"idest":idest,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/ajax_get_direccion');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		// cargarBusqueda(data.data[0]);
	           		// _global_idcur=data.data[0];
	           		document.getElementById("locdpto").value=data.data[0];
	           		document.getElementById("locprovin").value=data.data[1];
	           		document.getElementById("locmuni").value=data.data[2];
	           		document.getElementById("loclocal").value=data.data[3];
	           		document.getElementById("loczona").value=data.data[4];
	           		document.getElementById("loccalle").value=data.data[5];
	           		document.getElementById("locnum").value=data.data[6];
	           		document.getElementById("locfono").value=data.data[7];
	           		document.getElementById("loccel").value=data.data[8];
	           		//getUnidad();
	           		//alert(data.data[0]);

	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	
	}

	function getUnidad()
	{
		var options="<option value=''></option>";
		//var idcur=document.getElementById('idcur').value;

		var dataest={
			//"idcur":idcur,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/ajax_get_unid');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           			 
	           		// if(item=='TECNICO HUMANISTICO DON BOSCO'){id='3';}
           			// if(item=='DON BOSCO B'){id='2';}
           			// if(item=='DON BOSCO A'){id='1';}
	           		// options=options+"<option value='"+id+"'>"+item+"</option>";	           		 	           		    	
	           		// document.getElementById("unidedu").value=data.data[0];          		 	           		    	
	           		// document.getElementById("unidedu").value=data.data[0];
	           		// /*document.getElementById("unidedu").value=data.data[0];
	           		// var cole=data.data[0];
	           		// selectUnid(cole);*/

	           		data.data.forEach(function(item){
	           			if(item=='TECNICO HUMANISTICO DON BOSCO'){id='3';}
	           			if(item=='DON BOSCO B'){id='2';}
	           			if(item=='DON BOSCO A'){id='1';}
		           		options=options+"<option value='"+id+"'>"+item+"</option>";     			
	           		});
	           		document.getElementById("unidedu").innerHTML=options;   

	           		
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}

	function getUnidadAll()
	{
		var options="<option value=''></option>";
		//var idcur=document.getElementById('idcur').value;

		var dataest={
			// "idcur":idcur,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/ajax_get_unidAll');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        data:dataest,
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		// //document.getElementById("unidedu").value=data.data[0];

	           		// data.data.forEach(function(item){
	           			
	           		// 	options=options+"<option value='"+item+"'>"+item+"</option>";
	           			           			
	           		// });
	           		// document.getElementById('unidedu').innerHTML=options;
	           		// //document.getElementById('inscole').innerHTML=options;

	           		data.data.forEach(function(item){
	           			if(item=='TECNICO HUMANISTICO DON BOSCO'){id='3';}
	           			if(item=='DON BOSCO B'){id='2';}
	           			if(item=='DON BOSCO A'){id='1';}
		           		options=options+"<option value='"+id+"'>"+item+"</option>";     			
	           		});
	           		document.getElementById("unidedu").innerHTML=options;  
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}
	
	function selectUnid(cole)
	{
		if(cole=='3'){cole='TECNICO HUMANISTICO DON BOSCO';}
		if(cole=='2'){cole='DON BOSCO B';}
		if(cole=='1'){cole='DON BOSCO A';}
		var datacole={
			"cole":cole,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/ajax_get_cole');?>";
		if (cole!='')
		{
			$.ajax({
				
		        url : url,
		        type: "POST",
		        dataType: "JSON",
		        data:datacole,
		        success: function(data)//cargado de datos del registro 
		        {   
		           if(data.status)
		           {	           		 	           		    	
		           		
		           		document.getElementById("sie").value=data.data[1];
		           		document.getElementById("distrito").value="1001 SUCRE";
		           		document.getElementById("depend").value=data.data[4];
		           		// document.getElementById("unidedu").value=cole;
		           		//document.getElementById("antsie").value=data.data[1];
		           		//document.getElementById("antunidadedu").value=cole;
		           		
		           }
		        },
		        error: function (jqXHR, textStatus, errorThrown)
		        {
		           // alert('No puede obtener codigo nuevo, para el registro');
		        }
	    	});
	    }
	}

	function selcole(col)
	{
		var options="<option value=''></option>";
		var datacol={
			"col":col,
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/ajax_get_niveles');?>";
		if (col!='')
		{
			$.ajax({
				
		        url : url,
		        type: "POST",
		        dataType: "JSON",
		        data:datacol,
		        success: function(data)//cargado de datos del registro 
		        {   
		           if(data.status)
		           {	
						data.data.forEach(function(item){
		           			if(item=='PRIMARIA TARDE'){id='PT';}
		           			if(item=='SECUNDARIA TARDE'){id='ST';}
		           			if(item=='PRIMARIA MAÑANA'){id='PM';}
		           			if(item=='SECUNDARIA MAÑANA'){id='SM';}
		           			options=options+"<option value='"+id+"'>"+item+"</option>";	           			
	           			});
	           		document.getElementById('niveles').innerHTML=options;         		
		           }
		        },
		        error: function (jqXHR, textStatus, errorThrown)
		        {
		           // alert('No puede obtener codigo nuevo, para el registro');
		        }
	    	});
	    }
	}	

	function selnivel(level)
	{
		var options="<option value=''></option>";
		var col=document.getElementById("inscole").value;

		var datalevel={
			"level":level,
			"col":col
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/ajax_get_curso');?>";
		if (level!='')
		{
			$.ajax({
				
		        url : url,
		        type: "POST",
		        dataType: "JSON",
		        data:datalevel,
		        success: function(data)//cargado de datos del registro 
		        {   
		           if(data.status)
		           {	           		 	           		    			           		
		           		//document.getElementById("niveles").value=data.data[0]; 
		           		data.data.forEach(function(item){
	           			
	           			options=options+"<option value='"+item+"'>"+item+"</option>";
	           			           			
	           			});
	           			document.getElementById('curso').innerHTML=options;          		
		           }
		        },
		        error: function (jqXHR, textStatus, errorThrown)
		        {
		           // alert('No puede obtener codigo nuevo, para el registro');
		        }
	    	});
	    }
	    var n=level.indexOf(" ");
	    var turno=level.substr(n+1, level.length); //para guaradar turno
	    _turno=turno;
	    
	}
	function get_nivel()
	{
		var options="<option value=''></option>";
		//envio de valores
		var data1={
				"table":"nivel",
		};
		
		$.ajax({
			
	        url : "<?php echo site_url('Est_Inscrip_contr/ajax_get_nivel1');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   var id;
	           if(data.status)
	           {	           		
	           		data.data.forEach(function(item){
	           			if(item=='PRIMARIA TARDE'){id='PT';}
	           			if(item=='SECUNDARIA TARDE'){id='ST';}
	           			if(item=='PRIMARIA MAÑANA'){id='PM';}
	           			if(item=='SECUNDARIA MAÑANA'){id='SM';}
	           			options=options+"<option value='"+id+"'>"+item+"</option>";	           			
	           		});
	           		document.getElementById('niveles').innerHTML=options;	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}
	function getidcur()
	{
		var niveles=document.getElementById("niveles").value;
		var curso=document.getElementById("curso").value;
		var inscole=document.getElementById("inscole").value;
		//alert(niveles+" "+curso+" "+inscole);
		
		var datacole={
			"nivel":niveles,
			"curso":curso,
			"inscole":inscole
		}
				
		var url="<?php echo site_url('Est_Inscrip_contr/ajax_get_idcur');?>";
		
			$.ajax({
				
		        url : url,
		        type: "POST",
		        dataType: "JSON",
		        data:datacole,
		        success: function(data)//cargado de datos del registro 
		        {   
		           if(data.status)
		           {	           		 	           		    	
		           		
		           		document.getElementById("idcurnew").value=data.data[0];
		           		
		           }
		        },
		        error: function (jqXHR, textStatus, errorThrown)
		        {
		           // alert('No puede obtener codigo nuevo, para el registro');
		        }
	    	});
	   
	}

	function getidestnew()
	{
		var data1={
				"table":"estudiante",
				"cod":"EST-",
		};
	
		$.ajax({
			
	        url : "<?php echo site_url('Est_Inscrip_contr/ajax_get_idestnew');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		//datos recuperados
	           		document.getElementById('idestnew').value=data.codgen;
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	   
	}


	function guardarIns()
	{

		
		//var user=document.getElementById("user").value;
		//var fechains=document.getElementById("fechains").value;
		//var unidedu=document.getElementById("unidedu").value;
		//var depend=document.getElementById("depend").value;
		//var distrito=document.getElementById("distrito").value;
		//var sie=document.getElementById("sie").value;
		//var idcur=document.getElementById("idcur").value;


		var appat=document.getElementById("appat").value;
		var apmat=document.getElementById("apmat").value;
		var nombres=document.getElementById("nombres").value;
		var genero=document.getElementById("genero").value;
		var rude=document.getElementById("rude").value;
		var ci=document.getElementById("ci").value;
		var complemento=document.getElementById("complemento").value;
		var extension=document.getElementById("extension").value;
		var codigobanco=document.getElementById("codigobanco").value;
		var idest1=document.getElementById("idest1").value;
		//var antsie=document.getElementById("antsie").value;
		//var antunidadedu=document.getElementById("antunidadedu").value; 
		

		var gestion=document.getElementById("gestion").value;
		//var inscole=document.getElementById("inscole").value;
		//var niveles=document.getElementById("niveles").value;
		//var curso=document.getElementById("curso").value; 
		//var turno=_turno;	
		//var nitnombre=document.getElementById("nitnombre").value;
		//var nit=document.getElementById("nit").value;


		var pais=document.getElementById("pais").value;
		var dpto=document.getElementById("dpto").value;
		var provincia=document.getElementById("provincia").value;
		var localidad=document.getElementById("localidad").value;
		var oficialia=document.getElementById("oficialia").value;
		var libro=document.getElementById("libro").value;
		var partida=document.getElementById("partida").value;
		var folio=document.getElementById("folio").value;


		var discap=document.getElementsByName("rdiscap");
		for(i=0; i<discap.length; i++){
	        if(discap[i].checked){
	            var discap1=discap[i].value;
	        }
    	}
		// if(document.getElementById("discap1").checked)
		// 	var discap1=true;
		// else var discap1=false;

		var regdiscap=document.getElementById("regdiscap").value;
		var tdiscap=document.getElementById("tdiscap").value;
		var gradodiscap=document.getElementById("gradodiscap").value;

		var locdpto=document.getElementById("locdpto").value;
		var locprovin=document.getElementById("locprovin").value;
		var locmuni=document.getElementById("locmuni").value;
		var loclocal=document.getElementById("loclocal").value;
		var loczona=document.getElementById("loczona").value;
		var loccalle=document.getElementById("loccalle").value;
		var locnum=document.getElementById("locnum").value;
		var locfono=document.getElementById("locfono").value;
		var loccel=document.getElementById("loccel").value; 

		var idiomanatal=document.getElementById("idiomanatal").value;
		var idioma1=document.getElementById("idioma1").value;
		var idioma2=document.getElementById("idioma2").value;
		var idioma3=document.getElementById("idioma3").value;
		var nacion=document.getElementById("nacion").value; 
		var postas=document.getElementsByName("posta");
		for(i=0; i<postas.length; i++){
	        if(postas[i].checked){
	            var posta1=postas[i].value;
	        }
    	}

		// if (document.getElementById("posta1").checked)
		// 	var posta1=true;
		// else
		// 	var posta1=false;

		if (document.getElementById("visitaposta1").checked)
			var visitaposta1="1";
		else
			var visitaposta1="0";

		if(document.getElementById("visitaposta2").checked)
			var visitaposta2="2";
		else
			var visitaposta2="0";

		if(document.getElementById("visitaposta3").checked)
			var visitaposta3="3";
		else
			var visitaposta3="0";

		if(document.getElementById("visitaposta4").checked)
			var visitaposta4="4";
		else
			var visitaposta4="0";

		if(document.getElementById("visitaposta5").checked)
			var visitaposta5="5";
		else
			var visitaposta5="0";

		if(document.getElementById("visitaposta6").checked)
			var visitaposta6="6";
		else
			var visitaposta6="0";
	
		var veces=document.getElementById("veces").value;

		var seguros=document.getElementsByName("seguro");
		for(i=0; i<seguros.length; i++){
	        if(seguros[i].checked){
	            var seguro1=seguros[i].value;
	        }
    	}
		// if(document.getElementById("seguro1").checked)
		// 	var seguro1=true;
		// else
		// 	var seguro1=false;
		var agua=document.getElementsByName("agua");
		for(i=0; i<agua.length; i++){
	        if(agua[i].checked){
	            var agua1=agua[i].value;
	        }
    	}

		// if(document.getElementById("agua1").checked)
		// 	var agua1=true;
		// else
		// 	var agua1=false;
		var banio=document.getElementsByName("banio");
		for(i=0; i<banio.length; i++){
	        if(banio[i].checked){
	            var banio1=banio[i].value;
	        }
    	}
		// if(document.getElementById("banio1").checked)
		// 	var banio1=true;
		// else
		// 	var banio1=false;
		var alcantarillado=document.getElementsByName("alcantarillado");
		for(i=0; i<alcantarillado.length; i++){
	        if(alcantarillado[i].checked){
	            var alcan1=alcantarillado[i].value;
	        }
    	}
		// if(document.getElementById("alcan1").checked)
		// 	var alcan1=true;
		// else
		// 	var alcan1=false;
		var luz=document.getElementsByName("luz");
		for(i=0; i<luz.length; i++){
	        if(luz[i].checked){
	            var luz1=luz[i].value;
	        }
    	}
		// if(document.getElementById("luz1").checked)
		// 	var luz1=true;
		// else
		// 	var luz1=false;
		var basura=document.getElementsByName("basura");
		for(i=0; i<basura.length; i++){
	        if(basura[i].checked){
	            var basura1=basura[i].value;
	        }
    	}
		// if(document.getElementById("basura1").checked)
		// 	var basura1=true;
		// else
		// 	var basura1=false;

		var hogar=document.getElementById("hogar").value;



		if(document.getElementById("netvivienda").checked)
			var netvivienda=1;
		else
			var netvivienda=0;

		if(document.getElementById("netunidadedu").checked)
			var netunidadedu=2;
		else
			var netunidadedu=0;

		if(document.getElementById("netpublic").checked)
			var netpublic=3;
		else
			var netpublic=0;

		if(document.getElementById("netcelu").checked)
			var netcelu=4;
		else
			var netcelu=0;

		if(document.getElementById("nonet").checked)
			var nonet=5;
		else
			var nonet=0;
		
		var netfrecuencia=document.getElementById("netfrecuencia").value;

		var trabajo=document.getElementsByName("trabajo");
		for(i=0; i<trabajo.length; i++){
	        if(trabajo[i].checked){
	            var trabajo1=trabajo[i].value;
	        }
    	}
		// if(document.getElementById("trabajo1").checked)
		// 	var trabajo1=true;
		// else
		// 	var trabajo1=false;

		if(document.getElementById("ene").checked)
			var ene=1;
		else
			var ene=0;

		if(document.getElementById("feb").checked)
			var feb=2;
		else
			var feb=0;

		if(document.getElementById("mar").checked)
			var mar=3;
		else
			var mar=0;

		if(document.getElementById("abr").checked)
			var abr=4;
		else
			var abr=0;

		if(document.getElementById("may").checked)
			var may=5;
		else
			var may=0;

		if(document.getElementById("jun").checked)
			var jun=6;
		else
			var jun=0;

		if(document.getElementById("jul").checked)
			var jul=7;
		else
			var jul=0;

		if(document.getElementById("ago").checked)
			var ago=8;
		else
			var ago=0;
		
		if(document.getElementById("sep").checked)
			var sep=9;
		else
			var sep=0;

		if(document.getElementById("oct").checked)
			var oct=10;
		else
			var oct=0;

		if(document.getElementById("nov").checked)
			var nov=11;
		else
			var nov=0;
		
		if(document.getElementById("dic").checked)
			var dic=12;
		else
			var dic=0;

		var actividad=document.getElementById("actividad").value;
		var otrotrabajo=document.getElementById("otrotrabajo").value;
		if(document.getElementById("turnoman").checked)
			var turnoman=1;
		else
			var turnoman=0;

		if(document.getElementById("turnotar").checked)
			var turnotar=1;
		else
			var turnotar=0;

		if(document.getElementById("turnonoc").checked)
			var turnonoc=1;
		else
			var turnonoc=0;
		
		var trabfrecuencia=document.getElementById("trabfrecuencia").value;

		if(document.getElementById("pagotrab1").checked)
			var pagotrab1=1;
		else
			var pagotrab1=0;


		var tipopago=document.getElementById("tipopago").value;
		var transpllega=document.getElementById("transpllega").value;
		var otrollega=document.getElementById("otrollega").value;
		var tllegada=document.getElementById("tllegada").value;
		if(document.getElementById("abandono1").checked)
			var abandono1=1;
		else
			var abandono1=0;
		
		if(document.getElementById("razon0").checked)
			var razon0=1;
		else
			var razon0=0;

		if(document.getElementById("razon1").checked)
			var razon1=2;
		else
			var razon1=0;

		if(document.getElementById("razon2").checked)
			var razon2=3;
		else
			var razon2=0;

		if(document.getElementById("razon3").checked)
			var razon3=4;
		else
			var razon3=0;

		if(document.getElementById("razon4").checked)
			var razon4=5;
		else
			var razon4=0;

		if(document.getElementById("razon5").checked)
			var razon5=6;
		else
			var razon5=0;

		if(document.getElementById("razon6").checked)
			var razon6=7;
		else
			var razon6=0;

		if(document.getElementById("razon7").checked)
			var razon7=8;
		else
			var razon7=0;

		if(document.getElementById("razon8").checked)
			var razon8=9;
		else
			var razon8=0;

		if(document.getElementById("razon9").checked)
			var razon9=10;
		else
			var razon9=0;

		if(document.getElementById("razon10").checked)
			var razon10=11;
		else
			var razon10=0;

		if(document.getElementById("razon11").checked)
			var razon11=12;
		else
			var razon11=0;

				
		var otrarazon=document.getElementById("otrarazon").value;

		var t1appat=document.getElementById("t1appat").value;
		var t1apmat=document.getElementById("t1apmat").value;
		var t1nombres=document.getElementById("t1nombres").value;
		var t1ci=document.getElementById("t1ci").value;
		var t1comple=document.getElementById("t1comple").value;
		var t1extension=document.getElementById("t1exten").value;
		var t1ocup=document.getElementById("t1ocup").value; 
		var t1lugartra=document.getElementById("t3lug").value;
		var t1id=document.getElementById("t1id").value;
		var t1grado=document.getElementById("t1grado").value;
		var t1idioma=document.getElementById("t1idioma").value;
		var t1ofifono=document.getElementById("t1ofifono").value;
		var t1celular=document.getElementById("t1celular").value;
		var t1fn=document.getElementById("t1fn").value;

		var t2appat=document.getElementById("t2appat").value;
		var t2apmat=document.getElementById("t2apmat").value;
		var t2nombres=document.getElementById("t2nombres").value;
		var t2ci=document.getElementById("t2ci").value;
		var t2comple=document.getElementById("t2comple").value;
		var t2extension=document.getElementById("t2exten").value;
		var t2ocup=document.getElementById("t2ocup").value;
		var t2lugartra=document.getElementById("t3lug").value;
		var t2id=document.getElementById("t2id").value;
		var t2grado=document.getElementById("t2grado").value;
		var t2idioma=document.getElementById("t2idioma").value;
		var t2ofifono=document.getElementById("t2ofifono").value;
		var t2celular=document.getElementById("t2celular").value;
		var t2fn=document.getElementById("t2fn").value;

		var t3appat=document.getElementById("t3appat").value;
		var t3apmat=document.getElementById("t3apmat").value;
		var t3nombres=document.getElementById("t3nombres").value;
		var t3ci=document.getElementById("t3ci").value;
		var t3comple=document.getElementById("t3comple").value;
		var t3extension=document.getElementById("t3exten").value;
		var t3ocup=document.getElementById("t3ocup").value;
		var t3grado=document.getElementById("t3grado").value;
		var t3idioma=document.getElementById("t3idioma").value;
		var t3ofifono=document.getElementById("t3ofifono").value;
		var t3celular=document.getElementById("t3celular").value;
		var t3fn=document.getElementById("t3fn").value;
		var t3parentesco=document.getElementById("t3parentesco").value;
		var t3lugartra=document.getElementById("t3lug").value;
		var t3id=document.getElementById("t3id").value;
		var vivecon=document.getElementById("vivecon").value;
		var cont=document.getElementsByName("contrato");
		var fnaci=document.getElementById("fnaci").value;
		for(i=0; i<cont.length; i++){
	        if(cont[i].checked){
	            var contrato=cont[i].value;
	        }
    	}

    	//alert("asasa");
		//exit();
		//var contrato=document.getElementsByName("contrato");
		// alert("-"+niveles);
		// alert("100000");	
		url="<?php  echo site_url('Est_Inscrip_contr/ajax_save_estud');?>";
		// alert(fnaci);
		// exit();
		
		var data1={
			//"usuario":user,
			//"fechains":fechains,
			//"unidedu":unidedu,
			//"depend":depend,
			//"distrito":distrito,
			//"sie":sie,
			//"idcur":idcur,		
			"appaterno":appat,
			"apmaterno":apmat,
			"nombres":nombres,
			"genero":genero,
			"rude":rude,
			"ci":ci,
			"complemento":complemento,
			"extension":extension,
			"codigobanco":codigobanco,
			"idest1":idest1,
			//"antsie":antsie, 
			//"antunidadedu":antunidadedu,
			"gestion":gestion,
			//"inscole":inscole,
			//"colegio":inscole,
			//"nivel":niveles,
			//"curso":curso,
			//"turno":turno,		
			//"nitnombre":nitnombre,
			//"nit":nit,
			"pais":pais,
			"dpto":dpto,
			"provincia":provincia,
			"localidad":localidad,
			"oficialia":oficialia,
			"libro":libro,
			"partida":partida,
			"folio":folio,
			"discap1":discap1,
			"regdiscap":regdiscap,
			"tdiscap":tdiscap,
			"gradodiscap":gradodiscap,
			"locdpto":locdpto,
			"locprovin":locprovin,
			"locmuni":locmuni,
			"loclocal":loclocal,
			"loczona":loczona,
			"loccalle":loccalle,
			"locnum":locnum,
			"locfono":locfono,
			"loccel":loccel,
			"idiomanatal":idiomanatal,
			"idioma1":idioma1,
			"idioma2":idioma2,
			"idioma3":idioma3,
			"nacion":nacion,
			"posta1":posta1,
			"visitaposta1":visitaposta1,
			"visitaposta2":visitaposta2,
			"visitaposta3":visitaposta3,
			"visitaposta4":visitaposta4,
			"visitaposta5":visitaposta5,
			"visitaposta6":visitaposta6,
			"veces":veces,
			"seguro1":seguro1,
			"agua1":agua1,
			"banio1":banio1,
			"alcanta1":alcan1,
			"luz1":luz1,
			"basura1":basura1,
			"hogar":hogar,
			"netvivienda":netvivienda,
			"netunidadedu":netunidadedu,
			"netpublic":netpublic,
			"netcelu":netcelu,
			"nonet":nonet,
			"netfrecuencia":netfrecuencia,
			"trabajo1":trabajo1,
			"ene":ene,
			"feb":feb,
			"mar":mar,
			"abr":abr,
			"may":may,
			"jun":jun,
			"jul":jul,
			"ago":ago,
			"sep":sep,
			"oct":oct,
			"nov":nov,
			"dic":dic,
			"actividad":actividad,
			"otrotrabajo":otrotrabajo,
			"turnoman":turnoman,
			"turnotar":turnotar,
			"turnonoc":turnonoc,		
			"trabfrecuencia":trabfrecuencia,
			"pagotrab1":pagotrab1,
			"tipopago":tipopago,
			"transpllega":transpllega,
			"otrollega":otrollega,
			"tllegada":tllegada,
			"abandono1":abandono1,		
			"razon0":razon0,
			"razon1":razon1,
			"razon2":razon2,
			"razon3":razon3,
			"razon4":razon4,
			"razon5":razon5,
			"razon6":razon6,
			"razon7":razon7,
			"razon8":razon8,
			"razon9":razon9,
			"razon10":razon10,
			"razon11":razon11,
			"otrarazon":otrarazon,
			"t1appat":t1appat,
			"t1apmat":t1apmat,
			"t1nombres":t1nombres,
			"t1ci":t1ci,
			"t1comple":t1comple,
			"t1extension":t1extension,
			"t1ocup":t1ocup,
			"t1id":t1id,
			"t1lugartra":t1lugartra,
			"t1grado":t1grado,
			"t1idioma":t1idioma,
			"t1ofifono":t1ofifono,
			"t1celular":t1celular,
			"t1fn":t1fn,
			"t2appat":t2appat,
			"t2apmat":t2apmat,
			"t2nombres":t2nombres,
			"t2ci":t2ci,
			"t2comple":t2comple,
			"t2extension":t2extension,
			"t2ocup":t2ocup,
			"t2id":t2id,
			"t2lugartra":t2lugartra,
			"t2grado":t2grado,
			"t2idioma":t2idioma,
			"t2ofifono":t2ofifono,
			"t2celular":t2celular,
			"t2fn":t2fn,
			"t3appat":t3appat,
			"t3apmat":t3apmat,
			"t3nombres":t3nombres,
			"t3ci":t3ci,
			"t3comple":t3comple,
			"t3extension":t3extension,
			"t3ocup":t3ocup,
			"t3id":t3id,
			"t3grado":t3grado,
			"t3lugartra":t3lugartra,
			"t3idioma":t3idioma,
			"t3ofifono":t3ofifono,
			"t3celular":t3celular,
			"t3fn":t3fn,
			"t3parentesco":t3parentesco,
			"vivecon":vivecon,
			"contrato":contrato,
			"fnaci":fnaci};
		
		$.ajax({
				
		        url : url,
		        type: "POST",
		        data:data1,
		        dataType: "JSON",
		        success: function(data)//cargado de datos del registro 
		        {   
		           if(data.status)
		           {		           		
		           		swal({
				            title: "Inscripción Guardada!",
				            text: "Registro Guardado, Satisfactoriamente!",
				            confirmButtonColor: "#66BB6A",
				            type: "success"
				        });	
				        _idinscrip=data.idinscrip;  
				        document.getElementById("btnrude").disabled = false;
           				document.getElementById("btnctto").disabled = false;
           				document.getElementById("btnrude1").disabled = false;
           				document.getElementById("btnctto1").disabled = false;
           				//document.getElementById("btncodigo").disabled = false;         			   
		           }
		        },
		        error: function (jqXHR, textStatus, errorThrown)
		        {
		          // alert('error al enviar data');
		        }
	    	});	
			
		document.getElementById("btnguardar").disabled = true;
		document.getElementById("btnguardar1").disabled = true;
	
	}

	//ventana para el pdf
	function export_pdf()
	{
		var id_est=document.getElementById("idest1").value;
		var gestion=2021;
		var idins=id_est+"-"+gestion+"-";
	    var url = "<?php  echo site_url('Est_Inscrip_contr/print_rude');?>/"+idins;


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
	}


	function print_ctto()
	{
		var id_est=document.getElementById("idest1").value;
		var gestion=2021;
		
	    var url = "<?php  echo site_url('Est_Inscrip_contr/print_ctto');?>/"+gestion+"-"+id_est+"-";	
	    
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
	}



//************************************************************************************
	//obtener estudiante en tabla, una vez escogido el curso
	function getestud()
	{		
		var idcur="";
		var curso=document.getElementById('Fcurso').value;
		var nivel=document.getElementById('Fnivel').value;

		//alert(curso);
		
		var dataestu={
			"EstNivel":nivel,
			"EstCurso":curso,
		}

		var url="<?php echo site_url('Reg_Est_Inscrip_contr/ajax_get_idcurso');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        data:dataestu,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		 cargarBusqueda(data.data[0]);
	           		 _global_idcur=data.data[0];
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});

	}

	//abrir archivo
	function openFile(element)
	{
		
		var x=new FileReader();
		var file=element.files[0];
		var reader = new FileReader();
	  	reader.onloadend = function() {
	  		document.getElementById('txtar').innerHTML=reader.result;  		
			document.getElementById('loadimg').src=reader.result;
	  	}
	  	reader.readAsDataURL(file);
	  		  	
	}



	//cargar tabla
	function reload_estud()
	{
		testudiante.ajax.reload(null,false);

	}

	

	
	

</script>



