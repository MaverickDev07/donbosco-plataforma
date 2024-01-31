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
								<li class="navigation-header"><span>Olimpiadas 2da Fase</span> <i class="icon-menu" title="Usuarios"></i></li>
								<li>
									<a href='<?php echo site_url('Prof_profesores_contr');?>'><i class="icon-users4"></i> <span>Registrar Examenes</span></a>								
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
								
								<li class="active">Exámen</li>
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
									<h6 class="panel-title">Registrar Exámen</h6>
									<div class="heading-elements">
										<ul class="icons-list">
											<li><a data-action="reload"></a></li>
					                	</ul>
				                	</div>
								</div>

								<div class="panel-body">
									<button class="btn bg-grey-300" onclick="add_exam()"><i class="glyphicon glyphicon-plus"></i> Add Examén</button>
							        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
							        <button class="btn  btn-danger pull-right" onclick="export_pdf()" ><i class="icon icon-file-pdf"></i> PDF</button>
				                    				
							        <br />
							        <br />
							        <table id="table_prof" class="table datatable-select-basic table-bordered " cellspacing="0" width="100%">
							            <thead class="bg-grey-300">
							                <tr>
							                    <th>ID EXAM</th>							                    
							                    <th>ID PROF</th>
							                    <th>EXAM</th>
							                    <th>CREADOR</th>
							                    <th>FECHA</th>
							                    <th>BIMESTRE</th>
							                    <th>GESTION</th>							                    
							                    <th>ACTION</th>
							                </tr>
							            </thead>
							            <tbody>
							            </tbody>

							            <tfoot class="bg-grey-300">
								            <tr>
							                    <th>ID EXAM</th>							                    
							                    <th>ID PROF</th>
							                    <th>EXAM</th>
							                    <th>CREADOR</th>
							                    <th>FECHA</th>
							                    <th>BIMESTRE</th>
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
						&copy; 2021.<a href="#">Sistema de Control Académico "DON BOSCO"</a> by <a href="mailto:bitpixel@donboscosucre.edu.bo" target="_blank">bitPixel</a>
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
				"url":"<?php echo site_url('Prof_examen_contr/ajax_list');?>",
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
		
		deshabilitar();
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

	function add_exam()
	{
		//var temp="<?php //echo site_url('assets/images/anonimo.jpg');?>";
		//alert(temp);
	    save_method = 'add';
	    $('#form')[0].reset(); // reset form on modals
	    $('.form-group').removeClass('has-error'); // clear error class
	    $('.help-block').empty(); // clear error string
	   // document.getElementById('loadimg').src=temp;
	    $('#modal_form').modal('show'); // show bootstrap modal
	    $('.modal-title').text('HACER UN EXAMEN'); // Set Title to Bootstrap modal title
	    $('#btnSave').text('Guardar');

	    get_idcod();
	    get_prof();
	    get_gestion();

	}

	function get_prof()
	{
		
		var url="<?php echo site_url('Prof_examen_contr/ajax_usuario')?>";
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
	           		document.getElementById('idprof').value=data.data[0];	    //idprof       		
	           		document.getElementById('creador').value=data.data[1];		//profesor           		
	           }
	           else
	           {
	           		swal({
				            title: "Información",
				            text: "Usted no esta habilitado como Profesor!",
				            confirmButtonColor: "#2196F3",
				            type: "info"
				        });
	           }
	        }
    	});
    	
	}

	//obtener codigo nuevo para el registro
	function get_idcod()
	{
		//envio de valores
		var data1={
				"table":"examen",
				"cod":"EXAM-",
		};
	
		$.ajax({
			
	        url : "<?php echo site_url('Prof_examen_contr/ajax_get_id');?>",
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

	//idpreg
	function get_idpreg()
	{
		//envio de valores
		var data1={
				"table":"pregunta",
				"cod":"PREG-",
		};
	
		$.ajax({
			
	        url : "<?php echo site_url('Prof_examen_contr/ajax_get_idpreg');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		//datos recuperados
	           		document.getElementById('idpreg').value=data.codgen;
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
		//envio de valores
		var data1={
				"table":"remoto",
		};
	
		$.ajax({
			
	        url : "<?php echo site_url('Prof_examen_contr/ajax_get_gestion');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		//datos recuperados
	           		document.getElementById('gestion').value=data.data[0];
	           		document.getElementById('bimestre').value=data.data[1];

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
	function save_profe()
	{
		//alert("guardar");
		var idexam,titulo,idprof,creador,fecha,bimestre,gestion;
		var error=false;
		var url;

		//alert(save_method);

		
		if(save_method=='add')
		{
			url="<?php echo site_url('Prof_examen_contr/ajax_set_examen');?>";
		}
		else{
			url="<?php echo site_url('Prof_examen_contr/ajax_update_examen');?>";
		}

		idexam=document.getElementById('newcod').value;
		idprof=document.getElementById('idprof').value;		
		gestion=document.getElementById('gestion').value;
		
		//
		if(!validacion('bimestre','Bimestre'))		
			bimestre=document.getElementById('bimestre').value;
		else
			error=true;

		if(!validacion('titulo','Titulo del Examén'))		
			titulo=document.getElementById('titulo').value;
		else
			error=true;

		if(!validacion('fecha','Fecha de Creacion'))		
			fecha=document.getElementById('fecha').value;
		else
			error=true;

		if(!validacion('creador','El nombre del profesor'))		
			creador=document.getElementById('creador').value;
		else
			error=true;
		
		if (!error)
		{
			var data1={
				"idexam":idexam,
				"idprof":idprof,
				"examen":titulo,
				"creador":creador,
				"fecha":fecha,
				"bimestre":bimestre,
				"gestion":gestion
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
		            url : "<?php echo site_url('Prof_examen_contr/ajax_delete')?>/"+id,
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

		$.ajax({
			url:"<?php echo site_url('Prof_examen_contr/ajax_edit_examen'); ?>/"+id,
			type:"GET",
			dataType:"JSON",
			success: function(data)
			{

				$('[name="newcod"]').val(data.idexam);
				$('[name="idprof"]').val(data.idprof);
				$('[name="titulo"]').val(data.examen);
				$('[name="creador"]').val(data.creador);
				$('[name="fecha"]').val(data.fecha);
				$('[name="bimestre"]').val(data.bimestre);
				$('[name="gestion"]').val(data.gestion);

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

	function asignar_preguntas(id)
	{
		/*save_method='update';
		$('#btnSave').text('Modificar');

		$.ajax({
			url:"<?php //echo site_url('Prof_examen_contr/ajax_edit_examen'); ?>/"+id,
			type:"GET",
			dataType:"JSON",
			success: function(data)
			{

				$('[name="newcod"]').val(data.idexam);
				$('[name="idprof"]').val(data.idprof);
				$('[name="titulo"]').val(data.examen);
				$('[name="creador"]').val(data.creador);
				$('[name="fecha"]').val(data.fecha);
				$('[name="bimestre"]').val(data.bimestre);
				$('[name="gestion"]').val(data.gestion);

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
		});*/
		var temp="<?php echo site_url('assets/images/ecuacion.jpg');?>";
		document.getElementById('loadimg').src=temp;
		get_idpreg();
		$('[name="idexam"]').val(id);
		$('#modal_preguntas').modal('show');

	}
	
	function reload_table()
	{
		tprofe.ajax.reload(null,false);

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


	function habilitar()
	{
		if(document.getElementById('chk1').checked) 
				document.getElementById('resp1').disabled=true;
		else 
				document.getElementById('resp1').disabled=false;

		if(document.getElementById('chk2').checked) 
				document.getElementById('resp2').disabled=true;
		else 
				document.getElementById('resp2').disabled=false;

		if(document.getElementById('chk3').checked) 
				document.getElementById('resp3').disabled=true;
		else 
				document.getElementById('resp3').disabled=false;

		if(document.getElementById('chk4').checked) 
				document.getElementById('resp4').disabled=true;
		else 
				document.getElementById('resp4').disabled=false;

		if(document.getElementById('chk5').checked) 
				document.getElementById('resp5').disabled=true;
		else 
				document.getElementById('resp5').disabled=false;


	}

	function deshabilitar()
	{
		document.getElementById('resp1').disabled=true;
		document.getElementById('resp2').disabled=true;
		document.getElementById('resp3').disabled=true;
		document.getElementById('resp4').disabled=true;
		document.getElementById('resp5').disabled=true;
		document.getElementById('resp6').disabled=true;
	}
	

</script>


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-grey-300">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Formulario para crear Examenes</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                    	<div class="row">
                    		<div class="col-md-4">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">CÓDIGO:</label>
		                            <div class="col-md-8">
		                                <input name="newcod" class="form-control" type="text" readonly="true" id="newcod">
		                                <span class="help-block"></span>
		                            </div>		                    
		                        </div>
		                    </div>
		                    <div class="col-md-4">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">IDPROF:</label>
		                            <div class="col-md-8">
		                                <input name="idprof"  class="form-control" type="text" readonly="true" id="idprof">
		                                <span class="help-block"></span>
		                            </div>		                    
		                        </div>
		                    </div>
		                    <div class="col-md-4">
		                    	<div class="form-group">
		                            <label class="control-label col-md-4">GESTION</label>
		                            <div class="col-md-8">
		                                <input name="gestion"  class="form-control" type="text" id="gestion" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
                    			
		                    </div>
		                </div>
		                <div class="row">
		                    <div class="col-md-4">                    			
		                        <div class="form-group">
		                            <label class="control-label col-md-4">BIMESTRE</label>
		                            <div class="col-md-8">
		                                <select class="form-control" id="bimestre" name="bimestre">
		                                	  <option value=""></option>
											  <option value="1">1ER BIMESTRE</option>
											  <option value="2">2DO BIMESTRE</option>
											  <option value="3">3ER BIMESTRE</option>
											  <option value="4">4TO BIMESTRE</option>
										</select>
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="col-md-4">                    			
		                        <div class="form-group">
		                            <label class="control-label col-md-4">FECHA:</label>
		                            <div class="col-md-8">
		                                <input name="fecha"  class="form-control" type="date" id="fecha" value="<?php echo date("Y-m-d");?>">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="row">
		                    <div class="col-md-6">
		                        <div class="form-group">
		                            <label class="control-label col-md-3">TITULO:</label>
		                            <div class="col-md-9">
		                                <input name="titulo" class="form-control" type="text" id="titulo">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="col-md-6">
		                        <div class="form-group">
		                            <label class="control-label col-md-3">CREADOR:</label>
		                            <div class="col-md-9">
		                                <input type="text" name="creador" class="form-control" id="creador" readonly="true">
		                                <span class="help-block"></span>
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

/* ******************************************************************************* */

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_preguntas" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-grey-300">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Formulario para Preguntas y Respuestas</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-vertical">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">

                    	<div class="row">
							<div class="col-md-6">
								<fieldset>
									<legend ><i class="icon-reading position-left"></i> Preguntas</legend>
									<div class="col-md-6">
		                    			<div class="form-group">
				                            <label class="control-label col-md-4">ID PREG:</label>
				                            <div class="col-md-8">
				                                <input name="idpreg"  class="form-control" type="text" readonly="true" id="idpreg">
				                                <span class="help-block"></span>
				                            </div>		                    
				                        </div>
				                    </div>
		                    		<div class="col-md-6">
		                    			<div class="form-group">
				                            <label class="control-label col-md-4">ID EXAM:</label>
				                            <div class="col-md-8">
				                                <input name="idexam" class="form-control" type="text" readonly="true" id="idexam">
				                                <span class="help-block"></span>
				                            </div>		                    
				                        </div>
				                    </div>	

				                    <label class="control-label col-md-2">PREGUNTA</label>
		                            <div class="col-md-10">
		                                <textarea  rows="3" cols="100" name="pregunta" class="form-control" type="text" id="pregunta">
		                                </textarea>
		                                <span class="help-block"></span>
		                            </div>

		                             <div class="row">
					                    <div class="col-md-6">
					                         <div class="form-group">
					                         	<label class="control-label col-md-4">IMAGEN</label>
					                        	<div class="col-md-8" id="loadfoto">
					                                <img src='' width='330' height='200' id="loadimg">
					                                <br>
					                                <br>
					                                330 x 200
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

								</fieldset>
							</div>
					
							<div class="col-md-6">
								<fieldset>
				                	<legend class="text-semibold"><i class="icon-truck position-left"></i>RESPUESTAS</legend>								
									<div class="form-group">
										<div class="checkbox" id='chk1' onclick="habilitar()">
									      <label><input type="checkbox" value="">Respuesta 1</label>
									    </div>
									    <label class="control-label col-md-3">RESPUESTA:</label>
			                            <div class="col-md-9">
			                                <textarea rows="2" cols="40" id="resp1" class="form-control">
					                        </textarea>
			                                <span class="help-block"></span>
			                            </div>	
									</div>
									<div class="form-group">
										<div class="checkbox" id='chk2' onclick="habilitar()">
									      <label><input type="checkbox" value="">Respuesta 2</label>
									    </div>
									    <label class="control-label col-md-3">RESPUESTA:</label>
			                            <div class="col-md-9">
			                                <textarea rows="2" cols="40" id="resp2" class="form-control">
					                        </textarea>
			                                <span class="help-block"></span>
			                            </div>	
									</div>
									<div class="form-group">
										<div class="checkbox" id='chk3' onclick="habilitar()">
									      <label><input type="checkbox" value="">Respuesta 3</label>
									    </div>
									    <label class="control-label col-md-3">RESPUESTA:</label>
			                            <div class="col-md-9">
			                                <textarea rows="2" cols="40" id="resp3" class="form-control">
					                        </textarea>
			                                <span class="help-block"></span>
			                            </div>	
									</div>
									<div class="form-group" id='chk4' onclick="habilitar()">
										<div class="checkbox">
									      <label><input type="checkbox" value="">Respuesta 4</label>
									    </div>
									    <label class="control-label col-md-3">RESPUESTA:</label>
			                            <div class="col-md-9">
			                                <textarea rows="2" cols="40" id="resp4" class="form-control">
					                        </textarea>
			                                <span class="help-block"></span>
			                            </div>	
									</div>
									<div class="form-group">
										<div class="checkbox" id='chk5' onclick="habilitar()">
									      <label><input type="checkbox" value="">Respuesta 5</label>
									    </div>
									    <label class="control-label col-md-3">RESPUESTA:</label>
			                            <div class="col-md-9">
			                                <textarea rows="2" cols="40" id="resp5" class="form-control">
					                        </textarea>
			                                <span class="help-block"></span>
			                            </div>	
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Correcta:</label>
										<div class="custom-control custom-radio col-md-9">
											<label><input type="radio" name="optradio" id='radresp1'>1 Resp</label>
											<label><input type="radio" name="optradio" id='radresp2'>2 Resp</label>
											<label><input type="radio" name="optradio" id='radresp3'>3 Resp</label>
											<label><input type="radio" name="optradio" id='radresp4'>4 Resp</label>
											<label><input type="radio" name="optradio" id='radresp5'>5 Resp</label>
										</div>
									</div>									
								</fieldset>
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
