<body class="login-container login-cover">


	<!-- Page container -->
	<div class="page-container">
 
		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Content area -->
				<div class="content pb-20">

					<!-- Advanced login --> 
						<div class="panel panel-body login-form">
							<div class="text-center">
								<div class="icon-object border-blue-700 text-blue"><img src='<?php echo base_url()?>assets/images/logo.jpg' width='90' height='100'></div>
								<h5 class="content-group text-blue-700">SISTEMA DE  CONTROL ACADEMICO DE "DON BOSCO" <small class="display-block">Padres de Familias</small></h5>
							</div>

							<div class="form-group has-feedback has-feedback-left">
								<input type="text" class="form-control" placeholder="Usuario" id='us'>
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
							</div>

							<div class="form-group has-feedback has-feedback-left">
								<input type="password" class="form-control" placeholder="Contraseña" id='cl'>
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
							</div>
							<input type="radio" id="inscrip" name="inscrip" value="Madre"> Madre
							<input type="radio" id="inscrip" name="inscrip" value="Padre" > Padre
							<input type="radio" id="inscrip" name="inscrip" value="Tutor"> Tutor 
							<div class="form-group">
								<button type="submit" class="btn bg-blue-700 btn-block" onclick="validar()">Ingresar <i class="icon-circle-right2 position-right"></i></button>
							</div>
						</div>
					
					<!-- /advanced login -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

	<script type="text/javascript">

		function validar()
		{
			var us=document.getElementById('us').value;
			var cl=document.getElementById('cl').value;
			var inscrip=document.getElementsByName('inscrip');
		    // for(i=0;i<inscrip.length;i++){
		    //     if (inscrip[i].checked==true) {
		    //         inscrip = inscrip[i].value;
		    //         return;
		    //     }  
		    // }
		    if(inscrip[0].checked==true){
		    	inscrip=inscrip[0].value;
		    }
		    if(inscrip[1].checked==true){
		    	inscrip=inscrip[1].value;
		    }
		    if(inscrip[2].checked==true){
		    	inscrip=inscrip[2].value;
		    }

			// alert(inscrip);
			var data1={
				"us":us,
				"cl":cl,
				"inscrip":inscrip,
			};

			//alert(data1.cl);

			var url="<?php echo site_url('LoginP/ajax_validacion');?>";

			$.ajax({
	            url : url,
	            type: "POST",
	            data:data1,
	            dataType: "JSON",
	            success: function(data)
	            {
	                if(!data.login){
	                	alert("Login no aceptado, o Usuario no Activo, Comuniquese con el Administrador!!");
	                }
	                else
	                {
	                	alert("Bienvenido al sistema "+data.nameP);
	                	var enlace="<?php echo site_url('Control_estudiante_contr');?>";
	                	window.location.replace(enlace);	                	
	                }
	               
	            },
	            error: function (jqXHR, textStatus, errorThrown)
	            {
	                alert('Error al recibir los datos');
	            }
        	});
		}
		

	</script>

</body>