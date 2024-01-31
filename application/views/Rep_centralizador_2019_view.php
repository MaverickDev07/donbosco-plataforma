<body>
	<!-- Main navbar -->
	<div class="navbar bg-slate-700 ">
		<div class="navbar-header">
			<a class="navbar-brand text-white" href="">SISTEMA DE CONTROL ACADEMICO "DON BOSCO" <i class="icon-graduation"></i></a>

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
									<a href='<?php echo site_url('Rep_centralizador_2019_contr');?>'><i class="icon-user-lock"></i> <span>Centralizador</span></a>									
								</li>
								<li>
									<a href='<?php echo site_url('Rep_estadisticas_2019_contr');?>'><i class="icon-user-lock"></i> <span>Estadísticas</span></a>									
								</li>
								<li>
									<a href='<?php echo site_url('Rep_registro_contr');?>'><i class="icon-file-check"></i> <span>Acceso a Notas solo lectura</span></a>								
								</li>
								<!--<li>
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
									<small class="display-block">Gestión 2018</small>

								</h5>
								<br>
							</div>							
						</div>

						<div class="breadcrumb-line">
							<ul class="breadcrumb">
								<li><a href="Principal/index"><i class="icon-home2 position-left"></i> Principal</a></li>
								
								<li class="active">Centralizador</li>
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
								<div class="panel-heading bg-slate">
									<h6 class="panel-title">Centralizador de Notas</h6>
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
						                                <select
						                                ct class="form-control" type="text" id="Fgestion">
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

						                    <div class="col-sm-3">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">UNID. EDU:</label>
						                            <div class="col-md-9">
						                                <input name="Fcolegio" placeholder="" class="form-control"  id="Fcolegio" readonly="true">
						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div>
						                   
						                    
						                    <div class="col-sm-5">
				                    			<div class="col-md-12">
				    
				                    				<div class="bs-example" data-example-id="button-group-nesting"> 
				                    					<div class="btn-group" role="group" aria-label="Button group with nested dropdown"> 
				                    					<div class="btn-group" role="group"> 
		                    							<button type="button" class="btn btn-success">REPORTES</button>
		                    							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>

		                    						<ul class="dropdown-menu bg-success-300 btn-float btn-float-lg" id="menu1" aria-labelledby="drop4"> 
		                    				  			<li><a  onclick="export_excel()">CENTRALIZADOR<i class="icon icon-file-excel"></i></a></li> 
		                    				  			<li><a  onclick="reprobados()" >REPROBADOS<i class="icon icon-file-excel"></i></a></li> 
		                    				  			<li role="separator" class="divider"></li> 
		                    				  			<li><a onclick="acumulador()">ACUMULADO<i class="icon icon-file-pdf"></i></a></li>
		                    				  			<li><a onclick="acumulador6to()">ACU. INFORMATICA<i class="icon icon-file-pdf"></i></a></li>
		                    				  			<li><a onclick="acumulador_rep()">ACUMULADO REPROBADO<i class="icon icon-file-pdf"></i></a></li> 
		                    				  			<li><a onclick="acumulador_rep1()">ACUMULADO REPROBADO 53<i class="icon icon-file-pdf"></i></a></li> 
		                    				  			<li role="separator" class="divider"></li> 
		                    				  			<li><a onclick="export1_excel()">5to 6to <i class="icon icon-file-excel"></i></a></li>
		                    				  			<!-- <li><a onclick="estadisticas()">EST. APRO<i class="icon icon-file-excel"></i></a></li>   -->
		                    				  			<li role="separator" class="divider"></li> 
		                    				  			<li><a onclick="boletin()">BOLETINES <i class="icon icon-file-pdf"></i></a></li> 
		                    				  			<li role="separator" class="divider"></li> 
		                    				  			<li><a onclick="bonojp()">BONO JP <i class="icon icon-file-pdf"></i></a></li> 
		                    				  			<li role="separator" class="divider"></li> 
		                    				  			<li><a onclick="kardexs()">KARDEX<i class="icon icon-file-pdf"></i></a></li> 
		                    				  		</ul> 
		                    						</div>
				                    						<button class="btn  btn-danger" onclick="export_pdf()" ><i class="icon icon-file-pdf"></i> &nbsp;&nbsp;PDF&nbsp;&nbsp;&nbsp;</button>
				                    						<button class="btn  bg-orange-400" onclick="acumulados_pdf()" ><i class="icon icon-sort-amount-asc"></i> &nbsp;&nbsp;ACUMULADOS&nbsp;&nbsp;&nbsp;</button>

		                    						
				                    					</div> 
				                    				</div>
				                    			</div>
						                    </div>
						                    </div>
						                </div>
						                <div class="row">
						                	<div class="col-sm-2">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">GRADO:</label>
						                            <div class="col-md-9">
						                                <select  name="Fcurso" class="form-control" id="Fcurso" onchange="limpiarbi()">
						                                </select>
						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div>                    
						                    <div class="col-sm-2">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">BIMESTRE:</label>
						                            <div class="col-md-9">
						                                <select  name="Fbimestre" class="form-control" id="Fbimestre">
						                                	<option></option>
						                                	<option value='1'>PRIMER BIMESTRE</option>
						                                	<option value='2'>SEGUNDO BIMESTRE</option>
						                                	<option value='3'>TERCER BIMESTRE</option>
						                                	<option value='4'>CUATRO BIMESTRE</option>
						                                </select>
						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div>  
						                    <div class="col-sm-3">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">NOTA MIN:</label>
						                            <div class="col-md-9">
						                                <input  class="form-control" type="Number" min=11 max=101 id="Fnotlimit">

						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div>
						                    <div class="col-sm-3">
				                    			<div class="form-group">
				                    				<button class="btn  bg-slate-300" onclick="getestud()"><i class="glyphicon  glyphicon-th-list" ></i> LISTAR</button>
				                    				<button class="btn  bg-primary-300" onclick="printBoletin()"><i class="glyphicon  glyphicon-list-alt" ></i> BOLETINES</button>
				                    				<button class="btn  bg-success-300" onclick="printPinto()"><i class="glyphicon  glyphicon-user" ></i> BONO JP</button>                   				
						                            
						                        </div>
						                    </div>
						                    <!-- <div class="col-sm-3">
				                    			<div class="form-group">
				                    				<button class="btn  bg-primary-300" onclick="boletin()"><i class="glyphicon  glyphicon-list-alt" ></i> BOLETINES</button>               				
						                        </div>
						                    </div> -->
						                </div>
						                <hr class="bg-slate-300">
						                <!-- la tabla-->
						                <div id='tabla'>
									        
									    </div>
								</div>
							</div>
						</div>
					</div>

					<!-- Footer -->
					<div class="footer text-muted">
						&copy; 2021.<a href="#">Sistema de Control Académico "DON BOSCO"</a> by <a href="donboscosucre.edu.bo" target="_blank">Departamento de Informatica </a>
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
	var _global_idcur="";


	$(document).ready(function(){
		
		/*testudiante=$('#table_estudiante').DataTable({
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				"url":"<?php //echo site_url('Est_estudiante_contr/ajax_list');?>",
				"type":"POST"
			},

			"columnDefs":[
			{
				"targets":[-1],
				"orderable":false,
			},
			],
		});*/
		get_nivel();
		get_gestion();
				
	});

	function cerrar()
	{		
		var url="<?php echo site_url('Est_estudiante_contr/ajax_cerrar')?>";
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
	
	function get_gestion()
	{
		var options="";
		//envio de valores
		var data1={
				"table":"gestion",
		};
		
		$.ajax({
			
	        url : "<?php echo site_url('Rep_centralizador_2019_contr/ajax_get_gestion');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		
	           		data.data.forEach(function(item){
	           			
	           			options=options+"<option value='"+item+"'>"+item+"</option>";	           			
	           		});
	           		document.getElementById('Fgestion').innerHTML=options;
	           		document.getElementById('Fgestion').value='2019';
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}



	//SELECT nivel
	function get_nivel()
	{
		var options="<option value=''></option>";
		//envio de valores
		var data1={
				"table":"nivel",
		};
		
		$.ajax({
			
	        url : "<?php echo site_url('Est_estudiante_contr/ajax_get_nivel');?>",
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
	           		document.getElementById('Fnivel').innerHTML=options;	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}
	
	//gescole una vez selecionado el nivel carga el colegio y la gestion
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
			
	        url : "<?php echo site_url('Est_estudiante_contr/ajax_get_level');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		//datos recuperados
	           		//document.getElementById('Fgestion').value=data.data[0];
	           		document.getElementById('Fcolegio').value=data.data[1];           	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
		
	}

	//obtener id curso en la tabla curso
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
			
	        url : "<?php echo site_url('Est_estudiante_contr/ajax_get_curso');?>",
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
	           		document.getElementById('Fcurso').innerHTML=options;           	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
		
	}

	//obtener estudiante en tabla, una vez escogido el curso
	function getestud()
	{		
		var idcur="";
		var curso=document.getElementById('Fcurso').value;
		var nivel=document.getElementById('Fnivel').value;
		var bimes=document.getElementById('Fbimestre').value;
		var gestion=document.getElementById('Fgestion').value;
		var notlimit=document.getElementById('Fnotlimit').value;
		if(nivel=='PT'){nivel='PRIMARIA TARDE';}
	    if(nivel=='ST'){nivel='SECUNDARIA TARDE';}
	    if(nivel=='PM'){nivel='PRIMARIA MAÑANA';}
	    if(nivel=='SM'){nivel='SECUNDARIA MAÑANA';}

	    if(curso=='1A'){curso='PRIMERO A';}
	    if(curso=='1B'){curso='PRIMERO B';}
	    if(curso=='2A'){curso='SEGUNDO A';}
	    if(curso=='2B'){curso='SEGUNDO B';}
	    if(curso=='3A'){curso='TERCERO A';}
	    if(curso=='3B'){curso='TERCERO B';}
	    if(curso=='4A'){curso='CUARTO A';}
	    if(curso=='4B'){curso='CUARTO B';}
	    if(curso=='5A'){curso='QUINTO A';}
	    if(curso=='5B'){curso='QUINTO B';}
	    if(curso=='5C'){curso='QUINTO C';}
	    if(curso=='6A'){curso='SEXTO A';}
	    if(curso=='6B'){curso='SEXTO B';}
	    if(curso=='6C'){curso='SEXTO C';}

		
		var dataestu={
			"nivel":nivel,
			"curso":curso
		}


		var url="<?php echo site_url('Rep_centralizador_2019_contr/ajax_get_idcurso');?>";
		
		$.ajax({
			
	        url : url,
	        type: "POST",
	        data:dataestu,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		cargarBusqueda(notlimit,data.data[0],bimes,gestion,nivel,curso);
	           		 _global_idcur=data.data[0];
	           		//alert(notlimit+"-"+data.data[0]+"-"+bimes+"-"+gestion);
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
		
	}

	//cargar Busqueda
	function cargarBusqueda(notlimit,idcur,bimes,gestion,nivel,curso)
	{
		
		var data1={
			"notlimit":notlimit,
			"idcur":idcur,
			"bimes":bimes,
			"gestion":gestion,
			"nivel":nivel,
			"curso":curso		
		}

		if((nivel=='PRIMARIA MAÑANA')||(nivel=='PRIMARIA TARDE'))
		{
			var tabla="<table id='table_estudiante' class='table table-bordered table-hover' cellspacing='0' width='100%' id='table_usuario'><thead class='bg-slate'><tr><th>ID EST</th><th>APPAT</th><th>APMAT</th><th>NOMBRES</th><th>LENGUAJE</th><th>INGLES</th><th>PROMEDIO</th><th>SOCIALES</th><th>EDU FISICA</th><th>MUSICA</th><th>ART PLAST</th><th>MATEMATICAS</th><th>INFORMATICA</th><th>CIENCIAS</th><th>RELIGION</th><th>FINAL</th></tr></thead><tbody></tbody><tfoot class='bg-slate'><tr><th>ID EST</th><th>APPAT</th><th>APMAT</th><th>NOMBRES</th><th>LENGUAJE</th><th>INGLES</th><th>PROMEDIO</th><th>SOCIALES</th><th>EDU FISICA</th><th>MUSICA</th><th>ART PLAST</th><th>MATEMATICAS</th><th>INFORMATICA</th><th>CIENCIAS</th><th>RELIGION</th><th>FINAL</th></tr></tfoot></table>";

			document.getElementById('tabla').innerHTML=tabla;
		}

		if((nivel=='SECUNDARIA MAÑANA')||(nivel=='SECUNDARIA TARDE'))
		{
			if((curso=='PRIMERO A')||(curso=='PRIMERO B')||(curso=='SEGUNDO A')||(curso=='SEGUNDO B'))
			{
				var tabla="<table id='table_estudiante' class='table table-bordered table-hover' cellspacing='0' width='100%' id='table_usuario'><thead class='bg-slate'><tr><th>ID EST</th><th>APPAT</th><th>APMAT</th><th>NOMBRES</th><th>LENGUAJE</th><th>QUECHUA</th><th>PROM LCO</th><th>INGLES</th><th>SOCIALES</th><th>EDU FISICA</th><th>MUSICA</th><th>ART PLAST</th><th>PROM COMUNIDAD</th><th>MATEMATICAS</th><th>INFORMATICA</th><th>CIENCIA TECNOL</th><th>CIENCIAS</th><th>FISICA</th><th>QUIMICA</th><th>PROMD FC</th><th>PROM VIDA TIERRA</th><th>COSMOVISIONES</th><th>RELIGION</th><th>PROM COSMOS</th><th>FINAL</th></tr></thead><tbody></tbody><tfoot class='bg-slate'> <tr><th>ID EST</th><th>APPAT</th><th>APMAT</th><th>NOMBRES</th><th>LENGUAJE</th><th>QUECHUA</th><th>PROM LCO</th><th>INGLES</th><th>SOCIALES</th><th>EDU FISICA</th><th>MUSICA</th><th>ART PLAST</th><th>PROM COMUNIDAD</th><th>MATEMATICAS</th><th>INFORMATICA</th><th>CIENCIA TECNOL</th><th>CIENCIAS</th><th>FISICA</th><th>QUIMICA</th><th>PROMD FC</th><th>PROM VIDA TIERRA</th><th>COSMOVISIONES</th><th>RELIGION</th><th>PROM COSMOS</th><th>FINAL</th></tr></tfoot></table>";

				document.getElementById('tabla').innerHTML=tabla;
			}
			if((curso=='TERCERO A')||(curso=='TERCERO B'))
			{
				var tabla="<table id='table_estudiante' class='table table-bordered table-hover' cellspacing='0' width='100%' id='table_usuario'><thead class='bg-slate'><tr><th>ID EST</th><th>APPAT</th><th>APMAT</th><th>NOMBRES</th><th>LENGUAJE</th><th>QUECHUA</th><th>PROM LCO</th><th>INGLES</th><th>SOCIALES</th><th>EDU FISICA</th><th>MUSICA</th><th>ART PLAST</th><th>PROM COMUNIDAD</th><th>MATEMATICAS</th><th>INFORMATICA</th><th>CIENCIA TECNOL</th><th>CIENCIAS</th><th>FISICA</th><th>QUIMICA</th><th>PROMD FC</th><th>PROM VIDA TIERRA</th><th>COSMOVISIONES</th><th>RELIGION</th><th>PROM COSMOS</th><th>FINAL</th></tr></thead><tbody></tbody><tfoot class='bg-slate'> <tr><th>ID EST</th><th>APPAT</th><th>APMAT</th><th>NOMBRES</th><th>LENGUAJE</th><th>QUECHUA</th><th>PROM LCO</th><th>INGLES</th><th>SOCIALES</th><th>EDU FISICA</th><th>MUSICA</th><th>ART PLAST</th><th>PROM COMUNIDAD</th><th>MATEMATICAS</th><th>INFORMATICA</th><th>CIENCIA TECNOL</th><th>CIENCIAS</th><th>FISICA</th><th>QUIMICA</th><th>PROMD FC</th><th>PROM VIDA TIERRA</th><th>COSMOVISIONES</th><th>RELIGION</th><th>PROM COSMOS</th><th>FINAL</th></tr></tfoot></table>";

				document.getElementById('tabla').innerHTML=tabla;
			}
			if((curso=='CUARTO A')||(curso=='CUARTO B'))
			{
				var tabla="<table id='table_estudiante' class='table table-bordered table-hover' cellspacing='0' width='100%' id='table_usuario'><thead class='bg-slate'><tr><th>ID EST</th><th>APPAT</th><th>APMAT</th><th>NOMBRES</th><th>LENGUAJE</th><th>INGLES</th><th>SOCIALES</th><th>EDU FISICA</th><th>MUSICA</th><th>ART PLAST</th><th>PROM COMUNIDAD</th><th>MATEMATICAS</th><th>INFORMATICA</th><th>CIENCIA TECNOL</th><th>CIENCIAS</th><th>FISICA</th><th>QUIMICA</th><th>PROMD FC</th><th>PROM VIDA TIERRA</th><th>COSMOVISIONES</th><th>RELIGION</th><th>PROM COSMOS</th><th>FINAL</th>               </tr></thead><tbody></tbody><tfoot class='bg-slate'> <tr><th>ID EST</th><th>APPAT</th><th>APMAT</th><th>NOMBRES</th><th>LENGUAJE</th><th>INGLES</th><th>SOCIALES</th><th>EDU FISICA</th><th>MUSICA</th><th>ART PLAST</th><th>PROM COMUNIDAD</th><th>MATEMATICAS</th><th>INFORMATICA</th><th>CIENCIA TECNOL</th><th>CIENCIAS</th><th>FISICA</th><th>QUIMICA</th><th>PROMD FC</th><th>PROM VIDA TIERRA</th><th>COSMOVISIONES</th><th>RELIGION</th><th>PROM COSMOS</th><th>FINAL</th></tr></tfoot></table>";

				document.getElementById('tabla').innerHTML=tabla;
			}
			if((curso=='QUINTO A')||(curso=='QUINTO B')||(curso=='SEXTO A')||(curso=='SEXTO B'))
			{
				var tabla="<table id='table_estudiante' class='table table-bordered table-hover' cellspacing='0' width='100%' id='table_usuario'><thead class='bg-slate'><tr><th>ID EST</th><th>APPAT</th><th>APMAT</th><th>NOMBRES</th><th>LENGUAJE</th><th>INGLES</th><th>HISTORIA</th><th>CIVICA</th><th>PROM SC</th><th>EDU FISICA</th><th>MUSICA</th><th>ART PLAST</th><th>PROM COMUNIDAD</th><th>MATEMATICAS</th><th>INFORMATICA</th><th>CIENCIA TECNOL</th><th>BIOLOGIA</<th><th>GEOGRAFIA</<th><th>CIENCIAS</th><th>FISICA</th><th>QUIMICA</th><th>PROMD FC</th><th>PROM VIDA TIERRA</th><th>COSMOVISIONES</th><th>RELIGION</th><th>PROM COSMOS</th><th>FINAL</th></tr></thead><tbody></tbody><tfoot class='bg-slate'> <tr><th>ID EST</th><th>APPAT</th><th>APMAT</th><th>NOMBRES</th><th>LENGUAJE</th><th>INGLES</th><th>HISTORIA</th><th>CIVICA</th><th>PROM SC</th><th>EDU FISICA</th><th>MUSICA</th><th>ART PLAST</th><th>PROM COMUNIDAD</th><th>MATEMATICAS</th><th>INFORMATICA</th><th>CIENCIA TECNOL</th><th>BIOLOGIA</<th><th>GEOGRAFIA</<th><th>CIENCIAS</th><th>FISICA</th><th>QUIMICA</th><th>PROMD FC</th><th>PROM VIDA TIERRA</th><th>COSMOVISIONES</th><th>RELIGION</th><th>PROM COSMOS</th><th>FINAL</th></tr></tfoot></table>";

				document.getElementById('tabla').innerHTML=tabla;
			}
			
		}

		url="<?php  echo site_url('Rep_centralizador_2019_contr/ajax_load_sql');?>";

		testudiante=$('#table_estudiante').DataTable({
						"destroy": true,
						"serverSide":true,
						"order":[],
						"processing":true,
						"ajax":{
							"url":url,	
							"data":data1,					
							"type":"POST",
						},														
						
						"columnDefs":[
						{
							"targets":[-1],
							"orderable":false,
						},
						],
						"scrollX":true,
						"fixedColumns": {
				            "leftColumns": 4,
				            "rightColumns": 0
				        }
					}); 


	}

	//validacion de datos para el forumulario
	function validacion(campo,descrip)
	{
		var error=false;
		if(document.getElementById(campo).value=='')
		{
			error=true;
			swal({
            title: "Información",
            text: "Debe introducir en el campo  "+descrip,
            confirmButtonColor: "#2196F3",
            type: "info"
        	});
			//alert("debe introducir en el campo  "+descrip);
		}
		return error;
	}

	//mensaje para guarfar
	function msg_guardar()
	{
		swal({
            title: "Guardado!",
            text: "Registro Guardado, Satisfactoriamente!",
            confirmButtonColor: "#66BB6A",
            type: "success"
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

	//guardado de usuario
	function save_estud()
	{
		//alert(save_method);
		
		var idest,rude,ci,paterno,materno,nombre,genero,codigo;
		var error=false;
		var url;
				
		
		url="<?php echo site_url('Est_estudiante_contr/ajax_update_estud');?>";
		
		idest=document.getElementById('idest').value;

		
		if(!validacion('rude','RUDE'))		
			rude=document.getElementById('rude').value;
		else
			error=true;

		if(!validacion('ci','CI'))		
			ci=document.getElementById('ci').value;
		else
			error=true;

		if(!validacion('appaterno','APELLIDO PATERNO'))		
			appaterno=document.getElementById('appaterno').value;
		else
			error=true;

		if(!validacion('apmaterno','APELLIDO MATERNO'))		
			apmaterno=document.getElementById('apmaterno').value;
		else
			error=true;

		if(!validacion('nombre','NOMBRES'))		
			nombre=document.getElementById('nombre').value;
		else
			error=true;

		if(!validacion('genero','GENERO'))		
			genero=document.getElementById('genero').value;
		else
			error=true;

		if(!validacion('codigo','CODIGO'))		
			codigo=document.getElementById('codigo').value;
		else
			error=true;

		var x=document.getElementById('txtar').value;
	
		if (!error)
		{
			var data1={
				"idest":idest,
				"rude":rude,
				"ci":ci,
				"appaterno":appaterno,
				"apmaterno":apmaterno,
				"nombre":nombre,
				"genero":genero,
				"codigo":codigo,
				"foto":x
			};
						
			$.ajax({
				
		        url : url,
		        type: "POST",
		        data:data1,
		        dataType: "JSON",
		        success: function(data)//cargado de datos del registro 
		        {   
		           if(data.status)
		           {		           		
		           		msg_guardar();
		           		$('#modal_form').modal('hide');
		           		reload_table();		           		
		           }
		        },
		        error: function (jqXHR, textStatus, errorThrown)
		        {
		           // alert('No puede obtener codigo nuevo, para el registro');
		        }
	    	});		    	
	    
		}
		
	}

	//borrar registro
	function delete_estud(id)
	{
		swal({
            title: "¿Esta Seguro?",
            text: "Esta seguro de eliminar el registro "+ id+" !!!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#EF5350",
            confirmButtonText: "Si, Borrar!",
            cancelButtonText: "No, Cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
            	$.ajax({
		            url : "<?php echo site_url('Est_estudiante_contr/ajax_delete')?>/"+id,
		            type: "POST",
		            dataType: "JSON",
		            success: function(data)
		            {
		                
		                swal({
		                    title: "Eliminado!",
		                    text: "Registro Borrado!!!",
		                    confirmButtonColor: "#66BB6A",
		                    type: "success"
		                });

		                reload_table();
		                
		            },
		            error: function (jqXHR, textStatus, errorThrown)
		            {
		                swal({
				            title: "Información",
				            text: "Hubo un error al intentar Borrar el Registro!",
				            confirmButtonColor: "#2196F3",
				            type: "info"
				        });
		            }
		        });
  
            }
            else {
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
	function edit_estud(id)
	{
		//alert(id);
		save_method='update';
		var img="";
		$('#btnSave').text('Modificar');
		var temp="<?php echo site_url('assets/images/anonimo.jpg');?>";

		$.ajax({
			url:"<?php  echo site_url('Est_estudiante_contr/ajax_edit_estud'); ?>/"+id,
			type:"GET",
			dataType:"JSON",
			success: function(data)
			{
				$('[name="idest"]').val(data.idest);
				$('[name="rude"]').val(data.rude);
				$('[name="ci"]').val(data.ci);	
				$('[name="appaterno"]').val(data.appaterno);
				$('[name="apmaterno"]').val(data.apmaterno);						
				$('[name="nombre"]').val(data.nombres);				
				$('[name="genero"]').val(data.genero);
				$('[name="codigo"]').val(data.codigo);
				$('[name="idcurso"]').val(data.idcurso);
				$('[name="colegio"]').val(data.colegio);
				$('[name="gestion"]').val(data.gestion);
				
				if(data.foto=="")
				{					
					document.getElementById('loadimg').src=temp;
				}
				else
				{
					document.getElementById('loadimg').src=data.foto;
				}
				

			},
			error: function(jqXHR,textStatus,errorThrown)
			{
				swal({
			            title: "Información",
			            text: "Error al obtener los datos",
			            confirmButtonColor: "#2196F3",
			            type: "info"
			        });
			}

		});

		$('#modal_form').modal('show');
		
	}
	

	//cargar tabla
	function reload_estud()
	{
		testudiante.ajax.reload(null,false);

	}

	function reload_all()
	{
		/*
		testudiante=$('#table_estudiante').DataTable({
			"destroy": true,
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				"url":"<?php //echo site_url('Est_estudiante_contr/ajax_list');?>",
				"type":"POST"
			},
			"scrollX":true,
			"columnDefs":[
			{
				"targets":[-1],
				"orderable":false,
			},
			],
		});
		document.getElementById('Fnivel').value="";
		document.getElementById('Fcolegio').value="";
		document.getElementById('Fgestion').value="";
		document.getElementById('Fcurso').options.length = 0;
*/
	}

	function limpiarbi()
	{
		document.getElementById('Fbimestre').value="";
	}

	//ventana para el pdf
	function export_pdf()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var limit=document.getElementById('Fnotlimit').value;
		var gestion=document.getElementById('Fgestion').value;
		
		var valor=limit+"."+bimes+"."+_global_idcur+"_"+gestion;

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_centralizador_2019_contr/printcentr');?>/"+valor;

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
	function estadisticas()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso="";
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		
		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_0_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_centralizador_2019_contr/estadisticas_apro');?>/"+valor;

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
	function export1_excel()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso=document.getElementById('Fcurso').value;
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		
		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_0_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_centralizador_2019_contr/excel_centralizador');?>/"+valor;

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
	function export_excel()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso=document.getElementById('Fcurso').value;
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		
		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_0_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_centralizador_2019_contr/excel_curso');?>/"+valor;

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
	function boletin()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso=document.getElementById('Fcurso').value;
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		
		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_0_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_centralizador_2019_contr/boletines');?>/"+valor;

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
	function bonojp()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso=document.getElementById('Fcurso').value;
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		
		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_0_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_centralizador_2019_contr/bonojp');?>/"+valor;

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

	function kardexs()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso=document.getElementById('Fcurso').value;
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		
		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_0_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_centralizador_2019_contr/kardexs');?>/"+valor;

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
	function acumulador_rep()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso=document.getElementById('Fcurso').value;
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		
		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_0_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_centralizador_2019_contr/acumulador_rep');?>/"+valor;

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
	function acumulador_rep1()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso=document.getElementById('Fcurso').value;
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		
		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_0_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_centralizador_2019_contr/acumulador_rep1');?>/"+valor;

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
function acumulador()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso=document.getElementById('Fcurso').value;
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		
		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_0_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_centralizador_2019_contr/acumulador');?>/"+valor;

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
	function acumulador6to()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso=document.getElementById('Fcurso').value;
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		
		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_0_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_centralizador_2019_contr/acumulador6to');?>/"+valor;

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
function reprobados()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso=document.getElementById('Fcurso').value;
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		
		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_1_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_centralizador_2019_contr/excel_curso');?>/"+valor;

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
	           
	}reprobados

	function acumulados_pdf()
	{

		
		var gestion=document.getElementById('Fgestion').value;		
		var valor=_global_idcur+"."+gestion;
	
	    var url = "<?php echo site_url('Rep_centralizador_2019_contr/printacumulado');?>/"+valor;

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


	function printBoletin()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var limit=document.getElementById('Fnotlimit').value;
		var gestion=document.getElementById('Fgestion').value;
		
		var valor=limit+"."+bimes+"."+_global_idcur+"_"+gestion;

	    var url = "<?php  echo site_url('Rep_centralizador_2019_contr/printboletin');?>/"+valor;

	    $.ajax({
	            url :url,
	            type: "POST",
	            ContentType:"application/pdf",
	            success: function(data)
	            {
	                window.open(url,"menubar=no,scrollbars=yes,statubar=no,titlebar=yes,width=500,height=500"); 

	                swal({
		                    title: "Archivo",
		                    text: "Boletines Generado Satisfactoriamente!!!",
		                    confirmButtonColor: "#66BB6A",
		                    type: "success"
		                });

	                
	            },
	            error: function (jqXHR, textStatus, errorThrown)
	            {
	                swal({
	                    title: "Archivo NO Generado",
	                    text: "Hubo un error, al generar los Boletines comuniquese con el administrador.",
	                    confirmButtonColor: "#2196F3",
	                    type: "error"
	                });
	            }
	        });	
	}

	function printPinto()
	{

		var bimes=document.getElementById('Fbimestre').value;
		var limit=document.getElementById('Fnotlimit').value;
		var gestion=document.getElementById('Fgestion').value;
		
		var valor=limit+"."+bimes+"."+_global_idcur+"_"+gestion;
		//alert(id);
		
	    var url = "<?php  echo site_url('Rep_centralizador_2019_contr/printjuancito');?>/"+valor;

	    $.ajax({
	            url :url,
	            type: "POST",
	            ContentType:"application/pdf",
	            success: function(data)
	            {
	                window.open(url,"menubar=no,scrollbars=yes,statubar=no,titlebar=yes,width=500,height=500"); 

	                swal({
		                    title: "Archivo",
		                    text: "Lista Bono, Generado Satisfactoriamente!!!",
		                    confirmButtonColor: "#66BB6A",
		                    type: "success"
		                });

	                
	            },
	            error: function (jqXHR, textStatus, errorThrown)
	            {
	                swal({
	                    title: "Archivo NO Generado",
	                    text: "Hubo un error, al generar los Boletines comuniquese con el administrador.",
	                    confirmButtonColor: "#2196F3",
	                    type: "error"
	                });
	            }
	        });	
	}
	
	function export_xls()
	{
		var valor=_global_idcur;
				
	    var url = "<?php  echo site_url('Est_estudiante_contr/printxls');?>/"+valor;

	    $.ajax({
	            url :url,
	            type: "POST",
	            //ContentType:"application/xls",
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

</script>


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-slate-300">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Formulario de Revisión de Alumnos</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                    	<div class="row">
                    		<div class="col-sm-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">ID EST:</label>
		                            <div class="col-md-8">
		                                <input name="idest" placeholder="" class="form-control" type="text" id="idest" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-md-4">PATERNO:</label>
		                            <div class="col-md-8">
		                                <input type="text" name="appaterno" placeholder="apellido paterno" class="form-control" id="appaterno">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-md-4">GENERO:</label>
		                            <div class="col-md-8">
		                                <input type="text" class="form-control" name="genero" id="genero">
		                                
		                                <span class="help-block"></span>
		                            </div>
		                        </div>		                        
                    		</div>

                    		<div class="col-sm-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">RUDE:</label>
		                            <div class="col-md-8">
		                                <input name="rude" placeholder="RUDE" class="form-control" type="text" id="rude">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>

		                         <div class="form-group">
		                            <label class="control-label col-md-4">MATERNO</label>
		                            <div class="col-md-8">
		                                <input type="text" name="apmaterno" placeholder="apellido materno" class="form-control" id="apmaterno" >
		                                <span class="help-block"></span>
		                            </div>
		                        </div>

		                        <div class="form-group">
		                            <label class="control-label col-md-4">CODIGO:</label>
		                            <div class="col-md-8">
		                            	<input type="text" class="form-control" name="codigo" id="codigo"> 
		                                <span class="help-block"></span>
		                            </div>
		                        </div>		                        
                    		</div>

                    		<div class="col-sm-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">CI:</label>
		                            <div class="col-md-8">
		                                <input name="ci" placeholder="CI" class="form-control" type="text" id="ci">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-md-4">NOMBRE:</label>
		                            <div class="col-md-8">
		                                <input type="text" name="nombre" placeholder="nombres" class="form-control" id="nombre" >
		                                <span class="help-block"></span>
		                            </div>
		                        </div>		                                       
                    		</div>
                    		<div class="col-sm-3">
	                        	<img src='' width='160' height='200' id="loadimg"> 
	                        	<br><br>
                    			<label class="btn bg-slate btn-file">
   								 	Cargar Foto
   								 	<input type="file" class="form-control " id="photo" onchange="openFile(this)">
								</label>             	                          
	                    	</div>
                    	</div>
                    	<hr class="bg-slate-300">
                    	<div class="row">
                    		<div class="col-sm-4">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">ID CURSO:</label>
		                            <div class="col-md-8">
		                            	<input type="text" name="idcurso" id="idcurso" class="form-control" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
                    		</div>
                    		<div class="col-sm-4">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">COLEGIO:</label>
		                            <div class="col-md-8">
		                            	<input type="text" class="form-control" name="colegio" id="colegio" readonly="true"> 
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
                    		</div>
                    		<div class="col-sm-4">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">GESTION:</label>
		                            <div class="col-md-8">
		                                <input type="text" name="gestion" placeholder="año" class="form-control" id="gestion" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <div class="form-group">
		                            	<textarea rows="10" cols="100" id="txtar" hidden="true" >
		                            	</textarea>
		                            </div>
		                        </div>
                    		</div>
                    	</div>
                     
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-slate-300">
            	<br>
                <button type="button" id="btnSave" onclick="save_estud()" class="btn bg-green-700">Guardar</button>
                <button type="button" class="btn bg-danger-300" data-dismiss="modal">Cancelar</button>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
