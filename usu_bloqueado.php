<?php
	include_once "class/conexao.class.php";
    include_once "funcoes.php";
    
   session_start();
   $_SESSION['valido'] = 0;

	//echo $_SESSION['ult_pagina'];
   if($_POST['form_senha']!=""){
	    $query = "select * from usu_usuario where id = " . $_SESSION['user_id'] . " and senha = md5('".$_POST['form_senha']."')";
		$res_usu = $conexao->pesquisa($query);
	     //if(strtoupper($_POST['form_senha'])!=$row_usu['senha']){
		if(mysqli_num_rows($res_usu)==0){
			 $msg = "Senha inválida. Por favor, digite novamente sua senha";
		 }else{
			 $row_usu = mysqli_fetch_assoc($res_usu);
			 $_SESSION['valido'] = 1;
			 $query = "select * from usu_pagina_atual where sessao = " . $_SESSION['sessao'];
			 $res_pg = $conexao->pesquisa($query);
			 $row_pg = mysqli_fetch_assoc($res_pg);
			 $volta = "Location: ". $row_pg['pagina'];
			 header($volta);
			 die();
		 }
   }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?=$titulo;?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #3 for " name="description" />
        <meta content="" name="author" />
       <meta charset="utf-8" />
        <title><?=$titulo;?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #3 for " name="description" />
        <meta content="" name="author" />
        <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' rel='stylesheet'/>
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/layouts/layout3/css/custom.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/css/geral.css" rel="stylesheet" type="text/css" />
		<link rel="shortcut icon" href="favicon.ico" />
        <link href="assets/pages/css/lock.min.css" rel="stylesheet" type="text/css" />
		</head>
	<script>
		<?php
		if($msg!=""){echo "alert('".$msg."');";}
		?>
	</script>
    <body class="">
        <div class="page-lock">
            <div class="page-logo">

            </div>
            <div class="page-body">
                <div class="lock-head"> <?=traduzir("Sessão bloqueada");?> </div>
                <div class="lock-body">
                    <div class="lock-cont">
                        <div class="lock-item">
                            <div class="pull-left lock-avatar-block">
                                 </div>
                        </div>
                        <div class="lock-item lock-item-full">
                            <form class="lock-form pull-left" action="usu_bloqueado.php" method="post">
                                <h4><?=$_SESSION['nome_usuario'];?></h4>
                                <div class="form-group">
                                    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="<?=traduzir("Senha");?>" name="form_senha" id="form_senha"> </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn red uppercase"><?=traduzir("Entrar");?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="lock-bottom">
                    <a href="usu_logoff.php"><?=traduzir("Não é ");?><b><?=$_SESSION['nome_usuario'];?></b>?</a>
                </div>
            </div>
        </div>
        <script src="../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <script src="../assets/global/scripts/app.min.js" type="text/javascript"></script>
        <script src="../assets/pages/scripts/lock.min.js" type="text/javascript"></script>
</body>


</html>