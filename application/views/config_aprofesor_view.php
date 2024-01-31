<body>
	<!-- Main navbar -->
	<div class="navbar bg-primary-300 ">
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

								
								<li class="navigation-header"><span>Configuración</span> <i class="icon-menu" title="Usuarios"></i></li>
								<li>
									<a href='<?php echo site_url('Config_gestion_contr');?>'><i class="icon-user-lock"></i> <span>Gestión</span></a>									
								</li>
								<li>
									<a href='<?php echo site_url('Config_colegios_contr');?>'><i class="icon-users4"></i> <span>Colegios</span></a>								
								</li>
								<li>
									<a href='<?php echo site_url('Config_nivel_contr');?>'><i class="icon-users4"></i> <span>Nivel</span></a>								
								</li>
								<li>
									<a href='<?php echo site_url('Config_curso_contr');?>'><i class="icon-user-minus"></i> <span>Cursos</span></a>									
								</li>
								
								<li>
									<a href='<?php echo site_url('Config_aprofesor_contr');?>'><i class="icon-puzzle"></i> <span>Materias</span></a>									
								</li>
								<li>
									<a href='<?php echo site_url('Config_amateria_contr');?>'><i class="icon-puzzle"></i> <span>Asignar de Materia</span></a>									
								</li>
								<li>
									<a href='<?php echo site_url('Config_aprofesor_contr');?>'><i class="icon-puzzle"></i> <span>Asignar de Profesor</span></a>									
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
							<div class="page-title ">
								<br>
								<h5 class="text-right">	
									<img src="assets/images/logo1.png" alt="" width="65" height="75" >									
									<small class="display-block">Gestión 2020</small>

								</h5>
								<br>
							</div>							
						</div>

						<div class="breadcrumb-line">
							<ul class="breadcrumb">
								<li><a href="Principal/index"><i class="icon-home2 position-left"></i> Principal</a></li>
								
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
									<h6 class="panel-title">Registrar Materia</h6>
									<div class="heading-elements">
										<ul class="icons-list">
											<li><a data-action="reload"></a></li>
					                	</ul>
				                	</div>
								</div>

								<div class="panel-body">
									<div class="col-sm-3">
		                    			<div class="form-group">
											<button class="btn bg-primary-600" onclick="add_mat()"><i class="glyphicon glyphicon-plus"></i> Add profesor</button>
									        <!-- <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button> -->
									    </div>
									</div>
							        <div class="col-sm-2">
		                    			<div class="form-group">
		                    				<label class="control-label">GESTION:</label>						                    
									        	<select class="form-control" type="text" id="Fgestion" onchange="">
			                                	</select>			                                
			                                <span class="help-block"></span>
			                            </div>			                            
					                </div>
					                <div class="col-sm-2">
					                	<div class="form-group">
		                    				<label class="control-label">NIVEL:</label>
				                                <Select class="form-control" type="text" id="Fnivel" onchange="gescole(this.value);getcur(this.value)">
				                                </Select>
				                                <span class="help-block"></span>				                           
			                            </div>
					                </div>
					                <div class="col-sm-2">
					                	<div class="form-group">
		                    				<label class="control-label">COLEGIO:</label>
				                               <input type="text" id='Fcolegio' class="form-control" readonly="true">
				                                <span class="help-block"></span>				                           
			                            </div>
					                </div>
					                <div class="col-sm-2">
					                	<div class="form-group">
				                            <label class="control-label">CURSO:</label>
				                                <select  name="Fcurso" class="form-control" id="Fcurso" onchange="getestud();materias();">
				                                </select>
				                                <span class="help-block"></span>
				                        </div>
					                </div>
	                                <!--<div class="col-sm-3">
		                    			<div class="form-group">
							        		<button class="btn  btn-danger pull-right" onclick="export_pdf()" ><i class="icon icon-file-pdf"></i> PDF</button>
							        	</div>
							        </div>-->
				                    				
							        <br />
							        <br />
							        <table id="table_mat" class="table datatable-select-basic table-bordered " cellspacing="0" width="100%">
							            <thead class="bg-primary-300">
							                <tr>
							                    <th>NRO</th>
							                    <th>MATERIA</th>
							                    <th>CURSO</th>
							                    <th>NIVEL</th>
							                    <th>PROFESOR</th>
							                    <th>GESTION</th>
							                    <th>ACTION</th>
							                </tr>
							            </thead>
							            <tbody>
							            </tbody>

							            <tfoot class="bg-primary-300">
								             <tr>
							                    <th>NRO</th>
							                    <th>MATERIA</th>
							                    <th>CURSO</th>
							                    <th>NIVEL</th>
							                    <th>PROFESOR</th>
							                    <th>GESTION</th>
							                    <th>ACTION</th>
							                </tr>
							            </tfoot>
							        </table>
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
	var tmateria;
	var save_method;
	var _global_idcur="";

	$(document).ready(function(){
		get_gestion();
		get_nivel();
		getprofe()

	});

	//SELECT nivel
	function get_nivel()
	{
		var options="<option value=''></option>";
		//envio de valores
		var data1={
				"table":"nivel",
		};
		
		$.ajax({
			
	        url : "<?php echo site_url('Karde_contr/ajax_get_niveles');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	
	           		i=0;           		
	           		data.data.forEach(function(item){
	           			
	           			options=options+"<option value='"+data.data1[i]+"'>"+item+"</option>";
	           			i++;	           			
	           		});
	           		document.getElementById('Fnivel').innerHTML=options;
	           		document.getElementById('nivel').innerHTML=options;	
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
			
	        url : "<?php echo site_url('Karde_contr/ajax_get_colegio');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		//datos recuperados
	           	//	document.getElementById('Fgestion').value=data.data[0];
	           		document.getElementById('Fcolegio').value=data.data[0];           	
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
			
	        url : "<?php echo site_url('Karde_contr/ajax_get_cursos');?>",
	        type: "POST",
	        data:datacur,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           	i=0;
	           		//alert(data.data[0]);
	           		data.data.forEach(function(item){
	           			
	           			options=options+"<option value='"+data.data1[i]+"'>"+item+"</option>";	 
	           			i++;          			
	           		});
	           		document.getElementById('Fcurso').innerHTML=options;  
	           		curso(nivel,"acurso");
					curso(nivel,"codcurso");         	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
		
	}

	function cerrar()
	{		
		var url="<?php echo site_url('Config_aprofesor_contr/ajax_cerrar')?>";
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
			
	        url : "<?php echo site_url('Config_aprofesor_contr/ajax_get_gestion');?>",
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
	           		//document.getElementById('Fgestion').value='2019';
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}

	function add_mat() //CARGA EL MODAL
	{
	    save_method = 'add';
	    $('#form')[0].reset(); // reset form on modals
	    $('.form-group').removeClass('has-error'); // clear error class
	    $('.help-block').empty(); // clear error string
	   // document.getElementById('loadimg').src=temp;
	  
	    var gestion=document.getElementById('Fgestion').value;
	    document.getElementById('gestion').value=gestion;

	    $('#modal_form').modal('show'); // show bootstrap modal
	    $('.modal-title').text('ADICIONAR PROFESOR'); // Set Title to Bootstrap modal title
	    $('#btnSave').text('Guardar');
	    //alert(document.getElementById('Fgestion').value);

	}

	//obtener codigo nuevo para el registro
	function get_idcod()
	{
		//envio de valores
		var data1={
				"table":"materia",
				"cod":"MAT-",
		};
	
		$.ajax({
			
	        url : "<?php echo site_url('Config_aprofesor_contr/ajax_get_id');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		//datos recuperados
	           		document.getElementById('newcod').value=data.codgen;
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}

	function getnivel()
	{
		var options="<option value=''></option>";

		var data1={
				"table":"curso",
		};
	
		$.ajax({

	        url : "<?php echo site_url('Config_aprofesor_contr/ajax_get_level');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		//alert(data.data[0]);
	           		data.data.forEach(function(item){
	           			
	           			options=options+"<option value='"+item+"'>"+item+"</option>";	           			
	           		});
	           		document.getElementById('nivel').innerHTML=options;                	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}

	function getcole(id)
	{		
		

		var data1={
				"table":"curso",
				"lvl":id,
		};
	
		$.ajax({
			
	        url : "<?php echo site_url('Karde_contr/ajax_get_colegio');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		//datos recuperados
	           	//	document.getElementById('Fgestion').value=data.data[0];
	           		document.getElementById('colegio').value=data.data[0];           	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}

	function getcurso(id)
	{
		var options="<option value=''></option>";
		var data1={
				"table":"curso",
				"nivel":id,
		};
		// alert(id);
		$.ajax({

	        url : "<?php echo site_url('Config_aprofesor_contr/ajax_get_curso');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   var i=-1;
	           if(data.status)
	           {
	           	
	           		//alert(data.data[0]);
	           		data.data.forEach(function(item){	
	           		i++;           			
	           			options=options+"<option value='"+data.data1[i]+"'>"+item+"</option>";	           			
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
	function materias()
	{
		var options="<option value=''></option>";

		var data1={
				"table":"curso",
				"nivel":document.getElementById('Fnivel').value,
				"curso":document.getElementById('Fcurso').value,
		};

		// alert(id);
		$.ajax({

	        url : "<?php echo site_url('Config_aprofesor_contr/ajax_materias');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   var i=-1;
	           if(data.status)
	           {
	           	
	           		//alert(data.data[0]);
	           		data.data.forEach(function(item){	
	           		i++;           			
	           			options=options+"<option value='"+data.data1[i]+"'>"+item+"</option>";	           			
	           		});
	           		document.getElementById('materia').innerHTML=options;            	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}
	function getmateria(id)
	{
		var options="<option value=''></option>";
		var data1={
				"table":"curso",
				"materia":id,
		};
		// alert(id);
		$.ajax({

	        url : "<?php echo site_url('Config_aprofesor_contr/ajax_get_materia');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   var i=-1;
	           if(data.status)
	           {
	           	
	           		//alert(data.data[0]);
	           		data.data.forEach(function(item){	
	           		i++;           			
	           			options=options+"<option value='"+data.data1[i]+"'>"+item+"</option>";	           			
	           		});
	           		document.getElementById('materia').innerHTML=options;            	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}

	function getcurso2(id)
	{
		var options="<option value=''></option>";
		var data1={
				"table":"curso",
				"idcur":id,
		};
	
		$.ajax({

	        url : "<?php echo site_url('Config_aprofesor_contr/ajax_get_curso2');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		document.getElementById('curso').value=data.data[0];  
	           		document.getElementById('corto').value=data.data[1];             	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});

	}


	function getcorto(cur)
	{
		var data1={
				"table":"curso",
				"nivel":document.getElementById('nivel').value,
				"curso":cur,
		};
	
		$.ajax({

	        url : "<?php echo site_url('Config_aprofesor_contr/ajax_get_corto');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		document.getElementById('corto').value=data.data[0];  
	           		document.getElementById('idcur').value=data.data[1];            	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}

	function getcampo()
	{
		var a='COMUNIDAD Y SOCIEDAD';
		var b='CIENCIA TECNOLOGIA Y PRODUCCION';
		var c='VIDA TIERRA TERRITORIO';
		var d='COSMOS Y PENSAMIENTO';
		var options="<option value=''></option>";
		options=options+"<option value='"+a+"'>"+a+"</option>";
		options=options+"<option value='"+b+"'>"+b+"</option>";
		options=options+"<option value='"+c+"'>"+c+"</option>";
		options=options+"<option value='"+d+"'>"+d+"</option>";
		document.getElementById('campo').innerHTML=options; 
	}

	function getarea(campo)
	{
		
		var a='COMUNICACION Y LENGUAJES';
		var b='CIENCIAS SOCIALES';
		var c='EDUCACION FÍSICA Y DEPORTES';
		var d='EDUCACION MUSICAL';
		var e='ARTES PLÁSTICAS Y VISUALES';
		var f='MATEMÁTICAS';
		var g='TÉCNICA Y TECNOLÓGICA GENERAL';
		var h='CIENCIAS NATURALES';
		var i='VALORES, ESPIRITUALIDAD Y RELIGIONES';
		//--------------------------------------------------
		var j='LENGUA CASTELLANA Y ORIGINARIA';
		var k='LENGUA EXTRANJERA';
		var l='CIENCIAS NATURALES:BIOLOGIA - GEOGRAFIA';
		var m='COSMOVISIONES, FILOSOFÍA Y SICOLOGÍA';
		//--------------------------------------------------
		var n='LENGUA EXTRANJERA';
		var o='TÉCNICA Y TECNOLÓGICA ESPECIALIDAD';
		var p='CIENCIAS NATURALES:FISICA - QUIMICA';


		var x=campo;
		var y=document.getElementById('nivel').value;
		var z=document.getElementById('curso').value;
		var options="<option value=''></option>";
		
		if((y=="PRIMARIA MAÑANA")||(y=="PRIMARIA TARDE"))
		{
			if(x=='COMUNIDAD Y SOCIEDAD')
			{
				options=options+"<option value='"+a+"'>"+a+"</option>";
				options=options+"<option value='"+b+"'>"+b+"</option>";
				options=options+"<option value='"+c+"'>"+c+"</option>";
				options=options+"<option value='"+d+"'>"+d+"</option>";
				options=options+"<option value='"+e+"'>"+e+"</option>";
			}
			if(x=='CIENCIA TECNOLOGIA Y PRODUCCION')
			{
				options=options+"<option value='"+f+"'>"+f+"</option>";
				options=options+"<option value='"+g+"'>"+g+"</option>";
			}
			if(x=='VIDA TIERRA TERRITORIO')
			{
				options=options+"<option value='"+h+"'>"+h+"</option>";
			}
			if(x=='COSMOS Y PENSAMIENTO')
			{
				options=options+"<option value='"+i+"'>"+i+"</option>";
			}
		}
		
		if((y=="SECUNDARIA MAÑANA")||(y=="SECUNDARIA TARDE"))
		{
			if((z=='PRIMERO A')||(z=='PRIMERO B')||(z=='SEGUNDO A')||(z=='SEGUNDO B'))
			{
				if(x=='COMUNIDAD Y SOCIEDAD')
				{
					options=options+"<option value='"+j+"'>"+j+"</option>";
					options=options+"<option value='"+k+"'>"+k+"</option>";
					options=options+"<option value='"+b+"'>"+b+"</option>";
					options=options+"<option value='"+c+"'>"+c+"</option>";
					options=options+"<option value='"+d+"'>"+d+"</option>";
					options=options+"<option value='"+e+"'>"+e+"</option>";
				}
				if(x=='CIENCIA TECNOLOGIA Y PRODUCCION')
				{
					options=options+"<option value='"+f+"'>"+f+"</option>";
					options=options+"<option value='"+g+"'>"+g+"</option>";
				}
				if(x=='VIDA TIERRA TERRITORIO')
				{
					options=options+"<option value='"+l+"'>"+l+"</option>";

				}
				if(x=='COSMOS Y PENSAMIENTO')
				{
					options=options+"<option value='"+m+"'>"+m+"</option>";
					options=options+"<option value='"+i+"'>"+i+"</option>";
				}
			}
			else
			{
				if(x=='COMUNIDAD Y SOCIEDAD')
				{
					options=options+"<option value='"+j+"'>"+j+"</option>";
					options=options+"<option value='"+k+"'>"+k+"</option>";
					options=options+"<option value='"+b+"'>"+b+"</option>";
					options=options+"<option value='"+c+"'>"+c+"</option>";
					options=options+"<option value='"+d+"'>"+d+"</option>";
					options=options+"<option value='"+e+"'>"+e+"</option>";
				}
				if(x=='CIENCIA TECNOLOGIA Y PRODUCCION')
				{
					options=options+"<option value='"+f+"'>"+f+"</option>";
					options=options+"<option value='"+o+"'>"+o+"</option>";
				}
				if(x=='VIDA TIERRA TERRITORIO')
				{
					options=options+"<option value='"+l+"'>"+l+"</option>";
					options=options+"<option value='"+p+"'>"+p+"</option>";
				}
				if(x=='COSMOS Y PENSAMIENTO')
				{
					options=options+"<option value='"+m+"'>"+m+"</option>";
					options=options+"<option value='"+i+"'>"+i+"</option>";
				}
			}
		}
		
		document.getElementById('area').innerHTML=options; 
	}

	function getprofe()
	{
		var options="<option value=''></option>";
		var data1={
				"table":" ",
		};
	
		$.ajax({

	        url : "<?php echo site_url('Config_aprofesor_contr/ajax_get_profe');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		var i=0;
	           		//alert(data.data[0]);
	           		data.data.forEach(function(item){	           			
	           			options=options+"<option value='"+data.data1[i]+"'>"+item+"</option>";	    
	           			i++;       			
	           		});
	           		document.getElementById('profe').innerHTML=options;            	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}
	function getprofe2(id)
	{
		var data1={
				"table":"profesor",
				"idprof":id,
		};
	
		$.ajax({

	        url : "<?php echo site_url('Config_aprofesor_contr/ajax_get_profe2');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		document.getElementById('profe').value=data.data[0];             	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}

	function getidprof(nomb)
	{
		var data1={
				"table":"profesor",
				"profe":document.getElementById('profe').value,
		};
	
		$.ajax({

	        url : "<?php echo site_url('Config_aprofesor_contr/ajax_get_idprofe');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		document.getElementById('idprof').value=data.data[0];             	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}

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
	function msg_guardar()
	{
		swal({
            title: "Guardado!",
            text: "Registro Guardado, Satisfactoriamente!",
            confirmButtonColor: "#66BB6A",
            type: "success"
        });

	}

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
	function save_profesor()
	{
		var id,materia,area,nivel,idprof,gestion,curso;
		var error=false;
		var url;

		//alert(save_method);

		
		if(save_method=='add')
		{
			url="<?php echo site_url('Config_aprofesor_contr/ajax_set_profesor');?>";
		}
		else{
			url="<?php echo site_url('Config_aprofesor_contr/ajax_update_profeso');?>";
		}

		id=document.getElementById('id').value;	
		materia=document.getElementById('materia').value;
		curso=document.getElementById('curso').value;	
		nivel=document.getElementById('nivel').value;	
		idprof=document.getElementById('profe').value;	
		gestion=document.getElementById('gestion').value;
		
		if (!error)
		{
			var data1={
				"id":id,
				"materia":materia,
				"curso":curso,
				"nivel":nivel,
				"idprof":idprof,
				"gestion":gestion,			
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

		        }
	    	});		    	
	    	
		}
		
	}

	function delete_materia(id)
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
		            url : "<?php echo site_url('Config_aprofesor_contr/ajax_delete')?>/"+id,
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

	function edit_profesor(id)
	{
		//alert(id);
		save_method='update';
		$('#btnSave').text('Modificar');

		$.ajax({
			url:"<?php echo site_url('Config_aprofesor_contr/ajax_edit_profesor'); ?>/"+id,
			type:"GET",
			dataType:"JSON",
			success: function(data)
			{
				var codigo=data.codigo;
				codigos= codigo.split('-');
				$('[name="id"]').val(data.id_asg_prof);
				$('[name="materia"]').val(data.id_asg_mate);		
				$('[name="profe"]').val(data.id_prof);
				$('[name="nivel"]').val(codigos[1]);
				$('[name="curso"]').val(codigos[4]);
				// alert(data.id_asg_mate);
				$('[name="gestion"]').val(data.gestion);							
				getarea(data.id_asg_mate);
			},
			error: function(jqXHR,textStatus,errorThrown)
			{
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
	
	function reload_table()
	{
		tmateria.ajax.reload(null,false);

	}

	//ventana para el pdf
	function export_pdf()
	{
	
		
	    var url = "<?php  echo site_url('Prof_profesores_contr/print');?>";

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
	function getarea(id_mat)
	{		
		// alert(id_mat);
		var data1={
				"table":"curso",
				"id_mat":id_mat,
		};
	
		$.ajax({
			
	        url : "<?php echo site_url('Config_aprofesor_contr/ajax_get_area');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		//datos recuperados
	           	//	document.getElementById('Fgestion').value=data.data[0];
	           		document.getElementById('area').value=data.data[0];           	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
		
	}
	

	function getestud()
	{

		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		var curso=document.getElementById('Fcurso').value;
		var id=gestion+"-"+nivel+"-"+curso;
		getcurso(nivel);	
		getcole(nivel);

				// alert(codigos[4]);
		// alert("");
		url1="<?php echo site_url('Config_aprofesor_contr/ajax_list_profesor');?>/"+id;    
		tmateria=$('#table_mat').DataTable({
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
                    <input type="hidden" value="" name="id"/> 
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
		                                <input name="colegio" placeholder="colegio" class="form-control" type="text" readonly="true" id="colegio" >
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
		                                <input name="gestion" class="form-control" type="text" readonly="true" id="gestion" >
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
                    			<div class="form-group">
		                            <label class="control-label col-md-2">MATERIA</label>
		                            <div class="col-md-10">
		                                
		                                <Select class="form-control"  name="materia" id="materia" onchange="getarea(this.value)">
						                </Select>
		                                <span class="help-block"></span>
		                            </div>
		                        </div>                    			
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
