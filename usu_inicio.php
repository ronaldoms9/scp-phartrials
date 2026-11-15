<?php
	session_start();
	include_once "class/conexao.class.php";
	include "funcoes.php";

	$lang = $_SESSION['lang'];
	$variavel = base64_encode("opc");
	$valor    = base64_encode("login_arquivo");
	?>
	<html lang="en">
		<head>
			<meta charset="utf-8" />
			<title>SPC - Sistema de Pesquisa Clínicaa</title>
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta content="width=device-width, initial-scale=1" name="viewport" />
			<meta content="Preview page of Metronic Admin Theme #3 for " name="description" />
			<meta content="" name="author" />
			<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
			<link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
			<link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
			<link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
			<link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
			<link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
			<link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
			<link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
			<link href="assets/pages/css/login.min.css" rel="stylesheet" type="text/css" />
			<link rel="shortcut icon" href="favicon.ico" />
		</head>
		<body class=" login">
			<div class="logo">
				<a href="index.html"><img src="logo_spc_escuro.png" alt="" /> </a>
			</div>
			<div class="content">
				<form autocomplete="luisbueno@qsbsoft.com.br"
					name="formulario" id="formulario" class="login-form" action="act_login.php?<?=$variavel."=".$valor;?>&target=<?=$_GET['target'];?>" method="post">
					<h3 class="form-title font-green"><?=traduzir("Acesso ao sistema", $_SESSION['lang']);?></h3>
					<div class="alert alert-danger display-hide">
						<button class="close" data-close="alert"></button>
							<span><?=traduzir("Digite o nome de usuário e a senha", $_SESSION['lang']);?>. </span>
					</div>
					<div class="form-group">
						<label class="control-label visible-ie8 visible-ie9">Username</label>
						<input class="form-control form-control-solid placeholder-no-fix"
							type="text" autocomplete="off" placeholder="<?=traduzir("Nome de usuário", $_SESSION['lang']);?>"
							name="form_usuario" id="form_usuario">
					</div>
					<div class="form-group">
						<label class="control-label visible-ie8 visible-ie9">Password</label>
						<input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="<?=traduzir("Senha", $_SESSION['lang']);?>" name="form_senha" id="form_senha">
					</div>
					<div class="form-actions">
						<button type="button" onclick="valida();" class="btn green uppercase">Login</button>
						<a href="javascript:;" id="forget-password" class="forget-password"><?=traduzir("Esqueci minha senha", $_SESSION['lang']);?></a>
						<br><br>
					</div>
					<div class="create-account">
						<p>
							<a href="javascript:;" id="register-btn" class="uppercase"><?=traduzir("Solicitar acesso ao Sistema", $_SESSION['lang']);?></a>
						</p>
					</div>
				</form>
			</div>
			<div class="copyright">
				<?=date("Y");?> © <?=traduzir("SPC - Sistema de Pesquisa Clínica.", $_SESSION['lang']);?>
			</div>
			<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
			<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
			<script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
			<script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
			<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
			<script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
			<script src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
			<script src="assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
			<script src="assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
			<script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
			<script src="assets/pages/scripts/login.min.js" type="text/javascript"></script>
			<script src="js/loadingoverlay.min.js" type="text/javascript"></script>
			<script>
				function mostraCarregando(){$.LoadingOverlay("show");}
				function removeCarregando(){$.LoadingOverlay("hide");}
				function valida(){
					var ok = true;
					var usuario = document.getElementById("form_usuario").value;
					if(usuario==""){
						alert("Campo usuário não preenchido. Por favor, revise as informações");
						ok=false;
						return;
					}
					var senha = document.getElementById("form_senha").value;
					if(senha==""){
						alert("Campo senha não preenchido. Por favor, revise as informações");
						ok=false;
						return;
					}
					mostraCarregando();
					document.formulario.submit();
				}
			</script>
			<?php
				if(isset($_GET['erro'])){
				if($_GET['erro']>0){
			?>
				<script>
					erro = <?=$_GET['erro'];?>;
					if(erro=="1"){
						alert("<?=traduzir("Usuário inválido. Por favor, reveja o nome de usuário e tente novamente", $_SESSION['lang']);?>!");
					}
					if(erro=="2"){
						alert("<?=traduzir("Senha inválida. Por favor, reveja a senha e tente novamente", $_SESSION['lang']);?>!");
					}
					if(erro=="5"){
						alert("<?=traduzir("Email inválido. Email informado inválido", $_SESSION['lang']);?>!");
					}
					if(erro=="6"){
						alert("<?=traduzir("Senha resetada. Uma nova senha foi enviada a seu email Por favor, confira", $_SESSION['lang']);?>!");
					}
					if(erro=="8"){
						alert("<?=traduzir("Solicitação de acesso recebida com sucesso. Em breve seu acesso será enviado para seu email.", $_SESSION['lang']);?>!");
					}
				</script>
				<?php
				}
				}
			?>
		</body>
	</html>