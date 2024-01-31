<body>
	<!-- Main navbar -->
	<div class="navbar bg-primary-400">
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
									<a href='<?php echo site_url('Est_inscripcion_contr');?>'><i class="icon-user-lock"></i> <span>Alumnos</span></a>									
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
								
								<li class="active">Inscripciones</li>
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
									<h6 class="panel-title">Inscripción</h6>
									<div class="heading-elements">
										<ul class="icons-list">
										   	<li><a data-action="reload"></a></li>
					                	</ul>
				                	</div>
								</div>

								<div class="panel-body">
										<div class="row">
											<div class="form-group">
				                    				<div class="form-group">
				                    				<button class="btn  bg-success-300" onclick="estnew()" ><i class="icon icon-user-plus"></i> Nuevo Alumno de 1ro Primaria</button>
						                    </div>
										</div>
										<hr class="bg-primary-300">
				                    	<div class="row">
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
						                                <input name="Fcolegio" placeholder="" class="form-control"  id="Fcolegio" readonly="true">
						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div>
						                    <div class="col-sm-2">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">GESTION:</label>
						                            <div class="col-md-9">
						                                <input name="Fgestion" placeholder="" class="form-control" type="text" id="Fgestion" readonly="true">
						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div>
						                    <div class="col-sm-2">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">CURSO:</label>
						                            <div class="col-md-9">
						                                <select  name="Fcurso" class="form-control" id="Fcurso" onchange="getestud()">
						                                </select>
						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div>                    

						                    <div class="col-sm-4">
				                    			<div class="form-group">
				                    				<button class="btn  btn-danger" onclick="export_pdf()" ><i class="icon icon-file-pdf"></i> PDF</button>
				                    				&nbsp;&nbsp;

				                    				<!--<button class="btn bg-primary-800" onclick="export_xls()" ><i class="icon icon-file-excel"></i> EXCEL</button>-->
				                    				
				                    				<button class="btn btn-default" onclick="reload_all()" ><i class="icon icon-user-block"></i>SIN FILTRO</button>
				                    				&nbsp;&nbsp;

						                            <button class="btn btn-default pull-right" onclick="reload_estud()" ><i class="glyphicon glyphicon-refresh"></i> ACTUALIZAR</button>
						                            
						                        </div>
						                    </div>
						                </div>
						                <hr class="bg-primary-300">
							        <table id="table_estudiante" class="table datatable-responsive" cellspacing="0" width="100%" id="table_usuario">
							            <thead class="bg-primary-300">
							                <tr>
							                    <th>ID ESTUD</th>
							                    <th>RUDE</th>
							                    <th>CI</th>
							                    <th>AP PATERNO</th>
							                    <th>AP MATERNO</th>
							                    <th>NOMBRES</th>
							                    <th>GENERO</th>
							                    <th>ID CURSO</th>
							                    <th>CODIGO</th>
							                    
							                    <th>ACTION</th>
							                </tr>
							            </thead>
							            <tbody>
							            </tbody>

							            <tfoot class="bg-primary-300">
								            <tr>
							                    <th>ID ESTUD</th>
							                    <th>RUDE</th>
							                    <th>CI</th>
							                    <th>AP PATERNO</th>
							                    <th>AP MATERNO</th>
							                    <th>NOMBRES</th>
							                    <th>GENERO</th>
							                    <th>ID CURSO</th>
							                    <th>CODIGO</th>							                   
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

	$(document).ready(function(){
		
		testudiante=$('#table_estudiante').DataTable({
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				"url":"<?php echo site_url('Est_inscripcion_contr/ajax_list');?>",
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
				
	});

	function cerrar()
	{		
		var url="<?php echo site_url('Est_inscripcion_contr/ajax_cerrar')?>";
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
			
	        url : "<?php echo site_url('Est_inscripcion_contr/ajax_get_nivel');?>",
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
			
	        url : "<?php echo site_url('Est_inscripcion_contr/ajax_get_level');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		//datos recuperados
	           		document.getElementById('Fgestion').value=data.data[0];
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
		var options="<option value=''></option>";
		//alert(nivel);
		var datacur={
			"TablaCur":"curso",
			"nivel":nivel,
		}

		$.ajax({
			
	        url : "<?php echo site_url('Est_inscripcion_contr/ajax_get_curso');?>",
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

		var url="<?php echo site_url('Est_inscripcion_contr/ajax_get_idcurso');?>";

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

	//cargar Busqueda
	function cargarBusqueda(idcur)
	{

		url1="<?php echo site_url('Est_inscripcion_contr/ajax_list_idcurso');?>/"+idcur;    
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


	//borrar registro
	function delete_estud(id)
	{
		swal({
            title: "¿Esta Seguro?",
            text: "Esta seguro de eliminar el registro "+ id+" !!!",
            type: "info",
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
		            url : "<?php echo site_url('Est_inscripcion_contr/ajax_delete')?>/"+id,
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

	function estnew()
	{								
		var enlace="<?php echo base_url();?>Reg_inscripcion_contr";
		window.location=enlace;	
	}

	function estinscrip(id)
	{
		var enlace="<?php echo base_url();?>Reg_inscripcion_contr/"+id;
		window.location=enlace;			
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
				"url":"<?php echo site_url('Est_inscripcion_contr/ajax_list');?>",
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

	//ventana para el pdf
	function export_pdf()
	{
		var valor=_global_idcur;
		//alert(valor);
		
	    var url = "<?php  echo site_url('Est_inscripcion_contr/print');?>/"+valor;

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
	/*
	function export_xls()
	{
		var valor=_global_idcur;
				
	    var url = "<?php // echo site_url('Est_inscripcion_contr/printxls');?>/"+valor;

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
	}*/

</script>


