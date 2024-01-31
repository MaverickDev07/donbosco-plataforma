<body>
	<!-- Main navbar -->
	<div class="navbar bg-slate-700 ">
		<div class="navbar-header">
			<a class="navbar-brand text-white" href="">SISTEMA DE CONTROL ACADEMICO "DON BOSCO" <i class="icon-graduation"></i></a>

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
								
								<li class="navigation-header"><span>Reportes</span> <i class="icon-menu" title="Usuarios"></i></li>
								<li>
									<a href='<?php echo site_url('Rep_centralizador_2019_contr');?>'><i class="icon-user-lock"></i> <span>Centralizador</span></a>									
								</li>
								<li>
									<a href='<?php echo site_url('Rep_estadisticas_2019_contr');?>'><i class="icon-user-lock"></i> <span>Estadísticas</span></a>									
								</li>
								<li>
									<a href='<?php echo site_url('Rep_registro_contr');?>'><i class="icon-file-check"></i> <span>Acceso a Notas solo lectura</span></a>								
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
							<div class="panel panel-dange ">
								<div class="panel-heading bg-slate">
									<h6 class="panel-title">Gestionar Alumnos</h6>
									<div class="heading-elements">
										<ul class="icons-list">
										   	<li><a data-action="reload"></a></li>
					                	</ul>
				                	</div>
								</div>

								<div class="panel-body">
				                    	 <div class="row">
				                    	 	<div class="col-sm-2">
				                    			<div class="form-group">
				                    				<label class="control-label">Gestion</label>
				                    				<select  name="Fgestion" class="form-control" id="Fgestion">
						                            </select>
				                    			</div>
				                    		</div>
				                    		<div class="col-sm-2">
				                    			<div class="form-group">
					                    			<label class="control-label">Nivel:</label>
							                        <Select class="form-control" type="text" id="Fnivel" onchange="gescole(this.value);getcur(this.value)">
						                            </Select>
					                        	</div>
				                    		</div>
				                    		<div class="col-sm-3">
				                    			<div class="form-group">
				                    				<label class="control-label">Colegio</label>
				                    				<input name="Fcolegio" placeholder="" class="form-control"  id="Fcolegio" readonly="true">
				                    			</div>
				                    		</div>
				                    		<div class="col-sm-3">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">BIMESTRE:</label>
						                                <select  name="Fbimestre" class="form-control" id="Fbimestre">
						                                	<option value='1'>PRIMER BIMESTRE</option>
						                                	<option value='2'>SEGUNDO BIMESTRE</option>
						                                	<option value='3'>TERCER BIMESTRE</option>
						                                	<option value='4'>CUATRO BIMESTRE</option>
						                                </select>
						                                <span class="help-block"></span>
						                            
						                        </div>
						                    </div> 
						                    <div class="col-sm-2">
						                    	<div class="col-md-12">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">CANTIDAD:</label>
						                                <select  name="Fcantidad" class="form-control" id="Fcantidad">
						                                	<option value='2'>2</option>
						                                	<option value='3'>3</option>
						                                	<option value='6'>6</option>
						                                	<option value='8'>8</option>
						                                	<option value='10'>10</option>
						                                </select>
						                                <span class="help-block"></span>
						                            
						                        </div>
						                    </div>
						                    </div>
				                    		
				                    		
				                    	</div>  
				                    	<!-- <div class="row">  
				               
				                    			
				                    		<div class="col-sm-3">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">BIMESTRE:</label>
						                                <select  name="Fbimestre" class="form-control" id="Fbimestre">
						                                	<option value='1'>PRIMER BIMESTRE</option>
						                                	<option value='2'>SEGUNDO BIMESTRE</option>
						                                	<option value='3'>TERCER BIMESTRE</option>
						                                	<option value='4'>CUATRO BIMESTRE</option>
						                                </select>
						                                <span class="help-block"></span>
						                            
						                        </div>
						                    </div> 
						                    <div class="col-sm-3">
						                    	<div class="col-md-9">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">CANTIDAD:</label>
						                                <select  name="Fcantidad" class="form-control" id="Fcantidad">
						                                	<option value='3'>3</option>
						                                	<option value='6'>6</option>
						                                	<option value='8'>8</option>
						                                	<option value='10'>10</option>
						                                </select>
						                                <span class="help-block"></span>
						                            
						                        </div>
						                    </div>
						                    </div>
						                    </div> -->
						                    <div class="row"> 
				                    				<div class="col-md-12">
				                    			
				                    				<div class="bs-example" data-example-id="button-group-nesting"> 
					                    						<div class="btn-group" role="group"> 
					                    							<button type="button" class="btn btn-primary">BAJO RENDIMIENTO</button>
					                    							<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>

					                    							<ul class="dropdown-menu bg-primary-300 btn-float btn-float-lg" id="menu1" aria-labelledby="drop4"> 
					                    				  			<li><a  onclick="Bxcurso()">POR CURSO <i class="icon icon-file-excel"></i></a></li>  
					                    				  			<li role="separator" class="divider"></li>
					                    				  			<li><a  onclick="Bxcolegio()" >POR COLEGIO <i class="icon icon-file-excel"></i></a></li> 
					                    				  			<li role="separator" class="divider"></li> 
					                    				  			
					                    				  		</ul> 
					                    						</div> 
					                    						<div class="btn-group" role="group"> 
					                    							<button type="button" class="btn btn-success">MEJORES ESTU.</button>
					                    							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>

					                    							<ul class="dropdown-menu bg-success-300 btn-float btn-float-lg" id="menu1" aria-labelledby="drop4"> 
					                    				  			<li><a  onclick="Mxcurso()">POR CURSO <i class="icon icon-file-excel"></i></a></li>  
					                    				  			<li role="separator" class="divider"></li>
					                    				  			<li><a  onclick="Mxcolegio()" >POR COLEGIO <i class="icon icon-file-excel"></i></a></li> 
					                    				  			<li role="separator" class="divider"></li> 
					                    				  		</ul> 
					                    						</div> 
					                    						<div class="btn-group" role="group"> 
					                    							<button type="button" class="btn btn-warning">CUADRO DE HONOR</button>
					                    							<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>

					                    							<ul class="dropdown-menu bg-warning-300 btn-float btn-float-lg" id="menu1" aria-labelledby="drop4"> 
					                    				  			<li><a  onclick="Cxcurso()">POR CURSO <i class="icon icon-file-excel"></i></a></li>  
					                    				  			<li role="separator" class="divider"></li>
					                    				  			<li><a  onclick="Cxcolegio()" >POR COLEGIO <i class="icon icon-file-excel"></i></a></li> 
					                    				  			<li role="separator" class="divider"></li> 
					                    				  		</ul> 
					                    						</div> 
					                    						<div class="btn-group" role="group"> 
					                    							<button type="button" class="btn btn-info">ESTADISTICAS</button>
					                    							<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>

					                    							<ul class="dropdown-menu bg-info-300 btn-float btn-float-lg" id="menu1" aria-labelledby="drop4"> 
					                    				  			<li><a  onclick="Axcurso()">APROBECHAMIENTO <i class="icon icon-file-excel"></i></a></li> 
					                    				  			<li role="separator" class="divider"></li> 
					                    				  			<li><a  onclick="familia()">FAMILIAS <i class="icon icon-file-excel"></i></a></li> 
					                    				  			<li role="separator" class="divider"></li> 
					                    				  			<li><a  onclick="estadistica_a()">ESTADISTAS <i class="icon icon-file-excel"></i></a></li> 
					                    				  			<li role="separator" class="divider"></li> 
					                    				  		</ul> 
					                    						</div> 
				                    						</div>
				                    					</div> 
				                    				</div>

				                    		</div>

						           </div>
				                   <div class="row">  

						           </div>
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
	var testudiante;
	var save_method;
	var _global_idcur="";

	$(document).ready(function(){
		
		
		get_nivel();
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
	function Mxcurso()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso='d';
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		var cantidad=document.getElementById('Fcantidad').value;

		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_"+cantidad+"_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_estadisticas_2019_contr/Mxcurso');?>/"+valor;

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
	function Cxcurso()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso='d';
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		var cantidad=document.getElementById('Fcantidad').value;

		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_"+cantidad+"_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_estadisticas_2019_contr/Cxcurso');?>/"+valor;

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
	function Axcurso()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso='d';
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		var cantidad=document.getElementById('Fcantidad').value;

		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_"+cantidad+"_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_estadisticas_2019_contr/Axcurso');?>/"+valor;

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
	function familia()
	{
		var bimes=0;
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		var cantidad=document.getElementById('Fcantidad').value;

		var valor=bimes+"_"+nivel+"_"+gestion+"_"+nivel+"_"+cantidad+"_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_estadisticas_2019_contr/familia');?>/"+valor;

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
	function estadistica_a()
	{
		var bimes=document.getElementById('Fbimestre').value;;
		var curso='d';
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		var cantidad=0;

		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_"+cantidad+"_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_estadisticas_2019_contr/estadistica_a');?>/"+valor;

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
	function Bxcurso()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso='d';
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		var cantidad=document.getElementById('Fcantidad').value;

		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_"+cantidad+"_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_estadisticas_2019_contr/Bxcurso');?>/"+valor;

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
	function Bxcolegio()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso='d';
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		var cantidad=document.getElementById('Fcantidad').value;

		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_"+cantidad+"_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_estadisticas_2019_contr/Bxcolegio');?>/"+valor;

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
	function Mxcolegio()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso='d';
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		var cantidad=document.getElementById('Fcantidad').value;

		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_"+cantidad+"_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_estadisticas_2019_contr/Mxcolegio');?>/"+valor;

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
	function Cxcolegio()
	{
		var bimes=document.getElementById('Fbimestre').value;
		var curso='d';
		var gestion=document.getElementById('Fgestion').value;
		var nivel=document.getElementById('Fnivel').value;
		var cantidad=document.getElementById('Fcantidad').value;

		var valor=bimes+"_"+curso+"-"+nivel+"_"+gestion+"_"+nivel+"_"+curso+"_"+cantidad+"_";

		//alert(valor);

		
	    var url = "<?php echo site_url('Rep_estadisticas_2019_contr/Cxcolegio');?>/"+valor;

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
	//SELECT nivel
	function get_nivel()
	{
		var options="<option value=''></option>";
		//envio de valores
		var data1={
				"table":"nivel",
		};
		
		$.ajax({
			
	        url : "<?php echo site_url('Rep_estadisticas_2019_contr/ajax_get_nivel');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	 var i=0;    		
	           		data.data.forEach(function(item){
	           			
	           			options=options+"<option value='"+data.data1[i]+"'>"+item+"</option>";	
	           			i++;           			
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
	function get_gestion()
	{
		var options="";
		//envio de valores
		var data1={
				"table":"gestion",
		};
		
		$.ajax({
			
	        url : "<?php echo site_url('Rep_centralizador_contr/ajax_get_gestion');?>",
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
			
	        url : "<?php echo site_url('Rep_estadisticas_2019_contr/ajax_get_level');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   var fecha = new Date();
			var ano = fecha.getFullYear();
	           if(data.status)
	           {
	           		//datos recuperados
	           		//document.getElementById('Fgestion').value=ano;
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
			
	        url : "<?php echo site_url('Rep_estadisticas_2019_contr/ajax_get_curso');?>",
	        type: "POST",
	        data:datacur,
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
	           		document.getElementById('Fcurso').innerHTML=options;           	
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
		
	}

	function consultar()
	{		
		var tipo='';
		if (document.getElementById('est1').checked)
		{
			tipo="promovidos";
			$('#modal_form').modal('show');
		}
		if (document.getElementById('est2').checked)
			tipo="aprovechamiento";
		if (document.getElementById('est3').checked)
			tipo="curricular";
		
		
	}

	function listprom(v)
	{	

		
		if(document.getElementById('t1').checked)
		{
			var bi=document.getElementById('bimestre').value;
			var gestion=document.getElementById('Fgestion').value;

			var data1={
				"bimes":bi,
				"nivel":v,
				"gestion":gestion
			}

			url="<?php echo site_url('Rep_estadisticas_2019_contr/ajax_list_promovidos');?>";


			tpromovidos=$('#table_promovidos').DataTable({
							"destroy": true,
							"serverSide":true,
							"order":[],
							"processing":true,
							"ajax":{
								"url":url,	
								"data":data1,					
								"type":"POST",
							},														
							
							"columnDefs":[
							{
								"targets":[-1],
								"orderable":false,
							},
							],
							"scrollX":true
							
						}); 
		}
		
		
	}



	/*
	//ventana para el pdf
	function export_pdf()
	{
		var valor=_global_idcur;
		//alert(valor);
		
	    var url = "<?php  //echo site_url('Rep_estadisticas_2019_contr/print');?>/"+valor;

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
	*/

	/*
	function export_xls()
	{
		var valor=_global_idcur;
				
	    var url = "<?php  //echo site_url('Rep_estadisticas_2019_contr/printxls');?>/"+valor;

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


<!-- Bootstrap modal
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-slate-300">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Formulario de Revisión de Alumnos</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">                    
                    <div class="form-body">
    					<div class="row">
                    		<div class="col-sm-4">
                    			<div class="form-group">
	                    			<label class="control-label">Nivel:</label>
			                        <Select class="form-control" type="text" id="Fnivel" onchange="gescole(this.value);getcur(this.value);listprom(this.value);">
		                            </Select>
	                        	</div>
                    		</div>
                    		<div class="col-sm-4">
                    			<div class="form-group">
                    				<label class="control-label">Colegio</label>
                    				<input name="Fcolegio" placeholder="" class="form-control"  id="Fcolegio" readonly="true">
                    			</div>
                    		</div>
                    		<div class="col-sm-4">
                    			<div class="form-group">
                    				<label class="control-label">Gestion</label>
                    				<input name="Fgestion" placeholder="" class="form-control" type="text" id="Fgestion" readonly="true">
                    			</div>
                    		</div>
                    		
                    	</div>	

                    	<div class="tabbable">
							<ul class="nav nav-tabs bg-slate-300 nav-tabs-component nav-justified">
								<li class="active"><a href="#colored-rounded-justified-tab1" data-toggle="tab">PROMOVIDOS Y REPROBADOS</a></li>
								<li><a href="#colored-rounded-justified-tab2" data-toggle="tab">GRAFICO</a></li>	
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="colored-rounded-justified-tab1">
									<table id="table_promovidos" class="table datatable-responsive" cellspacing="0" width="100%">
							            <thead class="bg-slate-300">
							                <tr>
							                	<th rowspan="2">CURSO</th>
							                    <th colspan="4">PROMOVIDOS</th>
							                    <th colspan="4">REPROBADOS</th>
							                </tr>
							                <tr>

							                	<th>V</th>
							                	<th>M</th>
							                	<th>T</th>
							                	<th>%</th>							         
							                	<th>V</th>
							                	<th>M</th>
							                	<th>T</th>
							                	<th>%</th>
							                </tr>
							            </thead>
							            <tbody>
							            </tbody>

							            <tfoot class="bg-slate-300">
								            <tr>
								            	<th>CURSO</th>
							                	<th>V</th>
							                	<th>M</th>
							                	<th>T</th>
							                	<th>%</th>
							                	<th>V</th>
							                	<th>M</th>
							                	<th>T</th>
							                	<th>%</th>
							                </tr>
							            </tfoot>
							        </table>
								</div>
								<div class="tab-pane" id="colored-rounded-justified-tab2">

								</div>
							</div>                     
                    	</div>
                </form>
            </div>
            <div class="modal-footer bg-slate-300">
            	<br>
                <button type="button" id="btnSave" onclick="save_estud()" class="btn bg-slate-700">Guardar</button>
                <button type="button" class="btn bg-danger-300" data-dismiss="modal">Cancelar</button>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div> /.modal -->
<!-- End Bootstrap modal -->
