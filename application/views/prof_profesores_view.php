<body>
	<!-- Main navbar -->
	<div class="navbar bg-grey ">
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
								<li class="navigation-header"><span>Profesores</span> <i class="icon-menu" title="Usuarios"></i></li>
								<li>
									<a href='<?php echo site_url('Prof_profesores_contr');?>'><i class="icon-users4"></i> <span>Registrar Profesores</span></a>								
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
								
								<li class="active">Profesores</li>
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
								<div class="panel-heading bg-grey-300">
									<h6 class="panel-title">Registrar Profesor</h6>
									<div class="heading-elements">
										<ul class="icons-list">
											<li><a data-action="reload"></a></li>
					                	</ul>
				                	</div>
								</div>

								<div class="panel-body">
									<button class="btn bg-grey-300" onclick="add_profe()"><i class="glyphicon glyphicon-plus"></i> Add Profesor</button>
							        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
							        <button class="btn  btn-danger pull-right" onclick="export_pdf()" ><i class="icon icon-file-pdf"></i> PDF</button>
				                    				
							        <br />
							        <br />
							        <table id="table_prof" class="table datatable-select-basic table-bordered " cellspacing="0" width="100%">
							            <thead class="bg-grey-300">
							                <tr>
							                    <th>ID</th>
							                    <th>ITEM</th>
							                    <th>CI</th>
							                    <th>PATERNO</th>
							                    <th>MATERNO</th>
							                    <th>NOMBRES</th>
							                    <th>DIRECCION</th>
							                    <th>TELEFONO</th>
							                    <th>GENERO</th>
							                    <th>FOTO</th>
							                    <th>ACTION</th>
							                </tr>
							            </thead>
							            <tbody>
							            </tbody>

							            <tfoot class="bg-grey-300">
								            <tr>
								                <th>ID</th>
							                    <th>ITEM</th>
							                    <th>CI</th>
							                    <th>PATERNO</th>
							                    <th>MATERNO</th>
							                    <th>NOMBRES</th>
							                    <th>DIRECCION</th>
							                    <th>TELEFONO</th>
							                    <th>GENERO</th>
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
	var tprofe;
	var save_method;

	$(document).ready(function(){
		
		tprofe=$('#table_prof').DataTable({
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				"url":"<?php echo site_url('Prof_profesores_contr/ajax_list');?>",
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
		var url="<?php echo site_url('Prof_profesores_contr/ajax_cerrar')?>";
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

	function add_profe()
	{
		var temp="<?php echo site_url('assets/images/anonimo.jpg');?>";
		//alert(temp);
	    save_method = 'add';
	    $('#form')[0].reset(); // reset form on modals
	    $('.form-group').removeClass('has-error'); // clear error class
	    $('.help-block').empty(); // clear error string
	    document.getElementById('loadimg').src=temp;
	    $('#modal_form').modal('show'); // show bootstrap modal
	    $('.modal-title').text('ADICIONAR PROFESORES'); // Set Title to Bootstrap modal title
	    $('#btnSave').text('Guardar');

	    get_idcod();

	}

	//obtener codigo nuevo para el registro
	function get_idcod()
	{
		//envio de valores
		var data1={
				"table":"profesor",
				"cod":"PROF-",
		};
	
		$.ajax({
			
	        url : "<?php echo site_url('Prof_profesores_contr/ajax_get_id');?>",
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
	function save_profe()
	{
		//alert("guardar");
		var codigo,item,ci,appat,apmat,nombre,direc,fono,genero;
		var error=false;
		var url;

		//alert(save_method);

		
		if(save_method=='add')
		{
			url="<?php echo site_url('Prof_profesores_contr/ajax_set_profe');?>";
		}
		else{
			url="<?php echo site_url('Prof_profesores_contr/ajax_update_profe');?>";
		}

		codigo=document.getElementById('newcod').value;
		
		if(!validacion('item','Item'))		
			item=document.getElementById('item').value;
		else
			error=true;

		if(!validacion('ci','Carnet de Identidad'))		
			ci=document.getElementById('ci').value;
		else
			error=true;

		if(!validacion('appaterno','Apellido Paterno'))		
			appat=document.getElementById('appaterno').value;
		else
			error=true;

		if(!validacion('apmaterno','Apellido Materno'))		
			apmat=document.getElementById('apmaterno').value;
		else
			error=true;

		if(!validacion('nombres','Nombres'))		
			nombre=document.getElementById('nombres').value;
		else
			error=true;

		if(!validacion('direc','Dirección'))		
			direc=document.getElementById('direc').value;
		else
			error=true;

		if(!validacion('fono','Telefono'))		
			fono=document.getElementById('fono').value;
		else
			error=true;

		if(!validacion('genero','Genero'))		
			genero=document.getElementById('genero').value;
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

			var data1={
				"codigo":codigo,
				"item":item,
				"ci":ci,
				"appat":appat,
				"apmat":apmat,
				"nombre":nombre,
				"direc":direc,
				"fono":fono,
				"genero":genero,
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

	function delete_profesor(id)
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
		            url : "<?php echo site_url('Prof_profesores_contr/ajax_delete')?>/"+id,
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
		
		save_method='update';
		$('#btnSave').text('Modificar');
		var img="";

		$.ajax({
			url:"<?php echo site_url('Prof_profesores_contr/ajax_edit_profesor'); ?>/"+id,
			type:"GET",
			dataType:"JSON",
			success: function(data)
			{

				$('[name="codigo"]').val(data.idprof);
				$('[name="item"]').val(data.item);
				$('[name="ci"]').val(data.ci);
				$('[name="appaterno"]').val(data.appaterno);
				$('[name="apmaterno"]').val(data.apmaterno);
				$('[name="nombres"]').val(data.nombres);
				$('[name="direc"]').val(data.direccion);
				$('[name="fono"]').val(data.telefono);
				$('[name="genero"]').val(data.genero);				
				document.getElementById('loadimg').src=data.foto;
				

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
		tprofe.ajax.reload(null,false);

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
	

</script>


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-grey-300">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Formulario de Registro de Profesores</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                    	<div class="row">
                    		<div class="col-md-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">CÓDIGO</label>
		                            <div class="col-md-8">
		                                <input name="codigo" placeholder="codigo" class="form-control" type="text" readonly="true" id="newcod">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-md-4">PATERNO</label>
		                            <div class="col-md-8">
		                                <input name="appaterno" placeholder="apellido" class="form-control" type="text" id="appaterno">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-md-4">DIRECCION</label>
		                            <div class="col-md-8">
		                                <input type="text" name="direc" placeholder="direccion" class="form-control" id="direc">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">ITEM</label>
		                            <div class="col-md-8">
		                                <input name="item" placeholder="item" class="form-control" type="text" id="item">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-md-4">MATERNO</label>
		                            <div class="col-md-8">
		                                <input name="apmaterno" placeholder="apellido" class="form-control" type="text" id="apmaterno">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-md-4">TELEFONO</label>
		                            <div class="col-md-8">
		                                <input type="TEXT" name="fono" placeholder="celular" class="form-control" id="fono">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">CI</label>
		                            <div class="col-md-8">
		                                <input name="ci" placeholder="ci" class="form-control" type="text" id="ci">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-md-4">NOMBRES</label>
		                            <div class="col-md-8">
		                                <input type="text" name="nombres" placeholder="nombres" class="form-control" id="nombres">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-md-4">GENERO</label>
		                            <div class="col-md-8">
		                                <select class="form-control" id="genero" name="genero">
											  <option value="M">MASCULINO</option>
											  <option value="F">FEMENINO</option>
										</select>
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="col-md-3">
		                        <div class="form-group">
		                        	<div class="col-md-8" id="loadfoto">
		                                <img src='' width='160' height='200' id="loadimg">
		                                <br>
		                                <br>
		                                <label class="btn bg-grey-400 btn-file">
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
                    	</div>
                      
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-grey-300">
            	<br>
                <button type="button" id="btnSave" onclick="save_profe()" class="btn bg-grey-600">Save</button>
                <button type="button" class="btn bg-danger-300" data-dismiss="modal">Cancel</button>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
