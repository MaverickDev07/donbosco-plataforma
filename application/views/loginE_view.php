<head>
	<link href="<?php echo base_url() ?>assets/css/login.css?v=1.1" rel="stylesheet" type="text/css">
</head>

<body>
	<main class="login--nuevo">
		<div class="login--background">
			<div class="login--ball-wrapper">
				<div class="login--ball login--ball-yellow"></div>
				<div class="login--ball login--ball-blue"></div>
			</div>
		</div>
		<!-- Login Content -->
		<div class="login--wrapper" id="login">
			<div class="login--box">
				<img src="<?php echo base_url() ?>assets/images/modern/logo-min-min.png" alt="Logo del colegio" class="login--image">
				<div class="login--title">
					<svg width="322" height="46" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 322 46">
						<path fill="#000" fill-rule="nonzero" d="M20.09 45c4.08 0 7.43-1.11 10.05-3.33 2.62-2.22 3.93-4.99 3.93-8.31 0-2.4-.68-4.52-2.04-6.36-1.36-1.84-3.24-3.16-5.64-3.96 1.84-.96 3.27-2.25 4.29-3.87s1.53-3.43 1.53-5.43C32.21 10.58 31 8 28.58 6s-5.61-3-9.57-3H.17v42h19.92zm-3.06-24.9H9.35V10.08h7.68c1.84 0 3.3.48 4.38 1.44s1.62 2.16 1.62 3.6c0 1.4-.54 2.58-1.62 3.54s-2.54 1.44-4.38 1.44zm1.38 17.88H9.35V26.7h9.06c2 0 3.59.53 4.77 1.59 1.18 1.06 1.77 2.39 1.77 3.99s-.59 2.95-1.77 4.05c-1.18 1.1-2.77 1.65-4.77 1.65zm26.64-27.12c1.6 0 2.92-.51 3.96-1.53s1.56-2.31 1.56-3.87c0-1.52-.52-2.79-1.56-3.81S46.65.12 45.05.12s-2.92.52-3.96 1.56c-1 1-1.5 2.26-1.5 3.78 0 1.56.51 2.85 1.53 3.87s2.33 1.53 3.93 1.53zM49.49 45V14.04h-8.82V45h8.82zm22.62.6c1.84 0 3.58-.22 5.22-.66 1.64-.44 2.95-.94 3.93-1.5s1.88-1.2 2.7-1.92c.82-.72 1.34-1.22 1.56-1.5.22-.28.39-.5.51-.66l-5.16-4.98-.84.9c-.56.64-1.52 1.27-2.88 1.89-1.36.62-2.84.93-4.44.93-2.28 0-4.18-.63-5.7-1.89-1.52-1.26-2.44-2.93-2.76-5.01h22.92l.12-.36c.08-.2.16-.54.24-1.02.08-.48.12-.98.12-1.5 0-4.08-1.5-7.58-4.5-10.5s-6.64-4.38-10.92-4.38c-4.72 0-8.69 1.56-11.91 4.68-3.22 3.12-4.83 6.96-4.83 11.52s1.57 8.36 4.71 11.4c3.14 3.04 7.11 4.56 11.91 4.56zm7.14-19.74H64.43c.36-1.8 1.24-3.26 2.64-4.38 1.4-1.12 3.06-1.68 4.98-1.68 1.8 0 3.38.58 4.74 1.74 1.36 1.16 2.18 2.6 2.46 4.32zM102.05 45V29.22c0-2.64.61-4.6 1.83-5.88 1.22-1.28 2.81-1.92 4.77-1.92 1.8 0 3.25.63 4.35 1.89 1.1 1.26 1.65 3.13 1.65 5.61V45h8.7V26.16c0-4-1.09-7.12-3.27-9.36-2.18-2.24-4.97-3.36-8.37-3.36-4.6 0-8 1.78-10.2 5.34l-.42-4.74h-7.74V45h8.7zm44.22 0l12.3-30.96h-9.18l-7.14 19.62-7.14-19.62h-9.66L137.81 45h8.46zm29.52.6c1.84 0 3.58-.22 5.22-.66 1.64-.44 2.95-.94 3.93-1.5s1.88-1.2 2.7-1.92c.82-.72 1.34-1.22 1.56-1.5.22-.28.39-.5.51-.66l-5.16-4.98-.84.9c-.56.64-1.52 1.27-2.88 1.89-1.36.62-2.84.93-4.44.93-2.28 0-4.18-.63-5.7-1.89-1.52-1.26-2.44-2.93-2.76-5.01h22.92l.12-.36c.08-.2.16-.54.24-1.02.08-.48.12-.98.12-1.5 0-4.08-1.5-7.58-4.5-10.5s-6.64-4.38-10.92-4.38c-4.72 0-8.69 1.56-11.91 4.68-3.22 3.12-4.83 6.96-4.83 11.52s1.57 8.36 4.71 11.4c3.14 3.04 7.11 4.56 11.91 4.56zm7.14-19.74h-14.82c.36-1.8 1.24-3.26 2.64-4.38 1.4-1.12 3.06-1.68 4.98-1.68 1.8 0 3.38.58 4.74 1.74 1.36 1.16 2.18 2.6 2.46 4.32zM205.73 45V29.22c0-2.64.61-4.6 1.83-5.88 1.22-1.28 2.81-1.92 4.77-1.92 1.8 0 3.25.63 4.35 1.89 1.1 1.26 1.65 3.13 1.65 5.61V45h8.7V26.16c0-4-1.09-7.12-3.27-9.36-2.18-2.24-4.97-3.36-8.37-3.36-4.6 0-8 1.78-10.2 5.34l-.42-4.74h-7.74V45h8.7zm32.88-34.14c1.6 0 2.92-.51 3.96-1.53s1.56-2.31 1.56-3.87c0-1.52-.52-2.79-1.56-3.81S240.21.12 238.61.12s-2.92.52-3.96 1.56c-1 1-1.5 2.26-1.5 3.78 0 1.56.51 2.85 1.53 3.87s2.33 1.53 3.93 1.53zM243.05 45V14.04h-8.82V45h8.82zm20.94.6c4.32 0 7.76-1.74 10.32-5.22l.72 4.62h7.26V3h-8.7v14.58c-2.32-2.76-5.42-4.14-9.3-4.14-4.16 0-7.74 1.57-10.74 4.71-3 3.14-4.5 6.97-4.5 11.49 0 4.48 1.46 8.26 4.38 11.34s6.44 4.62 10.56 4.62zm1.8-7.98c-2.28 0-4.19-.78-5.73-2.34-1.54-1.56-2.31-3.48-2.31-5.76s.77-4.2 2.31-5.76c1.54-1.56 3.45-2.34 5.73-2.34 2.32 0 4.25.78 5.79 2.34 1.54 1.56 2.31 3.48 2.31 5.76s-.77 4.2-2.31 5.76c-1.54 1.56-3.47 2.34-5.79 2.34zm38.82 7.98c4.8 0 8.82-1.57 12.06-4.71 3.24-3.14 4.86-6.99 4.86-11.55 0-4.56-1.57-8.35-4.71-11.37-3.14-3.02-7.11-4.53-11.91-4.53s-8.82 1.56-12.06 4.68c-3.24 3.12-4.86 6.96-4.86 11.52s1.57 8.36 4.71 11.4c3.14 3.04 7.11 4.56 11.91 4.56zm.12-7.98c-2.28 0-4.19-.78-5.73-2.34-1.54-1.56-2.31-3.48-2.31-5.76s.77-4.2 2.31-5.76c1.54-1.56 3.45-2.34 5.73-2.34 2.32 0 4.25.78 5.79 2.34 1.54 1.56 2.31 3.48 2.31 5.76s-.77 4.2-2.31 5.76c-1.54 1.56-3.47 2.34-5.79 2.34z" />
					</svg>
				</div>
				<div class="login--subtitle">Plataforma de Estudiantes</div>
				<!-- Formulario de envio -->
				<form onsubmit="validar(event);" class="login--form">
					<div class="login--input">
						<input type="password" placeholder="Contraseña" id="cl">
						<div class="login--input-icon">
							<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24">
								<path d="M0 0h24v24H0z" fill="none" />
								<path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" fill="#0091ff" />
							</svg>
						</div>
					</div>
					<button type="submit" class="login--button" id="submitButton">
						<div id="submitClick" class="login--submit login--submit-click">
							INGRESAR
							<div>
								<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24">
									<path fill="none" d="M0 0h24v24H0z" />
									<path fill="#fff" d="M15 5l-1.41 1.41L18.17 11H2v2h16.17l-4.59 4.59L15 19l7-7-7-7z" />
								</svg>
							</div>
						</div>
						<div id="submitLoading" class="login--submit login--submit-loading">
							<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24">
								<path fill="none" d="M0 0h24v24H0z" />
								<path fill="#000" d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z" />
							</svg>
						</div>
					</button>
				</form>

				<div class="login--author">
					<div class="login--author-black">Don Bosco</div>
					<div class="login--author-light">&nbsp;Sucre</div>
				</div>
				<div class="login--copy">
					© 2022 All Rights Reserved
				</div>
				<br>
				<br>
				<div class="newapp">
					<a href="http://216.238.80.194:3000">
						<div class="newapp--card">
							Sistema de Inscripcion
						</div>
						<div class="newapp--card-bubble">
							2023
						</div>
					</a>
				</div>
			</div>
		</div>
		<!-- Welcome Content -->
		<div class="login--wrapper" id="welcome">
			<div class="login--box">
				<img src="<?php echo base_url() ?>assets/images/modern/emoji-hi-min.png" alt="Logo del colegio" class="login--emoji">
				<div class="login--title">
					<svg width="163" height="43" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 43">
						<path d="M9.95 42V24.3h18.3V42h9.12V0h-9.12v16.14H9.95V0H.77v42h9.18zm50.94.6c4.8 0 8.82-1.57 12.06-4.71 3.24-3.14 4.86-6.99 4.86-11.55 0-4.56-1.57-8.35-4.71-11.37-3.14-3.02-7.11-4.53-11.91-4.53S52.37 12 49.13 15.12c-3.24 3.12-4.86 6.96-4.86 11.52s1.57 8.36 4.71 11.4c3.14 3.04 7.11 4.56 11.91 4.56zm.12-7.98c-2.28 0-4.19-.78-5.73-2.34-1.54-1.56-2.31-3.48-2.31-5.76s.77-4.2 2.31-5.76c1.54-1.56 3.45-2.34 5.73-2.34 2.32 0 4.25.78 5.79 2.34 1.54 1.56 2.31 3.48 2.31 5.76s-.77 4.2-2.31 5.76c-1.54 1.56-3.47 2.34-5.79 2.34zM92.63 42V0h-8.82v42h8.82zm17.88.6c3.96 0 7.12-1.08 9.48-3.24l.66 2.64h6.78V24.48c0-4.4-1.25-7.84-3.75-10.32-2.5-2.48-5.93-3.72-10.29-3.72-2.48 0-4.84.38-7.08 1.14-2.24.76-3.69 1.36-4.35 1.8-.66.44-1.17.82-1.53 1.14l3.54 5.58.84-.6c.6-.4 1.54-.8 2.82-1.2 1.28-.4 2.62-.6 4.02-.6 2.12 0 3.83.58 5.13 1.74 1.3 1.16 1.95 2.8 1.95 4.92v1.32c-2.12-1.48-4.76-2.22-7.92-2.22-3.64 0-6.58.92-8.82 2.76-2.24 1.84-3.36 4.16-3.36 6.96s1.07 5.07 3.21 6.81c2.14 1.74 5.03 2.61 8.67 2.61zm2.58-5.58c-1.76 0-3.16-.39-4.2-1.17-1.04-.78-1.56-1.79-1.56-3.03 0-1.2.53-2.2 1.59-3 1.06-.8 2.45-1.2 4.17-1.2 1.8 0 3.24.4 4.32 1.2 1.08.8 1.62 1.8 1.62 3 0 1.24-.53 2.25-1.59 3.03-1.06.78-2.51 1.17-4.35 1.17zm26.1 5.58c1.52 0 2.81-.53 3.87-1.59s1.59-2.35 1.59-3.87c0-1.48-.54-2.76-1.62-3.84s-2.36-1.62-3.84-1.62c-1.52 0-2.81.53-3.87 1.59s-1.59 2.35-1.59 3.87.53 2.81 1.59 3.87 2.35 1.59 3.87 1.59zm21.48-14.82L162.65 0h-10.62l1.98 27.78h6.66zm-3.3 14.82c1.52 0 2.81-.54 3.87-1.62s1.59-2.36 1.59-3.84c0-1.52-.53-2.81-1.59-3.87s-2.35-1.59-3.87-1.59-2.82.54-3.9 1.62c-1.08 1.04-1.62 2.32-1.62 3.84s.54 2.81 1.62 3.87 2.38 1.59 3.9 1.59z" fill="#000" fill-rule="nonzero" />
					</svg>
				</div>
				<div class="login--estudiante" id="estudiante">
					Don Bosco
				</div>
			</div>
		</div>
	</main>
	<script type="text/javascript">
		function validar(e) {
			e.preventDefault();
			var cl = document.getElementById('cl').value;
			var data1 = {
				"cl": cl,
			};

			var url = "<?php echo site_url('LoginE/ajax_validacion'); ?>";
			/* Iniciamos el spinner */
			document.querySelector("#submitButton").disabled = true;
			document.querySelector("#submitClick").style.display = "none";
			document.querySelector("#submitLoading").style.display = "flex";

			$.ajax({
				url: url,
				type: "POST",
				data: data1,
				dataType: "JSON",
				success: function(data) {
					console.log('LOGIN ESTUDIANTE: ', data)
					if (!data.login) {
						alert("Login no aceptado, o Usuario no Activo, Comuniquese con el Administrador!!");
					} else {
						if (data.name === "") {
							document.querySelector("#submitButton").disabled = false;
							document.querySelector("#submitClick").style.display = "flex";
							document.querySelector("#submitLoading").style.display = "none";
							document.querySelector("#cl").value = "";
							document.querySelector("#cl").focus();
							alert('Error al recibir los datos');
						} else {
							document.querySelector("#login").style.display = "none";
							document.querySelector("#welcome").style.display = "flex";
							document.querySelector("#estudiante").innerHTML = data.name;
							if (data.pre == 1) {
								var enlace = "<?php echo site_url('inscrip_est_contr/inscrip_alumn/"+data.id+"'); ?>";
							} else {
								if (data.inscrito == 1 && data.gestion == 2023) {
									var enlace = "<?php echo site_url('Estudiantes_su_contr'); ?>";
								} else {
									// var enlace="<?php //echo site_url('Est_Inscrip_contr/inscrip_alumn/"+data.id+"');
																	?>";
									var enlace = "<?php echo site_url('inscrip_est_contr/inscrip_alumn/"+data.id+"'); ?>";
								}
							}
						}
						setTimeout(() => {
							window.location.replace(enlace);
						}, 1500);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					document.querySelector("#submitButton").disabled = false;
					document.querySelector("#submitClick").style.display = "flex";
					document.querySelector("#submitLoading").style.display = "none";
					document.querySelector("#cl").value = "";
					document.querySelector("#cl").focus();
					console.error(jqXHR, textStatus, errorThrown)
					alert('Error al recibir los datos');
				}
			});
		}
	</script>

</body>