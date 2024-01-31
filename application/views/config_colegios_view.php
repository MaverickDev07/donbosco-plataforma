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
								
								<li class="active">Gestionar Niveles</li>
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
									<h6 class="panel-title">Crear Niveles</h6>
									<div class="heading-elements">
										<ul class="icons-list">
										   	<li><a data-action="reload"></a></li>
					                	</ul>
				                	</div>
								</div>

								<div class="panel-body">
									<button class="btn btn-primary" onclick="add_usuario()"><i class="glyphicon glyphicon-plus"></i> Nuevo Colegio</button>
							        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Cargar</button>
							        <br />
							        <br />
							        <table id="table_colegios" class="table datatable-select-basic table-bordered " cellspacing="0" width="100%" id="table_usuario">
							            <thead class="bg-primary-300">
							                <tr>
							                	<th>NRO</th>
							                    <th>COLEGIO</th>
							                    <th>SIE</th>
							                    <th>DIRECCION</th>
							                    <th>TELEFONO</th>
							                    <!-- <th>LOGO</th> -->
							                    <th>ACTION</th>
							                </tr>
							            </thead>
							            <tbody>
							            </tbody>

							            <tfoot class="bg-primary-300">
								            <tr>
							                	<th>NRO</th>
												<th>COLEGIO</th>
							                    <th>SIE</th>
							                    <th>DIRECCION</th>
							                    <th>TELEFONO</th>
							                    <!-- <th>LOGO</th> -->
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
						&copy; 2021.<a href="#">Sistema de Control Académico "DON BOSCO"</a> by <a href="@donboscosucre.edu.bo" target="_blank">Departamento de Informatica</a>
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
	var tusurio;
	var save_method;

	$(document).ready(function(){

		//alert("hola");

		
		tcolegios=$('#table_colegios').DataTable({
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				"url":"<?php echo site_url('Config_colegios_contr/ajax_list');?>",
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
		

	});

	function cerrar()
	{
		//alert("hola");
		
		var url="<?php echo site_url('config_colegios_view/ajax_cerrar')?>";
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


	function add_usuario()
	{

		var temp="<?php echo site_url('assets/images/anonimo.jpg');?>";
		//alert(temp);
	    save_method = 'add';
	    $('#form')[0].reset(); // reset form on modals
	    $('.form-group').removeClass('has-error'); // clear error class
	    $('.help-block').empty(); // clear error string
	    $('#modal_form').modal('show'); // show bootstrap modal
	    $('.modal-title').text('ADICIONAR COLEGIOS'); // Set Title to Bootstrap modal title
	    $('#btnSave').text('Guardar');

	   // get_idcod();

	}

	//obtener codigo nuevo para el registro
	function get_idcod()
	{
		//envio de valores
		var data1={
				"table":"estudiante",
				"cod":"EST-",
		};
	
		$.ajax({
			
	        url : "<?php echo site_url('Config_colegios_contr/ajax_get_id');?>",
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
	function save_colegios()
	{
		//alert("guardar");
		
		var colegio,sie,direccion,telefono;
		var error=false;
		var url;

				
		if(save_method=='add')
		{
			url="<?php echo site_url('Config_colegios_contr/ajax_set_colegios');?>";
		}
		else{
			url="<?php echo site_url('Config_colegios_contr/ajax_update_colegios');?>";
		}
		sigla=document.getElementById('sigla').value;
		colegio=document.getElementById('colegio').value;
		sie=document.getElementById('sie').value;
		direccion=document.getElementById('direccion').value;
		telefono=document.getElementById('fono').value;
		dependencia=document.getElementById('dependencia').value;
		if (!error)
		{
			var data1={
				"sigla":colegio,
				"colegio":colegio,
				"sie":sie,
				"direccion":direccion,
				"telefono":telefono,
				"dependencia":dependencia
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

	function delete_coles(id)
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
		            url : "<?php echo site_url('Config_colegios_contr/ajax_delete')?>/"+id,
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

	function edit_coles(id)
	{
		save_method='update';
		$('#btnSave').text('Modificar');
		var img="";

		$.ajax({
			url:"<?php  echo site_url('Config_colegios_contr/ajax_edit_coles'); ?>/"+id,
			type:"GET",
			dataType:"JSON",
			success: function(data)
			{
				$('[name="sigla"]').val(data.sigla);
				$('[name="colegio"]').val(data.nombre);
				$('[name="sie"]').val(data.sie);
				$('[name="dependencia"]').val(data.dependencia);
				$('[name="direccion"]').val(data.direccion);
				$('[name="fono"]').val(data.telefono);
				// document.getElementById('loadimg').src=data.logo;

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
		tcolegios.ajax.reload(null,false);

	}

	

</script>


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary-300">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Formulario de Registro de Colegios</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">

                        <div class="form-group">
                            <label class="control-label col-md-3">SIGLA</label>
                            <div class="col-md-9">
                                <input type="text" name="sigla" placeholder="sigla" class="form-control" id="sigla">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    	<div class="form-group">
                            <label class="control-label col-md-3">COLEGIO</label>
                            <div class="col-md-9">
                                <input name="colegio" placeholder="nombre" class="form-control" type="text" id="colegio">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">SIE</label>
                            <div class="col-md-9">
                                <input name="sie" placeholder="SIE" class="form-control" type="text" id="sie">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">DEPENDENCIA</label>
                            <div class="col-md-9">
                                <input name="dependencia" placeholder="dependencia" class="form-control" type="text" id="dependencia">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">DIRECCION</label>
                            <div class="col-md-9">
                                <input name="direccion" placeholder="direccion" class="form-control" type="text" id="direccion">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">TELEFONO</label>
                            <div class="col-md-9">
                                <input type="text" name="fono" placeholder="fono" class="form-control" id="fono">
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
                </form>
            </div>
            <div class="modal-footer bg-primary-300">
            	<br>
                <button type="button" id="btnSave" onclick="save_colegios()" class="btn bg-info-700">Save</button>
                <button type="button" class="btn bg-danger-300" data-dismiss="modal">Cancel</button>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
