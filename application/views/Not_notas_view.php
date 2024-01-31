<body>
	<!-- Main navbar -->
	<div class="navbar bg-brown-700 ">
		<div class="navbar-header">  
			<a class="navbar-brand text-white" href="<?php echo base_url();?>Principal">SISTEMA DE CONTROL ACADEMICO "DON BOSCO" <i class="icon-graduation"></i></a>

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
									<a href='<?php echo site_url('Not_notas_contr');?>'><i class="icon-user-lock"></i> <span>Notas</span></a>									
								</li>
								<li>
									<a href='<?php echo site_url('Not_notas_subir_contr');?>'><i class="icon-user-lock"></i> <span>Subir Notas</span></a>									
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
								<li><a href='<?php echo site_url('Principal');?>'><i class="icon-home2 position-left"></i> Principal</a></li>
								
								<li class="active">Notas</li>
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
								<div class="panel-heading bg-brown">
									<h6 class="panel-title">Gestionar Calificaciones</h6>
									<div class="heading-elements">
										<ul class="icons-list">
										   	<li><a data-action="reload"></a></li>
					                	</ul>
				                	</div>
								</div>

								<div class="panel-body">
										<div class="row">
											<div class="col-sm-6">
				                    			<div class="form-group">
						                            <label class="control-label col-md-2">PROFESOR:</label>
						                            <div class="col-md-10">
						                                 <input name="Fprofe" placeholder="" class="form-control"  id="Fprofe" readonly="true">
						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div>
						                    <div class="col-sm-3">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">ID PROF:</label>
						                            <div class="col-md-9">
						                                 <input name="Fidprofe" placeholder="" class="form-control"  id="Fidprofe" readonly="true">
						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div>
						                    <div class="col-sm-3">
						                    	<!--<button class="btn  btn-danger" onclick="export_pdf()" ><i class="icon icon-file-pdf"></i>  &nbsp;&nbsp;&nbsp;&nbsp;PDF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
				                    				&nbsp;&nbsp;
				                    			

				                    			 <button class="btn bg-brown-800" onclick="export_xls()" ><i class="icon icon-file-excel"></i>&nbsp;&nbsp;&nbsp;EXCEL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> -->
						                    </div>
										</div>
				                    	<!-- <div class="row">
				                    		<div class="col-sm-3">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">NIVEL:</label>
						                            <div class="col-md-9">
						                                <Select class="form-control" type="text" id="Fnivel" onchange="gescole(this.value);getcur(this.value)">
						                                </Select>
						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div>
						                    <div class="col-sm-3">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">COLEGIO:</label>
						                            <div class="col-md-9">
						                                <input name="Fcolegio" placeholder="" class="form-control"  id="Fcolegio" readonly="true">
						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div>
						                    <div class="col-sm-3">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">GESTION:</label>
						                            <div class="col-md-9">
						                                <input name="Fgestion" placeholder="" class="form-control" type="text" id="Fgestion" readonly="true">
						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div>          
						                </div>
						                <div class="row">
				                    		<div class="col-sm-3">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">CURSO:</label>
						                            <div class="col-md-9">
						                                <select  name="Fcurso" class="form-control" id="Fcurso" onchange="getmat(this.value);resetIndik();">
						                                </select>
						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div>
						                    <div class="col-sm-3">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">MATERIA:</label>
						                            <div class="col-md-9">
						                                <select  name="Fmateria" class="form-control" id="Fmateria" onchange="iniBi(this.value)">
						                                </select>
						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div> 
						                    <div class="col-sm-3">
				                    			<div class="form-group">
						                            <label class="control-label col-md-3">BIMESTRE:</label>
						                            <div class="col-md-9">
						                                
						                                	<select  name="Fbimestre" class="form-control" id="Fbimestre">
						                                	<option value="2">SEGUNDO BIMESTRE</option>
						                                </select>
						                                <span class="help-block"></span>
						                            </div>
						                        </div>
						                    </div> -->
						                    <!-- fin de  -->
						                                       
						                   <!-- <div class="col-sm-3">
						                    	<div class="form-group">
						                    	 <button class="btn bg-brown-300" onclick="llenado_notas_exel()"   id="btll"><i class="glyphicon glyphicon-check"></i>PLANILLA</button>	

				                    			<form  method="post" enctype="multipart/form-data">
														<input type="file" name="mi_archivo">
													</form>
													<button class="btn bg-brown-300" onclick="#"   id="SUBIR"><i class="glyphicon glyphicon-check"></i>SUBIR PLANILLAS</button>
						                    </div>
						                    </div>-->				                

						           <!-- <button class="btn bg-brown-300" onclick="generar_estudiantes()"  id="btnindik"><i class="glyphicon glyphicon-check"></i>GENE. ESTUDIANTES</button>-->

						                </div>
						                <hr class="bg-brown">
						                <!-- <div class="panel-body">  
        										<div class="col-md-6 col-md-offset-3">                        
                   				 					<div class="col-md-5">
                   				 						<button class="btn btn-success" onclick="llenado_notas_exel()"   id="btll"><i class="glyphicon glyphicon-cloud-download"></i>PLANILLA</button>
                   				 					</div>
                   				 					<div class="col-md-5">
                   				 						<button class="btn btn-success" onclick="notas_exel()"   id="btll"><i class="glyphicon glyphicon-cloud-download"></i>NOTAS</button>
                   				 					</div> -->
                   				 					<!-- <div class="col-md-2">
                   				 					<form action="<?=base_url("Not_notas_contr/subir")?>" method="post" enctype="multipart/form-data">

                   				 					<input  name="profesor" id="profesor" type="hidden" value="KDFJDKLJKD">
                         							<input type="file" name="planilla" id="planilla" accept=".xls,.xlsx" />
    												
    												<button class="btn btn-primary" onclick="<?=base_url("Not_notas_contr/subir")?>" id="bt2l"><i class="glyphicon glyphicon-cloud-upload"></i>SUBIR</button>
													</form>
													</div> -->
                   				 					<!--<div class="col-md-2">
                   				 						<input type="file" name="planilla" title="seleccionar fichero" id="planilla" accept=".xls,.xlsx" />
														<button class="btn btn-primary" onclick="subir()" id="bt2l"><i class="glyphicon glyphicon-cloud-upload"></i>SUBIR</button>
                   				 					</div>-->
            									<!-- </div>
            									
        								</div>    -->   
				                    	<div class="row">                  
						                </div>
						                <hr class="bg-brown">
							        <table id="planilla" class="table datatable-responsive" cellspacing="0" width="100%">
							            <thead class="bg-brown">
							                <tr>
							                    <th>Nro</th>
							                    <th>CURSO</th>
							                    <th>MATERIA</th>
							                    <th>NIVEL</th>
							                    <th>COLEGIO</th>
							                    <th>BIMESTRE</th>
							                    <th>ACTION</th>
							                </tr>
							            </thead>
							            <tbody>
							            </tbody>

							            <tfoot class="bg-brown">
								            <tr>
							                    <th>Nro</th>
							                    <th>CURSO</th>
							                    <th>MATERIA</th>
							                    <th>NIVEL</th>
							                    <th>COLEGIO</th>
							                    <th>BIMESTRE</th>
							                    <th>ACTION</th>
							                </tr>
							            </tfoot>
							        </table>



						                 <!--<button class="btn btn-success" onclick="llenado_notas_exel()"   id="btll"><i class="glyphicon glyphicon-cloud-download"></i>PLANILLA</button>	
						                 <input type="file" name="archivo" title="seleccionar fichero" id="importData" accept=".xls,.xlsx" />
										<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-cloud-upload"></i>SUBIR</button>-->
							        <!--<table id="table_estudiante" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" width="100%" id="table_usuario">
							            <thead class="bg-brown-300">
							                <tr style="align : 'center';">
							                	<th rowspan="4">ID. NOTA</th>
												<th rowspan="4">AP. PATERNO</th>
												<th rowspan="4">AP. MATERNO</th>
												<th rowspan="4">NOMBRES</th>
												<th colspan="22">EVALUACIÓN DEL MAESTRO</th>
												<th colspan="2" rowspan="2">AUTOEVAL</th>
												<th rowspan="4" class="bg-primary-300" >&nbsp;&nbsp;FINAL&nbsp;    </th>
												<th rowspan="4">ACTION</th>
							                </tr>
							                 <tr>
												<th colspan="4">SER 10pt</th>
												<th colspan="7">SABER 35pt</th>
												<th colspan="7">HACER 35pt</th>
												<th colspan="4">DECIDIR 10pt</th>
							                </tr>
							                 <tr>							             
							                 	<th class="bg-orange-300"><label id='ser1'>Disciplina</label></th>
												<th class="bg-orange-300"><label id='ser2'>Resp. Puntual</label></th>
												<th class="bg-orange-300"><label id='ser3'>Honestidad</label></th>
												<th class="bg-primary-300" rowspan="2">PROMD. SER</th>
												<th class="bg-orange-300"><label id='sab1'></label></th>
												<th class="bg-orange-300"><label id='sab2'></label></th>
												<th class="bg-orange-300"><label id='sab3'></label></th>
												<th class="bg-orange-300"><label id='sab4'></label></th>
												<th class="bg-orange-300"><label id='sab5'></label></th>
												<th class="bg-orange-300"><label id='sab6'></label></th>
												<th class="bg-primary-300" rowspan="2">PROMD. SABER</th>
												<th class="bg-orange-300"><label id='hac1'></label></th>
												<th class="bg-orange-300"><label id='hac2'></label></th>
												<th class="bg-orange-300"><label id='hac3'></label></th>
												<th class="bg-orange-300"><label id='hac4'></label></th>
												<th class="bg-orange-300"><label id='hac5'></label></th>
												<th class="bg-orange-300"><label id='hac6'></label></th>
												<th class="bg-primary-300" rowspan="2">PROMD. HACER</th>
												<th class="bg-orange-300"><label id='dec1'>Partcip.</label></th>
												<th class="bg-orange-300"><label id='dec2'>Solidar. Generos</label></th>
												<th class="bg-orange-300"><label id='dec3'>Comunic.</label></th>
												<th class="bg-primary-300" rowspan="2">PROMD. DEC</th>
												<th class="bg-orange-300" rowspan="2">autoser</th>
												<th class="bg-orange-300" rowspan="2">autodec</th>

							                </tr>
							                <tr>
							                 	<th class="bg-orange-300">1ser</th>
												<th class="bg-orange-300">2ser</th>
												<th class="bg-orange-300">3ser</th>												
												<th class="bg-orange-300">1sab</th>
												<th class="bg-orange-300">2sab</th>
												<th class="bg-orange-300">3sab</th>
												<th class="bg-orange-300">4sab</th>
												<th class="bg-orange-300">5sab</th>
												<th class="bg-orange-300">6sab</th>												
												<th class="bg-orange-300">1hac</th>
												<th class="bg-orange-300">2hac</th>
												<th class="bg-orange-300">3hac</th>
												<th class="bg-orange-300">4hac</th>
												<th class="bg-orange-300">5hac</th>
												<th class="bg-orange-300">6hac</th>												
												<th class="bg-orange-300">1dec</th>
												<th class="bg-orange-300">2dec</th>
												<th class="bg-orange-300">3dec</th>
												

							                </tr>
							            </thead>
							            <tbody>
							            </tbody>

							            <tfoot class="bg-brown-300">
								            <tr>
							                    <th>idnota</th>
												<th>appat</th>
												<th>apmat</th>
												<th>nombres</th>
												<th class="bg-orange-300">Disciplina</th>
												<th class="bg-orange-300">Resp. Puntual</th>
												<th class="bg-orange-300">Honestidad</th>
												<th class="bg-primary-300">PROMD. SER</th>
												<th class="bg-orange-300">1sab</th>
												<th class="bg-orange-300">2sab</th>
												<th class="bg-orange-300">3sab</th>
												<th class="bg-orange-300">4sab</th>
												<th class="bg-orange-300">5sab</th>
												<th class="bg-orange-300">6sab</th>
												<th class="bg-primary-300">PROMD. SABER</th>
												<th class="bg-orange-300">1hac</th>
												<th class="bg-orange-300">2hac</th>
												<th class="bg-orange-300">3hac</th>
												<th class="bg-orange-300">4hac</th>
												<th class="bg-orange-300">5hac</th>
												<th class="bg-orange-300">6hac</th>
												<th class="bg-primary-300">PROMD. HACER</th>
												<th class="bg-orange-300">Partcip.</th>
												<th class="bg-orange-300">Solidar. Generos</th>
												<th class="bg-orange-300">Comunic.</th>
												<th class="bg-primary-300">PROMD. DEC</th>
												<th class="bg-orange-300">autoser</th>
												<th class="bg-orange-300">autodec</th>
												<th class="bg-primary-300">FINAL</th>
												<th>ACTION</th>
							                </tr>
							            </tfoot>
							        </table>-->
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
	var accion="";
	var _global_idcur="";
	var _global_mat="";
	var _idnota="";
	var _global_IdMat="";
	var _global_idindi="";
	var contsab=0;
	var conthac=0;

	$('#btnAvance').attr('disabled',true);


	$(document).ready(function(){

			testudiante=$('#planilla').DataTable({
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				"url":"<?php echo site_url('Not_notas_contr/ajax_list');?>",
				"type":"POST"
			},

			"columnDefs":[
			{
				"targets":[-1],
				"orderable":false,
			},
			],
		});
		get_prof();	
		//document.getElementById('btnindik').disabled = true;	
	});

	function get_idcod()
	{
		//envio de valores
		var data1={
				"table":"indicador",
				"cod":"IND-",
		};
	
		$.ajax({
			
	        url : "<?php echo site_url('Not_notas_contr/ajax_get_idindi');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		//datos recuperados
	           		_global_idindi=data.codgen;
	           		
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
	}

	function saber(id)
	{
		
		if(document.getElementById(id).checked)
		{
			var idnew=id.substr(2,id.length);
			swal({
	        title: "Indicador",
	        text: "Escriba el Indicador",
	        type: "input",
	        showCancelButton: true,
	        confirmButtonColor: "#2196F3",
	        closeOnConfirm: false,
	        animation: "slide-from-bottom",
	        inputPlaceholder: "Indicador"
	        },
	        function(inputValue){
	            if (inputValue === false) 
	            {
	            	document.getElementById(id).checked=false;
	            	return false;
	            }	            	
	            if (inputValue === "") {
	                swal.showInputError("Tu Necesitas Escribir algun Indicador!");
	                return false
	            }
	            swal({
	                title: "Correcto!!",
	                text: "Fue creado  y Habilitado el indicador : " + inputValue,
	                type: "success",
	                confirmButtonColor: "#2196F3"
	            });
	            //guadar indicador
	            
	            
				
				//verificar
	            document.getElementById(idnew).innerHTML="<input type='checkbox' id='"+id+"' onchange='saber(this.id)' checked>"+inputValue;
	        });	

		}
		else
		{
			var idnew=id.substr(2,id.length);
			document.getElementById(idnew).innerHTML="<input type='checkbox' id='"+id+"' onchange='saber(this.id)'>"+idnew;
		}
		 
	}

	function hacer(id)
	{
		if(document.getElementById(id).checked)
		{
			var idnew=id.substr(2,id.length);
			swal({
	        title: "Indicador",
	        text: "Escriba el Indicador",
	        type: "input",
	        showCancelButton: true,
	        confirmButtonColor: "#2196F3",
	        closeOnConfirm: false,
	        animation: "slide-from-bottom",
	        inputPlaceholder: "Indicador"
	        },
	        function(inputValue){
	            if (inputValue === false) 
	            {
	            	document.getElementById(id).checked=false;
	            	return false;
	            }	            	
	            if (inputValue === "") {
	                swal.showInputError("Tu Necesitas Escribir algun Indicador!");
	                return false
	            }
	            swal({
	                title: "Correcto!!",
	                text: "Fue creado  y Habilitado el indicador : " + inputValue,
	                type: "success",
	                confirmButtonColor: "#2196F3"
	            });
	            document.getElementById(idnew).innerHTML="<input type='checkbox' id='"+id+"' onchange='hacer(this.id)' checked>"+inputValue;
	        });	
		}
		else
		{
			var idnew=id.substr(2,id.length);
			document.getElementById(idnew).innerHTML="<input type='checkbox' id='"+id+"' onchange='hacer(this.id)'>"+idnew;
		}
		 
	}

	function guardar_nota(idnota)
	{
		var vali=false;
		var final=document.getElementById('final'+idnota).value;
		if(final!='')
		{
			var ser1,ser2,ser3,promser,sab1,sab2,sab3,sab4,sab5,sab6,promsab,hac1,hac2,hac3,hac4,hac5,hac6,promhac,dec1,dec2,dec3,promdec,autoser,autodec,final,btn;
			var url;
				
			ser1=document.getElementById('ser1'+idnota).value;
			ser2=document.getElementById('ser2'+idnota).value;
			ser3=document.getElementById('ser3'+idnota).value;
			promser=document.getElementById('promser'+idnota).value;
			sab1=document.getElementById('sab1'+idnota).value;
			sab2=document.getElementById('sab2'+idnota).value;
			sab3=document.getElementById('sab3'+idnota).value;
			sab4=document.getElementById('sab4'+idnota).value;
			sab5=document.getElementById('sab5'+idnota).value;
			sab6=document.getElementById('sab6'+idnota).value;
			promsab=document.getElementById('promsab'+idnota).value;
			hac1=document.getElementById('hac1'+idnota).value;
			hac2=document.getElementById('hac2'+idnota).value;
			hac3=document.getElementById('hac3'+idnota).value;
			hac4=document.getElementById('hac4'+idnota).value;
			hac5=document.getElementById('hac5'+idnota).value;
			hac6=document.getElementById('hac6'+idnota).value;
			promhac=document.getElementById('promhac'+idnota).value;
			dec1=document.getElementById('dec1'+idnota).value;
			dec2=document.getElementById('dec2'+idnota).value;
			dec3=document.getElementById('dec3'+idnota).value;
			promdec=document.getElementById('promdec'+idnota).value;
			autoser=document.getElementById('autoser'+idnota).value;
			autodec=document.getElementById('autodec'+idnota).value;
			btn=document.getElementById('btn'+idnota).id;
			
			url="<?php echo site_url('Not_notas_contr/ajax_update_nota');?>";

			var data1={
					"idnota":idnota,
					"ser1":ser1,
					"ser2":ser2,
					"ser3":ser3,
					"promser":promser,
					"sab1":sab1,
					"sab2":sab2,
					"sab3":sab3,
					"sab4":sab4,
					"sab5":sab5,
					"sab6":sab6,
					"promsab":promsab,
					"hac1":hac1,
					"hac2":hac2,
					"hac3":hac3,
					"hac4":hac4,
					"hac5":hac5,
					"hac6":hac6,
					"promhac":promhac,
					"dec1":dec1,
					"dec2":dec2,
					"dec3":dec3,
					"promdec":promdec,
					"autoser":autoser,
					"autodec":autodec,
					"final":final,
				};
			
			if (Number(promser)<5) //validacion menor a 5
			{
				/*swal({
			            title: "Información",
			            text: "El Promedio del SER no debe ser menor a 5, Registro no Guardado!!!",
			            confirmButtonColor: "#2196F3",
			            type: "info"
				    });
				vali=false;*/
				vali=true;	
			} 
			else if(Number(promdec)<5) //validacion menor a 5
			{
				/*swal({
			            title: "Información",
			            text: "El Promedio del DECIDIR no debe ser menor a 5, Registro no Guardado!!!",
			            confirmButtonColor: "#2196F3",
			            type: "info"
				    });
				vali=false;		*/		
				vali=true;	
			}
			else if(Number(final)<36)
			 {
			 	/*swal({
			             title: "Información",
			             text: "La NOTA FINAL no debe ser menor a 36, Registro NO Guardado!!!",
			             confirmButtonColor: "#2196F3",
			             type: "info"
			 	    });
			 	vali=false;*/
			 	vali=true;	
			 }
			else {
				vali=true;					
			}
			
			if(vali)
			{				
				$.ajax({
					
			        url : url,
			        type: "POST",
			        data:data1,
			        dataType: "JSON",
			        success: function(data)//cargado de datos del registro 
			        {   
			           if(data.status)
			           {		           		
			           		
			           		//document.getElementById(btn).text= 'Guardado';	
			           		msg_guardar(btn);
			           		//document.getElementById(btn).style.backgroundColor= '#A1887F';           	
			           }
			        },
			        error: function (jqXHR, textStatus, errorThrown)
			        {
			           // alert('No puede obtener codigo nuevo, para el registro');
			        }
		    	});	
		    }
			    	
		}
		else
			{
				swal({
                    title: "Sin Datos",
                    text: "Este alumno no tiene Nota!!!",
                    confirmButtonColor: "#2196F3",
                    type: "error"
                });
			}
	}



	function get_prof()
	{
		var idprof='';
		var url="<?php echo site_url('Not_notas_contr/ajax_usuario')?>";
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
	           		idprof=data.data[0];
	           		document.getElementById('Fidprofe').value=data.data[0];	           		
	           		document.getElementById('Fprofe').value=data.data[1];
	           		//document.getElementById('profesor').value=data.data[1];	
	           		get_nivel(idprof);
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
	
	//SELECT nivel
	function get_nivel($idp)
	{
		var options="<option value=''></option>";
		//envio de valores
		var data1={
				"table":"materia",
				"idprof":$idp,
		};
		
		$.ajax({
			
	        url : "<?php echo site_url('Not_notas_contr/ajax_get_nivel');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	    
	           		var i=0;       		
	           		data.data.forEach(function(item){
	           			
	           			options=options+"<option value='"+data.data[i]+"'>"+data.data1[i]+"</option>";
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
	
	//gescole una vez selecionado el nivel carga el colegio y la gestion
	function gescole(level)
	{
		
		var data1={
				"table":"curso",
				"lvl":level,
		};
	
		$.ajax({
			
	        url : "<?php echo site_url('Not_notas_contr/ajax_get_level');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		//datos recuperados
	           		var ano = (new Date).getFullYear();
	           		document.getElementById('Fgestion').value=ano;
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
		var idprof=document.getElementById('Fidprofe').value;
		var options="<option value=''></option>";
		//alert(nivel);
		var datacur={
			"tabla":"materia",
			"idprof":idprof,
			"nivel":nivel,
		}
		$.ajax({
			
	        url : "<?php echo site_url('Not_notas_contr/ajax_get_curso');?>",
	        type: "POST",
	        data:datacur,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	
	           		var i=0;           		
	           		data.data.forEach(function(item){	           			
	           			options=options+"<option value='"+data.data[i]+"'>"+data.data1[i]+"</option>";	
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

	function getmat(cur)
	{
		//alert(cur);
		var idprof=document.getElementById('Fidprofe').value;
		var nivel=document.getElementById('Fnivel').value;
		var options="<option value=''></option>";
		//alert(nivel);
		var datamat={
			"tabla":"materia",
			"idprof":idprof,
			"nivel":nivel,
			"curso":cur,
		}
		
		$.ajax({
			
	        url : "<?php echo site_url('Not_notas_contr/ajax_get_mat');?>",
	        type: "POST",
	        data:datamat,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	
	           		//alert(data.data[1]); 
	           		//alert(data.data);
	           		var i=0;
	           		data.data.forEach(function(item){
	           			options=options+"<option value='"+data.data[i]+"'>"+data.data1[i]+"</option>";
	           			i++;             			
	           		});
	           		document.getElementById('Fmateria').innerHTML=options;          			           		
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');

	        }
    	});

    	//document.getElementById('Fbimestre').value='';
    	
	}

	function iniBi(mat)
	{
		//Jdocument.getElementById('Fbimestre').value='';
		resetIndik();
		
		_global_mat=mat;

	}


	//obtener estudiante en tabla, una vez escogido el curso
	function fill_table_nota(bi)
	{		
		contsab=0;
		conthac=0;
		var cur=document.getElementById('Fcurso').value;
		var nivel=document.getElementById('Fnivel').value;
		var idprof=document.getElementById('Fidprofe').value;
		var gestion=document.getElementById('Fgestion').value;
		var materia=_global_mat;
		
		var bimestre=bi;
		var datacur={
			"cur":cur,
			"nivel":nivel,
			"idprof":idprof,
			"gestion":gestion,
			"bimestre":bimestre,
			"materia":materia,
		}

		$.ajax({
			
	        url : "<?php echo site_url('Not_notas_contr/ajax_get_idcurso');?>",
	        type: "POST",
	        data:datacur,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           	
	           		cargarLista(data.data.idmat,data.data.idcurso,data.data.idprof,data.data.gestion,data.data.bimestre); 
	           		_global_IdMat=data.data.bimestre+"-"+data.data.idmat;  

	           		//alert(data.data.idmat+"-"+data.data.idcurso+"-"+data.data.idprof+"-"+data.data.gestion+"-"+data.data.bimestre);
	           		document.getElementById('idmat').value=data.data.idmat;	
	           		document.getElementById('gestion').value=data.data.gestion;           		   
	           		$('#btnAvance').removeAttr('disabled');		
	           }
	        }	        
    	});  

		document.getElementById('btnindik').disabled = false;

	}

	
	//cargar solo curso y materia
	function cargarLista(idmat,idcur,idprof,gestion,bimestre)
	{
		
		var data={
				"idmat":idmat,
				"idcur":idcur,
				"idprof":idprof,
				"gestion":gestion,
				"bimestre":bimestre,
			};

        var data1={
				"table":"indicador",
				"bimes":bimestre,
				"idmat":idmat,
				"gestion":gestion
		};

		$.ajax({					
		        url : "<?php echo site_url('Not_notas_contr/ajax_if_indi');?>",
		        type: "POST",
		        data:data1,
		        dataType: "JSON",
		        success: function(data)//cargado de datos del registro 
		        {   		           	       
	           		
	           		if(data.sab1!='')
	           		{
	           			document.getElementById('sab1').innerHTML=data.sab1;
	           			
	           			contsab=contsab+1;	           				           			
	           		}
	           		else
	           		{
	           			document.getElementById('sab1').innerHTML="";
	           		}

	           		if(data.sab2!='')
	           		{	
						document.getElementById('sab2').innerHTML=data.sab2;
						contsab=contsab+1;	
					}
					else
						document.getElementById('sab2').innerHTML="";

					if(data.sab3!='')
	           		{
						document.getElementById('sab3').innerHTML=data.sab3;
						contsab=contsab+1;	
					}
					else
						document.getElementById('sab3').innerHTML="";

					if(data.sab4!='')
	           		{					
						document.getElementById('sab4').innerHTML=data.sab4;
						contsab=contsab+1;	
					}
					else
						document.getElementById('sab4').innerHTML="";

					if(data.sab5!='')
	           		{
						document.getElementById('sab5').innerHTML=data.sab5;
						contsab=contsab+1;	
					}
					else
						document.getElementById('sab5').innerHTML="";

					if(data.sab6!='')
	           		{
						document.getElementById('sab6').innerHTML=data.sab6;
						contsab=contsab+1;	
					}
					else
						document.getElementById('sab6').innerHTML="";

					if(data.hac1!='')
	           		{
						document.getElementById('hac1').innerHTML=data.hac1;
						conthac=conthac+1;	
					}
					else
						document.getElementById('hac1').innerHTML="";

					if(data.hac2!='')
	           		{
						document.getElementById('hac2').innerHTML=data.hac2;
						conthac=conthac+1;	
					}
					else
						document.getElementById('hac2').innerHTML="";

					if(data.hac3!='')
	           		{
						document.getElementById('hac3').innerHTML=data.hac3;
						conthac=conthac+1;	
					}
					else
						document.getElementById('hac3').innerHTML="";
					if(data.hac4!='')
	           		{
						document.getElementById('hac4').innerHTML=data.hac4;
						conthac=conthac+1;	
					}
					else
						document.getElementById('hac4').innerHTML="";

					if(data.hac5!='')
	           		{
						document.getElementById('hac5').innerHTML=data.hac5;
						conthac=conthac+1;	
					}
					else
						document.getElementById('hac5').innerHTML="";

					if(data.hac6!='')
	           		{
						document.getElementById('hac6').innerHTML=data.hac6;
						conthac=conthac+1;	
					}
					else
						document.getElementById('hac6').innerHTML="";

					           	
		        },
		        error: function (jqXHR, textStatus, errorThrown)
		        {
		            
		        }
	    	});	    	


		
		url1="<?php echo site_url('Not_notas_contr/ajax_list_alum');?>";    
		testudiante=$('#table_estudiante').DataTable({
			"destroy": true,

			"serverSide":true,
			"order":[],
			"processing":true,
			"ajax":{
				"url":url1,
				"type":"POST",
				"data":data,
		        "dataType": "JSON",
			},
			
			"columnDefs":[
			{
				"targets":[-1],
				"orderable":false,
			},
			],
			"scrollX":true,
			"scrollCollapse": true,
	        "fixedColumns": {
	            "leftColumns": 4,
	            "rightColumns": 0
	        }
		});

		
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
	function msg_guardar(btn)
	{
		document.getElementById(btn).style.backgroundColor= '#81C784';
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
		            url : "<?php echo site_url('Est_estudiante_contr/ajax_delete')?>/"+id,
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

	//editar estudiante
	function edit_estud(id)
	{
		//alert(id);
		save_method='update';
		var img="";
		$('#btnSave').text('Modificar');
		var temp="<?php echo site_url('assets/images/anonimo.jpg');?>";

		$.ajax({
			url:"<?php  echo site_url('Est_estudiante_contr/ajax_edit_estud'); ?>/"+id,
			type:"GET",
			dataType:"JSON",
			success: function(data)
			{
				$('[name="idest"]').val(data.idest);
				$('[name="rude"]').val(data.rude);
				$('[name="ci"]').val(data.ci);	
				$('[name="appaterno"]').val(data.appaterno);
				$('[name="apmaterno"]').val(data.apmaterno);						
				$('[name="nombre"]').val(data.nombres);				
				$('[name="genero"]').val(data.genero);
				$('[name="codigo"]').val(data.codigo);
				$('[name="idcurso"]').val(data.idcurso);
				$('[name="colegio"]').val(data.colegio);
				$('[name="gestion"]').val(data.gestion);
				
				if(data.foto=="")
				{					
					document.getElementById('loadimg').src=temp;
				}
				else
				{
					document.getElementById('loadimg').src=data.foto;
				}
				

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
	
	function val10(val,id,limt)
	{
		var nota=val;
		if (val>limt)
		{
			swal({
		            title: "Información",
		            text: "Solo es permito valores de 1 a "+limt,
		            confirmButtonColor: "#2196F3",
		            type: "info"
			});
			document.getElementById(id).value='';
		}
	}

	function promser(val,id)
	{
		var idnota=id.substr(4, id.length);
		var c=3,ser1=0,ser2=0,ser3=0,promd=0;
		
		if(document.getElementById("ser1"+idnota).value!='') ser1=Number(document.getElementById("ser1"+idnota).value);						 
		if(document.getElementById("ser2"+idnota).value!='') ser2=Number(document.getElementById("ser2"+idnota).value);						
		if(document.getElementById("ser3"+idnota).value!='') ser3=Number(document.getElementById("ser3"+idnota).value);				
		//var prom=(ser1+ser2)/2;
		var prom=(ser1+ser2+ser3)/c; //habilitar tres indicadores
		document.getElementById('promser'+idnota).value=Number(prom).toFixed(0); 
		promd=Number(prom).toFixed(0);
		
		notafinal(idnota);
		var btn="btn"+idnota;
		document.getElementById(btn).style.backgroundColor= '#A1887F';
		
	}

	function promsaber(val,id)
	{
		var idnota=id.substr(4, id.length);
		var sab1=0,sab2=0,sab3=0,sab4=0,sab5=0,sab6=0;
		if(document.getElementById("sab1"+idnota).value!='') sab1=Number(document.getElementById("sab1"+idnota).value);					
		if(document.getElementById("sab2"+idnota).value!='') sab2=Number(document.getElementById("sab2"+idnota).value);							
		if(document.getElementById("sab3"+idnota).value!='') sab3=Number(document.getElementById("sab3"+idnota).value);				
		if(document.getElementById("sab4"+idnota).value!='') sab4=Number(document.getElementById("sab4"+idnota).value);				
		if(document.getElementById("sab5"+idnota).value!='') sab5=Number(document.getElementById("sab5"+idnota).value);				
		if(document.getElementById("sab6"+idnota).value!='') sab6=Number(document.getElementById("sab6"+idnota).value);				
		
		if(contsab==1) var prom=(sab1)/contsab;
		if(contsab==2) var prom=(sab1+sab2)/contsab;
		if(contsab==3) var prom=(sab1+sab2+sab3)/contsab;
		if(contsab==4) var prom=(sab1+sab2+sab3+sab4)/contsab;
		if(contsab==5) var prom=(sab1+sab2+sab3+sab4+sab5)/contsab;
		if(contsab==6) var prom=(sab1+sab2+sab3+sab4+sab5+sab6)/contsab;


		document.getElementById('promsab'+idnota).value=Number(prom).toFixed(0); 
		notafinal(idnota);

		var btn="btn"+idnota;
		document.getElementById(btn).style.backgroundColor= '#A1887F';
	}
	
	function promhacer(val,id)
	{
		var idnota=id.substr(4, id.length);
		var hac1=0,hac2=0,hac3=0,hac4=0,hac5=0,hac6=0;
		if(document.getElementById("hac1"+idnota).value!='') hac1=Number(document.getElementById("hac1"+idnota).value);				
		if(document.getElementById("hac2"+idnota).value!='') hac2=Number(document.getElementById("hac2"+idnota).value);				
		if(document.getElementById("hac3"+idnota).value!='') hac3=Number(document.getElementById("hac3"+idnota).value);				
		if(document.getElementById("hac4"+idnota).value!='') hac4=Number(document.getElementById("hac4"+idnota).value);				
		if(document.getElementById("hac5"+idnota).value!='') hac5=Number(document.getElementById("hac5"+idnota).value);				
		if(document.getElementById("hac6"+idnota).value!='') hac6=Number(document.getElementById("hac6"+idnota).value);				
		if(conthac==1) var prom=(hac1)/conthac;
		if(conthac==2) var prom=(hac1+hac2)/conthac;
		if(conthac==3) var prom=(hac1+hac2+hac3)/conthac;
		if(conthac==4) var prom=(hac1+hac2+hac3+hac4)/conthac;
		if(conthac==5) var prom=(hac1+hac2+hac3+hac4+hac5)/conthac;
		if(conthac==6) var prom=(hac1+hac2+hac3+hac4+hac5+hac6)/conthac;

		document.getElementById('promhac'+idnota).value=Number(prom).toFixed(0); 
		notafinal(idnota);

		var btn="btn"+idnota;
		document.getElementById(btn).style.backgroundColor= '#A1887F';
	}

	function promdec(val,id)
	{
		var idnota=id.substr(4, id.length);
		var c=3,dec1=0,dec2=0,dec3=0;
		
		if(document.getElementById("dec1"+idnota).value!='') dec1=Number(document.getElementById("dec1"+idnota).value);				

		if(document.getElementById("dec2"+idnota).value!='') dec2=Number(document.getElementById("dec2"+idnota).value);				

		if(document.getElementById("dec3"+idnota).value!='') dec3=Number(document.getElementById("dec3"+idnota).value);				

		//var prom=(dec1+dec2)/2;
		var prom=(dec1+dec2+dec3)/c; //habilitar tres indicadores

		document.getElementById('promdec'+idnota).value=Number(prom).toFixed(0);
		notafinal(idnota); 

		var btn="btn"+idnota;
		document.getElementById(btn).style.backgroundColor= '#A1887F';
	}

	function autoser(val,id)
	{
		var idnota=id.substr(7, id.length);
		notafinal(idnota);

		var btn="btn"+idnota;
		document.getElementById(btn).style.backgroundColor= '#A1887F';
	}

	function autodec(val,id)
	{
		var idnota=id.substr(7, id.length);
		notafinal(idnota);	

		var btn="btn"+idnota;
		document.getElementById(btn).style.backgroundColor= '#A1887F';
	}

	function notafinal(idnota)
	{
		var promser=0,promsab=0,promhac=0,promdec=0,autoser=0,autodec=0;
		promser=Number(document.getElementById('promser'+idnota).value);
		promsab=Number(document.getElementById('promsab'+idnota).value);
		promhac=Number(document.getElementById('promhac'+idnota).value);
		promdec=Number(document.getElementById('promdec'+idnota).value);
		autoser=Number(document.getElementById('autoser'+idnota).value);
		autodec=Number(document.getElementById('autodec'+idnota).value);		

		var final=promser+promsab+promhac+promdec+autoser+autodec;

		document.getElementById('final'+idnota).value=final;
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
				"url":"<?php echo site_url('Est_estudiante_contr/ajax_list');?>",
				"type":"POST"
			},

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

	function avance()
	{
		var data1={
				"table":"temasbi",
				"cod":"TEM-",
		};
	
		$.ajax({
			
	        url : "<?php echo site_url('Not_notas_contr/ajax_get_id');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {
	           		//datos recuperados
	           		document.getElementById('idavance').value=data.data;
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});

   		 
   		document.getElementById('materia').value=document.getElementById('Fmateria').value;
   		document.getElementById('bimestre').value=document.getElementById('Fbimestre').value;
   		estadistica();
		$('#modal_form').modal('show');
	}

	function porcenTemas()
	{
		var porcen=0;		
		if(document.getElementById('Tanual').value!='')
			if(Number(document.getElementById('Tanual').value)>0)
			var tanual=Number(document.getElementById('Tanual').value);

		if(document.getElementById('Tprog').value!='')
			if(Number(document.getElementById('Tprog').value)>0)				
			var tprog=Number(document.getElementById('Tprog').value);

		if(document.getElementById('Tavanz').value!='')
			if(Number(document.getElementById('Tavanz').value)>0)
			var tavanz=Number(document.getElementById('Tavanz').value);

		porcen=(tavanz*100)/tanual;
		document.getElementById('Tporc').value=Number(porcen).toFixed(0)+"%";

	}

	function estadistica()
	{
		var idmat=_global_IdMat;
		var url = "<?php  echo site_url('Not_notas_contr/ajax_get_estadis');?>/"+idmat;

	    $.ajax({
	            url :url,
	            type: "POST",
	            ContentType:"application/pdf",
	            success: function(data)
	            {
	                if(data.status)
	           		{
	           		//datos recuperados
	           		document.getElementById('idavance').value=data.data;
	           		}	                
	            },
	            error: function (jqXHR, textStatus, errorThrown)
	            {
	                
	            }
	        });	


	}

	function modal_indica()
	{
		get_idcod();
		resetIndi()
		var existe=false;
		var bi=_global_IdMat.substr(0,1);
        var idmat=_global_IdMat.substr(2,_global_IdMat.length);
        var gestion=document.getElementById('Fgestion').value; 

        var data1={
				"table":"indicador",
				"bimes":bi,
				"idmat":idmat,
				"gestion":gestion
		};

		$.ajax({					
	        url : "<?php echo site_url('Not_notas_contr/ajax_if_exit');?>",
	        type: "POST",
	        data:data1,
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           					           		
	           		if(data.data)
	           			existe=true;
	           		else
	           		{
	           			accion="new";
	           			var idindi=_global_idindi;
						document.getElementById('Kidindi').value=idindi;
				        document.getElementById('Kidmat').value=idmat;
				        document.getElementById('Kbimestre').value=bi;
				        document.getElementById('Kgestion').value=gestion;
						$('#btnSave').text('Guardar');										
						
	           		}	           		
	           		 	           		
	           }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	           // alert('No puede obtener codigo nuevo, para el registro');
	        }
    	});
		
		

		if(!existe)
		{
			 accion="update";
			$.ajax({					
		        url : "<?php echo site_url('Not_notas_contr/ajax_if_indi');?>",
		        type: "POST",
		        data:data1,
		        dataType: "JSON",
		        success: function(data)//cargado de datos del registro 
		        {   
		           	        			        
	           		document.getElementById('Kidindi').value=data.idindi;
	           		document.getElementById('Kidmat').value=data.idmat;
	           		document.getElementById('Kbimestre').value=data.bimestre;
	           		document.getElementById('Kgestion').value=data.gestion;
	           		if(data.sab1!='')
	           		{
	           			document.getElementById('inpsab1').value=data.sab1;
	           				           				           			
	           		}
	           				
	           		if(data.sab2!='')
	           		{	
						document.getElementById('inpsab2').value=data.sab2;
					}

							
					if(data.sab3!='')
	           		{
						document.getElementById('inpsab3').value=data.sab3;
					}


					if(data.sab4!='')
	           		{					
						document.getElementById('inpsab4').value=data.sab4;
					}


					if(data.sab5!='')
	           		{
						document.getElementById('inpsab5').value=data.sab5;
					}


					if(data.sab6!='')
	           		{
						document.getElementById('inpsab6').value=data.sab6;
					}


					if(data.hac1!='')
	           		{
						document.getElementById('inphac1').value=data.hac1;
					}


					if(data.hac2!='')
	           		{
						document.getElementById('inphac2').value=data.hac2;
					}

					if(data.hac3!='')
	           		{
						document.getElementById('inphac3').value=data.hac3;
					}
					if(data.hac4!='')
	           		{
						document.getElementById('inphac4').value=data.hac4;
					}
					if(data.hac5!='')
	           		{
						document.getElementById('inphac5').value=data.hac5;
					}
					if(data.hac6!='')
	           		{
						document.getElementById('inphac6').value=data.hac6;
					}
					$('#btnSave').text('Modificar');	           	
		        },
		        error: function (jqXHR, textStatus, errorThrown)
		        {
		            
		        }
	    	});	    	
		}


		
		$('#modal_form2').modal('show');
	}

	function generar_estudiantes()
	{
		$.ajax({					
	        url : "<?php echo site_url('Not_notas_contr/gene_estu');?>",
	        type: "POST",
	        data:'',
	        dataType: "JSON",
	        success: function(data)//cargado de datos del registro 
	        {   
	           if(data.status)
	           {	           					           		
	           		if(data.data)
	           			existe=true;
	           		else
	           		{
	           			accion="new";					
	           		}	           		
	           		 	           		
	           }
	        },
    	});
	}
	
	function if_indik_existe()
	{
		
	}


	function habindi(id)
	{
		var indi=id.substr(3,id.length);
		var codinp="inp"+indi;
		if(document.getElementById(id).checked)
			document.getElementById(codinp).disabled=false;
		else
		{
			document.getElementById(codinp).disabled=true;
			document.getElementById(codinp).value="";
		}		
	}

	function resetIndi()
	{
		document.getElementById('Kidindi').value="";
		document.getElementById('Kidmat').value="";
		document.getElementById('Kbimestre').value="";
		document.getElementById('Kgestion').value="";
		document.getElementById('inpsab1').value="";
		document.getElementById('inpsab2').value="";
		document.getElementById('inpsab3').value="";
		document.getElementById('inpsab4').value="";
		document.getElementById('inpsab5').value="";
		document.getElementById('inpsab6').value="";
		document.getElementById('inphac1').value="";
		document.getElementById('inphac2').value="";
		document.getElementById('inphac3').value="";
		document.getElementById('inphac4').value="";
		document.getElementById('inphac5').value="";
		document.getElementById('inphac6').value="";

	}	
	//reset en tabla
	function resetIndik()
	{
		document.getElementById('sab1').innerHTML="";
		document.getElementById('sab2').innerHTML="";
		document.getElementById('sab3').innerHTML="";
		document.getElementById('sab4').innerHTML="";
		document.getElementById('sab5').innerHTML="";
		document.getElementById('sab6').innerHTML="";
		document.getElementById('hac1').innerHTML="";
		document.getElementById('hac2').innerHTML="";
		document.getElementById('hac3').innerHTML="";
		document.getElementById('hac4').innerHTML="";
		document.getElementById('hac5').innerHTML="";
		document.getElementById('hac6').innerHTML="";
		
	}

function notas_exel()
	{
		var cur=document.getElementById('Fcurso').value;
		var idprof=document.getElementById('Fidprofe').value;
		var gestion=document.getElementById('Fgestion').value;
		var materia=document.getElementById('Fmateria').value;
		var bimestre=document.getElementById('Fbimestre').value;
		var nivel=document.getElementById('Fnivel').value;
		

		var id=gestion+"W"+cur+"W"+idprof+"W"+materia+"W"+bimestre+"W"+nivel+"W";
		
 		
 		 var url = "<?php  echo site_url('Not_notas_contr/notas_exel');?>/"+id;

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
	/*function subir()
	{
		var cur=document.getElementById('Fcurso').value;
		var idprof=document.getElementById('Fidprofe').value;
		var gestion=document.getElementById('Fgestion').value;
		var materia=document.getElementById('Fmateria').value;
		var bimestre=document.getElementById('Fbimestre').value;
		var nivel=document.getElementById('Fnivel').value;
		var planillas=document.getElementById('planilla').value;
		
		//var id=gestion+"W"+cur+"W"+idprof+"W"+materia+"W"+bimestre+"W"+nivel+"W"+planillas+"W";
		var id=planillas;
		alert(planillas);
 		
 		 var url = "<?php // echo site_url('Not_notas_contr/subir');?>/"+id;

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
	}*/		
	function llenado_notas_exel()
	{
		var cur=document.getElementById('Fcurso').value;
		var idprof=document.getElementById('Fidprofe').value;
		var gestion=document.getElementById('Fgestion').value;
		var materia=document.getElementById('Fmateria').value;
		var bimestre=document.getElementById('Fbimestre').value;
		var nivel=document.getElementById('Fnivel').value;
		

		var id=gestion+"W"+cur+"W"+idprof+"W"+materia+"W"+bimestre+"W"+nivel+"W";
		
 		
 		 var url = "<?php  echo site_url('Not_notas_contr/llenado_notas_exel');?>/"+id;

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
	function d_planilla($id,$id1)
	{		
 		 var url = "<?php  echo site_url('Not_notas_contr/d_planilla1');?>/"+$id;

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

	function d_planilla_notas($id,$id1)
	{
 		 var url = "<?php  echo site_url('Not_notas_contr/d_planilla_notas');?>/"+$id;

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

	function save_indicad()
	{
		//alert(accion);
			
		var idindi=document.getElementById('Kidindi').value;
		var idmat=document.getElementById('Kidmat').value;
		var bimes=document.getElementById('Kbimestre').value;
		var gestion=document.getElementById('Kgestion').value;
		var inpsab1=document.getElementById('inpsab1').value;
		var inpsab2=document.getElementById('inpsab2').value;
		var inpsab3=document.getElementById('inpsab3').value;
		var inpsab4=document.getElementById('inpsab4').value;
		var inpsab5=document.getElementById('inpsab5').value;
		var inpsab6=document.getElementById('inpsab6').value;
		var inphac1=document.getElementById('inphac1').value;
		var inphac2=document.getElementById('inphac2').value;
		var inphac3=document.getElementById('inphac3').value;
		var inphac4=document.getElementById('inphac4').value;
		var inphac5=document.getElementById('inphac5').value;
		var inphac6=document.getElementById('inphac6').value;

        var data1={
			"table":"indicador",
			"idindi":idindi,
			"idmat":idmat,
			"bimes":bimes,
			"gestion":gestion,
			"inpsab1":inpsab1,
			"inpsab2":inpsab2,
			"inpsab3":inpsab3,
			"inpsab4":inpsab4,
			"inpsab5":inpsab5,
			"inpsab6":inpsab6,
			"inphac1":inphac1,
			"inphac2":inphac2,
			"inphac3":inphac3,
			"inphac4":inphac4,
			"inphac5":inphac5,
			"inphac6":inphac6,			
		};				
			
		if(accion=='new')
		{
			$.ajax({					
		        url : "<?php echo site_url('Not_notas_contr/ajax_set_indi');?>",
		        type: "POST",
		        data:data1,
		        dataType: "JSON",
		        success: function(data)//cargado de datos del registro 
		        {   
		           if(data.status)
		           {	           					           		
		           		 swal({
			                    title: "Archivo",
			                    text: "Indicadores Guardados para esta Materia y Curso",
			                    confirmButtonColor: "#66BB6A",
			                    type: "success"
			                });	
		           		 
		           }
		        },
		        error: function (jqXHR, textStatus, errorThrown)
		        {
		           // alert('No puede obtener codigo nuevo, para el registro');
		        }
	    	});
		}
		if(accion=='update')
		{
			contsab=0;
			conthac=0;
			$.ajax({					
		        url : "<?php  echo site_url('Not_notas_contr/ajax_update_indi');?>",
		        type: "POST",
		        data:data1,
		        dataType: "JSON",
		        success: function(data)//cargado de datos del registro 
		        {   
		           if(data.status)
		           {	           					           		
		           		 swal({
			                    title: "Archivo",
			                    text: "Indicadores Actualizados para esta Materia y Curso",
			                    confirmButtonColor: "#66BB6A",
			                    type: "success"
			                });	
		           		 
		           		
		           }
		        },
		        error: function (jqXHR, textStatus, errorThrown)
		        {
		           // alert('No puede obtener codigo nuevo, para el registro');
		        }
	    	});
		}
		 

		if(inpsab1!='')
		{
			document.getElementById('sab1').innerHTML=inpsab1;
			
	    	contsab=contsab+1;	 
		}
		else
			document.getElementById('sab1').innerHTML="";

		if(inpsab2!='')
		{
			document.getElementById('sab2').innerHTML=inpsab2;
	    	contsab=contsab+1;	 
		}
		else
			document.getElementById('sab2').innerHTML="";

		if(inpsab3!='')
		{
			document.getElementById('sab3').innerHTML=inpsab3;
	    	contsab=contsab+1;	 
		}
		else
			document.getElementById('sab3').innerHTML="";

		if(inpsab4!='')
		{
			document.getElementById('sab4').innerHTML=inpsab4;
	    	contsab=contsab+1;	 
		}
		else
			document.getElementById('sab4').innerHTML="";

		if(inpsab5!='')
		{
			document.getElementById('sab5').innerHTML=inpsab5;
	    	contsab=contsab+1;	 
		}
		else
			document.getElementById('sab5').innerHTML="";

		if(inpsab6!='')
		{
			document.getElementById('sab6').innerHTML=inpsab6;
	    	contsab=contsab+1;	 
		}
		else
			document.getElementById('sab6').innerHTML="";


		if(inphac1!='')
		{
			document.getElementById('hac1').innerHTML=inphac1;
	    	conthac=conthac+1;	 
		}
		else
			document.getElementById('hac1').innerHTML="";

		if(inphac2!='')
		{
			document.getElementById('hac2').innerHTML=inphac2;
	    	conthac=conthac+1;	 
		}
		else
			document.getElementById('hac2').innerHTML="";

		if(inphac3!='')
		{
			document.getElementById('hac3').innerHTML=inphac3;
	    	conthac=conthac+1;	 
		}
		else
			document.getElementById('hac3').innerHTML="";

		if(inphac4!='')
		{
			document.getElementById('hac4').innerHTML=inphac4;
	    	conthac=conthac+1;	 
		}
		else
			document.getElementById('hac4').innerHTML="";

		if(inphac5!='')
		{
			document.getElementById('hac5').innerHTML=inphac5;
	    	conthac=conthac+1;	 
		}
		else
			document.getElementById('hac5').innerHTML="";

		if(inphac6!='')
		{
			document.getElementById('hac6').innerHTML=inphac6;
	    	conthac=conthac+1;	 
		}
		else
			document.getElementById('hac6').innerHTML="";
			


		 $('#modal_form2').modal('hide');

		
    	//resetIndi();
	}

	//controla si hay indicador creado
	function mensIndi(idcolm,id)
	{
		var inp=document.getElementById(idcolm).innerHTML;
		var n = inp.length;

		if(n==0)
		{
			swal({
                    title: "Indicador",
                    text: "Debe crear el indicador para esta columna",
                    confirmButtonColor: '#ffd43b',
                    type: "warning"
                });
			document.getElementById(id).value="";
		}
		
	}

	function valindik(id)
	{
		var idcolm=id.substr(0,4);
		
		if(idcolm=='sab1') 
		{
			mensIndi(idcolm,id);
			
		}
		if(idcolm=='sab2') 
		{
			mensIndi(idcolm,id);
		}
		if(idcolm=='sab3') 
		{
			mensIndi(idcolm,id);
		}
		if(idcolm=='sab4') 
		{
			mensIndi(idcolm,id);
		}
		if(idcolm=='sab5') 
		{
			mensIndi(idcolm,id);
		}
		if(idcolm=='sab6') 
		{
			mensIndi(idcolm,id);
		}
		if(idcolm=='hac1') 
		{
			mensIndi(idcolm,id);
			
		}
		if(idcolm=='hac2') 
		{
			mensIndi(idcolm,id);
		}
		if(idcolm=='hac3') 
		{
			mensIndi(idcolm,id);
		}
		if(idcolm=='hac4') 
		{
			mensIndi(idcolm,id);
		}
		if(idcolm=='hac5') 
		{
			mensIndi(idcolm,id);
		}
		if(idcolm=='hac6') 
		{
			mensIndi(idcolm,id);
		}

	}
	

	//ventana para el pdf
	function export_pdf()
	{
		//alert(_global_IdMat);
		var idmat=_global_IdMat;
		
	//	alert(idmat);
		
		var url = "<?php  echo site_url('Not_notas_contr/printnotas');?>/"+idmat;

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
	



	function export_xls()
	{
		/*var valor=_global_idcur;
				
	    var url = "<?php // echo site_url('Est_estudiante_contr/printxls');?>/"+valor;

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
	        });	 */
	}

</script>


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-brown-300">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Formulario de Avance de Temas</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                    	<div class="row">
                    		<div class="col-sm-2">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">ID:</label>
		                            <div class="col-md-8">
		                                <input name="idavance" placeholder="" class="form-control" type="text" id="idavance" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        	                        
                    		</div>

                    		<div class="col-sm-2">
                    			<div class="form-group">
		                            <label class="control-label col-md-3">MAT:</label>
		                            <div class="col-md-8">
		                                <input class="form-control" type="text" id="idmat" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>

		                         	                        
                    		</div>

                    		<div class="col-sm-4">
                    			<div class="form-group">
		                            <label class="control-label col-md-3">MATERIA:</label>
		                            <div class="col-md-9">
		                                <input class="form-control" type="text" id="materia" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        	                                       
                    		</div>
                    		<div class="col-sm-2">
	                        	<div class="form-group">
		                            <label class="control-label col-md-4">BIMES:</label>
		                            <div class="col-md-8">
		                            	<input type="text" id="bimestre" class="form-control" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>       	                          
	                    	</div>
                    		<div class="col-sm-2">
	                        	<div class="form-group">
		                            <label class="control-label col-md-4">GEST:</label>
		                            <div class="col-md-8">
		                            	<input type="text" id="gestion" class="form-control" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>       	                          
	                    	</div>
                    	</div>
                    	<h6 class="alpha-brown" style='color:brown;'>Avance de Materia Bimestral</h6>
                    	
                     	<div class="row">
                    		<div class="col-sm-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-6">TEMAS ANUL:</label>
		                            <div class="col-md-6">
		                                <input type="Number" class="form-control"  min='1' max='30' id="Tanual" onchange="porcenTemas()">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-md-6">TEMAS PROG:</label>
		                            <div class="col-md-6">
		                                <input class="form-control" type="Number" min="1" max='10' id="Tprog" onchange="porcenTemas()">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>		                          <div class="form-group">
		                            <label class="control-label col-md-6">TEMAS AVANZ:</label>
		                            <div class="col-md-6">
		                                <input class="form-control" type="Number" min="1" max='10' id="Tavanz" onchange="porcenTemas()">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-md-6">PORCENTAJE:</label>
		                            <div class="col-md-6">
		                                <input class="form-control" type="text" min="1" id="Tporc" readonly="TRUE">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>                   
                    		</div>
                    		<div class="col-sm-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-6">INSCRITOS:</label>
		                            <div class="col-md-6">
		                                <input class="form-control" type="text" min="1" id="Tinscrit" readonly="TRUE">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>  
		                        <div class="form-group">
		                            <label class="control-label col-md-6">RETIRADOS:</label>
		                            <div class="col-md-6">
		                                <input class="form-control" type="Number" min="0" id="Tretir" value="0">
		                                <span class="help-block"></span>
		                            </div>
		                        </div> 
		                        <div class="form-group">
		                            <label class="control-label col-md-6">EFECTIVOS:</label>
		                            <div class="col-md-6">
		                                <input class="form-control" type="text" min="1" id="Tefect" readonly="TRUE">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>                   
                    		</div>
                    		<div class="col-sm-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-6">NUM REPRO:</label>
		                            <div class="col-md-6">
		                                <input class="form-control" type="text" min="1" id="Trepro" readonly="TRUE">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>  
		                        <div class="form-group">
		                            <label class="control-label col-md-6">NUM % REPROB:</label>
		                            <div class="col-md-6">
		                                <input class="form-control" type="Number" min="1" id="Tporrepro" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>   
                    		</div>
                    		<div class="col-sm-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-6">NUM APROB:</label>
		                            <div class="col-md-6">
		                                <input class="form-control" type="text" min="1" id="Taprob" readonly="TRUE">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>  
		                        <div class="form-group">
		                            <label class="control-label col-md-6">NUM % APROB:</label>
		                            <div class="col-md-6">
		                                <input class="form-control" type="Number" min="1" id="Tporaprob" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-md-6">PROMEDIO:</label>
		                            <div class="col-md-6">
		                                <input class="form-control bg-info" type="Number" min="1" id="Tpromedio" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>   
                    		</div>
                    	</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-brown-300">
            	<br>
                <button type="button" id="btnSave" onclick="save_estud()" class="btn bg-brown-700">Guardar</button>
                <button type="button" class="btn bg-danger-300" data-dismiss="modal">Cancelar</button>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->



<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form2" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-brown-300">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Formularios para registro de indicadores</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                    	<div class="row">
                    		<div class="col-sm-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-4">ID:</label>
		                            <div class="col-md-8">
		                                <input class="form-control" type="text" id="Kidindi" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>
		                        	                        
                    		</div>

                    		<div class="col-sm-3">
                    			<div class="form-group">
		                            <label class="control-label col-md-3">MAT:</label>
		                            <div class="col-md-8">
		                                <input class="form-control" type="text" id="Kidmat" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>

		                         	                        
                    		</div>

							<div class="col-sm-3">
	                        	<div class="form-group">
		                            <label class="control-label col-md-4">BIMES:</label>
		                            <div class="col-md-8">
		                            	<input type="text" id="Kbimestre" class="form-control" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>       	                          
	                    	</div>
                    		<div class="col-sm-3">
	                        	<div class="form-group">
		                            <label class="control-label col-md-4">GEST:</label>
		                            <div class="col-md-8">
		                            	<input type="text" id="Kgestion" class="form-control" readonly="true">
		                                <span class="help-block"></span>
		                            </div>
		                        </div>       	                          
	                    	</div>
                    	</div>
                    	
                    	
                     	<div class="row">
                    		<div class="col-sm-6">
                    			<h6 class="alpha-brown" style='color:brown;'>Indicadores de SABER</h6>
		                        <div class="form-group">	                    			
	                    			<label class="control-label col-lg-4">IND SAB1:</label>
									<div class="col-lg-8">
										<div class="input-group">
											<input type="text" class="form-control" id="inpsab1">
										</div>
									</div>
		                        </div>
		                        <div class="form-group">	                    			
	                    			<label class="control-label col-lg-4">IND SAB2:</label>
									<div class="col-lg-8">
										<div class="input-group">											
											<input type="text" class="form-control" id="inpsab2">
										</div>
									</div>
		                        </div>
		                        <div class="form-group">	                    			
	                    			<label class="control-label col-lg-4">IND SAB3:</label>
									<div class="col-lg-8">
										<div class="input-group">											
											<input type="text" class="form-control" id="inpsab3">
										</div>
									</div>
		                        </div>
		                        <div class="form-group">	                    			
	                    			<label class="control-label col-lg-4">IND SAB4:</label>
									<div class="col-lg-8">
										<div class="input-group">											
											<input type="text" class="form-control" id="inpsab4">
										</div>
									</div>
		                        </div>
		                        <div class="form-group">	                    			
	                    			<label class="control-label col-lg-4">IND SAB5:</label>
									<div class="col-lg-8">
										<div class="input-group">											
											<input type="text" class="form-control" id="inpsab5">
										</div>
									</div>
		                        </div>
		                        <div class="form-group">	                    			
	                    			<label class="control-label col-lg-4">IND SAB6:</label>
									<div class="col-lg-8">
										<div class="input-group">											
											<input type="text" class="form-control" id="inpsab6">
										</div>
									</div>
		                        </div>
                    		</div>
                    		<div class="col-sm-6">
                    			<h6 class="alpha-brown" style='color:brown;'>Indicadores del HACER</h6>                    			
		                        <div class="form-group">	                    			
	                    			<label class="control-label col-lg-4">IND HAC1:</label>
									<div class="col-lg-8">
										<div class="input-group">											
											<input type="text" class="form-control" id="inphac1">
										</div>
									</div>
		                        </div>
		                        <div class="form-group">	                    			
	                    			<label class="control-label col-lg-4">IND HAC2:</label>
									<div class="col-lg-8">
										<div class="input-group">											
											<input type="text" class="form-control" id="inphac2">
										</div>
									</div>
		                        </div>
		                        <div class="form-group">	                    			
	                    			<label class="control-label col-lg-4">IND HAC3:</label>
									<div class="col-lg-8">
										<div class="input-group">											
											<input type="text" class="form-control" id="inphac3">
										</div>
									</div>
		                        </div>
		                        <div class="form-group">	                    			
	                    			<label class="control-label col-lg-4">IND HAC4:</label>
									<div class="col-lg-8">
										<div class="input-group">											
											<input type="text" class="form-control" id="inphac4">
										</div>
									</div>
		                        </div>
		                        <div class="form-group">	                    			
	                    			<label class="control-label col-lg-4">IND HAC5:</label>
									<div class="col-lg-8">
										<div class="input-group">											
											<input type="text" class="form-control" id="inphac5">
										</div>
									</div>
		                        </div>
		                        <div class="form-group">	                    			
	                    			<label class="control-label col-lg-4">IND HAC6:</label>
									<div class="col-lg-8">
										<div class="input-group">											
											<input type="text" class="form-control" id="inphac6">
										</div>
									</div>
		                        </div>
		                                        
                    		</div>
                    		
                    	</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-brown-300">
            	<br>
                <button type="button" id="btnSave" onclick="save_indicad()" class="btn bg-brown-700">Guardar</button>
                <button type="button" class="btn bg-danger-300" data-dismiss="modal" onclick="resetIndi();">Cancelar</button>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

