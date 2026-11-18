<?php
	if(!isset($_SESSION)){  session_start();    }
	if($_SESSION['valido']<1){echo "<script>window.location.replace('expirado.php');</script>";};


	include_once "class/conexao.class.php";
	include_once __DIR__."/funcoes.php";
	include_once "inicializa.php";

	$conexao = new Conexao();


	$pagina_atual = basename($_SERVER['PHP_SELF']);

	//Verificar se o tipo de usuário pode acessar a página em questão
	$PAGacessa = "";
	$PAGedita  = "";
	$PAGexclui = "";
	$query_seg = "select * from vw_pagina_grupo where pagina = '$pagina_atual' and grupo_id = " . $_SESSION['user_tipo'];
	$res_seg   = $conexao->pesquisa($query_seg);
	if(mysqli_num_rows($res_seg)>0){
		$row_seg = mysqli_fetch_assoc($res_seg);
		$PAGacessa = $row_seg['acessa'];
		$PAGedita  = $row_seg['edita'];
		//$PAGexclui = $row_seg['exclui'];
		$_SESSION['seg_acessa'] = $row_seg['acessa'];
		$_SESSION['seg_edita'] = $row_seg['edita'];
		//$_SESSION['seg_exclui'] = $row_seg['exclui'];
	}

	$notAllowedCommands = array(
		'DELETE',
		'TRUNCATE',
		'DROP',
		'USE'
	);
	//Loop em todas as variáveis vindas de _GET para verificar se não tem nenhum SQL junto
	foreach ($_GET as $key => $value) {
		if(preg_match('[' . implode(' |', $notAllowedCommands ) . ']i', $value) == true) {
			//echo "VALOR -> " . $value;
			//echo "<script>window.location.replace('usu_logoff.php');</script>";
		}
	}

?>