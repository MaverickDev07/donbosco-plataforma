<body>
	<!-- Main navbar -->
	<div class="navbar bg-info-300 ">
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
								<li class="navigation-header"><span>Usuarios</span> <i class="icon-menu" title="Usuarios"></i></li>
								<li>
									<a href="#"><i class="icon-users4"></i> <span>Crear Usuarios</span></a>								
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
								
								<li class="active">Crear Usuarios</li>
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
								<div class="panel-heading bg-info-300">
									<h6 class="panel-title">Crear Usuarios</h6>
									<div class="heading-elements">
										<ul class="icons-list">			                	<li><a data-action="reload"></a></li>
					                	</ul>
				                	</div>
								</div>

								<div class="panel-body">
									<button class="btn btn-info" onclick="add_usuario()"><i class="glyphicon glyphicon-plus"></i> Add Person</button>
							        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
							        <br />
							        <br />
							        <table id="table_usuario" class="table datatable-select-basic table-bordered " cellspacing="0" width="100%" id="table_usuario">
							            <thead class="bg-info-300">
							                <tr>
							                    <th>Nro</th>
							                    <th>USUARIO</th>
							                    <th>PATERNO</th>
							                    <th>MATERNO</th>
							                    <th>NOMBRE</th>
							                    <th>ROL</th>
							                    <th>ACTIVO</th>
							                    <th>FOTO</th>
							                    <th>ACTION</th>
							                </tr>
							            </thead>
							            <tbody>
							            </tbody>

							            <tfoot class="bg-info-300">
								            <tr>
								                <th>Nro</th>
							                    <th>USUARIO</th>
							                    <th>PATERNO</th>
							                    <th>MATERNO</th>
							                    <th>NOMBRE</th>
							                    <th>ROL</th>
							                    <th>ACTIVO</th>
							                    <th>FOTO</th>
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
						&copy; 2021.<a href="#">Sistema de Control Académico "DON BOSCO"</a> by <a href="onboscosucre.edu.bo" target="_blank">Departamento de Informatica </a>
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
		
		tusuario=$('#table_usuario').DataTable({
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				"url":"<?php echo site_url('Reg_usuarios_contr/ajax_list');?>",
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
		var url="<?php echo site_url('Reg_usuarios_contr/ajax_cerrar')?>";
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
	    document.getElementById('loadimg').src=temp;
	    $('#modal_form').modal('show'); // show bootstrap modal
	    $('.modal-title').text('ADICIONAR USUARIOS'); // Set Title to Bootstrap modal title
	    $('#btnSave').text('Guardar');

	    get_idcod();

	}

	//obtener codigo nuevo para el registro
	function get_idcod()
	{
		//envio de valores
		var data1={
				"table":"usuario",
				"cod":"US-",
		};
	
		$.ajax({
			
	        url : "<?php echo site_url('Reg_usuarios_contr/ajax_get_id');?>",
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
	function save_usuario()
	{
		//alert("guardar");
		var codigo,appaterno,apmaterno,nombre,usuario,clave;
		var activo=0;
		var error=false;
		var url;

		//alert(save_method);

		
		if(save_method=='add')
		{
			url="<?php echo site_url('Reg_usuarios_contr/ajax_set_usuario');?>";
		}
		else{
			url="<?php echo site_url('Reg_usuarios_contr/ajax_update_usuario');?>";
		}

		codigo=document.getElementById('newcod').value;
		if(document.getElementById('activo').checked)
			activo=document.getElementById('activo').value;
		else
			activo=document.getElementById('inactivo').value;
		//activo=document.getElementById('activo').value;

		if(!validacion('appat','Apellido Paterno'))		
			appat=document.getElementById('appat').value;
		else
			error=true;

		if(!validacion('apmat','Apellido Materno'))		
			apmat=document.getElementById('apmat').value;
		else
			error=true;

		if(!validacion('name','Nombre'))		
			nombre=document.getElementById('name').value;
		else
			error=true;

		if(!validacion('user','Usuario'))		
			usuario=document.getElementById('user').value;
		else
			error=true;

		if(!validacion('clave','Contraseña'))		
			clave=document.getElementById('clave').value;
		else
			error=true;

		if(!validacion('rol','ROL'))		
			rol=document.getElementById('rol').value;
		else
			error=true;

		if(!validacion('photo','Imagen'))		
		{
			var photo=document.getElementById('photo').value;
			var x=document.getElementById('txtar').value;
			
		}
		else
			error=true;
		


		if (!error)
		{
			//alert("entro");
			var data1={
				"codigo":codigo,
				"appat":appat,
				"apmat":apmat,
				"nombre":nombre,
				"usuario":usuario,
				"clave":clave,
				"rol":rol,
				"activo":activo,
				"foto":x
			};
			//alert(data1.nombre);
			
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

	function delete_usuario(id)
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
		            url : "<?php echo site_url('Reg_usuarios_contr/ajax_delete')?>/"+id,
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

	function edit_usuario(id)
	{
		save_method='update';
		$('#btnSave').text('Modificar');
		var img="";

		$.ajax({
			url:"<?php echo site_url('Reg_usuarios_contr/ajax_edit_usuario'); ?>/"+id,
			type:"GET",
			dataType:"JSON",
			success: function(data)
			{
				$('[name="codigo"]').val(data.idcod);
				$('[name="appat"]').val(data.appat);
				$('[name="apmat"]').val(data.apmat);
				$('[name="nombre"]').val(data.nombre);
				$('[name="usuario"]').val(data.usuario);
				$('[name="clave"]').val(data.clave);
				$('[name="rol"]').val(data.rol);
				if(data.activo==1)
				{	
					document.getElementById('activo').checked=true;
				}
				if(data.activo==0)
				{				
					document.getElementById('inactivo').checked=true;
				}
				//img="<img src='"+data.foto+"'' width='100' height='120'>";
				//document.getElementById('loadfoto').innerHTML=img;
				document.getElementById('loadimg').src=data.foto;
				//$('#photo').attr("src",data.foto);

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
		tusuario.ajax.reload(null,false);

	}

	

</script>


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info-300">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Formulario de Registro de Usuarios</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                    	<div class="form-group">
                            <label class="control-label col-md-3">CÓDIGO</label>
                            <div class="col-md-9">
                                <input name="codigo" placeholder="codigo" class="form-control" type="text" readonly="true" id="newcod">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">AP PATERNO</label>
                            <div class="col-md-9">
                                <input name="appat" placeholder="paterno" class="form-control" type="text" id="appat">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">AP MATERNO</label>
                            <div class="col-md-9">
                                <input name="apmat" placeholder="materno" class="form-control" type="text" id="apmat">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">NOMBRE</label>
                            <div class="col-md-9">
                                <input name="nombre" placeholder="Nombre completo" class="form-control" type="text" id="name">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">USUARIO</label>
                            <div class="col-md-9">
                                <input name="usuario" placeholder="Usuario" class="form-control" type="text" id="user">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">CONTRASEÑA</label>
                            <div class="col-md-9">
                                <input type="password" name="clave" placeholder="clave" class="form-control" id="clave">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">ROL</label>
                            <div class="col-md-9">
                                <select  name="rol" class="form-control" id="rol" onchange="">
                                	<option value=''></option>
                                	<option value='SECRETARIA'>SECRETARIA</option>
                                	<option value='KARDIXTA'>KARDIXTA</option>
                                	<option value='PROFESOR'>PROFESOR</option>
                                	<option value='INSCRIPTOR'>INSCRIPTOR</option>
                                	<option value='DIRECTOR'>DIRECTOR</option>
                                	<option value='ADMINISTRADOR'>ADMINISTRADOR</option>                                	
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">ACTIVO</label>
                            <div class="col-md-9">
                            	<div class="radio">
								  <label><input type="radio" id ="activo" name="ractivo" checked="true" value="1">Activo</label>
								</div>
								<div class="radio">
								  <label><input type="radio" id="inactivo" name="ractivo" value="0">Inactivo</label>
								</div>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                        	<label class="control-label col-md-3">Imagen</label>
                        	<div class="col-md-9" id="loadfoto">
                                <img src='' width='180' height='220' id="loadimg">
                                <br>
                                <br>
                                <label class="btn btn-primary btn-file">
   								 	Cargar Imagén
   								 	<input type="file" class="form-control " id="photo" onchange="openFile(this)">
								</label>
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
            <div class="modal-footer bg-info-300">
            	<br>
                <button type="button" id="btnSave" onclick="save_usuario()" class="btn bg-info-700">Save</button>
                <button type="button" class="btn bg-danger-300" data-dismiss="modal">Cancel</button>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
