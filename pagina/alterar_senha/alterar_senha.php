<?php
	 header('Content-Type:text/html; charset=ISO-8859-1');
?>
<html lang="en">
<head>
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<body>
	<div class="container">
		<br><br>
		<div class="row">
			<div class="col-sm-2"></div>
			<div class="col-sm-6">
				<p class="text-center">Preencha os dados abaixo para trocar sua senha.</p>
				<br>
				<form method="post" id="passwordForm">
					<input
						type="password"
						class="input-lg form-control"
						name="password1"
						id="password1"
						placeholder="Senha"
						autocomplete="off"
					>
					<div class="row">
						<div class="col-sm-6">
							<span id="8char" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> 8 Caracteres<br>
							<span id="ucase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Uma letra MAIÚSCULA
						</div>
						<div class="col-sm-6">
							<span id="lcase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Uma letra MINÚSCULA<br>
							<span id="num" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Um número
						</div>
					</div>
					<br>
					<input
						type="password"
						class="input-lg form-control"
						name="password2"
						id="password2"
						placeholder="Repita a senha"
						autocomplete="off"
					>
					<div class="row">
						<div class="col-sm-12">
							<span id="pwmatch" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Senhas coincidem
						</div>
					</div>
					<br><br>
					<input
						type="button"
						class="col-xs-12 btn btn-primary btn-load btn-lg"
						data-loading-text="Mudando..."
						value="Mudar Senha"
						onclick="mudar();"
						data-dismiss="modal"
						>
				</form>
			</div><!--/col-sm-6-->
			<div class="col-sm-2"></div>
		</div><!--/row-->
	</div>
	<br><br>
</body>
<script>
	$("input[type=password]").keyup(function(){
		var ucase = new RegExp("[A-Z]+");
		var lcase = new RegExp("[a-z]+");
		var num = new RegExp("[0-9]+");

		if($("#password1").val().length >= 8){
			$("#8char").removeClass("glyphicon-remove");
			$("#8char").addClass("glyphicon-ok");
			$("#8char").css("color","#00A41E");
		}else{
			$("#8char").removeClass("glyphicon-ok");
			$("#8char").addClass("glyphicon-remove");
			$("#8char").css("color","#FF0004");
		}

		if(ucase.test($("#password1").val())){
			$("#ucase").removeClass("glyphicon-remove");
			$("#ucase").addClass("glyphicon-ok");
			$("#ucase").css("color","#00A41E");
		}else{
			$("#ucase").removeClass("glyphicon-ok");
			$("#ucase").addClass("glyphicon-remove");
			$("#ucase").css("color","#FF0004");
		}

		if(lcase.test($("#password1").val())){
			$("#lcase").removeClass("glyphicon-remove");
			$("#lcase").addClass("glyphicon-ok");
			$("#lcase").css("color","#00A41E");
		}else{
			$("#lcase").removeClass("glyphicon-ok");
			$("#lcase").addClass("glyphicon-remove");
			$("#lcase").css("color","#FF0004");
		}

		if(num.test($("#password1").val())){
			$("#num").removeClass("glyphicon-remove");
			$("#num").addClass("glyphicon-ok");
			$("#num").css("color","#00A41E");
		}else{
			$("#num").removeClass("glyphicon-ok");
			$("#num").addClass("glyphicon-remove");
			$("#num").css("color","#FF0004");
		}

		if( ($("#password1").val() == $("#password2").val()) && ($("#password1").val()!="")) {
			$("#pwmatch").removeClass("glyphicon-remove");
			$("#pwmatch").addClass("glyphicon-ok");
			$("#pwmatch").css("color","#00A41E");
		}else{
			$("#pwmatch").removeClass("glyphicon-ok");
			$("#pwmatch").addClass("glyphicon-remove");
			$("#pwmatch").css("color","#FF0004");
		}
	});
	function mudar(){
		var baseUrl = "../../php_php/php_usu_usuarios.php?";
		var ucase = new RegExp("[A-Z]+");
		var lcase = new RegExp("[a-z]+");
		var num = new RegExp("[0-9]+");

		var seguir = true;
		if($("#password1").val().length < 8){alert("Comprimento da senha tem que ter ao menos 8 Caracteres. Operação abortada");seguir=false;return;}
		if(!ucase.test($("#password1").val())){alert("A senha precisa ter ao menos um caracter em Maiúsculo. Operação abortada");seguir=false;return;}
		if(!lcase.test($("#password1").val())){alert("A senha precisa ter ao menos um caracter em Minúsculo. Operação abortada");seguir=false;return;}
		if(!num.test($("#password1").val())){alert("A senha precisa ter ao menos um caracter Numérico. Operação abortada");seguir=false;return;}
		if($("#password1").val() != $("#password2").val()){alert("A senha digitada não confere. Por favor, revise as informações");seguir=false;return;}
		if(seguir==true){

		varUrl = baseUrl + "act=ALTERA";
		var formData = {senha: $("#password1").val()}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : true,
			success: function(response, textStatus, jqXHR) {
				alert("Senha alterada com sucesso!");
			},
			error: function (jqXHR, textStatus, errorThrown) {
			}
		});
		}
	}
</script>
</html>