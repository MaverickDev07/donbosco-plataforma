
<body> 

	<!-- Main navbar -->
	<div class="navbar bg-green-700 ">
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
									<small class="display-block">Gestión 2018</small>

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
								<div class="panel-heading bg-green">
									<h6 class="panel-title">Gestionar Alumnos</h6>
									<div class="heading-elements">
										<ul class="icons-list">
										   	<li><a data-action="reload"></a></li>
					                	</ul>
				                	</div>
								</div>

								<div class="col-sm-12">
								<div class="panel-body">
				                    	<div class="row">
				                    		<div class="col-sm-3">
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
						                            <label class="control-label col-md-3">COLEGIO:</label>
						                            <div class="col-md-9">
						                                <input name="Fcolegio" placeholder="" class="form-control"  id="Fcolegio" readonly="true">
						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div>
						                    <div class="col-sm-3">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">GESTION:</label>
						                            <div class="col-md-9">
						                               <!-- <input name="Fgestion" placeholder="" class="form-control" type="text" id="Fgestion" readonly="true">-->
						                                <select class="form-control" type="text" id="Fgestion">
						                                </select>

						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div>
						                    <div class="col-sm-3">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">CURSO:</label>
						                            <div class="col-md-9">
						                                <select  name="Fcurso" class="form-control" id="Fcurso" onchange="getestud()">
						                                </select>
						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div> 

						                    


				                    	<div class="row">
				                    		<div class="col-sm-12">
						                     <div class="bs-example"> 
						                     	<ul class="nav nav-pills" role="tablist">
						                     		<!--<button class="btn  btn-danger" onclick="export_pdf()" ><i class="icon icon-file-pdf"></i> PDF</button>-->
						                     		<button class="btn btn-sm bg-green-800" onclick="export_exel()" ><i class="icon icon-file-excel"></i>&nbsp;&nbsp;EXEL&nbsp;&nbsp;</button>
						                     		&nbsp;&nbsp;
				              						<button class="btn  bg-orange-400" onclick="export_pdf_fono()" ><i class="icon icon-file-pdf"></i> FONOS</button>
				              						&nbsp;&nbsp;
				              						<button class="btn  btn-primary" onclick="nuevo_estud()" ><i class="glyphicon glyphicon-plus"></i> Pre Alumnno</button>
				              						&nbsp;&nbsp;
						                     		 <button class="btn btn-default" onclick="reload_all()" ><i class="icon icon-user-block"></i>SIN FILTRO</button>
						                     		 &nbsp;&nbsp;
						        					<button class="btn btn-default" onclick="reload_estud()" ><i class="glyphicon glyphicon-refresh"></i> ACTUALIZAR</button>
						                     		 <li role="presentation" class="dropdown">
						                    		<div class="bs-example">
				                    					<li role="presentation" class="active">
				                    				  		<a href="#" class="btn active" id="drop4" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> REPORTES<span class="glyphicon glyphicon-menu-down"></span> </a> 
				                    				  		<ul class="dropdown-menu bg-primary-300 btn-float btn-float-lg" id="menu1" aria-labelledby="drop4"> 
				                    				  			<li><a  onclick="genero()">GENERO <i class="icon icon-file-pdf"></i></a></li> 
				                    				  			<li><a  onclick="cursorude()">RUDE POR CURSO <i class="icon icon-file-pdf"></i></a></li>
				                    				  			<li><a  onclick="generoCurso()" >GENERO POR CURSO <i class="icon icon-file-pdf"></i></a></li> 
				                    				  			<li role="separator" class="divider"></li> 
				                    				  			<li><a onclick="export_fecha_pdf()">DIRECCION <i class="icon icon-file-excel"></i></a></li>
				                    				  			<li><a onclick="export_naci_pdf()">FEC. NANC <i class="icon icon-file-excel"></i></a></li>  
				                    				  	
				                    				  			<li><a onclick="export_est_curso_exel()">ESTUDIANTE <i class="icon icon-file-excel"></i></a></li> 
				                    				  		</ul> 
				                    				  	</li>
				                    				</div>
				                    			</li>
				                    			
				                    			</ul>
				                    		</div>
				                    	</div>
				                    </div>


				              

						                    <!--<div class="col-sm-4">
				                    			<div class="form-group">
				                    				<button class="btn  btn-danger" onclick="export_pdf()" ><i class="icon icon-file-pdf"></i> PDF</button>
				                    				&nbsp;&nbsp;

				                    				<button class="btn  bg-orange-400" onclick="export_pdf_fono()" ><i class="icon icon-file-pdf"></i> FONOS</button>
				                    				&nbsp;&nbsp;

				                    				<button class="btn  btn-primary" onclick="nuevo_estud()" ><i class="glyphicon glyphicon-plus"></i> Pre Alumnno</button>
				                    				&nbsp;&nbsp;

				                    				<button class="btn  btn-primary" onclick="genero()" ><i class="icon icon-file-pdf"></i>REP DE GENERO</button>
				                    				&nbsp;&nbsp;

				                    				<button class="btn  btn-primary" onclick="generoCurso()" ><i class="icon icon-file-pdf"></i>REP DE GENERO POR CURSO</button>
				                    				&nbsp;&nbsp;

				                    				<button class="btn  btn-danger" onclick="export_fecha_pdf()" ><i class="icon icon-file-pdf"></i>DIRECCION</button>
				                    				&nbsp;&nbsp;

				                    				<button class="btn  btn-danger" onclick="export_naci_pdf()" ><i class="icon icon-file-pdf"></i>FEC. NANC</button>
				                    				&nbsp;&nbsp;

				                    				<button class="btn  btn-danger" onclick="export_est_curso_exel()" ><i class="icon icon-file-pdf"></i>ESTUDIANTE</button>
				                    				&nbsp;&nbsp;-->

				                    				<!--<button class="btn  btn-danger" onclick="mejores_notas()" ><i class="icon icon-file-pdf"></i>notas</button>
				                    				&nbsp;&nbsp;
				                    				

				                    				<button class="btn btn-default" onclick="reload_all()" ><i class="icon icon-user-block"></i>SIN FILTRO</button>
				                    				&nbsp;&nbsp;

						                            <button class="btn btn-default pull-right" onclick="reload_estud()" ><i class="glyphicon glyphicon-refresh"></i> ACTUALIZAR</button>-->
						                            
						                        </div>
						                    </div>
						                </div>
						                </div>
						                <hr class="bg-green-300">
							        <table id="table_estudiante" class="table datatable-responsive" cellspacing="0" width="100%" id="table_usuario">
							            <thead class="bg-green">
							                <tr>
							                    <th>Nro</th>
							                    <th>RUDE</th>
							                    <th>CI</th>
							                    <th>AP PATERNO</th>
							                    <th>AP MATERNO</th>
							                    <th>NOMBRES</th>
							                    <th>GENERO</th>
							                    <th>CURSO</th>
							                    <th>CODIGO</th>							                    
							                    <th>ACTION</th>
							                    <th>RUDE</th>
							                </tr>
							            </thead>
							            <tbody>
							            </tbody>

							            <tfoot class="bg-green">
								            <tr>
							                    <th>Nro</th>
							                    <th>RUDE</th>
							                    <th>CI</th>
							                    <th>AP PATERNO</th>
							                    <th>AP MATERNO</th>
							                    <th>NOMBRES</th>
							                    <th>GENERO</th>
							                    <th>CURSO</th>
							                    <th>CODIGO</th>							                   
							                    <th>ACTION</th>
							                    <th>RUDE</th>
							                </tr>
							            </tfoot>
							        </table>
								</div>
							</div>
						</div>
					</div>

					<!-- Footer -->
					<div class="footer text-muted">
						&copy; 2021.<a href="#">Sistema de Control Académico "DON BOSCO"</a> by <a href="donboscosucre.edu.bo" target="_blank">Departamento de Informatica</a>
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
	var _global_idcur_new="";
	var _idinscrip="";

	$(document).ready(function(){
		
		testudiante=$('#table_estudiante').DataTable({
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				"url":"<?php echo site_url('Est_estudiante_contr/ajax_list');?>",
				"type":"POST"
			},

			"columnDefs":[
			{
				"targets":[-1],
				"orderable":false,
			},
			],
		});
		get_nivel();
		getidestnew();
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
	        {   
	           if(data.status)
	           {	           		
	           		data.data.forEach(function(item){
	           			
	           			options=options+"<option value='"+item+"'>"+item+"</option>";	           			
	           		});
	           		document.getElementById('Fnivel').innerHTML=options;
	           		document.getElementById('newnivel').innerHTML=options;	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}

	function get_gestion()
	{
		var options="";
		//envio de valores
		var data1={
				"table":"gestion",
		};
		
		$.ajax({
			
	        url : "<?php echo site_url('Est_estudiante_contr/ajax_get_gestion');?>",
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
	
	//gescole una vez selecionado el nivel carga el colegio y la gestion
	function gescole(level)
	{
		
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
	           		document.getElementById('newgestion').value=data.data[0];
	           		document.getElementById('newcolegio').value=data.data[1];          	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
		
	}

	function newgescole(level)
	{
		
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
	           		document.getElementById('newgestion').value=data.data[0];
	           		document.getElementById('newcolegio').value=data.data[1];          	
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
	           {
	           		//alert(data.data[0]);
	           		data.data.forEach(function(item){
	           			
	           			options=options+"<option value='"+item+"'>"+item+"</option>";	           			
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


	//obtener id curso en la tabla curso
	function newgetcur(nivel)
	{
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
	           {
	           		//alert(data.data[0]);
	           		data.data.forEach(function(item){
	           			
	           			options=options+"<option value='"+item+"'>"+item+"</option>";	           			
	           		});
	           		document.getElementById('newcurso').innerHTML=options;           	
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
		var gestion=document.getElementById('Fgestion').value;

		//alert(curso);
		
		var dataestu={
			"EstNivel":nivel,
			"EstCurso":curso,
		}

		var url="<?php echo site_url('Est_estudiante_contr/ajax_get_idcurso');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        data:dataestu,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		// cargarBusqueda(data.data[0]);
	           		//
	           		var gestion=document.getElementById('Fgestion').value;
	           		//alert(data.data[0]+"&"+gestion);
	           		var id=gestion+data.data[0];
	           		cargarBusqueda(id);
	           		 _global_idcur=data.data[0];
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});

	}


	//cargar Busqueda
	function cargarBusqueda(id)
	{

	    url1="<?php echo site_url('Est_estudiante_contr/ajax_list_idcurso');?>/"+id;    
		testudiante=$('#table_estudiante').DataTable({
			"destroy": true,
			"serverSide":true,
			"order":[],
			"processing":true,
			"ajax":{
				"url":url1,
				"type":"POST",
			},

			"columnDefs":[
			{
				"targets":[-1],
				"orderable":false,
			},
			],
		});

		
	}

	function getidcur()
	{		
		var idcur="";
		var curso=document.getElementById('newcurso').value;
		var nivel=document.getElementById('newnivel').value;

		//alert(curso);
		
		var dataestu={
			"EstNivel":nivel,
			"EstCurso":curso,
		}

		var url="<?php echo site_url('Est_estudiante_contr/ajax_get_idcurso');?>";

		$.ajax({
			
	        url : url,
	        type: "POST",
	        data:dataestu,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		 	           		    	
	           		document.getElementById('newidcurso').value=data.data[0];
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});

	}



	//cargar Busqueda
	function cargarBusqueda(idcur)
	{

		url1="<?php echo site_url('Est_estudiante_contr/ajax_list_idcurso');?>/"+idcur;    
		testudiante=$('#table_estudiante').DataTable({
			"destroy": true,
			"serverSide":true,
			"order":[],
			"processing":true,
			"ajax":{
				"url":url1,
				"type":"POST",
			},

			"columnDefs":[
			{
				"targets":[-1],
				"orderable":false,
			},
			],
		});

		//alert(nivel+"-"+gestion+"-"+colegio+"-"+cur);
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
		
		var idest,rude,ci,paterno,materno,nombre,genero,codigo,observ,debe,repite;
		var error=false;
		var url;
				
		
		url="<?php echo site_url('Est_estudiante_contr/ajax_update_estud');?>";
		
		idest=document.getElementById('idest').value;
		observ=document.getElementById('observ').value;

		
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
		var debe=document.getElementById('debe').value;
		var repite=document.getElementById('repite').value;
	
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
				"foto":x,
				"debe":debe,
				"repite":repite,
				"observ":observ
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

	function reload_table()
	{
		testudiante.ajax.reload(null,false);

	}

	function export_exel()
	{
		var gestion=document.getElementById('Fgestion').value;
		var colegios=document.getElementById('Fcolegio').value;
		var nivel=document.getElementById('Fnivel').value;
		var valor=_global_idcur;//curso
		
		var id=gestion+"W"+valor+"W"+colegios+"W"+nivel+"W";

		//alert("curso:"+valor+"gestion:"+gestion+"cplegio:"+colegios+"Nivel:"+nivel);
		//alert("curso:"+id);
	
		
	    var url = "<?php  echo site_url('Est_estudiante_contr/export_exel');?>/"+id;
	    //alert(url);

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

		//guardado de usuario
	function save_estud_new()
	{
		//alert(save_method);
		
		var idest,rude,ci,paterno,materno,nombre,genero,codigo,nivel,gestion,colegio,idcurso;
		var error=false;
		var url;
				
		
		url="<?php echo site_url('Est_estudiante_contr/ajax_save_estud');?>";
		
		// idest=document.getElementById('idestnew').value;		
		rude=document.getElementById('newrude').value;
		ci=document.getElementById('newci').value;
		appaterno=document.getElementById('newappaterno').value;
		apmaterno=document.getElementById('newapmaterno').value;
		nombre=document.getElementById('newnombre').value;
		genero=document.getElementById('newgenero').value;
		codigo=document.getElementById('newcodigo').value;
		// nivel=document.getElementById('newnivel').value;
		// gestion=document.getElementById('newgestion').value;
		// colegio=document.getElementById('newcolegio').value;
		// idcurso=document.getElementById('newidcurso').value;

		var data1={
			// "idest":idest,
			"rude":rude,
			"ci":ci,
			"appaterno":appaterno,
			"apmaterno":apmaterno,
			"nombres":nombre,
			"genero":genero,
			// "idcurso":idcurso,			
			// "nivel":nivel,
			// "gestion":gestion,
			// "colegio":colegio,
			"codigo":codigo
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
		           		$('#modal_form_nuevo').modal('hide');

		           		           		
		           }
		        },
		        error: function (jqXHR, textStatus, errorThrown)
		        {
		           // alert('No puede obtener codigo nuevo, para el registro');
		        }
	    	});		    	
	    
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

		reload_table();
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
				$('[name="debe"]').val(data.debe);
				$('[name="repite"]').val(data.repite);
				
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


	function nuevo_estud()
	{

		
		$('#btnSave').text('Guardar');
		
		//$('[name="idestnew"]').val(data.idest);
		$('[name="newrude"]').val("");
		$('[name="newci"]').val("");	
		$('[name="newappaterno"]').val("");
		$('[name="newapmaterno"]').val("");						
		$('[name="newnombre"]').val("");						
		$('[name="newcodigo"]').val("");
		$('[name="newnivel"]').val("");
		$('[name="newcurso"]').val("");
		$('[name="newidcurso"]').val("");
		$('[name="newcolegio"]').val("");
		$('[name="newgestion"]').val("");


		$('#modal_form_nuevo').modal('show');
	}

	function generoCursoColegio()
	{
		var gestion=document.getElementById('Fgestion').value;
		var colegios=document.getElementById('Fcolegio').value;
		var nivel=document.getElementById('Fnivel').value;
		var valor=_global_idcur;//curso
		
		var id=gestion;

		//alert("curso:"+valor+"gestion:"+gestion+"cplegio:"+colegios+"Nivel:"+nivel);
		//alert("curso:"+id);
	
		
	    var url = "<?php  echo site_url('Est_estudiante_contr/print_generoCurso');?>/"+id;
	    //alert(url);

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

	function mejores_notas()
	{
		var gestion=document.getElementById('Fgestion').value;
		var colegios=document.getElementById('Fcolegio').value;
		var nivel=document.getElementById('Fnivel').value;
		var valor=_global_idcur;//curso
		
		var id=gestion+"W"+valor+"W"+colegios+"W"+nivel+"W";

		//alert("curso:"+valor+"gestion:"+gestion+"cplegio:"+colegios+"Nivel:"+nivel);
		//alert("curso:"+id);
	
		
	    var url = "<?php  echo site_url('Est_estudiante_contr/print_mejor_notas_exel');?>/"+id;
	    //alert(url);

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

	function generoCurso()
	{
		var gestion=document.getElementById('Fgestion').value;
		var colegios=document.getElementById('Fcolegio').value;
		var nivel=document.getElementById('Fnivel').value;
		var valor=_global_idcur;//curso
		
		var id=gestion+"W"+valor+"W"+colegios+"W"+nivel+"W";

		//alert("curso:"+valor+"gestion:"+gestion+"cplegio:"+colegios+"Nivel:"+nivel);
		//alert("curso:"+id);
	
		
	    var url = "<?php  echo site_url('Est_estudiante_contr/print_generoCurso');?>/"+id;
	    //alert(url);

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
	function genero()
	{
		var gestion=document.getElementById('Fgestion').value;
		var colegio=document.getElementById('Fcolegio').value
		var valor=_global_idcur;
		var id=gestion+valor;
		//alert(valor);
		
	    var url = "<?php  echo site_url('Est_estudiante_contr/print_genero');?>/"+id;

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
	

	//cargar tabla
	function reload_estud()
	{
		testudiante.ajax.reload(null,false);

	}

	function reload_all()
	{
		testudiante=$('#table_estudiante').DataTable({
			"destroy": true,
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				"url":"<?php echo site_url('Est_estudiante_contr/ajax_list');?>",
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

	}

	function getidestnew()
	{
		var data1={
				"table":"estudiante",
				"cod":"EST-",
		};
	
		$.ajax({
			
	        url : "<?php echo site_url('Est_estudiante_contr/ajax_get_idestnew');?>",
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

	function export_est_curso_exel()
	{
		var gestion=document.getElementById('Fgestion').value;
		var colegios=document.getElementById('Fcolegio').value;
		var nivel=document.getElementById('Fnivel').value;
		var valor=_global_idcur;//curso
		
		var id=gestion+"W"+valor+"W"+colegios+"W"+nivel+"W";

		//alert(valor);
		
	    var url = "<?php  echo site_url('Est_estudiante_contr/print_report_est_curso');?>/"+id;

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

	function export_naci_pdf()
	{
		var gestion=document.getElementById('Fgestion').value;
		var colegios=document.getElementById('Fcolegio').value;
		var nivel=document.getElementById('Fnivel').value;
		var valor=_global_idcur;//curso
		
		var id=gestion+"W"+valor+"W"+colegios+"W"+nivel+"W";

		//alert(valor);
		
	    var url = "<?php  echo site_url('Est_estudiante_contr/print_report_lugar_nac');?>/"+id;

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

	function export_fecha_pdf()
	{
		var gestion=document.getElementById('Fgestion').value;
		var colegios=document.getElementById('Fcolegio').value;
		var nivel=document.getElementById('Fnivel').value;
		var valor=_global_idcur;//curso
		
		var id=gestion+"W"+valor+"W"+colegios+"W"+nivel+"W";

		//alert(valor);  
		
	    var url = "<?php  echo site_url('Est_estudiante_contr/print_report_fecha_exel');?>/"+id;

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


	//ventana para el pdf
	function export_pdf()
	{
		var gestion=document.getElementById('Fgestion').value;
		var valor=_global_idcur;
		var id=gestion+valor;
		//alert(valor);
		
	    var url = "<?php  echo site_url('Est_estudiante_contr/print_report');?>/"+id;

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

	//ventana para el pdf
	function export_pdf_fono()
	{
		var gestion=document.getElementById('Fgestion').value;
		// var valor=_global_idcur;
		// var id=gestion+valor;

		var nivel=document.getElementById('Fnivel').value;
		var curso=document.getElementById('Fcurso').value;
		var col=document.getElementById('Fcolegio').value;
		if(nivel=="PRIMARIA TARDE") {nivel="PT"; }
		if(nivel=="SECUNDARIA TARDE") {nivel="ST"; }
		if(nivel=="PRIMARIA MAÑANA") {nivel="PM"; }
		if(nivel=="SECUNDARIA MAÑANA") {nivel="SM"; }
		if(curso=='PRIMERO A'){curso='1A';}
		if(curso=='PRIMERO B'){curso='1B';}
		if(curso=='SEGUNDO A'){curso='2A';}
		if(curso=='SEGUNDO B'){curso='2B';}
		if(curso=='TERCERO A'){curso='3A';}
		if(curso=='TERCERO B'){curso='3B';}
		if(curso=='CUARTO A'){curso='4A';}
		if(curso=='CUARTO B'){curso='4B';}
		if(curso=='QUINTO A'){curso='5A';}
		if(curso=='QUINTO B'){curso='5B';}
		if(curso=='QUINTO C'){curso='5C';}
		if(curso=='SEXTO A'){curso='6A';}
		if(curso=='SEXTO B'){curso='6B';}
		if(curso=='SEXTO C'){curso='6C';}
		//alert(valor);
		var id=gestion+"-"+nivel+"-"+curso+"-"+col+"-";
	    var url = "<?php  echo site_url('Est_estudiante_contr/print_report_fono');?>/"+id;

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

	 function ajax_get_idinscrip(id)
	 {
	
		var url = "<?php  echo site_url('Est_estudiante_contr/ajax_get_idins');?>/"+id;
		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		//datos recuperados	           		
	           		//return data.idins;	           		
	           		//_idinscrip=data.idins;
	           		print_rude1(data.idins);
	           		
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	           
	        }
    	});

	 }

	 function ajax_get_idinscrip2(id)
	 {
	
		var url = "<?php  echo site_url('Est_estudiante_contr/ajax_get_idins');?>/"+id;
		$.ajax({
			
	        url : url,
	        type: "POST",
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		//datos recuperados	           		
	           		//return data.idins;	           		
	           		//_idinscrip=data.idins;
	           		
	           		print_ctto1(data.idins);
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	           
	        }
    	});

	 }

	 function print_rude1(id)
	 {

	 	var url = "<?php echo site_url('inscrip_contr/print_rude7');?>/"+id;
   
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
	 function cursorude()
	 {

		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		var curso=document.getElementById('Fcurso').value;//curso
		if(curso=='PRIMERO A'){cursos='1A';}
		if(curso=='PRIMERO B'){cursos='1B';}
		if(curso=='SEGUNDO A'){cursos='2A';}
		if(curso=='SEGUNDO B'){cursos='2B';}
		if(curso=='TERCERO A'){cursos='3A';}
		if(curso=='TERCERO B'){cursos='3B';}
		if(curso=='CUARTO A'){cursos='4A';}
		if(curso=='CUARTO B'){cursos='4B';}
		if(curso=='QUINTO A'){cursos='5A';}
		if(curso=='QUINTO B'){cursos='5B';}
		if(curso=='QUINTO C'){cursos='5C';}
		if(curso=='SEXTO A'){cursos='6A';}
		if(curso=='SEXTO B'){cursos='6B';}
		if(curso=='SEXTO C'){cursos='6C';}
		if(nivel=="PRIMARIA TARDE"){nivels="PT";}
		if(nivel=="SECUNDARIA TARDE"){nivels="ST";}
		if(nivel=="PRIMARIA MAÑANA"){nivels="PM";}
		if(nivel=="SECUNDARIA MAÑANA"){nivels="SM";}

		var id=gestion+"W"+cursos+"W"+nivels+"W";

	 	var url = "<?php echo site_url('inscrip_contr/print_rude_curso');?>/"+id;
   
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

	 function print_ctto1(id)
	 {
	 	var url = "<?php echo site_url('inscrip_contr/print_ctto7');?>/"+id;
   	   	   
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

	 function edit_rude(id)
	 {
	 		var url = "<?php  echo site_url('Edit_inscrip_contr/inscrip_alumn');?>/"+id;
			//var url = "<?php // echo site_url('inscrip_contr');?>/"+id;
		    $.ajax({
		            url :url,
		            type: "POST",	            
		            success: function(data)
		            {
		                window.open(url,"menubar=no,scrollbars=yes,statubar=yes,titlebar=yes,width=700,height=700"); 

		            },
		            error: function (jqXHR, textStatus, errorThrown)
		            {
		                swal({
		                    title: "Enlace no Generado",
		                    text: "Hubo un error, comuniquese con el administrador.",
		                    confirmButtonColor: "#2196F3",
		                    type: "error"
		                });
		            }
		        });	

	 }



	//print RUDE

	function print_rude(id)
	{
		ajax_get_idinscrip(id);		
	}

	function print_ctto(id)
	{
		ajax_get_idinscrip2(id);	
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
            <div class="modal-header bg-green-300">
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
		                        <div class="form-group">
		                        	<label class="control-label col-md-4">DEBE:</label>
		                            <div class="col-md-8">
		                            	<select class="form-control" name="debe" id="debe">
										  <option value="0">NO</option>
										  <option value="1">SI</option>
										</select>
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
		                        <div class="form-group">
		                        	<label class="control-label col-md-4">REPITENTE:</label>
		                            <div class="col-md-8">
		                            	<select class="form-control" name="repite" id="repite">
										  <option value="0">NO</option>
										  <option value="1">SI</option>
										</select>
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
		                        <div class="form-group">
		                        	<label class="control-label col-md-4">OBSERV:</label>
		                            <div class="col-md-8">
		                            	<select class="form-control" name="observ" id="observ">
										  <option value=""></option>
										  <option value="abandono">ABANDONO</option>
										  <option value="fallecimiento">FALLECIMIENTO</option>
										  <option value="traslado">TRASLADO</option>
										</select>
		                                <span class="help-block"></span>
		                            </div>
		                        </div>		                                       
                    		</div>
                    		<div class="col-sm-3">
	                        	<img src='' width='160' height='200' id="loadimg"> 
	                        	<br><br>
                    			<label class="btn bg-green btn-file">
   								 	Cargar Foto
   								 	<input type="file" class="form-control " id="photo" onchange="openFile(this)">
								</label>             	                          
	                    	</div>
                    	</div>
                    	<hr class="bg-green-300">
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
            <div class="modal-footer bg-green-300">
            	<br>
                <button type="button" id="btnSave" onclick="save_estud()" class="btn bg-green-700">Guardar</button>
                <button type="button" class="btn bg-danger-300" data-dismiss="modal">Cancelar</button>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->



<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_nuevo" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary-300">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Formulario de Revisión de Alumnos</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                    	<div class="row">
                    		<div class="col-sm-3">
                    			<!-- <div class="form-group">
		                            <label class="control-label col-md-4">ID EST:</label>
		                            <div class="col-md-8">
		                                <input name="newidest" placeholder="" class="form-control" type="text" id="idestnew" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div> -->
		                        <div class="form-group">
		                            <label class="control-label col-md-4">PATERNO:</label>
		                            <div class="col-md-8">
		                                <input type="text" name="appaterno" placeholder="apellido paterno" class="form-control" id="newappaterno">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>

		                        <div class="form-group">
		                            <label class="control-label col-md-4">RUDE:</label>
		                            <div class="col-md-8">
		                                <input name="rude" placeholder="RUDE" class="form-control" type="text" id="newrude">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        
		                        		                        
                    		</div>

                    		<div class="col-sm-3">

                    			<div class="form-group">
		                            <label class="control-label col-md-4">MATERNO</label>
		                            <div class="col-md-8">
		                                <input type="text" name="apmaterno" placeholder="apellido materno" class="form-control" id="newapmaterno" >
		                                <span class="help-block"></span>
		                            </div>
		                        </div>

                    			

		                        <div class="form-group">
		                            <label class="control-label col-md-4">CI:</label>
		                            <div class="col-md-8">
		                                <input name="ci" placeholder="CI" class="form-control" type="text" id="newci">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>

		                         

		                       
		                        	                        
		                        
                    		</div>

                    		<div class="col-sm-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">NOMBRE:</label>
		                            <div class="col-md-8">
		                                <input type="text" name="nombre" placeholder="nombres" class="form-control" id="newnombre" >
		                                <span class="help-block"></span>
		                            </div>
		                        </div>

                    			<div class="form-group">
		                            <label class="control-label col-md-4">CODIGO:</label>
		                            <div class="col-md-8">
		                            	<input type="text" class="form-control" name="codigo" id="newcodigo" placeholder="banco" 
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        
		                        	                                       
                    		</div>
                    		<div class="col-md-3">
                    			
		                        <div class="form-group">
		                            <label class="control-label col-md-4">GENERO:</label>
		                            <div class="col-md-8">
		                                <select id='newgenero' class="form-control">
		                                	<option value="F">FEMENINO</option>
											<option value="M">MASCULINO</option>											
										</select>
		                            </div>
		                        </div>
                    		</div>
                    		
                    	</div>
                    	<hr class="bg-primary-300">
                    	<!-- <div class="row">
                    		<div class="col-sm-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">NIVEL:</label>
		                            <div class="col-md-8">
		                            	<select id='newnivel' class="form-control" onchange="newgescole(this.value);newgetcur(this.value)">
		                            	</select>	
		                            </div>
		                        </div>
                    		</div>
                    		<div class="col-sm-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">COLEGIO:</label>
		                            <div class="col-md-8">
		                            	<input type="text" class="form-control" name="colegio" id="newcolegio" readonly="true"> 
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
                    		</div>
                    		<div class="col-sm-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">GESTION:</label>
		                            <div class="col-md-8">
		                                <input type="text" name="gestion" placeholder="año" class="form-control" id="newgestion" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                       
                    		</div>
                    		<div class="col-sm-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">CURSO:</label>
		                            <div class="col-md-8">
		                                <select id='newcurso' class="form-control" onchange="getidcur()">
		                            	</select>	
		                            </div>
		                        </div>		                       
                    		</div>
                    	</div>
                    	<hr class="bg-primary-300">
                    	<div class="row">
                    		<div class="col-sm-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">ID CURSO:</label>
		                            <div class="col-md-8">
		                                <input type="text" name="newidcurso" placeholder="año" class="form-control" id="newidcurso" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>		                       
                    		</div>
                    	</div>
                     
                    </div> -->
                </form>
            </div>
            <div class="modal-footer bg-primary-300">
            	<br>
                <button type="button" id="btnSave" onclick="save_estud_new()" class="btn bg-primary-700">Guardar</button>
                <button type="button" class="btn bg-danger-300" data-dismiss="modal">Cancelar</button>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
