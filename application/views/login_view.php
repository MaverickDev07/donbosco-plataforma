<body class="login-container login-cover">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300&display=swap" rel="stylesheet">
	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Content area -->
				<div class="content pb-20">

					<!-- Advanced login -->
					<form id="login">
						<div class="panel panel-body login-form">
							<div class="text-center">
								<h5 class="content-group text-blue-700">Acceso Admin<small class="display-block">Ingreso al Sistema</small></h5>
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

							<div class="form-group">
								<button type="submit" class="btn bg-blue-700 btn-block">Ingresar <i class="icon-circle-right2 position-right"></i></button>
							</div>
						</div>
					</form>

					<!--<div class="newapp">
						<a href="http://181.115.156.38/donboscoapp/">
							<div class="newapp--card">
								Sistema Academico
							</div>
							<div class="newapp--card-bubble">
								2022
							</div>
						</a>
					</div>
					<br>-->

					<div class="newapp">
						<a href="http://216.238.80.194:3000">
							<div class="newapp--card">
								Sistema Académico 2023
							</div>
							<div class="newapp--card-bubble">
								2023
							</div>
						</a>
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
		$(function() {
			$('#login').on('submit', function(event) {
				event.preventDefault();
				validar();
			});
		});

		function validar() {
			var us = document.getElementById('us').value;
			var cl = document.getElementById('cl').value;

			var data1 = {
				"us": us,
				"cl": cl,
			};

			//alert(data1.cl);

			var url = "<?php echo site_url('Login/ajax_validacion'); ?>";

			$.ajax({
				url: url,
				type: "POST",
				data: data1,
				dataType: "JSON",
				success: function(data) {
					if (!data.login) {
						alert("Login no aceptado, o Usuario no Activo, Comuniquese con el Administrador!!");
					} else {
						alert("Bienvenido al sistema " + data.name);

						var enlace = "<?php echo site_url('Principal'); ?>";
						window.location.replace(enlace);
					}

				},
				error: function(jqXHR, textStatus, errorThrown) {
					alert('Error al recibir los datos');
				}
			});
		}
	</script>

</body>
