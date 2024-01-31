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

								<!-- Forms -->
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
									<a href='<?php echo site_url('Config_materia_contr');?>'><i class="icon-puzzle"></i> <span>Materias</span></a>									
								</li>
								<li>
									<a href='<?php echo site_url('Config_amateria_contr');?>'><i class="icon-puzzle"></i> <span>Asignar de Materia</span></a>									
								</li>
								<li>
									<a href='<?php echo site_url('Config_aprofesor_contr');?>'><i class="icon-puzzle"></i> <span>Asignar de Profesor</span></a>									
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
									<img src="assets/images/logo1.png" alt="" width="65" height="75" >									
									<small class="display-block">Gestión 2018</small>

								</h5>
								<br>
							</div>							
						</div>

						<div class="breadcrumb-line">
							<ul class="breadcrumb">
								<li><a href="Principal/index"><i class="icon-home2 position-left"></i> Principal</a></li>
								
								<li class="active">Niveles</li>
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
									<h6 class="panel-title">Gestionar Niveles</h6>
									<div class="heading-elements">
										<ul class="icons-list">
										   	<li><a data-action="reload"></a></li>
					                	</ul>
				                	</div>
								</div>

								<div class="panel-body">
									<button class="btn btn-primary" onclick="add_nivel()"><i class="glyphicon glyphicon-plus"></i> Nuevo Nivel</button>
							        <button class="btn btn-default" onclick="reload_nivel()"><i class="glyphicon glyphicon-refresh"></i> Cargar</button>
							        <br />
							        <br />
							        <table id="table_nivel" class="table datatable-select-basic table-bordered " cellspacing="0" width="100%" id="table_usuario">
							            <thead class="bg-primary-300">
							                <tr>
							                    <th>ID NIVEL</th>
							                    <th>NIVEL</th>
							                    <th>NOMBRE</th>
							                    <th>GESTION</th>
							                    <th>COLEGIO</th>
							                    <th>TURNO</th>
							                    <th>ACTION</th>
							                </tr>
							            </thead>
							            <tbody>
							            </tbody>

							            <tfoot class="bg-primary-300">
								            <tr>
							                    <th>ID NIVEL</th>
							                    <th>NIVEL</th>
							                    <th>NOMBRE</th>
							                    <th>GESTION</th>
							                    <th>COLEGIO</th>
							                    <th>TURNO</th>
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
	var tnivel;
	var save_method;

	$(document).ready(function(){
		
		tnivel=$('#table_nivel').DataTable({
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				"url":"<?php echo site_url('Config_nivel_contr/ajax_list');?>",
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
		get_colegio();
		

	});

	function cerrar()
	{		
		var url="<?php echo site_url('principal/ajax_cerrar')?>";
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

	function add_nivel()
	{
	   
	    save_method = 'add';
	    $('#form')[0].reset(); // reset form on modals
	    $('.form-group').removeClass('has-error'); // clear error class
	    $('.help-block').empty(); // clear error string
	    $('#modal_form').modal('show'); // show bootstrap modal
	    $('.modal-title').text('ADICIONAR NIVELES'); // Set Title to Bootstrap modal title
	    $('#btnSave').text('Guardar');
	    get_idcod();
	    

	}

	//obtener codigo nuevo para el registro
	function get_idcod()
	{
		//envio de valores
		var data1={
				"table":"nivel",
				"cod":"NIV-",
		};
	
		$.ajax({
			
	        url : "<?php echo site_url('Config_nivel_contr/ajax_get_id');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		//datos recuperados
	           		document.getElementById('idnivel').value=data.codgen;
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}

	//SELECT COLEGIO
	function get_colegio()
	{
		var options="";
		//envio de valores
		var data1={
				"table":"colegios",
		};
		
		$.ajax({
			
	        url : "<?php echo site_url('Config_nivel_contr/ajax_get_colegio');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           		
	           		data.data.forEach(function(item){
	           			//alert(item);
	           			options=options+"<option value='"+item+"'>"+item+"</option>";	           			
	           		});
	           		document.getElementById('colegio').innerHTML=options;          	
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

	//guardado de usuario
	function save_nivel()
	{
	//	alert("hijito");
		
		var idnivel,nivel,nombre,gestion,colegio,turno;
		var error=false;
		var url;
				
		if(save_method=='add')
		{
			url="<?php echo site_url('Config_nivel_contr/ajax_set_nivel');?>";
		}
		else{
			url="<?php echo site_url('Config_nivel_contr/ajax_update_nivel');?>";
		}

		idnivel=document.getElementById('idnivel').value;

		if(!validacion('nivel','NIVEL'))		
			nivel=document.getElementById('nivel').value;
		else
			error=true;

		if(!validacion('nombre','NOMBRE'))		
			nombre=document.getElementById('nombre').value;
		else
			error=true;

		if(!validacion('gestion','GESTION'))		
			gestion=document.getElementById('gestion').value;
		else
			error=true;

		if(!validacion('colegio','COLEGIO'))		
			colegio=document.getElementById('colegio').value;
		else
			error=true;

		if(!validacion('turno','TURNO'))		
			turno=document.getElementById('turno').value;
		else
			error=true;

	
		if (!error)
		{
			var data1={
				"idnivel":idnivel,
				"nivel":nivel,
				"nombre":nombre,
				"gestion":gestion,
				"colegio":colegio,
				"turno":turno
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

	function delete_nivel(id)
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
		            url : "<?php echo site_url('Config_nivel_contr/ajax_delete')?>/"+id,
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

	function edit_nivel(id)
	{
		
		save_method='update';
		$('#btnSave').text('Modificar');
		var img="";

		$.ajax({
			url:"<?php  echo site_url('Config_nivel_contr/ajax_edit_nivel'); ?>/"+id,
			type:"GET",
			dataType:"JSON",
			success: function(data)
			{
				$('[name="idnivel"]').val(data.idnivel);
				$('[name="nivel"]').val(data.nivel);
				$('[name="nombre"]').val(data.nombre);
				$('[name="gestion"]').val(data.gestion);
				$('[name="colegio"]').val(data.colegio);
				//document.getElementById('colegio').value=data.colegio;
				$('[name="turno"]').val(data.turno);

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
	
	function reload_table()
	{
		tnivel.ajax.reload(null,false);

	}

	

</script>


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary-300">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Formulario de Registro de Niveles</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                    	<div class="form-group">
                            <label class="control-label col-md-3">ID NIVEL</label>
                            <div class="col-md-9">
                                <input name="idnivel" placeholder="nivel" class="form-control" type="text" id="idnivel" readonly="true">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">NIVEL</label>
                            <div class="col-md-9">
                                <input name="nivel" placeholder="nombre" class="form-control" type="text" id="nivel">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">NOMBRE</label>
                            <div class="col-md-9">
                                <input name="nombre" placeholder="Nombre Completo" class="form-control" type="text" id="nombre">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">GESTION</label>
                            <div class="col-md-9">
                                <input type="number" name="gestion" placeholder="año" class="form-control" id="gestion">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">COLEGIO</label>
                            <div class="col-md-9">
                            	<select class="form-control" name="colegio" id="colegio">
                            	</select>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">TURNO</label>
                            <div class="col-md-9">
                            	<select class="form-control" name='turno' id='turno'>
								  <option value="MAÑANA">MAÑANA</option>
								  <option value="TARDE">TARDE</option>
								</select>                               
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-primary-300">
            	<br>
                <button type="button" id="btnSave" onclick="save_nivel()" class="btn bg-info-700">Guardar</button>
                <button type="button" class="btn bg-danger-300" data-dismiss="modal">Cancelar</button>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
