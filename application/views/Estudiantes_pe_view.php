<?php
	$id_est = $this->session->get_userdata()['id'];
	$course = $this->session->get_userdata()['course'];
	$gestion = $this->session->get_userdata()['gestion'];
	$nivel = $this->session->get_userdata()['nivel'];
	$value = $course.'-'.$nivel.'-'.$gestion.'-'.$id_est;
	$kardex_url = base_url().'Karde_contr/printKar_studien/'.$value;
?>

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

								
								<li class="navigation-header"><span>TAREAS</span> <i class="icon-menu" title="Usuarios"></i></li>
								<li>
									<a href='<?php echo site_url('Estudiantes_su_contr');?>'><i class="icon-user-lock"></i> <span>Subir Tareas</span></a>									
								</li>
								<li>
									<a href='<?php echo site_url('Estudiantes_de_contr');?>'><i class="icon-user-lock"></i> <span>Descargar Tareas y Temas</span></a>									
								</li>
								<li>
									<a href='<?php echo site_url('Estudiantes_pe_contr');?>'><i class="icon-user-lock"></i> <span>Foro</span></a>									
								</li>
								<li class="navigation-header"><span>CLASE VIRTUALES</span> <i class="icon-menu" title="Usuarios"></i></li>
								<!-- <li>
									<a href='<?php echo site_url('Estudiantes_ca_contr');?>'><i class="icon-user-lock"></i> <span>Calendario Virtual</span></a>									
								</li> -->
								<li>
									<a href='<?php echo site_url('Estudiantes_sac_contr');?>'><i class="icon-user-lock"></i> <span>Olimpiadas 2da Fase</span></a>									
								</li>
								<li>
									<a href='<?php echo site_url('Estudiantes_link_contr');?>'><i class="icon-user-lock"></i> <span>Clases</span></a>									
								</li>
								<li>
									<a href='<?php echo site_url('Estudiantes_nota_contr');?>'><i class="icon-user-lock"></i> <span>Notas</span></a>									
								</li>
								
								<li class="navigation-header"><span>Kardex</span> <i class="icon-menu" title="Usuarios"></i></li>
								<li>
									<a href='<?php echo $kardex_url;?>' target='_blank'><i class="icon-user-lock"></i> <span>Kardex</span></a>									
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
								<li><a href=""><i class="icon-home2 position-left"></i> Principal</a></li>
								
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
									<h6 class="panel-title">Preguntas</h6>
									<div class="heading-elements">
										<ul class="icons-list">
											<li><a data-action="reload"></a></li>
					                	</ul>
				                	</div>
								</div>

								<div class="panel-body">
									<div class="col-lg-12">
										<div class="col-sm-5">
					            			<div class="form-group">
					                            <label class="control-label">ESTUDIANTE:</label>
					                                 <input name="Festud" placeholder="" class="form-control"  id="Festud" readonly="true">
					                                <span class="help-block"></span>
					                        </div>
					                    </div>
					                    <input name="id_est" placeholder="" class="form-control"  id="id_est" readonly="true">
					                              
							        <div class="col-sm-2">
		                    			<div class="form-group">
		                    				<label class="control-label">GESTION:</label>						                    
								<input class="form-control" type="text" name="Fgestion"  id="Fgestion" readonly="true">		                                
			                                <span class="help-block"></span>
			                            </div>			                            
					                </div>
					                <div class="col-sm-3">
					                	<div class="form-group">
		                    				<label class="control-label">NIVEL:</label>
				                                <input class="form-control" type="text" name="Fnivel"  id="Fnivel" readonly="true">
				                                <span class="help-block"></span>				                           
			                            </div>
					                </div>
					                <div class="col-sm-2">
					                	<div class="form-group">
				                            <label class="control-label">CURSO:</label>
				                                <input   name="Fcurso" class="form-control" id="Fcurso" readonly="true">
				                                <span class="help-block"></span>
				                        </div>
					                </div>
					                <div class="col-sm-3">
					                	<div class="form-group">
				                            <label class="control-label">MATERIA:</label>
				                                <select  name="materia" class="form-control" id="materia" onchange="gesprof(this.value)">
				                                </select>
				                                <span class="help-block"></span>
				                        	</div>
							</div>
					                <div class="col-sm-3">
					                	<div class="form-group">
				                            <label class="control-label">PROFESOR:</label>
				                                <select  name="prof" class="form-control" id="prof" onchange="gestemas(this.value)">
				                                </select>
				                                <span class="help-block"></span>
				                        	</div>
							</div>
					                <div class="col-sm-3">
					                	<div class="form-group">
				                            <label class="control-label">TEMAS:</label>
				                              <select  name="tema" class="form-control" id="tema" onchange="get_preguntas()">
				                                	</select>
				                        </div>
					                </div>  
							<div class="col-sm-12">
		                    			<div class="form-group">
		                    				<label class="control-label">PREGUNTA:</label>						                    
								<!--  <input class="form-control" type="text" name="pregunta"  id="pregunta">	-->	                                <textarea class="form-control" name="pregunta"  id="pregunta"rows="1" cols="100"></textarea>
			                                <span class="help-block"></span>
			                            </div>			                            
					                </div>  
					                <div class="col-sm-2">
					                	<div class="form-group">	
				                             <button type="button" onclick="pregunta()" class="btn bg-green-700">Guardar</button>
				                        </div>
					                </div>      
							        <br />
							        <br />
							         <table id="table_mat" class="table datatable-select-basic table-bordered " cellspacing="0" width="100%">
	
							            <thead class="bg-primary-300">
							                <tr>
							                    <th>NRO</th>
							                    <th>MATERIA</th>
							                    <th>TEMA</th>
							                    <th>PREGUNTA</th>
							                    <th>ACTION</th>
							                </tr>
							            </thead>
							            <tbody>
							            </tbody>

							            <tfoot class="bg-primary-300">
								             <tr>
							                    <th>NRO</th>
							                    <th>MATERIA</th>
							                    <th>TEMA</th>
							                    <th>PREGUNTA</th>
							                    <th>ACTION</th>
							                </tr>
							            </tfoot>
							        </table>
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

		</div>
		<!-- /page content -->

	</div>

		<div class="footer text-muted">
						&copy; 2021 .<a href="#">Sistema de Control Académico "DON BOSCO"</a> by <a href="donboscosucre.edu.bo" target="_blank">Departamento de Informatica </a>
					</div>
	<!-- /page container -->
</body>

<script type="text/javascript">
	var tmateria;
	var save_method;
	var _global_idcur="";
	
	$(document).ready(function(){
		Get_estu();
		materias();
		document.getElementById('id_est').style.display = 'none';	
	});

	function generar()
	{
		var id=document.getElementById('Fgestion').value+"-"+document.getElementById('Fnivel').value+"-"+document.getElementById('Fcurso').value;
		
	    var url = "<?php  echo site_url('Estudiantes_pe_contr/generar');?>/"+id;

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

	function temas(cat)
	{	
	     if(cat=='T'){
		document.getElementById('tem').style.display = 'none';	
		document.getElementById('temas').style.display = 'block';
		var data1={
				"table":"tema",
				"nivel":document.getElementById('Fnivel').value,
				"curso":document.getElementById('Fcurso').value,
				"materia":document.getElementById('materia').value,
				"id_prof":document.getElementById('Fidprofe').value,
				"gestion":document.getElementById('Fgestion').value,
			};
			
			$.ajax({
				
		        url : "<?php echo site_url('Estudiantes_pe_contr/temas');?>",
		        type: "POST",
		        data:data1,
		        dataType: "JSON",
		        success: function(data)//cargado de datos del registro 
		        {   
		           if(data.status)
	           		{
	           			i=0;
	           			//alert("asas");
					options="";
	           			data.data.forEach(function(item){
	           			
	           				options=options+"<option value='"+data.data[i]+"'>"+data.data1[i]+"</option>";	 
	           				i++;          			
	           			});
					
	           			document.getElementById('ttema').innerHTML=options;      	
	           		}
		        },
		        error: function (jqXHR, textStatus, errorThrown)
		        {
		           // alert('No puede obtener codigo nuevo, para el registro');
		        }
	    	});
		
	    }else{
			document.getElementById('tem').style.display = 'block';	
			document.getElementById('temas').style.display = 'none';

		}
		
	}
	function cerrar()
	{		
		var url="<?php echo site_url('Estudiantes_pe_contr/ajax_cerrar')?>";
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
    	var enlace="<?php echo  base_url();?>LoginE";
	    window.location.replace(enlace);
    	
	}

	function Get_estu()
	{
		//alert();
		var data1={
				"table":"curso",
				"lvl":"",
		};
		$.ajax({

	        url : "<?php echo site_url('Estudiantes_pe_contr/Get_estu');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
			
	           if(data.status)
	           {
				//alert(data.data[2]);
				
	           		document.getElementById('Festud').value=data.data[0];    
	           		document.getElementById('id_est').value=data.data[1];   
	           		document.getElementById('Fgestion').value=data.data[2];   
	           		document.getElementById('Fnivel').value=data.data[3];   
	           		document.getElementById('Fcurso').value=data.data[4];             	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}
	function gestemas(id_prof)
	{
		var options="<option value=''></option>";
		
		var data1={
				"id_mat":document.getElementById('materia').value,
				"id_prof":id_prof,
		};

		//alert(document.getElementById('materia').value);
		$.ajax({

	        url : "<?php echo site_url('Estudiantes_pe_contr/ajax_temas');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   var i=-1;
	           if(data.status)
	           {
	           		data.data.forEach(function(item){	
	           		i++;           			
	           			options=options+"<option value='"+data.data1[i]+"'>"+item+"</option>";	           			
	           		});
	           		document.getElementById('tema').innerHTML=options;            	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}
	function gesprof(id_mat)
	{
		var options="<option value=''></option>";

		var data1={
				"id_mat":id_mat,
		};

		// alert(id);
		$.ajax({

	        url : "<?php echo site_url('Estudiantes_pe_contr/ajax_profesor');?>",
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
	           		document.getElementById('prof').innerHTML=options;            	
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

	        url : "<?php echo site_url('Estudiantes_pe_contr/ajax_materias');?>",
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
		var nivel=document.getElementById('Fnivel').value;
		var data1={
				"table":"curso",
				"curso":id,
				"nivel":nivel,
		};
		// alert(id);
		$.ajax({

	        url : "<?php echo site_url('Estudiantes_pe_contr/ajax_get_materia');?>",
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

	function pregunta()
	{
		//alert(document.getElementById('pregunta').value);
		var id_tema,id_est,pregunta,gestion;
		var error=false;
		var url;

		url="<?php echo site_url('Estudiantes_pe_contr/ajax_set_pregunta');?>";
		
		id_tema=document.getElementById('tema').value;
		id_est=document.getElementById('id_est').value;	
		//alert(id_est);
		gestion=document.getElementById('Fgestion').value;	
		pregunta=document.getElementById('pregunta').value;	
		
		if (!error)
		{
			var data1={
				"id_tema":id_tema,
				"id_est":id_est,
				"gestion":gestion,
				"pregunta":pregunta,		
			};

			$.ajax({				
		        url : url,
		        type: "POST",
		        data:data1,
		        dataType: "JSON",
		        success: function(data)//cargado de datos del registro 
		        {   
		           	alert("Guardo Con exito");
				document.getElementById('pregunta').value="";
				get_preguntas();
		        },
		        error: function (jqXHR, textStatus, errorThrown)
		        {

		        }
	    	});		    	
	    	
		}
		
	}

	function delete_pregunta(id)
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
		            url : "<?php echo site_url('Estudiantes_pe_contr/ajax_delete_pregunta')?>/"+id,
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
			
	        url : "<?php echo site_url('Estudiantes_pe_contr/ajax_get_area');?>",
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
	function get_preguntas()
	{
		var id_tema=document.getElementById('tema').value;
		var gestion=document.getElementById('Fgestion').value;
		var id=id_tema+"-"+gestion;
		
		url1="<?php echo site_url('Estudiantes_pe_contr/ajax_list_temas');?>/"+id;    
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
		url1="<?php echo site_url('Estudiantes_pe_contr/ajax_list_profesor');?>/"+id;    
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
