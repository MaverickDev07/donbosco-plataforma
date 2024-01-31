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
	<div class="navbar bg-green-300 ">
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
								<div class="panel-heading bg-green-300">
									<h6 class="panel-title">Clase Vitual</h6>
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
							        <br />
							        <br />
							         <table id="table_mat" class="table datatable-select-basic table-bordered " cellspacing="0" width="100%">
	
							            <thead class="bg-green-300">
							                <tr>
							                    <th>NRO</th>
							                    <th>MATERIA</th>
							                    <th>PROFESOR</th>
							                    <th>DIA</th>
							                    <th>HORA ENTRADA</th>
							                    <th>HORA SALIDA</th>
							                    <th>ACTION</th>
							                </tr>
							            </thead>
							            <tbody>
							            </tbody>

							            <tfoot class="bg-green-300">
								             <tr>
							                    <th>NRO</th>
							                    <th>MATERIA</th>
							                    <th>PROFESOR</th>
							                    <th>DIA</th>
							                    <th>HORA ENTRADA</th>
							                    <th>HORA SALIDA</th>
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
		tcolegios=$('#table_mat').DataTable({
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				"url":"<?php echo site_url('Estudiantes_sac_contr/ajax_list');?>",
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
		var url="<?php echo site_url('Estudiantes_sac_contr/ajax_cerrar')?>";
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
	function Get_estu()
	{
		//alert();
		var data1={
				"table":"curso",
				"lvl":"",
		};
		$.ajax({

	        url : "<?php echo site_url('Estudiantes_sac_contr/Get_estu');?>",
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

	        url : "<?php echo site_url('Estudiantes_sac_contr/ajax_materias');?>",
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
	
</script>


