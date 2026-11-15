<?php
	session_start();
	$nome = $_SESSION['nome_usuario'];
	$id   = $_SESSION['user_id'];
	$usuario = $_SESSION['user_nome'];
	$variavel = base64_encode("opc");
	$valor    = base64_encode("login");
	
	session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>SPC - Sistema de Pesquisa Clínica</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #3 for " name="description" />
        <meta content="" name="author" />
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/pages/css/lock.min.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="favicon.ico" /> </head>
    <body class="">
        <div class="page-lock">
            <div class="page-logo">
                <a class="brand" href="index.html">
                    <img src="assets/pages/img/logo-big.png" alt="logo" /> </a>
            </div>
			<form class="login-form" action="acao.php?<?=$variavel."=".$valor;?>" method="post">
				<div class="page-body">
					<div class="lock-head"> Sistema TRAVADO </div>
					<div class="lock-body">
						<div class="lock-cont">
							<div class="lock-item">
							</div>
							<div class="lock-item lock-item-full">
								<form class="lock-form pull-left" action="index.html" method="post">
									<h4><?=$nome;?></h4>
									<div class="form-group">
										<input class="form-control placeholder-no-fix" type="hidden" name="form_usuario" id="form_usuario" value="<?=$usuario;?>"/> </div>
										<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Senha" name="form_senha" id="form_senha" /> </div>
									<div class="form-actions">
										<button type="submit" class="btn red uppercase">Login</button>
									</div>
								</form>
							</div>
						</div>
					</div>                
				</div>
			</form>
        </div>
        <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/lock.min.js" type="text/javascript"></script>
        <script>
            $(document).ready(function()
            {
                $('#clickmewow').click(function()
                {
                    $('#radio1003').attr('checked', 'checked');
                });
            })
        </script>
    
</body>


</html>